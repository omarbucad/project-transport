<?php

class Vehicle_model extends CI_Model {

    public function get_vehicle_list(){
        $store_id = $this->data['session_data']->store_id;

        /*
            TODO :: Searching logic here
        */

        if($vehicle_id = $this->input->get("vehicle_id")){
            
            $vehicle_id = $this->hash->decrypt($vehicle_id);

            $this->db->where("vehicle_id" , $vehicle_id);
        }

        $result = $this->db->where("store_id" , $store_id)->order_by("vehicle_registration_number" , "ASC")->get("vehicle")->result();

        foreach($result as $key => $row){
            $result[$key]->status = convert_status($row->status);
        }

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

    public function get_trailer_list(){
        $store_id = $this->data['session_data']->store_id;

        /*
            TODO :: Searching logic here
        */
            
        if($trailer_id = $this->input->get("trailer_id")){
            
            $trailer_id = $this->hash->decrypt($trailer_id);

            $this->db->where("trailer_id" , $trailer_id);
        }

        $result = $this->db->where("store_id" , $store_id)->order_by("trailer_number" , "ASC")->get("trailer")->result();

        foreach($result as $key => $row){
            $result[$key]->status = convert_status($row->status);
        }

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
}