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

		$this->db->trans_start();
		$this->db->insert("store_address",[	"street1" => "Street"]);

		$address_id = $this->db->insert_id();


		$this->db->insert("store",[
			"store_name" => "Company Name",
			"address_id" => $address_id,
			"created" => time()
		]);

		$store_id = $this->db->insert_id();

		$this->db->insert("user",[
			"display_name" => "Firstname Lastname",
			"email_address" => $data->email,
			"username" => $data->username,
			"role" => "ADMIN",
			"status" => 1,
			"created" => time(),
			"password" => md5($data->password),
			"phone" => ($data->phone == '') ? NULL : $data->phone,
			"store_id" => $store_id,
			"deleted" => NULL
		]);

		$userid = $this->db->insert_id();

		$this->db->insert("user_plan",[
			"store_id" => $store_id,
			"plan_id" => 1,
			"plan_created" => time(),
			"plan_expiration" => strtotime("+1 month", time()),
			"billing_type" => "NA",
			"who_updated" => $userid,
			// "ip_address" => $data->ip_address,
			"active" => 1,
			"updated" => NULL
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
				"logo_image_name" => 'company_logo.png'
			]);
		}

		$this->login_trail($userid);

		$this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            echo json_encode(["status" => false , "message" => "Failed", "action" => "register"]);
        }else{
            echo json_encode(["status" => true , "message" => "Successfully Registered", "action" => "register"]);
        }
	}

	// public function register_driver(){
	// 	$data = $this->post;		

	// 	$this->db->insert("user",[
	// 		"display_name" => "Firstname Lastname",
	// 		"email_address" => $data->email,
	// 		"username" => $data->username,
	// 		"role" => "DRIVER",
	// 		"store_id" => $data->store_id,
	// 		"status" => 1,
	// 		"created" => time(),
	// 		"password" => md5($data->password),
	// 		"store_id" => $store_id
	// 	]);

	// 	$userid = $this->db->insert_id();

	// 	if($data->image){
	// 		$img = $this->save_profile_image($userid);
	// 		$this->db->where("user_id",$userid);
	// 		$this->db->update("user",[
	// 			"image_path" => $img['image_path'],
	// 			"image_name" => $img['image_name']
	// 		]);
	// 	}

	// 	$this->login_trail($userid);

	// 	$this->db->trans_complete();

 //        if ($this->db->trans_status() === FALSE){
 //            echo json_encode(["status" => false , "message" => "Failed", "action" => "register"]);
 //        }else{
 //            echo json_encode(["status" => true , "message" => "Successfully Registered", "action" => "register"]);
 //        }

	// }

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
	    	echo json_encode(["status" => true , "message" => "403: Access Forbidden", "action" => "register"]);
	    }
	}

	public function add(){

		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$data = $this->post;

			$this->db->trans_start();

			$this->db->insert("user",[
				"display_name" => $data->display_name,
				"email_address" => $data->email,
				"username" => $data->username,
				"role" => $data->role,
				"status" => 1,
				"created" => time(),
				"password" => md5($data->password),
				"phone" => ($data->phone == '') ? NULL : $data->phone,
				"store_id" => $data->store_id,
				"deleted" => NULL
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
	    }else{
	    	echo json_encode(["status" => true , "message" => "403: Access Forbidden", "action" => "add"]);
	    }
	}

	public function edit(){

		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$data = $this->post;

			$this->db->trans_start();
			$this->db->where("user_id", $data->user_id);
			if(isset($data->password)){
				$this->db->update("user",[
					"display_name" => $data->display_name,
					"email_address" => $data->email,
					"username" => $data->username,
					"role" => $data->role,
					"status" => 1,
					"password" => md5($data->password),
					"phone" => ($data->phone == '') ? NULL : $data->phone,
					"store_id" => $data->store_id,
					"deleted" => NULL
				]);
			}else{
				$this->db->update("user",[
					"display_name" => $data->display_name,
					"email_address" => $data->email,
					"username" => $data->username,
					"role" => $data->role,
					"phone" => ($data->phone == '') ? NULL : $data->phone,
					"status" => 1,
					"store_id" => $data->store_id,
					"deleted" => NULL
				]);
			}
			

			if(isset($data->image)){
				$img = $this->save_profile_image($data->user_id);
				$this->db->where("user_id",$data->user_id);
				$this->db->update("user",[
					"image_path" => $img['image_path'],
					"image_name" => $img['image_name']
				]);
			}

			if($data->role == "ADMIN"){
				$this->db->where("user_id",$data->user_id);
				$this->db->update("store",[
					"store_name" => $data->company_name
				]);

				$this->db->where("store_address_id",$data->store_address_id);
				$this->db->update("store_address",[
					"street1" => $data->street1,
					"street2" => $data->street2,
					"suburb" => $data->suburb,
					"city" => $data->city,
					"state" => $data->state,
					"postcode" => $data->postcode,
					"country" => $data->country,
					"timezone" => NULL
				]);

				if(isset($data->logo)){
					$logo = $this->save_logo($data->store_id);

					$this->db->where("store_id",$data->store_id);
					$this->db->update("store",[
						"logo_image_path" => $img['logo_image_path'],
						"logo_image_name" => $img['logo_image_name']
					]);
				}
			}
			
			$this->db->trans_complete();

	        if ($this->db->trans_status() === FALSE){
	            echo json_encode(["status" => false , "message" => "Failed", "action" => "edit"]);
	        }else{
	            echo json_encode(["status" => true , "message" => "Updated Successfully", "action" => "edit"]);
	        }		
	    }else{
	    	echo json_encode(["status" => true , "message" => "403: Access Forbidden", "action" => "edit"]);
	    }
	}

	// public function delete(){
	// 	$data = $this->post;
		
	// 	$this->db->trans_start();
	// 	$this->db->where("store_id", $data->store_id);
	// 	$this->db->where("user_id", $data->user_id);
	// 	$this->db->update("user",[
	// 		"deleted" => time()
	// 	]);

	// 	$this->db->trans_complete();

 //        if ($this->db->trans_status() === FALSE){
 //            echo json_encode(["status" => false , "message" => "Failed", "action" => "edit"]);
 //        }else{
 //            echo json_encode(["status" => true , "message" => "Deleted Successfully", "action" => "edit"]);
 //        }
	// }

	private function login_trail($user_id){
        $result = $this->db->insert("login_trail" , [
            "user_id"       => $user_id ,
            "ip_address"    => $this->input->ip_address() ,
            "user_agent"    => $_SERVER['HTTP_USER_AGENT'] ,
            "login_time"    => time()
        ]);
    }

    private function save_profile_image($user_id){

		$allowed = validate_app_token($this->post->token);
    	if($allowed){

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
	    }else{
	    	echo json_encode(["status" => true , "message" => "403: Access Forbidden", "action" => "save_profile_image"]);
	    }
	}

	private function save_logo($store_id){

		$allowed = validate_app_token($this->post->token);
		if($allowed){
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
	    }else{
	    	echo json_encode(["status" => true , "message" => "403: Access Forbidden", "action" => "save_logo"]);
	    }
	}

	public function get_all_emp(){

		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$data = $this->post;
			if($data->store_id){
				$this->db->where("deleted IS NULL");
				$this->db->where("store_id",$data->store_id);
				$result = $this->db->where_in("role", ["DRIVER","MANAGER"])->get("user")->result();

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
				echo json_encode(["status" => true, "message" => "No store id", "action" => "get_all_emp"]);
			}
		}else{
	    	echo json_encode(["status" => true , "message" => "403: Access Forbidden", "action" => "get_all_emp"]);
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
	    	echo json_encode(["status" => true , "message" => "403: Access Forbidden", "action" => "multiple_delete"]);
	    }
	}
}
