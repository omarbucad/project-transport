<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends MY_Controller {

	public function __construct() {
       parent::__construct();
       $this->load->model('invoice_model', 'invoice');
       $this->load->model('accounts_model', 'accounts');

    }
	public function index(){
		$this->data['role'] = $this->session->userdata('user')->role;
		$this->data['session_data'] = $this->session->userdata('user');
		$this->data['page_name'] = "Transport Accounts";
		$this->data['main_page'] = "backend/page/admin/invoice";

		//PAGINATION
		$this->data['config']["base_url"] = base_url("admin/invoice/") ;
		$this->data['config']["total_rows"] = $this->accounts->get_admin_accounts(true);
		$this->pagination->initialize($this->data['config']);
		$this->data["links"] = $this->pagination->create_links();

		$this->data['result']	 = $this->accounts->get_admin_accounts();
		$driver = count($this->accounts->get_driver());
		$mechanic = count($this->accounts->get_mechanic());
		$admin = count($this->accounts->get_admin());
		$this->data['plan_type']		= $this->data['session_data']->plan_type;
		$this->data['total_accounts']	= $driver + $mechanic + $admin;

		$this->load->view('backend/master' , $this->data);
	}
}
