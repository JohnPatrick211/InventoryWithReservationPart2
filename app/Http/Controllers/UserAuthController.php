<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use App\Models\User;
use App\Models\CMS;
use DB;

class UserAuthController extends Controller
{
    public function index() {
        $cms = DB::table('cms')->where('id', 1)->first();
            Session::put('cms_name', $cms->name);
            Session::put('cms_address', $cms->address);
            Session::put('cms_theme_color', $cms->theme_color);
            Session::put('cms_undraw_img', $cms->undraw_img);
            Session::put('cms_bg_admin_login', $cms->bg_admin_login);
            Session::put('cms_logo', $cms->logo);
        if (Auth::check()) {
            $access_level = Auth::user()->access_level;
            Session::put('Name', Auth::user()->name);
            
            // 4 = student
            // 3 = System Administrator
            // 2 = Proware Specialist
            // 1 = Assistant Proware Specialist
            if (in_array($access_level, array( 4 )))
                 return redirect()->intended('/');  
            else if (in_array($access_level, array( 3 )))
                return redirect()->intended('/users');  
            else if (in_array($access_level, array( 1 )))
                return redirect()->intended('/stock-adjustment');  
            else if (in_array($access_level, array( 2 )))
                return redirect()->intended('/dashboard'); 
        }
        return view('admin.login');
    }

    public function customer_index() {
        $cms = DB::table('cms')->where('id', 1)->first();
        Session::put('cms_name', $cms->name);
        Session::put('cms_address', $cms->address);
        Session::put('cms_theme_color', $cms->theme_color);
        Session::put('cms_undraw_img', $cms->undraw_img);
        Session::put('cms_logo', $cms->logo);
        if (Auth::check()) {
            return redirect()->intended('/');  
        }
        return view('login');
    }

    public function signup_view() {
        $cms = DB::table('cms')->where('id', 1)->first();
        Session::put('cms_name', $cms->name);
        Session::put('cms_address', $cms->address);
        Session::put('cms_theme_color', $cms->theme_color);
        Session::put('cms_undraw_img', $cms->undraw_img);
        Session::put('cms_logo', $cms->logo);
        if (Auth::check()) {
            return redirect()->intended('/');  
        }
        return view('signup');
    }

    public function login(Request $data) {
        // dd(strlen($data['password']));
        if(strlen($data['password']) < 8){
            return redirect()->back()->with('danger', 'Minimum Length of Password is 8');   
        }
        $cms = DB::table('cms')->where('id', 1)->first();
        Session::put('cms_name', $cms->name);
        Session::put('cms_address', $cms->address);
        Session::put('cms_theme_color', $cms->theme_color);
        Session::put('cms_undraw_img', $cms->undraw_img);
        Session::put('cms_logo', $cms->logo);
        if (Auth::attempt(['username' => $data['username'], 'password' => $data['password']])) 
        {
            $status = Auth::user()->status;
            $access_level = Auth::user()->access_level;
            if( $status == 0){
                Auth::logout();
                Session::flush();
                if($access_level != 4){
                    return redirect()->back()->with('danger', 'Your Account Is Inactive, Please Contact Your System Administrator');
                }
                else{
                    return redirect()->back()->with('danger', 'Your Account Is Under Verification, Please Contact Your System Administrator');
                }
               
            }
            else{
                Session::put('Name', Auth::user()->name);
                 // 4 = student
                // 3 = System Administrator
                // 2 = Proware Specialist
                // 1 = Assistant Proware Specialist
                if (in_array($access_level, array( 4 )))
                    return redirect()->intended('/');    
                else if (in_array($access_level, array( 3 )))
                    return redirect()->intended('/users');  
                else if (in_array($access_level, array( 1 )))
                    return redirect()->intended('/stock-adjustment');  
                else if (in_array($access_level, array( 2 )))
                    return redirect()->intended('/dashboard'); 
            }    
        }
        else {
            return redirect()->back()->with('danger', 'Invalid username or password.');  
        }
    }

    public function createAccount(Request $data) {
       
        $alert = 'success';
        $message = 'You have successfully registered!';

        if ($this->isEmailExists($data->input('email'))) {
            $alert = 'danger';
            $message = 'Email is already exists.';
        }
        else if ($this->isUsernameExists($data->input('username'))) {
            $alert = 'danger';
            $message = 'Username is already exists.';
        }
        else {
            $user = new User;
            $user->name = $data->input('firstname') ." ". $data->input('lastname');
            $user->email = $data->input('email');
            $user->access_level = 4;
            $user->username = $data->input('username');
            $user->password = \Hash::make($data->input('password'));
            $user->id_type = $data->input('id_type');

            if ($data->hasFile('identification_photo')) {
                $user->identification_photo = $this->imageUpload($data, 'id_only');
            }

            if ($data->hasFile('selfie_with_identification_photo')) {
                $user->selfie_with_identification_photo = $this->imageUpload($data, 'selfie_with_id');
            }

            $user->phone = $data->input('phone');
            $user->status = 0;
            $user->save();
        }

        return redirect()->back()
            ->with($alert, $message);
    
    }

    public function isEmailExists($email)
    {
        $res = User::where('email', $email)->get();
        return count($res) == 1 ? true : false;
    }

    public function isUsernameExists($username)
    {
        $res = User::where('username', $username)->get();
        return count($res) == 1 ? true : false;
    }

    public function imageUpload($request, $type) 
    {
        $folder_to_save = 'user-identification';

        if ($type == 'id_only') {
            $image_name = uniqid() . "." . $request->identification_photo->extension();
            $request->identification_photo->move(public_path('images/' . $folder_to_save), $image_name);
            return $folder_to_save . "/" . $image_name;
        }
        else {
            $image_name = uniqid() . "." . $request->selfie_with_identification_photo->extension();
            $request->selfie_with_identification_photo->move(public_path('images/' . $folder_to_save), $image_name);
            return $folder_to_save . "/" . $image_name;
        }
    }

    public function logout()
    {
        $access_level = Auth::user()->access_level;

        Auth::logout();
        Session::flush();

        if ($access_level == 4) {
            return redirect()->intended('/');
        }
        else {
            return redirect()->intended('/admin');
        }
    }
}
