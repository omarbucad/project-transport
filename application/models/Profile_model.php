<?php

class Profile_model extends CI_Model {

    public function get_profile(){

        $user_id = $this->data['session_data']->user_id;
        $store_id = $this->data['session_data']->store_id;

        
        $this->db->select("
            u.user_id, u.display_name, u.email_address, u.username, u.firstname, u.lastname, u.image_path, u.image_name, u.role, u.status,
            s.store_id, s.store_name, s.address_id, s.logo_image_path, s.logo_image_name,
            sa.street1, sa.street2, sa.suburb, sa.city, sa.postcode, sa.state, sa.country, sa.countryCode
        ");
        $this->db->join("store s","s.store_id = u.store_id");
        $this->db->join("store_address sa","sa.store_address_id = s.address_id");

        /*
            TODO :: Searching logic here
        */
        
        // if($checklist_id = $this->input->get("checklist_id")){

            
        //     $checklist_id = $this->hash->decrypt($checklist_id);

        //     $this->db->where("checklist_id" , $checklist_id);
        // }

        $this->db->where("u.deleted IS NULL");

        $result = $this->db->where("user_id" , $user_id)->get("user u")->row();
        $result->status = convert_status($result->status);
        $result->logo = ($result->logo_image_path != 'public/img/') ? $this->config->site_url("public/upload/company/".$result->logo_image_path."80/80/".$result->logo_image_name) : $this->config->site_url($result->logo_image_path.$result->logo_image_name);

        return $result;
    }
    public function update_profile($user_id){
        $post = $this->input->post();

        $this->db->trans_start();

        $this->db->where("user_id",$user_id);
        $this->db->update("user", [
            "display_name"  => $post["display_name"],
            "firstname"  => $post["firstname"],
            "lastname"  => $post["lastname"]
        ]);

        if($post["password"] != ""){
            $this->db->where("user_id",$user_id);
            $this->db->update("user", [
                "password"  => md5($post["password"])
            ]);
        }

        $this->db->where("store_address_id", $post["address_id"]);
        $this->db->update("store_address", [
            "street1"       => $post["physical"]["street1"],
            "street2"       => $post["physical"]["street2"],
            "suburb"        => $post["physical"]["suburb"],
            "city"          => $post["physical"]["city"],
            "postcode"      => $post["physical"]["postcode"],
            "state"         => $post["physical"]["state"],
            "country"       => $post["physical"]["country"],
            "countryCode"   => $post["physical"]["countryCode"]
        ]);

        
        $this->do_upload($user_id);
        

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $user_id;
        }
    }

    private function do_upload($user_id){
        $year = date("Y");
        $month = date("m");
        $folder = "./public/upload/user/".$year."/".$month;

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
            mkdir($folder.'/thumbnail', 0777, true);

            create_index_html($folder);
        }
    
        $image_name = md5($user_id).'_'.time().'_'.$_FILES['file']['name'];
        $image_name = str_replace("^", "_", $image_name);
       
        $config['upload_path']          = $folder;
        $config['allowed_types']        = 'gif|jpg|png';
        $config['file_name']            = $image_name;

        $this->load->library('upload', $config);
        $this->load->library('image_lib');

        if ($this->upload->do_upload('file')){
            $this->db->where("user_id" , $user_id)->update("user" , [
                "image_path" => $year."/".$month ,
                "image_name" => $image_name
            ]);
        }
    }

    private function logo_upload($store_id){
        $year = date("Y");
        $month = date("m");
        $folder =  $folder = "./public/upload/company/".$year."/".$month;

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
            mkdir($folder.'/thumbnail', 0777, true);

            create_index_html($folder);
        }
    
        $image_name = md5($store_id).'_'.time().'_'.$_FILES['file']['name'];
        $image_name = str_replace("^", "_", $image_name);
       
        $config['upload_path']          = $folder;
        $config['allowed_types']        = 'gif|jpg|png';
        $config['file_name']            = $image_name;

        $this->load->library('upload', $config);
        $this->load->library('image_lib');

        if ($this->upload->do_upload('file')){
            $this->db->where("store_id" , $store_id)->update("store" , [
                "image_path" => $year."/".$month ,
                "image_name" => $image_name
            ]);
        }
    }

    public function get_userplan($user_id){
        $this->db->select("up.*,p.*");
        $this->db->where("u.user_id",$user_id);
        $this->db->where("up.active",1);
        $this->db->join("user u","u.store_id = up.store_id");
        $this->db->join("plan p","p.planId = up.plan_id");
        $result = $this->db->get("user_plan up")->row();
        if($result->plan_expiration != 0){
            $today = date("d/M/Y 00:00:00");
            $expiry = convert_timezone($result->plan_expiration, true);
            $timeleft = strtotime($expiry) - strtotime(str_replace("/"," ",$today));
            
            $days = floor($timeleft / 86400);
            $timeleft %= 86400;

            $hours = floor($timeleft / 3600);
            $timeleft %= 3600;

            $minutes = floor($timeleft / 60);
            $timeleft %= 60;

            $left =  "$days days $hours hours $minutes minutes";
            $result->trial_left = $left;

            return $result;
        }else{
            return "No expiration";
        }

        
    }
}