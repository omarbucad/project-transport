<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Thumbs extends CI_Controller {
 
    public function __construct(){
        parent::__construct();
        $this->load->library('image_lib');
    }

    public function images($type , $year , $month , $width, $height, $img){
        $path = $year.'/'.$month;

        // Checking if the file exists, otherwise we use a default image
        $img = is_file('public/upload/'.$type.'/'.$path.'/'.$img) ? $img : false;
      
        // If the thumbnail already exists, we just read it
        // No need to use the GD library again
        if( !is_file('public/upload/'.$type.'/'.$path.'/thumbnail/'.$width.'x'.$height.'_'.$img) ){
           
            $config['source_image'] = 'public/upload/'.$type.'/'.$path.'/'.$img;
            $config['new_image'] = 'public/upload/'.$type.'/'.$path.'/thumbnail/'.$width.'x'.$height.'_'.$img;
            $config['width'] = $width;
            $config['height'] = $height;
            
            $this->image_lib->clear(); 
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
        }
        header('Content-Type: image/jpg');
        if($img){
            readfile('public/upload/'.$type.'/'.$path.'/thumbnail/'.$width.'x'.$height.'_'.$img);
        }else{
            readfile('public/img/person-placeholder.jpg');
        }
        
    }
 
}