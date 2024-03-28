<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Replacement;
use App\Models\Category;
use Auth;
use DB;
use Session;

class ReplacmentController extends Controller
{
    public function index() {
        $cms = DB::table('cms')->where('id', 1)->first();
        Session::put('cms_name', $cms->name);
        Session::put('cms_theme_color', $cms->theme_color);
        Session::put('cms_undraw_img', $cms->undraw_img);
        $user = User::where('id', Auth::id())->first();
        $address = UserAddress::where('user_id', Auth::id())->first();
        return view('replacement.replacement', compact('user', 'address'));
    }

    public function createindex() {
        $cms = DB::table('cms')->where('id', 1)->first();
        Session::put('cms_name', $cms->name);
        Session::put('cms_theme_color', $cms->theme_color);
        Session::put('cms_undraw_img', $cms->undraw_img);
        $user = User::where('id', Auth::id())->first();
        $address = UserAddress::where('user_id', Auth::id())->first();
        $product = DB::table('orders AS BR')
        ->select('BR.*', 'users.*', 'product.*','product.id as product_id')
        ->leftJoin('users', 'BR.user_id', '=', 'users.id')
        ->leftJoin('product', 'BR.product_code', '=', DB::raw("CONCAT(product.prefix,product.id)"))
        ->where('BR.status',4)
        ->where('users.id',Auth::id())
        ->groupBy('product_id')
        ->get();
        return view('addreplacement', compact('user', 'address','product'));
    }

    public function storerequest(Request $data) {
        $user = new Replacement;
            $user->user_id = Auth::user()->id;
            $user->product_id = $data->input('product_name');
            $user->qty = $data->input('qty');
            $user->reason = $data->input('reason');
            $user->status = '0';

            if ($data->hasFile('receipt')) {
                $user->image_receipt = $this->receiptUpload($data, 'receipt_only');
            }

            $user->save();

            return redirect()->back()
            ->with('success', 'Product Request Replacement was added successfully');
    }

    public function receiptUpload($request, $type) 
    {
        $folder_to_save = 'receipt';

        if ($type == 'receipt_only') {
            $image_name = uniqid() . "." . $request->receipt->extension();
            $request->receipt->move(public_path('images/' . $folder_to_save), $image_name);
            return $folder_to_save . "/" . $image_name;
        }
    }

    public function fetchReplacementData(){
        $product = new Replacement;
        $product = $product->readAllReplacement();

        if(request()->ajax())
        { 
            return datatables()->of($product)
                ->addColumn('action', function($product)
                {
                    $button = ' <a class="btn btn-sm" data-id="'. $product->id .'" href="'. url('updatereplacement/'.$product->id) .'"><i class="fa fa-edit"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    if($product->status == '1'){
                        $button .= '<a class="btn btn-sm btn-archive-product disabled" data-id="'. $product->id .'"><i  style="color:#DC3545;" class="fa fa-archive"></i></a>';
                    }
                    else{
                        $button .= '<a class="btn btn-sm btn-archive-product" data-id="'. $product->id .'"><i  style="color:#DC3545;" class="fa fa-archive"></i></a>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function updateindex($id) {
        $cms = DB::table('cms')->where('id', 1)->first();
        Session::put('cms_name', $cms->name);
        Session::put('cms_theme_color', $cms->theme_color);
        Session::put('cms_undraw_img', $cms->undraw_img);
        $user = User::where('id', Auth::id())->first();
        $address = UserAddress::where('user_id', Auth::id())->first();
       // $product = Product::where('status', 1)->get();
        $product = DB::table('orders AS BR')
        ->select('BR.*', 'users.*', 'product.*','product.id as product_id')
        ->leftJoin('users', 'BR.user_id', '=', 'users.id')
        ->leftJoin('product', 'BR.product_code', '=', DB::raw("CONCAT(product.prefix,product.id)"))
        ->where('product.status',1)
        ->where('BR.status',4)
        ->where('users.id',Auth::id())
        ->groupBy('product_id')
        ->get();

    //     $product = DB::table('product AS BR')
    //     ->select('BR.*', 'users.*', 'orders.product_code', DB::raw("CONCAT(BR.prefix,' ',BR.id) as ids"))
    //     ->leftJoin('orders', DB::raw("CONCAT('BR.prefix','BR.id')") , '=', 'orders.product_code')
    //     ->leftJoin('users', 'orders.user_id', '=', 'users.id')
    //     //->where('product.status',1)
    //    // ->where('users.id',Auth::id())
    //     ->get();

        //return dd($product);
        $replacement = Replacement::where('id', $id)->first();
        return view('updatereplacement', compact('user', 'address','product','replacement'));
       // return dd($replacement->reason);
       //return dd($product);
    }

    public function updaterequest(Request $data) {

            //return dd($data->id);

            if ($data->hasFile('receipt')) {
                Replacement::where('id', $data->id)
                ->update([
                'image_receipt' => $this->receiptUpload($data, 'receipt_only')
                ]);
            }

             Replacement::where('id', $data->id)
            ->update([
                'product_id' => $data->input('update_product_name'),
                'qty' => $data->input('update_qty'),
                'reason' => $data->input('update_reason'),
                'status' => '0',

            ]);

           return redirect()->back()
            ->with('success', 'Product Request Replacement was updated successfully');
    }

    public function deleterequest($id){
        Replacement::where('id', $id)->delete();

        return redirect()->back()
            ->with('success', 'Product Request Replacement was deleted successfully');
    }

    //Admin
    public function adminindex(Request $request){
        $cms = DB::table('cms')->where('id', 1)->first();
        Session::put('cms_name', $cms->name);
        Session::put('cms_theme_color', $cms->theme_color);
        Session::put('cms_undraw_img', $cms->undraw_img);
        
        $data = DB::table('replacement AS BR')
        ->select('BR.*', 'users.name AS studentName', 'product.description AS productName', 'BR.qty AS replacement_qty')
        ->leftJoin('users', 'BR.user_id', '=', 'users.id')
        ->leftJoin('product', 'BR.product_id', '=', 'product.id')
        ->where('BR.status',0)
        ->where('BR.archive_status','!=', 0)
        ->get();

        if(request()->ajax())
        { 
            return datatables()->of($data)
                ->addColumn('action', function($data){
                    $button = '<a class="btn btn-sm btn-full-view" data-id='. $data->id .'
                    data-image="/images/'.$data->image_receipt.'">
                    <i class="fa fa-eye"></i></a>';
                    return $button;
                })
                ->addColumn('status', function($data){
                    $status = '<span class="badge badge-success">Approved</span>';
                    if ($data->status == 0) {
                        $status = '<span class="badge badge-warning text-white">Pending</span>';
                    }
                    return $status;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.replacement.index');
    }

    public function getApprovedReplacement(){
        $data = DB::table('replacement AS BR')
        ->select('BR.*', 'users.name AS studentName', 'product.description AS productName', 'BR.qty AS replacement_qty')
        ->leftJoin('users', 'BR.user_id', '=', 'users.id')
        ->leftJoin('product', 'BR.product_id', '=', 'product.id')
        ->where('BR.status',1)
        ->where('BR.archive_status','!=', 0)
        ->get();

        if(request()->ajax())
        { 
            return datatables()->of($data)
            ->addColumn('action', function($data){
                $button = '<a class="btn btn-sm btn-full-view" data-id='. $data->id .'
                data-image="/images/'.$data->image_receipt.'">
                <i class="fa fa-eye"></i></a>';
                return $button;
            })
                ->addColumn('status', function($data){
                    $status = '<span class="badge badge-success">Approved</span>';
                    if ($data->status == 0) {
                        $status = '<span class="badge badge-warning text-white">Pending</span>';
                    }
                    return $status;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
    }

    public function Approve($user_id) {
        Replacement::where('id', $user_id)->update(['status' => 1]);
    }

    public function Reject($user_id) {
        Replacement::where('id', $user_id)->update(['status' => 2]);
    }
}
