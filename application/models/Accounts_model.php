<?php

class Accounts_model extends CI_Model {

    public function get_admin_accounts($count = false){
        $skip = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
        $limit = ($this->input->get("limit")) ? $this->input->get("limit") : 10;

        $this->db->select("u.*, up.plan_type, up.billing_type, up.plan_created, up.plan_expiration, up.updated, up.active, up.who_updated, u2.display_name as updated_name");
        $this->db->join("user_plan up","up.store_id = u.store_id");
        $this->db->join("user u2", "u2.user_id = up.who_updated");

        $this->db->where("up.active",1);

        if($plan = $this->input->get('plan')){
            $this->db->where("up.plan_type",$plan);
        }

        if($status = $this->input->get('status')){
            $this->db->where("u.status",$status);
        }

        if($name = $this->input->get('name')){
            $this->db->like("u.display_name",$name);
            $this->db->or_like("u.username",$name);
        }

        if($type = $this->input->get('type')){
            $this->db->where("up.billing_type",$type);
        }

        if($count){
            return $result = $this->db->get("user u")->num_rows();
        }
        
        $result = $this->db->where("u.role","SUPER ADMIN")->limit($limit , $skip)->order_by("u.display_name" , "ASC")->get("user u")->result();

        
        foreach($result as $k => $row){
            $result[$k]->status = convert_status($row->status);
            $result[$k]->created = convert_timezone($row->created,true);
            $result[$k]->plan_created = convert_timezone($row->plan_created,true);
            $result[$k]->plan_expiration = convert_timezone($row->plan_expiration,true);
        }

        return $result;
    }

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

    public function get_driver(){
        $store_id = $this->data['session_data']->store_id;
        $this->db->where("store_id" , $store_id);

        $result = $this->db->where('role',"DRIVER")->get("user")->result();

        foreach($result as $k => $row){
            $result[$k]->status = convert_status($row->status);
        }
        return $result;
       
    }

    public function get_mechanic(){
        $store_id = $this->data['session_data']->store_id;
        $this->db->where("store_id" , $store_id);

        $result = $this->db->where('role',"MECHANIC")->get("user")->result();

        foreach($result as $k => $row){
            $result[$k]->status = convert_status($row->status);
        }
        return $result;
       
    }
    public function get_admin(){
        $store_id = $this->data['session_data']->store_id;
        $this->db->where("store_id" , $store_id);

        $result = $this->db->where('role',"ADMIN")->get("user")->result();

        foreach($result as $k => $row){
            $result[$k]->status = convert_status($row->status);
        }
        return $result;
       
    }

    public function get_account_info($user_id){
        $this->db->where("user_id", $user_id);
        $result = $this->db->get("user")->row();

        $this->db->select("id,checklist_id");
        $this->db->where("user_id", $user_id);
        $user_checklist = $this->db->get("user_checklist")->result();
        $result->user_checklist = [];
        if($user_checklist != ""){
            foreach($user_checklist as $key => $value){
                $result->user_checklist[$value->checklist_id] = $value->checklist_id;
            }
        }
        return $result;
    }

    public function add_users(){
        $store_id = $this->data['session_data']->store_id;
        $role = $this->input->post("role");
        $this->db->trans_start();

        $this->db->insert("user" , [
            "email_address"=> $this->input->post("email") ,
            "username"     => $this->input->post("username") ,
            "password"     => $this->input->post("password"),
            "display_name" => $this->input->post("display_name") ,
            "role"         => $role ,
            "store_id"     => $store_id,
            "image_path"   => "public/img/",
            "image_name"   => "person-placeholder.jpg",
            "status"       => 1,
            "created"      => time()
        ]);

        $last_id = $this->db->insert_id();

        if($role != "ADMIN"){
            foreach($this->input->post("checklist") as $key => $value){
                $this->db->insert("user_checklist",[
                    "user_id"      => $last_id,
                    "checklist_id" => $value
                ]);
            }
        }
       

        $this->do_upload($last_id);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $last_id;
        }
    }

    public function edit_user($user_id){
        $role = $this->input->post("role");
        $this->db->trans_start();

        $this->db->where("user_id", $user_id)->update("user" , [
            "email_address"=> $this->input->post("email") ,
            "username"     => $this->input->post("username") ,
            "display_name" => $this->input->post("display_name") ,
            "role"         => $role 
        ]);

        if($this->input->post("password") != ""){
            $this->db->where("user_id", $user_id);
            $this->db->update("user" , [
                "password"=> $this->input->post("password")
            ]);
        }


        if($role != "ADMIN"){
  
            $this->db->where("user_id", $user_id)->delete("user_checklist");
            $checklist = $this->input->post("checklist");
            $user_checklist = array();

            foreach ($checklist as $key => $value) {
                $user_checklist[] = array(
                    "user_id"       => $user_id ,
                    "checklist_id"  => $value
                );
            }

            $this->db->insert_batch("user_checklist" , $user_checklist);
  
        }
       
        $this->do_upload($user_id);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $user_id;
        }
    }

    public function delete_user($user_id){

        $this->db->trans_start();

        $this->db->where("user_id", $user_id);
        $this->db->update("user", [
            "deleted" => time()
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return true;
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


    public function update_user_plan($user_id){
        $this->db->where("active",1);
        $plan_data = $this->db->select("user_plan_id,store_id")->get("user_plan")->row();

        $who_updated = $this->session->userdata("user")->user_id;

        $post = $this->input->post();
        if($post["plan"] == "TRIAL"){
            $expiration = strtotime("+1 month");
            $billing = "NA";
        }else{

            $billing = $post["billing_type"];
            $expiration = ($post["billing_type"] == "MONTHLY") ? strtotime("+1 month") : strtotime("+1 year");
        }

        if($plan_data){
            $this->db->where("user_plan_id",$plan_data->user_plan_id);
            $updated = $this->db->update("user_plan",array("active" => 0));
            if($updated){
                $plan_created = $this->db->insert("user_plan",[
                    "store_id" => $plan_data->store_id,
                    "plan_type" => $post["plan"],
                    "billing_type" => $billing,
                    "plan_created" => time(),
                    "plan_expiration"   => $expiration,
                    "ip_address" => $this->input->ip_address(),
                    "active" => 1,
                    "updated" => time(),
                    "who_updated" => $who_updated
                ]);

                $invoice_id = $this->db->insert_id();
                $invoice_no = date("dmY").'-'.sprintf('%05d', $invoice_id);
                $this->db->where("invoice_id",$invoice_id);
                $this->db->update("invoice", array("invoice_no" => $invoice_no));

                if($plan_created){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

}