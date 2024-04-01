<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class Order extends Model
{


    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'order_no',
        'product_code',
        'qty',
        'amount',
        'status',
        'payment_method',
        'delivery_date'
    ];

    public function readOrders($user_id)
    {
        return DB::table($this->table . ' as O')
            ->select(
                "O.amount",
                'O.qty',
                DB::raw('CONCAT(prefix, P.id) as product_code'),
                'O.order_no',
                'O.payment_method',
                'O.created_at',
                'O.delivery_date',
                'P.image',
                'P.description',
                'selling_price',
                'U.name as unit',
                'S.shipping_fee',
                'O.status'
            )
            ->leftJoin('product as P', DB::raw('CONCAT(prefix, P.id)'), '=', 'O.product_code')
            ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
            ->leftJoin('order_shipping_fee as S', 'S.order_no', '=', 'O.order_no')
            ->where('O.user_id', $user_id)
            ->where('O.status', '!=', 5)
            ->where('O.reservation', '!=', 1)
            ->groupBy(
                'O.order_no',
                'O.amount',
                'O.qty',
                'P.prefix',
                'P.id',
                'P.description',
                'P.image',
                'O.delivery_date',
                'O.payment_method',
                'selling_price',
                'U.name',
                'O.created_at',
                'S.shipping_fee',
                'O.status'
            )
            ->orderBy('O.id', 'desc')
            ->get();
    }

    public function readReservation($user_id)
    {
        return DB::table($this->table . ' as O')
            ->select(
                "O.amount",
                'O.qty',
                DB::raw('CONCAT(prefix, P.id) as product_code'),
                'O.order_no',
                'O.payment_method',
                'O.created_at',
                'O.delivery_date',
                'P.image',
                'P.description',
                'selling_price',
                'U.name as unit',
                'S.shipping_fee',
                'O.status'
            )
            ->leftJoin('product as P', DB::raw('CONCAT(prefix, P.id)'), '=', 'O.product_code')
            ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
            ->leftJoin('order_shipping_fee as S', 'S.order_no', '=', 'O.order_no')
            ->where('O.user_id', $user_id)
            ->where('O.reservation', 1)
            ->groupBy(
                'O.order_no',
                'O.amount',
                'O.qty',
                'P.prefix',
                'P.id',
                'P.description',
                'P.image',
                'O.delivery_date',
                'O.payment_method',
                'selling_price',
                'U.name',
                'O.created_at',
                'S.shipping_fee',
                'O.status'
            )
            ->orderBy('O.id', 'desc')
            ->get();
    }

    public function readReservationReports($date_from, $date_to, $order_from, $payment_method)
    {
        if ($payment_method == 'All') {
            return DB::table($this->table . ' as O')
                ->select(
                    "O.amount",
                    'O.qty',
                    DB::raw('CONCAT(prefix, P.id) as product_code'),
                    'O.order_no',
                    'O.payment_method',
                    'O.created_at',
                    'O.delivery_date',
                    'P.image',
                    'P.description',
                    'selling_price',
                    'U.name as unit',
                    'S.shipping_fee',
                    'O.status'
                )
                ->leftJoin('product as P', DB::raw('CONCAT(prefix, P.id)'), '=', 'O.product_code')
                ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
                ->leftJoin('order_shipping_fee as S', 'S.order_no', '=', 'O.order_no')
                ->where('O.reservation', 1)
                ->whereBetween(DB::raw('DATE(O.created_at)'), [$date_from, $date_to])
                ->groupBy(
                    'O.order_no',
                    'O.amount',
                    'O.qty',
                    'P.prefix',
                    'P.id',
                    'P.description',
                    'P.image',
                    'O.delivery_date',
                    'O.payment_method',
                    'selling_price',
                    'U.name',
                    'O.created_at',
                    'S.shipping_fee',
                    'O.status'
                )
                ->orderBy('O.id', 'desc')
                ->get();
        } else {
            return DB::table($this->table . ' as O')
                ->select(
                    "O.amount",
                    'O.qty',
                    DB::raw('CONCAT(prefix, P.id) as product_code'),
                    'O.order_no',
                    'O.payment_method',
                    'O.created_at',
                    'O.delivery_date',
                    'P.image',
                    'P.description',
                    'selling_price',
                    'U.name as unit',
                    'S.shipping_fee',
                    'O.status'
                )
                ->leftJoin('product as P', DB::raw('CONCAT(prefix, P.id)'), '=', 'O.product_code')
                ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
                ->leftJoin('order_shipping_fee as S', 'S.order_no', '=', 'O.order_no')
                ->where('O.payment_method', $payment_method)
                ->where('O.reservation', 1)
                ->whereBetween(DB::raw('DATE(O.created_at)'), [$date_from, $date_to])
                ->groupBy(
                    'O.order_no',
                    'O.amount',
                    'O.qty',
                    'P.prefix',
                    'P.id',
                    'P.description',
                    'P.image',
                    'O.delivery_date',
                    'O.payment_method',
                    'selling_price',
                    'U.name',
                    'O.created_at',
                    'S.shipping_fee',
                    'O.status'
                )
                ->orderBy('O.id', 'desc')
                ->get();
        }

    }

    public function readPreOrder($user_id)
    {
        return DB::table($this->table . ' as O')
            ->select(
                "O.amount",
                'O.qty',
                DB::raw('CONCAT(prefix, P.id) as product_code'),
                'O.order_no',
                'O.payment_method',
                'O.created_at',
                'O.delivery_date',
                'P.image',
                'P.description',
                'selling_price',
                'U.name as unit',
                'S.shipping_fee',
                'O.status'
            )
            ->leftJoin('product as P', DB::raw('CONCAT(prefix, P.id)'), '=', 'O.product_code')
            ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
            ->leftJoin('order_shipping_fee as S', 'S.order_no', '=', 'O.order_no')
            ->where('O.user_id', $user_id)
            ->where('O.pre_order', 1)
            ->groupBy(
                'O.order_no',
                'O.amount',
                'O.qty',
                'P.prefix',
                'P.id',
                'P.description',
                'P.image',
                'O.delivery_date',
                'O.payment_method',
                'selling_price',
                'U.name',
                'O.created_at',
                'S.shipping_fee',
                'O.status'
            )
            ->orderBy('O.id', 'desc')
            ->get();
    }

    public function readOrdersByStatus($status)
    {

        $data = DB::table($this->table . ' as O')
            ->select('O.*', 'O.created_at as date_order', 'users.*', 'UA.map', 'users.id_type', 'O.status as order_status')
            ->leftJoin('order_shipping_fee as S', 'S.order_no', '=', 'O.order_no')
            ->leftJoin('users', 'users.id', '=', 'O.user_id')
            ->leftJoin('user_address as UA', 'UA.user_id', '=', 'O.user_id')
            ->where('O.status', $status)
            // ->orWhere('O.reservation', 1)
            ->orderBy('O.id', 'desc')
            ->get();



        return $data->unique('order_no');
    }

    public function readOneOrder($order_no)
    {
        return DB::table($this->table . ' as O')
            ->select('O.*', 'P.description', 'P.selling_price', 'U.name as unit', 'O.qty as qty', 'O.created_at as date_order', 'users.*', 'S.shipping_fee')
            ->leftJoin('product as P', DB::raw('CONCAT(prefix, P.id)'), '=', 'O.product_code')
            ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
            ->leftJoin('order_shipping_fee as S', 'S.order_no', '=', 'O.order_no')
            ->leftJoin('users', 'users.id', '=', 'O.user_id')
            ->where('O.order_no', $order_no)
            ->get();
    }

    public function getTotalAmount($order_no)
    {
        return DB::table('orders')->where('order_no', $order_no)->sum('amount');
    }

    public function computeReservationQty($product_code)
    {
        // $product_code = 'P-00000' . $product_code;
        return DB::table($this->table . ' as O')
            // ->where('O.product_code', $product_code)
            ->where('O.product_code', 'like', '%' . $product_code . '%')
            ->where('O.reservation', 1)
            ->where('O.status', 5)
            ->sum('qty');

        // return dd(DB::table($this->table . ' as O')
        // // ->where('O.product_code', $product_code)
        // ->where('O.product_code', 'like', '%' . $product_code . '%')
        // ->where('O.reservation', 1)
        // ->where('O.status', 5)
        // ->sum('qty'));
    }



}
