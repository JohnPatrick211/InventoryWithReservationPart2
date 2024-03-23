<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BackupInfo;
use DB;
use DateTime;

class BackupAndRestoreController extends Controller
{
    public function index() {
        $res = DB::table('backup_info')->orderBy('created_at', 'desc')->first();
        $last_backup = "No backup database yet.";
        if (isset($res->file_name)) {
            $last_backup = 'Last backup: '.date('F d, Y h:i A', strtotime($res->created_at));
        }
        return view('admin.utilities.backup-and-restore.index', compact('last_backup'));
   }

    public function backup() {

        // $filename = "backup-db-" . date('Y-m-d') .".sql";


        // $command = "".env('DUMP_PATH')." --user=" . env('DB_USERNAME') . " --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  > " . storage_path() . "/app/backup/" . $filename;

        // $returnVar = NULL;
        // $output = NULL;

        $get_all_table_query = "SHOW TABLES";
    $result = DB::select(DB::raw($get_all_table_query));

    $tables = [
        'audit_trail','backup_info','cart','cashiering_tray','category','cms'
        ,'delivery_area','discount','failed_jobs','feedback','migrations','orders',
        'order_shipping_fee','password_resets','paymongo_payment','personal_access_tokens','product','product_return','purchase_order','replacement','sales','stock_adjustment','supplier','supplier_delivery','tbl_brgy','tbl_citymun','tbl_province','unit','users','user_address'
    ];

    $structure = '';
    $data = '';
    foreach ($tables as $table) {
        $show_table_query = "SHOW CREATE TABLE " . $table . "";

        $show_table_result = DB::select(DB::raw($show_table_query));

        foreach ($show_table_result as $show_table_row) {
            $show_table_row = (array)$show_table_row;
            $structure .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
        }
        $select_query = "SELECT * FROM " . $table;
        $records = DB::select(DB::raw($select_query));

        foreach ($records as $record) {
            $record = (array)$record;
            $table_column_array = array_keys($record);
            foreach ($table_column_array as $key => $name) {
                $table_column_array[$key] = '`' . $table_column_array[$key] . '`';
            }

            $table_value_array = array_values($record);
            $data .= "\nINSERT INTO $table (";

            $data .= "" . implode(", ", $table_column_array) . ") VALUES \n";

            foreach($table_value_array as $key => $record_column)
                $table_value_array[$key] = addslashes($record_column);

            $data .= "('" . implode("','", $table_value_array) . "');\n";
        }
    }
    $file_name = 'DB_Backup_' .date('M-d-Y') . '.sql';
    $file_handle = fopen($file_name, 'w + ');

    $output = $structure . $data;
    fwrite($file_handle, $output);
    fclose($file_handle);


        BackupInfo::create([
            'file_name' => $file_name
        ]);
       // return redirect()->back()->with('success', 'Database was backup successfully.');
       return response()->download($file_name);
    }

    public function restore() {
        
        $sql = public_path() . "/DB_Backup_" . date('M-d-Y') .".sql";
        \DB::unprepared(file_get_contents($sql));
        return redirect()->back()->with('success', 'Database was restored successfully.');

        /*
        $db = [
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE')
        ];
    
        exec("mysql --user={$db['username']} --password={$db['password']} --host={$db['host']} --database {$db['database']} < $sql");
    
        \Log::info('SQL Import Done');*/
    }

    
    function timeAgo($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}
