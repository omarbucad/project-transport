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
		$user_id = $this->post->user_id;

		$this->db->select("c.checklist_id , c.checklist_name , c.vehicle_type");
		$this->db->join("user_checklist uc" , "uc.checklist_id = c.checklist_id");
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
		}

		echo json_encode(["status" => true , "data" => $result]);
	}

	public function save_checklist(){
		if($this->post){
			$data = $this->post;
			$remind_in = NULL;
			$this->db->trans_start();

			//Create report
			if($data->user->role == "MECHANIC"){
				$checklist_data = $this->db->select("reminder_every")->where("checklist_id" , $data->checklist_type)->get("checklist")->row();
				$remind_in = remind_in($checklist_data->reminder_every);

				//then update all the report of the vehicle into remind done

				$this->db->where("report_by" , $data->user->user_id)->where("vehicle_registration_number" , $data->vehicle_registration_number)->update("report" , [
					"remind_done" => true
				]);
			}

			$this->db->insert("report" , [
				"report_by"						=> $data->user->user_id ,
				"vehicle_registration_number"	=> (isset($data->vehicle_registration_number)) ? $data->vehicle_registration_number : "" ,
				"trailer_number"				=> (isset($data->trailer_number)) ? $data->trailer_number : "" ,
				"checklist_id"					=> $data->checklist_type ,
				"start_mileage"					=> $data->start_mileage ,
				"end_mileage"					=> $data->end_mileage ,
				"report_notes"					=> (isset($data->note)) ? $data->note : "" ,
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
				"user_id"			=> $data->user->user_id ,
				"created"			=> time(),
				"longitude"			=> $data->longitude,
				"latitude"			=> $data->latitude,
				"signature"			=> $signature_path
			]);
			$status_id = $this->db->insert_id();

			//save checklist items

			foreach($data->checklist as $item){
				$item_batch = array(
					"report_id"				=> $report_id ,
					"checklist_item_id"		=> $item->checklist_id ,
					"checklist_value"		=> $item->note ,
					"checklist_ischeck"		=> $item->checkbox,
				);

				$this->db->insert("report_checklist" , $item_batch);
				$report_checklist_id = $this->db->insert_id();

				if($item->images){
					$this->save_image($report_id , $report_checklist_id , $item->images);
				}
			}

			$report_number = date("dmY").'-'.sprintf('%05d', $report_id);

			

			$this->db->where("report_id" , $report_id)->update("report" , [
				"report_number"		=> $report_number,
				"status_id"			=> $status_id 
			]);

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE){

	            $this->return_false();

	        }else{
	           
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

	private function save_image($report_id , $report_checklist_id , $images){

		$img_batch = array();

		foreach($images as $img){

			$name = $report_id.'_'.$report_checklist_id.'_'.time().'.PNG';
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

	        $encoded = $img;

		    //explode at ',' - the last part should be the encoded image now
		    $exp = explode(',', $encoded);

		    //we just get the last element with array_pop
		    $base64 = array_pop($exp);

		    //decode the image and finally save it
		    $data = base64_decode($base64);


		    //make sure you are the owner and have the rights to write content
		    file_put_contents($path, $data);

		    $img_batch[] = array(
		    	"report_id"				=> $report_id ,
		    	"report_checklist_id"	=> $report_checklist_id ,
		    	"image_path"			=> $year."/".$month.'/' ,
		    	"image_name"			=> $name
		    );
		}

		$this->db->insert_batch('report_images', $img_batch);

	}
}
