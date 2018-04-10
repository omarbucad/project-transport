<?php

class Accounts_model extends CI_Model {

    public function get_accounts_list($count = false){
        $store_id = $this->data['session_data']->store_id;
        $skip = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
        $limit = ($this->input->get("limit")) ? $this->input->get("limit") : 10;

        if($count){
            return $result = $this->db->where("store_id" , $store_id)->get("user")->num_rows();
        }else{
            $result = $this->db->where("store_id" , $store_id)->limit($limit , $skip)->order_by("display_name" , "ASC")->get("user")->result();
        }
        
        foreach($result as $k => $row){
            $result[$k]->status = convert_status($row->status);
        }

        return $result;
    }

    public function add_users(){
        $store_id = $this->data['session_data']->store_id;

        $this->db->trans_start();

        $this->db->insert("user" , [
            "email_address"=> $this->input->post("email") ,
            "username"     => $this->input->post("username") ,
            "password"     => $this->input->post("password"),
            "display_name" => $this->input->post("display_name") ,
            "role"         => $this->input->post("role") ,
            "store_id"     => $store_id,
            "image_path"   => "public/image/",
            "image_name"   => "person-placeholder.jpg",
            "status"       => 1,
            "created"      => time()
        ]);

        $last_id = $this->db->insert_id();

        $this->do_upload($last_id);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $last_id;
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

}