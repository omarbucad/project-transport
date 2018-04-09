<?php

class Register_model extends CI_Model {

    public function signup(){
    	$this->db->trans_start();

    	/* ADDRESS */

        $this->db->insert("store_address" , [
        	"country" => $this->input->post("country") , 
            "city" => $this->input->post("city") , 
        	"state" => $this->input->post("state") , 
        	"postcode" => $this->input->post("postcode")
        ]);
        $address_id = $this->db->insert_id();

         /* STORE CONTACT INFORMATION */

        $this->db->insert("store_contact" , [
            "first_name"    => $this->input->post("first_name"),
            "last_name"     => $this->input->post("last_name"),
            "email"         => $this->input->post("email"),
            "phone"         => $this->input->post("phone") 
        ]);
        $contact_id = $this->db->insert_id();

        /*  USER INFORMATION  */
        $this->db->insert("user" ,[
            "display_name"  		=> $this->input->post("first_name").' '.$this->input->post("last_name") ,
            "username"      		=> $this->input->post("username"),
            "password"      		=> md5($this->input->post("password")),
            "email_address" 		=> $this->input->post("email_address"),
            "role"          		=> "ADMIN" ,
            "image_path"            => "public/image/",
            "image_name"            => "person-placeholder.jpg",
            "created"       		=> time()
        ]);
        $user_id = $this->db->insert_id();


        /* STORE INFORMATION */

        $this->db->insert("store" , [
            "store_name"            => $this->input->post("store_name") ,
            "address_id"            => $address_id ,
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
            return $user_id;
        }
    }

    public function signin($user){
        $this->db->select("u.user_id , u.display_name , u.email_address , u.role , u.store_id , u.image_path , u.image_name");
        $this->db->select("up.plan_type");
        $this->db->select("a1.country");
        $this->db->join("user_plan up" , "up.store_id = u.store_id");
        $this->db->join("store s" , "s.store_id = u.store_id");
        $this->db->join("store_address a1" , "a1.store_address_id = s.address_id");

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