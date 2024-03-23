<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Feedback;
use Auth;
use DB;
use Session;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index(Order $order)
    {
        $cms = DB::table('cms')->where('id', 1)->first();
        Session::put('cms_name', $cms->name);
        Session::put('cms_theme_color', $cms->theme_color);
        Session::put('cms_undraw_img', $cms->undraw_img);
        $user_id = Auth::id();
        $orders = $order->readOrders($user_id);

        return view('orders', compact('orders'));
    }

    public function cancelOrder($order_no)
    {
        $date_order = strtotime(request()->date_time_order);
        $five_minutes_ago = strtotime("-5 minutes");
    
        if ($date_order >= $five_minutes_ago) {
            Order::where('order_no', $order_no)->update([
                'status' => 0
            ]);
            return 'status changed to cancel';
        }
        else {
            return 'more than 5 mins';
        }
    }

    
    public function sendFeedback()
    {
        Feedback::create([
            'user_id' => Auth::id(),
            'order_no' => request()->order_no,
            'comment' => request()->comment,
            'suggestion' => request()->suggestion,
        ]);
    }

    public function readOneFeedback()
    {
        return Feedback::where('user_id', Auth::id())
            ->where('order_no', request()->order_no)
            ->first();
    }

    public function previewOrderEReceipt($order_no){

        $order = new Order;

        $data = $order->readOneOrder($order_no);
        // $supplier_name = $supplier->getSupplierNameByID($supplier_id);

        // $supplier_contact = $supplier->getSupplierContact($supplier_id);
        // $supplier_address = $supplier->getSupplierAddress($supplier_id);
         $output = $this->generateReceipt($data);
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A5', 'portrait');
    
        return $pdf->stream('Purchase-Order'.date('Y-m-d h:m'));

        //return dd($data[0]->id);

    }

    public function generateReceipt($items){

        $title = Session::get('cms_name');
        $address = Session::get('cms_address');
        $orderDate = Carbon::parse($items[0]->date_order)->format('F d, Y g:i A');

        $output = '
        <!DOCTYPE html>
        <html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style type="text/css">';

        $output .= $this->style();

        $output .='
        </style>
        </head>

        <body>

        <div style="width:100%">
        <div class="center">
        <img src="images/'.Session::get('cms_logo').'" style="width:20%; align:middle;">
        </div>
        <br> <br> <br> <br>
        <h1 class="p-name">'.$title.'</h1>
        <div style="text-align:center;">'.$address.'<div>
        <h2 style="text-align:center;">OFFICIAL RECEIPT</h2>
        <p style="text-align:left;">Order No.: '.$items[0]->order_no.'</p>
        <p style="text-align:left;">Student Name: '.$items[0]->name.'</p>
        <p style="text-align:left;">Date Ordered: '. $orderDate.'</p>
        <table width="100%" style="border-collapse:collapse; border: 1px solid;">                
            <thead>
                <tr>
                    <th>Product Code</th>    
                    <th>Name</th>   
                    <th>Unit</th>   
                    <th>Payment Method</th>   
                    <th>Qty</th>   
                    <th>Price</th>   
               
            <tbody>';

                if($items){
                    $total = 0;
                    foreach ($items as $data) {
                    
                        $output .='
                    <tr class="align-text">              
                        <td>'. $data->product_code .'</td>  
                        <td>'. $data->description .'</td>
                        <td>'. $data->unit .'</td>
                        <td>'. $data->payment_method .'</td>
                        <td>'. $data->qty .'</td>
                        <td style="text-align:right;"><span>&#8369;</span>'. number_format($data->amount,2,'.',',') .'</td>   
                    </tr>';

                    $total = $total + $data->amount;
                } 
                $output.='<tr class="align-text"><td></td><td></td><td></td><td></td><td></td>  
                        <td style="text-align:right;"><span>&#8369;</span>'. number_format($total,2,'.',',') .'</td>   
                    </tr>';
            }
            else{
                echo "No data found";
            }
            
            
        $output .='
 
        </tbody>
        </table>
        <p class="ar2"> Date Generated: '. Carbon::now()->format('F d, Y').' <br/> Preview Prepared By: '. Session::get('Name') .'</p>
             
    </div>


        </body>

        </html>
        
       ';

        return $output;
    }

    public function style() {
       return '
        @page { margin: 20px; }
        body{ font-family: sans-serif; }
        th{
            border: 1px solid;
        }
        td{
            font-size: 14px;
            border: 1px solid;
            padding-right: 2px;
            padding-left: 2px;
        }

        .center img {
            display: block;
            margin-left: auto;
            margin-right: auto;
            position:absolute;
            left:220px;
            }

        .p-name{
            text-align:center;
            margin-bottom:5px;
        }

        .address{
            text-align:center;
            margin-top:0px;
        }

        .p-details{
            margin:0px;
        }

        .ar{
            text-align:right;
        }

        .al{
            text-left:right;
        }

        .align-text{
            text-align:center;
        }

        .align-text td{
            text-align:center;
        }

        .w td{
            width:20px;
        }

   

        .b-text .line{
            margin-bottom:0px;
        }

        .b-text .b-label{
            font-size:12px;
            margin-top:-7px;
            margin-right:12px;
            font-style:italic;
        }

        .f-courier{
            font-family: monospace, sans-serif;
            font-size:14px;
        }

        span {
            font-family: DejaVu Sans; sans-serif;
        }

        .ar2{
            position:absolute; 
            bottom:0px;
            right:0px;
            
        }
        
        ';
    }
}
