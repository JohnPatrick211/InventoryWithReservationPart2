<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\SupplierDelivery;
use App\Models\Product;
use App\Models\Order;
use App\Models\PurchaseOrder;
use DB;
use Input;

class SupplierDeliveryController extends Controller
{
    public function index()
    {
       // $product = Product::where('status', 1)->where('qty',0)->get();
        $product = DB::table('orders as O')
        ->select('O.*', 'P.description','P.id as product_ids')
        // ->where('O.product_code', $product_code)
        ->leftJoin('product as P', DB::raw('CONCAT(prefix, P.id)'), '=', 'O.product_code')
        ->where('O.reservation', 1)
        ->where('O.status', 5)
        ->get();
        $supplier = Supplier::where('status', 1)->get();
        return view('admin.inventory.supplier-delivery.index',['supplier' => $supplier, 'product' => $product]);
    }

    public function computeReservationQty(){
        $input = Input::all();
        $product_code = $input['product_id'];

        $data = new Order;
        return $data->computeReservationQty($product_code);
    }

    public function readSupplierDelivery(Request $request)
    {
        $product = new SupplierDelivery;
        $product = $product->readSupplierDelivery($request->supplier_id, $request->date_from, $request->date_to);
        if(request()->ajax())
        { 
            return datatables()->of($product)
            ->rawColumns(['action'])
            ->make(true);
        }

    }

    public function readSupplierDeliveryPartial(Request $request)
    {
        $product = new SupplierDelivery;
        $product = $product->readSupplierDeliveryPartial($request->supplier_id, $request->date_from, $request->date_to);
        if(request()->ajax())
        { 
            return datatables()->of($product)
            ->addColumn('action', function($data){
                if ($data->remarks == 'Partially Completed') {
                    $button = '<a class="btn btn-sm btn-outline-success btn-show-order"
                    data-toggle="modal" data-target="#delivery-modal" 
                    data-id="'. $data->id .'">Add delivery</a>';
                    return $button;
                }
            })
            ->rawColumns(['action'])
            ->make(true);
        }

    }

    public function createDelivery(){

        $data = Input::all();
        $qty_delivered = "";
        if (request()->active_tab == 'partial') {
            SupplierDelivery::where('id', request()->data_id)
            ->update([
                'qty_delivered' => DB::raw('qty_delivered + '. request()->qty_delivered .'')
            ]);

            $qty_delivered = SupplierDelivery::where('id', request()->data_id)->value('qty_delivered');
           // $qty_delivered = (int)$qty_delivered + (int)request()->qty_delivered;
            $remarks = $this->validateDeliveredQty($data['po_no'], $data['product_code'], $qty_delivered, $data['date_reservation']);

            SupplierDelivery::where('id', request()->data_id)
            ->update([
                'remarks' => $remarks
            ]);
        }
        else {
            $s = new SupplierDelivery;
            $s->po_id = $data['data_id'];
            $s->po_no = $data['po_no'];
            $s->product_code = $data['product_code'];
            $s->qty_delivered = $data['qty_delivered'];
            $s->date_delivered = $data['date_recieved'];
            $qty_delivered = $data['qty_delivered'];

            $remarks = $this->validateDeliveredQty($data['po_no'], $data['product_code'], $qty_delivered, $data['date_reservation']);
            $s->remarks = $remarks;
            $s->save();

        }

        $this->updatePurchaseOrder($data['po_no'], $data['product_code'], $remarks);
        $this->updateInventory($data['product_code'], $data['qty_delivered']); 
    }

    public function updatePurchaseOrder($po_no, $product_code, $remarks){
        $status = 3;
    //    $remarks = "Pending";
        if($remarks == 'Partially Completed'){
            $status = 3;
            $remarks = "Partially Completed";
        }
        else if($remarks == 'Completed'){
            $status = 4;
            $remarks = "Completed";
        }
    
        DB::table('purchase_order as PO')
            ->where('PO.product_code', '=', $product_code)
            ->where(DB::raw('CONCAT(PO.prefix, PO.po_no)'), '=', $po_no)
            ->update([
                'PO.status' => $status,
                'PO.remarks' => $remarks
            ]);
    }

    public function validateDeliveredQty($po_no, $product_code, $qty_delivered, $date_deliver){
        $qty_order = DB::table('purchase_order as PO')
        ->where(DB::raw('CONCAT(PO.prefix, PO.po_no)'), $po_no)
        ->where('product_code', $product_code)
        ->value('qty_order');
        
        $res = '';
        if($qty_order == $qty_delivered){
            $res = 'Completed';

           $reservationcount =  DB::table('orders as O')->where('O.product_code', $product_code)
           ->where('O.reservation', 1)
           ->where('O.status', 5)
           ->sum('qty');

           DB::table('product as P')
            ->where(DB::raw('CONCAT(P.prefix, P.id)'), '=',  $product_code)
            ->update(array(
                'P.qty' => DB::raw('P.qty - '. $reservationcount .'')));

           DB::table('orders as O')
            ->where('O.product_code', '=', $product_code)
            ->where('O.reservation',1)
            ->update([
                'O.status' => 2,
                'O.delivery_date' => $date_deliver,
            ]);     

        }
        else if($qty_order > $qty_delivered){
            $res = 'Partially Completed';
        }
        
        return $res;
    }

    public function updateInventory($product_code, $qty_delivered)
    {
        DB::table('product as P')
            ->where(DB::raw('CONCAT(P.prefix, P.id)'), '=',  $product_code)
            ->update(array(
                'P.qty' => DB::raw('P.qty + '. $qty_delivered .'')));
        
    }


}
