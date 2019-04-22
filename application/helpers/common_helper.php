<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('print_r_die'))
{
    function print_r_die($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die();
    }   
}

if( ! function_exists('create_index_html')){
    
    function create_index_html($folder) {
        $content = "<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>";
        $folder = $folder.'/index.html';
        $fp = fopen($folder , 'w');
        fwrite($fp , $content);
        fclose($fp);
    }
}

if( ! function_exists('convert_gender')){
    
    function convert_gender($gender) {
        if($gender){
            if($gender == "M"){
                return "Male";
            }else{
                return "Female";
            }
        }

        return false;
    }
}

if( ! function_exists('convert_status')){
    
    function convert_status($status) {
        if($status == 1){
            return "<span class='label label-success'>Active</span>";
        }else{
            return "<span class='label label-danger'>Inactive</span>";
        }
    }
}

if( ! function_exists('convert_invoice_status')){
    
    function convert_invoice_status($status) {
        if($status == "UNPAID"){
            return "<span class='label label-danger'>Unpaid</span>";
        }else{
            return "<span class='label label-success'>Paid</span>";
        }
    }
}

if( ! function_exists('convert_payment_status')){
    
    function convert_payment_status($status , $raw = false) {
        if($raw){
            if($status == "COD"){
                return "Cash on Delivery";
            }else{
                return "Pay by Cheque";
            }
        }else{
            if($status == "COD"){
                return "<span class='label label-info'>Cash on Delivery</span>";
            }else{
                return "<span class='label label-primary'>Pay by Cheque</span>";
            }
        }
    }
}

if( ! function_exists('convert_customer_status')){
    
    function convert_customer_status($status) {
        if($status == 1){
            return "<span class='label label-success'>Activated</span>";
        }else if($status == 2){
            return "<span class='label label-info'>Unactivated</span>";
        }else{
            return "<span class='label label-danger'>Inactive</span>";
        }

        return false;
    }
}

if( ! function_exists('remind_in')){
    
    function remind_in($type) {
        switch ($type) {
            case '1 MONTH':
                return strtotime("+1 Month");
                break;
            case '2 MONTHS':
                return strtotime("+2 Months");
                break;
            case '3 MONTHS':
                return strtotime("+3 Months");
                break;
            case '6 MONTHS':
                return strtotime("+6 Months");
                break;
        }
    }
}

if ( ! function_exists('report_status'))
{
    function report_status($type , $raw = false)
    {
        /*
            0 - NO DEFECT
            1 - OPEN
            2 - FIXED
            3 - ON MAINTENANCE


        */
        if($raw){
            switch ($type) {
                // case 3:
                //     return 'On Maintenance';
                //     break;
                case 2:
                    return 'Fixed';
                    break;
                case 1:
                    return 'DEFECT';
                    break;                    
                case 0:
                    return 'GOOD';
                    break;
                default:
                    # code...
                    break;
            }
        }else{
            switch ($type) {
                
                case 2:
                    return '<span class="label label-success">Fixed</span>';
                    break;
                // case 3:
                //     return '<span class="label label-warning">On Maintenance</span>';
                //     break;
                case 1:
                    return '<span class="label label-danger">DEFECT</span>';
                    break;
                case 0:
                    return '<span class="label label-success">GOOD</span>';
                    break; 
                // default:
                //     # code...
                //     break;
            }
        }
    }   
}

if( ! function_exists('date_of_birth')){
    
    function date_of_birth($d , $m , $y) {
        if($d AND $m AND $y){
            return $d.' '.DateTime::createFromFormat('!m', $m)->format("F").' '.$y;
        }else{
            return false;
        }
    }
}


if ( ! function_exists('convert_timezone'))
{
    function convert_timezone($time , $with_hours = false , $with_timezone = true , $hour_only = false , $custom_format_date_with_hour = "d M Y h:i:s A" , $custom_format_date = "d M Y" , $custom_format_hour = "h:i:s A")
    {
        if(!$time OR $time == 0){
            return "NA";
        }

        if($with_timezone){
            $timezone = get_timezone();
            //$timezone = "Asia/Kuala_Lumpur";

            if($with_hours){
                $date_format = $custom_format_date_with_hour;

                $triggerOn = date($date_format , $time);

                $tz = new DateTimeZone($timezone);
                $datetime = new DateTime($triggerOn);

                $datetime->setTimezone($tz);
                
                return $datetime->format("d/M/Y H:i:s A");

            }else if($hour_only){
                $date_format = $custom_format_hour;
                $triggerOn = date($date_format , $time);

                $tz = new DateTimeZone($timezone);
                $datetime = new DateTime($triggerOn);

                $datetime->setTimezone($tz);

                
                return $datetime->format( $date_format );
            }else{
                $date_format = $custom_format_date;

                $triggerOn = date($date_format , $time);

                $tz = new DateTimeZone($timezone);
                $datetime = new DateTime($triggerOn);

                $datetime->setTimezone($tz);

                return $datetime->format("d/M/Y");
            }
            

        }else{
            if($with_hours){
                $date_format = "d/M/Y H:i:s A";
            }else if($hour_only){
                $date_format = $custom_format_hour;
            }else{
                $date_format = "d/M/Y";
            }

            return date($date_format , $time);
        }
    }   
}

if ( ! function_exists('report_type'))
{
    function report_type($type , $raw = false)
    {
        if($raw){
            switch ($type) {
                case 0:
                    return 'No Defect';
                break;
                case 1:
                    return 'Open';
                break;
                case 2:
                    return 'Fixed';
                break;
                // case 3:
                //     return 'Fixed';
                // break;
            }
        }else{
            switch ($type) {
                case 0:
                    return '<span class="label label-success">GOOD</span>';
                break;
                case 1:
                    return '<span class="label label-danger">DEFECT</span>';
                break;
                case 2:
                    return '<span class="label label-warning">Fixed</span>';
                break;
                // case 3:
                //     return '<span class="label label-success">Fixed</span>';
                // break;
            }
        }
    }   
}

if(!function_exists("fromNow")){

    function fromNow($time) {
        $time = time() - $time; // to get the time since that moment
        $time = ($time<1)? 1 : $time;
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'').' ago';
        }
    }
}


if ( ! function_exists('custom_money_format'))
{
    function custom_money_format($money , $no_format = false)
    {

        if(!$money){
            $money = "0.00";
        }
   
        $formatted = "$ ";

        $formatted .= number_format(sprintf('%0.2f', preg_replace("/[^0-9.]/", "", $money)), 2);

        if($no_format){
            return number_format(sprintf('%0.2f', preg_replace("/[^0-9.]/", "", $money)), 2);
        }else{
            return $money < 0 ? "({$formatted})" : "{$formatted}";
        }
        
    }   
}

if ( ! function_exists('array2csv'))
{
    function array2csv($array) 
    {
        if (count($array) == 0) {
            return null;
        }
        ob_start();

        $df = fopen("php://output", 'w');

        fputcsv($df, array_keys(reset($array)));

        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        
        fclose($df);

        return ob_get_clean();
    }
}

if ( ! function_exists('download_send_headers'))
{
    function download_send_headers($filename) 
    {
        // disable caching
        $now = gmdate("D, d/M/Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download  
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }
}


if ( ! function_exists('my_current_url'))
{
    function my_current_url($myurl) 
    {
        $CI =& get_instance();

        $url = $CI->config->site_url($CI->uri->uri_string());
        return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'].$myurl : $url.'?'.$myurl;
    }
}

if ( ! function_exists('sao_side'))
{
    function sao_side($account_type) 
    {
        $CI =& get_instance();

       if($account_type == $CI->session->userdata("user")->account_type){
             return true;
        }else{
            return false;
        }
    }
}

if ( ! function_exists('get_timezone'))
{
    function get_timezone()
    {
        $CI =& get_instance();

        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else{
          $ip=$_SERVER['REMOTE_ADDR'];
        }

        $check = $CI->db->where('ip_address' , $ip)->get('timezone')->row();

        if($check){
            return $check->timezone;
        }else{
            //$data = file_get_contents("http://freegeoip.net/json/".$ip);
            //$data = file_get_contents("https://ipapi.co/210.4.107.115/json/");
            $data = file_get_contents("https://api.ipdata.co/210.4.107.115?api-key=83d9b5dd65c9df1ab11c6bbffc4973a213440ee52d377547289e516a");
            //$data = file_get_contents("https://api.ipdata.co/210.4.107.115?api-key=83d9b5dd65c9df1ab11c6bbffc4973a213440ee52d377547289e516a");
            //$data = file_get_contents("http://api.ipstack.com/134.201.250.155?access_key=7caab6737ec2f78861757d3b25cf6309");
            $data = json_decode($data);

            $timezone =  ($data->time_zone->name) ? $data->time_zone->name : "Asia/Manila";
                
            $arr = array("ip_address" => $ip , "timezone" => $timezone);

            $CI->db->insert('timezone' , $arr);

            return $timezone;
        }
    }   
}

if ( ! function_exists('crypto_rand_secure'))
{
    function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) {
            return $min; // not so random...
        }

        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1

        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);

        return $min + $rnd;
    }
}


function getToken($length)
{
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet .= "0123456789";
    $max = strlen($codeAlphabet); // edited

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max-1)];
    }

    return $token;
}


if ( ! function_exists('generate_app_token'))
{

    function generate_app_token($user_id, $device_id, $device_type)
    {

        $CI =& get_instance();

        #Method start validation
        if ($user_id == 0) {
            return false;
        }

        if ($device_id == null) {
            return false;
        }

        if ($device_type == null) {
            return false;
        }
        #Method end validation

        #Method check if data exist
        $CI->db->where("device_id",$device_id);
        $app_data = $CI->db->where("user_id",$user_id)->get("app_token")->row();

        #Method insert / update record
        if (!empty($app_data)) {
            # UPDATE PARAMS
            $params = array(
                "token" => getToken(255),
                "date_updated" => time()
            );
            # UPDATE RECORD
            $CI->db->where("device_id",$device_id);
            $bool = $CI->db->where("user_id",$user_id)->update("app_token",$params);
        } else {            
            # INSERT PARAMS
            $params = array(
                "user_id" => $user_id,
                "device_id" => $device_id,
                "device_type" => $device_type,
                "token" => getToken(255),
                "date_created" => time()
            );

            # INSERT NEW RECORD
            $bool = $CI->db->insert("app_token",$params);
        }

        return ($bool) ? $params['token'] : false;
    }
}

if ( ! function_exists('validate_app_token'))
{

    function validate_app_token($app_token)
    {
         $CI =& get_instance();

        #Method check if token exist
        $app_token = $CI->db->select("token")->where("token",$app_token)->get("app_token")->row();

        return ($app_token) ? true : false;
    }
}

if( ! function_exists('convert_availability')){
    
    function convert_availability($availability, $raw = false) {
        if($raw){
            return ($availability == 1) ? "Available" : "Unavailable";
        }else{
            if($availability == 1){
                return "<span class='label label-success'>Available</span>";
            }else{
                return "<span class='label label-danger'>Unavailable</span>";
            }
        }
    }
}

if( ! function_exists('convert_vehicle_status')){
    
    function convert_vehicle_status($status, $raw = false) {

        if($raw){
            switch ($status) {
                case 0:
                    return 'Initial';
                break;
                case 1:
                    return 'Checking';
                break;
                case 2:
                    return 'Checked';
                break;
            }
        }else{
            switch ($status) {
                case 0:
                    return '<span class="label label-primary">Initial</span>';
                break;
                case 1:
                    return '<span class="label label-warning">Checking</span>';
                break;
                case 2:
                    return '<span class="label label-success">Checked</span>';
                break;
            }
        }
    }
}

function getCode($length)
{
    $token = "";
    // $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    // $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet = "0123456789";
    $max = strlen($codeAlphabet); // edited

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max-1)];
    }

    return $token;
}

if ( ! function_exists('generate_email_code'))
{

    function generate_email_code($user_id)
    {

        $CI =& get_instance();

        if ($user_id == 0) {
            return false;
        }

        $CI->db->select("code");
        $app_data = $CI->db->where("user_id",$user_id)->get("user")->row()->code;

        if (!empty($app_data)) {
            $params = array(
                "code" => getCode(6),
                "code_created" => time()
            );
            $bool = $CI->db->where("user_id",$user_id)->update("user",$params);
        }else{            
            $params = array(
                "code" => getCode(6),
                "code_created" => time()
            );

            $CI->db->where("user_id",$user_id);
            $bool = $CI->db->update("user",$params);
        }

        return ($bool) ? $params['code'] : false;
    }
}

if ( ! function_exists('validate_email_code'))
{

    function validate_email_code($data)
    {
        $CI =& get_instance();

        $CI->db->where("user_id",$data->user_id);
        $code = $CI->db->select("code, code_created")->where("code",$data->code)->get("user")->row();
        if($code){
            if($code->code_created < (time() - (30*60))){
                return "expired";
            }else{
                return "valid";
            }
        }else{
            return "invalid";
        }
        

        //return ($code) ? true : false;
    }
}

if( ! function_exists('tire_report_status')){
    
    function tire_report_status($status, $raw = false) {

        if($raw){
            switch ($status) {
                case 0:
                    return 'Good';
                break;
                case 1:
                    return 'Defect';
                break;
                case 2:
                    return 'Recheck';
                break;
            }
        }else{
            switch ($status) {
                case 0:
                    return '<span class="label label-success">Good</span>';
                break;
                case 1:
                    return '<span class="label label-danger">Defect</span>';
                break;
                case 2:
                    return '<span class="label label-warning">Recheck</span>';
                break;
            }
        }
    }
}

if( ! function_exists('driver_seat')){
    
    function driver_seat($status, $raw = false) {
        switch ($status) {
            case 0:
                return '<span class="label label-primary">LEFT</span>';
            break;
            case 1:
                return '<span class="label label-primary">RIGHT</span>';
            break;
            case 2:
                return '<span class="label label-default">NOT SET</span>';
            break;
        }
    }
}


