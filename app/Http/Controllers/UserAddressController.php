<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAddress;
use App\Models\DeliveryArea;
use Auth;
use DB;
use Session;

class UserAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DeliveryArea $d)
    {
        $brgys = $d->getBrgy();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(DeliveryArea $d)
    {
        $s = new DeliveryArea();
        $cms = DB::table('cms')->where('id', 1)->first();
        Session::put('cms_name', $cms->name);
        Session::put('cms_theme_color', $cms->theme_color);
        Session::put('cms_undraw_img', $cms->undraw_img);
        $address = UserAddress::where('user_id', Auth::id())->first();
        $municipalities = DB::table('tbl_citymun')->orderBy('citymunDesc','ASC')->get();
        $brgys = $d->getBrgy();
        $provinces = DB::table('tbl_province')->orderBy('provDesc','ASC')->get();
        return view('edit-address', compact('address', 'municipalities', 'brgys','provinces'));
       // dd($municipalities);
    }

    public function getMunicipalityByProvince(DeliveryArea $d,$province)
    {
        return $d->getMunicipalityByProvince($province);
    }

    public function getBrgyByMunicipality(DeliveryArea $d,$municipality)
    {
        return $d->getBrgyByMunicipality($municipality);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $citymun = DB::table('tbl_citymun')->select('citymunDesc')->where('citymunCode', $request->municipality)->first();
        //return $request->all();
        if ($this->isAddressExists(Auth::id())) {
           
            UserAddress::where('user_id', Auth::id())->update(['municipality' => $citymun->citymunDesc, 'brgy' => $request->brgy, 'street' => $request->street, 'notes' => $request->notes]);
        }
        else {
            $request['user_id'] = Auth::id();
            UserAddress::create(['user_id' => Auth::id(),'municipality' => $citymun->citymunDesc, 'brgy' => $request->brgy, 'street' => $request->street, 'notes' => $request->notes]);
        }
       return redirect()->back()->with('success', 'Address was updated.');
       //return dd($citymun->citymunDesc);
    }

    public function isAddressExists($user_id)
    {
        $res = UserAddress::where('user_id', $user_id)->get();
        return count($res) > 0 ? true : false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
