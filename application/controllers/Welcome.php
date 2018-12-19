<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {
	
	public function __construct() {
       parent::__construct();

       $this->load->model('register_model', 'register');
    }

	public function index(){
		$this->data["user_plans"] = $this->db->get("plan")->result();
		$this->load->view('frontend/index' , $this->data);
	}


	public function register(){

		$this->form_validation->set_rules('store_name'		, 'Store Name'			, 'trim|required');
		$this->form_validation->set_rules('first_name'		, 'First Name'			, 'trim|required');
		$this->form_validation->set_rules('last_name'		, 'Last Name'			, 'trim|required');
		$this->form_validation->set_rules('email_address'	, 'Email Address'		, 'trim|required|valid_email|is_unique[user.email_address]');
		$this->form_validation->set_rules('username'		, 'Username'			, 'trim|required|min_length[3]|max_length[15]|is_unique[user.username]');

		$this->form_validation->set_rules('password'		, 'Password'			, 'trim|required|min_length[5]');

		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');

		$this->form_validation->set_rules('city'			, 'City'				, 'trim|required');
		$this->form_validation->set_rules('country'			, 'Country'				, 'trim|required');
		$this->form_validation->set_rules('phone'			, 'Phone'				, 'trim|required');


		if ($this->form_validation->run() == FALSE){
			$this->data['website_title'] = "Sign up for free trial | ".$this->data['application_name'];
			$this->data['countries_list'] = $this->countries_list();
		}else{

			if($user_id = $this->register->signup()){
				
				$this->session->set_flashdata('status' , 'success');
				$this->session->set_flashdata('message' , 'Welcome to '.$this->data['application_name']);

				$user_data = $this->register->signin($user_id);

				$this->session->set_userdata("user" , $user_data);

				$role = $this->session->userdata('user')->role;
				if($role == "ADMIN"){
		    		if($this->session->userdata("user")->plan_expiration > strtotime("now")){
				    	$expired = false;
					}else{
						$expired = true;
					}
					$this->session->userdata('user')->expired = $expired;
			    	
			    }			

				redirect('/app/dashboard', 'refresh');
			}else{
				$this->session->set_flashdata('status' , 'error');
				redirect('/welcome/register', 'refresh');
			}

		}

		$this->load->view('frontend/register' , $this->data);
	}

	public function emailus(){
		$this->form_validation->set_rules('fullname'		, 'Full Name'		, 'trim|required');
		$this->form_validation->set_rules('email'			, 'Email address'	, 'trim|required');
		$this->form_validation->set_rules('message'			, 'Message'			, 'trim|required');

		if ($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('status' , 'error');	
			$this->session->set_flashdata('message' , validation_errors());

			redirect('/welcome#contactForm', 'refresh');
		}else{
			$email = $this->input->post("email");

			$info = $this->db->select("email_address")->where("email_address", $email)->get("user")->row();

			$data['fullname'] = $this->input->post("fullname");
			$data['email'] = $this->input->post("email");
			$data['message'] = $this->input->post("message");
			$this->email->from($email, 'Trackerteer | Vehicle Checklist');
			$this->email->to('cherlaj@trackerteer.com');
			$this->email->set_mailtype("html");
			$this->email->subject('Inquiry | Contact Us');
			$this->email->message($this->load->view('email/contactus_email', $data , true));

			if($this->email->send()){
				// $this->session->set_flashdata('status' , 'success');	
				// $this->session->set_flashdata('message' , 'Email has been sent.');

				echo json_encode(["status" => "success","message" => "Your message has been sent."]);
			}else{
				// $this->session->set_flashdata('status' , 'error');	
				// $this->session->set_flashdata('message' , 'Something went wrong. Please try again.');

				// redirect('?status=failed', 'refresh');
				echo json_encode(["status" => "error","message" => "Something went wrong. Please try again."]);
			}
		}
	}
}
