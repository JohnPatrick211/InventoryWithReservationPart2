<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\SupplierDelivery;
use Session;

class SupplierDeliveryReportController extends Controller
{
    public function index(Request $request)
    {
        $supplier = Supplier::where('status', 1)->get();
        $product = new SupplierDelivery;
        $product = $product->readSupplierDelivery($request->supplier_id, $request->date_from, $request->date_to);

        if(request()->ajax())
        { 
            return datatables()->of($product)
            ->addColumn('action', function($data){
                $button = '<a class="btn btn-sm btn-archive-supplier-delivery" data-id='. $data->id .'">
                <i class="fas fa-archive"></i></a>';
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('admin.reports.supplier-delivery-report', compact('supplier'));
    }

    public function archive($id)
    {
        SupplierDelivery::where('id', $id)
        ->update([
            'archive_status' => 0,
        ]);

        return redirect()->back()
            ->with('success', 'Supplier Delivery was archived.');
    }

    public function previewReport($supplier_id, $date_from, $date_to){

        $sp = new SupplierDelivery;
        $supplier = new Supplier;

        $items = $sp->readSupplierDelivery($supplier_id, $date_from, $date_to);
        $supplier_name = $supplier->getSupplierNameByID($supplier_id);

        $output = $this->reportLayout($items, $supplier_name, $date_from, $date_to);
    
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->stream($supplier_name .' supplier-delivery-'.$date_from.'-to-'.$date_to.'.pdf');
    }
    
    public function downloadReport($supplier_id, $date_from, $date_to){

        $sp = new SupplierDelivery;
        $supplier = new Supplier;

        $items = $sp->readSupplierDelivery($supplier_id, $date_from, $date_to);
        $supplier_name = $supplier->getSupplierNameByID($supplier_id);

        $output = $this->reportLayout($items, $supplier_name, $date_from, $date_to);
    
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->download($supplier_name .' supplier-delivery-'.$date_from.'-to-'.$date_to.'.pdf');
    }

    public function reportLayout($items, $supplier_name, $date_from, $date_to){

        $title = Session::get('cms_name');
        $address = Session::get('cms_address');
        
        $output = '
        
        <div style="width:100%">
        <div class="center">
        <img src="images/'.Session::get('cms_logo').'" style="width:15%; align:middle;  display: block;
        margin-left: auto;
        margin-right: auto;
        position:absolute;
        left:445px;">
        </div>
        <br> <br> <br> <br> <br> <br>
        <h1 style="text-align:center;">'.$title.'</h1>
        <div style="text-align:center;">'.$address.'<div>
        <h2 style="text-align:center;">Supplier Delivery Report</h2>

        <p style="text-align:left;">Date: '. date("F j, Y", strtotime($date_from)) .' - '. date("F j, Y", strtotime($date_to)) .'</p>
        <p style="text-align:left;">Supplier: <b> '. $supplier_name .' </b> </p>

        <table width="100%" style="border-collapse:collapse; border: 1px solid;">
                      
            <thead>
                <tr>
                    <th style="border: 1px solid;">Delivery #</th>
                    <th style="border: 1px solid;">PO #</th>
                    <th style="border: 1px solid;">Product Code</th>     
                    <th style="border: 1px solid;">Name</th>   
                    <th style="border: 1px solid;">Supplier</th> 
                    <th style="border: 1px solid;">Unit</th>      
                    <th style="border: 1px solid;">Qty Ordered</th>                              
                    <th style="border: 1px solid;">Qty Delivered</th>   
                    <th style="border: 1px solid;">Date Recieved</th>
                    <th style="border: 1px solid;">Remarks</th>  
            </thead>
            <tbody>
                ';
    
            if($items){
                foreach ($items as $data) {
                
                $output .='
                <tr>        
                    <td style="border: 1px solid; padding:10px;">'. $data->del_no .'</td>                     
                    <td style="border: 1px solid; padding:10px;">'. $data->po_no .'</td>
                    <td style="border: 1px solid; padding:10px;">'. $data->product_code .'</td>
                    <td style="border: 1px solid; padding:10px;">'. $data->description .'</td>
                    <td style="border: 1px solid; padding:10px;">'. $data->supplier .'</td>    
                    <td style="border: 1px solid; padding:10px;">'. $data->unit .'</td>     
                    <td style="border: 1px solid; padding:10px;">'. $data->qty_order .'</td>  
                    <td style="border: 1px solid; padding:10px;">'. $data->qty_delivered .'</td>  
                    <td style="border: 1px solid; padding:10px;">'. $data->date_delivered .'</td>  
                    <td style="border: 1px solid; padding:10px;">'. $data->remarks .'</td>  
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
            </div>';
    
        return $output;
    }
}
