<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $walk_in_sales = Sales::whereRaw('Date(created_at) = CURDATE()')->where('order_from', 'walk-in')->sum('amount');
        $online_sales = Sales::whereRaw('Date(created_at) = CURDATE()')->sum('amount');
        $orders_today = Order::whereRaw('Date(created_at) = CURDATE()')->where('status','!=',5)->count('order_no');
        $preorder = Order::whereRaw('Date(created_at) = CURDATE()')->where('pre_order',1)->count('order_no');
        $cashiering_today = Sales::whereRaw('Date(created_at) = CURDATE()')->where('order_from', 'walk-in')->count('id');
        $reservation = DB::table('orders as O')
        ->select('O.*', 'O.created_at as date_order', 'users.*', 'UA.map', 'users.id_type', 'O.status as order_status')
        ->leftJoin('order_shipping_fee as S', 'S.order_no', '=', 'O.order_no')
        ->leftJoin('users', 'users.id', '=', 'O.user_id')
        ->leftJoin('user_address as UA', 'UA.user_id', '=', 'O.user_id')
        ->where('O.status', 5)
        ->orderBy('O.id', 'desc')
        ->count();
        $total_users = User::count('id');
        $reorder_count = DB::table('product as P')
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
        ->where('P.qty', '!=', 0)
        ->count();
        return view('admin.dashboard', compact('walk_in_sales', 'online_sales', 'orders_today', 'total_users','reorder_count','reservation','cashiering_today','preorder'));
    }
}
