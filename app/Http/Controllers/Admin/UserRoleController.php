<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\base;
use DB;
use Session;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $user = User::whereIn('status', [1, 0])->where('access_level','!=', 4)->paginate(10);
        $userroles = UserRole::paginate(10);
        $userrolemenus = DB::table('user_role_menus as urm')
            ->select('um.id','um_title', 'um_url','um_class','um_icon','um_has_sub_menu','urm.urm_user_role_id')
            ->join('ui_menus as um','um.id','=','urm.urm_menu_id')
            ->where('um.um_is_active',true)
            ->get();

        return view('admin.utilities.userrole.index', compact('userroles','userrolemenus'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.utilities.userrole.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $userroles = new UserRole;
        $userroles->ur_description = $request->input('description');
        $userroles->save();

        return redirect()->refresh()->with('success', 'Access Lavel was created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(UserRole $userrole,$id)
    {
        $userrole = UserRole::where('id',$id)->first();
        
        $userrolemenus = DB::table('user_role_menus as urm')
                    ->select('um.id','um_title', 'um_url','um_class','um_icon','um_has_sub_menu')
                    ->join('ui_menus as um','um.id','=','urm.urm_menu_id')
                    ->where('urm.urm_user_role_id',$id)
                    ->where('um.um_is_active',true)
                    ->get();
     
        $ui_menus = DB::table('ui_menus as um')
                    ->select('um.id','um_title', 'um_url','um_class','um_icon','um_has_sub_menu')
                    ->where('um.um_is_active',true)
                    ->get();
        
        return view('admin.utilities.userrole.edit', compact('userrole','userrolemenus','ui_menus'));
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
       

        UserRole::where('id', $id)
        ->update([
            'ur_description' => $request->input('description'),
            'ur_is_active' => $request->input('ur_is_active')
        ]);

        
        return redirect()->back()
            ->with('success', 'User role was succesfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        User::where('id', $id)
        ->update([
            'status' => -1,
            'updated_at' => date('Y-m-d h:m:s')
        ]);

        return redirect()->back()
            ->with('success', 'User was archived.');
    }

    public function export(Request $request)
    {

          base::CSVExporter($this->getUserdata());
        //   $getname = Session::get('Name');
        //     $getusertype = Session::get('User-Type');
        //     base::recordAction( $getname, $getusertype,'User Maintenance', 'export');
    }

    public function getUserdata()
    {
        return DB::table('users as U')
                ->select('U.id','U.name','U.username','U.password','U.email','U.phone','U.access_level','U.status','U.created_at','U.updated_at')
                ->get();
    }

    function import(Request $request)
    {
        $file = $request->file('file');

        base::CSVImporter($file);
        $no_of_duplicates = Session::get('NO_OF_DUPLICATES');
        // $getname = Session::get('Name');
        //     $getusertype = Session::get('User-Type');
        //     base::recordAction( $getname, $getusertype,'User Maintenance', 'import');

       if($no_of_duplicates>0)
       {
        return redirect('/users')
        ->with('success', 'User information imported successfully! There are '.$no_of_duplicates.' user are not imported because the user is already exists.');
       }
       else
       {
        return redirect('/users')
        ->with('success', 'User information imported successfully!');
       }
    }
}
