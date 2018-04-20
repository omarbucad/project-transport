<?php

class Report_model extends CI_Model {

    public function get_reports_list(){

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
            $this->db->where('u.display_name' , $this->input->get('checklist_name'));
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

        $this->db->select('r.* , rs.status, rs.created as status_created, c.checklist_name, u.display_name');

        $this->db->join('report_status rs', 'rs.id = r.status_id');
        $this->db->join('checklist c', 'c.checklist_id = r.checklist_id');
        $this->db->join('user u', 'u.user_id = r.report_by');

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

    public function get_report_by_id($report_id){
        $id = $this->hash->decrypt($report_id);

        $this->db->select('r.* , rs.status, rs.created as status_created, c.checklist_name, u.display_name');

        $this->db->join('report_status rs', 'rs.id = r.status_id');
        $this->db->join('checklist c', 'c.checklist_id = r.checklist_id');
        $this->db->join('user u', 'u.user_id = r.report_by');

        $this->db->where("r.report_id" , $id);
        $result = $this->db->order_by("r.created" , "DESC")->get("report r")->row();

        // Get All Status 
        $this->db->select("rs.* , u.display_name");
        $this->db->join("user u", "u.user_id = rs.user_id");
        $this->db->where("rs.report_id", $id);
        $result->report_statuses = $this->db->get("report_status rs")->result();
        $marks = array();
        $center = array();
        foreach ($result->report_statuses as $key => $row) {
            if($row->id == $result->status_id){
                $center[] = array(
                    "longitude" => $row->longitude,
                    "latitude"  => $row->latitude
                );
                $marks[] = array(
                    "longitude" => $row->longitude,
                    "latitude"  => $row->latitude
                );
                
               $result->report_statuses[$key]->status = report_status($row->status);
               $result->report_statuses[$key]->created = convert_timezone($row->created,true);
            }else{
                $marks[] = array(
                    "longitude" => $row->longitude,
                    "latitude"  => $row->latitude
                );

               $result->report_statuses[$key]->status = report_status($row->status);
               $result->report_statuses[$key]->created = convert_timezone($row->created,true);
            }
            
        }

        $result->status_markers = $marks;
        $result->center_marker = $center;

        //Get All Report Checklist
        $this->db->select("rc.* , ci.item_name");
        $this->db->where("report_id", $id);
        $this->db->join("checklist_items ci", "ci.id = rc.checklist_item_id");
        $result->report_checklist = $this->db->get("report_checklist rc")->result();

        $result->status = report_status($result->status);
        $result->created = convert_timezone($result->created,true);

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

}