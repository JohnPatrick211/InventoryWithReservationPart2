<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Supplier;
use DB;
use Session;

class HomePageController extends Controller
{
    public function index()
    {
        $cms = DB::table('cms')->where('id', 1)->first();
        Session::put('cms_name', $cms->name);
        Session::put('cms_theme_color', $cms->theme_color);
        Session::put('cms_undraw_img', $cms->undraw_img);
        Session::put('cms_logo', $cms->logo);
        $product = new Product;
        $product = $product->readAllProduct();
        $categories = Category::where('status', 1)->get();
        return view('index', compact('product', 'categories'));
    }

    public function readAllProduct()
    {
        $product = new Product;
        return $product->readAllProduct();

    }

    public function searchProduct()
    {
        $product = new Product;
        return $product->seachProduct();
    }

    public function readProductByCategory($category_id)
    {
        $product = new Product;
        return $product->readProductByCategory($category_id);
    }
}

