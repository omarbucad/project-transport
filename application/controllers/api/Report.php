<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

	private $post;

	public function __construct( ) {
		parent::__construct();

		//http://stackoverflow.com/questions/18382740/cors-not-working-php
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
        
		$this->post = json_decode(file_get_contents("php://input"));
	}

	public function get_report($mechanic = false){

		$report_by = $this->post->user_id;
		$store_id = $this->post->store_id;

		$this->db->select("r.report_id , r.report_number , r.vehicle_registration_number , r.trailer_number , r.start_mileage , r.end_mileage , r.report_notes  , r.created");
		$this->db->select("u.display_name , u2.display_name as updated_by");
		$this->db->select("rs.status , rs.notes as status_notes , rs.signature");
		$this->db->select("c.checklist_name");
		$this->db->join("user u" , "u.user_id = r.report_by");
		$this->db->join("report_status rs" , "rs.id = r.status_id");
		$this->db->join("user u2" , "rs.user_id = u2.user_id");
		$this->db->join("checklist c" , "c.checklist_id = r.checklist_id");

		if($mechanic){
			$this->db->where_in("rs.status" , [1 , 2])->where("u.store_id" , $store_id);
		}else{
			$this->db->where("r.report_by" , $report_by);
		}

		$result = $this->db->order_by("r.created" , "DESC")->get("report r")->result();


		foreach($result as $key => $row){
			$result[$key]->signature = $this->config->site_url("public/upload/signature/".$row->signature);
			$result[$key]->created   = convert_timezone($row->created , true );
			$result[$key]->status_raw = report_type($row->status , true);
			$result[$key]->status = report_type($row->status);
			$result[$key]->pdf = [
				"path"	=> "http://192.168.1.116/project-transport/public/upload/pdf.pdf" ,
				"name"	=> "pdf.pdf"
			];
			

			$this->db->select("rc.checklist_ischeck , rc.checklist_value , ci.item_name , rc.id");
			$this->db->join("checklist_items ci" , "ci.id = rc.checklist_item_id");
			$result[$key]->checklist = $this->db->where("report_id" , $row->report_id)->order_by("ci.item_position" , "ASC")->get("report_checklist rc")->result();

			foreach($result[$key]->checklist as $k => $r){
				$images = $this->db->where("report_id" , $row->report_id)->where("report_checklist_id" , $r->id)->get("report_images")->result();

				foreach($images as $ki => $ro){
					$images[$ki]->thumbnail = $this->config->site_url("thumbs/images/report/".$ro->image_path."250/250/".$ro->image_name);
					$images[$ki]->image = $this->config->site_url("thumbs/images/report/".$ro->image_path."500/500/".$ro->image_name);
				}

				$result[$key]->checklist[$k]->images = $images;
			}

			$this->db->select("rs.status , rs.notes  , u.display_name , rs.created , rs.longitude , rs.latitude , rs.signature");
			$this->db->join("user u" , "u.user_id = rs.user_id");
			$status = $this->db->where("rs.report_id" , $row->report_id)->order_by("rs.created" , "DESC")->get("report_status rs")->result();

			foreach($status as $k => $r){
				$status[$k]->status = report_type($r->status );
				$status[$k]->created   = convert_timezone($r->created , true );
				$status[$k]->signature = $this->config->site_url("public/upload/signature/".$r->signature);
			}

			$result[$key]->status_list = $status;
		}

		echo json_encode($result);
	}

	public function fix_report(){

		$this->db->trans_start();

		$signature_path = $this->save_signature($this->post->report);

		if($this->post){

			$this->db->insert("report_status" , [
				"report_id"		=> $this->post->report ,
				"status"		=> $this->post->status,
				"notes"			=> $this->post->note ,
				"user_id"		=> $this->post->user->user_id,
				"longitude"		=> $this->post->longitude,
				"latitude"		=> $this->post->latitude,
				"signature"		=> $signature_path ,
				"created"		=> time()
			]);

			$status_id = $this->db->insert_id();


			$this->db->where("report_id" , $this->post->report)->update('report' , [
				"status_id"		=> $status_id
			]);

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE){

	           echo json_encode(["status" => 0 , "message" => "Submit Failed"]);

	        }else{
	           
	           echo json_encode(["status" => 1 , "message" => "Successfully Submitted"]);
	        }
		}
	}

	private function save_signature($report_number){
		$image = $this->post->signature;

		$name = $report_number.'_'.time().'.PNG';
        $year = date("Y");
        $month = date("m");
        
        $folder = "./public/upload/signature/".$year."/".$month;
        
        $date = time();

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
            create_index_html($folder);
        }

        $path = $folder.'/'.$name;

        $encoded = $image;

	    //explode at ',' - the last part should be the encoded image now
	    $exp = explode(',', $encoded);

	    //we just get the last element with array_pop
	    $base64 = array_pop($exp);

	    //decode the image and finally save it
	    $data = base64_decode($base64);


	    //make sure you are the owner and have the rights to write content
	    file_put_contents($path, $data);

        return $year."/".$month.'/'.$name;
	}
}