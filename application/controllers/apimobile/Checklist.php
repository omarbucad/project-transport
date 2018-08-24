<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checklist extends CI_Controller {

	private $post;

	public function __construct( ) {
		parent::__construct();

		//http://stackoverflow.com/questions/18382740/cors-not-working-php
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
        
		//$this->post = json_decode(file_get_contents("php://input"));
		 $this->load->model('report_model', 'reports');
		$this->post = (object)$this->input->post();
	}

	public function get_my_checklist(){
		$store_id = $this->post->store_id;
		$user_id = $this->post->user_id;

		$this->db->select("c.checklist_id , c.checklist_name , c.vehicle_type");
		$this->db->join("user_checklist uc" , "uc.checklist_id = c.checklist_id");
		$result = $this->db->where("c.store_id" , $store_id)->where("uc.user_id" , $user_id)->where("c.status" , 1)->where("c.deleted IS NULL")->order_by("c.checklist_name" , "ASC")->get("checklist c")->result();

		echo json_encode(["status" => 1 , "data" => $result]);
	}

	public function get_checklist_by_type(){
		$store_id = $this->post->store_id;
		$user_id = $this->post->user_id;
		$type = $this->post->type;
		
		if($type == 'VEHICLE'){
			$c_type = "TRUCK";
		}else{
			$c_type = "TRAILER";
		}

		$this->db->select("c.checklist_id , c.checklist_name , c.vehicle_type");
		$this->db->join("user_checklist uc" , "uc.checklist_id = c.checklist_id");
		$this->db->where("c.vehicle_type",$type);
		$this->db->where("c.vehicle_type","BOTH");
		$result = $this->db->where("c.store_id" , $store_id)->where("uc.user_id" , $user_id)->where("c.status" , 1)->where("c.deleted IS NULL")->order_by("c.checklist_name" , "ASC")->get("checklist c")->result();

		echo json_encode(["status" => 1 , "data" => $result]);
	}

	public function get_vehicle_registration_list(){
		$store_id = $this->post->store_id;

		$this->db->select("vehicle_id , vehicle_registration_number");
		$result = $this->db->where("store_id" , $store_id)->where("status" , 1)->where("deleted IS NULL")->order_by("vehicle_registration_number" , "ASC")->get("vehicle")->result();

		echo json_encode(["status" => true , "data" => $result]);
	}

	public function get_trailer_list(){
		$store_id = $this->post->store_id;

		$this->db->select("trailer_id , trailer_number");
		$result = $this->db->where("store_id" , $store_id)->where("status" , 1)->where("deleted IS NULL")->order_by("trailer_number" , "ASC")->get("trailer")->result();

		echo json_encode(["status" => true , "data" => $result]);
	}

	public function get_checklist_items(){
		$checklist_id = $this->post->checklist_id;

		$result = $this->db->select()->where("checklist_id" , $checklist_id)->where("DELETED IS NULL")->order_by("item_position" , "ASC")->get("checklist_items")->result();

		foreach($result as $key => $row){
			$result[$key]->color = "light";
			$result[$key]->image = ($row->image_name) ? $this->config->site_url("thumbs/images/checklist/".$row->image_path."/250/250/".$row->image_name) : "";
			$result[$key]->help_image  = ($row->help_image_name) ? $this->config->site_url("thumbs/images/checklist/".$row->help_image_path."/250/250/".$row->help_image_name) : "";
		}

		echo json_encode(["status" => true , "data" => $result]);
	}

	public function save_checklist(){
		//$data = (object)$this->input->post();
		$data = (object)$this->input->post();
		$user = json_decode($data->user);
		if($data){
			$remind_in = NULL;
			$this->db->trans_start();

			//Create report
			if($user->role == "MECHANIC"){
				$checklist_data = $this->db->select("reminder_every")->where("checklist_id" , $data->checklist_type)->get("checklist")->row();
				$remind_in = remind_in($checklist_data->reminder_every);

				//then update all the report of the vehicle into remind done

				$this->db->where("report_by" , $user->user_id)->where("vehicle_registration_number" , $data->vehicle_registration_number)->update("report" , [
					"remind_done" => true
				]);
			}


			$this->db->insert("report" , [
				"report_by"						=> $user->user_id ,
				"vehicle_registration_number"	=> (isset($data->vehicle_registration_number)) ? $data->vehicle_registration_number : NULL ,
				"trailer_number"				=> (isset($data->trailer_number)) ? $data->trailer_number : NULL ,
				"checklist_id"					=> $data->checklist_id,
				"start_mileage"					=> $data->start_mileage ,
				"end_mileage"					=> $data->end_mileage ,
				"report_notes"					=> (isset($data->note)) ? $data->note : NULL ,
				"created"						=> time(),
				"remind_in"						=> $remind_in,
				"remind_done"					=> false
			]);

			$report_id = $this->db->insert_id();

			$signature_path = $this->save_signature($report_id);

			//Create status
			$this->db->insert("report_status" , [
				"report_id"			=> $report_id ,
				"status"			=> ($this->isDefect()) ? 1 : 0 ,
				"notes"				=> "Initial" ,
				"user_id"			=> $user->user_id ,
				"created"			=> time(),
				"start_longitude"	=> ($data->start_longitude == '') ? NULL : $data->start_longitude,
				"start_latitude"	=> ($data->start_latitude == '') ? NULL : $data->start_latitude,
				"longitude"			=> ($data->longitude == '') ? NULL : $data->longitude,
				"latitude"			=> ($data->latitude == '') ? NULL : $data->latitude,
				"signature"			=> $signature_path
			]);
			$status_id = $this->db->insert_id();

			//save checklist items
			$checklist = json_decode($data->checklist);
			

			foreach($checklist as $item){

				$item_batch = array(
					"report_id"				=> $report_id ,
					"checklist_item_id"		=> $item->checklist_id ,
					"checklist_value"		=> ($item->note == '') ? NULL : $item->note,
					"checklist_ischeck"		=> $item->checkbox,
				);

				$this->db->insert("report_checklist" , $item_batch);
				$report_checklist_id = $this->db->insert_id();

				if(!empty($item->images)){
					$this->save_image($report_id , $report_checklist_id , $item->images);
				}
			}

			$report_number = date("dmY").'-'.sprintf('%05d', $report_id);

			$this->db->where("report_id" , $report_id)->update("report" , [
				"report_number"		=> $report_number,
				"status_id"			=> $status_id ,
			]);
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE){

	            $this->return_false();

	        }else{
	            $pdf = $this->pdf($report_id);

	            $this->db->where("report_id" , $report_id)->update("report" , [
					"pdf_path"			=> $pdf['path'],
					"pdf_file" 			=> $pdf['filename']
				]);
	           
	            echo json_encode(["status" => 1 , "message" => "Successfully Submitted"]);
	        }
		}
	}

	private function return_false($rules = 0){
		$msg = "Server Error , Please Try Again Later";

		switch ($rules) {
			case 1:
				
				$msg = "Invalid Username & Password";

				break;
			
			default:
				# code...
				break;
		}

		echo json_encode([
				"status" 	=> false ,
				"message" 	=> $msg
		]);
	}

	private function isDefect(){
		$data = (object)$this->input->post();
		$checklist = json_decode($data->checklist);
		$_isDefect = false;

		foreach($checklist as $row){
			if($row->checkbox == 1){
				$_isDefect = true;
			}
		}

		return $_isDefect;
	}

	private function pdf($report_id){

		$report = $this->reports->get_report_by_id($report_id);

		$pdf = $this->pdf->create_report_checklist($report , "F");

		$pdf['file'] = $this->config->site_url($pdf['file']);

		return $pdf;
	}

	private function save_signature($report_number){
		$data = (object)$this->input->post();
		$image = $data->signature;
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

        $image;
	    $data = base64_decode($image);


	    //make sure you are the owner and have the rights to write content
	    file_put_contents($path, $data);

        return $year."/".$month.'/'.$name;
	}

	private function save_image($report_id , $report_checklist_id , $images){

		$img_batch = array();
		$ctr = 1;
		foreach($images as $img){

			$name = $report_id.'_'.$report_checklist_id.'_'.$ctr.'_'.time().'.PNG';
	        $year = date("Y");
	        $month = date("m");
	        
	        $folder = "./public/upload/report/".$year."/".$month;
	        
	        $date = time();

	        if (!file_exists($folder)) {
	            mkdir($folder, 0777, true);
	            mkdir($folder.'/thumbnail', 0777, true);
	            create_index_html($folder);
	        }


	        $path = $folder.'/'.$name;

		    $data = base64_decode($img);

		    //make sure you are the owner and have the rights to write content
		    file_put_contents($path, $data);
		    
		    $this->db->insert('report_images', [
		    	"report_id"				=> $report_id ,
		    	"report_checklist_id"	=> $report_checklist_id ,
		    	"image_path"			=> $year."/".$month.'/' ,
		    	"image_name"			=> $name
		    ]);

		    $ctr++;
		}	
	}
}
