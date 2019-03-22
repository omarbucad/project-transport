<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {

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
	public function register(){
		$data = $this->post;
		$exists = $this->db->select("username")->where("username",$data->username)->get("user")->num_rows();
		$emailexists = $this->db->select("email_address")->where("email_address",$data->email)->get("user")->num_rows();
		if($emailexists > 0){
			 echo json_encode(["status" => false , "message" => "Email already exists", "action" => "register", "err_code" => "2001"]);
			 return false;
		}
		if($exists > 0){
			 echo json_encode(["status" => false , "message" => "Username already exists", "action" => "register",  "err_code" => "2002"]);
			 return false;
		}else{
			$this->db->trans_start();

			$this->db->insert("store_address",[	"street1" => "Street"]);

			$address_id = $this->db->insert_id();


			$this->db->insert("store",[
				"store_name" => "Vehicle Checklist",
				"address_id" => $address_id,
				"created" => time()
			]);

			$store_id = $this->db->insert_id();

			$this->db->insert("user",[
				"display_name" => "Firstname Lastname",
				"firstname" => "Firstname",
				"lastname" => "Lastname",
				"email_address" => $data->email,
				"username" => $data->username,
				"role" => "DRIVER",
				"status" => 1,
				"created" => time(),
				"password" => md5($data->password),
				"phone" => ($data->phone == '') ? NULL : $data->phone,
				"store_id" => $store_id,
				"deleted" => NULL,
				"verified" => 0
			]);

			$userid = $this->db->insert_id();

			$this->db->insert("user_plan",[
				"store_id" => $store_id,
				"plan_id" => 'N/A',
				"subscription_id" => "N/A",
				"vehicle_limit" => 5,
				"plan_created" => time(),
				"plan_expiration" => 0,
				"billing_type" => "NA",
				"who_updated" => $userid,
				// "ip_address" => $data->ip_address,
				"active" => 1,
				"updated" => NULL,
				"payment_type" => NULL,
				"payment_name" => NULL
			]);

			if(isset($data->image)){
				$img = $this->save_profile_image($userid);
				$this->db->where("user_id",$userid);
				$this->db->update("user",[
					"image_path" => $img['image_path'],
					"image_name" => $img['image_name']
				]);
			}else{
				$this->db->where("user_id",$userid);
				$this->db->update("user",[
					"image_path" => 'public/img/' ,
					"image_name" => 'person-placeholder.jpg'
				]);
			}

			if(isset($data->logo)){
				$logo = $this->save_logo($store_id);
				$this->db->where("store_id",$store_id);
				$this->db->update("store",[
					"logo_image_name" => $logo['logo_image_name'],
					"logo_image_path" => $logo['logo_image_path']
				]);
			}else{
				$this->db->where("store_id",$store_id);
				$this->db->update("store",[
					"logo_image_path" => 'public/img/',
					"logo_image_name" => 'vehicle-checklist.png'
				]);
			}

			$this->login_trail($userid);

			$this->db->trans_complete();

	        if ($this->db->trans_status() === FALSE){
	            echo json_encode(["status" => false , "message" => "Failed", "action" => "register"]);
	        }else{
	        	$code = $this->generate_email_code($userid);
	        	switch ($code) {
	        		case 'sent':
	        			echo json_encode(["status" => true , "message" => "Successfully registered", "action" => "register"]);
	        			break;
	        		case 'failed':
	        			echo json_encode(["status" => false , "message" => "Successfully registered. Failed to send verification email.", "action" => "register"]);
	        			break;
	        		case 'not exist':
	        			echo json_encode(["status" => false , "message" => "User does not exist.", "action" => "register"]);
	        			break;
	        		case 'generate failed':
	        			echo json_encode(["status" => false , "message" => "Successfully registered. Failed to generate verification code", "action" => "register"]);
	        			break;
	        		case 'no data':
	        			echo json_encode(["status" => false , "message" => "Successfully registered. Failed to generate verification code", "action" => "register"]);
	        			break;
	        	}
	        	echo json_encode(["status" => true , "message" => "Successfully registered", "data" => $userid,"action" => "register"]);
	           
	        }
		}		
	}

	public function generate_email_code(){
		$user_id = $this->post->user_id;

		if($user_id){
			$generated = generate_email_code($user_id);

			if($generated){
				$info = $this->db->select("email_address")->where("user_id", $user_id)->get("user")->row();

				if(count($info) > 0){
					//$code = $this->hash->encrypt($email . "&time=".$time);
					// $data['code'] = $generated;
					// $data['app_icon'] = $this->config->site_url("public/img/vehicle-checklist.png");
					// $data['background'] = $this->config->site_url("public/img/reset-pass.jpg");
					// $data['email'] = $info->email_address;

					// $this->email->from('no-reply@trackerteer.com', 'Trackerteer | Vehicle Checklist');
					// $this->email->to($info->email_address);
					// $this->email->set_mailtype("html");
					// $this->email->subject('Your verification code');
					// $this->email->message($this->load->view('email/email_verification', $data , true));
					

					// if($this->email->send()){
						//return "sent";
						echo json_encode(["status" => true , "message" => "Email verification code sent successfully", "action" => "generate_email_code"]);
					// }else{
					// 	//return "failed";
					// 	echo json_encode(["status" => false , "message" => "Sending email failed", "action" => "generate_email_code"]);
					// }				
				}else{
					// return "not exist";
					echo json_encode(["status" => false , "message" => "User not found", "action" => "generate_email_code"]);
				}
			}else{
				//return "generate failed";
				echo json_encode(["status" => false , "message" => "Something went wrong. Please try again", "action" => "generate_email_code"]);
			}
		}else{
			//return "no data";
			echo json_encode(["status" => false , "message" => "No data received", "action" => "generate_email_code"]);
		}
	}

	public function verify_email(){
		$data = $this->post;

		if($data){

			// $verified = validate_email_code($data);
			$data->email = $this->db->select("email_address")->where("user_id", $data->user_id)->get("user")->row()->email_address;
			// switch ($verified) {
			// 	case 'expired':
			// 		echo json_encode(["status" => false , "message" => "Expired code", "action" => "verify_email"]);
			// 		break;

			// 	case 'valid':

					$this->db->where("user_id",$data->user_id);
					$update = $this->db->update("user",[
						"verified" => 1
					]);
					
					if($update){
						// $this->email->from('no-reply@trackerteer.com', 'Trackerteer | Vehicle Checklist');
						// $this->email->to($data->email);
						// $this->email->set_mailtype("html");
						// $this->email->subject('Email verification successful');
						// $this->email->message($this->load->view('email/verified_successfully', $data , true));

						// if($this->email->send()){
							echo json_encode(["status" => true , "message" => "Email has been verified", "action" => "verify_email"]);
						// }else{
						// 	echo json_encode(["status" => true , "message" => "Verified Successful. Email notification failed to send.", "action" => "verify_email"]);
			 		// 	}													
						
					}else{
						echo json_encode(["status" => false , "message" => "Something went wrong. Please try again", "action" => "verify_email"]);
					}		
					
					// break;

				// case 'invalid':
				// 	echo json_encode(["status" => false , "message" => "Invalid Code", "action" => "verify_email"]);
				// 	break;
			// }

		}else{
			echo json_encode(["status" => false , "message" => "No data received", "action" => "verify_email"]);
		}
	}

	public function get_user(){

		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$data = $this->post;		

			$this->db->trans_start();
			$this->db->where("user_id",$data->user_id);
			$this->db->where("store_id",$data->store_id);
			$this->db->where("deleted IS NULL");
			$result = $this->db->get("user")->row();
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE){
	            echo json_encode(["status" => false , "message" => "Failed", "action" => "get_user"]);
	        }else{

				if($result){
					if($result->image_path == "public/img/"){
					 	$result->full_path = $this->config->site_url($result->image_path.$result->image_name);
					}else{
						$result->full_path = $this->config->site_url("public/upload/user/".$result->image_path.$result->image_name);
					}
					echo json_encode(["status" => true , "data" => $result, "action" => "get_user"]);
				}else{
					echo json_encode(["status" => false , "message" => "No Data Available", "action" => "get_user"]);
				}            
	        }
	    }else{
	    	echo json_encode(["status" => true , "message" => "403: Access Forbidden", "action" => "get_user"]);
	    }
	}

	public function add(){

		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$data = $this->post;
				$exists = $this->db->select("username")->where("username",$data->username)->get("user")->num_rows();
				$emailexists = $this->db->select("email_address")->where("email_address",$data->email)->get("user")->num_rows();
				if($emailexists > 0){
					echo json_encode(["status" => false , "message" => "Email already exists", "action" => "add","err_code" => "2001"]);
					return false;
				}
				if($exists > 0){
					 echo json_encode(["status" => false , "message" => "Username already exists", "action" => "add", "err_code" => "2002"]);
					 return false;
				}else{

					$this->db->trans_start();

					$this->db->insert("user",[
						"display_name" => $data->firstname ." ". $data->lastname,
						"firstname" => $data->firstname,
						"lastname" => $data->lastname,
						"email_address" => $data->email,
						"username" => $data->username,
						"role" => $data->role,
						"status" => 1,
						"created" => time(),
						"password" => md5($data->password),
						"phone" => ($data->phone == '') ? NULL : $data->phone,
						"store_id" => $data->store_id,
						"deleted" => NULL,
						"verified" => 1
					]);

					$user_id = $this->db->insert_id();

					if(isset($data->image)){
						$img = $this->save_profile_image($user_id);
						$this->db->where("user_id",$user_id);
						$this->db->update("user",[
							"image_path" => $img['image_path'],
							"image_name" => $img['image_name']
						]);
					}else{
						$this->db->where("user_id",$user_id);
						$this->db->update("user",[
							"image_path" => 'public/img/' ,
							"image_name" => 'person-placeholder.jpg'
						]);
					}

					$this->db->trans_complete();

			        if ($this->db->trans_status() === FALSE){
			            echo json_encode(["status" => false , "message" => "Failed", "action" => "add"]);
			        }else{
			            echo json_encode(["status" => true , "message" => "Added Successfully", "action" => "add"]);
			        }
			    }
	    }else{
	    	echo json_encode(["status" => true , "message" => "403: Access Forbidden", "action" => "add"]);
	    }
	}

	public function edit(){
		//print_r_die($this->post);

		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$data = $this->post;

			$this->db->trans_start();
			$deleted = $this->db->select("deleted")->where("user_id",$data->user_id)->get("user")->row()->deleted;
			if($deleted != ''){
				echo json_encode(["status" => false , "message" => "User doesn't exist", "action" => "edit"]);
				return false;
			}
			$this->db->where("user_id", $data->user_id);
			if(isset($data->password)){
				$this->db->update("user",[
					"display_name" => $data->firstname ." ". $data->lastname,
					"firstname" => $data->firstname,
					"lastname" => $data->lastname,
					"role" => $data->role,
					"status" => 1,
					"password" => md5($data->password),
					"phone" => ($data->phone == '') ? NULL : $data->phone,
					"deleted" => NULL
				]);
			}else{
				$this->db->update("user",[
					"display_name" => $data->firstname ." ". $data->lastname,
					"firstname" => $data->firstname,
					"lastname" => $data->lastname,
					"role" => $data->role,
					"status" => 1,
					"deleted" => NULL
				]);

				if(isset($data->phone)){
					$this->db->update("user",[
						"phone" => $data->phone
					]);
				}
			}			

			if(isset($data->image)){
				$img = $this->save_profile_image($data->user_id);
				$this->db->where("user_id",$data->user_id);
				$this->db->update("user",[
					"image_path" => $img['image_path'],
					"image_name" => $img['image_name']
				]);
			}
			
			if($data->company_name != ''){
				$this->db->where("store_id",$data->store_id);
				$this->db->update("store",[
					"store_name" => $data->company_name
				]);
			}			

			$this->db->where("store_address_id",$data->store_address_id);
			$this->db->update("store_address",[
				"street1" => NULL,
				"street2" => NULL,
				"suburb" => NULL,
				"city" => NULL,
				"state" => NULL,
				"postcode" => NULL,
				"country" => $data->country,
				"countryNameCode" => $data->countryNameCode,
				"countryCode" => $data->countryCode,
				"timezone" => NULL,
				"address" => $data->address
			]);

			if(isset($data->logo)){
				$logo = $this->save_logo($data->store_id);

				$this->db->where("store_id",$data->store_id);
				$this->db->update("store",[
					"logo_image_path" => $logo['logo_image_path'],
					"logo_image_name" => $logo['logo_image_name']
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

	private function login_trail($user_id){
        $result = $this->db->insert("login_trail" , [
            "user_id"       => $user_id ,
            "ip_address"    => $this->input->ip_address() ,
            "user_agent"    => $_SERVER['HTTP_USER_AGENT'] ,
            "login_time"    => time()
        ]);
    }

    private function save_profile_image($user_id){

			$image = $this->post->image;

			$name = md5($user_id).'_'.time().'.PNG';
	        $year = date("Y");
	        $month = date("m");
	        
	        $folder = "./public/upload/user/".$year."/".$month;
	        
	        $date = time();

	        if (!file_exists($folder)) {
	            mkdir($folder, 0777, true);
	            mkdir($folder.'/thumbnail', 0777, true);

	            create_index_html($folder);
	        }

	        $path = $folder.'/'.$name;

	      //  $encoded = $image;

		    //explode at ',' - the last part should be the encoded image now
		   // $exp = explode(',', $encoded);

		    //we just get the last element with array_pop
		   // $base64 = array_pop($exp);

		    //decode the image and finally save it
		    $data = base64_decode($image);

		    //make sure you are the owner and have the rights to write content
		    file_put_contents($path, $data);

		    $img = array(
		    	"image_path"			=> $year."/".$month.'/' ,
		    	"image_name"			=> $name
		    );

	        return $img;
	}

	private function save_logo($store_id){

		
			$image = $this->post->logo;

			$name = md5($store_id).'_'.time().'.PNG';
	        $year = date("Y");
	        $month = date("m");
	        
	        $folder = "./public/upload/company/".$year."/".$month;
	        
	        $date = time();

	        if (!file_exists($folder)) {
	            mkdir($folder, 0777, true);
	            mkdir($folder.'/thumbnail', 0777, true);

	            create_index_html($folder);
	        }

	        $path = $folder.'/'.$name;

	      //  $encoded = $image;

		    //explode at ',' - the last part should be the encoded image now
		   // $exp = explode(',', $encoded);

		    //we just get the last element with array_pop
		   // $base64 = array_pop($exp);

		    //decode the image and finally save it
		    $data = base64_decode($image);

		    //make sure you are the owner and have the rights to write content
		    file_put_contents($path, $data);

		    $img = array(
		    	"logo_image_path"			=> $year."/".$month.'/' ,
		    	"logo_image_name"			=> $name
		    );

	        return $img;
	}

	public function get_all_emp(){

		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$data = $this->post;
			if($data->store_id){
				
				// $offset = (isset($data->offset)) ? $data->offset : 0;
	   			// $limit = (isset($data->limit)) ? $data->limit : 4;

				$this->db->where("deleted IS NULL");
				$this->db->where("store_id",$data->store_id);
				//$result = $this->db->where_in("role", ["DRIVER PREMIUM","MANAGER"])->limit($limit, $offset)->get("user")->result();
				$result = $this->db->where("role", "DRIVER PREMIUM")->get("user")->result();

				//print_r_die($this->db->last_query());
				if($result){
					foreach ($result as $key => $value) {
						if($value->image_path == "public/img/"){
						 	$result[$key]->full_path = $this->config->site_url($value->image_path.$value->image_name);
						}else{
							$result[$key]->full_path = $this->config->site_url("public/upload/user/".$value->image_path."/".$value->image_name);
						}
					}
					echo json_encode(["status" => true, "data" => $result, "action" => "get_all_emp"]);
				}else{
					echo json_encode(["status" => false, "message" => "No data available", "action" => "get_all_emp"]);
				}
			}else{
				echo json_encode(["status" => false, "message" => "No store id", "action" => "get_all_emp"]);
			}
		}else{
	    	echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "get_all_emp"]);
	    }
	}

	public function multiple_delete(){
		
		$allowed = validate_app_token($this->post->token);
		if($allowed){

			$data = $this->post;

			$users = json_decode($data->users);
			$this->db->trans_start();

			foreach ($users as $key) {
				$this->db->where("store_id", $data->store_id);
				$this->db->where("user_id", $key);
				$this->db->update("user",[
					"deleted" => time(),
					"status" => 0
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
}
