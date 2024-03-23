<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Session;
use Carbon\Carbon;
class InventoryReportController extends Controller
{
    public function index()
    {
        $category = Category::where('status', 1)->get();
        $product = new Product;
        $product = $product->readAllProduct();

        if(request()->ajax())
        { 
            return datatables()->of($product)
            ->addColumn('selling_price', function($product)
                {
                    $button = ' <div class="text-right">'.$product->selling_price.'</div>';
                   
                    return $button;
                })
                ->addColumn('orig_price', function($product)
                {
                    $button = ' <div class="text-right">'.$product->selling_price.'</div>';
                   
                    return $button;
                })
                ->rawColumns(['selling_price', 'orig_price'])
                ->make(true);
        }

        return view('admin.reports.inventory-report', compact('category'));
    }

    public function readProductByCategory($category_id)
    {
        $product = new Product;
        $product = $product->readProductByCategory($category_id);

        if(request()->ajax())
        { 
            return datatables()->of($product)
                ->make(true);
        }

        return view('admin.reports.inventory-report', compact('supplier'));
    }

    public function previewReport($category_id){

        $product = new Product;
        $category = new Category;

        if ($category_id != 0) {
            $product = $product->readProductByCategory($category_id);
        }
        else {
            $product = $product->readAllProduct();
        }

        $category_name = $category->getCategoryName($category_id);

        $output = $this->reportLayout($product, $category_name);
    
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'portrait');
    
        return $pdf->stream('inventory_report.pdf');
    }
    
    public function downloadReport($category_id){
        $product = new Product;
        $category = new Category;

        if ($category_id != 0) {
            $product = $product->readProductByCategory($category_id);
        }
        else {
            $product = $product->readAllProduct();
        }

        $category_name = $category->getCategoryName($category_id);

        $output = $this->reportLayout($product, $category_name);
    
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->download('inventory_report_'. date('Y_m_d_h:m:s').'.pdf');
    }

    public function reportLayout($items, $category_name){

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
        <h2 style="text-align:center;">Inventory Report</h2>
        
        ';

        if ($category_name != "") {
            $output .= '<p>Category: <b> '. $category_name .' </b></p>';
        }
        else {
            $output .= '<p>Category: <b> All category </b></p>';
        }

        $output .='

        <p>Total number of products: <b> '. count($items) .'</p> </b>
        
        <p>As of : <b> '. date("F j, Y") .'</p> </b>
    
        <table width="100%" style="border-collapse:collapse; border: 1px solid;">
                      
            <thead>
                <tr>
                    
                    <th style="border: 1px solid;">Product Code</th>
                    <th style="border: 1px solid;">Name</th>
                    <th style="border: 1px solid;">Qty</th>
                    <th style="border: 1px solid;">Unit</th>
                    <th style="border: 1px solid;">Category</th>
                    <th style="border: 1px solid;">Supplier</th>
                    <th style="border: 1px solid;">Original Price</th>
                    <th style="border: 1px solid;">Selling Price</th>
            </thead>
            <tbody>
                ';
    
            if($items){
                foreach ($items as $data) {
                
                $output .='
                <tr>                             
                    <td style="border: 1px solid; padding:10px;">'. $data->product_code .'</td>
                    <td style="border: 1px solid; padding:10px;">'. $data->description .'</td>     
                    <td style="border: 1px solid; padding:10px;">'. $data->qty .'</td>  
                    <td style="border: 1px solid; padding:10px;">'. $data->unit .'</td>  
                    <td style="border: 1px solid; padding:10px;">'. $data->category .'</td>  
                    <td style="border: 1px solid; padding:10px;">'. $data->supplier .'</td>  
                    <td style="border: 1px solid; padding:10px; text-align:right;">'. $data->orig_price .'</td>  
                    <td style="border: 1px solid; padding:10px; text-align:right;">'. $data->selling_price .'</td>  
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
