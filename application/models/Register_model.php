<?php

class Register_model extends CI_Model {

    public function signup(){
    	$this->db->trans_start();

    	/* ADDRESS */

        $this->db->insert("store_address" , [
        	"country" => $this->input->post("country"),
            "city" => $this->input->post("city")
        ]);
        $address_id = $this->db->insert_id();

        /*  USER INFORMATION  */
        $this->db->insert("user" ,[
            "display_name"  		=> $this->input->post("first_name").' '.$this->input->post("last_name") ,
            "username"      		=> $this->input->post("username"),
            "password"      		=> md5($this->input->post("password")),
            "email_address" 		=> $this->input->post("email_address"),
            "status"                => 1,
            "role"          		=> "ADMIN" ,
            "image_path"            => "public/img/",
            "image_name"            => "person-placeholder.jpg",
            "created"       		=> time()
        ]);
        $user_id = $this->db->insert_id();


        /* STORE INFORMATION */

        $this->db->insert("store" , [
            "store_name"            => $this->input->post("store_name") ,
            "address_id"            => $address_id ,
            "created"               => time()
        ]);
        $store_id = $this->db->insert_id();

        /* STORE PLAN */

        $this->db->insert("user_plan" , [
            "store_id" => $store_id ,
            "plan_id" =>  "N/A",
            "plan_created" => time() ,
            "plan_expiration" => NULL,
            "billing_type"  => "NA",
            "who_updated" => $user_id ,
//            "ip_address" => $this->input->ip_address() ,
            "active" => 1,
            "updated" => time()
        ]);

        /* UPDATE USER */
        $this->db->where("user_id" , $user_id)->update("user" , [
            "store_id" => $store_id
        ]);

        //SETUP THE DEFAULT CHECKLIST IN THIS COMPANY
        //$this->setup_checklist($store_id);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $user_id;
        }
    }

    public function signin($user){
        $this->db->select("u.user_id , u.username, u.display_name ,u.firstname, u.lastname, u.email_address , u.role , u.store_id , u.image_path , u.image_name, u.phone, u.verified");
        $this->db->select("s.store_name, s.logo_image_path, s.logo_image_name, a1.*, up.plan_expiration, up.user_plan_id, up.subscription_id, up.vehicle_limit, up.billing_type, p.planId, p.title");
        // //$this->db->select("a1.country");

        $this->db->join("store s" , "s.store_id = u.store_id");
        $this->db->join("store_address a1" , "a1.store_address_id = s.address_id");
        $this->db->join("user_plan up" , "up.store_id = u.store_id");
        $this->db->join("plan p","p.planId = up.plan_id");

        if(is_array($user)){

            $this->db->where("u.password" , md5($user['password']));
            $this->db->group_start();
            $this->db->where("u.username" , $user['username']);
            $this->db->or_where("u.email_address", $user['username']);
            $this->db->group_end();
        }else{
            $this->db->group_start();
            $this->db->where("u.user_id" , $user);
            $this->db->or_where("u.email_address", $user['username']);
            $this->db->group_end();
        }
        $this->db->where("up.active" , 1);
        $this->db->where("u.deleted IS NULL");
        $result = $this->db->get("user u")->row();
        if($result){
            if($result->logo_image_name != ''){
                if($result->logo_image_path == 'public/img/'){
                    $result->company_logo = $this->config->site_url($result->logo_image_path.$result->logo_image_name);
                }else{
                    $result->company_logo = $this->config->site_url("public/upload/company/".$result->logo_image_path.$result->logo_image_name);
                }                
            }
            $this->login_trail($result->user_id);
            return $result;
        }else{
            return false;
        }        
    }

    private function login_trail($user_id){
        $result = $this->db->insert("login_trail" , [
            "user_id"       => $user_id ,
            "ip_address"    => $this->input->ip_address() ,
            "user_agent"    => $_SERVER['HTTP_USER_AGENT'] ,
            "login_time"    => time()
        ]);
    }

    private function setup_checklist($store_id){
        $this->truck_checklist($store_id);
        $this->trailer_checklist($store_id);
    }

    private function truck_checklist($store_id){

        $this->db->insert("checklist" , [
            "store_id"          => $store_id ,
            "checklist_name"    => "Vehicle Checklist" ,
            "description"       => "Default Checklist" ,
            "vehicle_type"      => "TRUCK",
            "status"            => 1 ,
            "created"           => time()
        ]);

        $checklist_id = $this->db->insert_id();

        $items = array(
            'Lamps/indicators/stop lamps',
            'Reflectors/markers/warning devices',
            'Battery Cover (security/condition)',
            'Mirrors (clean/condition/security)',
            'Brakes (Pressure/operation/leaks)',
            'Driving control (Steering/wear/operation)',
            'Tyres (Inflation/Any Damage/Wear)',
            'Wheels/Nuts (Condition/Security)',
            'Guards/Wings/Spray Suppression',
            'Body/Load (Security/Protection)',
            'Number Plates (Condition/Security/Bulbs)',
            'Horn/Wipers/Washers/Windscreen',
            'Engine Oil/Water (Fuel/level/leaks)',
            'Fuel Cap On and Secure',
            'Exhaust (condition/smoke/emission)',
            'Tachograph/Speedometer Working',
            'Speed Limiter Sticker Present',
            'Trailer Coupling and Condition',
            'Trailer Connection Wear/Leaks',
            'Trailer Landing Legs Working',
            'Spare Digital Roll',
            'Height Indicator Set at Vehicle Height',
            'Licence Disk Visible and In Date',
            'Reversing Alarm Working',
            'No Smoking Sticker in Cab',
            'HIABS/Tipper Rams All Working',
            'All Fors Cameras and Stickers Working',
            'Brakes(warning devices and instruments)'
        );

        $batch = array();

        foreach($items as $k => $v){
            $batch[] = array(
                "checklist_id"  => $checklist_id ,
                "item_name"     => $v ,
                "item_position" => $k
            );
        }

        $this->db->insert_batch("checklist_items" , $batch);

    }

    private function trailer_checklist($store_id){
        $this->db->insert("checklist" , [
            "store_id"          => $store_id ,
            "checklist_name"    => "Trailer Checklist" ,
            "description"       => "Default Checklist" ,
            "vehicle_type"      => "TRAILER",
            "status"            => 1 ,
            "created"           => time()
        ]);

        $checklist_id = $this->db->insert_id();

        $items = array(
            'Tyres & Wheel Nuts',
            'Pin/Coupling Condition',
            'Spray Suppression',
            'Anti-Under Run Bars',
            'Condition of Curtains*',
            'Condition of Curtain Straps*',
            'Load Straps*',
            'Mot Plate & Disc',
            'Landing Legs',
            'Roof Pole*',
            'Doors, Hinges & Locks',
            'Lights & Reflectors',
            'Air Leaks',
            'Brakes',
            'Air/Electrical Couplings',
            'Body Condition',
            'Load & Security',
            'Tir Cord*',
            'Slats/Boards All Present*',
            'Internal/External Bulkhead',
            'Rear Marker Boards',
            'Mud Wings & Stays',
            'Spray Suspension',
            'Number Plate Holder',
            'Fire Extinguishers (ADR)*',
            'Fridge Unit Operation*',
        );

        $batch = array();

        foreach($items as $k => $v){
            $batch[] = array(
                "checklist_id"  => $checklist_id ,
                "item_name"     => $v ,
                "item_position" => $k
            );
        }

        $this->db->insert_batch("checklist_items" , $batch);
    }
}