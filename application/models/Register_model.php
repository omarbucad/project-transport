<?php

class Register_model extends CI_Model {

    public function signup(){
    	$this->db->trans_start();

    	/* ADDRESS */

        $this->db->insert("address" , [
        	"country" => $this->input->post("country") , 
        	"city" => $this->input->post("city") , 
        	"postcode" => $this->input->post("postcode")
        ]);
        $physical_address_id = $this->db->insert_id();

        $this->db->insert("address" , [
        	"country" => $this->input->post("country") , 
        	"city" => $this->input->post("city") , 
        	"state" => $this->input->post("state") , 
        	"postcode" => $this->input->post("postcode")
        ]);
        $postal_address_id = $this->db->insert_id();

        /*  USER INFORMATION  */
        $this->db->insert("user" ,[
            "display_name"  		=> $this->input->post("first_name").' '.$this->input->post("last_name") ,
            "username"      		=> $this->input->post("username"),
            "password"      		=> md5($this->input->post("password")),
            "email_address" 		=> $this->input->post("email_address"),
            "role"          		=> "ADMIN" ,
            "phone_number"			=> $this->input->post("phone"),
            "physical_address_id" 	=> $physical_address_id,
            "created"       		=> time()
        ]);
        $user_id = $this->db->insert_id();

        

        /* STORE CONTACT INFORMATION */

        $this->db->insert("store_contact" , [
            "first_name"    => $this->input->post("first_name") ,
            "last_name"     => $this->input->post("last_name") ,
            "email"         => $this->input->post("email_address") ,
            "mobile"         => $this->input->post("phone")
        ]);

        $contact_id = $this->db->insert_id();

        /* STORE INFORMATION */
        $store_subdomain = str_replace(" ", "", $this->input->post("store_name"));
        $store_subdomain = strtolower($store_subdomain);

        $this->db->insert("store" , [
            "store_name"            => $this->input->post("store_name") ,
            "user_id"               => $user_id ,
            "physical_address"      => $physical_address_id ,
            "postal_address"        => $postal_address_id ,
            "contact_id"            => $contact_id ,
            "created"               => time()
        ]);
        $store_id = $this->db->insert_id();

        /* STORE PLAN */

        $this->db->insert("user_plan" , [
            "store_id" => $store_id ,
            "plan_type" => "TRIAL" ,
            "plan_created" => time() ,
            "plan_expiration" => strtotime("+1 month") ,
            "who_updated" => $user_id ,
            "ip_address" => $this->input->ip_address() ,
            "active" => 1,
            "updated" => time()
        ]);

        /* UPDATE USER */
        $this->db->where("user_id" , $user_id)->update("user" , [
            "store_id" => $store_id
        ]);


        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return [
                "user_id"       => $user_id
            ];
        }
    }

    public function signin($user){
        $this->db->select("u.user_id , u.display_name , u.email_address , u.role , u.store_id , u.image_path , u.image_name");
        $this->db->select("up.plan_type");
        $this->db->select("a1.country");
        $this->db->join("user_plan up" , "up.store_id = u.store_id");
        $this->db->join("store s" , "s.store_id = u.store_id");
        $this->db->join("address a1" , "a1.address_id = s.physical_address");
        if(is_array($user)){
            $this->db->where("u.username" , $user['username']);
            $this->db->where("u.password" , md5($user['password']));
        }else{
            $this->db->where("u.user_id" , $user);
        }

        $result = $this->db->where("up.active" , 1)->get("user u")->row();
    
        if($result){
            $this->login_trail($result->user_id);
            return $result;
        }

        return false;
    }

    private function login_trail($user_id){
        $result = $this->db->insert("login_trail" , [
            "user_id"       => $user_id ,
            "ip_address"    => $this->input->ip_address() ,
            "user_agent"    => $_SERVER['HTTP_USER_AGENT'] ,
            "login_time"    => time()
        ]);
    }
}