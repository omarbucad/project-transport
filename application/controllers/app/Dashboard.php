<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	function __construct() {
       parent::__construct();
       $this->load->model('vehicle_model', 'vehicle');
       $this->load->model('accounts_model', 'accounts');
       $this->load->model('report_model', 'report');
       $this->data['notification_list'] = $this->notification->notify_list();

    }

	public function index(){
		// $n = convert_timezone(strtotime("now"), true, true,false,"m/d/Y h:i:sP");
  //       $now = new DateTime($n);
  //       $now->format("m/d/Y h:i:sP");
  //       print_r_die($now);
		// $bt = $this->braintree_lib->webhook_subscription();
		
		$this->data['page_name'] = "Dashboard";
		$this->data['main_page'] = "backend/page/dashboard/dashboard";
		$this->data['drivers'] = $this->accounts->get_driver();
		$this->data['trucks'] = $this->vehicle->get_vehicle_list();
		$this->data['available_trucks'] = $this->vehicle->get_available_vehicle();
		$this->data['unavailable_trucks'] = $this->vehicle->get_unavailable_vehicle();
		$this->data['reports_today'] = $this->report->get_today_reports();
		$this->data['defects_under_maintenance'] = $this->report->defect_reports();
		$this->data['fixed_today'] = $this->report->fixed_today_reports();
		$this->load->view('backend/master' , $this->data);
	}
}
