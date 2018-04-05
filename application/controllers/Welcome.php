<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {
	
	public function __construct() {
       parent::__construct();

       $this->load->model('register_model', 'register');
    }

	public function index(){
		$this->load->view('frontend/index' , $this->data);
	}


	public function register(){

		$this->form_validation->set_rules('store_name'		, 'Store Name'			, 'trim|required');
		$this->form_validation->set_rules('first_name'		, 'First Name'			, 'trim|required');
		$this->form_validation->set_rules('first_name'		, 'Last Name'			, 'trim|required');
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

			if($data = $this->register->signup()){
				
				$this->session->set_flashdata('status' , 'success');
				$this->session->set_flashdata('message' , 'Welcome to '.$this->data['application_name']);

				$user_data = $this->register->signin($data['user_id']);

				$this->session->set_userdata("user" , $user_data);

				redirect('/app/welcome', 'refresh');
			}else{
				$this->session->set_flashdata('status' , 'error');
				redirect('/welcome/register', 'refresh');
			}

		}

		$this->load->view('frontend/register' , $this->data);
	}

}
