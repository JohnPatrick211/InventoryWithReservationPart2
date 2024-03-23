<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Supplier;
use Carbon\Carbon;
use Session;

class ReorderListController extends Controller
{
    public function index(Request $request)
    {
        $supplier = Supplier::where('status', 1)->get();
        $data = new Product;
        if(request()->ajax())
        {       
            if($request->supplier_id){
                return datatables()->of($data->readReorderBySupplier($request->supplier_id))
                ->addColumn('orig_price', function($product)
                {
                    $button = ' <div class="text-right">'.$product->orig_price.'</div>';
                   
                    return $button;
                })
                ->rawColumns(['orig_price'])
                ->make(true);   
            }
                    
        }

        return view('admin.reports.reorder-list-report', compact('supplier'));
    }

    public function previewReport($supplier_id){

        $data = $this->readReportData($supplier_id);
        $supplier = new Supplier;
        $supplier_name = $supplier->getSupplierNameByID($supplier_id);
    
        return $data->stream('reorder-list-' . $supplier_name . '.pdf');
    }
    
    public function downloadReport($supplier_id){

        $data = $this->readReportData($supplier_id);
        $supplier = new Supplier;
        $supplier_name = $supplier->getSupplierNameByID($supplier_id);
    
        return $data->download('reorder-list-' . $supplier_name . '.pdf');
    }

    public function readReportData($supplier_id) {
        $data = new Product;
        $supplier = new Supplier;

        $items = $data->readReorderBySupplier($supplier_id);
        $supplier_name = $supplier->getSupplierNameByID($supplier_id);

        return $this->setReportLayout($items, $supplier_name);
    }

    public function setReportLayout($items, $supplier_name) {
        $output = $this->reportLayout($items, $supplier_name);
    
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        return $pdf->setPaper('A4', 'landscape');
    }

    public function reportLayout($items, $supplier_name){

        $title = Session::get('cms_name');
        $address = Session::get('cms_address');
        
        $output = '
        <style>
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
            left:440px;
            }
        </style>
        <div style="width:100%">
        <div class="center">
        <img src="images/'.Session::get('cms_logo').'" style="width:15%; align:middle;">
        </div>
        <br> <br> <br> <br> <br> <br>
        <h1 style="text-align:center;">'.$title.'</h1>

        <div style="text-align:center;">'.$address.'<div>
        <h2 style="text-align:center;">Reorder List Report</h2>

        <p style="text-align:left;">As of: '. date("F j, Y") .'</p>
        <p style="text-align:left;">Supplier: <b> '. $supplier_name .' </b> </p>

        <table width="100%" style="border-collapse:collapse; border: 1px solid;">
                      
            <thead>
                <tr>  
                    <th style="border: 1px solid;">Product Code</th>
                    <th style="border: 1px solid;">Name</th> 
                    <th style="border: 1px solid;">Unit</th>      
                    <th style="border: 1px solid;">Category</th>      
                    <th style="border: 1px solid;">Supplier</th>   
                    <th style="border: 1px solid;">Original price</th>               
                    <th style="border: 1px solid;">Stock</th>                                
                    <th style="border: 1px solid;">Reorder Point</th>   
            </thead>
            <tbody>
                ';
    
            if($items){
                foreach ($items as $data) {
                
                $output .='
                <tr>                             
                    <td style="border: 1px solid; padding:10px;">'. $data->product_code .'</td>
                    <td style="border: 1px solid; padding:10px;">'. $data->description .'</td>
                    <td style="border: 1px solid; padding:10px;">'. $data->unit .'</td>     
                    <td style="border: 1px solid; padding:10px;">'. $data->category .'</td>     
                    <td style="border: 1px solid; padding:10px;">'. $data->supplier .'</td>    
                    <td style="border: 1px solid; padding:10px; text-align:right;">'. $data->orig_price .'</td>     
                    <td style="border: 1px solid; padding:10px;">'. $data->qty.'</td>  
                    <td style="border: 1px solid; padding:10px;">'. $data->reorder .'</td>  
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
