<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Feedback;
use Auth;
use DB;
use Session;

class MyReservationController extends Controller
{
    public function index(Order $order)
    {
        $cms = DB::table('cms')->where('id', 1)->first();
        Session::put('cms_name', $cms->name);
        Session::put('cms_theme_color', $cms->theme_color);
        Session::put('cms_undraw_img', $cms->undraw_img);
        $user_id = Auth::id();
        $orders = $order->readReservation($user_id);

        return view('reservation', compact('orders'));
    }
}
