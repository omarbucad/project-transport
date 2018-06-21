<?php

class Invoice_model extends CI_Model {

    public function get_admin_accounts($count = false){
        $skip = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
        $limit = ($this->input->get("limit")) ? $this->input->get("limit") : 10;

        if($count){
            return $result = $this->db->where("role","SUPER ADMIN")->get("user")->num_rows();
        }else{
            $result = $this->db->where("role","SUPER ADMIN")->limit($limit , $skip)->order_by("display_name" , "ASC")->get("user")->result();
        }
        
        foreach($result as $k => $row){
            $result[$k]->status = convert_status($row->status);
        }

        return $result;
    }

}