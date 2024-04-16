<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Sales;
use Session;
use DB;
use Carbon\Carbon;

class CustomerOrderController extends Controller
{
    public function index()
    {

        return view('admin.customer-orders.index');
    }

    public function readOrders(Order $o)
    {
        $status = 5;
        $reserve = 0;
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
        $orders = $this->readOneOrder($order_no);
        //dd($orders[0]->pre_order);
        $substring_id = substr($orders[0]->product_code, 2);
       $stock = DB::table('product')
        ->where('id', $substring_id)
        ->get();
        //dd($stock[0]->qty);
        if($stock[0]->qty == 0 && $orders[0]->pre_order == 1){
            return response()->json([
                'status' => 'error_qty',
                'message' => 'Please Check the Qty of the Product',
                'order_no' => $order_no,
            ]);
            
        }
        else{
            if (request()->status == 2) {
                dd('failed');
                if($orders[0]->reservation == 1){
                    dd('failed2');
                    $orders = $this->readOneOrder($order_no);
                    $this->recordSale2($orders);
        
                    $delivery_date = date('Y-m-d');
                    
                    if (request()->delivery_date) {
                        $delivery_date = request()->delivery_date;
                    }
                    Order::where('order_no', $order_no)->update([
                        'delivery_date' => $delivery_date
                    ]);
                }
                else{
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
               
            }
            else if(request()->status == 4){
                dd('success');
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

    public function recordSale2($orders)
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
    
                $this->updateInventory($items->product_code, $items->qty);
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
        
        $data = DB::table('orders AS BR')
        ->select('BR.*', 'users.name AS studentName', 'product.description AS productName', 'BR.qty AS preorder_qty','BR.created_at AS preorder_date')
        ->leftJoin('users', 'BR.user_id', '=', 'users.id')
        ->leftJoin('product', 'BR.product_code', '=', DB::raw('CONCAT(product.prefix, product.id)'))
        ->where('BR.pre_order',1)
        ->get();

        if(request()->ajax())
        { 
            return datatables()->of($data)
                ->make(true);
        }

        return view('admin.reports.preorder');
    }

    public function previewReport(){

        $data = DB::table('orders AS BR')
        ->select('BR.*', 'users.name AS studentName', 'product.description AS productName', 'BR.qty AS preorder_qty','BR.created_at AS preorder_date')
        ->leftJoin('users', 'BR.user_id', '=', 'users.id')
        ->leftJoin('product', 'BR.product_code', '=', DB::raw('CONCAT(product.prefix, product.id)'))
        ->where('BR.pre_order',1)
        ->get();

        $output = $this->reportLayout($data);
    
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'portrait');
    
        return $pdf->stream('preorder_report.pdf');
    }
    
    public function downloadReport(){

        $data = DB::table('orders AS BR')
        ->select('BR.*', 'users.name AS studentName', 'product.description AS productName', 'BR.qty AS preorder_qty','BR.created_at AS preorder_date')
        ->leftJoin('users', 'BR.user_id', '=', 'users.id')
        ->leftJoin('product', 'BR.product_code', '=', DB::raw('CONCAT(product.prefix, product.id)'))
        ->where('BR.pre_order',1)
        ->get();

        $output = $this->reportLayout($data);
    
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->download('preorder_report_'. date('Y_m_d_h:m:s').'.pdf');
    }

    public function reportLayout($items){

        $title = Session::get('cms_name');
        $address = Session::get('cms_address');
        
        $output = '
        <style>

        .ar2{
            position:absolute; 
            bottom:-30px;
            right:0px;
            
        }

        .center img {
            display: block;
            margin-left: auto;
            margin-right: auto;
            position:absolute;
            left:290px;
            }


         </style>
        <div style="width:100%">
        <div class="center">
        <img src="images/'.Session::get('cms_logo').'" style="width:20%; align:middle;">
        </div>
        <br> <br> <br> <br> <br>
        <h1 style="text-align:center;">'.$title.'</h1>

        <div style="text-align:center;">'.$address.'<div>
        <h2 style="text-align:center;">Pre-Order Report</h2>
        
        ';

        $output .='
        
        <p>As of : <b> '. date("F j, Y") .'</p> </b>
    
        <table width="100%" style="border-collapse:collapse; border: 1px solid;">
                      
            <thead>
                <tr>
                    
                    <th style="border: 1px solid;">ID</th>
                    <th style="border: 1px solid;">Student Name</th>
                    <th style="border: 1px solid;">Product Name</th>
                    <th style="border: 1px solid;">Qty</th>
                    <th style="border: 1px solid;">Amount</th>
                    <th style="border: 1px solid;">Pre-Order Date</th>
            </thead>
            <tbody>
                ';
    
            if($items){
                foreach ($items as $data) {
                
                $output .='
                <tr>                             
                    <td style="border: 1px solid; padding:10px;">'. $data->id .'</td>
                    <td style="border: 1px solid; padding:10px;">'. $data->studentName .'</td>     
                    <td style="border: 1px solid; padding:10px;">'. $data->productName .'</td>  
                    <td style="border: 1px solid; padding:10px;">'. $data->preorder_qty .'</td>  
                    <td style="border: 1px solid; padding:10px;">'. $data->amount .'</td>  
                    <td style="border: 1px solid; padding:10px;">'. $data->preorder_date .'</td>   
                </tr>
                ';
                
                } 
            }
            else{
                echo "No data found";
            }
        
          
            $output .='
            </tbody>
        </table>
        <p class="ar2"> Date Generated: '. Carbon::now()->format('F d, Y').' <br/> Report Prepared By: '. Session::get('Name') .'</p>
             
            </div>';

    
        return $output;
    }


}
