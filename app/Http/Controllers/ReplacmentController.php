<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Replacement;
use App\Models\Category;
use Carbon\Carbon;
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

    public function Reject($user_id, $remarks) {
        Replacement::where('id', $user_id)->update(['status' => 2, 'remarks' => $remarks]);
    }

    //Product Replacement Report
    public function indexreport(Request $request){
        $cms = DB::table('cms')->where('id', 1)->first();
        Session::put('cms_name', $cms->name);
        Session::put('cms_theme_color', $cms->theme_color);
        Session::put('cms_undraw_img', $cms->undraw_img);

        if($request->supplier_id == 'ALL'){
            $data = DB::table('replacement AS BR')
            ->select('BR.*', 'users.name AS studentName', 'product.description AS productName', 'BR.qty AS replacement_qty')
            ->leftJoin('users', 'BR.user_id', '=', 'users.id')
            ->leftJoin('product', 'BR.product_id', '=', 'product.id')
            ->where('BR.archive_status','!=', 0)
            ->where('BR.status','!=', 0)
            ->get();
        }
        else{
            $data = DB::table('replacement AS BR')
            ->select('BR.*', 'users.name AS studentName', 'product.description AS productName', 'BR.qty AS replacement_qty')
            ->leftJoin('users', 'BR.user_id', '=', 'users.id')
            ->leftJoin('product', 'BR.product_id', '=', 'product.id')
            ->where('BR.archive_status','!=', 0)
            ->where('BR.status', $request->supplier_id)
            ->get();
        }

        if(request()->ajax())
        { 
            return datatables()->of($data)
                ->addColumn('action', function($data){
                    $button = '<a class="btn btn-sm btn-replacement-archive" data-id='. $data->id .'
                    data-image="/images/'.$data->image_receipt.'">
                    <i class="fas fa-archive"></i></a>';
                    return $button;
                })
                ->addColumn('status', function($data){
                    $status = '<span class="badge badge-success">Approved</span>';
                    if ($data->status == 2) {
                        $status = '<span class="badge badge-danger text-white">Rejected</span>';
                    }
                    return $status;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.reports.replacement');
    }

    public function archive($id)
    {
        Replacement::where('id', $id)
        ->update([
            'archive_status' => 0,
        ]);

        return redirect()->back()
            ->with('success', 'Product was archived.');
    }

    public function previewReport($status){

        if($status == 'ALL'){
            $data = DB::table('replacement AS BR')
            ->select('BR.*', 'users.name AS studentName', 'product.description AS productName', 'BR.qty AS replacement_qty')
            ->leftJoin('users', 'BR.user_id', '=', 'users.id')
            ->leftJoin('product', 'BR.product_id', '=', 'product.id')
            ->where('BR.archive_status','!=', 0)
            ->where('BR.status','!=', 0)
            ->get();
        }
        else{
            $data = DB::table('replacement AS BR')
            ->select('BR.*', 'users.name AS studentName', 'product.description AS productName', 'BR.qty AS replacement_qty')
            ->leftJoin('users', 'BR.user_id', '=', 'users.id')
            ->leftJoin('product', 'BR.product_id', '=', 'product.id')
            ->where('BR.archive_status','!=', 0)
            ->where('BR.status', $status)
            ->get();
        }

        // $data = DB::table('replacement AS BR')
        // ->select('BR.*', 'users.name AS studentName', 'product.description AS productName', 'BR.qty AS replacement_qty')
        // ->leftJoin('users', 'BR.user_id', '=', 'users.id')
        // ->leftJoin('product', 'BR.product_id', '=', 'product.id')
        // ->where('BR.status','!=', 0)
        // ->where('BR.archive_status','!=', 0)
        // ->get();

        $output = $this->reportLayout($data);
    
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'portrait');
    
        return $pdf->stream('replacement_report.pdf');
    }
    
    public function downloadReport($status){

        if($status == 'ALL'){
            $data = DB::table('replacement AS BR')
            ->select('BR.*', 'users.name AS studentName', 'product.description AS productName', 'BR.qty AS replacement_qty')
            ->leftJoin('users', 'BR.user_id', '=', 'users.id')
            ->leftJoin('product', 'BR.product_id', '=', 'product.id')
            ->where('BR.archive_status','!=', 0)
            ->where('BR.status','!=', 0)
            ->get();
        }
        else{
            $data = DB::table('replacement AS BR')
            ->select('BR.*', 'users.name AS studentName', 'product.description AS productName', 'BR.qty AS replacement_qty')
            ->leftJoin('users', 'BR.user_id', '=', 'users.id')
            ->leftJoin('product', 'BR.product_id', '=', 'product.id')
            ->where('BR.archive_status','!=', 0)
            ->where('BR.status', $status)
            ->get();
        }

        // $data = DB::table('replacement AS BR')
        // ->select('BR.*', 'users.name AS studentName', 'product.description AS productName', 'BR.qty AS replacement_qty')
        // ->leftJoin('users', 'BR.user_id', '=', 'users.id')
        // ->leftJoin('product', 'BR.product_id', '=', 'product.id')
        // ->where('BR.status','!=', 0)
        // ->where('BR.archive_status','!=', 0)
        // ->get();

        $output = $this->reportLayout($data);
    
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->download('replacement_report_'. date('Y_m_d_h:m:s').'.pdf');
    }

    public function reportLayout($items){

        $title = Session::get('cms_name');
        $address = Session::get('cms_address');
        
        $output = '
        <style>

        .ar2{
            position:absolute; 
            bottom:-30px;
            right:0px;
            
        }

        .center img {
            display: block;
            margin-left: auto;
            margin-right: auto;
            position:absolute;
            left:290px;
            }


         </style>
        <div style="width:100%">
        <div class="center">
        <img src="images/'.Session::get('cms_logo').'" style="width:20%; align:middle;">
        </div>
        <br> <br> <br> <br> <br>
        <h1 style="text-align:center;">'.$title.'</h1>

        <div style="text-align:center;">'.$address.'<div>
        <h2 style="text-align:center;">Product Replacement Report</h2>
        
        ';

        $output .='
        
        <p>As of : <b> '. date("F j, Y") .'</p> </b>
    
        <table width="100%" style="border-collapse:collapse; border: 1px solid;">
                      
            <thead>
                <tr>
                    
                    <th style="border: 1px solid;">ID</th>
                    <th style="border: 1px solid;">Student Name</th>
                    <th style="border: 1px solid;">Product Name</th>
                    <th style="border: 1px solid;">Qty</th>
                    <th style="border: 1px solid;">Reason</th>
                    <th style="border: 1px solid;">Status</th>
            </thead>
            <tbody>
                ';
    
            if($items){
                foreach ($items as $data) {
                
                $output .='
                <tr>                             
                    <td style="border: 1px solid; padding:10px;">'. $data->id .'</td>
                    <td style="border: 1px solid; padding:10px;">'. $data->studentName .'</td>     
                    <td style="border: 1px solid; padding:10px;">'. $data->productName .'</td>  
                    <td style="border: 1px solid; padding:10px;">'. $data->replacement_qty .'</td>  
                    <td style="border: 1px solid; padding:10px;">'. $data->reason .'</td>  
                    <td style="border: 1px solid; padding:10px;">'. $data->status .'</td>   
                </tr>
                ';
                
                } 
            }
            else{
                echo "No data found";
            }
        
          
            $output .='
            </tbody>
        </table>
        <p class="ar2"> Date Generated: '. Carbon::now()->format('F d, Y').' <br/> Report Prepared By: '. Session::get('Name') .'</p>
             
            </div>';

    
        return $output;
    }

}
