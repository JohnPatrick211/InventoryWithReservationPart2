<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Models\Supplier;
use Carbon\Carbon;
use Input;
use DB;
use Session;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $supplier = Supplier::where('status', 1)->get();
        return view('admin.inventory.purchase-order.index', ['supplier' => $supplier]);
    }

    public function displayReorders(Request $request){
        $data = new PurchaseOrder;
        if(request()->ajax())
        {       
            if($request->supplier_id){
                return datatables()->of($data->readReorderBySupplier($request->supplier_id))
                ->addColumn('action', function($data){
                    $button = '<a class="btn btn-sm btn-add-to-order" data-id='. $data->id .'>
                    <i class="fa fa-cart-plus"></i></a>';
                    return $button;
                })
                ->addColumn('orig_price', function($product)
                {
                    $button = ' <div class="text-right">'.$product->orig_price.'</div>';
                   
                    return $button;
                })
                ->rawColumns(['action','orig_price'])
                ->make(true);   
            }
                    
        }
    }
    
    public function readPurchasedOrder(Request $request) {
        $data = new PurchaseOrder;
        $data = $data->readPurchasedOrder($request->supplier_id, $request->date_from, $request->date_to);
        if(request()->ajax())
        {       
            if($request->supplier_id){
                return datatables()->of($data)
                ->addColumn('action', function($data){
                    $button = '<a class="btn btn-sm btn-outline-success btn-show-order"
                    data-toggle="modal" data-target="#delivery-modal" data-id='. $data->id .'>Add Delivery</a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);   
            }
                    
        }
    }

    public function readPurchasedOrderInPurchase(Request $request) {
        $data = new PurchaseOrder;
        $data = $data->readPurchasedOrderInPurchase($request->supplier_id, $request->date_from, $request->date_to);
        if(request()->ajax())
        {       
            if($request->supplier_id){
                return datatables()->of($data)
                ->addColumn('action', function($data){
                    $button = '<a class="btn btn-sm btn-outline-success btn-show-ereceipt" data-id='. $data->supplier_id .' data-po='. $data->id .'>E-Receipt</a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);   
            }
                    
        }
    }

    public function addOrder(){

        $product_code = Input::input('product_code');
        $qty_order = Input::input('qty_order');
        $amount = Input::input('amount');
        
        if($this->isAlreadyOrder($product_code)){
            PurchaseOrder::where('product_code', $product_code)
            ->update(array(
                'amount' => DB::raw('amount + '. $amount),
                'qty_order' => DB::raw('qty_order + '. $qty_order),
                ));
        }
        else{
            PurchaseOrder::create([
                    'po_no' => 00000,
                    'product_code' => $product_code,
                    'qty_order' => $qty_order,
                    'amount' => $amount,
                    'status' => 1
                ]);
        }      
    }

    public function isAlreadyOrder($product_code){
        $p = PurchaseOrder::where('product_code', $product_code)->where('status', 1);
        if($p->count() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    public function getPONumber(){
        $po_no = PurchaseOrder::max('po_no');
        $po_no++;
        return $po_no;
    }

    public function readRequestOrderBySupplier(Request $request) {
        $po = new PurchaseOrder;
        Session::put('supplier_id', $request->supplier_id);
        return $po->readRequestOrderBySupplier($request->supplier_id);
    }

    public function removeRequest() {
        $data = Input::all();
        return PurchaseOrder::find($data['id'])->delete();
    }

    public function purchaseOrder() 
    {
        $data = Input::all();
        $product_codes = [];
        $product_codes = $data['product_codes'];
        $po_no = $this->getPONumber();
        for ($i = 0; $i < count($product_codes); $i++) 
        {
            PurchaseOrder::where('product_code', $product_codes[$i])
            ->where('status', 1)
            ->update([ 
                'po_no' => $po_no,
                'status' => 2,
                'remarks' => 'Pending',
                'updated_at' => date('Y-m-d h:m:s')
            ]);
        }
    }

    public function previewRequestPurchaseOrder(){

        $supplier_id = Session::get('supplier_id');

        $po = new PurchaseOrder;
        $supplier = new Supplier;

        $data = $po->readRequestOrderBySupplier($supplier_id);
        $supplier_name = $supplier->getSupplierNameByID($supplier_id);

        $supplier_contact = $supplier->getSupplierContact($supplier_id);
        $output = $this->generateHTML($data, $supplier_name, $supplier_contact );
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->stream('Purchase-Order'.date('Y-m-d h:m'));
    }

    public function previewRequestEReceipt($id,$pos){

        $supplier_id = $id;
        $po2 = $pos;

        $po = new PurchaseOrder;
        $supplier = new Supplier;

        $data = $po->readPurchasedOrderBySupplier($supplier_id,$po2);
        $supplier_name = $supplier->getSupplierNameByID($supplier_id);

        $supplier_contact = $supplier->getSupplierContact($supplier_id);
        $supplier_address = $supplier->getSupplierAddress($supplier_id);
        $output = $this->generateReceipt($data, $supplier_name, $supplier_contact, $supplier_address);
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A5', 'portrait');
    
        return $pdf->stream('Purchase-Order'.date('Y-m-d h:m'));
    }

    public function downloadRequestPurchaseOrder(){
        $supplier_id = Session::get('supplier_id');

        $po = new PurchaseOrder;
        $supplier = new Supplier;

        $data = $po->readRequestOrderBySupplier($supplier_id);
        $supplier_name = $supplier->getSupplierNameByID($supplier_id);
        $supplier_contact = $supplier->getSupplierContact($supplier_id);
        $output = $this->generateHTML($data, $supplier_name, $supplier_contact );
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->download();
    }

    public function generateHTML($items, $supplier_name, $supplier_contact ){

        $title = Session::get('cms_name');
        $address = Session::get('cms_address');

        // if($supplier_name == 'All Suppliers'){
        //     $supplierformat = '';
        // }
        // else{
        //     $supplierformat = '<p style="text-align:left;">Supplier: '.$supplier_name.'</p>';
        // }

        if($supplier_contact == 'All'){
            $supplierformat = '';
        }
        else{
            $supplierformat = '<p style="text-align:left;">Contact number: '.$supplier_contact .'</p>';
        }

        $output = '
        <!DOCTYPE html>
        <html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style type="text/css">';

        $output .= $this->style();

        $output .='
        </style>
        </head>

        <body>

        <div style="width:100%">
        <div class="center">
        <img src="images/'.Session::get('cms_logo').'" style="width:15%; align:middle;">
        </div>
        <br> <br> <br> <br> <br> <br>
        <h1 class="p-name">'.$title.'</h1>
        <div style="text-align:center;">'.$address.'<div>
        <h2 style="text-align:center;">Purchase Order</h2>
        <p style="text-align:left;">Supplier: '.$supplier_name.'</p>
        '.$supplierformat.'
        <p style="text-align:left;">Date: '. date("F j, Y") .'</p>
        <table width="100%" style="border-collapse:collapse; border: 1px solid;">                
            <thead>
                <tr>
                    <th>Product Code</th>    
                    <th>Name</th>   
                    <th>Unit</th>   
                    <th>Category</th>  
                    <th>Supplier</th>  
                    <th>Qty</th>   
                    <th>Price</th>  
               
            <tbody>';

                if($items){
                    $total = 0;
                    foreach ($items as $data) {
                    
                        $output .='
                    <tr class="align-text">              
                        <td>'. $data->product_code .'</td>  
                        <td>'. $data->description .'</td>
                        <td>'. $data->unit .'</td>
                        <td>'. $data->category .'</td>
                        <td>'. $data->supplier .'</td>
                        <td>'. $data->qty_order .'</td>
                        <td style="text-align:right;"><span>&#8369;</span>'. number_format($data->amount,2,'.',',') .'</td>   
                    </tr>';

                    $total = $total + $data->amount;
                } 
                $output.='<tr class="align-text"><td></td><td></td><td></td><td></td><td></td><td></td>  
                        <td style="text-align:right;"><span>&#8369;</span>'. number_format($total,2,'.',',') .'</td>   
                    </tr>';
            }
            else{
                echo "No data found";
            }
            
            
        $output .='
 
        </tbody>
        </table>
        <p class="ar2"> Date Generated: '. Carbon::now()->format('F d, Y').' <br/> Preview Prepared By: '. Session::get('Name') .'</p>
             
    </div>


        </body>

        </html>
        
       ';

        return $output;
    }

    public function generateReceipt($items, $supplier_name, $supplier_contact, $supplier_address){

        $title = Session::get('cms_name');
        $address = Session::get('cms_address');

        // if($supplier_name == 'All Suppliers'){
        //     $supplierformat = '';
        // }
        // else{
        //     $supplierformat = '<p style="text-align:left;">Supplier: '.$supplier_name.'</p>';
        // }

        if($supplier_contact == 'All'){
            $supplierformat = '';
        }
        else{
            $supplierformat = '<p style="text-align:left;">Contact number: '.$supplier_contact .'</p>';
        }

        if($supplier_address == 'All'){
            $supplierformat2 = '';
        }
        else{
            $supplierformat2 = '<div style="text-align:center;">'.$supplier_address.'<div>';
        }

        $output = '
        <!DOCTYPE html>
        <html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style type="text/css">';

        $output .= $this->style();

        $output .='
        </style>
        </head>

        <body>

        <div style="width:100%">
        
        <h1 class="p-name">'.$supplier_name.'</h1>
        '.$supplierformat2.'
        <h2 style="text-align:center;">OFFICIAL RECEIPT</h2>
        <p style="text-align:left;">Customer: '.$title.'</p>
        <p style="text-align:left;">Date: '. date("F j, Y") .'</p>
        <table width="100%" style="border-collapse:collapse; border: 1px solid;">                
            <thead>
                <tr>
                    <th>Product Code</th>    
                    <th>Name</th>   
                    <th>Unit</th>   
                    <th>Category</th>  
                    <th>Supplier</th>  
                    <th>Qty</th>   
                    <th>Price</th>  
               
            <tbody>';

                if($items){
                    $total = 0;
                    foreach ($items as $data) {
                    
                        $output .='
                    <tr class="align-text">              
                        <td>'. $data->product_code .'</td>  
                        <td>'. $data->description .'</td>
                        <td>'. $data->unit .'</td>
                        <td>'. $data->category .'</td>
                        <td>'. $data->supplier .'</td>
                        <td>'. $data->qty_order .'</td>
                        <td style="text-align:right;"><span>&#8369;</span>'. number_format($data->amount,2,'.',',') .'</td>   
                    </tr>';

                    $total = $total + $data->amount;
                } 
                $output.='<tr class="align-text"><td></td><td></td><td></td><td></td><td></td><td></td>  
                        <td style="text-align:right;"><span>&#8369;</span>'. number_format($total,2,'.',',') .'</td>   
                    </tr>';
            }
            else{
                echo "No data found";
            }
            
            
        $output .='
 
        </tbody>
        </table>
        <p class="ar2"> Date Generated: '. Carbon::now()->format('F d, Y').' <br/> Preview Prepared By: '. Session::get('Name') .'</p>
             
    </div>


        </body>

        </html>
        
       ';

        return $output;
    }

    public function style() {
       return '
        @page { margin: 20px; }
        body{ font-family: sans-serif; }
        th{
            border: 1px solid;
        }
        td{
            font-size: 14px;
            border: 1px solid;
            padding-right: 2px;
            padding-left: 2px;
        }

        .center img {
            display: block;
            margin-left: auto;
            margin-right: auto;
            position:absolute;
            left:470px;
            }

        .p-name{
            text-align:center;
            margin-bottom:5px;
        }

        .address{
            text-align:center;
            margin-top:0px;
        }

        .p-details{
            margin:0px;
        }

        .ar{
            text-align:right;
        }

        .al{
            text-left:right;
        }

        .align-text{
            text-align:center;
        }

        .align-text td{
            text-align:center;
        }

        .w td{
            width:20px;
        }

   

        .b-text .line{
            margin-bottom:0px;
        }

        .b-text .b-label{
            font-size:12px;
            margin-top:-7px;
            margin-right:12px;
            font-style:italic;
        }

        .f-courier{
            font-family: monospace, sans-serif;
            font-size:14px;
        }

        span {
            font-family: DejaVu Sans; sans-serif;
        }

        .ar2{
            position:absolute; 
            bottom:0px;
            right:0px;
            
        }
        
        ';
    }
}
