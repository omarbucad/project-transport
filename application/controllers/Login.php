<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {
	public function __construct() {
       parent::__construct();
       $this->load->helper('cookie');
       $this->load->model('register_model', 'register');

    }
	public function index(){
		
		$this->load->view('frontend/login');
	}
	public function forgot_password(){
		echo "FORGOT PASSWORD";
	}
	public function do_login(){

		if($data = $this->register->signin($this->input->post())){
			$this->session->set_userdata("user" , $data);

			$this->session->set_flashdata('status' , 'success');
			redirect('/app/dashboard', 'refresh');

		}else{
			$this->session->set_flashdata('status' , 'failed');
			$this->session->set_flashdata('message' , 'Incorrect Username or Password');

			redirect('/login', 'refresh');
		}
	}
	
	public function logout(){
		$this->session->set_flashdata('status' , 'success');
		$this->session->set_flashdata('message' , 'You have been Logout');

		redirect('/login', 'refresh');
	}
}
