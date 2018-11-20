<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	private $post;

	public function __construct( ) {
		parent::__construct();

		//http://stackoverflow.com/questions/18382740/cors-not-working-php
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
        
        $this->load->model('register_model', 'register');
        $this->load->model('profile_model', 'profile');

        //kapag ionic ito ung gamitin
		//$this->post = json_decode(file_get_contents("php://input"));

		//kapag hindi
		$this->post = (object)$this->input->post();
	}

	public function signin(){

		$username = $this->post->username;
		$password = $this->post->password;
		$device_id = $this->post->device_id;
		$device_type = $this->post->device_type;

		if($this->post){
			if($data = $this->register->signin(["username" => $username , "password" => $password])){				

				if($data->image_name == "person-placeholder.jpg"){
					$data->image = "assets/imgs/person-placeholder.jpg";
				}else{
					$data->image = $this->config->site_url("thumbs/images/user/".$data->image_path."/80/80/".$data->image_name);
				}
				if($data->plan_expiration == ''){
					$expired = 0;
				}else{
					if($data->plan_expiration > strtotime("now")){
			    		$expired = 0;
				    }else{
						$expired = 1;
					}
				}
				$data->expired = $expired;
				//$data->expiring_in = $this->profile->get_userplan($data->user_id);
				$data->servertime = time();
				$token = generate_app_token($data->user_id, $device_id, $device_type);

				echo json_encode(["status" => true , "data" => $data, "action" => "signin", "token" => $token]);
			}else{
				$this->return_false(1);
			}

		}else{
			$this->return_false(1);
		}
	}
	

	private function return_false($rules = 0){
		$msg = "Server Error , Please Try Again Later";

		switch ($rules) {
			case 1:
				
				$msg = "Invalid Username & Password";

				break;
			
			default:
				# code...
				break;
		}

		echo json_encode([
				"status" 	=> false ,
				"message" 	=> $msg ,
				"action" 	=> "signin"
		]);
	}

	public function forgot_password(){
		$email = $this->post->email;


		if($email){

			$info = $this->db->select("email_address")->where("email_address", $email)->get("user")->num_rows();

			if($info > 0){
				$time = time();
				$code = $this->hash->encrypt($email . "&time=".$time);

				$link = site_url("/login/change_password/".$code);

				$this->db->where("email_address",$email);
				$this->db->update("user",[
					"link" => $link,
					"link_created" => $time
				]);

				$data['link'] = $link;
				$data['app_icon'] = $this->config->site_url("public/img/vehicle-checklist.png");
				$data['background'] = $this->config->site_url("public/img/reset-pass.jpg");

				$this->email->from('no-reply@trackerteer.com', 'Trackerteer | Vehicle Checklist');
				$this->email->to($email);
				$this->email->set_mailtype("html");
				$this->email->subject('Password Reset');
				$this->email->message($this->load->view('email/change_password_email', $data , true));

				if($this->email->send()){

					echo json_encode(["status" => true , "message" => "Change Password email sent", "action" => "forgot_password"]);
				}else{
					echo json_encode(["status" => false , "message" => "Sending email failed", "action" => "forgot_password"]);
				}				

			}else{

				echo json_encode(["status" => false , "message" => "Email doesn't exist", "action" => "forgot_password"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "Email is required", "action" => "forgot_password"]);
		}				
	}
}
