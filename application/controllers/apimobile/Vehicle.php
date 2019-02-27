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

			$insert = $this->db->insert("vehicle",[
				"vehicle_registration_number" => $data->vehicle_registration_number,
				"status"                        => 0,
                "availability"                  => 1,
                "last_checked"                  => NULL,
				"vehicle_type_id" => $data->vehicle_type_id,
				"created" => time(),
				"store_id" => $data->store_id,
				"deleted" => NULL,
				"axle" => isset($data->axle) ? $data->axle : NULL
			]);
			// if($insert){
			// 	$vehicle_id = $this->db->insert_id();

			// 	if(isset($data->tire)){
			// 		foreach ($data->tire as $key => $value) {
			// 			$this->db->insert("vehicle_tires",[
			// 				"vehicle_id" => $vehicle_id,
			// 				"axle_no" => $value->axle_no,
			// 				"tire_count" => $value->tire_count,
			// 				"position" => $value->position,
			// 				"created" => time(),
			// 				"deleted" => NULL
			// 			]);
			// 		}
			// 	}
			// }else{
			// 	echo json_encode(["status" => false , "message" => "Failed", "action" => "add"])
			// }			

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

	public function get_recently_used(){
		$data = $this->post;
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			if($data){
				$today = strtotime("tomorrow midnight -1 second");
				$from = strtotime("today midnight - 6 days");
				$this->db->trans_start();
				$this->db->select("r.vehicle_registration_number, v.vehicle_id, v.vehicle_registration_number, v.vehicle_type_id, v.status, vt.type");
				$this->db->join("vehicle v","v.vehicle_registration_number = r.vehicle_registration_number");
				$this->db->join("vehicle_type vt","vt.vehicle_type_id = v.vehicle_type_id");
				$this->db->join("user u", "u.user_id = r.report_by");
				$this->db->join("store s", "s.store_id = u.store_id");
				$this->db->where("r.created >=",$from);
				$this->db->where("r.created <=", $today);
				$this->db->where("u.store_id", $data->store_id);
				$this->db
				$list = $this->db->order_by("r.created", "DESC")->group_by("r.vehicle_registration_number")->get("report r")->result();

				$this->db->trans_complete();
				if ($this->db->trans_status() === FALSE){
					echo json_encode(["status" => false , "message" => "Something went wrong.", "action" => "get_recently_used"]);
				}else{
					echo json_encode(["status" => true , "data" => $list, "action" => "get_recently_used"]);
				}
			}else{
				echo json_encode(["status" => false , "message" => "No passed data", "action" => "get_recently_used"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "get_recently_used"]);
		}
	}

	public function get_least_used(){
		$data = $this->post;
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			if($data){
				// $today = strtotime("tomorrow midnight -13 days");
				// $from = strtotime("today 23:59 - 6 days");


				$now = strtotime("tomorrow midnight -1 second");
				$start = strtotime("today midnight - 6 days");
				$this->db->trans_start();
				$this->db->select("r.vehicle_registration_number");
				$this->db->join("user u", "u.user_id = r.report_by");
				$this->db->join("store s", "s.store_id = u.store_id");
				$this->db->where("r.created >=",$start);
				$this->db->where("r.created <=", $now);
				$this->db->where("u.store_id", $data->store_id);
				$recent = $this->db->order_by("r.created", "DESC")->group_by("r.vehicle_registration_number")->get("report r")->result();


				if(empty($recent)){
					$this->db->trans_start();

					$this->db->select("v.vehicle_id, v.vehicle_registration_number, v.vehicle_type_id, v.status, vt.type");
					$this->db->join("vehicle_type vt","vt.vehicle_type_id = v.vehicle_type_id");
					$this->db->where("v.store_id", $data->store_id);
					$list = $this->db->order_by("v.created", "DESC")->get("vehicle v")->result();

					$this->db->trans_complete();

					if ($this->db->trans_status() === FALSE){
						echo json_encode(["status" => false , "message" => "Something went wrong.", "action" => "get_least_used"]);
					}else{
						echo json_encode(["status" => true , "data" => $list, "action" => "get_least_used"]);
					}
				}else{
					$recent_vehicles = array();
					foreach ($recent as $key => $value) {
						array_push($recent_vehicles, $value->vehicle_registration_number);
					}
					$this->db->trans_start();

					$this->db->select("v.vehicle_id, v.vehicle_registration_number, v.vehicle_type_id, v.status, vt.type");
					$this->db->join("vehicle_type vt","vt.vehicle_type_id = v.vehicle_type_id");
					$this->db->where("v.store_id", $data->store_id);
					$this->db->where_not_in("v.vehicle_registration_number",$recent_vehicles);
					$list = $this->db->order_by("v.created", "DESC")->get("vehicle v")->result();		

					$this->db->trans_complete();			

					if ($this->db->trans_status() === FALSE){
						echo json_encode(["status" => false , "message" => "Something went wrong.", "action" => "get_least_used"]);
					}else{
						echo json_encode(["status" => true , "data" => $list, "action" => "get_least_used"]);
					}
				}

			}else{
				echo json_encode(["status" => false , "message" => "No passed data", "action" => "get_least_used"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "get_least_used"]);
		}
	}

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
		$data = $this->post;
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			if(!empty($_FILES)){
				if($_FILES['data']['error'] == 0){
					$data = file_get_contents($_FILES['data']['tmp_name']);

					$data = (object)json_decode($data);

					if($data){
						$this->db->trans_start();

						$tire_report = $this->db->insert("tire_report", [
							"vehicle_id" => $data->vehicle_id,
							"status" => $data->status,
							"created" => $data->created
						]);
						if($tire_report){
							$tire_report_id = $this->db->insert_id();
							foreach ($data->tire_info_report as $key => $value) {
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
									"is_damage" => $value->is_damage,
									"damage_time" => $value->damage_time,
									"note" => $value->note,
									"tire_status" => $value->status
								]);
								if($tire_info){
									$tire_info_report_id = $this->db->insert_id();
									if(!empty($value->damage_images)){
										$this->tire_image($tire_info_report_id , $tire_report_id, 1,$value->images);
									}

									if(!empty($value->tread_images)){
										$this->tire_image($tire_info_report_id , $tire_report_id, 2,$value->images);
									}
								}else{
									echo json_encode(["status" => false , "message" => "Saving failed", "action" => "save_tire_management"])
									return false;
								}
							}	

						}else{
							echo json_encode(["status" => false , "message" => "Saving failed", "action" => "save_tire_management"])
							return false;
						}

						$this->db->trans_complete();

						if($this->db->trans_status() === FALSE){
							echo json_encode(["status" => false , "message" => "Something went wrong.", "action" => "save_tire_management"]);
						}else{
							echo json_encode(["status" => true , "message" => "Saved Successfully", "action" => "save_tire_management"]);
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
		    	"tir_id" 			=> $tire_report_id,
		    	"tire_report_id"	=> $tire_report_id ,
		    	"type"				=> $type ,
		    	"image_path"		=> $year."/".$month.'/' ,
		    	"image_name"		=> $name
		    ]);

		    $ctr++;
		}	
	}

	public function update_tires(){
		$data = $this->post;
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			if(!empty($_FILES)){
				if($_FILES['data']['error'] == 0){
					$data = file_get_contents($_FILES['data']['tmp_name']);

					$data = (object)json_decode($data);

					if($data){
						$this->db->trans_start();
							if(isset($data->axle)){
								$this->db->where("vehicle_id", $data->vehicle_id)->update("vehicle",[
									"axle" => $data->axle
								]);
							}

							foreach ($data->tire as $key => $value) {
								$this->db->where("vt_id", $value->vt_id);
								$this->db->where("vehicle_id",$data->vehicle_id);
								$this->db->update("vehicle_tires",[
									"axle_no" => $value->axle_no,
									"tire_count" => $value->tire_count,
									"position" => $value->position,
									"deleted" => ($value->deleted == '') ? NULL : convert_timezone(strtotime(str_replace("/"," ",$value->deleted)), true, false)
								]);
							}

						$this->db->trans_complete();
						if ($this->db->trans_status() === FALSE){
							echo json_encode(["status" => false , "message" => "Something went wrong.", "action" => "update_tires"]);
						}else{
							echo json_encode(["status" => true , "message" => "Saved Successfully", "action" => "update_tires"]);
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
	public function add_tires(){
		$data = $this->post;
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			if(!empty($_FILES)){
				if($_FILES['data']['error'] == 0){
					$data = file_get_contents($_FILES['data']['tmp_name']);

					$data = (object)json_decode($data);

					if($data){
						$this->db->trans_start();
							if(isset($data->axle)){
								$this->db->where("vehicle_id", $data->vehicle_id)->update("vehicle",[
									"axle" => $data->axle
								]);
							}

							foreach ($data->tire as $key => $value) {
								$this->db->insert("vehicle_tires",[
									"vehicle_id" => ,$data->vehicle_id,
									"axle_no" => $value->axle_no,
									"tire_count" => $value->tire_count,
									"position" => $value->position,
									"created" => ($value->created == '') ? NULL : convert_timezone(strtotime(str_replace("/"," ",$value->created)), true, false)
								]);
							}

						$this->db->trans_complete();
						if ($this->db->trans_status() === FALSE){
							echo json_encode(["status" => false , "message" => "Something went wrong.", "action" => "add_tires"]);
						}else{
							echo json_encode(["status" => true , "message" => "Saved Successfully", "action" => "add_tires"]);
						}
					}else{
						echo json_encode(["status" => false , "message" => "Data is empty","action" => "add_tires"]);
					}
				}else{					
					echo json_encode(["status" => false , "message" => $this->codeToMessage($_FILES['data']['error']),"action" => "add_tires"]);
				}
			}else{
				echo json_encode(["status" => false , "message" => "No file","action" => "add_tires"]);
			}
			
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "add_tires"]);
		}
	}
}
