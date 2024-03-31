<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Replacement;
use App\Models\StockAdjustment;
use App\Models\SupplierDelivery;
use DB;

class ArchiveController extends Controller
{
   public function index() {
        return view('admin.utilities.archive.index');
   }

   public function readArchiveProduct()
   {
        $product = new Product;
        $product = $product->readArchiveProduct(request()->date_from, request()->date_to);
        if(request()->ajax())
        {
            return datatables()->of($product)       
            ->addColumn('action', function($product)
            {
                $button = ' <a class="btn btn-sm btn-restore" data-id="'. $product->id .'"><i class="fa fa-recycle"></i></a>';
                return $button;
            })
            ->addColumn('updated_at', function($p)
            {
                $date_time = date('F d, Y h:i A', strtotime($p->updated_at));
                return $date_time;
            })
            ->rawColumns(['action', 'updated_at'])
            ->make(true);       
        }
   }

   public function readArchiveSales()
   {
        $product = DB::table('sales as S')
        ->select('S.*', 'P.*', 'S.qty', 'S.id as id', 'S.updated_at',
                'U.name as unit', 
                DB::raw('S.created_at as date_time'))
        ->leftJoin('product as P', DB::raw('CONCAT(P.prefix, P.id)'), '=', 'S.product_code')
        ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
        ->where('S.status', -1)
        ->orderBy   ('S.invoice_no', 'desc')
        ->get();
        if(request()->ajax())
        {
            return datatables()->of($product)       
            ->addColumn('action', function($product)
            {
                $button = ' <a class="btn btn-sm btn-restore" data-id="'. $product->id .'"><i class="fa fa-recycle"></i></a>';
                return $button;
            })
            //->rawColumns(['action', 'updated_at'])
            ->make(true);       
        }
   }

   public function readArchiveReplacement()
   {
        $product = DB::table('replacement AS BR')
        ->select('BR.*', 'users.name AS studentName', 'product.description AS productName', 'BR.qty AS replacement_qty')
        ->leftJoin('users', 'BR.user_id', '=', 'users.id')
        ->leftJoin('product', 'BR.product_id', '=', 'product.id')
        ->where('BR.archive_status', 0)
        ->get();
        if(request()->ajax())
        {
            return datatables()->of($product)       
            ->addColumn('action', function($product)
            {
                $button = ' <a class="btn btn-sm btn-restore-replacement" data-id="'. $product->id .'"><i class="fa fa-recycle"></i></a>';
                return $button;
            })
            ->addColumn('status', function($data){
                $status = '<span class="badge badge-success">Approved</span>';
                if ($data->status == 2) {
                    $status = '<span class="badge badge-danger text-white">Rejected</span>';
                }
                return $status;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);       
        }
   }

   public function readStockAdjustment(){
    $product = DB::table('stock_adjustment AS SA')
    ->select('SA.*', 'P.*','SA.id as ID',
            'U.name as unit', 
            'S.supplier_name as supplier', 
            'C.name as category',
            DB::raw('SA.created_at as date_adjusted'))
    ->leftJoin('product as P', DB::raw('CONCAT(P.prefix, P.id)'), '=', 'SA.product_code')
    ->leftJoin('supplier as S', 'S.id', '=', 'P.supplier_id')
    ->leftJoin('category as C', 'C.id', '=', 'P.category_id')
    ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
    ->where('archive_status',0)
    ->orderBy('date_adjusted','desc')
    ->get();
    if(request()->ajax())
    {
        return datatables()->of($product)       
        ->addColumn('action', function($product)
        {
            $button = ' <a class="btn btn-sm btn-restore-stockadjustment" data-id="'. $product->ID .'"><i class="fa fa-recycle"></i></a>';
            return $button;
        })
        ->rawColumns(['action'])
        ->make(true);       
    }
   }

   public function readSupplierDelivery(){
    $product = DB::table('supplier_delivery AS SD')
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
    ->where('PO.status', 4)
    ->where('SD.archive_status', 0)
    ->get();
    if(request()->ajax())
    {
        return datatables()->of($product)       
        ->addColumn('action', function($product)
        {
            $button = ' <a class="btn btn-sm btn-restore-supplierdelivery" data-id="'. $product->id .'"><i class="fa fa-recycle"></i></a>';
            return $button;
        })
        ->rawColumns(['action'])
        ->make(true);       
    }
   }

   public function readArchiveUsers()
   {
        $user = User::whereBetween(DB::raw('DATE(users.updated_at)'), [request()->date_from, request()->date_to])
        ->where('status', -1)->get();
        if(request()->ajax())
        {
            return datatables()->of($user)       
            ->addColumn('action', function($user)
            {
                $button = ' <a class="btn btn-sm btn-restore" data-id="'. $user->id .'"><i class="fa fa-recycle"></i></a>';
                return $button;
            })
            ->addColumn('updated_at', function($p)
            {
                $date_time = date('F d, Y h:i A', strtotime($p->updated_at));
                return $date_time;
            })
            ->addColumn('access_level', function($p)
            {
                $access_level = "";
                switch($p->access_level) {
                    case 1:
                        $access_level = "Sales Clerk";
                        break;
                    case 2:
                        $access_level = "Inventory Clerk";
                        break;
                    case 3:
                        $access_level = "Owner";
                        break;
                    case 4:
                        $access_level = "Administrator";
                        break;
                    case 5:
                        $access_level = "Customer";
                        break;
                }

                return $access_level;
            })
            ->rawColumns(['action', 'updated_at', 'access_level'])
            ->make(true);       
        }
   }

   public function restore($id)
   {
       if (request()->object == 'product') {
            Product::where('id', $id)
            ->update([
                'status' => 1,
            ]);
       }
       else { 
            User::where('id', $id)
            ->update([
                'status' => 1,
            ]);
       }
   }

   public function restorereplacement($id)
   {
           Replacement::where('id', $id)
            ->update([
                'archive_status' => 1,
            ]);
       
   }
   public function restorestockadjustment($id){
        StockAdjustment::where('id', $id)
            ->update([
                'archive_status' => 1,
            ]);
   }

   public function restoresupplierdelivery($id){
    SupplierDelivery::where('id', $id)
        ->update([
            'archive_status' => 1,
        ]);
}
}
