<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Input extends CI_Input {

    public function __construct(){
        parent::__construct();
    }

    public function post($index = NULL, $xss_clean = TRUE){
        return parent::post($index, $xss_clean);
    }
}