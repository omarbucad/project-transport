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
		//print_r_die($this->post);
		$allowed = validate_app_token($this->post->token);

		if($allowed){

			$result = $this->braintree_lib->create_subscription_token($this->post);
			if(empty($result->error)){
				// $data = $this->post;

				// $this->db->trans_start();
                
		  //       $this->db->where("store_id", $data->store_id);
		  //       $this->db->where("active", 1);
		  //       $deactivated = $this->db->update("user_plan",[
		  //           "active" => 0,
		  //           "updated" => time(),
		  //           "who_updated" => NULL
		  //       ]);

		  //       if($deactivated){
		  //           if($data->billing_type == 'MONTHLY'){
		  //               $billing = " + 1 month";
		  //           }else if($data->billing_type == 'YEARLY'){
		  //               $billing = " + 1 year";
		  //           }else{
		  //               $billing = "N/A";
		  //           }
		  //           $expiration = 0;
		  //           $created = date("M d Y H:i:s A", time());

		  //           $new = $this->db->insert("user_plan",[
		  //               "store_id" => $data->store_id,
		  //               "plan_id" => $data->planId,
		  //               "vehicle_limit" => (isset($data->quantity) != '') ? $data->quantity : 0,
		  //               "plan_created" => strtotime($created),
		  //               "plan_expiration" => ($billing == 'N/A') ? NULL : strtotime(trim($created . $billing)),
		  //               "billing_type" => $data->billing_type,
		  //               "who_updated" => NULL,
		  //               "active" => 1,
		  //               "updated" => NULL
		  //           ]);

		  //           if($new){
		  //           	$users_list = $this->db->where("store_id",$data->store_id)->get("user")->result();

			 //            foreach ($users_list as $key => $value) {
			 //                if(($data->planId == 'sandbox_custom_monthly') || ($data->planId == 'sandbox_premium_monthly') || ($data->planId == 'sandbox_premium_yearly')){
			 //                    switch ($value->role) {
			 //                        case 'DRIVER':
			 //                            $this->db->where("user_id",$value->user_id)->update("user",["role" => "ADMIN PREMIUM"]);
			 //                            continue;
			 //                        case 'ADMIN FREE':
			 //                            $this->db->where("user_id",$value->user_id)->update("user",["role" => "ADMIN PREMIUM"]);
			 //                            continue;
			 //                        // case 'MANAGER FREE':
			 //                        //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "MANAGER PREMIUM"]);
			 //                        //     continue;
			 //                    }
			 //                }elseif($data->planId == 3){
			 //                    switch ($value->role) {
			 //                        case 'DRIVER':
			 //                            $this->db->where("user_id",$value->user_id)->update("user",["role" => "ADMIN FREE"]);
			 //                            continue;
			 //                        // case 'DRIVER FREE':
			 //                        //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "DRIVER PREMIUM"]);
			 //                        //     continue;
			 //                        // case 'MANAGER FREE':
			 //                        //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "MANAGER PREMIUM"]);
			 //                        //     continue;
			 //                    }
			 //                }else{
			 //                    switch ($value->role) {
			 //                        case 'ADMIN PREMIUM':
			 //                            $this->db->where("user_id",$value->user_id)->update("user",["role" => "DRIVER"]);
			 //                            continue;
			 //                        // case 'DRIVER PREMIUM':
			 //                        //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "DRIVER FREE"]);
			 //                        //     continue;
			 //                        // case 'MANAGER PREMIUM':
			 //                        //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "MANAGER FREE"]);
			 //                        //     continue;
			 //                    }
			 //                }
			 //            }
		  //           }else{
		  //           	  echo json_encode(["status" => false , "message" => "Failed to update database","action" => "create_subscription_token"]); 
		  //           }

		  //           $this->db->select("role");
		  //           $this->db->where("user_id",$data->user_id);
		  //           $role = $this->db->get("user")->row()->role;

		  //           $result->role = $role;

		  //       }else{

		  //           echo json_encode(["status" => false , "message" => "Subscription created, Failed to update database","action" => "create_subscription_token"]);
		  //       }


		  //       $this->db->trans_complete();
		  //       if ($this->db->trans_status() === FALSE){

		  //           echo json_encode(["status" => false , "message" => "Subscription created, Failed to update database","action" => "create_subscription_token"]);

		  //       }else{
		  //           echo json_encode(["status" => true , "data" => $result, "action" => "create_subscription_token"]);
		  //       }

				$role = $this->update_subscription();

				$result->role = $role;
		        // if ($this->db->trans_status() === FALSE){
				if($result->role == ''){
		           echo json_encode(["status" => false , "message" => "Subscription created. Failed to update database","action" => "create_subscription_token"]);
				}else{
		            echo json_encode(["status" => true , "data" => $result, "action" => "create_subscription_token"]);
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
		//print_r_die($this->post);
		$allowed = validate_app_token($this->post->token);

		if($allowed){

			$result = $this->braintree_lib->update_subscription($this->post);
			if($result){
				// $data = $this->post;

				// $this->db->trans_start();
                
		  //       $this->db->where("store_id", $data->store_id);
		  //       $this->db->where("active", 1);
		  //       $deactivated = $this->db->update("user_plan",[
		  //           "active" => 0,
		  //           "updated" => time(),
		  //           "who_updated" => NULL
		  //       ]);

		  //       if($deactivated){
		  //           if($data->billing_type == 'MONTHLY'){
		  //               $billing = " + 1 month";
		  //           }else if($data->billing_type == 'YEARLY'){
		  //               $billing = " + 1 year";
		  //           }else{
		  //               $billing = "N/A";
		  //           }
		  //           $expiration = 0;
		  //           $created = date("M d Y H:i:s A", time());

		  //           $this->db->insert("user_plan",[
		  //               "store_id" => $data->store_id,
		  //               "plan_id" => $data->planId,
		  //               "plan_created" => strtotime($created),
		  //               "plan_expiration" => ($billing == 'N/A') ? NULL : strtotime(trim($created . $billing)),
		  //               "billing_type" => $data->billing_type,
		  //               "who_updated" => NULL,
		  //               "active" => 1,
		  //               "updated" => NULL
		  //           ]);

		  //           $users_list = $this->db->where("store_id",$data->store_id)->get("user")->result();

		  //           foreach ($users_list as $key => $value) {
		  //               if(($data->planId == 'sandbox_custom_monthly') || ($data->planId == 'sandbox_premium_monthly') || ($data->planId == 'sandbox_premium_yearly')){
		  //                   switch ($value->role) {
		  //                       case 'DRIVER':
		  //                           $this->db->where("user_id",$value->user_id)->update("user",["role" => "ADMIN PREMIUM"]);
		  //                           continue;
		  //                       case 'ADMIN FREE':
		  //                           $this->db->where("user_id",$value->user_id)->update("user",["role" => "ADMIN PREMIUM"]);
		  //                           continue;
		  //                       // case 'MANAGER FREE':
		  //                       //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "MANAGER PREMIUM"]);
		  //                       //     continue;
		  //                   }
		  //               }elseif($data->planId == 3){
		  //                   switch ($value->role) {
		  //                       case 'DRIVER':
		  //                           $this->db->where("user_id",$value->user_id)->update("user",["role" => "ADMIN FREE"]);
		  //                           continue;
		  //                       // case 'DRIVER FREE':
		  //                       //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "DRIVER PREMIUM"]);
		  //                       //     continue;
		  //                       // case 'MANAGER FREE':
		  //                       //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "MANAGER PREMIUM"]);
		  //                       //     continue;
		  //                   }
		  //               }else{
		  //                   switch ($value->role) {
		  //                       case 'ADMIN PREMIUM':
		  //                           $this->db->where("user_id",$value->user_id)->update("user",["role" => "DRIVER"]);
		  //                           continue;
		  //                       // case 'DRIVER PREMIUM':
		  //                       //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "DRIVER FREE"]);
		  //                       //     continue;
		  //                       // case 'MANAGER PREMIUM':
		  //                       //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "MANAGER FREE"]);
		  //                       //     continue;
		  //                   }
		  //               }
		  //           }
		  //       }
		  //       $this->db->select("role");
	   //          $this->db->where("user_id",$data->user_id);
	   //          $role = $this->db->get("user")->row();

	   //          $result->role = $role;
				$role = $this->update_subscription();
				$result->role = $role;
		        // if ($this->db->trans_status() === FALSE){
				if($result->role == ''){
		            echo json_encode(["status" => false , "message" => "Subscription updated, Failed to update database","action" => "update_plan_subscription"]);
				}else{
		            echo json_encode(["status" => true , "data" => $result, "action" => "update_plan_subscription"]);
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

		if($allowed){

			$result = $this->braintree_lib->cancel_subscription($this->post);
			if($result){
				echo json_encode(["status" => true , "data" => $result, "action" => "cancel_subscription"]);
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
				
				echo json_encode(["status" => true , "data" => $result, "action" => "createPaymentMethod"]);
			}else{
				echo json_encode(["status" => false , "error" => $result, "action" => "createPaymentMethod"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "403: Access Forbidden", "action" => "createPaymentMethod"]);
		}
	}
	
	public function update_subscription(){

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
            $created = date("M d Y H:i:s A", time());

            $new = $this->db->insert("user_plan",[
                "store_id" => $data->store_id,
                "plan_id" => $data->planId,
                "vehicle_limit" => (isset($data->quantity) != '') ? $data->quantity : 0,
                "plan_created" => strtotime($created),
                "plan_expiration" => ($billing == 'N/A') ? NULL : strtotime(trim($created . $billing)),
                "billing_type" => $data->billing_type,
                "who_updated" => NULL,
                "active" => 1,
                "updated" => NULL
            ]);
            if($new){
            	$users_list = $this->db->where("store_id",$data->store_id)->get("user")->result();

	            foreach ($users_list as $key => $value) {
	                if(($data->planId == 'sandbox_custom_monthly') || ($data->planId == 'sandbox_premium_monthly') || ($data->planId == 'sandbox_premium_yearly')){
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
	                }elseif($data->planId == 3){
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

        $this->db->select("role");
        $this->db->where("user_id",$data->user_id);
        $role = $this->db->get("user")->row()->role;
        
        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $role;
        }
	}
}
