<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\ReservationBook;
use App\Models\Product;
use Auth;
use Input;
use DB;
use Session;

class ReservationBookController extends Controller
{
    public function index()
    {
        $cms = DB::table('cms')->where('id', 1)->first();
        Session::put('cms_name', $cms->name);
        Session::put('cms_theme_color', $cms->theme_color);
        Session::put('cms_undraw_img', $cms->undraw_img);
        return view('reservationbook');
    }

    public function readBook(ReservationBook $book)
    { 
        return $book->readBook();
    }

    public function addToReservation()
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $input = Input::all();
            $product_code = $input['product_code'];
            $qty = $input['qty'];
            $amount = $input['amount'];

            //return dd($this->isProductOutofStock($product_code));

                if($this->isProductExists($product_code, $user_id) == true){
                        ReservationBook::where([
                            ['user_id', $user_id],
                            ['product_code', $product_code]
                        ])
                            ->update([
                                'amount' => DB::raw('amount + '. $amount .''),
                                'qty' => DB::raw('qty + '. $qty)
                            ]);
                            
                            return response()->json([
                                'status' =>  'success',
                                'data' => $input
                            ], 200);
                }
                else
                    {
                    ReservationBook::create([
                            'user_id' => $user_id,
                            'product_code' => $product_code, 
                            'qty' => $qty,
                            'amount' => $amount
                        ]);
        
                        return response()->json([
                            'status' =>  'success',
                            'data' => $input
                        ], 200);
                    }
            

            return response()->json([
                'status' =>  'fail',
                'data' => $input
            ], 200);
        }
        else {
            return response()->json([
                'status' =>  'not_auth',
                'message' => 'login first'
            ], 200);
        }
    }

    public function isProductExists($product_code, $user_id){
        $cart = ReservationBook::where([
                ['user_id', $user_id],
                ['product_code', $product_code]
            ])->get();

        return $cart->count() > 0 ? true : false;
    }

    public function readBookCount(){
        return ReservationBook::where('user_id', Auth::id())->count();
    }

    public function reservationTotal(){
        return ReservationBook::where('user_id', Auth::id())->sum('amount');
    }

    public function removeItem($id){

        $cart = ReservationBook::where('id', $id);
        if ($cart->delete()) {
            return response()->json([
                'status' =>  'success',
                'message' => 'remove success'
            ], 200);
        }
        
        return response()->json([
            'status' =>  'not_auth',
            'message' => 'fail',
            'message' => 'remove fail'
        ], 200);
    }

    public function changeQuantity(){

        $input = Input::all();
        $id = $input['id'];
        $qty = $input['qty'];
        $amount = $input['amount'];
        $product_id = $input['product_id'];

        $product = substr($product_id, 2);
        $stock = DB::table('product')->select('qty')->where('id', $product)->first();
        $qty2 = DB::table('reservationbook')->select('qty')->where('product_code', $product_id)->where('user_id', Auth::user()->id)->first();
          

        
        if($qty == 0 || $qty == null){
            ReservationBook::where('id', $id)
            ->delete();
        }
        else {
            ReservationBook::where('id', $id)
            ->update([
                'amount' => $amount,
                'qty' => $qty
            ]);
        }
            
        return response()->json([
            'status' =>  'success',
            'data' => $input
        ], 200);
    }
}
