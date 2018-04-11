<?php

class Vehicle_model extends CI_Model {

    public function get_vehicle_list(){
        $store_id = $this->data['session_data']->store_id;

        /*
            TODO :: Searching logic here
        */
        if($this->input->get("status") != ""){
            $this->db->where("status", $this->input->get("status"));
        }

        if($reg_number = $this->input->get("registration_number")){
            $this->db->where("vehicle_registration_number", $reg_number);
        }

        if($vehicle_id = $this->input->get("vehicle_id")){
            
            $vehicle_id = $this->hash->decrypt($vehicle_id);

            $this->db->where("vehicle_id" , $vehicle_id);
        }

        $this->db->where("deleted IS NULL");

        $result = $this->db->where("store_id" , $store_id)->order_by("vehicle_registration_number" , "ASC")->get("vehicle")->result();

        foreach($result as $key => $row){
            $result[$key]->status = convert_status($row->status);
        }

        return $result;
    }

    public function get_truck($vehicle_id){
        $id = $this->hash->decrypt($vehicle_id);
        $this->db->where("vehicle_id", $id);
        $result = $this->db->get('vehicle')->row();

        return $result;
    }

    public function add_truck(){
        $store_id = $this->data['session_data']->store_id;

        $this->db->insert("vehicle" , [
            "vehicle_registration_number"   => $this->input->post("registration_number"),
            "status"                        => 1 ,
            "store_id"                      => $store_id ,
            "description"                   => $this->input->post("description"),
            "created"                       => time()
        ]);

        return $this->db->insert_id();
    }

    public function edit_truck($vehicle_id){
        $id = $this->hash->decrypt($vehicle_id);
        $this->db->trans_start();

        $this->db->where("vehicle_id", $id);
        $this->db->update("vehicle" , [
            "vehicle_registration_number"   => $this->input->post("vehicle_registration_number"),
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
        $this->db->where("store_id" , $store_id);

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
            //print_r_die($trailer_id);

            $this->db->where("trailer_id" , $trailer_id);
        }

        $this->db->where("deleted IS NULL");

        $result = $this->db->order_by("trailer_number" , "ASC")->get("trailer")->result();

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

        $this->db->insert("trailer" , [
            "trailer_number"                => $this->input->post("trailer_number"),
            "status"                        => 1 ,
            "store_id"                      => $store_id ,
            "description"                   => $this->input->post("description"),
            "created"                       => time()
        ]);

        return $this->db->insert_id();
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
}