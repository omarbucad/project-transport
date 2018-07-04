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

        //kapag ionic ito ung gamitin
		//$this->post = json_decode(file_get_contents("php://input"));

		//kapag hindi
		$this->post = (object) $this->input->post();
	}

	public function signin(){

		$username = $this->post->username;
		$password = $this->post->password;

		if($this->post){
			if($data = $this->register->signin(["username" => $username , "password" => $password])){				

				if($data->image_name == "person-placeholder.jpg"){
					$data->image = "assets/imgs/person-placeholder.jpg";
				}else{
					$data->image = $this->config->site_url("thumbs/images/user/".$data->image_path."/80/80/".$data->image_name);
				}
				

				echo json_encode(["status" => true , "data" => $data]);
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
				"message" 	=> $msg
		]);
	}
}
