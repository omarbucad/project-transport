<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function __construct() {
       parent::__construct();
       $this->load->model('vehicle_model', 'vehicle');
       $this->load->model('accounts_model', 'accounts');
       $this->load->model('report_model', 'report');
       $this->load->model('invoice_model', 'invoice');

    }

	public function index(){
		$this->data['page_name'] = "Dashboard";
		$this->data['main_page'] = "backend/page/admin/dashboard";
		$this->data['session_data'] = $this->session->userdata('user');

		$this->load->view('backend/master' , $this->data);
	}
}
