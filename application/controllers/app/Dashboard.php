<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function index(){

		//print_r_die($this->data['session_data'] );
		$this->data['page_name'] = "Dashboard";
		$this->data['main_page'] = "backend/page/dashboard/dashboard";
		$this->load->view('backend/master' , $this->data);
	}
}
