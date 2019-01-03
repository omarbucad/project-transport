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
        $this->load->model('report_model', 'reports');        
		//$this->post = json_decode(file_get_contents("php://input"));
		$this->post = (object) $this->input->post();
	}

	public function get_report($mechanic = false){

		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$report_by = $this->post->user_id;
			$store_id = $this->post->store_id;
			$plan = $this->post->plan;

			$today = convert_timezone(time());
	        $start   = strtotime(trim($today.' 00:00 -6 days'));	        
	        $end = strtotime(trim($today.' 23:59'));

			$this->db->select("r.report_id , r.report_number , r.vehicle_registration_number , r.trailer_number, r.start_mileage , r.end_mileage , r.report_notes  , r.created, r.pdf_path, r.pdf_file,  c.vehicle_type_id ,vt.type, s.store_name, s.logo_image_name, s.logo_image_path");
			$this->db->select("u.display_name , u2.display_name as updated_by");
			$this->db->select("rs.status , rs.notes as status_notes , rs.signature");
			$this->db->select("c.checklist_name");
			$this->db->join("user u" , "u.user_id = r.report_by");
			$this->db->join("report_status rs" , "rs.id = r.status_id");
			$this->db->join("user u2" , "rs.user_id = u2.user_id");
			$this->db->join("checklist c" , "c.checklist_id = r.checklist_id");
			$this->db->join("vehicle_type vt","vt.vehicle_type_id = c.vehicle_type_id");
			$this->db->join("store s","s.store_id = u.store_id");
			$this->db->where("r.end_mileage !=", NULL);
			$this->db->where("r.report_by" , $report_by);
			$this->db->where("u.store_id" , $store_id);


			$result = $this->db->order_by("r.report_id" , "DESC")->get("report r")->result();

			if($result){
				foreach($result as $key => $row){

					if($plan == 'Free'){
						if($result[$key]->created <= $end && $result[$key]->created >= $start){
							$result[$key]->displayed = true;
						}else{
							$result[$key]->displayed = false;
						}
					}else{
						$result[$key]->displayed = true;
					}					

					if($result[$key]->logo_image_path == 'public/img/'){
		                $result[$key]->company_logo = $this->config->site_url($result[$key]->logo_image_path.$result[$key]->logo_image_name);
		            }else{
		                $result[$key]->company_logo = $this->config->site_url("public/upload/company/".$result[$key]->logo_image_path.$result[$key]->logo_image_name);
		            }
					
					$result[$key]->created   =  convert_timezone($result[$key]->created,true,false);

					//$result[$key]->created = convert_timezone($row->created,true, true);
					//$result[$key]->convert_timezone($r->created , true);
					$result[$key]->status_raw = report_type($row->status , true);
					$result[$key]->status = report_type($row->status);

					$this->db->select("rc.checklist_ischeck , rc.checklist_value , rc.timestamp, rc.updated_ischeck, rc.updated_value,rc.updated_timestamp,rc.final_update_ischeck, rc.final_update_value, rc.final_update_timestamp, ci.item_name, ci.image_path as item_path, ci.image_name as item_img_name, rc.id");
					$this->db->join("checklist_items ci" , "ci.id = rc.checklist_item_id");
					$result[$key]->checklist = $this->db->where("report_id" , $row->report_id)->order_by("ci.item_position" , "ASC")->get("report_checklist rc")->result();

					foreach($result[$key]->checklist as $k => $r){

						$result[$key]->checklist[$k]->item_image = $this->config->site_url("public/upload/checklist/".$r->item_path."/".$r->item_img_name);
						
						$images = $this->db->where("report_id" , $row->report_id)->where("report_checklist_id" , $r->id)->get("report_images")->result();

						foreach($images as $ki => $ro){
							// $images[$ki]->thumbnail = $this->config->site_url("public/upload/report/".$ro->image_path.$ro->image_name);
							$images[$ki]->image = $this->config->site_url("public/upload/report/".$ro->image_path.$ro->image_name);
						}

						$result[$key]->checklist[$k]->images = $images;

						if($r->updated_ischeck != ''){
							$updated_img = $this->db->where("report_id" , $row->report_id)->where("report_checklist_id" , $r->id)->get("report_update_images")->result();
							if($updated_img){
								foreach($updated_img as $ui => $u){
									
									// $updated_img[$ui]->thumbnail = $this->config->site_url("public/upload/report/update/".$u->image_path.$u->image_name);
									$updated_img[$ui]->image = $this->config->site_url("public/upload/report/update/".$u->image_path.$u->image_name);
								}
								$result[$key]->checklist[$k]->update_images = $updated_img;
							}else{
								$result[$key]->checklist[$k]->update_images = [];
							}
						}

						if($r->final_update_ischeck != ''){
							$final_img = $this->db->where("report_id" , $row->report_id)->where("report_checklist_id" , $r->id)->get("report_final_images")->result();
							if($final_img){
								foreach($final_img as $ui => $u){
									
									// $updated_img[$ui]->thumbnail = $this->config->site_url("public/upload/report/update/".$u->image_path.$u->image_name);
									$final_img[$ui]->image = $this->config->site_url("public/upload/report/finalupdate/".$u->image_path.$u->image_name);
								}
								$result[$key]->checklist[$k]->final_images = $final_img;
							}else{
								$result[$key]->checklist[$k]->final_images = [];
							}
						}
					}

					$this->db->select("rs.status , rs.notes  , u.display_name , u.role, rs.created , rs.start_longitude , rs.start_latitude ,rs.longitude , rs.latitude , rs.signature");
					$this->db->join("user u" , "u.user_id = rs.user_id");
					$status = $this->db->where("rs.report_id" , $row->report_id)->order_by("rs.created" , "DESC")->get("report_status rs")->result();
					
					foreach($status as $k => $r){
						$status[$k]->status = report_type($r->status );
						$status[$k]->created   = convert_timezone($r->created , true);
						$status[$k]->signature = $this->config->site_url("public/upload/signature/".$r->signature);
					}

					$result[$key]->status_list = $status;
					$result[$key]->signature = $status[0]->signature;
				}
				echo json_encode(["status" => true, "data" => $result, "action" => "get_report"]);
			}else{
				echo json_encode(["status" => false, "message" => "No data available", "action" => "get_report"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "get_report"]);
		}
	}

	// public function get_report_by_vehicle(){
	// 	$store_id = $this->post->store_id;
	// 	$registration = $this->post->vehicle_registration_number;

	// 	$this->db->select("pdf_path,pdf_file");
	// 	$this->db->where("store_id",$store_id);
	// 	$this->db->where("vehicle_registration_number",$registration);
		
	// }
	// public function get_report_by_trailer(){
	// 	$store_id = $this->post->store_id;
	// 	$registration = $this->post->vehicle_registration_number;

	// 	$this->db->select("pdf_path,pdf_file");
	// 	$this->db->where("store_id",$store_id);
	// 	$this->db->where("vehicle_registration_number",$registration);
	// 	$this->db->where("created >=", strtotime("today midnight"));
 //        $this->db->where("created <=", strtotime("tomorrow midnight -1 second"))->get("report");
		
	// }

	public function vehicle_reports(){

		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$store_id = $this->post->store_id;
			$vehicle_registration_number = $this->post->vehicle_registration_number;

			// $today = convert_timezone(time());
	  //       $start = strtotime(trim($today.' 00:00'));
	  //       $end   = strtotime(trim($today.' 23:59'));

			$this->db->select("r.report_id , r.report_number , r.vehicle_registration_number , r.start_mileage , r.end_mileage , r.report_notes  , r.created, r.pdf_path, r.pdf_file,  c.vehicle_type_id ,vt.type, s.store_name, s.logo_image_path, s.logo_image_name");
			$this->db->select("u.display_name , u2.display_name as updated_by");
			$this->db->select("rs.status , rs.notes as status_notes , rs.signature");
			$this->db->select("c.checklist_name");
			$this->db->join("user u" , "u.user_id = r.report_by");
			$this->db->join("report_status rs" , "rs.id = r.status_id");
			$this->db->join("user u2" , "rs.user_id = u2.user_id");
			$this->db->join("checklist c" , "c.checklist_id = r.checklist_id");
			$this->db->join("vehicle_type vt","vt.vehicle_type_id = c.vehicle_type_id");
			$this->db->join("store s","s.store_id = u.store_id");

			$this->db->where("r.vehicle_registration_number",$vehicle_registration_number);
			// $this->db->where("r.created >=",$start);
			// $this->db->where("r.created <=",$end);
			//$this->db->where("r.end_mileage !=", NULL);

			$this->db->where("u.store_id" , $store_id);
			$result = $this->db->order_by("rs.created" , "DESC")->get("report r")->result();
			if($result){
				foreach($result as $key => $row){

					if($result[$key]->logo_image_path == 'public/img/'){
		                $result[$key]->company_logo = $this->config->site_url($result[$key]->logo_image_path.$result[$key]->logo_image_name);
		            }else{
		                $result[$key]->company_logo = $this->config->site_url("public/upload/company/".$result[$key]->logo_image_path.$result[$key]->logo_image_name);
		            }

					$result[$key]->created   = convert_timezone($row->created , true , false);
					$result[$key]->status_raw = report_type($row->status , true);
					$result[$key]->status = report_type($row->status);			

					$this->db->select("rc.checklist_ischeck , rc.checklist_value ,rc.timestamp, rc.updated_ischeck, rc.updated_value,rc.updated_timestamp,rc.final_update_ischeck, rc.final_update_value, rc.final_update_timestamp, ci.item_name , rc.id");
					$this->db->join("checklist_items ci" , "ci.id = rc.checklist_item_id");
					$result[$key]->checklist = $this->db->where("report_id" , $row->report_id)->order_by("ci.item_position" , "ASC")->get("report_checklist rc")->result();

					foreach($result[$key]->checklist as $k => $r){
						
						$images = $this->db->where("report_id" , $row->report_id)->where("report_checklist_id" , $r->id)->get("report_images")->result();

						foreach($images as $ki => $ro){
							// $images[$ki]->thumbnail = $this->config->site_url("public/upload/report/".$ro->image_path.$ro->image_name);
							$images[$ki]->image = $this->config->site_url("public/upload/report/".$ro->image_path.$ro->image_name);
						}

						$result[$key]->checklist[$k]->images = $images;

						if($r->updated_ischeck != ''){
							$updated_img = $this->db->where("report_id" , $row->report_id)->where("report_checklist_id" , $r->id)->get("report_update_images")->result();
							if($updated_img){
								foreach($updated_img as $ui => $u){
									
									// $updated_img[$ui]->thumbnail = $this->config->site_url("public/upload/report/update/".$u->image_path.$u->image_name);
									$updated_img[$ui]->image = $this->config->site_url("public/upload/report/update/".$u->image_path.$u->image_name);
								}
								$result[$key]->checklist[$k]->update_images = $updated_img;
							}else{
								$result[$key]->checklist[$k]->update_images = [];
							}
						}
						if($r->final_update_ischeck != ''){
							$final_img = $this->db->where("report_id" , $row->report_id)->where("report_checklist_id" , $r->id)->get("report_final_images")->result();
							if($final_img){
								foreach($final_img as $ui => $u){
									
									// $updated_img[$ui]->thumbnail = $this->config->site_url("public/upload/report/update/".$u->image_path.$u->image_name);
									$final_img[$ui]->image = $this->config->site_url("public/upload/report/finalupdate/".$u->image_path.$u->image_name);
								}
								$result[$key]->checklist[$k]->final_images = $final_img;
							}else{
								$result[$key]->checklist[$k]->final_images = [];
							}
						}
					}

					$this->db->select("rs.status , rs.notes  , u.display_name , u.role, rs.created , rs.start_longitude , rs.start_latitude ,rs.longitude , rs.latitude , rs.signature");
					$this->db->join("user u" , "u.user_id = rs.user_id");
					$status = $this->db->where("rs.report_id" , $row->report_id)->order_by("rs.created" , "DESC")->get("report_status rs")->result();

					foreach($status as $k => $r){
						$status[$k]->status = report_type($r->status );
						$status[$k]->created   = convert_timezone($r->created , true );
						$status[$k]->signature = $this->config->site_url("public/upload/signature/".$r->signature);
					}
					$result[$key]->signature = $status[0]->signature;
					$result[$key]->status_list = $status;
				}
				echo json_encode(["status" => true, "data" => $result, "action" => "vehicle_reports"]);
			}else{
				echo json_encode(["status" => false, "message" => "No data available", "action" => "vehicle_reports"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "vehicle_reports"]);
		}		
	}

	public function pdf($report_id){

		//$report_id = $this->hash->decrypt($report_id);
		$report = $this->reports->get_report_by_id($report_id);

		$pdf = $this->pdf->create_report_checklist($report , "F");

		//$pdf = $this->config->site_url($pdf['file']);

		//echo json_encode($pdf);
		return $pdf;
	}

	public function fix_report(){

		$allowed = validate_app_token($this->post->token);
		if($allowed){
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

				$pdf = $this->pdf($this->post->report);	

				$this->db->where("report_id" , $this->post->report)->update('report' , [
					"status_id"		=> $status_id,
					"pdf_path"			=> $pdf['path'],
					"pdf_file" 			=> $pdf['filename']
				]);

				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE){

		           echo json_encode(["status" => false , "message" => "Submit Failed", "action" => "fix_report"]);

		        }else{
		           
		           echo json_encode(["status" => true , "message" => "Successfully Submitted", "action" => "fix_report"]);
		        }
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "fix_report"]);
		}	
	}

	public function delete_pdf(){
		if($this->post){
			echo json_encode(["status" => true]);
			unlink($this->post->file);
		}
	}

	// private function save_signature($report_number){
	// 	$image = $this->post->signature;

	// 	$name = $report_number.'_'.time().'.PNG';
 //        $year = date("Y");
 //        $month = date("m");
        
 //        $folder = "./public/upload/signature/".$year."/".$month;
        
 //        $date = time();

 //        if (!file_exists($folder)) {
 //            mkdir($folder, 0777, true);
 //            create_index_html($folder);
 //        }

 //        $path = $folder.'/'.$name;

 //        $encoded = $image;

	//     //explode at ',' - the last part should be the encoded image now
	//     $exp = explode(',', $encoded);

	//     //we just get the last element with array_pop
	//     $base64 = array_pop($exp);

	//     //decode the image and finally save it
	//     $data = base64_decode($base64);


	//     //make sure you are the owner and have the rights to write content
	//     file_put_contents($path, $data);

 //        return $year."/".$month.'/'.$name;
	// }

	public function allreports(){

		$allowed = validate_app_token($this->post->token);
		if($allowed){

			$store_id = $this->post->store_id;

			$this->db->select("r.report_id , r.report_number , r.vehicle_registration_number , r.start_mileage , r.end_mileage , r.report_notes  , r.created, r.pdf_path, r.pdf_file,  c.vehicle_type_id ,vt.type, s.store_name,s.logo_image_name, s.logo_image_path");
			$this->db->select("u.display_name , u2.display_name as updated_by");
			$this->db->select("rs.status , rs.notes as status_notes , rs.signature");
			$this->db->select("c.checklist_name");
			$this->db->join("user u" , "u.user_id = r.report_by");
			$this->db->join("report_status rs" , "rs.id = r.status_id");
			$this->db->join("user u2" , "rs.user_id = u2.user_id");
			$this->db->join("checklist c" , "c.checklist_id = r.checklist_id");
			$this->db->join("vehicle_type vt","vt.vehicle_type_id = c.vehicle_type_id");
			$this->db->join("store s","s.store_id = u.store_id");
			$this->db->where("r.end_mileage !=", NULL);
			$this->db->where("u.store_id" , $store_id);

			$result = $this->db->order_by("rs.created" , "DESC")->get("report r")->result();
			if($result){
				foreach($result as $key => $row){
					if($result[$key]->logo_image_path == 'public/img/'){
		                $result[$key]->company_logo = $this->config->site_url($result[$key]->logo_image_path.$result[$key]->logo_image_name);
		            }else{
		                $result[$key]->company_logo = $this->config->site_url("public/upload/company/".$result[$key]->logo_image_path.$result[$key]->logo_image_name);
		            }

					$result[$key]->created   = convert_timezone($row->created , true ,false);
					$result[$key]->status_raw = report_type($row->status , true);
					$result[$key]->status = report_type($row->status);

					$this->db->select("rc.checklist_ischeck , rc.checklist_value ,rc.timestamp, rc.updated_ischeck, rc.updated_value,rc.updated_timestamp,rc.final_update_ischeck, rc.final_update_value, rc.final_update_timestamp, ci.item_name , ci.image_path as item_path, ci.image_name as item_img_name, rc.id");
					$this->db->join("checklist_items ci" , "ci.id = rc.checklist_item_id");
					$result[$key]->checklist = $this->db->where("report_id" , $row->report_id)->order_by("ci.item_position" , "ASC")->get("report_checklist rc")->result();

					foreach($result[$key]->checklist as $k => $r){
						$result[$key]->checklist[$k]->item_image = $this->config->site_url("public/upload/checklist/".$r->item_path."/".$r->item_img_name);
						
						$images = $this->db->where("report_id" , $row->report_id)->where("report_checklist_id" , $r->id)->get("report_images")->result();

						foreach($images as $ki => $ro){
							// $images[$ki]->thumbnail = $this->config->site_url("public/upload/report/".$ro->image_path.$ro->image_name);
							$images[$ki]->image = $this->config->site_url("public/upload/report/".$ro->image_path.$ro->image_name);
						}

						$result[$key]->checklist[$k]->images = $images;

						if($r->updated_ischeck != ''){
							$updated_img = $this->db->where("report_id" , $row->report_id)->where("report_checklist_id" , $r->id)->get("report_update_images")->result();
							if($updated_img){
								foreach($updated_img as $ui => $u){
									
									// $updated_img[$ui]->thumbnail = $this->config->site_url("public/upload/report/update/".$u->image_path.$u->image_name);
									$updated_img[$ui]->image = $this->config->site_url("public/upload/report/update/".$u->image_path.$u->image_name);
								}
								$result[$key]->checklist[$k]->update_images = $updated_img;
							}else{
								$result[$key]->checklist[$k]->update_images = [];
							}
						}
						if($r->final_update_ischeck != ''){
							$final_img = $this->db->where("report_id" , $row->report_id)->where("report_checklist_id" , $r->id)->get("report_final_images")->result();
							if($final_img){
								foreach($final_img as $ui => $u){
									
									// $updated_img[$ui]->thumbnail = $this->config->site_url("public/upload/report/update/".$u->image_path.$u->image_name);
									$final_img[$ui]->image = $this->config->site_url("public/upload/report/finalupdate/".$u->image_path.$u->image_name);
								}
								$result[$key]->checklist[$k]->final_images = $final_img;
							}else{
								$result[$key]->checklist[$k]->final_images = [];
							}
						}						
					}

					$this->db->select("rs.status , rs.notes  , u.display_name , u.role, rs.created , rs.start_longitude , rs.start_latitude ,rs.longitude , rs.latitude , rs.signature");
					$this->db->join("user u" , "u.user_id = rs.user_id");
					$status = $this->db->where("rs.report_id" , $row->report_id)->order_by("rs.created" , "DESC")->get("report_status rs")->result();

					foreach($status as $k => $r){
						$status[$k]->status = report_type($r->status );
						$status[$k]->created   = convert_timezone($r->created , true );
						$status[$k]->signature = $this->config->site_url("public/upload/signature/".$r->signature);
					}
					$result[$key]->signature = $status[0]->signature;
					$result[$key]->status_list = $status;
				}

				echo json_encode(["status" => true, "data" => $result, "action" => "allreports"]);
			}else{
				echo json_encode(["status" => false, "message" => "No data available", "action" => "allreports"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "allreports"]);
		}
	}

	public function done_checklist(){

		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$data = $this->post;

			$vehicle_registration_number = $data->vehicle_registration_number;
			$user_id = $data->user_id;
			$checklist_id = $data->checklist_id;
			$today = date("M d Y", time());

			$this->db->where("vehicle_registration_number", $vehicle_registration_number);
			$this->db->where("checklist_id", $checklist_id);
			$this->db->where("created >=",strtotime($today . " 00:00"));
			$this->db->where("created <=",strtotime($today . " 11:59"));

			$result = $this->db->order_by("created","DESC")->limit(1)->get("report")->row();

			if($result){
				
				if($value->report_by == $user_id){
					echo json_encode(["status" => false , "message" => "Consecutive Checklist Report is not allowed", "action" => "done_checklist"]);	
				}else{
					echo json_encode(["status" => true , "message" => "Allowed to Checklist", "action" => "done_checklist"]);	
				}
				
				
			}else{
				echo json_encode(["status" => true , "message" => "Allowed to Checklist", "action" => "done_checklist"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "done_checklist"]);
		}
	}

	public function check_end_mileage(){

		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$data = $this->post;
			if($data){
				$this->db->select("r.report_id , r.report_number , r.vehicle_registration_number , r.trailer_number, r.start_mileage , r.end_mileage , r.report_notes  , r.created, r.pdf_path, r.pdf_file,  c.vehicle_type_id ,vt.type, s.store_name,s.logo_image_name, s.logo_image_path");
				$this->db->select("u.display_name , u2.display_name as updated_by");
				$this->db->select("rs.status , rs.notes as status_notes , rs.signature");
				$this->db->select("c.checklist_name");
				$this->db->join("user u" , "u.user_id = r.report_by");
				$this->db->join("report_status rs" , "rs.id = r.status_id");
				$this->db->join("user u2" , "rs.user_id = u2.user_id");
				$this->db->join("checklist c" , "c.checklist_id = r.checklist_id");
				$this->db->join("vehicle_type vt","vt.vehicle_type_id = c.vehicle_type_id");
				$this->db->join("store s","s.store_id = u.store_id");

				$this->db->where("r.report_by",$data->user_id);
				$this->db->where("r.end_mileage IS NULL");

				$result = $this->db->order_by("created","DESC")->limit(1)->get("report r")->row();

			
				if($result){

					if($result->logo_image_path == 'public/img/'){
		                $result->company_logo = $this->config->site_url($result->logo_image_path.$result->logo_image_name);
		            }else{
		                $result->company_logo = $this->config->site_url("public/upload/company/".$result->logo_image_path.$result->logo_image_name);
		            }
					
					$result->created   = convert_timezone($result->created , true ,false);
					$result->status_raw = report_type($result->status , true);
					$result->status = report_type($result->status);

					$this->db->select("rc.checklist_ischeck , rc.checklist_value , rc.timestamp,rc.updated_ischeck, rc.updated_value,rc.updated_timestamp,rc.final_update_ischeck, rc.final_update_value, rc.final_update_timestamp, ci.item_name ,ci.help_text, ci.image_path, ci.image_name, ci.help_image_path, ci.help_image_name, rc.id");
					$this->db->join("checklist_items ci" , "ci.id = rc.checklist_item_id");
					$this->db->where("ci.deleted IS NULL");

					$result->checklist = $this->db->where("report_id" , $result->report_id)->order_by("ci.item_position" , "ASC")->get("report_checklist rc")->result();

					foreach($result->checklist as $k => $r){
						if($r->image_name != ''){
							$result->checklist[$k]->item_image = $this->config->site_url("thumbs/images/checklist/".$r->image_path."/250/250/".$r->image_name);
						}

						if($r->help_image_name != ''){
							$result->checklist[$k]->help_image = $this->config->site_url("thumbs/images/checklist/".$r->help_image_path."/250/250/".$r->help_image_name);
						}

						$images = $this->db->where("report_id" , $result->report_id)->where("report_checklist_id" , $r->id)->get("report_images")->result();

						foreach($images as $ki => $ro){
							// $images[$ki]->thumbnail = $this->config->site_url("public/upload/report/".$ro->image_path.$ro->image_name);
							$images[$ki]->image = $this->config->site_url("public/upload/report/".$ro->image_path.$ro->image_name);
						}

						$result->checklist[$k]->images = $images;

						if($r->updated_ischeck != ''){
							$updated_img = $this->db->where("report_id" , $result->report_id)->where("report_checklist_id" , $r->id)->get("report_update_images")->result();
							if($updated_img){
								foreach($updated_img as $ui => $u){
									
									// $updated_img[$ui]->thumbnail = $this->config->site_url("public/upload/report/update/".$u->image_path.$u->image_name);
									$updated_img[$ui]->image = $this->config->site_url("public/upload/report/update/".$u->image_path.$u->image_name);
								}
								$result->checklist[$k]->update_images = $updated_img;
							}else{
								$result->checklist[$k]->update_images = [];
							}
						}

						if($r->final_update_ischeck != ''){
							$final_img = $this->db->where("report_id" , $result->report_id)->where("report_checklist_id" , $r->id)->get("report_final_images")->result();
							if($final_img){
								foreach($final_img as $ui => $u){
									
									// $updated_img[$ui]->thumbnail = $this->config->site_url("public/upload/report/update/".$u->image_path.$u->image_name);
									$final_img[$ui]->image = $this->config->site_url("public/upload/report/finalupdate/".$u->image_path.$u->image_name);
								}
								$result->checklist[$k]->final_images = $final_img;
							}else{
								$result->checklist[$k]->final_images = [];
							}
						}
					}

					$this->db->select("rs.status, rs.id as report_status_id, rs.notes  , u.display_name , u.role, rs.created , rs.start_longitude , rs.start_latitude ,rs.longitude , rs.latitude , rs.signature");
					$this->db->join("user u" , "u.user_id = rs.user_id");
					$status = $this->db->where("rs.report_id" , $result->report_id)->order_by("rs.created" , "DESC")->get("report_status rs")->result();
					foreach($status as $k => $r){
						$status[$k]->status = report_type($r->status );
						$status[$k]->created   = convert_timezone($r->created , true );
						if($status[$k]->signature != ''){
							$status[$k]->signature = $this->config->site_url("public/upload/signature/".$r->signature);
						}
					}
					$result->signature = ($status[0]->signature == '') ? '' : $status[0]->signature;
					$result->status_list = $status;

					echo json_encode(["status" => true , "message" => "Incomplete Report", "data" => $result, "action" => "check_end_mileage"]);				
				}else{
					echo json_encode(["status" => false , "message" => "No incomplete report", "action" => "check_end_mileage"]);	
				}
			}else{
				echo json_encode(["status" => false , "message" => "No passed report id", "action" => "check_end_mileage"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "check_end_mileage"]);
		}
	}


	// private function isDefect(){
	// 	$data = (object)$this->input->post();
	// 	$checklist = json_decode($data->items);
	// 	$_isDefect = false;

	// 	foreach($checklist as $row){
	// 		if($row->checkbox == 1){
	// 			$_isDefect = true;
	// 		}
	// 	}

	// 	return $_isDefect;
	// }

	public function update_report(){

		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$data = $this->post;
			if($data){
				$this->db->trans_start();

				if(isset($data->end_mileage)){
					$this->db->where("report_id",$data->report_id);
					$this->db->update("report",[
						"end_mileage" => $data->end_mileage,
						"report_notes" => ($data->report_notes == '') ? NULL : $data->report_notes,
					]);	
					$signature_path = $this->save_signature($data->report_number);

					$this->db->where("id",$data->status_id);
					$this->db->update("report_status",[
						"longitude" => $data->longitude,
						"latitude" => $data->latitude,
						"signature" => $signature_path
					]);
				}

				if(isset($data->items)){

					$item = json_decode($data->items);
					
					$_isDefect = false;
					foreach($item as $key => $value){

						$this->db->where("id",$value->id);
						$this->db->where("report_id",$data->report_id);
						$this->db->update("report_checklist" , [
							"updated_value"		=> ($value->update_note == '') ? NULL : $value->update_note,
							"updated_ischeck"	=> $value->update_check,
							"updated_timestamp" => $value->updated_timestamp,
							"final_update_value" => ($value->final_update_value == '') ? NULL : $value->final_update_value,
							"final_update_ischeck" => ($value->final_update_check == '') ? NULL : $value->final_update_check,
							"final_update_timestamp" => ($value->final_update_timestamp  == '') ? NULL : $value->final_update_timestamp
						]);

						if(isset($value->update_images)){
							if(!empty($value->update_images)){
								$this->save_image($data->report_id , $value->id , $value->update_images);
							}							
						}
						if(isset($value->final_update_images)){
							if(!empty($value->final_update_images)){
								$this->save_final_image($data->report_id , $value->id , $value->final_update_images);
							}							
						}

						if($value->update_check == 1){
							$_isDefect = true;
						}

						if($value->final_update_check == 1){
							$_isDefect = true;
						}
					}

					$this->db->where("id",$data->status_id);
					$this->db->update("report_status",[
						"notes" => "Fixed",
						"status" => ($_isDefect) ? 1 : 2 
					]);

					$this->db->where("vehicle_registration_number",$data->vehicle_registration_number);
					$this->db->update("vehicle",[
						"last_checked" => time(),
						"status" => 2
					]);
				}

				$pdf = $this->pdf($data->report_id);

	            $this->db->where("report_id" , $data->report_id)->update("report" , [
					"pdf_path"			=> $pdf['path'],
					"pdf_file" 			=> $pdf['filename']
				]);
				
				$this->db->trans_complete();


				if ($this->db->trans_status() === FALSE){

		            echo json_encode(["status" => false , "message" => "Something went wrong", "action" => "update_report"]);

		        }else{
		           
		            echo json_encode(["status" => true , "message" => "Updated successfully","action" => "update_report"]);
		        }
			}else{
				echo json_encode(["status" => false , "message" => "Data is empty", "action" => "update_report"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "update_report"]);
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

			$img_batch = array();
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

	private function pdf_multiple_reports(){

		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$data = $this->post;

			$reports = $this->allreports_daterange($data->store_id,$data->start,$data->end);
			$start = date("dMY",$data->start);
			$end = date("dMY",$data->end);

			$pdf = $this->pdf->create_multiple_report($reports ,"D", $start, $end);		
			if($pdf){
				echo json_encode(["status"=>true,"message"=>"PDF generated successfully.","action"=>"pdf_multiple_reports"]);
			}else{
				echo json_encode(["status"=>false,"message"=>"Something went wrong.","action"=>"pdf_multiple_reports"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "pdf_multiple_reports"]);
		}
	}

	public function allreports_daterange($store_id, $start, $end){	
		
		$allowed = validate_app_token($this->post->token);
		if($allowed){

			$this->db->select("r.report_id , r.report_number , r.vehicle_registration_number , r.start_mileage , r.end_mileage , r.report_notes  , r.created, r.pdf_path, r.pdf_file,  c.vehicle_type_id ,vt.type, s.store_name, s.logo_image_path, s.logo_image_name");
			$this->db->select("u.display_name , u2.display_name as updated_by");
			$this->db->select("rs.status , rs.notes as status_notes , rs.signature");
			$this->db->select("c.checklist_name");
			$this->db->join("user u" , "u.user_id = r.report_by");
			$this->db->join("report_status rs" , "rs.id = r.status_id");
			$this->db->join("user u2" , "rs.user_id = u2.user_id");
			$this->db->join("checklist c" , "c.checklist_id = r.checklist_id");
			$this->db->join("vehicle_type vt","vt.vehicle_type_id = c.vehicle_type_id");
			$this->db->join("store s","s.store_id = u.store_id");

			$this->db->where("r.end_mileage !=", NULL);
			$this->db->where("u.store_id" , $store_id);
			$this->db->where("r.created >=",$start);
			$this->db->where("r.created <=",$end);

			$result = $this->db->order_by("rs.created" , "DESC")->get("report r")->result();
			if($result){
				foreach($result as $key => $row){
					if($result[$key]->logo_image_path == 'public/img/'){
		                $result[$key]->company_logo = $this->config->site_url($result[$key]->logo_image_path.$result[$key]->logo_image_name);
		            }else{
		                $result[$key]->company_logo = $this->config->site_url("public/upload/company/".$result[$key]->logo_image_path.$result[$key]->logo_image_name);
		            }

					$result[$key]->created   = convert_timezone($row->created , true ,false);
					$result[$key]->status_raw = report_type($row->status , true);
					$result[$key]->status = report_type($row->status);

					$this->db->select("rc.checklist_ischeck , rc.checklist_value , rc.timestamp,rc.updated_ischeck, rc.updated_value,rc.updated_timestamp,rc.final_update_ischeck, rc.final_update_value, rc.final_update_timestamp, ci.item_name , rc.id");
					$this->db->join("checklist_items ci" , "ci.id = rc.checklist_item_id");
					$result[$key]->checklist = $this->db->where("report_id" , $row->report_id)->order_by("ci.item_position" , "ASC")->get("report_checklist rc")->result();

					foreach($result[$key]->checklist as $k => $r){
						$images = $this->db->where("report_id" , $row->report_id)->where("report_checklist_id" , $r->id)->get("report_images")->result();

						foreach($images as $ki => $ro){
							// $images[$ki]->thumbnail = $this->config->site_url("public/upload/report/".$ro->image_path.$ro->image_name);
							$images[$ki]->image = $this->config->site_url("public/upload/report/".$ro->image_path.$ro->image_name);
						}

						$result[$key]->checklist[$k]->images = $images;

						if($r->updated_ischeck != ''){
							$updated_img = $this->db->where("report_id" , $row->report_id)->where("report_checklist_id" , $r->id)->get("report_update_images")->result();
							if($updated_img){
								foreach($updated_img as $ui => $u){
									
									// $updated_img[$ui]->thumbnail = $this->config->site_url("public/upload/report/update/".$u->image_path.$u->image_name);
									$updated_img[$ui]->image = $this->config->site_url("public/upload/report/update/".$u->image_path.$u->image_name);
								}
								$result[$key]->checklist[$k]->update_images = $updated_img;
							}else{
								$result[$key]->checklist[$k]->update_images = [];
							}
						}			
						if($r->final_update_ischeck != ''){
							$final_img = $this->db->where("report_id" , $row->report_id)->where("report_checklist_id" , $r->id)->get("report_final_images")->result();
							if($final_img){
								foreach($final_img as $ui => $u){
									
									// $updated_img[$ui]->thumbnail = $this->config->site_url("public/upload/report/update/".$u->image_path.$u->image_name);
									$final_img[$ui]->image = $this->config->site_url("public/upload/report/finalupdate/".$u->image_path.$u->image_name);
								}
								$result[$key]->checklist[$k]->final_images = $final_img;
							}else{
								$result[$key]->checklist[$k]->final_images = [];
							}
						}
					}

					$this->db->select("rs.status , rs.notes  , u.display_name , u.role, rs.created , rs.start_longitude , rs.start_latitude ,rs.longitude , rs.latitude , rs.signature");
					$this->db->join("user u" , "u.user_id = rs.user_id");
					$status = $this->db->where("rs.report_id" , $row->report_id)->order_by("rs.created" , "DESC")->get("report_status rs")->result();

					foreach($status as $k => $r){
						$status[$k]->status = report_type($r->status );
						$status[$k]->created   = convert_timezone($r->created , true );
						$status[$k]->signature = $this->config->site_url("public/upload/signature/".$r->signature);
					}
					$result[$key]->signature = $status[0]->signature;
					$result[$key]->status_list = $status;
				}

				echo json_encode(["status" => true, "data" => $result, "action" => "allreports_daterange"]);
			}else{
				echo json_encode(["status" => false, "message" => "No data available", "action" => "allreports_daterange"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "allreports_daterange"]);
		}
	}
}
