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
	
	public function update_subscription(){

		$data = $this->post;		
		$allowed = validate_app_token($this->post->token);

		if($allowed){
			if($data){
				$this->db->trans_start();
				
				$this->db->where("store_id", $data->store_id);
				$this->db->where("user_plan_id", $data->user_plan_id);
				$deactivated = $this->db->update("user_plan",[
					"active" => 0,
					"updated" => time(),
					"who_updated" => $data->user_id
				]);

				if($deactivated){
					if($data->billing_type == 'MONTHLY'){
						$billing = " + 1 month";
					}else if($data->billing_type == 'ANNUAL'){
						$billing = " + 1 year";
					}else{
						$billing = "N/A";
					}
					$expiration = 0;
					$created = date("M d Y H:i:s A", time());

					$this->db->insert("user_plan",[
						"store_id" => $data->store_id,
						"plan_id" => $data->plan_id,
						"plan_created" => strtotime($created),
						"plan_expiration" => ($billing == 'N/A') ? NULL : strtotime(trim($created . $billing)),
						"billing_type" => $data->billing_type,
						"who_updated" => NULL,
						"active" => 1,
						"updated" => NULL
					]);

					$users_list = $this->db->where("store_id",$data->store_id)->get("user")->result();

					foreach ($users_list as $key => $value) {
						if($data->plan_id == 2){
							switch ($value->role) {
					            case 'DRIVER':
					                $this->db->where("user_id",$value->user_id)->update("user",["role" => "ADMIN PREMIUM"]);
					                break;
					            case 'ADMIN FREE':
					                $this->db->where("user_id",$value->user_id)->update("user",["role" => "ADMIN PREMIUM"]);
					                break;
					            // case 'MANAGER FREE':
					            //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "MANAGER PREMIUM"]);
					            //     break;
					        }
						}elseif($data->plan_id == 3){
							switch ($value->role) {
					            case 'DRIVER':
					                $this->db->where("user_id",$value->user_id)->update("user",["role" => "ADMIN FREE"]);
					                break;
					            // case 'DRIVER FREE':
					            //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "DRIVER PREMIUM"]);
					            //     break;
					            // case 'MANAGER FREE':
					            //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "MANAGER PREMIUM"]);
					            //     break;
					        }
						}else{
							switch ($value->role) {
					            case 'ADMIN PREMIUM':
					                $this->db->where("user_id",$value->user_id)->update("user",["role" => "DRIVER"]);
					                break;
					            // case 'DRIVER PREMIUM':
					            //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "DRIVER FREE"]);
					            //     break;
					            // case 'MANAGER PREMIUM':
					            //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "MANAGER FREE"]);
					            //     break;
					        }
						}
					}
				}


				$this->db->trans_complete();

		        if ($this->db->trans_status() === FALSE){
		            echo json_encode(["status" => false , "message" => "Something went wrong", "action" => "update_subscription"]);
		        }else{
		            echo json_encode(["status" => true , "message" => "Updated Successfully", "action" => "update_subscription"]);
		        }

			}else{
				echo json_encode(["status" => true , "message" => "No passed data", "action" => "update_subscription"]);
			}
	    }else{
	    	echo json_encode(["status" => true , "message" => "403: Access Forbidden", "action" => "update_subscription"]);
	    }
	}
}
