<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller {

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

	public function webhooks(){
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$result = $this->braintree_lib->getAllPlan();
			if($result){
				echo json_encode(["status" => true , "data" => $result, "action" => "webhooks"]);
			}else{
				echo json_encode(["status"=> true, "message" => "No notification available", "action" => "webhooks"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "webhooks"]);
		}
	}

	public function getPlans(){
		$allowed = validate_app_token($this->post->token);
		if($allowed){
			$result = $this->braintree_lib->getAllPlan();
			if($result){
				echo json_encode(["status" => true , "data" => $result, "action" => "getPlans"]);
			}else{
				echo json_encode(["status" => false , "message" => "Something went wrong.", "action" => "getPlans"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "getPlans"]);
		}
	}

	public function braintree_token(){
		$allowed = validate_app_token($this->post->token);

		if($allowed){
			$btoken = $this->braintree_lib->create_client_token();

			echo json_encode(["status" => true , "data" => $btoken, "action" => "braintree_token"]);
		}else{
	    	echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "braintree_token"]);
		}


	}

	public function check_customer(){
		$allowed = validate_app_token($this->post->token);

		if($allowed){
			$data = $this->braintree_lib->check_customer($this->post);
			if($data){

				echo json_encode(["status" => true , "data" => $data, "action" => "check_customer"]);
			}else{

				echo json_encode(["status" => false , "message" => "Not exist", "action" => "check_customer"]);
			}

		}else{
	    	echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "check_customer"]);
		}
	}
	public function create_subscription_token(){
		$allowed = validate_app_token($this->post->token);

		if($allowed){

			$result = $this->braintree_lib->create_subscription_token($this->post);
			if(empty($result->error)){

				$info = $this->update_subscription($result->subscription->id);

				$result->role = $info->role;
				$result->title = $info->title;
		        // if ($this->db->trans_status() === FALSE){
				if($result->role == ''){
		           echo json_encode(["status" => false , "message" => "Subscription created. Failed to update database","action" => "create_subscription_token"]);
				}else{
					
					$this->db->trans_start();
					$plan = $this->db->select("title")->where("planId",$this->post->planId)->get("plan")->row()->title;

					$this->db->insert("notification", [
						"description" => "Subscription ".$plan." - Created",
						"type" => 2,
						"isread" => 0,
						"ref_id" => $result->subscription->id,
						"user_id" => $this->post->user_id,
						"created" => time()
					]);

					$this->db->trans_complete();
					// if($this->db->trans_complete() === FALSE){

					// }else{


					// 	$this->email->from('no-reply@trackerteer.com', 'Trackerteer | Vehicle Checklist');
					// 	$this->email->to($data->email);
					// 	$this->email->set_mailtype("html");
					// 	$this->email->subject('Email verification successful');
					// 	$this->email->message($this->load->view('email/subscription_success', $data , true));

					// 	if($this->email->send()){
							echo json_encode(["status" => true , "data" => $result, "action" => "create_subscription_token"]);
						// }else{
						// 	echo json_encode(["status" => false , "message" => "Subscription created. Email notification failed to send.", "action" => "create_subscription_token"]);
			 		// 	}				            
					// }
		        }
			}else{
				echo json_encode(["status" => false , "error" => $result, "message" => "Something went wrong", "action" => "create_subscription_token"]);
			}
			
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "create_subscription_token"]);
		}
	}

	public function create_subscription_nonce(){
		$allowed = validate_app_token($this->post->token);

		if($allowed){

			$result = $this->braintree_lib->create_subscription_nonce($this->post);
			if($result){
				echo json_encode(["status" => true , "data" => $result, "action" => "create_subscription_nonce"]);
			}else{
				echo json_encode(["status" => false , "message" => "Something went wrong", "action" => "create_subscription_nonce"]);
			}
			
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "create_subscription_nonce"]);
		}
	}

	public function update_plan_subscription(){
		$allowed = validate_app_token($this->post->token);

		if($allowed){

			$result = $this->braintree_lib->update_subscription($this->post);
			if($result){
				
				$role = $this->update_subscription($result->subscription->id);
				$result->role = $info->role;
				$result->title = $info->title;
				if($result->role == ''){
		            echo json_encode(["status" => false , "message" => "Subscription updated, Failed to update database","action" => "update_plan_subscription"]);
				}else{

					if($data->isUpgrade){
						if($vehicles){
				        	$this->db->trans_start();
				        	$this->db->where("store_id",$data->store_id);
				        	$this->db->where_not_in("vehicle_id",$vehicles);
				        	$this->db->update("vehicle",[
				        		"is_active" => 0
				        	]);
				        	$this->db->trans_complete();
				        	if ($this->db->trans_status() === FALSE){
				        		echo json_encode(["status" => false , "message" => "Failed to update vehicles", "action" => "cancel_subscription"]);
				        	}			        	
				        }
					}
					// $data['app_icon'] = $this->config->site_url("public/img/vehicle-checklist.png");
					// $data['background'] = $this->config->site_url("public/img/reset-pass.jpg");

					// $this->email->from('no-reply@trackerteer.com', 'Trackerteer | Vehicle Checklist');
					// $this->email->to($email);
					// $this->email->set_mailtype("html");
					// $this->email->subject('Subscription Update');
					// $this->email->message($this->load->view('email/subscription_update', $data , true));

					// if($this->email->send()){
						$this->db->trans_start();

						$plan = $this->db->select("title")->where("planId",$this->post->planId)->get("plan")->row()->title;

						$this->db->insert("notification", [
						"description" => "Subscription: ".$plan." - Updated",
							"type" => 2,
							"isread" => 0,
							"ref_id" => $result->subscription->id,
							"user_id" => $this->post->user_id,
							"created" => time()
						]);

						$this->db->trans_complete();
						echo json_encode(["status" => true , "data" => $result, "action" => "update_plan_subscription"]);
					// }else{
					// 	echo json_encode(["status" => true , "message" => "Failed to send email", "data" => $result, "action" => "update_plan_subscription"]);
					// }		            
		        }
			}else{
				echo json_encode(["status" => false , "message" => "Something went wrong", "action" => "update_plan_subscription"]);
			}
			
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "update_plan_subscription"]);
		}
	}

	public function cancel_subscription(){
		$allowed = validate_app_token($this->post->token);
		$data = $this->post;
		$vehicles = json_decode($data->vehicles);
		if($allowed){

			$result = $this->braintree_lib->cancel_subscription($this->post);

			if($result){
				$this->db->trans_start();

				$this->db->where("store_id", $this->post->store_id);
		        $this->db->where("active", 1);
		        $deactivated = $this->db->update("user_plan",[
		            "active" => 0,
		            "updated" => time(),
		            "who_updated" => NULL
		        ]);

		        if($deactivated){
		           
		            $expiration = 0;
		            $created = date("d/M/Y H:i:s A", time());

		            $new = $this->db->insert("user_plan",[
		                "store_id" => $this->post->store_id,
		                "plan_id" => 'N/A',
		                "subscription_id" => "N/A",
		                "vehicle_limit" => 5,
		                "plan_created" => strtotime(str_replace("/"," ",$created)),
		                "plan_expiration" => 0,
		                "billing_type" => "N/A",
		                "who_updated" => NULL,
		                "active" => 1,
		                "updated" => NULL,
		                "payment_type" => NULL,
		                "payment_name" => NULL
		            ]);
		            if($new){
				        $this->db->where("user_id",$this->post->user_id);
				        $role = $this->db->update("user",[
				        	"role" => "DRIVER"
				        ]);

				        if($data->isUpgrade){
							if($vehicles){
					        	$this->db->trans_start();
					        	$this->db->where("store_id",$data->store_id);
					        	$this->db->where_not_in("vehicle_id",$vehicles);
					        	$this->db->update("vehicle",[
					        		"is_active" => 0
					        	]);
					        	$this->db->trans_complete();
					        	if ($this->db->trans_status() === FALSE){
					        		echo json_encode(["status" => false , "message" => "Failed to update vehicles", "action" => "cancel_subscription"]);
					        	}			        	
					        }
						}

		            }else{
		            	return false;
		            }            
		        }
		        $this->db->trans_complete();

		        $this->db->select("role");
		        $this->db->where("user_id",$this->post->user_id);
		        $role = $this->db->get("user")->row()->role;
		        
		        if ($this->db->trans_status() === FALSE){
		           echo json_encode(["status" => false , "message" => "Failed to update database", "action" => "cancel_subscription"]);
		        }else{
		        	$result->role = $role;
		        	$result->plan_title = "Free";
		   //      	$data['app_icon'] = $this->config->site_url("public/img/vehicle-checklist.png");
					// $data['background'] = $this->config->site_url("public/img/reset-pass.jpg");

					// $this->email->from('no-reply@trackerteer.com', 'Trackerteer | Vehicle Checklist');
					// $this->email->to($email);
					// $this->email->set_mailtype("html");
					// $this->email->subject('Subscription Update');
					// $this->email->message($this->load->view('email/cancel_subscription', $data , true));

					// if($this->email->send()){
						 echo json_encode(["status" => true , "data" => $result, "action" => "cancel_subscription"]);
					// }else{
					// 	echo json_encode(["status" => true , "message" => "Failed to send email", "data" => $result, "action" => "cancel_subscription"]);
					// }		           
		        }
				
			}else{
				echo json_encode(["status" => false , "message" => "Something went wrong", "action" => "cancel_subscription"]);
			}
			
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "cancel_subscription"]);
		}
	}

	public function get_active_subscription(){
		$allowed = validate_app_token($this->post->token);

		if($allowed){

			$result = $this->braintree_lib->get_active_subscription($this->post);
			if($result){
				echo json_encode(["status" => true , "data" => $result, "action" => "get_active_subscription"]);
			}else{
				echo json_encode(["status" => false , "message" => "Something went wrong", "action" => "get_active_subscription"]);
			}			
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "get_active_subscription"]);
		}
	}

	public function get_customer_data(){
		$allowed = validate_app_token($this->post->token);

		if($allowed){

			$result = $this->braintree_lib->get_customer_data($this->post);

			if($result->exist == 1){

				echo json_encode(["status" => true , "data" => $result,"is_exist" => $result->exist, "action" => "get_customer_data"]);
			}else if($result->exist == 2){
				echo json_encode(["status" => true , "data" => $result,"is_exist" => $result->exist, "action" => "get_customer_data"]);
				
			}else{
				echo json_encode(["status" => false , "message" => "Something went wrong.", "action" => "get_customer_data"]);
			}
			
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "get_customer_data"]);
		}
	}

	public function create_payment_method(){
		$allowed = validate_app_token($this->post->token);

		if($allowed){

			$result = $this->braintree_lib->create_payment_method($this->post);
			if($result){
				
				echo json_encode(["status" => true , "data" => $result, "action" => "create_payment_method"]);
			}else{
				echo json_encode(["status" => false , "error" => $result, "action" => "create_payment_method"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "create_payment_method"]);
		}
	}

	public function update_payment_method(){
		$allowed = validate_app_token($this->post->token);

		if($allowed){
			$result = $this->braintree_lib->update_payment_method($this->post);
			if($result){
				// $data['app_icon'] = $this->config->site_url("public/img/vehicle-checklist.png");
				// $data['background'] = $this->config->site_url("public/img/reset-pass.jpg");
				// $data['card'] = $result->card;
				// $data['card_expiration'] = $result->exp;

				// $this->email->from('no-reply@trackerteer.com', 'Trackerteer | Vehicle Checklist');
				// $this->email->to($email);
				// $this->email->set_mailtype("html");
				// $this->email->subject('Your payment detail has been successfully updated');
				// $this->email->message($this->load->view('email/payment_method_update', $data , true));

				// if($this->email->send()){
					echo json_encode(["status" => true , "data" => $result, "action" => "update_payment_method"]);
				// }else{
				// 	echo json_encode(["status" => true , "message" => "Failed to send email", "data" => $result, "action" => "update_payment_method"]);
				// }		   
			}else{
				echo json_encode(["status" => false , "error" => $result, "action" => "update_payment_method"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "update_payment_method"]);
		}
	}

	public function update_addOn_subscription(){
		$allowed = validate_app_token($this->post->token);
		$data = $this->post;
		$vehicles = json_decode($data->vehicles);

		if($allowed){

			$result = $this->braintree_lib->update_addOn_subscription($this->post);
			if($result){
				$this->db->trans_start();
				$this->db->where("store_id",$this->post->store_id);
				$this->db->where("active",1);

				$updated = $this->db->update("user_plan",[	               
	                "vehicle_limit" => $this->post->quantity
	            ]);
	            
				if($data->isUpgrade){
					if($vehicles){
			        	$this->db->trans_start();
			        	$this->db->where("store_id",$data->store_id);
			        	$this->db->where_not_in("vehicle_id",$vehicles);
			        	$this->db->update("vehicle",[
			        		"is_active" => 0
			        	]);
			        	$this->db->trans_complete();
			        	if ($this->db->trans_status() === FALSE){
			        		echo json_encode(["status" => false , "message" => "Failed to update vehicles", "action" => "cancel_subscription"]);
			        	}			        	
			        }
				}
	            
		        $this->db->trans_complete();

		        if($updated){
		        	echo json_encode(["status" => true , "message" => "Successfully updated", "action" => "update_addOn_subscription"]);
		        }else{
		        	echo json_encode(["status" => false , "message" => "Updated. Failed to update database", "action" => "update_addOn_subscription"]);
		        }
			}else{
				echo json_encode(["status" => false , "data" => $result, "action" => "update_addOn_subscription"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "update_addOn_subscription"]);
		}
	}

	public function retry_charge(){
		$data = $this->post;

		$allowed = validate_app_token($this->post->token);

		if($allowed){
			$result = $this->braintree_lib->retry_charge($this->post->subscription_id);
			if($result){
				
			}else{
				
			}

		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "retry_charge"]);
		}
	}

	
	public function update_subscription($subscription_id){

		$data = $this->post;

		$this->db->trans_start();
        
        $this->db->where("store_id", $data->store_id);
        $this->db->where("active", 1);
        $deactivated = $this->db->update("user_plan",[
            "active" => 0,
            "updated" => time(),
            "who_updated" => NULL
        ]);

        if($deactivated){
            if($data->billing_type == 'MONTHLY'){
                $billing = " + 1 month";
            }else if($data->billing_type == 'YEARLY'){
                $billing = " + 1 year";
            }else{
                $billing = "N/A";
            }
            $expiration = 0;
            $created = date("d/M/Y H:i:s A", time());

            $new = $this->db->insert("user_plan",[
                "store_id" => $data->store_id,
                "plan_id" => $data->planId,
                "subscription_id" => $subscription_id,
                "vehicle_limit" => $data->quantity,
                "plan_created" => strtotime(str_replace("/"," ",$created)),
                "plan_expiration" => ($billing == 'N/A') ? NULL : strtotime(trim(str_replace("/"," ",$created) . $billing)),
                "billing_type" => $data->billing_type,
                "who_updated" => NULL,
                "active" => 1,
                "updated" => NULL,
                "payment_name" => $data->payment_name,
                "payment_type" => $data->payment_type
            ]);

            if($new){
            	$users_list = $this->db->where("store_id",$data->store_id)->get("user")->result();

	            foreach ($users_list as $key => $value) {
	            	// if(($data->planId == 'custom_monthly') || ($data->planId == 'custom_yearly') || ($data->planId == 'premium_monthly') || ($data->planId == 'premium_yearly')){
	                if(($data->planId == 'sandbox_custom_monthly') || ($data->planId == 'sandbox_custom_yearly') || ($data->planId == 'sandbox_premium_monthly') || ($data->planId == 'sandbox_premium_yearly')){
	                    switch ($value->role) {
	                        case 'DRIVER':
	                            $this->db->where("user_id",$value->user_id)->update("user",["role" => "ADMIN PREMIUM"]);
	                            continue;
	                        case 'ADMIN FREE':
	                            $this->db->where("user_id",$value->user_id)->update("user",["role" => "ADMIN PREMIUM"]);
	                            continue;
	                        // case 'MANAGER FREE':
	                        //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "MANAGER PREMIUM"]);
	                        //     continue;
	                    }
	                // }elseif($data->planId == 'free_trial'){
	                }elseif($data->planId == 'sandbox_free_trial'){
	                    switch ($value->role) {
	                        case 'DRIVER':
	                            $this->db->where("user_id",$value->user_id)->update("user",["role" => "ADMIN FREE"]);
	                            continue;
	                        // case 'DRIVER FREE':
	                        //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "DRIVER PREMIUM"]);
	                        //     continue;
	                        // case 'MANAGER FREE':
	                        //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "MANAGER PREMIUM"]);
	                        //     continue;
	                    }
	                }elseif($data->planId == 'sandbox_basic_plan_trial_3'){
	                	switch ($value->role) {
	                        case 'DRIVER':
	                            $this->db->where("user_id",$value->user_id)->update("user",["role" => "ADMIN FREE"]);
	                            continue;
	                        // case 'DRIVER FREE':
	                        //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "DRIVER PREMIUM"]);
	                        //     continue;
	                        // case 'MANAGER FREE':
	                        //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "MANAGER PREMIUM"]);
	                        //     continue;
	                    }
	                }else{
	                    switch ($value->role) {
	                        case 'ADMIN PREMIUM':
	                            $this->db->where("user_id",$value->user_id)->update("user",["role" => "DRIVER"]);
	                            continue;
	                        // case 'DRIVER PREMIUM':
	                        //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "DRIVER FREE"]);
	                        //     continue;
	                        // case 'MANAGER PREMIUM':
	                        //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "MANAGER FREE"]);
	                        //     continue;
	                    }
	                }
	            }
            }else{
            	return false;
            }            
        }
        $this->db->trans_complete();

        $this->db->select("u.role, p.title");
        $this->db->join("user_plan up", "up.store_id = u.store_id");
        $this->db->join("plan p", "p.planId = up.plan_id");

        $this->db->where("user_id",$data->user_id);
        $this->db->where("up.active",1);
        $info = $this->db->get("user u")->row();
        
        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $info;
        }
	}

	public function plan_inquiry(){
		$allowed = validate_app_token($this->post->token);

		if($allowed){
			//print_r_die($this->post);
			$data['fullname'] = $this->post->fullname;
			$data['phone'] = $this->post->phone;
			$data['company'] = $this->post->company;
			$data['message'] = $this->post->message;
			$data['email'] = $this->post->email;
			$this->email->from($this->post->email, 'Vehicle Checklist Customer');
			$this->email->to("trackerteer.sender@gmail.com");
			$this->email->set_mailtype("html");
			$this->email->subject('Enterprise Plan Inquiry');
			$this->email->message($this->load->view('email/enterprise_plan_inquiry', $data, true));

			if($this->email->send()){

				echo json_encode(["status" => true , "message" => "Inquiry has been sent", "action" => "plan_inquiry"]);
			}else{
				echo json_encode(["status" => false , "message" => "Inquiry sending failed", "action" => "plan_inquiry"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "plan_inquiry"]);
		}
	}
}
