<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Input;
use Auth;

class Replacement extends Model
{
    protected $table = 'replacement';

    protected $fillable = [
        'user_id',
        'product_id',
        'image_receipt',
        'reason',
        'status'
    ];

    public function readAllReplacement()
    {
        return DB::table($this->table . ' as P')
            ->select("P.*", 
                    'U.description as product_name')
            ->leftJoin('product as U', 'U.id', '=', 'P.product_id')
            ->where('P.user_id', Auth::user()->id)
            ->get();
    }
}
