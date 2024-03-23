<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymongoReservation;
use App\Models\Order;
use App\Models\Cart;
use App\Models\UserAddress;
use App\Models\ReservationBook;
use App\Models\DeliveryArea;
use Auth;
use DB;
use Session;

class CheckoutReservationController extends Controller
{
    private $authorization = 'Basic c2tfdGVzdF9RQ3NHc3h3Z0JBRWZrZ0tYQzE0NkdaeVA6cGtfdGVzdF8yaXd1bzkyeXJYUFVUbXlwZGFCbmphY2E=';

    public function index() {
        $cms = DB::table('cms')->where('id', 1)->first();
        Session::put('cms_name', $cms->name);
        Session::put('cms_theme_color', $cms->theme_color);
        Session::put('cms_undraw_img', $cms->undraw_img);
        $charge = 0.00;
        $subtotal = $this->reservationTotal();
        $productdetails = $this->reservationbook();
        return view('checkout-reservation', compact('charge', 'subtotal'));
    }

    public function reservationTotal(){
        return ReservationBook::where('user_id', Auth::id())->sum('amount');
    }

    public function reservationbook(){
       // return Cart::where('user_id', Auth::id())->get();
        return DB::table('reservationbook AS BR')
        ->select('BR.*', 'product.*','product.id as product_id')
        ->leftJoin('product', 'BR.product_code', '=', 'product.id')
        ->where('BR.user_id',Auth::id())
        ->get();
    }

    public function placeOrderCard2(ReservationBook $cart) {
        $cart = $cart->readBook();
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
                    'payment_method' => 'Card'
                ]);

                Order::where('order_no', $order_no)->update([
                    'status' => 5,
                    'reservation' => 1
                ]);

                $total = $total + $item->amount;
              //  $this->updateInventory($item->product_code, $item->qty);
            }
            ReservationBook::truncate();

            $order = new Order();
            $cms = DB::table('cms')->where('id', 1)->first();
            Session::put('cms_name', $cms->name);
            Session::put('cms_theme_color', $cms->theme_color);
            Session::put('cms_undraw_img', $cms->undraw_img);
            $orders = $order->readOrders($user_id);


            return redirect('/order-info-reserve/'. $order_no.'/card')->with('success', 'Reservation received, your payment ₱'.($total).' via Card was successful!');

            //return view('orders', compact('orders'));

        }
    }

    public function placeOrderGcash2(ReservationBook $cart) {
        $cart = $cart->readBook();
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
                    'payment_method' => 'GCash'
                ]);

                Order::where('order_no', $order_no)->update([
                    'status' => 5,
                    'reservation' => 1
                ]);
                $total = $total + $item->amount;
               // $this->updateInventory($item->product_code, $item->qty);
            }
            ReservationBook::truncate();

            $order = new Order();
            $cms = DB::table('cms')->where('id', 1)->first();
            Session::put('cms_name', $cms->name);
            Session::put('cms_theme_color', $cms->theme_color);
            Session::put('cms_undraw_img', $cms->undraw_img);
            $orders = $order->readOrders($user_id);

            $source_id = session()->get('source_id');
            $amount = session()->get('amount');
            $source =  $this->retrieveSource2($source_id);

            return redirect('/order-info-reserve/'.$source->data->id.'/gcash')->with('success', 'Reservation received, your payment ₱'.($amount/100).' via GCash was successful!');

        }
    }

    public function placeOrderPaymaya2(ReservationBook $cart) {
        $cart = $cart->readBook();
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
                    'payment_method' => 'PayMaya'
                ]);

                Order::where('order_no', $order_no)->update([
                    'status' => 5,
                    'reservation' => 1
                ]);
                
                $total = $total + $item->amount;

                //$this->updateInventory($item->product_code, $item->qty);
            }
            ReservationBook::truncate();

            $order = new Order();
            $cms = DB::table('cms')->where('id', 1)->first();
            Session::put('cms_name', $cms->name);
            Session::put('cms_theme_color', $cms->theme_color);
            Session::put('cms_undraw_img', $cms->undraw_img);
            $orders = $order->readOrders($user_id);

            $source_id = session()->get('source_id');
            $amount = session()->get('amount');
            $source =  $this->retrieveSource3($source_id);

            return redirect('/order-info-reserve/'.$source->data->id.'/paymaya')->with('success', 'Reservation received, your payment ₱'.($amount/100).' via PayMaya was successful!');

        }
    }

    
    public function createPaymayaPaymentMethod2(PaymongoReservation $paymongo) {

        $payment_method = request()->payment_method;
        $amount = request()->total;
        $amount = (float)$amount*100;
        $pm = $paymongo->createPaymayaPaymentMethod2();
        $payment_method_id = $pm->data->id;

        if ($payment_method_id) {
            session()->put('amount', $amount);
            $pi = $paymongo->createPaymayaPaymentIntent2($amount);
            $payment_intent_id = $pi->data->id;
            
            $attach_pi = $paymongo->attatchPaymayaPaymentIntent2($payment_intent_id, $payment_method_id);
            
            session()->put('source_id',$paymongo->getSourceID($attach_pi));
     
            return redirect($paymongo->getSourceURL($attach_pi));
        }
    }

    public function orderInfo($source_id, $payment_method) {
        if ($payment_method == 'card') {
            return view('paymentreserve-info');
        }
        else {
            // get source id from session is temporary, use paymongo_payment table to save source id with order #.
            if (session()->get('source_id') == $source_id) {
                return view('paymentreserve-info');
            }
            else {
                abort(404);
            }
        }
    }

    public function createSource2(PaymongoReservation $paymongo) {
        $amount = request()->total;
        $amount = (float)$amount*100;
        session()->put('amount', $amount);
        return $paymongo->createSource2($amount);
    }

    public function retrieveSource(PaymongoReservation $paymongo){
        return $paymongo->retrieveSource();
    }

    public function createPayment2(PaymongoReservation $paymongo) {
        return $paymongo->createPayment();
    }

    public function createCheckout2(PaymongoReservation $paymongo) {
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
