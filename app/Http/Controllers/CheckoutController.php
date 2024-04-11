<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PayMongo;
use App\Models\Order;
use App\Models\Cart;
use App\Models\UserAddress;
use App\Models\DeliveryArea;
use Auth;
use DB;
use Session;

class CheckoutController extends Controller
{
    private $authorization = 'Basic c2tfdGVzdF9RQ3NHc3h3Z0JBRWZrZ0tYQzE0NkdaeVA6cGtfdGVzdF8yaXd1bzkyeXJYUFVUbXlwZGFCbmphY2E=';

    public function index() {
        $cms = DB::table('cms')->where('id', 1)->first();
        Session::put('cms_name', $cms->name);
        Session::put('cms_theme_color', $cms->theme_color);
        Session::put('cms_undraw_img', $cms->undraw_img);
        $charge = 0.00;
        $subtotal = $this->cartTotal();
        $productdetails = $this->cart();
        return view('checkout', compact('charge', 'subtotal'));
    }

    public function cartTotal(){
        return Cart::where('user_id', Auth::id())->sum('amount');
    }

    public function cart(){
       // return Cart::where('user_id', Auth::id())->get();
        return DB::table('cart AS BR')
        ->select('BR.*', 'product.*','product.id as product_id')
        ->leftJoin('product', 'BR.product_code', '=', 'product.id')
        ->where('BR.user_id',Auth::id())
        ->get();
    }

    public function placeOrderCard(Cart $cart) {
        $cart = $cart->readCart();
        $user_id = Auth::id();
        $order_no = $this->generateOrderNumber();
        $payment_method = request()->payment_method;
        if ($cart) {
            $total = 0;
            foreach ($cart as $item) {
                Order::create([
                    'user_id' => $user_id,
                    'product_code' => $item->product_code,
                    'order_no' => $order_no,
                    'qty' => $item->qty,
                    'pre_order' => $item->pre_order,
                    'status' => 1,
                    'amount' => $item->amount,
                    'payment_method' => 'Card'
                ]);
                $total = $total + $item->amount;
                
                if($item->pre_order != 1){
                    $this->updateInventory($item->product_code, $item->qty);
                }
                else{
                    DB::table('orders')
                    ->where('order_no', $order_no)
                    ->update([
                        'status' => 6,
                        'pre_order' => 1
                    ]);
                }
                
            }
            Cart::truncate();

            $order = new Order();
            $cms = DB::table('cms')->where('id', 1)->first();
            Session::put('cms_name', $cms->name);
            Session::put('cms_theme_color', $cms->theme_color);
            Session::put('cms_undraw_img', $cms->undraw_img);
            $orders = $order->readOrders($user_id);


            return redirect('/order-info/'. $order_no.'/card')->with('success', 'Order received, your payment ₱'.($total).' via Card was successful!');

            //return view('orders', compact('orders'));

        }
    }

    public function placeOrderGcash(Cart $cart) {
        $cart = $cart->readCart();
        $user_id = Auth::id();
        $order_no = $this->generateOrderNumber();
        $payment_method = request()->payment_method;
        if ($cart) {
            $total = 0;
            foreach ($cart as $item) {
                Order::create([
                    'user_id' => $user_id,
                    'product_code' => $item->product_code,
                    'order_no' => $order_no,
                    'qty' => $item->qty,
                    'amount' => $item->amount,
                    'pre_order' => $item->pre_order,
                    'status' => 1,
                    'payment_method' => 'GCash'
                ]);
                $total = $total + $item->amount;

                if($item->pre_order != 1){
                    $this->updateInventory($item->product_code, $item->qty);
                }
                else{
                    DB::table('orders')
                    ->where('order_no', $order_no)
                    ->update([
                        'status' => 6,
                        'pre_order' => 1
                    ]);
                }
                
            }
            Cart::truncate();

            $order = new Order();
            $cms = DB::table('cms')->where('id', 1)->first();
            Session::put('cms_name', $cms->name);
            Session::put('cms_theme_color', $cms->theme_color);
            Session::put('cms_undraw_img', $cms->undraw_img);
            $orders = $order->readOrders($user_id);

            $source_id = session()->get('source_id');
            $amount = session()->get('amount');
            $source =  $this->retrieveSource2($source_id);

            return redirect('/order-info/'.$source->data->id.'/gcash')->with('success', 'Order received, your payment ₱'.($amount/100).' via GCash was successful!');

        }
    }

    public function placeOrderPaymaya(Cart $cart) {
        $cart = $cart->readCart();
        $user_id = Auth::id();
        $order_no = $this->generateOrderNumber();
        $payment_method = request()->payment_method;
        if ($cart) {
            $total = 0;
            foreach ($cart as $item) {
                Order::create([
                    'user_id' => $user_id,
                    'product_code' => $item->product_code,
                    'order_no' => $order_no,
                    'qty' => $item->qty,
                    'amount' => $item->amount,
                    'pre_order' => $item->pre_order,
                    'status' => 1,
                    'payment_method' => 'PayMaya'
                ]);
                $total = $total + $item->amount;

                if($item->pre_order != 1){
                    $this->updateInventory($item->product_code, $item->qty);
                }
                else{
                    DB::table('orders')
                    ->where('order_no', $order_no)
                    ->update([
                        'status' => 6,
                        'pre_order' => 1
                    ]);
                }
               
            }
            Cart::truncate();

            $order = new Order();
            $cms = DB::table('cms')->where('id', 1)->first();
            Session::put('cms_name', $cms->name);
            Session::put('cms_theme_color', $cms->theme_color);
            Session::put('cms_undraw_img', $cms->undraw_img);
            $orders = $order->readOrders($user_id);

            $source_id = session()->get('source_id');
            $amount = session()->get('amount');
            $source =  $this->retrieveSource3($source_id);

            return redirect('/order-info/'.$source->data->id.'/paymaya')->with('success', 'Order received, your payment ₱'.($amount/100).' via PayMaya was successful!');

        }
    }

    
    public function createPaymayaPaymentMethod(PayMongo $paymongo) {

        $payment_method = request()->payment_method;
        $amount = request()->total;
        $amount = (float)$amount*100;
        $pm = $paymongo->createPaymayaPaymentMethod();
        $payment_method_id = $pm->data->id;

        if ($payment_method_id) {
            session()->put('amount', $amount);
            $pi = $paymongo->createPaymayaPaymentIntent($amount);
            $payment_intent_id = $pi->data->id;
            
            $attach_pi = $paymongo->attatchPaymayaPaymentIntent($payment_intent_id, $payment_method_id);
            
            session()->put('source_id',$paymongo->getSourceID($attach_pi));
     
            return redirect($paymongo->getSourceURL($attach_pi));
        }
    }

    public function orderInfo($source_id, $payment_method) {
        if ($payment_method == 'card') {
            return view('payment-info');
        }
        else {
            // get source id from session is temporary, use paymongo_payment table to save source id with order #.
            if (session()->get('source_id') == $source_id) {
                return view('payment-info');
            }
            else {
                abort(404);
            }
        }
    }

    public function createSource(PayMongo $paymongo) {
        $amount = request()->total;
        $amount = (float)$amount*100;
        session()->put('amount', $amount);
        return $paymongo->createSource($amount);
    }

    public function retrieveSource(PayMongo $paymongo){
        return $paymongo->retrieveSource();
    }

    public function createPayment(PayMongo $paymongo) {
        return $paymongo->createPayment();
    }

    public function createCheckout(PayMongo $paymongo) {
        $amount = request()->total;
        $product_name = DB::table('cms')->select('name')->where('id', 1)->first();

        $amount = (float)$amount*100;
        session()->put('amount', $amount);
        return $paymongo->createCheckout($amount, $product_name);
    }

    public function generateOrderNumber() {
        $today = date("Ymd");
        return  $today .'-'. uniqid();
    }

    public function retrieveSource2($source_id){
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'https://api.paymongo.com/v1/sources/'.$source_id, [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => $this->authorization,
            ],
        ]);
        return json_decode($response->getBody());
    }

    public function retrieveSource3($source_id){
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'https://api.paymongo.com/v1/sources/'.$source_id, [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Basic c2tfdGVzdF9RQ3NHc3h3Z0JBRWZrZ0tYQzE0NkdaeVA6cGtfdGVzdF8yaXd1bzkyeXJYUFVUbXlwZGFCbmphY2E=',
            ],
        ]);
        return json_decode($response->getBody());
    }

    public function updateInventory($product_code, $qty){
        
        DB::table('product')
            ->where(DB::raw('CONCAT(prefix, id)'), $product_code)
            ->update([
                'qty' => DB::raw('qty - '. $qty .'')
            ]);
    }

    
  
}
