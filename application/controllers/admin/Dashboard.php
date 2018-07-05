<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function __construct() {
       parent::__construct();
       $this->load->model('accounts_model', 'accounts');
       $this->load->model('invoice_model', 'invoice');

    }

	public function index(){
		$this->data['page_name'] = "Dashboard";
		$this->data['main_page'] = "backend/page/admin/dashboard";
		$this->data['session_data'] = $this->session->userdata('user');

		$this->data['config']["base_url"] = base_url("admin/dashboard/") ;
		$this->data['config']["total_rows"] = $this->accounts->get_admin_accounts(true);
		$this->pagination->initialize($this->data['config']);
		$this->data["links"] = $this->pagination->create_links();
		$this->data['result']	 = $this->accounts->plan_in_week();

		$this->load->view('backend/master' , $this->data);
	}
}
