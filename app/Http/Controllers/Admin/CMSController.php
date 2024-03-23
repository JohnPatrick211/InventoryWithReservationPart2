<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CMS;
use DB;
use Session;

class CMSController extends Controller
{
    public function index()
    {
        $cms = DB::table('cms')->where('id', 1)->first();
        Session::put('cms_name', $cms->name);
        Session::put('cms_theme_color', $cms->theme_color);
        Session::put('cms_undraw_img', $cms->undraw_img);
        return view('admin.utilities.CMS.index', compact('cms'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);
        if ($request->hasFile('logo')) {
            $data['logo'] = $this->logoUpload($request);
        }
        if ($request->hasFile('bg_admin_login')) {
            $data['bg_admin_login'] = $this->bgUpload($request);
        }
        CMS::where('id', $id)->update($data);

        return redirect()->back()
            ->with('success', 'CMS was updated successfully, Please Relogin to take effect the logo.');
    }

    public function logoUpload($request) 
    {
        $folder_to_save = 'logo';
        $image_name = uniqid() . "." . $request->logo->extension();
        $request->logo->move(public_path('images/' . $folder_to_save), $image_name);
        return $folder_to_save . "/" . $image_name;
    }

    public function bgUpload($request) 
    {
        $folder_to_save = 'admin_background';
        $image_name = uniqid() . "." . $request->bg_admin_login->extension();
        $request->bg_admin_login->move(public_path('images/' . $folder_to_save), $image_name);
        return $folder_to_save . "/" . $image_name;
    }
}
