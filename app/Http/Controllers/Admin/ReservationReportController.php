<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Session;
use Carbon\Carbon;

class ReservationReportController extends Controller
{
    public function index()
    {
        $reservationOrder = Order::where('reservation', 1)->orderBy('created_at', 'desc')->get();

        if (request()->ajax()) {
            return datatables()->of($reservationOrder)->make(true);
        }

        return view('admin.reports.reservation-report', compact('reservationOrder'));

    }

    public function readReservations(Request $request)
    {
        $data = new Order;
        $data = $data->readReservationReports($request->date_from, $request->date_to, $request->order_from, $request->payment_method);
        if (request()->ajax()) {
            return datatables()->of($data)->make(true);
        }
    }

    public function previewReservationsReport($date_from, $date_to, $order_from, $payment_method)
    {

        $data = new Order;
        $data = $data->readReservationReports($date_from, $date_to, $order_from, $payment_method);
        $output = $this->generateReservationsReport($data, $date_from, $date_to, $order_from, $payment_method);

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('Reservation-#');
    }

    public function downloadReservationsReport($date_from, $date_to, $order_from, $payment_method){
        $data = new Order;
        $data = $data->readReservationReports($date_from, $date_to, $order_from, $payment_method);
        $output = $this->generateReservationsReport($data, $date_from, $date_to, $order_from, $payment_method);
    
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($output);
        $pdf->setPaper('A4', 'landscape');
    
        return $pdf->download('Reservation.pdf');
    }

    public function generateReservationsReport($items, $date_from, $date_to, $order_from, $payment_method)
    {
        $title = Session::get('cms_name');
        $address = Session::get('cms_address');
        $sales = new Order;
        $output = '
        <!DOCTYPE html>
        <html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style type="text/css">';

        $output .= $this->style();

        $output .= '
        </style>
        </head>

        <body>

        <div style="width:100%">
        <div class="center">
        <img src="images/' . Session::get('cms_logo') . '" style="width:15%; align:middle;">
        </div>
        <br> <br> <br> <br> <br> <br>
        <h1 class="p-name">' . $title . '</h1>
        <div style="text-align:center;">' . $address . '<div>
        <h2 style="text-align:center;">Reservation Report</h2>
        <p style="text-align:left;">Date: ' . date("F j, Y", strtotime($date_from)) . ' - ' . date("F j, Y", strtotime($date_to)) . '</p>
    
        <table width="100%" style="border-collapse:collapse; border: 1px solid;">                
            <thead>
                <tr>
                    <th>Product Code</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Created Date</th>
                    <th>Delivery Date</th>
                    <th>Status</th>
                </tr>
               
            <tbody>';

        if ($items) {
            foreach ($items as $data) {
                $status = 'Cancelled';
                if ($data->status == 1){
                    $status = 'Pending';
                }else if($data->status == 2){
                    $status = 'Prepared';
                }else if($data->status == 3){
                    $status = 'Shippedd';
                }else if($data->status == 4){
                    $status = 'Completed';
                }else if($data->status == 5){
                    $status = 'Reserved';
                }

                $output .= '
                    <tr class="align-text">                             
                        <td>' . $data->product_code . '</td>  
                        <td>' . $data->description . '</td>
                        <td>' . $data->qty . '</td>
                        <td>' . $data->created_at . '</td>
                        <td>' . $data->delivery_date . '</td>  
                        <td>' . $status . '</td>
                    </tr>';

            }
        } else {
            echo "No data found";
        }


        $output .= '
 
        </tbody>
        </table>
        <p class="ar2"> Date Generated: ' . Carbon::now()->format('F d, Y') . ' <br/> Report Prepared By: ' . Session::get('Name') . '</p>
              
    </div>


        </body>

        </html>
        
       ';

        return $output;
    }

    public function style()
    {
        return '
         @page { margin: 20px; }
         body{ font-family: sans-serif; }
         th{
             border: 1px solid;
         }
 
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
             left:470px;
             }
 
         td{
             font-size: 14px;
             border: 1px solid;
             padding-right: 2px;
             padding-left: 2px;
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
         
         ';
    }
}