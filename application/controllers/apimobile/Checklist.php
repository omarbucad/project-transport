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

		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$this->db->select("c.checklist_id , c.checklist_name , c.vehicle_type_id");
			$result = $this->db->where("c.status" , 1)->where("c.deleted IS NULL")->order_by("c.checklist_name" , "ASC")->get("checklist c")->result();

			echo json_encode(["status" => 1 , "data" => $result, "action" => "get_my_checklist"]);
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "get_my_checklist"]);
		}
		
	}

	public function get_checklists_by_type(){
		$allowed = validate_app_token($this->post->token);
		if($allowed){

			$types = $this->db->select("vehicle_type_id, type")->where("deleted IS NULL")->get("vehicle_type")->result();

			foreach ($types as $key => $value) {
				$this->db->select("c.checklist_id , c.checklist_name , c.vehicle_type_id");

				$this->db->where("c.vehicle_type_id",$value->vehicle_type_id);
				$types[$key]->checklist = $this->db->where("c.status" , 1)->where("c.deleted IS NULL")->order_by("c.checklist_name" , "ASC")->get("checklist c")->row();

				$types[$key]->checklist->items = $this->db->select("id,checklist_id,item_name,item_position,help_text")->where("checklist_id" , $types[$key]->checklist->checklist_id)->where("DELETED IS NULL")->order_by("item_position" , "ASC")->get("checklist_items")->result();

				foreach($types[$key]->checklist->items as $item => $v){
					$this->db->where("deleted IS NULL");
					$images = $this->db->select("image_path,image_name,help_image_path,help_image_name")->where("id",$v->id)->get("checklist_items")->row();
					$types[$key]->checklist->items[$item]->image = ($images->image_name) ? $this->config->site_url("thumbs/images/checklist/".$images->image_path."/250/250/".$images->image_name) : "";
					$types[$key]->checklist->items[$item]->help_image  = ($images->help_image_name) ? $this->config->site_url("thumbs/images/checklist/".$images->help_image_path."/250/250/".$images->help_image_name) : "";
				}
			}

			echo json_encode(["status" => true , "data" => $types, "action" => "get_checklists_by_type"]);
			
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "get_checklists_by_type"]);
		}
	}

	public function get_checklist_by_type(){
		
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$type = $this->post->vehicle_type_id;
			

			$this->db->select("c.checklist_id , c.checklist_name , c.vehicle_type_id");
			//$this->db->join("user_checklist uc" , "uc.checklist_id = c.checklist_id");
			$this->db->where("c.vehicle_type_id",$type);
			$result = $this->db->where("c.status" , 1)->where("c.deleted IS NULL")->order_by("c.checklist_name" , "ASC")->get("checklist c")->row();
			if($result){

				echo json_encode(["status" => true , "data" => $result, "action" => "get_checklist_by_type"]);
			}else{
				echo json_encode(["status" => false , "message" => "No checklist available", "action" => "get_checklist_by_type"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "get_checklist_by_type"]);
		}

	}

	
/*	public function get_vehicle_registration_list(){
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
	}*/

	public function get_all_vehicles(){
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$store_id = $this->post->store_id;
			
			$this->db->select("v.vehicle_id , v.vehicle_registration_number, v.vehicle_type_id, v.status, v.availability,v.last_checked, vt.type");
			$this->db->join("vehicle_type vt","vt.vehicle_type_id = v.vehicle_type_id");
			$this->db->where("vt.deleted IS NULL");
			$data = $this->db->where("store_id" , $store_id)->where("v.deleted IS NULL")->order_by("v.vehicle_registration_number" , "ASC")->get("vehicle v")->result();

			if($data){
				echo json_encode(["status" => true , "data" => $data, "action" => "get_all_vehicles"]);
			}else{
				echo json_encode(["status" => false , "message" => "No data available", "action" => "get_all_vehicles"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "get_all_vehicles"]);
		}
	}

	public function get_checklist_items(){

		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$checklist_id = $this->post->checklist_id;
			if($checklist_id){
				$result = $this->db->select("id,checklist_id,item_name,item_position,help_text")->where("checklist_id" , $checklist_id)->where("DELETED IS NULL")->order_by("item_position" , "ASC")->get("checklist_items")->result();
				if($result){

					foreach($result as $key => $row){
					$this->db->where("deleted IS NULL");
						$images = $this->db->select("image_path,image_name,help_image_path,help_image_name")->where("id",$row->id)->get("checklist_items")->row();
						$result[$key]->image = ($images->image_name) ? $this->config->site_url("thumbs/images/checklist/".$images->image_path."/250/250/".$images->image_name) : "";
						$result[$key]->help_image  = ($images->help_image_name) ? $this->config->site_url("thumbs/images/checklist/".$images->help_image_path."/250/250/".$images->help_image_name) : "";
					}

					echo json_encode(["status" => true , "data" => $result, "action" => "get_checklist_items"]);
				}else{
					echo json_encode(["status" => false , "message" => "No checklist data available", "action" => "get_checklist_items"]);
				}

			}else{
				echo json_encode(["status" => false , "message" => "No checklist id passed", "action" => "get_checklist_items"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "get_checklist_items"]);
		}
		
	}

	public function save_checklist(){
		
		$allowed = validate_app_token($this->post->token);
		if($allowed){
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
					"vehicle_registration_number"	=> ($data->vehicle_registration_number != '') ? $data->vehicle_registration_number : NULL ,
					"trailer_number"				=> ($data->trailer_number != '') ? $data->trailer_number : NULL ,
					"checklist_id"					=> $data->checklist_id,
					"start_mileage"					=> $data->start_mileage ,
					"end_mileage"					=> (isset($data->end_mileage)) ? $data->end_mileage : NULL ,
					"report_notes"					=> (isset($data->note)) ? $data->note : NULL ,
					"created"						=> strtotime(convert_timezone(strtotime(date("M d Y h:i:s A", time())), true, true)),
					"remind_in"						=> $remind_in,
					"remind_done"					=> false
				]);
				$signature_path = '';
				$report_id = $this->db->insert_id();
				
				if(isset($data->end_mileage)){
					$signature_path = $this->save_signature($report_id);
				}
				

				//Create status
				$this->db->insert("report_status" , [
					"report_id"			=> $report_id ,
					"status"			=> ($this->isDefect()) ? 1 : 0 ,
					"notes"				=> "Initial" ,
					"user_id"			=> $user->user_id ,
					"created"			=> time(),
					"start_longitude"	=> ($data->start_longitude == '') ? NULL : $data->start_longitude,
					"start_latitude"	=> ($data->start_latitude == '') ? NULL : $data->start_latitude,
					"longitude"			=> (isset($data->longitude)) ?  $data->longitude : NULL ,
					"latitude"			=> (isset($data->latitude)) ? $data->latitude : NULL,
					"signature"			=> ($signature_path == '')? NULL : $signature_path
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
						"timestamp"				=> $item->timestamp
					);

					$this->db->insert("report_checklist" , $item_batch);
					$report_checklist_id = $this->db->insert_id();

					if(!empty($item->images)){
						$this->save_image($report_id , $report_checklist_id , $item->images);
					}
				}

				if($data->trailer_number == ''){
					$this->db->select("id");
					$this->db->where("checklist_id", $data->checklist_id);
					$this->db->where("item_position >=", 29);
					$this->db->where("deleted IS NULL");
					$c_items = $this->db->get("checklist_items")->result();

					foreach ($c_items as $c => $c_v) {
						$this->db->insert("report_checklist",[
							"report_id"				=> $report_id ,
							"checklist_item_id"		=> $c_v->id ,
							"checklist_value"		=> NULL,
							"checklist_ischeck"		=> 3
						]);
					}
				}

				$report_number = date("dmY").'-'.sprintf('%05d', $report_id);

				$this->db->where("report_id" , $report_id)->update("report" , [
					"report_number"		=> $report_number,
					"status_id"			=> $status_id ,
				]);

				$this->db->where("vehicle_registration_number",$data->vehicle_registration_number);
				$this->db->update("vehicle",[
					"last_checked" => time(),
					"status" => 1
				]);

				$this->db->insert("vehicles_used",[
					"user_id" => $user->user_id,
					"vehicle_registration_number" => $data->vehicle_registration_number,
					"trailer_number" => ($data->trailer_number != '') ? $data->trailer_number : NULL,
					"vehicle_type" => $data->vehicle_type_id,
					"date_used" => time()
				]);
				
				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE){

		            echo json_encode(["status" => false , "message" => "Something went wrong","action" => "save_checklist"]);

		        }else{
		            $pdf = $this->pdf($report_id);

		            $this->db->where("report_id" , $report_id)->update("report" , [
						"pdf_path"			=> $pdf['path'],
						"pdf_file" 			=> $pdf['filename']
					]);
		           
		            echo json_encode(["status" => true , "message" => "Successfully Submitted","action" => "save_checklist"]);
		        }
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "save_checklist"]);
		}
	}

	// public function json_validate($string)
	// {
	//     // decode the JSON data
	//     $result = json_decode($string);

	//     // switch and check possible JSON errors
	//     switch (json_last_error()) {
	//         case JSON_ERROR_NONE:
	//             $error = ''; // JSON is valid // No error has occurred
	//             break;
	//         case JSON_ERROR_DEPTH:
	//             $error = 'The maximum stack depth has been exceeded.';
	//             break;
	//         case JSON_ERROR_STATE_MISMATCH:
	//             $error = 'Invalid or malformed JSON.';
	//             break;
	//         case JSON_ERROR_CTRL_CHAR:
	//             $error = 'Control character error, possibly incorrectly encoded.';
	//             break;
	//         case JSON_ERROR_SYNTAX:
	//             $error = 'Syntax error, malformed JSON.';
	//             break;
	//         // PHP >= 5.3.3
	//         case JSON_ERROR_UTF8:
	//             $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
	//             break;
	//         // PHP >= 5.5.0
	//         case JSON_ERROR_RECURSION:
	//             $error = 'One or more recursive references in the value to be encoded.';
	//             break;
	//         // PHP >= 5.5.0
	//         case JSON_ERROR_INF_OR_NAN:
	//             $error = 'One or more NAN or INF values in the value to be encoded.';
	//             break;
	//         case JSON_ERROR_UNSUPPORTED_TYPE:
	//             $error = 'A value of a type that cannot be encoded was given.';
	//             break;
	//         default:
	//             $error = 'Unknown JSON error occured.';
	//             break;
	//     }

	//     if ($error !== '') {
	//         // throw the Exception or exit // or whatever :)
	//         exit($error);
	//     }

	//     // everything is OK
	//     return $result;
	// }

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

	public function offline_save_checklist(){

		// print_r_die($this->post);

		$allowed = validate_app_token($this->input->post("token"));
		if($allowed){
			$data = '';

			if(!empty($_FILES)){
				if($_FILES['data']['error'] == 0){
					$data = file_get_contents($_FILES['data']['tmp_name']);
					$data = (object)json_decode($data);

					if($data){

						foreach ($data as $key => $value) {				
							$remind_in = NULL;
							$this->db->trans_start();
							$defect = 0;
							$fixed = 0;

							//Create report
							if($value->role == "MECHANIC"){
								$checklist_data = $this->db->select("reminder_every")->where("checklist_id" , $value->checklist_id)->get("checklist")->row();
								$remind_in = remind_in($checklist_data->reminder_every);

								//then update all the report of the vehicle into remind done

								$this->db->where("report_by" , $value->user_id)->where("vehicle_registration_number" , $value->vehicle_registration_number)->update("report" , [
									"remind_done" => true
								]);
							}

							$this->db->insert("report" , [
								"report_by"						=> $value->user_id ,
								"vehicle_registration_number"	=> ($value->vehicle_registration_number != '') ? $value->vehicle_registration_number : NULL ,
								"trailer_number"				=> ($value->trailer_number != '') ? $value->trailer_number : NULL ,
								"checklist_id"					=> $value->checklist_id,
								"start_mileage"					=> $value->start_mileage ,
								"end_mileage"					=> $value->end_mileage ,
								"report_notes"					=> (isset($value->report_notes)) ? $value->report_notes : NULL ,
								"created"						=> strtotime($value->created),
								"remind_in"						=> $remind_in,
								"remind_done"					=> false
							]);

							$report_id = $this->db->insert_id();
							$report_number = date("dmY").'-'.sprintf('%05d', $report_id);

							$signature_path = '';
							
							if(isset($value->end_mileage)){
								$signature_path = $this->offline_save_signature($report_id, $value->signature);
							}

							//save checklist items
							//$checklist = json_decode($value->checklist);
						
							foreach($value->checklist as $item){

								$item_batch = array(
									"report_id"				=> $report_id ,
									"checklist_item_id"		=> $item->checklist_id ,
									"checklist_value"		=> ($item->note == '') ? NULL : $item->note,
									"checklist_ischeck"		=> $item->checkbox,
									"timestamp"				=> convert_timezone(strtotime($item->timestamp), true, false),
									"updated_value"		=> ($item->update_note == '') ? NULL : $item->update_note,
									"updated_ischeck"	=> ($item->update_check == 99) ? NULL : $item->update_check,
									"updated_timestamp" => ($item->update_timestamp == '') ? NULL : convert_timezone(strtotime($item->update_timestamp), true, false),
									"final_update_value" => ($item->final_update_note == '') ? NULL : $item->final_update_note,
									"final_update_ischeck" => ($item->final_update_check == 99) ? NULL : $item->final_update_check,
									"final_update_timestamp" => ($item->final_update_timestamp  == '') ? NULL : convert_timezone(strtotime($item->final_update_timestamp), true, false)
								);

								$this->db->insert("report_checklist" , $item_batch);
								$report_checklist_id = $this->db->insert_id();

								if(!empty($item->images)){
									$this->offline_save_image($report_id , $report_checklist_id , $item->images);
								}

								if(isset($item->update_images)){
									if(!empty($item->update_images)){
										$this->save_update_image($report_id , $report_checklist_id , $item->update_images);
									}							
								}
								if(isset($item->final_update_images)){
									if(!empty($item->final_update_images)){
										$this->save_final_image($report_id , $report_checklist_id , $item->final_update_images);
									}							
								}

								if($item->checkbox == 1){
									$defect++;
								}
								if($item->checkbox == 2){
									if($item->update_check == 1){
										$defect++;
										if($item->final_update_check == 0){
											$fixed++;
										}
									}
									if($item->update_check == 0){
										if($item->final_update_check == 1){
											$defect++;
										}
									}
								}

								if($item->update_check == 0){
									$fixed++;
									if($item->final_update_check == 1){
										$defect++;
									}
								}

								if($item->update_check == 1){
									$defect++;
									if($item->final_update_check == 0){
										$fixed++;
									}
								}

								if($item->checkbox == 0 && $item->update_check == 2){
									if($item->final_update_check == 1){
										$defect++;
									}
								}								
							}
							
							if($defect != 0){
								if($defect > $fixed){
									$finalstat = 1;
								}else{
									$finalstat = 2;
								}
							}else{
								$finalstat = 0;
							}
							//Create status
							$this->db->insert("report_status" , [
								"report_id"			=> $report_id ,
								"status"			=> $finalstat,
								"notes"				=> $value->report_notes ,
								"user_id"			=> $value->user_id ,
								"created"			=> strtotime(convert_timezone(strtotime($value->created), true, true)),
								"start_longitude"	=> $value->start_longitude,
								"start_latitude"	=> $value->start_latitude,
								"longitude"			=> $value->longitude,
								"latitude"			=> $value->latitude,
								"signature"			=> ($signature_path == '')? NULL : $signature_path
							]);
							$status_id = $this->db->insert_id();

							// $this->db->where("report_id" , $report_id)->update("report" , [
							// 	"status_id"			=> $status_id
							// ]);

							$this->db->where("report_id" , $report_id)->update("report" , [
								"report_number"		=> $report_number,
								"status_id"			=> $status_id ,
							]);
							

							// $this->db->where("vehicle_registration_number",$value->vehicle_registration_number);
							// $this->db->update("vehicle",[
							// 	"last_checked" => $value->last_checked,
							// 	"status" => 2
							// ]);

							$this->db->insert("vehicles_used",[
								"user_id" => $value->user_id,
								"vehicle_registration_number" => $value->vehicle_registration_number,
								"trailer_number" => ($value->trailer_number != '') ? $value->trailer_number : NULL,
								"vehicle_type" => $value->vehicle_type_id,
								"date_used" => strtotime(convert_timezone(strtotime($value->created), true, true))
							]);

							if($value->trailer_number == ''){
								$this->db->select("id");
								$this->db->where("checklist_id", $value->checklist_id);
								$this->db->where("item_position >=", 29);
								$this->db->where("deleted IS NULL");
								$c_items = $this->db->get("checklist_items")->result();

								foreach ($c_items as $c => $c_v) {
									$this->db->insert("report_checklist",[
										"report_id"				=> $report_id ,
										"checklist_item_id"		=> $c_v->id ,
										"checklist_value"		=> NULL,
										"checklist_ischeck"		=> 3
									]);
								}
							}
							
							$this->db->trans_complete();

							if ($this->db->trans_status() === FALSE){

					            echo json_encode(["status" => false , "message" => "Something went wrong","action" => "offline_save_checklist"]);

					        }else{
					            $pdf = $this->pdf($report_id);

					            $this->db->where("report_id" , $report_id)->update("report" , [
									"pdf_path"			=> $pdf['path'],
									"pdf_file" 			=> $pdf['filename']
								]);
					        }
						}
						echo json_encode(["status" => true , "message" => "Successfully Submitted Offline Reports","action" => "offline_save_checklist"]);	
					}else{
						echo json_encode(["status" => false , "message" => "Data is empty","action" => "offline_save_checklist"]);
					}     
				}else{

					echo json_encode(["status" => false , "message" => $this->codeToMessage($_FILES['data']['error']),"action" => "offline_save_checklist"]);
				}
				
			}else{
				echo json_encode(["status" => false , "message" => "No file","action" => "offline_save_checklist"]);
			}
			

			
			
			
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "offline_save_checklist"]);
		}
	}

	
	// private function finalStatus(){
	// 	$data = (object)$this->input->post();
	// 	$checklist = json_decode($data->checklist);
	// 	$_isDefect = false;

	// 	foreach($checklist as $row){
	// 		if($row->checkbox == 1){
	// 			$_isDefect = true;
	// 		}
	// 		if($row->update_check == 1){
	// 			$_isDefect = true;
	// 		}
	// 		if($row->final_update_check == 1){
	// 			$_isDefect = true;
	// 		}
	// 	}

	// 	return $_isDefect;
	// }

	private function save_update_image($report_id , $report_checklist_id , $images){

		$allowed = validate_app_token($this->post->token);
		if($allowed){

			$ctr = 1;
			foreach($images as $img){

				$name = $report_id.'_'.$report_checklist_id.'_'.$ctr.'_'.time().'.PNG';
		        $year = date("Y");
		        $month = date("m");
		        
		        $folder = "./public/upload/report/update/".$year."/".$month;
		        
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
			    
			    $this->db->insert('report_update_images', [
			    	"report_id"				=> $report_id ,
			    	"report_checklist_id"	=> $report_checklist_id ,
			    	"image_path"			=> $year."/".$month.'/' ,
			    	"image_name"			=> $name
			    ]);

			    $ctr++;
			}	
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "save_image"]);
		}	
	}

	private function save_final_image($report_id , $report_checklist_id , $images){

		$allowed = validate_app_token($this->post->token);
		if($allowed){

			$ctr = 1;
			foreach($images as $img){

				$name = $report_id.'_'.$report_checklist_id.'_'.$ctr.'_'.time().'.PNG';
		        $year = date("Y");
		        $month = date("m");
		        
		        $folder = "./public/upload/report/finalupdate/".$year."/".$month;
		        
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
			    
			    $this->db->insert('report_final_images', [
			    	"report_id"				=> $report_id ,
			    	"report_checklist_id"	=> $report_checklist_id ,
			    	"image_path"			=> $year."/".$month.'/' ,
			    	"image_name"			=> $name
			    ]);

			    $ctr++;
			}	
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "save_image"]);
		}	
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

		//$pdf = $this->config->site_url($pdf);

		return $pdf;
	}
	private function offline_save_image($report_id , $report_checklist_id , $images){
		
		$allowed = validate_app_token($this->post->token);
		if($allowed){
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
		}else{
	    	echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "save_image"]);
	    }
	}

	private function offline_save_signature($report_number, $base64){

		$allowed = validate_app_token($this->post->token);
		if($allowed){

			$image = $base64;
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
	    }else{
	    	echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "save_signature"]);
	    }
	}

	private function save_signature($report_number){

		$allowed = validate_app_token($this->post->token);
		if($allowed){
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
	    }else{
	    	echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "save_signature"]);
	    }
	}

	private function save_image($report_id , $report_checklist_id , $images){
		
		$allowed = validate_app_token($this->post->token);
		if($allowed){
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
		}else{
	    	echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "save_image"]);
	    }
	}
}
