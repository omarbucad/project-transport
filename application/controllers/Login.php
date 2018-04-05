<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {
	public function __construct() {
       parent::__construct();
       $this->load->helper('cookie');
       $this->load->model('register_model', 'register');

    }
	public function index(){

		if($this->input->get("store") == "change"){
			delete_cookie('store_id');
			delete_cookie('store_name');
			
			$this->data['cookie_outlet'] = false;

		}else{
			$this->data['cookie_outlet'] = ($this->input->cookie("store_id")) ? $this->input->cookie() : false;
		}

		
		$this->load->view('frontend/login' , $this->data);
	}
	public function forgot_password(){
		echo "FORGOT PASSWORD";
	}
	public function do_login(){

		if($data = $this->register->signin($this->input->post())){
			$data->currency_symbol = $this->world_currency_symbol($data->default_currency);
			$this->session->set_userdata("user" , $data);
			$this->session->set_flashdata('status' , 'success');
			redirect('/app/welcome', 'refresh');

		}else{
			$this->session->set_flashdata('status' , 'failed');
			$this->session->set_flashdata('message' , 'Incorrect Username or Password');

			redirect('/login', 'refresh');
		}
	}
	public function set_store_name(){
		if($store_data = $this->register->checkStoreName($this->input->post("store_name"))){

			$this->input->set_cookie("store_id" , $store_data['store_id'] , COOKIE_EXPIRE , DOMAIN_IP);
			$this->input->set_cookie("store_name" , $store_data['store_name'] , COOKIE_EXPIRE , DOMAIN_IP);

			redirect('/login', 'refresh');

		}else{

			$this->session->set_flashdata('status' , 'failed');
			$this->session->set_flashdata('message' , 'Incorrect Store Name');

			redirect('/login', 'refresh');
		}
	}
	public function logout(){
		$this->session->set_flashdata('status' , 'success');
		$this->session->set_flashdata('message' , 'You have been Logout');

		redirect('/login', 'refresh');
	}
}
