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
				"vehicle_model" => ($data->vehicle_model == '') ? NULL : $data->vehicle_model,
				"year_model" => ($data->year_model == '') ? NULL : $data->year_model,
				"created" => time(),
				"store_id" => $data->store_id,
				"is_active" => 1,
				"deleted" => NULL,
				"axle" => NULL
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

			if(isset($data->vehicle_model)){
				$this->db->where("store_id",$data->store_id);
				$this->db->where("vehicle_id", $data->vehicle_id);
				$this->db->update("vehicle",[
					"vehicle_model" => ($data->vehicle_model == '') ? NULL : $data->vehicle_model					
				]);
			}

			if(isset($data->year_model)){
				$this->db->where("store_id",$data->store_id);
				$this->db->where("vehicle_id", $data->vehicle_id);
				$this->db->update("vehicle",[
					"year_model" => ($data->year_model == '') ? NULL : $data->year_model		
				]);
			}

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

	public function active_vehicles_count(){
		
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$data = $this->post;
			if($data){
				$this->db->where("store_id", $data->store_id);
				$this->db->where("is_active", 1);
				$count = $this->db->where("deleted IS NULL")->get("vehicle")->num_rows();

				 echo json_encode(["status" => true , "data" => $count, "action" => "active_vehicles_count"]);
			}else{
				echo json_encode(["status" => false , "message" => "No passed data", "action" => "active_vehicles_count"]);
			}			
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "active_vehicles_count"]);
		}		
	}

	public function vehicle_status(){
		$data = $this->post;
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			if($data){
				$this->db->trans_start();
				if(isset($data->previous_status)){
					$this->db->where("is_active",1);
					$this->db->where("vehicle_registration_number", $data->vehicle_registration_number);
					$this->db->update("vehicle",[
						"status" => $data->previous_status
					]);
				}else{
					$this->db->where("is_active",1);
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
				$this->db->where("is_active",1);
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
				$this->db->where("is_active",1);
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
				$this->db->join("vehicle v","v.vehicle_registration_number = vu.vehicle_registration_number");
				$this->db->join("vehicle_type vt","vt.vehicle_type_id = vu.vehicle_type");
				$this->db->where("vu.user_id", $data->user_id);
				$this->db->where("v.is_active",1);
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
	public function plan_sorted_vehicles(){
		$list = array();
		$data = $this->post;
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			if($data){
				// $today = strtotime("tomorrow midnight -13 days");
				// $from = strtotime("today 23:59 - 6 days");


				$now = strtotime("tomorrow midnight -1 second");
				$start = strtotime("today midnight - 6 days");
				$this->db->trans_start();
				$this->db->select("r.vehicle_registration_number, v.vehicle_id, v.vehicle_registration_number, v.vehicle_type_id, v.status, vt.type");
				$this->db->join("vehicle v","v.vehicle_registration_number = r.vehicle_registration_number");
				$this->db->join("vehicle_type vt","vt.vehicle_type_id = v.vehicle_type_id");
				$this->db->join("user u", "u.user_id = r.report_by");
				$this->db->join("store s", "s.store_id = u.store_id");
				$this->db->where("v.is_active",1);
				$this->db->where("r.created >=",$start);
				$this->db->where("r.created <=", $now);
				$this->db->where("u.store_id", $data->store_id);
				$recent = $this->db->order_by("r.created", "DESC")->group_by("r.vehicle_registration_number")->get("report r")->result();
				$this->db->trans_complete();
				if ($this->db->trans_status() === FALSE){
					echo json_encode(["status" => false , "message" => "Failed to retrieve recently used.", "action" => "plan_sorted_vehicles"]);
				}else{
					$list['recent'] = $recent;

					if(empty($recent)){
						$this->db->trans_start();

						$this->db->select("v.vehicle_id, v.vehicle_registration_number, v.vehicle_type_id, v.status, vt.type");
						$this->db->join("vehicle_type vt","vt.vehicle_type_id = v.vehicle_type_id");
						$this->db->where("v.store_id", $data->store_id);
						$this->db->where("v.is_active",1);
						$this->db->where("v.deleted IS NULL");
						$list['least'] = $this->db->order_by("v.created", "DESC")->get("vehicle v")->result();

						$this->db->trans_complete();

						if ($this->db->trans_status() === FALSE){
							echo json_encode(["status" => false , "message" => "Failed to retrieve least used.", "action" => "plan_sorted_vehicles"]);
						}else{
							
								echo json_encode(["status" => true , "data" => $list, "action" => "plan_sorted_vehicles"]);
						}
					}else{
						$recent_vehicles = array();
						foreach ($recent as $key => $value) {
							array_push($recent_vehicles, $value->vehicle_registration_number);
						}
						$this->db->trans_start();

						$this->db->select("v.vehicle_id, v.vehicle_registration_number, v.vehicle_type_id, v.status, vt.type");
						$this->db->join("vehicle_type vt","vt.vehicle_type_id = v.vehicle_type_id");
						$this->db->where("v.deleted IS NULL");
						$this->db->where("v.is_active", 1);
						$this->db->where("v.store_id", $data->store_id);
						$this->db->where_not_in("v.vehicle_registration_number",$recent_vehicles);
						$list['least'] = $this->db->order_by("v.created", "DESC")->get("vehicle v")->result();		

						$this->db->trans_complete();			

						if ($this->db->trans_status() === FALSE){
							echo json_encode(["status" => false , "message" => "Failed to retrieve least used.", "action" => "plan_sorted_vehicles"]);
						}else{
							// $this->db->trans_start();
							// $this->db->select("v.vehicle_id, v.vehicle_registration_number, v.vehicle_type_id, v.status, vt.type");
							// $this->db->join("vehicle_type vt","vt.vehicle_type_id = v.vehicle_type_id");
							// $this->db->where("v.store_id", $data->store_id);
							// $this->db->where("v.is_active",0);
							// $list['removed'] = $this->db->order_by("v.created", "DESC")->get("vehicle v")->result();
							// $this->db->trans_complete();

							// if ($this->db->trans_status() === FALSE){
							// 	echo json_encode(["status" => false , "message" => "Failed to retrieve removed.", "action" => "plan_sorted_vehicles"]);
							// }else{
								echo json_encode(["status" => true , "data" => $list, "action" => "plan_sorted_vehicles"]);
							//}
						}
					}
				}
			}else{
				echo json_encode(["status" => false , "message" => "No passed data", "action" => "plan_sorted_vehicles"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "plan_sorted_vehicles"]);
		}
	}

	public function archived_vehicles(){
		$data = $this->post;
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			if($data){
				$this->db->trans_start();
					$this->db->select("v.vehicle_id, v.vehicle_registration_number, v.vehicle_type_id, v.status, vt.type");
					$this->db->join("vehicle_type vt","vt.vehicle_type_id = v.vehicle_type_id");
					$this->db->where("v.store_id", $data->store_id);
					$this->db->where("v.deleted IS NULL");
					$this->db->where("v.is_active",0);
					$list = $this->db->order_by("v.created", "DESC")->get("vehicle v")->result();
				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE){
					echo json_encode(["status" => false , "message" => "Failed to retrieve archived vehicles.", "action" => "archived_vehicles"]);
				}else{
					echo json_encode(["status" => true , "data" => $list, "action" => "archived_vehicles"]);
				}
			}else{
				echo json_encode(["status" => false , "message" => "No passed data", "action" => "archived_vehicles"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "archived_vehicles"]);
		}
		
	}

	public function activate_archived_vehicles(){
		$data = $this->post;
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			if($data){
				$this->db->trans_start();
					
					$this->db->where("deleted IS NULL");
					$this->db->where("is_active",1);
					$total_vehicles = $this->db->where("store_id",$data->store_id)->get("vehicle")->num_rows();

					$this->db->where("active",1);
					$limit = $this->db->select("vehicle_limit")->where("store_id",$data->store_id)->get("user_plan")->row();
				$this->db->trans_complete();

					if(isset($data->vehicles)){
						$vehicles = json_decode($data->vehicles);
						if($limit->vehicle_limit >= ($total_vehicles + count($vehicles))){
							$this->db->trans_start();
				        	$this->db->where("store_id",$data->store_id);
				        	$this->db->where_in("vehicle_id",$vehicles);
				        	$this->db->update("vehicle",[
				        		"is_active" => 1
				        	]);
				        	$this->db->trans_complete();
				        	if ($this->db->trans_status() === FALSE){
				        		echo json_encode(["status" => false , "message" => "Failed to activate vehicles", "action" => "activate_archived_vehicles"]);
				        	}else{
				        		echo json_encode(["status" => true, "message" => "Activated successfully", "action" => "activate_archived_vehicles"]);
				        	}	
						}else{
							echo json_encode(["status" => false, "message" => "Exceeded vehicle limit", "action" => "activate_archived_vehicles"]);
						}
			        }else{
			        	echo json_encode(["status" => false, "message" => "No passed data", "action" => "activate_archived_vehicles"]);
			        }
			}else{
				echo json_encode(["status" => false , "message" => "No passed data", "action" => "archived_vehicles"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "archived_vehicles"]);
		}
	}

	// public function get_recently_used(){
	// 	$data = $this->post;
	// 	$allowed = validate_app_token($this->post->token);
	// 	if($allowed){
	// 		if($data){
	// 			$today = strtotime("tomorrow midnight -1 second");
	// 			$from = strtotime("today midnight - 6 days");
	// 			$this->db->trans_start();
	// 			$this->db->select("r.vehicle_registration_number, v.vehicle_id, v.vehicle_registration_number, v.vehicle_type_id, v.status, vt.type");
	// 			$this->db->join("vehicle v","v.vehicle_registration_number = r.vehicle_registration_number");
	// 			$this->db->join("vehicle_type vt","vt.vehicle_type_id = v.vehicle_type_id");
	// 			$this->db->join("user u", "u.user_id = r.report_by");
	// 			$this->db->join("store s", "s.store_id = u.store_id");
	// 			$this->db->where("r.created >=",$from);
	// 			$this->db->where("r.created <=", $today);
	// 			$this->db->where("u.store_id", $data->store_id);
	// 			$list = $this->db->order_by("r.created", "DESC")->group_by("r.vehicle_registration_number")->get("report r")->result();

	// 			$this->db->trans_complete();
	// 			if ($this->db->trans_status() === FALSE){
	// 				echo json_encode(["status" => false , "message" => "Something went wrong.", "action" => "get_recently_used"]);
	// 			}else{
	// 				echo json_encode(["status" => true , "data" => $list, "action" => "get_recently_used"]);
	// 			}
	// 		}else{
	// 			echo json_encode(["status" => false , "message" => "No passed data", "action" => "get_recently_used"]);
	// 		}
	// 	}else{
	// 		echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "get_recently_used"]);
	// 	}
	// }

	// public function get_least_used(){
	// 	$data = $this->post;
	// 	$allowed = validate_app_token($this->post->token);
	// 	if($allowed){
	// 		if($data){
	// 			// $today = strtotime("tomorrow midnight -13 days");
	// 			// $from = strtotime("today 23:59 - 6 days");


	// 			$now = strtotime("tomorrow midnight -1 second");
	// 			$start = strtotime("today midnight - 6 days");
	// 			$this->db->trans_start();
	// 			$this->db->select("r.vehicle_registration_number");
	// 			$this->db->join("user u", "u.user_id = r.report_by");
	// 			$this->db->join("store s", "s.store_id = u.store_id");
	// 			$this->db->where("r.created >=",$start);
	// 			$this->db->where("r.created <=", $now);
	// 			$this->db->where("u.store_id", $data->store_id);
	// 			$recent = $this->db->order_by("r.created", "DESC")->group_by("r.vehicle_registration_number")->get("report r")->result();


	// 			if(empty($recent)){
	// 				$this->db->trans_start();

	// 				$this->db->select("v.vehicle_id, v.vehicle_registration_number, v.vehicle_type_id, v.status, vt.type");
	// 				$this->db->join("vehicle_type vt","vt.vehicle_type_id = v.vehicle_type_id");
	// 				$this->db->where("v.store_id", $data->store_id);
	// 				$list = $this->db->order_by("v.created", "DESC")->get("vehicle v")->result();

	// 				$this->db->trans_complete();

	// 				if ($this->db->trans_status() === FALSE){
	// 					echo json_encode(["status" => false , "message" => "Something went wrong.", "action" => "get_least_used"]);
	// 				}else{
	// 					echo json_encode(["status" => true , "data" => $list, "action" => "get_least_used"]);
	// 				}
	// 			}else{
	// 				$recent_vehicles = array();
	// 				foreach ($recent as $key => $value) {
	// 					array_push($recent_vehicles, $value->vehicle_registration_number);
	// 				}
	// 				$this->db->trans_start();

	// 				$this->db->select("v.vehicle_id, v.vehicle_registration_number, v.vehicle_type_id, v.status, vt.type");
	// 				$this->db->join("vehicle_type vt","vt.vehicle_type_id = v.vehicle_type_id");
	// 				$this->db->where("v.store_id", $data->store_id);
	// 				$this->db->where_not_in("v.vehicle_registration_number",$recent_vehicles);
	// 				$list = $this->db->order_by("v.created", "DESC")->get("vehicle v")->result();		

	// 				$this->db->trans_complete();			

	// 				if ($this->db->trans_status() === FALSE){
	// 					echo json_encode(["status" => false , "message" => "Something went wrong.", "action" => "get_least_used"]);
	// 				}else{
	// 					echo json_encode(["status" => true , "data" => $list, "action" => "get_least_used"]);
	// 				}
	// 			}

	// 		}else{
	// 			echo json_encode(["status" => false , "message" => "No passed data", "action" => "get_least_used"]);
	// 		}
	// 	}else{
	// 		echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "get_least_used"]);
	// 	}

	private function codeToMessage($code) 
    { 
        switch ($code) { 
            case UPLOAD_ERR_INI_SIZE: 
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini"; 
                break; 
            case UPLOAD_ERR_FORM_SIZE: 
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form"; 
                break; 
            case UPLOAD_ERR_PARTIAL: 
                $message = "The uploaded file was only partially uploaded"; 
                break; 
            case UPLOAD_ERR_NO_FILE: 
                $message = "No file was uploaded"; 
                break; 
            case UPLOAD_ERR_NO_TMP_DIR: 
                $message = "Missing a temporary folder"; 
                break; 
            case UPLOAD_ERR_CANT_WRITE: 
                $message = "Failed to write file to disk"; 
                break; 
            case UPLOAD_ERR_EXTENSION: 
                $message = "File upload stopped by extension"; 
                break; 

            default: 
                $message = "Unknown upload error"; 
                break; 
        } 
        return $message; 
    } 

	public function save_tire_management(){
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			if(!empty($_FILES)){
				if($_FILES['data']['error'] == 0){
					$data = file_get_contents($_FILES['data']['tmp_name']);

					$data = json_decode($data);
					if($data){
						foreach($data as $ind => $val){
							$this->db->trans_start();

							$tire_report = $this->db->insert("tire_report", [
								"vehicle_id" => $val->vehicle_id,
								"status" => $val->status,
								"created" => $val->created,
								"store_id" => $val->store_id,
								"tire_report_number" => NULL
							]);
							if($tire_report){
								$tire_report_id = $this->db->insert_id();
								$tire_report_number = date("dmY").'-'.sprintf('%05d', $tire_report_id);
								$this->db->where("tire_report_id",$tire_report_id);
								$update = $this->db->update("tire_report", [
									"tire_report_number" => $tire_report_number
								]);

								foreach ($val->tire_info_report as $key => $value) {
									$tire_info = $this->db->insert("tire_info_report",[
										"vt_id" => $value->vt_id,
										"tire_report_id" => $tire_report_id,
										"current_pressure" => $value->current_pressure,
										"cp_time" => $value->cp_time,
										"new_pressure" => $value->new_pressure,
										"np_time" => $value->np_time,
										"left_treadDepth" => $value->left_treadDepth,
										"left_td_time" => $value->left_td_time,
										"right_treadDepth" => $value->right_treadDepth,
										"right_td_time" => $value->right_td_time,
										"tread_note" => $value->tread_note,
										"is_damage" => $value->is_damage,
										"damage_time" => $value->damage_time,
										"damage_note" => $value->damage_note,
										"tire_status" => $value->tire_status
									]);
									$tire_info_report_id = $this->db->insert_id();

									if($tire_info){										
										if(!empty($value->damage_images)){
											$this->tire_image($tire_info_report_id , $tire_report_id, 1,$value->damage_images);
										}

										if(!empty($value->tread_images)){
											$this->tire_image($tire_info_report_id , $tire_report_id, 2,$value->tread_images);
										}
									}else{
										echo json_encode(["status" => false , "message" => "Saving failed", "action" => "save_tire_management"]);
										return false;
									}
								}	

							}else{
								echo json_encode(["status" => false , "message" => "Saving failed", "action" => "save_tire_management"]);
								return false;
							}

							$this->db->trans_complete();

							if($this->db->trans_status() === FALSE){
								echo json_encode(["status" => false , "message" => "Something went wrong.", "action" => "save_tire_management"]);
							}else{
								$this->db->trans_start();
								$this->db->where("role", "ADMIN PREMIUM");
								$userid = $this->db->select("user_id")->where("store_id",$val->store_id)->get("user")->row()->user_id;

								$this->db->insert("notification", [
									"description" => "Tire Report #".$tire_report_number." | Vehicle Reg. #: ".$val->vehicle_registration_number." | Status: ".report_type(1),
									"type" => 1,
									"isread" => 0,
									"ref_id" => $tire_report_id,
									"user_id" => $userid,
									"created" => time()
								]);

								$this->db->trans_complete();
								echo json_encode(["status" => true , "message" => "Saved Successfully", "action" => "save_tire_management"]);
							}
						}
					}else{
						echo json_encode(["status" => false , "message" => "Data is empty","action" => "save_tire_management"]);
					}
				}else{					
					echo json_encode(["status" => false , "message" => $this->codeToMessage($_FILES['data']['error']),"action" => "save_tire_management"]);
				}
			}else{
				echo json_encode(["status" => false , "message" => "No file","action" => "save_tire_management"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "save_tire_management"]);
		}
	}
	private function tire_image($tire_info_report_id , $tire_report_id, $type,$images){
		$ctr = 1;
		foreach($images as $img){

			$name = $tire_report_id.'_'.$tire_info_report_id.'_'.$ctr.'_'.time().'.PNG';
	        $year = date("Y");
	        $month = date("m");
	        
	        $folder = "./public/upload/tire/".$year."/".$month;
	        
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
		    
		    $this->db->insert('tire_images', [
		    	"tir_id" 			=> $tire_info_report_id,
		    	"tire_report_id"	=> $tire_report_id ,
		    	"type"				=> $type ,
		    	"image_path"		=> $year."/".$month.'/' ,
		    	"image_name"		=> $name
		    ]);

		    $ctr++;
		}	
	}

	public function update_tires(){
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			if(!empty($_FILES)){
				if($_FILES['data']['error'] == 0){
					$data = file_get_contents($_FILES['data']['tmp_name']);
					$data = json_decode($data);

					if($data){
						foreach ($data as $ind => $val) {
							$this->db->trans_start();
							if(isset($val->axle)){
								$this->db->where("vehicle_id", $val->vehicle_id)->update("vehicle",[
									"axle" => $val->axle
								]); 
							}
							foreach ($val->tire as $key => $value) {
								$this->db->where("vt_id", $value->vt_id);
								$this->db->where("vehicle_id",$val->vehicle_id);
								$this->db->update("vehicle_tires",[
									"axle_no" => $value->axle_no,
									"tire_count" => $value->tire_count,
									"position" => $value->position,
									"created" => $value->created,
									"deleted" => $value->deleted
								]);
							}
							$this->db->trans_complete();
							if ($this->db->trans_status() === FALSE){
								echo json_encode(["status" => false , "message" => "Something went wrong.", "action" => "update_tires"]);
							}else{
								echo json_encode(["status" => true , "message" => "Updated Successfully", "action" => "update_tires"]);
							}
						}						
					}else{
						echo json_encode(["status" => false , "message" => "Data is empty","action" => "update_tires"]);
					}
				}else{					
					echo json_encode(["status" => false , "message" => $this->codeToMessage($_FILES['data']['error']),"action" => "update_tires"]);
				}
			}else{
				echo json_encode(["status" => false , "message" => "No file","action" => "update_tires"]);
			}			
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "update_tires"]);
		}
	}
	public function add_tire_layout(){
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			if(!empty($_FILES)){
				if($_FILES['data']['error'] == 0){
					$data = file_get_contents($_FILES['data']['tmp_name']);

					$data = json_decode($data);

					if($data){
						$this->db->trans_start();
						foreach($data as $k => $v){
							if(isset($data[$k]->axle)){
								$this->db->where("vehicle_id", $data[$k]->vehicle_id)->update("vehicle",[
									"axle" => $data[$k]->axle,
									"driver_seat_position" => $data[$k]->driver_seat_position
								]);
							}

							foreach ($data[$k]->tire as $key => $value) {
								$this->db->insert("vehicle_tires",[
									"vehicle_id" => $data[$k]->vehicle_id,
									"axle_no" => $value->axle_no,
									"tire_count" => $value->tire_count,
									"position" => $value->position,
									"created" => $value->created,
									"deleted" => $value->deleted
								]);
							}
						}
						$this->db->trans_complete();
						if ($this->db->trans_status() === FALSE){
							echo json_encode(["status" => false , "message" => "Something went wrong.", "action" => "add_tire_layout"]);
						}else{
							echo json_encode(["status" => true , "message" => "Saved Successfully", "action" => "add_tire_layout"]);
						}
					}else{
						echo json_encode(["status" => false , "message" => "Data is empty","action" => "add_tire_layout"]);
					}
				}else{					
					echo json_encode(["status" => false , "message" => $this->codeToMessage($_FILES['data']['error']),"action" => "add_tire_layout"]);
				}
			}else{
				echo json_encode(["status" => false , "message" => "No file","action" => "add_tire_layout"]);
			}
			
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "add_tire_layout"]);
		}
	}

	public function get_tires(){
		$data = $this->post;
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			if($data){
				$this->db->trans_start();


				$this->db->select("vehicle_id, axle, driver_seat_position");
				$this->db->where("vehicle_id", $data->vehicle_id);
				$data = $this->db->get("vehicle")->row();

				if($data){
					$this->db->select('vt_id, axle_no, tire_count, position, created, deleted');
					$data->tire = $this->db->where("vehicle_id",$data->vehicle_id)->get("vehicle_tires")->result();
				}else{
					return false;
				}

				$this->db->trans_complete();
				if ($this->db->trans_status() === FALSE){
					echo json_encode(["status" => false , "message" => "Something went wrong.", "action" => "get_tires"]);
				}else{
					echo json_encode(["status" => true , "data" => $data, "action" => "get_tires"]);
				}
			}else{
				echo json_encode(["status" => false , "message" => "No passed data","action" => "get_tires"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "get_tires"]);
		}
	}

	public function tire_management_list(){
		$data = $this->post;
		$allowed = validate_app_token($this->post->token);		
		$result = new stdClass;
		if($allowed){
			if($data){
				$this->db->trans_start();
				$this->db->select("tr.*, v.vehicle_id, v.vehicle_registration_number, v.vehicle_type_id, v.axle, v.driver_seat_position");
				$this->db->join("vehicle v","v.vehicle_id = tr.vehicle_id");
				$this->db->where("tr.store_id",$data->store_id);				
				$this->db->where("v.is_active",1);
				if(isset($data->vehicle_id)){
					$this->db->where("v.vehicle_id",$data->vehicle_id);
				}
				$this->db->order_by("tr.created","DESC");
				$result = $this->db->get("tire_report tr")->result();

				$this->db->trans_complete();
				if ($this->db->trans_status() === FALSE){
					echo json_encode(["status" => false , "message" => "Something went wrong.", "action" => "tire_management_list"]);
				}else{
					if($result){
						foreach ($result as $key => $value) {
							$this->db->select("tir.*");
							$this->db->join("vehicle_tires vt","vt.vt_id = tir.vt_id");
							$this->db->where("tir.tire_report_id",$value->tire_report_id);
							$result[$key]->tire = $this->db->get("tire_info_report tir")->result();
							foreach ($result[$key]->tire as $k => $v) {
								$this->db->select('image_name,image_path');
								$this->db->where('tire_report_id', $value->tire_report_id);
								$this->db->where("tir_id", $result[$key]->tire[$k]->tir_id);
								$damage_images = $this->db->where("type",1)->get('tire_images')->result();
								foreach ($damage_images as $d => $damage) {
									$result[$key]->tire[$k]->damage_images[$d] = $this->config->site_url("public/upload/tire/".$damage->image_path.$damage->image_name);
								}

								$this->db->select('image_name,image_path');
								$this->db->where('tire_report_id', $value->tire_report_id);
								$this->db->where("tir_id", $result[$key]->tire[$k]->tir_id);
								$tread_images = $this->db->where("type",2)->get('tire_images')->result();
								foreach ($tread_images as $t => $tread) {
									$result[$key]->tire[$k]->tread_images[$t] = $this->config->site_url("public/upload/tire/".$tread->image_path.$tread->image_name);
								}
								
							}
						}
						echo json_encode(["status" => true , "data" => $data, "action" => "tire_management_list"]);
					}else{
						echo json_encode(["status" => false , "message" => "No data available", "action" => "tire_management_list"]);
					}
					
				}
			}else{
				echo json_encode(["status" => false , "message" => "No passed data","action" => "tire_management_list"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "tire_management_list"]);
		}
	}

	public function tire_management(){
		$data = $this->post;

		$allowed = validate_app_token($this->post->token);
		$result = new stdClass;
		if($allowed){
			if($data){
				$this->db->trans_start();
				$this->db->select("tr.*,  v.vehicle_id, v.vehicle_registration_number, v.vehicle_type_id, v.axle, v.driver_seat_position");
				$this->db->join("vehicle v","v.vehicle_id = tr.vehicle_id");
				$this->db->where("v.is_active",1);
				$this->db->where("tr.store_id",$data->store_id);
				$this->db->where("tr.vehicle_id",$data->vehicle_id);
				$this->db->limit(1)->order_by("tr.created","DESC");
				$result = $this->db->get("tire_report tr")->row();

				$this->db->trans_complete();
				if ($this->db->trans_status() === FALSE){
					echo json_encode(["status" => false , "message" => "Something went wrong.", "action" => "tire_management"]);
				}else{
					if($result){
						$this->db->select("tir.*");
						$this->db->join("vehicle_tires vt","vt.vt_id = tir.vt_id");
						$this->db->where("tir.tire_report_id",$result->tire_report_id);
						$result->tire = $this->db->get("tire_info_report tir")->result();
						foreach ($result->tire as $k => $v) {
							$this->db->select('image_name,image_path');
							$this->db->where('tire_report_id', $result->tire_report_id);
							$this->db->where("tir_id", $result->tire[$k]->tir_id);
							$damage_images = $this->db->where("type",1)->get('tire_images')->result();
							foreach ($damage_images as $d => $damage) {
								$result->tire[$k]->damage_images[$d] = $this->config->site_url("public/upload/tire/".$damage->image_path.$damage->image_name);
							}

							$this->db->select('image_name,image_path');
							$this->db->where('tire_report_id', $result->tire_report_id);
							$this->db->where("tir_id", $result->tire[$k]->tir_id);
							$tread_images = $this->db->where("type",2)->get('tire_images')->result();
							foreach ($tread_images as $t => $tread) {
								$result->tire[$k]->tread_images[$t] = $this->config->site_url("public/upload/tire/".$tread->image_path.$tread->image_name);
							}
						}
						echo json_encode(["status" => true , "data" => $result, "action" => "tire_management"]);
					}else{
						echo json_encode(["status" => false, "message" => "No data Available", "action" => "tire_management"]);
					}					
				}
			}else{
				echo json_encode(["status" => false , "message" => "No passed data","action" => "tire_management"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "tire_management"]);
		}
	}
}
