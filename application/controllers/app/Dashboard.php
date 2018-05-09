<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function __construct() {
       parent::__construct();
       $this->load->model('vehicle_model', 'vehicle');
       $this->load->model('accounts_model', 'accounts');
       $this->load->model('report_model', 'report');

    }

	public function index(){

		$this->data['page_name'] = "Dashboard";
		$this->data['main_page'] = "backend/page/dashboard/dashboard";
		$this->data['drivers'] = $this->accounts->get_driver();
		$this->data['trailers'] = $this->vehicle->get_trailer_list();
		$this->data['active_trailers'] = $this->vehicle->get_activetrailer();
		$this->data['trucks'] = $this->vehicle->get_vehicle_list();
		$this->data['active_trucks'] = $this->vehicle->get_activetruck();
		$this->data['reports_today'] = $this->report->get_today_reports();
		$this->data['defects_under_maintenance'] = $this->report->defect_undermaintenance_reports();
		$this->data['fixed_today'] = $this->report->fixed_today_reports();

		$this->load->view('backend/master' , $this->data);
	}
}
