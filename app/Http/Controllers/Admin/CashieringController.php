<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cashiering;
use App\Models\Sales;
use DB;
use Input;
use Hash;
use Carbon\Carbon;
use Session;
use App\Models\Discount;

class CashieringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$invoicecount =   DB::table('sales')->max('invoice_no')->increment('invoice_no');
        // $invoicecount = DB::table('sales')
        // ->where('invoice_no', DB::raw("(select max(`invoice_no`) from sales)"))
        // ->first()
        // ->invoice_no + 1;
        return view('admin.sales.cashiering');
    }

    public function checklrn($lrn){
        $checklrn =  DB::table('lrn')->where('lrn',$lrn)
        ->first();

        if($checklrn){
            return 'success';
        }
        else{
            return 'failed';
        }
    }

    public function updateinvoice(){
        $invoicecount = DB::table('sales')
        ->where('invoice_no', DB::raw("(select max(`invoice_no`) from sales)"))
        ->first()
        ->invoice_no + 1;

        return $invoicecount;
    }

    public function addToTray()
    {
            $product_code = Input::input('product_code');
            $qty = Input::input('qty');
            $amount = Input::input('amount');
           
            if($this->isProductExists($product_code)){
                DB::table('cashiering_tray')
                ->where('product_code', $product_code)
                ->update(array(
                    'amount' => DB::raw('amount + '. $amount),
                    'qty' => DB::raw('qty + '. $qty)));
            }
            else{
                $c = new Cashiering;
                $c->product_code = $product_code;
                $c->qty = $qty;
                $c->amount = $amount;
                $c->save();
            }
    }

    public function checkProductQty($product_code, $qty_order)
    {
        $inventory_qty = DB::table('tblexpiration')
            ->where('cashiering_tray', $product_code)
            ->sum('qty');
            
        
        return $inventory_qty >= $qty_order ? '1' : '0';

    }

    public function isProductExists($product_code){
        $p = DB::table('cashiering_tray')->where('product_code', $product_code);
        if($p->count() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    public function readTray(){
        $cashiering = new Cashiering;
        return $cashiering->readCashieringTray();
    }

    public function void($id)
    {
        //$input = Input::all();
        
        // $data = DB::table('users')
        //     ->where('username', $input['username'])
        //     ->where('access_level', '!=', 4)
        //     ->first();
            
        // if ($data) {
            // if password is correct, void the item. 
            //if (Hash::check($input['password'], $data->password)) {      
                DB::table('cashiering_tray')->where('id', $id)->delete();
                return 'success';
            // }
            // else {
            //     return 'failed';
            // }
        // }
        // else {
        //     return 'failed';
        // }
       
    }

    public function recordSale()
    {
        $input = Input::all();
        $cashiering = new Cashiering;
        $data = $cashiering->readCashieringTray();
        $invoice_no = $input['invoice_no'];

        if (!$this->isInvoiceExists($invoice_no)) {
            foreach ($data as $items) {
                $sales = new Sales;
                $sales->prefix = date('Ymd');
                $sales->invoice_no = $invoice_no;
                $sales->product_code = $items->product_code;
                $sales->qty = $items->qty_order;
                $sales->amount = $items->amount;
                $sales->payment_method = $input['payment_method'];
                $sales->order_from = 'walk-in';
                $sales->status = 1;
                $sales->save();
    
                $this->updateInventory($items->product_code, $items->qty_order);
            }

            return 'success';
        }
        else {
            return 'invoice_exists';
        }
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

    public function readOneQty($product_code){
        return DB::table('cashiering_tray')
            ->where('product_code', $product_code)
            ->first('qty');
    }

    

    public function previewInvoice($wholesale_discount_amount, $senior_pwd_discount_amount, $studentname){

        $cashiering = new Cashiering;
        $data = $cashiering->readCashieringTray();
        $output = $this->generateSalesInvoice($data, $wholesale_discount_amount, $senior_pwd_discount_amount, $studentname);

        $this->removeAllTrayProducts();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A5', 'portrait');
    
        return $pdf->stream('Invoice-#');
    }

    public function readDiscount()
    {
        return Discount::first();
    }

    public function generateSalesInvoice($product, $wholesale_discount_amount, $senior_pwd_discount_amount, $studentname){

        $title = Session::get('cms_name');
        $address = Session::get('cms_address');

        $output = '
        <style>
        @page { margin: 10px; }
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
            text-align:right;
        }

        .align-text td{
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

        .ar2{
            position:absolute; 
            bottom:0px;
            right:0px;
            
        }

        .center img {
            display: block;
            margin-left: auto;
            margin-right: auto;
            position:absolute;
            left:220px;
            }


         </style>
        <div style="width:100%">
        <div class="center">
        <img src="images/'.Session::get('cms_logo').'" style="width:20%; align:middle;">
        </div>
        <br> <br> <br> <br>
        <h2 class="p-name">'.$title.'</h2>
        <p class="p-details address">'.$address.'</p>
        <h3 style="text-align:center;">RECEIPT</h3>

        <p style="text-align:left;">Student Name: '.$studentname.'</p>
    
        <table width="100%" style="border-collapse:collapse; border: 1px solid;">                
            <thead>
                <tr>
                    <th>Qty</th>  
                    <th>Unit</th>    
                    <th>Product Name</th>   
                    <th>Unit price</th>   
                    <th>Amount</th>   
            </thead>
        <tbody>
            ';
            $total_amount = 0;
            $sub_total = 0;
            if($product){
                foreach ($product as $data) {
                
                    $total_amount += $data->amount;
                
                    $output .='
                <tr class="align-text">                             
                    <td class="f-courier">'. $data->qty_order .'</td>  
                    <td class="f-courier">'. $data->unit .'</td>  
                    <td class="f-courier">'. $data->description .'</td>
                    <td class="f-courier">'. number_format($data->selling_price,2,'.',',') .'</td>   
                    <td class="f-courier align-text" style="width:110px;">'. number_format($data->amount,2,'.',',') .'</td>    
                </tr>
                ';
                
                } 
            }
            else{
                echo "No data found";
            }
            


            $total_amount = $total_amount - $wholesale_discount_amount;
            $total_amount = $total_amount - $senior_pwd_discount_amount;

            $output .='

            <tr>
                <td style="text-align:right;" colspan="4">Total Amount Due </td>
                <td class="align-text f-courier">PhP '. number_format(($total_amount),2,'.',',')  .'</td>
            </tr>

            </tbody>
        </table>
    
        <div class="b-text">
            <p class="ar line">----------------------------------------</p>
            <p class="ar b-label">Cashier/Authorized Representative</p>
        </div>
    </div>';

    $output .='
              </tbody>
              </table>
              <p class="ar2"> Date Generated: '. Carbon::now()->format('F d, Y').' <br/> Receipt Prepared By: '. Session::get('Name') .'</p>
              ';

        return $output;
    }

    public function getVAT($total_due){
        return $total_due * 0.12;
    }

    public function getNetOfVAT($total_due){
        return $total_due - ($total_due * 0.12);
    }

    public function getAmountDue($total_due){
        return $total_due - $this->getVAT($total_due);
    }

    public function removeAllTrayProducts() {
        $cashiering = new Cashiering;
        $cashiering->truncate();
    }
}
