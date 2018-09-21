<?php

class Report_model extends CI_Model {

    public function get_reports_list(){

        $role = $this->data['session_data']->role;

        if($report_id = $this->input->get("report_id")){

            $report_id = $this->hash->decrypt($report_id);

            $this->db->where("r.report_id" , $report_id);
        }

        // SEARCH
        if($report_number = $this->input->get("report_number")){
            $this->db->where("report_number", $report_number);
        }
        if($this->input->get("status") != ""){
            $this->db->where("rs.status", $this->input->get("status"));
        }

        if($this->input->get('checklist_name')){
            $this->db->like('c.checklist_name' , $this->input->get('checklist_name'));
        }

        if($this->input->get('trailer_number')){
            $this->db->where('r.trailer_number' , $this->input->get('trailer_number'));
        }

        if($this->input->get('vehicle_registration_number')){
            $this->db->where('r.vehicle_registration_number' , $this->input->get('vehicle_registration_number'));
        }

        if($this->input->get('display_name')){
            $this->db->where('u.display_name' , $this->input->get('name'));
        }

        if($date = $this->input->get("date")){
            $date  = explode("-", $date);
            $start = strtotime(trim($date[0].' 00:00'));
            $end   = strtotime(trim($date[1].' 23:59'));


            $this->db->where("r.created >= " , $start);
            $this->db->where("r.created <= " , $end);
        }   
        // End of Search
        if($role == 'MECHANIC'){
            $this->db->where("rs.status !=", 0);
        }

        $this->db->select('r.* , rs.status, rs.created as status_created, c.checklist_name, u.display_name, u2.display_name as updated_by,vt.type, s.store_name');

        $this->db->join('report_status rs', 'rs.id = r.status_id');
        $this->db->join('checklist c', 'c.checklist_id = r.checklist_id');
        $this->db->join('user u', 'u.user_id = r.report_by');
        $this->db->join('user u2','u2.user_id = rs.user_id');
        $this->db->join("vehicle_type vt","vt.vehicle_type_id = c.vehicle_type_id");
        $this->db->join("store s","s.store_id = u.store_id");

        $this->db->where("u.store_id",$this->data['session_data']->store_id);

        $result = $this->db->order_by("r.created" , "DESC")->get("report r")->result();
        foreach($result as $key => $row){
            if($result[$key]->status == 0){
                $result[$key]->status_created = 0;
            }else{
                $result[$key]->status_created = convert_timezone($row->status_created,true);
            }
            $result[$key]->status = report_status($row->status);
            $result[$key]->raw_status = report_status($row->status,true);
            $result[$key]->created = convert_timezone($row->created,true);
            
        }
        
        return $result;
    }

    public function get_pdf_file($id){
        $result = $this->db->select("pdf_path,pdf_file")->where("report_id",$id)->get("report")->row();
        return $result;
    }

    public function get_report_by_id($id){

        $this->db->select('r.* , rs.status, rs.created as status_created, c.checklist_name, u.display_name,vt.type,vt.vehicle_type_id, s.store_name');

        $this->db->join('report_status rs', 'rs.id = r.status_id');
        $this->db->join('checklist c', 'c.checklist_id = r.checklist_id');
        $this->db->join('user u', 'u.user_id = r.report_by');
        $this->db->join('vehicle_type vt',"vt.vehicle_type_id = c.vehicle_type_id");
        $this->db->join("store s","s.store_id = u.store_id");
        $result = $this->db->where("r.report_id" , $id)->order_by("r.created" , "DESC")->get("report r")->row();

        if($result){

            // Get All Status 
            $this->db->select("rs.* , u.display_name");
            $this->db->join("user u", "u.user_id = rs.user_id");
            $result->report_statuses = $this->db->where("rs.report_id", $id)->order_by("rs.created" , "ASC")->get("report_status rs")->result();

            $marks = array();

            foreach ($result->report_statuses as $key => $row) {
                $loc_end = [
                    $row->display_name,
                    $row->latitude,
                    $row->longitude
                ];
                $loc_start = [
                    $row->display_name,
                    $row->start_latitude,
                    $row->start_longitude
                ];
                array_push($marks, $loc_end);
                array_push($marks, $loc_start);
                $result->report_statuses[$key]->status = report_status($row->status);
                $result->report_statuses[$key]->signature = ($result->report_statuses[$key]->signature == '') ? '' : $this->config->site_url("public/upload/signature/".$row->signature);
                $result->report_statuses[$key]->created = convert_timezone($row->created,true);
            }

            $result->locations = json_encode($marks);

            //Get All Report Checklist
            $this->db->select("rc.* , ci.item_name");
            $this->db->join("checklist_items ci", "ci.id = rc.checklist_item_id");
            $result->report_checklist = $this->db->where("rc.report_id", $id)->get("report_checklist rc")->result();

            //get all report images
            $result->report_images = $this->db->where('report_id',$id)->get('report_images')->result();


            foreach($result->report_checklist as $key => $row){
                foreach($result->report_images as $k => $r){
                    if($row->id == $r->report_checklist_id){

                        $result->report_checklist[$key]->fullpath[] = ($r->image_name == '') ? '' : $this->config->site_url("public/upload/report/".$r->image_path.$r->image_name);
                    }
                }
            }
            $result->status = report_status($result->status);
            $result->created = convert_timezone($result->created,true);
        }

        return $result;
    }

    public function get_status_info($report_id){
        $this->db->where("report_id", $report_id);
        $result = $this->db->order_by("created","DESC")->limit(1)->get("report_status")->row();
        
        return $result;
    }

    public function update_report($report_id){
        $this->db->trans_start();

        $this->db->insert("report_status", [
            "report_id" => $report_id,
            "user_id"   => $this->data['session_data']->user_id,
            "status"    => $this->input->post("status"),
            "notes"     => $this->input->post("notes"),
            "created"   => time()
        ]);

        $last_id = $this->db->insert_id();

        $this->db->where("report_id",$report_id);
        $this->db->update("report", [
            "status_id" => $last_id
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return true;
        }
    }

    public function weekly_report(){
        if($driver_id = $this->input->get("driver")){
            $this->db->where("r.report_by", $driver_id);
        }
        if($this->input->get('checklist_type')){
            $this->db->where('c.checklist_id' , $this->input->get('checklist_type'));
        }
        if($this->input->get('vehicle')){
            $this->db->where('r.vehicle_registration_number' , $this->input->get('vehicle'));
        }
        if($date = $this->input->get("date")){

            $start = strtotime(trim($date.' 00:00'));
            $end  = strtotime(trim($date. '23:59 + 6 days'));        

            $this->db->where("r.created >= " , $start);
            $this->db->where("r.created <= " , $end);
        }else{
            return [];
        }
        // End of Search

        //$store_id = $this->data['session_data']->store_id;

        $this->db->select('r.* , rs.status, rs.created as status_created,rs.signature, c.checklist_name, u.display_name, u2.display_name as updated_by, s.store_name, s.address_id,sa.*,vt.type');

        $this->db->join('report_status rs', 'rs.id = r.status_id');
        $this->db->join('checklist c', 'c.checklist_id = r.checklist_id');
        $this->db->join('user u', 'u.user_id = r.report_by');
        $this->db->join('user u2','u2.user_id = rs.user_id');
        $this->db->join('store s', 's.store_id = u.store_id');
        $this->db->join('store_address sa', 'sa.store_address_id = s.address_id');
        $this->db->join('vehicle_type vt',"vt.vehicle_type_id = c.vehicle_type_id");

        $result['header'] = $this->db->order_by("r.created" , "ASC")->get("report r")->result();
        $checklist = array();        

        foreach($result['header'] as $key => $row){
            // if($result['header'][$key]->status == 0){
            //     $result['header'][$key]->status_created = 0;
            // }else{
                $result['header'][$key]->status_created = convert_timezone($row->status_created,false);
            //}
            $result['header'][$key]->signature = ($row->signature == '') ? '' : $this->config->site_url("public/upload/signature/".$row->signature);

            $result['header'][$key]->address = $row->street1.",";
            $result['header'][$key]->address .= ($row->street2) ? $row->street2."," : "";
            $result['header'][$key]->address .= ($row->suburb) ? $row->suburb."," : "";
            $result['header'][$key]->address .= ($row->city) ? $row->city."," : "";
            $result['header'][$key]->address .= ($row->state) ? $row->state."," : "";
            $result['header'][$key]->address .= ($row->postcode) ? $row->postcode : "";


            $result['header'][$key]->status = report_status($row->status);
            $result['header'][$key]->raw_status = report_status($row->status,true);
            $result['header'][$key]->created = convert_timezone($row->created,true);
            $result['header'][$key]->startrange = convert_timezone($start);
            $result['header'][$key]->endrange = convert_timezone($end);
            //Get All Report Checklist Item
            $this->db->select("rc.* , ci.item_name");
            $this->db->join("checklist_items ci", "ci.id = rc.checklist_item_id");
            $report_checklist = $this->db->where("report_id", $result['header'][$key]->report_id)->get("report_checklist rc")->result();

            array_push($checklist, $report_checklist);
        }    

        $item = array();
        if(!empty($checklist)){
            foreach ($checklist as $key =>$value) {
                $count_items = (count($checklist[$key]) - 1);   
            }
            $report_count = (count($checklist) - 1);
            for($i = 0; $i <= $count_items; $i++){
                $c = 0;

                $item[] = array(
                    "item_name" => $checklist[$c][$i]->item_name,
                    "day1"      => (($c <= $report_count) && isset($checklist[$c])) ? $checklist[$c][$i]->checklist_ischeck : "",
                    "day2"      => (($c++ <= $report_count) && isset($checklist[$c])) ? $checklist[$c][$i]->checklist_ischeck : "",
                    "day3"      => (($c++ <= $report_count) && isset($checklist[$c])) ? $checklist[$c][$i]->checklist_ischeck : "",
                    "day4"      => (($c++ <= $report_count) && isset($checklist[$c])) ? $checklist[$c][$i]->checklist_ischeck : "",
                    "day5"      => (($c++ <= $report_count) && isset($checklist[$c])) ? $checklist[$c][$i]->checklist_ischeck : "",
                    "day6"      => (($c++ <= $report_count) && isset($checklist[$c])) ? $checklist[$c][$i]->checklist_ischeck : "",
                    "day7"      => (($c++ <= $report_count) && isset($checklist[$c])) ? $checklist[$c][$i]->checklist_ischeck : "",
                    
                );
                $c = 0;
            }
        }
        
        $result['checklist'] = $item;
        
        return $result;
    }

    public function get_today_reports(){
        $role = $this->session->userdata("user")->role;

        $this->db->select('r.* , rs.status, rs.created as status_created, c.checklist_name, u.display_name, u2.display_name as updated_by');

        $this->db->join('report_status rs', 'rs.id = r.status_id');
        $this->db->join('checklist c', 'c.checklist_id = r.checklist_id');
        $this->db->join('user u', 'u.user_id = r.report_by');
        $this->db->join('user u2','u2.user_id = rs.user_id');

        $this->db->where("r.created >=", strtotime("today midnight"));
        $this->db->where("r.created <=", strtotime("tomorrow midnight -1 second"));
        if($role == "MECHANIC"){
            $this->db->where("rs.status !=",0);
        }
        $this->db->where("u.store_id",$this->session->userdata('user')->store_id);
        $result = $this->db->order_by("r.created" , "DESC")->get("report r")->result();

        foreach($result as $key => $row){
            if($result[$key]->status == 0){
                $result[$key]->status_created = 0;
            }else{
                $result[$key]->status_created = convert_timezone($row->status_created,true);
            }
            $result[$key]->status = report_status($row->status);
            $result[$key]->raw_status = report_status($row->status,true);
            $result[$key]->created = convert_timezone($row->created,true);
        }
        
        return $result;

    }

    public function defect_undermaintenance_reports(){

        $this->db->select('r.* , rs.status, rs.created as status_created, c.checklist_name, u.display_name, u2.display_name as updated_by');     

        $this->db->join('report_status rs', 'rs.id = r.status_id');
        $this->db->join('checklist c', 'c.checklist_id = r.checklist_id');
        $this->db->join('user u', 'u.user_id = r.report_by');
        $this->db->join('user u2','u2.user_id = rs.user_id');
        $this->db->where("u.store_id",$this->session->userdata('user')->store_id);
        $this->db->where_in("rs.status", [1,2]);  

        $result = $this->db->order_by("r.created" , "DESC")->get("report r")->result();

        foreach($result as $key => $row){
            if($result[$key]->status == 0){
                $result[$key]->status_created = 0;
            }else{
                $result[$key]->status_created = convert_timezone($row->status_created,true);
            }
            $result[$key]->status = report_status($row->status);
            $result[$key]->raw_status = report_status($row->status,true);
            $result[$key]->created = convert_timezone($row->created,true);
        }
        
        return $result;

    }
    
    public function fixed_today_reports(){

        $this->db->select('r.* , rs.status, rs.created as status_created, c.checklist_name, u.display_name, u2.display_name as updated_by');     

        $this->db->join('report_status rs', 'rs.id = r.status_id');
        $this->db->join('checklist c', 'c.checklist_id = r.checklist_id');
        $this->db->join('user u', 'u.user_id = r.report_by');
        $this->db->join('user u2','u2.user_id = rs.user_id');

        $this->db->where("rs.created >=", strtotime("today midnight"));
        $this->db->where("rs.created <=", strtotime("tomorrow midnight -1 second"));

        $this->db->where("rs.status", 3);  
        $this->db->where("u.store_id",$this->session->userdata('user')->store_id);

        $result = $this->db->order_by("r.created" , "DESC")->get("report r")->result();

        foreach($result as $key => $row){
            if($result[$key]->status == 0){
                $result[$key]->status_created = 0;
            }else{
                $result[$key]->status_created = convert_timezone($row->status_created,true);
            }
            $result[$key]->status = report_status($row->status);
            $result[$key]->raw_status = report_status($row->status,true);
            $result[$key]->created = convert_timezone($row->created,true);
        }
        
        return $result;

    }
}