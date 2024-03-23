<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Auth;
use Input;
use DB;
use Session;

class CartController extends Controller
{
    public function index()
    {
        $cms = DB::table('cms')->where('id', 1)->first();
        Session::put('cms_name', $cms->name);
        Session::put('cms_theme_color', $cms->theme_color);
        Session::put('cms_undraw_img', $cms->undraw_img);
        return view('cart');
    }

    public function readCart(Cart $cart)
    { 
        return $cart->readCart();
    }

    public function addToCart()
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $input = Input::all();
            $product_code = $input['product_code'];
            $qty = $input['qty'];
            $amount = $input['amount'];
            $pre_order = $input['is_pre_order'];

            //return dd($this->isProductOutofStock($product_code));

            if($this->isProductOutofStock($product_code) == true){
                if($this->isProductExists($product_code, $user_id) == true){
                    if($this->isProductGreaterThanStockCart($product_code, $qty) == true){
                        return response()->json([
                                    'status' =>  'greater2',
                                    'data' => 'The Product Qty is Greater Than Stock in your Cart'
                                ], 200);
                    }
                    else{
                        Cart::where([
                            ['user_id', $user_id],
                            ['product_code', $product_code],
                        ])
                            ->update([
                                'amount' => DB::raw('amount + '. $amount .''),
                                'qty' => DB::raw('qty + '. $qty),
                                'pre_order' => DB::raw('pre_order + '. $pre_order)
                            ]);
                            
                            return response()->json([
                                'status' =>  'success',
                                'data' => $input
                            ], 200);
                    }
                    }
                    else
                    {
                    Cart::create([
                            'user_id' => $user_id,
                            'product_code' => $product_code, 
                            'qty' => $qty,
                            'amount' => $amount,
                            'pre_order' => $pre_order,
                        ]);
        
                        return response()->json([
                            'status' =>  'success',
                            'data' => $input
                        ], 200);
                    }
                //return dd($product_code);
            }
            else if($this->isProductGreaterThanStock($product_code, $qty) == true){
                return response()->json([
                    'status' =>  'greater',
                    'data' => 'The Product Qty is Greater Than Stock'
                ], 200);
                //return dd($product_code);
            }
            // else if($this->isProductGreaterThanStockCart($product_code, $qty) == true){
            //     return response()->json([
            //         'status' =>  'greater2',
            //         'data' => 'The Product Qty is Greater Than Stock in your Cart'
            //     ], 200);
            //     //return dd($product_code);
            // }
            else{
                if($this->isProductExists($product_code, $user_id) == true){
                    if($this->isProductGreaterThanStockCart($product_code, $qty) == true){
                        return response()->json([
                                    'status' =>  'greater2',
                                    'data' => 'The Product Qty is Greater Than Stock in your Cart'
                                ], 200);
                    }
                    else{
                        Cart::where([
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
                    }
                    else
                    {
                    Cart::create([
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
            }

            return response()->json([
                'status' =>  'fail',
                'data' => $input
            ], 200);
        }
        else {
            $input = Input::all();
            $stock = $input['stock'];
            if($stock == 0){
                return response()->json([
                    'status' =>  'not_auth_qty',
                    'message' => 'login first',
                    'qty' => $stock
                ], 200);
            }
            else{
                return response()->json([
                    'status' =>  'not_auth',
                    'message' => 'login first',
                    'qty' => $stock
                ], 200);
            }
        }
    }

    public function isProductExists($product_code, $user_id){
        $cart = Cart::where([
                ['user_id', $user_id],
                ['product_code', $product_code]
            ])->get();

        return $cart->count() > 0 ? true : false;
    }

    public function isProductOutofStock($product_code){
        $product = substr($product_code, 2);
        $cart = Product::where([
                ['qty', 0],
                ['id', $product]
            ])->get();

       return $cart->count() > 0 ? true : false;
       //return $cart;
    }

    public function isProductGreaterThanStock($product_code,$qty){
        $product = substr($product_code, 2);
    //     $cart = Product::where([
    //             ['qty', 0],
    //             ['id', $product]
    //         ])->get();

    //    return $cart->count() > 0 ? true : false;
        $stock = DB::table('product')->select('qty')->where('id', $product)->first();
        if($qty > $stock->qty){
        return true;
        }
        else{
        return false;
        }

       //return $cart;
    }

    public function isProductGreaterThanStockCart($product_code,$qty){
        $product = substr($product_code, 2);
    //     $cart = Product::where([
    //             ['qty', 0],
    //             ['id', $product]
    //         ])->get();

    //    return $cart->count() > 0 ? true : false;
        $stock = DB::table('product')->select('qty')->where('id', $product)->first();
        $qty2 = DB::table('cart')->select('qty')->where('product_code', $product_code)->where('user_id', Auth::user()->id)->first();
        if($qty2->qty >= $stock->qty){
        return true;
        }
        else if($qty2->qty + $qty > $stock->qty){
            return true;
        }
        else{
        return false;
        }

       //return $cart;
    }

    public function cartCount(){
        return Cart::where('user_id', Auth::id())->count();
    }

    public function cartTotal(){
        return Cart::where('user_id', Auth::id())->sum('amount');
    }

    public function removeItem($id){

        $cart = Cart::where('id', $id);
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
        $qty2 = DB::table('cart')->select('qty')->where('product_code', $product_id)->where('user_id', Auth::user()->id)->first();
          

        
        if($qty > $stock->qty){
            return response()->json([
                'status' =>  'greater',
                'data' => $qty2->qty
            ], 200);
        }
        else if($qty == 0 || $qty == null){
            Cart::where('id', $id)
            ->delete();
        }
        else {
            Cart::where('id', $id)
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
