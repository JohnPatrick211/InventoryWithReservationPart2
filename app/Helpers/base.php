<?php

namespace App\Helpers;
use DB, Auth, Session;
class base
{
   
    public static function CSVImporter($file)
    {
        // File Details
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();

        // Valid File Extensions
        $valid_extension = array("csv");

        // 2MB in Bytes
        $maxFileSize = 2097152;

        // Check file extension
        if(in_array(strtolower($extension),$valid_extension)){

            // Check file size
            if($fileSize <= $maxFileSize){

                // File upload location
                $location = 'uploads';

                // Upload file
                $file->move($location,$filename);

                // Import CSV to Database
                $filepath = public_path($location.'/'.$filename);

                // Reading file
                $file = fopen($filepath,"r");

                $importData_arr = array();
                $i = 0;

                while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                    $num = count($filedata );

                    // Skip first row (Remove below comment if you want to skip the first row)
                    if($i == 0){
                        $i++;
                        continue;
                    }
                    for ($c=0; $c < $num; $c++) {
                        $importData_arr[$i][] = $filedata [$c];
                    }
                    $i++;
                }
                fclose($file);

                self::importStudent($importData_arr);

                Session::flash('message','Import Successful.');
            } else {
                Session::flash('message','File too large. File must be less than 2MB.');
            }

        } else {
            Session::flash('message','Invalid File Extension.');
        }
    }

    public static function importStudent($importData_arr)
{
    $num_of_duplicate = 0;

    foreach($importData_arr as $data_col) {
        

        //dd($data_col);

        if(empty($data_col[1]) && empty($data_col[2]) && empty($data_col[3]) && empty($data_col[4]) && empty($data_col[5]) && empty($data_col[6]) && empty($data_col[7])){
            Session::put('error_val','emptyrow');
            Session::flash('message', 'One of the Row Data is Empty');
            return; // Exit the method
        }
        
        // Check if email ends with "@gmail.com"
        $email = $data_col[4];

        if(empty($data_col[4])){
            Session::put('error_val','emptyemail');
            Session::flash('message', 'One of the Data in Email Column is Empty');
            return; // Exit the method
        }

        if (strpos($email, '@gmail.com') === false) {
            Session::put('error_val','email');
            // Email does not contain "@gmail.com"
            Session::flash('message','Invalid email format. Email must end with @gmail.com');
            return; // Exit the method
        }

        // Check if email already exists in the database
        if (!self::isEmailUnique($email)) {
            // Email already exists, skip inserting this record
            $num_of_duplicate++;
            continue;
        }

        // Check if password length is at least 8 characters
        $password = $data_col[3];
        if (strlen($password) < 8) {
            Session::put('error_val','password');
            Session::flash('message', 'Upload failed. Accounts must have a minimum of 8 characters in the password.');
            return; // Exit the method
        }
        if(empty($data_col[1])){
            Session::put('error_val','name');
            Session::flash('message', 'One of the Data in Name Column is Empty');
            return; // Exit the method
        }
        if(empty($data_col[2])){
            Session::put('error_val','username');
            Session::flash('message', 'One of the Data in Username Column is Empty');
            return; // Exit the method
        }
        if(empty($data_col[3])){
            Session::put('error_val','emptypassword');
            Session::flash('message', 'One of the Data in Password Column is Empty');
            return; // Exit the method
        }
        if(empty($data_col[5])){
            Session::put('error_val','emptyphone');
            Session::flash('message', 'One of the Data in Phone Number Column is Empty');
            return; // Exit the method
        }
        if(empty($data_col[6])){
            Session::put('error_val','emptyaccess');
            Session::flash('message', 'One of the Data in Access Level Column is Empty');
            return; // Exit the method
        }
        if(empty($data_col[7])){
            Session::put('error_val','emptystatus');
            Session::flash('message', 'One of the Data in Status Column is Empty');
            return; // Exit the method
        }

        // Password meets the length requirement, hash the password
        $hashedPassword = \Hash::make($password);

        // Email is unique and password meets the requirement, insert the record
        DB::table('users')
            ->insert([
                'name' => $data_col[1],
                'username' => $data_col[2],
                'password' => $hashedPassword,
                'email' => $email,
                'phone' => $data_col[5],
                'access_level' => $data_col[6],
                'status' => $data_col[7],
                'created_at' => now(), // Set created_at timestamp
                'updated_at' => '', // Set updated_at as blank
            ]);
    }
    Session::put('NO_OF_DUPLICATES',$num_of_duplicate);
}


public static function isEmailUnique($email)
{
    // Check if email already exists in the database
    return DB::table('users')->where('email', $email)->doesntExist();
}


public static function CSVExporter($users)
{
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=file-export-'.date('Y-m-d h:s:m').'.csv');
    $output = fopen('php://output', 'w');


        fputcsv($output, array('id','name','username', 'password','email','phone','access_level','status','created_at','updated_at'));



        if (count($users) > 0)
        {
            foreach ($users as $row)
            {
                fputcsv($output, (array) $row);
            }
        }
}



    public static function isUserExist($id)
    {
        $row = DB::table('users')->where('id', $id);

        return $row->count() > 0 ? true : false;
    }

}
