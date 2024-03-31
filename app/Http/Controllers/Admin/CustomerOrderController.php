<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Sales;
use Session;
use DB;

class CustomerOrderController extends Controller
{
    public function index()
    {

        return view('admin.customer-orders.index');
    }

    public function readOrders(Order $o)
    {
        $status = 5;
        if (request()->object == "pending") {
            $status = 1;
        }else if (request()->object == "prepared" ) {
            $status = 2;
        }else if (request()->object == "shipped") {
            $status = 3;
        }else if (request()->object == "completed" ) {
            $status = 4;
        }else if (request()->object == "cancelled") {
            $status = 0;
        }else if (request()->object == "pre") {
            $status = 6;
        }
        
        

        $order = $o->readOrdersByStatus($status);
        if(request()->ajax())
        { 
            return datatables()->of($order)
                ->addColumn('action', function($order)
                {
                    $order->delivery_date = date('F d, Y', strtotime($order->delivery_date));
                    if($order->order_status == '5'){
                        $button = '<a class="btn btn-sm btn-show-reservation" data-name="'. $order->name .'" data-order-no="'. $order->order_no .'" ';
                    }
                    else{
                        $button = '<a class="btn btn-sm btn-show-order" data-name="'. $order->name .'" data-order-no="'. $order->order_no .'" ';
                    }
                    $button .= 'data-user-id="'. $order->user_id .'" data-payment="'. $order->payment_method .'" data-delivery-date="'. $order->delivery_date .'" '; 
                    $button .= 'data-phone="'. $order->phone .'" data-email="'. $order->email .'" data-longlat="'. $order->map .'" data-id-type="'. $order->id_type .'" style="color:#1970F1;">Show orders</a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function readShippingAddress($user_id) {
        return DB::table('user_address')->where('user_id', $user_id)->first();
    }

    public function readOneOrder($order_no) {
        $order = new Order;
        return $order->readOneOrder($order_no);
    }

    public function readTotalAmount($order_no) {
        return DB::table('orders')
        ->where('order_no', $order_no)
        ->sum('amount');
    }
    
    public function getShippingFee($order_no) {
        return DB::table('order_shipping_fee')
        ->where('order_no', $order_no)
        ->value('shipping_fee');
    }

    public function orderChangeStatus($order_no) {
        
        if (request()->status == 2) { 
            $orders = $this->readOneOrder($order_no);
            $this->recordSale($orders);

            $delivery_date = date('Y-m-d');
            
            if (request()->delivery_date) {
                $delivery_date = request()->delivery_date;
            }
            Order::where('order_no', $order_no)->update([
                'delivery_date' => $delivery_date
            ]);
        }

        Order::where('order_no', $order_no)->update([
            'status' => request()->status
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'order changed status success',
            'order_no' => $order_no,
        ]);
    }

    public function recordSale($orders)
    {
        $invoice_no = $this->readInvoice();

        if (!$this->isInvoiceExists($invoice_no)) {
            foreach ($orders as $items) {
                $sales = new Sales;
                $sales->prefix = date('Ymd');
                $sales->invoice_no = $invoice_no;
                $sales->product_code = $items->product_code;
                $sales->qty = $items->qty;
                $sales->amount = $items->amount;
                $sales->payment_method = $items->payment_method;
                $sales->order_from = 'online';
                $sales->status = 1;
                $sales->save();
    
                //$this->updateInventory($items->product_code, $items->qty);
            }

            return 'success';
        }
        else {
            return 'invoice_exists';
        }
    }

    public function readInvoice(){
        $invoice_no = DB::table('sales')->max('invoice_no');
        return isset($invoice_no) ? $invoice_no+1 : 0;
    }

    public function isInvoiceExists($invoice_no){
        $row = DB::table('sales')->where('invoice_no', $invoice_no)->get();
        return count($row) > 0 ? true : false;
    }

    public function updateInventory($product_code, $qty){
        
        DB::table('product')
            ->where(DB::raw('CONCAT(prefix, id)'), $product_code)
            ->update([
                'qty' => DB::raw('qty - '. $qty .'')
            ]);
    }
    // Pre-Order Controller
    public function indexreport(Request $request){
        $cms = DB::table('cms')->where('id', 1)->first();
        Session::put('cms_name', $cms->name);
        Session::put('cms_theme_color', $cms->theme_color);
        Session::put('cms_undraw_img', $cms->undraw_img);

        $prefix = 'P-';
        
        $data = DB::table('cart AS BR')
        ->select('BR.*', 'users.name AS studentName', 'product.description AS productName', 'BR.qty AS preorder_qty','BR.created_at AS preorder_date')
        ->leftJoin('users', 'BR.user_id', '=', 'users.id')
        ->leftJoin('product', 'BR.product_code', DB::raw( "CONCAT('%', product.id, '%'))" ))
        ->get();

        if(request()->ajax())
        { 
            return datatables()->of($data)
                ->make(true);
        }

        return view('admin.reports.preorder');
    }


}
