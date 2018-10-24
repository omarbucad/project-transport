<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {
	public function __construct() {
       parent::__construct();
       $this->load->model('register_model', 'register');

    }
	public function index(){
		
		$this->load->view('frontend/login' , $this->data);
	}
	public function do_login(){

		if($data = $this->register->signin($this->input->post())){
			$this->session->set_userdata("user" , $data);
			$role = $this->session->userdata('user')->role;
			// $ex = convert_timezone($this->session->userdata("user")->plan_expiration, true,true);
		 //    $exp = new DateTime($ex);
		 //    $expiration = $exp->format('M d Y H:i:s');
		 //    $today = convert_timezone(strtotime(date("Y-m-d H:i:s")) , true ,true);
		 //    $timezone = get_timezone();
		 //    $dt = new DateTime($today, new DateTimeZone( $timezone ));
		 //    $now = $dt->format('M d Y H:i:s');

		    if($role == "ADMIN PREMIUM" || $role == "MECHANIC"){
		    	if($this->session->userdata("user")->plan->expiration == ''){
		    		$expired = 0;
		    	}else{
		    		if($this->session->userdata("user")->plan_expiration > strtotime("now")){
				    	$expired = 0;
					}else{
						$expired = 1;
					}
		    	}
	    		
				$this->session->userdata('user')->expired = $expired;
		    	
		    }			

			if($role == "SUPER ADMIN"){
				$this->session->set_flashdata('status' , 'success');
				redirect('/admin/dashboard', 'refresh');
			}elseif($role == "DRIVER PREMIUM" || $role == "DRIVER FREE" ){
				$this->session->set_flashdata('status' , 'failed');
				$this->session->set_flashdata('message' , 'Driver account is not allowed.');

				redirect('/login', 'refresh');
			}else{

				$this->session->set_flashdata('status' , 'success');
				redirect('/app/dashboard', 'refresh');
			}

		}else{
			$this->session->set_flashdata('status' , 'failed');
			$this->session->set_flashdata('message' , 'Incorrect Username or Password');

			redirect('/login', 'refresh');
		}
	}
	
	public function logout(){
		$this->session->set_flashdata('status' , 'success');
		$this->session->set_flashdata('message' , 'You have been Logged out');

		redirect('/login', 'refresh');
	}

	public function forgot_password(){

		$this->form_validation->set_rules('email', 'Email', 'trim|required');

		if ($this->form_validation->run() == FALSE){ 

			$this->data['title_page'] = "Vehicle Checklist | Forgotten Password";

			$this->load->view('frontend/forgot_password' , $this->data);

		}else{
			$email = $this->input->post("email");

			$info = $this->db->select("email_address")->where("email_address", $email)->get("user")->row();

			if($info){

					$code = $this->hash->encrypt($email);
					$link = site_url("/login/change_password/".$code);					

					$data['link'] = $link;

					$this->email->from('no-reply@trackerteer.com', 'Trackerteer Inc');
					$this->email->to($email);
					$this->email->set_mailtype("html");
					$this->email->subject('Transport - Password Reset');
					$this->email->message($this->load->view('email/change_password_email', $data , true));

					$this->email->send();

					$this->session->set_flashdata('status' , 'success');	
					$this->session->set_flashdata('message' , 'Change Password Email Sent. Please check your email.');

					redirect('/login/forgot_password/?status=emailsent', 'refresh');

			}else{

					$this->session->set_flashdata('status' , 'error');	
					$this->session->set_flashdata('message' , 'The email doesn\'t exist.');

					redirect('/login/forgot_password/?error=email_not_registered', 'refresh');
			}

		}
	}

	public function change_password($code){

		if($code){
			$email = $this->hash->decrypt($code);

			$this->form_validation->set_rules('password'		, 'Password'	    , 'trim|required|min_length[5]');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');

			if ($this->form_validation->run() == FALSE){ 
				$this->data['code'] = $code;
				$this->data['customer_email'] = $email;
				$this->data['title_page'] = "Change Password";

				$this->load->view('frontend/change_password' , $this->data);

			}else{

				$result = $this->db->select("email_address")->where("email_address" , $email)->get("user")->row();

				if($result){
					$this->db->where("email_address" , $result->email_address)->update("user" , [
						"password" => md5($this->input->post("password"))
					]);
					$this->session->set_flashdata('status' , 'success');	
					$this->session->set_flashdata('message' , 'Successfully Changed Password');

					redirect("/login/change_password/$code?success=change_password", "refresh");
				}			
			}
		}else{
			$this->session->set_flashdata('status' , 'error');	
			$this->session->set_flashdata('message' , 'Expired Link. Please Enter Your Email Address to Receive a Change Password Email');

			redirect("/login/forgot_password/?error=expired_link", "refresh");
		}
	}
}
