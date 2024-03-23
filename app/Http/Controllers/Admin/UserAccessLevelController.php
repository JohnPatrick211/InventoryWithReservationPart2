<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserAccessLevel;
use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\base;
use DB;
use Session;

class UserAccessLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $user = User::whereIn('status', [1, 0])->where('access_level','!=', 4)->paginate(10);
        $useraccesslevels = UserAccessLevel::whereIn('is_active', [1, 0])->paginate(10);

        return view('admin.utilities.useraccesslevel.index', compact('useraccesslevels'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.utilities.useraccesslevel.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $useraccesslevels = new UserAccessLevel;
        $useraccesslevels->description = $request->input('description');
        $useraccesslevels->save();

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
    public function edit(User $user)
    {
        return view('admin.utilities.user.edit', compact('user'));
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
        $request->validate([
            'name' => 'required:users',
            'username' => 'required:users',
            'email' => 'required:users',
            'access_level' => 'required:users',
        ]);

        User::where('id', $id)
        ->update([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'status' => $request->input('status'),
            'access_level' => $request->input('access_level')
        ]);

        if ($request->input('password')) {

            $request->validate([
                'password' => 'min:8',
            ]);

            User::where('id', $id)
            ->update([
                'password' => \Hash::make($request->input('password'))
            ]);
        }

        return redirect()->back()
            ->with('success', 'User was updated.');
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
