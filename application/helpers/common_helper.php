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

if( ! function_exists('convert_order_status')){
    
    function convert_order_status($status , $raw = false) {
        /*
            0 - cancelled Order
            1 - Placed an order
            2 - Admin Confirm
            3 - On Delivery
            4 - Delivered
        */


         if($raw){
            switch ($status) {
                case 0:
                    return "Cancelled";
                    break;
                case 1:
                    return "Processing";
                    break;
                case 2:
                    return "Confirmed Order";
                    break;
                case 3:
                    return "On Delivery";
                    break;
                case 4:
                    return "Delivered";
                    break;
                default:
                    return "Cancelled";
                    break;
            }
         }else{
            switch ($status) {
                case 0:
                    return "<span class='label label-danger'>Cancelled</span>";
                    break;
                case 1:
                    return "<span class='label label-info'>Processing</span>";
                    break;
                case 2:
                    return "<span class='label label-primary'>Confirmed Ordered</span>";
                    break;
                case 3:
                    return "<span class='label label-warning'>On Delivery</span>";
                    break;
                case 4:
                    return "<span class='label label-success'>Delivered</span>";
                    break;
                default:
                    return "<span class='label label-danger'>Cancelled</span>";
                    break;
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
    function convert_timezone($time , $with_hours = false , $with_timezone = true , $hour_only = false , $custom_format_date_with_hour = "M d Y h:i:s A" , $custom_format_date = "M d Y" , $custom_format_hour = "h:i:s A")
    {

        if(!$time OR $time == 0){
            return "NA";
        }

        if($with_timezone){
            //$timezone = get_timezone();
            $timezone = "Asia/Kuala_Lumpur";

            if($with_hours){
                $date_format = $custom_format_date_with_hour;
            }else if($hour_only){
                $date_format = $custom_format_hour;
            }else{
                $date_format = $custom_format_date;
            }
            
            $triggerOn = date($date_format , $time);

            $tz = new DateTimeZone($timezone);
            $datetime = new DateTime($triggerOn);
            $datetime->setTimezone($tz);

            return $datetime->format( $date_format );
        }else{
            if($with_hours){
                $date_format = $custom_format_date_with_hour;
            }else if($hour_only){
                $date_format = $custom_format_hour;
            }else{
                $date_format = $custom_format_date;
            }

            return date($date_format , $time);
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
   
        $formatted = "RM ";

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
        $now = gmdate("D, d M Y H:i:s");
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
