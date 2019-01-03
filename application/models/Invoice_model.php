<?php

class Invoice_model extends CI_Model {

    public function get_invoice_list($count = false){
        $skip = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
        $limit = ($this->input->get("limit")) ? $this->input->get("limit") : 10;

        $this->db->select("i.*,u.display_name, u.user_id, u.username, u.email_address, up.plan_created, up.plan_expiration, up.billing_type, up.who_updated, up.active, up.user_plan_id, u2.display_name as updated_by,p.*" );
        $this->db->join("user u","u.user_id = i.user_id");
        $this->db->join("user_plan up","up.user_plan_id = i.user_plan_id");        
        $this->db->join("user u2","u2.user_id = up.who_updated");    
        $this->db->join("plan p","p.planId = up.plan_id");
        $this->db->where("i.deleted IS NULL");    

        if($count){
            return $result = $this->db->get("invoice i")->num_rows();
        }else{
            $result = $this->db->order_by("i.created","DESC")->get("invoice i")->result();
        }
        
        foreach($result as $k => $row){
            $result[$k]->active = convert_status($row->active,true);
            $result[$k]->created = convert_timezone($row->created,true);
            $result[$k]->plan_created = convert_timezone($row->plan_created,true);
            $result[$k]->plan_expiration = convert_timezone($row->plan_expiration,true);
            $result[$k]->price = custom_money_format($result[$k]->price);
            $result[$k]->plan_price = custom_money_format($result[$k]->plan_price);
            $result[$k]->timeleft = $this->get_timeleft($result[$k]->user_plan_id);
        }

        return $result;
    }

    public function get_invoice($invoice_id){

        $this->db->select("i.*, up.plan_created, up.plan_expiration, up.billing_type, up.who_updated, u2.display_name as updated_by, u.user_id, u.display_name,u.email_address,s.store_name, sa.*, sc.phone ,p.*");

        $this->db->join("user u","u.user_id = i.user_id");
        $this->db->join("user_plan up","up.user_plan_id = i.user_plan_id");        
        $this->db->join("user u2","u2.user_id = up.who_updated");        
        $this->db->join("plan p","p.planId = up.plan_id");
        $this->db->join("store s","s.store_id = u.store_id");
        $this->db->join("store_address sa","sa.store_address_id = s.address_id");
        $this->db->join("store_contact sc","sc.contact_id = s.contact_id");
        $this->db->where("i.deleted IS NULL");

        $result = $this->db->where("i.invoice_id",$invoice_id)->get("invoice i")->row();
        
        $result->created = convert_timezone($result->created,true);
        $result->plan_created = convert_timezone($result->plan_created,true);
        $result->plan_expiration = convert_timezone($result->plan_expiration,true);
        $result->price = custom_money_format($result->price);
        $result->plan_price = custom_money_format($result->plan_price);

        $result->address = $result->street1;
        $result->address .= ($result->street2) ? ", ".$result->street2 : "";
        $result->address .= ($result->suburb) ? ", ".$result->suburb : "";
        $result->address .= ($result->city) ? ", ".$result->city : "";
        $result->address .= ($result->postcode) ? ", ".$result->postcode : "";
        $result->address .= ($result->state) ? ", ".$result->state : "";
        $result->address .= ($result->country) ? " ,".$result->country : "";

        return $result;
    }

    public function user_invoice($user_id){

        $this->db->select("i.*, up.plan_created, up.plan_expiration, up.billing_type, up.who_updated, u2.display_name as updated_by, u.display_name,u.email_address,s.store_name, sa.*, sc.phone ,p.*");

        $this->db->join("user u","u.user_id = i.user_id");
        $this->db->join("user_plan up","up.user_plan_id = i.user_plan_id");        
        $this->db->join("user u2","u2.user_id = up.who_updated");        
        $this->db->join("plan p","p.planId = up.plan_id");
        $this->db->join("store s","s.store_id = u.store_id");
        $this->db->join("store_address sa","sa.store_address_id = s.address_id");
        $this->db->join("store_contact sc","sc.contact_id = s.contact_id");
        $this->db->where("i.deleted IS NULL");

        $result = $this->db->where("i.user_id",$user_id)->order_by("i.created","DESC")->limit(1)->get("invoice i")->row();
        
        $result->created = convert_timezone($result->created,true);
        $result->plan_created = convert_timezone($result->plan_created,true);
        $result->plan_expiration = convert_timezone($result->plan_expiration,true);        
        $result->plan_price = custom_money_format($result->plan_price);
        $result->price = custom_money_format($result->price);

        $result->address = $result->street1;
        $result->address .= ($result->street2) ? ", ".$result->street2 : "";
        $result->address .= ($result->suburb) ? ", ".$result->suburb : "";
        $result->address .= ($result->city) ? ", ".$result->city : "";
        $result->address .= ($result->postcode) ? ", ".$result->postcode : "";
        $result->address .= ($result->state) ? ", ".$result->state : "";
        $result->address .= ($result->country) ? " ,".$result->country : "";

        return $result;
    }

    public function get_timeleft($user_plan_id){
        $this->db->select("plan_expiration");
        $this->db->where("user_plan_id",$user_plan_id);
        $result = $this->db->get("user_plan")->row()->plan_expiration;

        $today = date("M d Y 00:00:00");
        $expiry = convert_timezone($result, true);
        $timeleft = strtotime($expiry) - strtotime($today);
        
        $days = floor($timeleft / 86400);
        $timeleft %= 86400;

        $hours = floor($timeleft / 3600);
        $timeleft %= 3600;

        $minutes = floor($timeleft / 60);
        $timeleft %= 60;

        $left =  "$days day(s) $hours hr(s) $minutes min(s)";

        return $left;
    }


}