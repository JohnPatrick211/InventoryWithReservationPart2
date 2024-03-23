<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserAddress;
use Auth;
use DB;
use Session;

class AccountController extends Controller
{
    public function index() {
        $cms = DB::table('cms')->where('id', 1)->first();
        Session::put('cms_name', $cms->name);
        Session::put('cms_theme_color', $cms->theme_color);
        Session::put('cms_undraw_img', $cms->undraw_img);
        $user = User::where('id', Auth::id())->first();
        $address = UserAddress::where('user_id', Auth::id())->first();
        return view('account', compact('user', 'address'));
    }

    public function editAccount() {
        $cms = DB::table('cms')->where('id', 1)->first();
        Session::put('cms_name', $cms->name);
        Session::put('cms_theme_color', $cms->theme_color);
        Session::put('cms_undraw_img', $cms->undraw_img);
        $user = User::where('id', Auth::id())->first();
        $address = UserAddress::where('user_id', Auth::id())->first();
        return view('edit-account', compact('user', 'address'));
    }

    public function update(Request $request, $id) {
      
        User::where('id', $id)
        ->update([
            'name' => $request->input('name'),
            'student_id' => $request->input('studentId'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'access_level' => 4,
            'phone' => $request->input('phone'),
        ]);

        if ($request->input('password')) {
            User::where('id', $id)
            ->update([
                'password' => \Hash::make($request->input('password'))
            ]);
        }

        return redirect()->back()
            ->with('success', 'User was updated.');
    }
}
