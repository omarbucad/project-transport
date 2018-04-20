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
        
		$this->post = json_decode(file_get_contents("php://input"));
	}

	public function get_my_checklist(){
		$store_id = $this->post->store_id;

		$this->db->select("c.checklist_id , c.checklist_name");
		$result = $this->db->where("c.store_id" , $store_id)->where("c.status" , 1)->where("c.deleted IS NULL")->get("checklist c")->result();

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

		echo json_encode(["status" => true , "data" => $result]);
	}
	public function save_checklist(){
		if($this->post){
			$data = $this->post;

			$this->db->trans_start();

			//Create report
			$this->db->insert("report" , [
				"report_by"						=> $data->user->user_id ,
				"vehicle_registration_number"	=> $data->vehicle_registration_number ,
				"trailer_number"				=> $data->trailer_number ,
				"checklist_id"					=> $data->checklist_type ,
				"start_mileage"					=> $data->start_mileage ,
				"end_mileage"					=> $data->end_mileage ,
				"report_notes"					=> $data->note ,
				"created"						=> time()
			]);
			$report_id = $this->db->insert_id();

			//Create status
			$this->db->insert("report_status" , [
				"report_id"			=> $report_id ,
				"status"			=> ($this->isDefect()) ? 1 : 0 ,
				"notes"				=> "Initial" ,
				"user_id"			=> $data->user->user_id ,
				"created"			=> time()
			]);
			$status_id = $this->db->insert_id();

			//save checklist items

			$item_batch = array();
			foreach($data->checklist as $item){
				$item_batch[] = array(
					"report_id"				=> $report_id ,
					"checklist_item_id"		=> $item->checklist_id ,
					"checklist_value"		=> $item->note ,
					"checklist_ischeck"		=> $item->checkbox
				);
			}

			$this->db->insert_batch("report_checklist" , $item_batch);

			$report_number = date("dmY").'-'.sprintf('%05d', $report_id);

			$signature_path = $this->save_signature($report_number);

			$this->db->where("report_id" , $report_id)->update("report" , [
				"report_number"		=> $report_number,
				"status_id"			=> $status_id,
				"signature"			=> $signature_path
			]);

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE){

	            $this->return_false();

	        }else{
	           
	           echo json_encode(["status" => 1 , "Successfully Submitted"]);
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
		$_isDefect = false;

		foreach($this->post->checklist as $row){
			if($row->checkbox){
				$_isDefect = true;
			}
		}

		return $_isDefect;
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
