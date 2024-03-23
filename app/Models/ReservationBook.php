<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class ReservationBook extends Model
{
    protected $table = 'reservationbook';

    protected $fillable = [
        'id',
        'user_id',
        'product_code',
        'qty',
        'amount'
    ];

    public function readBook(){
        return ReservationBook::where('user_id', Auth::id())
        ->select("reservationbook.*", 'P.*',
                'reservationbook.id',
                'description',
                'selling_price', 
                'reservationbook.qty', 
                'U.name as unit', 
                'C.name as category'
                )
        ->leftJoin('product as P', DB::raw('CONCAT(P.prefix, P.id)'), '=', 'reservationbook.product_code')
        ->leftJoin('category as C', 'C.id', '=', 'P.category_id')
        ->leftJoin('unit as U', 'U.id', '=', 'P.unit_id')
        ->get();
    }
}
