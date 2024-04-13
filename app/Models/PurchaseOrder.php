<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class PurchaseOrder extends Model
{
    

    protected $table = 'purchase_order';

    protected $fillable = [
        'prefix',
        'po_no',
        'product_code',
        'qty_order',
        'amount',
        'remarks',
        'status'
    ];

    public function readReorderBySupplier($supplier_id){

        if($supplier_id == 'All'){
            return DB::table('product as P')
            ->select("P.*", DB::raw('CONCAT(prefix, P.id) as product_code'),
                    'description',
                    'reorder', 
                    'qty', 
                    'U.name as unit', 
                    'S.supplier_name as supplier', 
                    'C.name as category'
                    )
            ->leftJoin('supplier as S', 'S.id', '=', 'P.supplier_id')
            ->leftJoin('category as C', 'C.id', '=', 'P.category_id')
            ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
            ->where('P.status', 1)
            ->whereColumn('P.reorder','>=', 'P.qty')
            ->orderBy('P.updated_at', 'desc')
            ->get();
        }
        else{
            return DB::table('product as P')
            ->select("P.*", DB::raw('CONCAT(prefix, P.id) as product_code'),
                    'description',
                    'reorder', 
                    'qty', 
                    'U.name as unit', 
                    'S.supplier_name as supplier', 
                    'C.name as category'
                    )
            ->leftJoin('supplier as S', 'S.id', '=', 'P.supplier_id')
            ->leftJoin('category as C', 'C.id', '=', 'P.category_id')
            ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
            ->where('P.status', 1)
            ->where('P.supplier_id', $supplier_id)
            ->whereColumn('P.reorder','>=', 'P.qty')
            ->orderBy('P.updated_at', 'desc')
            ->get();
        }
    }

    public function readRequestOrderBySupplier($supplier_id){
        if($supplier_id == 'All'){
            return DB::table('purchase_order AS PO')
            ->select("PO.*",
                    'P.description',
                    'P.orig_price',
                    'U.name as unit', 
                    'S.supplier_name as supplier', 
                    'C.name as category')
            ->leftJoin('product as P', DB::raw('CONCAT(P.prefix, P.id)'), '=', 'PO.product_code')
            ->leftJoin('supplier as S', 'S.id', '=', 'P.supplier_id')
            ->leftJoin('category as C', 'C.id', '=', 'P.category_id')
            ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
            ->where('PO.status', 1)
            ->get();
        }
        else{
            return DB::table('purchase_order AS PO')
        ->select("PO.*",
                'P.description',
                'P.orig_price',
                'U.name as unit', 
                'S.supplier_name as supplier', 
                'C.name as category')
        ->leftJoin('product as P', DB::raw('CONCAT(P.prefix, P.id)'), '=', 'PO.product_code')
        ->leftJoin('supplier as S', 'S.id', '=', 'P.supplier_id')
        ->leftJoin('category as C', 'C.id', '=', 'P.category_id')
        ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
        ->where('P.supplier_id', $supplier_id)
        ->where('PO.status', 1)
        ->get();
        }
    }

    public function readPurchasedOrder($supplier_id, $date_from, $date_to){
        if($supplier_id == 'All'){
            return DB::table('purchase_order AS PO')
            ->select('PO.*', 'P.*',
                    'PO.id as id',
                    'U.name as unit', 
                    'S.supplier_name as supplier', 
                    'C.name as category',
                    DB::raw('CONCAT(PO.prefix, PO.po_no) as po_num'),
                    DB::raw('PO.updated_at as date_order'))
            ->leftJoin('product as P', DB::raw('CONCAT(P.prefix, P.id)'), '=', 'PO.product_code')
            ->leftJoin('supplier as S', 'S.id', '=', 'P.supplier_id')
            ->leftJoin('category as C', 'C.id', '=', 'P.category_id')
            ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
            ->where('PO.status', 2)
            ->whereBetween(DB::raw('DATE(PO.updated_at)'), [$date_from, $date_to])
            ->orderBy('date_order', 'desc')
            ->get();
        }
        else{
            return DB::table('purchase_order AS PO')
            ->select('PO.*', 'P.*',
                    'PO.id as id',
                    'U.name as unit', 
                    'S.supplier_name as supplier', 
                    'C.name as category',
                    DB::raw('CONCAT(PO.prefix, PO.po_no) as po_num'),
                    DB::raw('PO.updated_at as date_order'))
            ->leftJoin('product as P', DB::raw('CONCAT(P.prefix, P.id)'), '=', 'PO.product_code')
            ->leftJoin('supplier as S', 'S.id', '=', 'P.supplier_id')
            ->leftJoin('category as C', 'C.id', '=', 'P.category_id')
            ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
            ->where('P.supplier_id', $supplier_id)
            ->where('PO.status', 2)
            ->whereBetween(DB::raw('DATE(PO.updated_at)'), [$date_from, $date_to])
            ->orderBy('date_order', 'desc')
            ->get();
        }
    }

    public function readPendingOrder($supplier_id, $date_from, $date_to){
        if($supplier_id == 'All'){
            return DB::table('supplier_delivery AS SD')
            ->select('SD.*', 'P.*',
                    'SD.remarks',
                    'SD.id as id',
                    'SD.updated_at as updated',
                    'PO.qty_order',
                    'U.name as unit', 
                    'S.supplier_name as supplier', 
                    'C.name as category',
                    DB::raw('CONCAT(SD.prefix, SD.id) as del_no'),
                    DB::raw('SUM(PO.qty_order, SD.qty_delivered) as remaining_orders'),
                    'SD.date_delivered')
            ->leftJoin('product as P', DB::raw('CONCAT(P.prefix, P.id)'), '=', 'SD.product_code')
            ->leftJoin('purchase_order AS PO', 'PO.id', '=', 'SD.po_id')
            ->leftJoin('supplier as S', 'S.id', '=', 'P.supplier_id')
            ->leftJoin('category as C', 'C.id', '=', 'P.category_id')
            ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
            ->where('PO.status', 3)
            ->whereBetween(DB::raw('DATE(SD.date_delivered)'), [$date_from, $date_to])
            ->get();
        }
        else{
            return DB::table('supplier_delivery AS SD')
            ->select('SD.*', 'P.*',
                    'SD.remarks',
                    'SD.id as id',
                    'SD.updated_at as updated',
                    'PO.qty_order',
                    'U.name as unit', 
                    'S.supplier_name as supplier', 
                    'C.name as category',
                    DB::raw('CONCAT(SD.prefix, SD.id) as del_no'),
                    'SD.date_delivered')
            ->leftJoin('product as P', DB::raw('CONCAT(P.prefix, P.id)'), '=', 'SD.product_code')
            ->leftJoin('purchase_order AS PO', 'PO.id', '=', 'SD.po_id')
            ->leftJoin('supplier as S', 'S.id', '=', 'P.supplier_id')
            ->leftJoin('category as C', 'C.id', '=', 'P.category_id')
            ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
            ->where('P.supplier_id', $supplier_id)
            ->where('PO.status', 3)
            ->whereBetween(DB::raw('DATE(SD.date_delivered)'), [$date_from, $date_to])
            ->get();
        }
    
    }

    public function readPurchaseOrder(){
            return DB::table('product as P')
            ->select("P.*", DB::raw('CONCAT(prefix, P.id) as product_code'),
                    'description',
                    'reorder', 
                    'qty', 
                    'U.name as unit', 
                    'S.supplier_name as supplier', 
                    'C.name as category'
                    )
            ->leftJoin('supplier as S', 'S.id', '=', 'P.supplier_id')
            ->leftJoin('category as C', 'C.id', '=', 'P.category_id')
            ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
            ->where('P.status', 1)
            ->orderBy('P.updated_at', 'desc')
            ->get();
        
      
    }

    public function readPurchasedOrderInPurchase($supplier_id, $date_from, $date_to){
        if($supplier_id == 'All'){
            return DB::table('purchase_order AS PO')
            ->select('PO.*', 'P.*',
                    'PO.id as id',
                    'U.name as unit', 
                    'S.supplier_name as supplier', 
                    'C.name as category',
                    DB::raw('CONCAT(PO.prefix, PO.po_no) as po_num'),
                    DB::raw('PO.updated_at as date_order'))
            ->leftJoin('product as P', DB::raw('CONCAT(P.prefix, P.id)'), '=', 'PO.product_code')
            ->leftJoin('supplier as S', 'S.id', '=', 'P.supplier_id')
            ->leftJoin('category as C', 'C.id', '=', 'P.category_id')
            ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
            ->where('PO.status', '!=', 1)
            ->whereBetween(DB::raw('DATE(PO.updated_at)'), [$date_from, $date_to])
            ->orderBy('date_order', 'desc')
            ->get();
        }
        else{
            return DB::table('purchase_order AS PO')
            ->select('PO.*', 'P.*',
                    'PO.id as id',
                    'U.name as unit', 
                    'S.supplier_name as supplier', 
                    'C.name as category',
                    DB::raw('CONCAT(PO.prefix, PO.po_no) as po_num'),
                    DB::raw('PO.updated_at as date_order'))
            ->leftJoin('product as P', DB::raw('CONCAT(P.prefix, P.id)'), '=', 'PO.product_code')
            ->leftJoin('supplier as S', 'S.id', '=', 'P.supplier_id')
            ->leftJoin('category as C', 'C.id', '=', 'P.category_id')
            ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
            ->where('P.supplier_id', $supplier_id)
            ->where('PO.status', '!=', 1)
            ->whereBetween(DB::raw('DATE(PO.updated_at)'), [$date_from, $date_to])
            ->orderBy('date_order', 'desc')
            ->get();
        }
    }

    public function readPurchasedOrderBySupplier($supplier_id,$po){
        if($supplier_id == 'All'){
            return DB::table('purchase_order AS PO')
            ->select('PO.*', 'P.*',
                    'PO.id as id',
                    'U.name as unit', 
                    'S.supplier_name as supplier', 
                    'C.name as category',
                    DB::raw('CONCAT(PO.prefix, PO.po_no) as po_num'),
                    DB::raw('PO.updated_at as date_order'))
            ->leftJoin('product as P', DB::raw('CONCAT(P.prefix, P.id)'), '=', 'PO.product_code')
            ->leftJoin('supplier as S', 'S.id', '=', 'P.supplier_id')
            ->leftJoin('category as C', 'C.id', '=', 'P.category_id')
            ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
            ->where('PO.status', '!=', 1)
            ->where('PO.id', $po)
            ->orderBy('date_order', 'desc')
            ->get();
        }
        else{
            return DB::table('purchase_order AS PO')
            ->select('PO.*', 'P.*',
                    'PO.id as id',
                    'U.name as unit', 
                    'S.supplier_name as supplier', 
                    'C.name as category',
                    DB::raw('CONCAT(PO.prefix, PO.po_no) as po_num'),
                    DB::raw('PO.updated_at as date_order'))
            ->leftJoin('product as P', DB::raw('CONCAT(P.prefix, P.id)'), '=', 'PO.product_code')
            ->leftJoin('supplier as S', 'S.id', '=', 'P.supplier_id')
            ->leftJoin('category as C', 'C.id', '=', 'P.category_id')
            ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
            ->where('P.supplier_id', $supplier_id)
            ->where('PO.status', '!=', 1)
            ->where('PO.id', $po)
            ->orderBy('date_order', 'desc')
            ->get();

        }
    }
}
