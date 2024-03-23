<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockAdjustment;
use App\Models\AuditTrail;
use App\Models\Product;
use Input;
use DB;

class StockAdjustmentController extends Controller
{
    public $module = "Stock Adjustment";

    public function index()
    {
        $product = new Product;
        $product = $product->readAllProduct();
        if(request()->ajax())
        { 
            return datatables()->of($product)
                ->addColumn('action', function($product)
                {
                    $button = ' <a style="color:#1970F1;" class="btn btn-sm btn-adjust-qty" data-id="'. $product->id .'">Adjust</a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.inventory.stock-adjustment.index');
    }

    public function adjust(){

        $action = Input::get('rdo_addless');
        $product_id = Input::input('product_id');
        $at = new AuditTrail;
 
         if($action == 'add'){
 
             $stockad = new StockAdjustment;
             $stockad->product_code = Input::input('product_code');
             $stockad->qty_adjusted = Input::input('qty_to_adjust');
             $stockad->action = 'add';
             if(Input::input('remarks') == 'Others'){
                $stockad->remarks = Input::input('others');
             }
             else{
                $stockad->remarks = Input::input('remarks');
             }
             $stockad->save();
             $this->updateStock($action, $product_id, $stockad->qty_adjusted);
             $at->audit($this->module, 'Add Stock Adjustment With ID: P-00000' . $product_id);
         }
         else if($action == 'less'){
             $stockad = new StockAdjustment;
 
             $stockad->product_code = Input::input('product_code');
             $stockad->qty_adjusted = Input::input('qty_to_adjust');
             $stockad->action = 'less';
             if(Input::input('remarks') == 'Others'){
                $stockad->remarks = Input::input('others');
             }
             else{
                $stockad->remarks = Input::input('remarks');
             }
             $stockad->save();
             $this->updateStock($action, $product_id, $stockad->qty_adjusted);
             $at->audit($this->module, 'Less Stock Adjustment With ID: P-00000' . $product_id);
         }
     }
 
     public function updateStock($action, $product_id, $qty_adjusted){
         if($action == 'add'){ 
             Product::where('id', $product_id)
             ->update(array(
                 'qty' => DB::raw('qty + '. $qty_adjusted .'')));
         }
         else if($action == 'less'){
            Product::where('id', $product_id)
             ->update(array(
                 'qty' => DB::raw('qty - '. $qty_adjusted .'')));
         }
     }
}
