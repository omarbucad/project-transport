<?php

class Vehicle_model extends CI_Model {

    public function get_vehicle_list(){
        $store_id = $this->data['session_data']->store_id;

        //$plan = $this->data['session_data']->title;

        if($date = $this->input->get("last_checked")){
            $date  = explode("-", $date);
            $start = strtotime(trim($date[0].' 00:00'));
            $end   = strtotime(trim($date[1].' 23:59'));

            $this->db->where("v.last_checked >= " , $start);
            $this->db->where("v.last_checked <= " , $end);
        }   

        if($this->input->get("type") != ""){
            $this->db->where("v.vehicle_type_id", $this->input->get("type"));
        }
        if($this->input->get("availability") != ""){
            $this->db->where("v.availability", $this->input->get("availability"));
        }
        if($reg_number = $this->input->get("registration_number")){
            $this->db->like("v.vehicle_registration_number", $reg_number);
        }

        if($vehicle_id = $this->input->get("vehicle_id")){
            
            $vehicle_id = $this->hash->decrypt($vehicle_id);

            $this->db->where("v.vehicle_id" , $vehicle_id);
        }

        $this->db->where("v.deleted IS NULL");


        // if($plan == "Basic"){
        //     $limit = 1;
        // }else if($plan == "Standard"){
        //     $limit = 10;
        // }else{
            $limit = 0;
        //}
        $this->db->select("v.*,vt.type");
        $this->db->join("vehicle_type vt","vt.vehicle_type_id = v.vehicle_type_id");
        $result = $this->db->where("store_id" , $store_id)->order_by("v.vehicle_registration_number" , "ASC")->limit($limit)->get("vehicle v")->result();

        foreach($result as $key => $row){
            $result[$key]->status = convert_vehicle_status($row->status);
            $result[$key]->availability = convert_availability($row->availability);
            if($result[$key]->last_checked != ''){
                $result[$key]->last_checked = convert_timezone($row->last_checked, true);
            }

            switch ($result[$key]->type) {
                case 'Van':
                    $result[$key]->type_img = site_url("public/img/vehicles/van.png");
                    break;
                case 'Truck':
                    $result[$key]->type_img = site_url("public/img/vehicles/truck2.png");
                    break;
                case 'Forklift':
                    $result[$key]->type_img = site_url("public/img/vehicles/forklift.png");
                    break;
                case 'Bus':
                    $result[$key]->type_img = site_url("public/img/vehicles/bus.png");
                    break;
                case 'Trailer':
                    $result[$key]->type_img = site_url("public/img/vehicles/truck2.png");
                    break;
                case 'Cement Mixer':
                    $result[$key]->type_img = site_url("public/img/vehicles/cement_truck.png");
                    break;
            }
        }
        return $result;
    }

    public function vehicle_type_list(){
        $this->db->where("deleted IS NULL");
        $this->db->where("status",1);
        $result = $this->db->get("vehicle_type")->result();
        return $result;
    }

    
    public function get_available_vehicle(){
        $store_id = $this->data['session_data']->store_id;
        $this->db->select("v.*, vt.type");
        $this->db->where("v.availability",1);
        $this->db->where("v.deleted IS NULL");
        $this->db->join("vehicle_type vt","vt.vehicle_type_id = v.vehicle_type_id");
        $result = $this->db->where("v.store_id" , $store_id)->get("vehicle v")->result();

        if($result){
            foreach ($result as $key => $row) {
                if($result[$key]->last_checked != ''){
                    $result[$key]->last_checked = convert_timezone($row->last_checked, true);
                }
            }
            return $result;
        }else{
            return $result;
        }
    }

    public function get_unavailable_vehicle(){
        $store_id = $this->data['session_data']->store_id;
        $this->db->select("v.*, vt.type");
        $this->db->where("v.availability",0);
        $this->db->where("v.deleted IS NULL");
        $this->db->join("vehicle_type vt","vt.vehicle_type_id = v.vehicle_type_id");
        $result = $this->db->where("v.store_id" , $store_id)->get("vehicle v")->result();

        if($result){
            foreach ($result as $key => $row) {
                if($result[$key]->last_checked != ''){
                    $result[$key]->last_checked = convert_timezone($row->last_checked, true);
                }
            }
            return $result;
        }else{
            return $result;
        }
    }
    
    public function get_activetrailer(){
        $store_id = $this->data['session_data']->store_id;
        $this->db->where("status",1);
        return $result = $this->db->where("store_id" , $store_id)->get("trailer")->result();
    }
   
    public function get_truck($vehicle_id){
        $id = $this->hash->decrypt($vehicle_id);
        $this->db->where("vehicle_id", $id);
        $result = $this->db->get('vehicle')->row();

        return $result;
    }

    public function add_truck(){
        $store_id = $this->data['session_data']->store_id;

        $plan = $this->data['session_data']->title;
        $count = $this->data['session_data']->vehicle_limit;
        $vehicle = $this->db->where("store_id",$this->data['session_data']->title)->get("vehicle")->num_rows();

        if($plan == "Basic" && $vehicle < $count){
            $valid = true;
        }else if($plan == "Standard" && $vehicle < $count){
            $valid = true;
        }else if(($plan == "Trial" || $plan == "Premium") && $count == 0){
            $valid = true;
        }else if($vehicle >= $count){
            $valid = false;
        }
        if($valid){
            $this->db->insert("vehicle" , [
                "vehicle_registration_number"   => $this->input->post("registration_number"),
                "vehicle_type_id"               => $this->input->post("type"),
                "status"                        => 0,
                "availability"                  => 1,
                "last_checked"                  => NULL,
                "store_id"                      => $store_id ,
                "created"                       => time()
            ]);

            return $this->db->insert_id();
        }else{
            return $valid;
        }
    }

    public function edit_truck($vehicle_id){
        $id = $this->hash->decrypt($vehicle_id);
        $this->db->trans_start();

        $this->db->where("vehicle_id", $id);
        $this->db->update("vehicle" , [
            "vehicle_registration_number"   => $this->input->post("vehicle_registration_number"),
            "vehicle_type_id"               => $this->input->post("type"),
            "availability"                  => 1
        ]);
        
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $id;
        }
        
    }

    public function delete_truck($vehicle_id){
        $id = $this->hash->decrypt($vehicle_id);
        $this->db->trans_start();

        $this->db->where('vehicle_id', $id);
        $this->db->update("vehicle" , [
            "deleted" => time()
        ]);

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return false;
        }else{
           return true;
        }      
    }

    public function get_trailer_list(){
        $store_id = $this->data['session_data']->store_id;

        $plan = $this->data['session_data']->title;

        /*
            TODO :: Searching logic here
        */

        if($this->input->get("status") != ""){
            $this->db->where("status", $this->input->get("status"));
        }
        
        if($trailer_number = $this->input->get("trailer_number")){
            $this->db->where("trailer_number", $trailer_number);
        }
            
        if($trailer_id = $this->input->get("trailer_id")){
            
            $trailer_id = $this->hash->decrypt($trailer_id);

            $this->db->where("trailer_id" , $trailer_id);
        }

        $this->db->where("deleted IS NULL");

        if($plan == "Basic"){
            $limit = 1;
        }else if($plan == "Standard"){
            $limit = 10;
        }else{
            $limit = 0;
        }

        $result = $this->db->where("store_id" , $store_id)->order_by("trailer_number" , "ASC")->limit($limit)->get("trailer")->result();

        foreach($result as $key => $row){
            $result[$key]->status = convert_status($row->status);
        }

        return $result;
    }

    public function get_trailer($trailer_id){
        $id = $this->hash->decrypt($trailer_id);
        $this->db->where("trailer_id", $id);
        $result = $this->db->get('trailer')->row();

        return $result;
    }

    public function add_trailer(){
        $store_id = $this->data['session_data']->store_id;

        $plan = $this->data['session_data']->title;
        $count = $this->data['session_data']->vehicle_limit;
        $trailer = $this->db->where("store_id",$this->data['session_data']->title)->get("trailer")->num_rows();

        if($plan == "Basic" && $trailer < $count){
            $valid = true;
        }else if($plan == "Standard" && $trailer < $count){
            $valid = true;
        }else if(($plan == "Trial" || $plan == "Premium") && $count == 0){
            $valid = true;
        }else if($vehicle >= $count){
            $valid = false;
        }

        if($valid){
             $this->db->insert("trailer" , [
                "trailer_number"                => $this->input->post("trailer_number"),
                "status"                        => 1 ,
                "store_id"                      => $store_id ,
                "description"                   => $this->input->post("description"),
                "created"                       => time()
            ]);

            return $this->db->insert_id();
        }else{
            return $valid;
        }
    }

    public function edit_trailer($trailer_id){
        $id = $this->hash->decrypt($trailer_id);
        $this->db->trans_start();

        $this->db->where("trailer_id", $id);
        $this->db->update("trailer" , [
            "trailer_number"                => $this->input->post("trailer_number"),
            "status"                        => $this->input->post("status"),
            "description"                   => $this->input->post("description")
        ]);
        
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $id;
        }
        
    }

    public function delete_trailer($trailer_id){
        $id = $this->hash->decrypt($trailer_id);
        $this->db->trans_start();

        $this->db->where('trailer_id', $id);
        $this->db->update("trailer" , [
            "deleted" => time()
        ]);

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return false;
        }else{
           return true;
        }      
    }

    // VEHICLE TYPE

    public function get_type_list(){

        /*
            TODO :: Searching logic here
        */
        if($this->input->get("status") != ""){
            $this->db->where("status", $this->input->get("status"));
        }

        if($type_name = $this->input->get("type_name")){
            $this->db->where("type", $type_name);
        }


        $this->db->where("deleted IS NULL");
        $result = $this->db->get("vehicle_type")->result();


        foreach($result as $key => $row){
            $result[$key]->status = convert_status($row->status);
        }
        return $result;
    }

    public function get_type_info($type_id){
        $id = $this->hash->decrypt($type_id);

        $this->db->where('vehicle_type_id',$id);
        $result = $this->db->get('vehicle_type')->row();

        return $result;
        
    }

    public function add_type(){

        $this->db->insert('vehicle_type',[
            "type" => $this->input->post('name'),
            "created" => time(),
            "status" => 1
        ]);

        return $this->db->insert_id();
        
    }

    public function edit_type($type_id){

        $this->db->trans_start();

        $this->db->where('vehicle_type_id',$type_id);
        $this->db->update('vehicle_type',[
            "type" => $this->input->post('name'),
            "status" => $this->input->post('status')
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return true;
        }
        
    }

    public function delete_type($type_id){
        $type_id = $this->hash->decrypt($type_id);

        $this->db->trans_start();

        $this->db->where('vehicle_type_id',$type_id);
        $this->db->update('vehicle_type',[
            "deleted" => time()
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return true;
        }        
    }

    public function tire_management_list(){
        $this->db->trans_start();

        $this->db->select("tir.*");
        $this->db->join("tire_info_report tir","tir.tire_report_id = tr.tire_report_id");
        $this->db->join("vehicle v","v.vehicle_id = tr.vehicle_id");
        $this->db->where("tr.store_id",$this->session->userdata("user")->store_id);               
        $this->db->where("v.is_active",1);
        // if(isset($data->vehicle_id)){
        //     $this->db->where("v.vehicle_id",$vehicle_id);
        // }

        if($this->input->get("registration_number") != ""){
            $this->db->where("v.vehicle_registration_number", $this->input->get("registration_number"));
        }

        if($this->input->get("type") != ""){
            $this->db->where("v.vehicle_type_id", $this->input->get("type"));
        }

        if($this->input->get("status") != ""){
            $this->db->where("tr.status", $this->input->get("status"));
        }

        if($this->input->get("created") != ""){
            $date =  $this->input->get("created");

            $date  = explode("-", $date);
            $start = strtotime(trim($date[0].' 00:00'));
            $end   = strtotime(trim($date[1].' 23:59'));

            $this->db->where("tr.created >= " , $start);
            $this->db->where("tr.created <= " , $end);
        }

        $this->db->order_by("tr.created","DESC");
        $data = $this->db->get("tire_report tr")->result();

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            foreach ($data as $key => $value) {
                $this->db->select('image_name,image_path');
                $this->db->where('tire_report_id', $value->tire_report_id);
                $this->db->where("tir_id", $value->tir_id);
                $damage_images = $this->db->where("type",1)->get('tire_images')->result();
                $data[$key]->damage_images = $damage_images;

                $this->db->select('image_name,image_path');
                $this->db->where('tire_report_id', $value->tire_report_id);
                $this->db->where("tir_id", $value->tir_id);
                $tread_images = $this->db->where("type",2)->get('tire_images')->result();
                $data[$key]->tread_images = $tread_images;
            }
           return $data;
        }
    }
}