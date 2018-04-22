<?php

class Checklist_model extends CI_Model {

    public function get_checklist_list(){
        $store_id = $this->data['session_data']->store_id;

        /*
            TODO :: Searching logic here
        */
        if($checklist_name = $this->input->get("checklist_name")){
            $this->db->where("checklist_name", $checklist_name);
        }
        if($this->input->get("status") != ""){
            $this->db->where("status", $this->input->get("status"));
        }

        if($checklist_id = $this->input->get("checklist_id")){

            
            $checklist_id = $this->hash->decrypt($checklist_id);

            $this->db->where("checklist_id" , $checklist_id);
        }

        $this->db->where("deleted IS NULL");

        $result = $this->db->where("store_id" , $store_id)->order_by("created" , "ASC")->get("checklist")->result();

        foreach($result as $key => $row){
            $result[$key]->status = convert_status($row->status);
        }

        return $result;
    }

    public function add_checklist_name(){
        $store_id = $this->data['session_data']->store_id;

        $this->db->insert("checklist" , [
            "checklist_name"   => $this->input->post("checklist_name"),
            "status"           => 1,
            "store_id"         => $store_id ,
            "description"      => $this->input->post("description"),
            "vehicle_type"     => $this->input->post("vehicle_type"),
            "checklist_for"    => $this->input->post("checklist_for"),
            "reminder_every"   => ($this->input->post("checklist_for") == "MECHANIC") ? $this->input->post("reminder") : "",
            "created"          => time()
        ]);

        return $this->db->insert_id();
    }

    public function add_checklist_item(){
    
        $items = $this->input->post("item");
        $a = $this->input->post("account");

        $images = $_FILES['file'];
        
        $this->db->trans_start();

        $checklist_id = $this->add_checklist_name();
        
        $batch = array();

        foreach($items['name'] as $k => $name){

            $file = [
                'name'  => $images['name'][$k],
                'type'  => $images['type'][$k],
                'tmp_name'  => $images['tmp_name'][$k],
                'error'  => $images['error'][$k],
                'size'  => $images['size'][$k],
            ];

            if($file['name']){
                $img = $this->upload_image($file);
            }else{
                $img = array(
                    "image_path" => "",
                    "image_name" => ""
                );
            }

            $batch[] = array(
                "checklist_id"  => $checklist_id ,
                "item_name"     => $name ,
                "help_text"     => $items['help'][$k],
                "item_position" => $items['position'][$k] ,
                "image_path"    => $img['image_path'],
                "image_name"    => $img['image_name']
            );
        }

        $this->db->insert_batch("checklist_items" , $batch);

        $accounts = array();
        
        foreach($a as $row){
            $accounts[] = [
                "user_id"       => $row ,
                "checklist_id"  => $checklist_id
            ];
        }

        $this->db->insert_batch("user_checklist" , $accounts);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $checklist_id;
        }
    }

    public function get_meech_and_driver_list(){
         $store_id = $this->data['session_data']->store_id;
         $this->db->select("user_id , display_name , image_path , image_name , role");
         $result = $this->db->where("store_id" , $store_id)->where_in("role" , ['mechanic' , 'driver'])->order_by("display_name" , "ASC")->get("user")->result();

         foreach($result as $key => $row){
            $result[$key]->images = $this->config->site_url("thumbs/images/user/".$row->image_path."/50/50/".$row->image_name);
         }

         return $result;
    }

    private function upload_image($file){
        $year = date("Y");
        $month = date("m");
        $folder = "./public/upload/checklist/".$year."/".$month;

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
            mkdir($folder.'/thumbnail', 0777, true);
            create_index_html($folder);
        }

        $_FILES['file'] = $file;

        $image_name = md5(time()).'_'.$_FILES['file']['name'];
        $image_name = str_replace("^", "_", $image_name);

        $config['upload_path']          = $folder;
        $config['allowed_types']        = 'jpg|png';
        $config['file_name']            = $image_name;

        $this->load->library('upload', $config);
        $this->load->library('image_lib');

        if ($this->upload->do_upload('file')){

            return [
                "image_path" => $year."/".$month ,
                "image_name" => $image_name
            ];

        }else{
            return [
                "image_path" => "",
                "image_name" => ""
            ];
        }

    }

    public function get_checklist_items($checklist_id){
        $id = $this->hash->decrypt($checklist_id);
        $this->db->where("checklist_id", $id);
        $this->db->where("deleted IS NULL");
        $result = $this->db->order_by("item_position", "ASC")->get("checklist_items")->result_array();

        return $result;
    }

    public function edit_checklist($checklist_id){
        $checklistid = $this->hash->decrypt($checklist_id);

        $this->db->trans_start();

        $this->db->where("checklist_id", $checklistid);
        $checklist_info = $this->db->update("checklist", [
            "checklist_name" => $this->input->post("checklist_name"),
            "description"    => $this->input->post("description"),
            "vehicle_type"     => $this->input->post("vehicle_type"),
            "status"         => $this->input->post("status")
        ]);

        $items = $this->input->post("items");


        $batch = array();
        foreach($items['id'] as $k => $id){
            $batch[] = array(
                "id"            => $id,
                "item_name"     => $items['name'][$k],
                "item_position" => $items['position'][$k]
            );
        }

        foreach($batch as $key => $value){
            if($value['id'] == 0){

                $this->db->insert('checklist_items',[
                    "checklist_id"  => $checklistid,
                    "item_name"     => $value['item_name'],
                    "item_position" => $value['item_position']
                ]);
            }
            else{
                $this->db->where("id", $value['id']);
                $this->db->update('checklist_items',[
                    "item_name"     => $value['item_name'],
                    "item_position" => $value['item_position']
                ]);
            }
        }

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return false;
        }else{
           return true;
        }      
    }

    public function edit_checklist_item($item_id, $name, $position){

        $this->db->where("id", $item_id);
        $this->db->update("checklist_items", [
            "item_name" => $name,
            "item_position" => $position
        ]);

    }   

    public function delete_checklist($checklist_id){

        $id = $this->hash->decrypt($checklist_id);
        $this->db->trans_start();

        $this->db->where('checklist_id', $id);
        $this->db->update("checklist" , [
            "deleted" => time()
        ]);

        $this->db->where("checklist_id",$id);
        $this->db->update("checklist_items",[
            "deleted" => time()
        ]);

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return false;
        }else{
           return true;
        }      
    }

    public function delete_checklist_item($item_id){
        $this->db->trans_start();
        
        $this->db->where("id",$item_id);
        $this->db->update("checklist_items",[
            "deleted" => time()
        ]);

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return false;
        }else{
           return true;
        }      
    }

    public function get_checklist_dropdown(){
        $store_id = $this->data['session_data']->store_id;


        $this->db->where("deleted IS NULL");
        $this->db->where("status !=", 0);

        $result = $this->db->where("store_id" , $store_id)->order_by("created" , "ASC")->get("checklist")->result();

        return $result;
    }

    public function get_checklist($checklist_id){

        $id = $this->hash->decrypt($checklist_id);

        $this->db->where("checklist_id", $id);
        $result = $this->db->get('checklist')->row();
        return $result;
    }
}