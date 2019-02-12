<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle extends CI_Controller {

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
		$this->post = (object)$this->input->post();
	}

	public function add(){

		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$data = $this->post;

			$this->db->trans_start();

			$this->db->insert("vehicle",[
				"vehicle_registration_number" => $data->vehicle_registration_number,
				"status"                        => 0,
                "availability"                  => 1,
                "last_checked"                  => NULL,
				"vehicle_type_id" => $data->vehicle_type_id,
				"created" => time(),
				"store_id" => $data->store_id,
				"deleted" => NULL
			]);

			$this->db->trans_complete();

	        if ($this->db->trans_status() === FALSE){
	            echo json_encode(["status" => false , "message" => "Failed", "action" => "add"]);
	        }else{
	            echo json_encode(["status" => true , "message" => "Added Successfully", "action" => "add"]);
	        }
	    }else{
	    	echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "add"]);
	    }
	}

	public function edit(){

		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$data = $this->post;
			
			$this->db->trans_start();
			$this->db->where("store_id",$data->store_id);
			$this->db->where("vehicle_id", $data->vehicle_id);
			$this->db->update("vehicle",[
				"vehicle_registration_number" => $data->vehicle_registration_number,
                "availability"    => $data->availability,
				"vehicle_type_id" => $data->vehicle_type_id,
				"deleted" => NULL
			]);

			$this->db->trans_complete();

	        if ($this->db->trans_status() === FALSE){
	            echo json_encode(["status" => false , "message" => "Failed", "action" => "edit"]);
	        }else{
	            echo json_encode(["status" => true , "message" => "Updated Successfully", "action" => "edit"]);
	        }
	    }else{
	    	echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "edit"]);
	    }
	}

	// public function delete(){
	// 	$data = $this->post;
		
	// 	$this->db->trans_start();
	// 	$this->db->where("store_id",$data->store_id);
	// 	$this->db->where("vehicle_id", $data->vehicle_id);
	// 	$this->db->update("vehicle",[
	// 		"deleted" => time()
	// 	]);

	// 	$this->db->trans_complete();

 //        if ($this->db->trans_status() === FALSE){
 //            echo json_encode(["status" => false , "message" => "Failed", "action" => "delete"]);
 //        }else{
 //            echo json_encode(["status" => true , "message" => "Deleted Successfully", "action" => "delete"]);
 //        }
	// }

	public function multiple_delete(){
		
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$data = $this->post;
			//print_r_die($data);
			$vehicles = json_decode($data->vehicles);
			$this->db->trans_start();

			foreach ($vehicles as $key) {
				$this->db->where("store_id",$data->store_id);
				$this->db->where("vehicle_id", $key);
				$this->db->update("vehicle",[
					"deleted" => time()
				]);
			}			

			$this->db->trans_complete();

	        if ($this->db->trans_status() === FALSE){
	            echo json_encode(["status" => false , "message" => "Failed", "action" => "multiple_delete"]);
	        }else{
	            echo json_encode(["status" => true , "message" => "Deleted Successfully", "action" => "multiple_delete"]);
	        }
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "multiple_delete"]);
		}
	}

	public function vehicle_status(){
		$data = $this->post;
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			if($data){
				$this->db->trans_start();
				if(isset($data->previous_status)){
					$this->db->where("vehicle_registration_number", $data->vehicle_registration_number);
					$this->db->update("vehicle",[
						"status" => $data->previous_status
					]);
				}else{
					$this->db->where("vehicle_registration_number", $data->vehicle_registration_number);
					$this->db->update("vehicle",[
						"status" => 1
					]);
				}
				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE){
		            echo json_encode(["status" => false , "message" => "Failed", "action" => "vehicle_status"]);
		        }else{
		            echo json_encode(["status" => true , "message" => "Vehicle Status Updated Successfully", "action" => "vehicle_status"]);
		        }
			}else{
				echo json_encode(["status" => false , "message" => "No passed data", "action" => "vehicle_status"]);
			}			
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "vehicle_status"]);
		}
	}

	public function vehicle_availability(){
		$data = $this->post;
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			if($data){
				$this->db->trans_start();

				$this->db->where("vehicle_registration_number", $data->vehicle_registration_number);
				$this->db->update("vehicle", [
					"availability" => $data->availability
				]);

				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE){
		            echo json_encode(["status" => false , "message" => "Something went wrong.", "action" => "vehicle_availability"]);
		        }else{
		            echo json_encode(["status" => true , "message" => "Vehicle Availability Updated Successfully", "action" => "vehicle_availability"]);
		        }
			}else{
				echo json_encode(["status" => false , "message" => "No passed data", "action" => "vehicle_availability"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "vehicle_availability"]);
		}
	}

	public function check_availability(){
		$data = $this->post;
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			if($data){
				$this->db->trans_start();
				$this->db->select("availability");
				$this->db->where("deleted IS NULL");
				$this->db->where("vehicle_registration_number", $data->vehicle_registration_number);
				$result = $this->db->get("vehicle")->row();

				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE){
		            echo json_encode(["status" => false , "message" => "Something went wrong.", "action" => "check_availability"]);
		        }else{
		        	if($result->availability == 1){
		        		echo json_encode(["status" => true , "message" => "Available", "action" => "check_availability"]);
		        	}else{
		        		echo json_encode(["status" => true , "message" => "Unavailable", "action" => "check_availability"]);
		        	}		            
		        }
			}else{
				echo json_encode(["status" => false , "message" => "No passed data", "action" => "check_availability"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "check_availability"]);
		}
	}

	public function view_vehicles_used(){
		$data = $this->post;
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			if($data){
				$this->db->trans_start();

				$this->db->select("vu.vehicle_registration_number, vu.trailer_number, vu.vehicle_type, vu.date_used, vt.type");
				$this->db->where("vu.user_id", $data->user_id);
				$this->db->join("vehicle_type vt","vt.vehicle_type_id = vu.vehicle_type");
				$result = $this->db->order_by("vu.date_used","DESC")->get("vehicles_used vu")->result();
				
				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE){
					echo json_encode(["status" => false , "message" => "Something went wrong.", "action" => "view_vehicles_used"]);
				}else{
					if(!empty($result)){
						foreach ($result as $key => $value) {
							$result[$key]->date_used = convert_timezone($value->date_used, true);
						}
						echo json_encode(["status" => true , "data" => $result, "action" => "view_vehicles_used"]);
					}else{
						echo json_encode(["status" => true , "message" => "No Vehicles Used", "action" => "view_vehicles_used"]);
					}
					
				}
			}else{
				echo json_encode(["status" => false , "message" => "No passed data", "action" => "view_vehicles_used"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "view_vehicles_used"]);
		}
	}
}
