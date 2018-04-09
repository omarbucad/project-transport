<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle extends MY_Controller {

	public function __construct() {
       parent::__construct();
       $this->load->model('vehicle_model', 'vehicle');

    }

	public function truck(){
		$this->data['page_name'] = "Truck";
		$this->data['result']    =  $this->vehicle->get_vehicle_list();
		$this->data['main_page'] = "backend/page/vehicle/truck/view";
		$this->load->view('backend/master' , $this->data);
	}

	public function add_truck(){
		

		$this->form_validation->set_rules('registration_number'		, 'Registration Number'	, 'trim|required');

		if ($this->form_validation->run() == FALSE){ 

			$this->data['page_name'] = "Add Truck Information";
			$this->data['main_page'] = "backend/page/vehicle/truck/add";
			$this->load->view('backend/master' , $this->data);

		}else{

			if($vehicle_id = $this->vehicle->add_truck()){
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Added a new Truck');	

				redirect("app/vehicle/truck/?vehicle_id=".$this->hash->encrypt($vehicle_id).'?submit=submit' , 'refresh');
			}else{
				$this->session->set_flashdata('status' , 'error');
				$this->session->set_flashdata('message' , 'Something went wrong');	

				redirect("app/vehicle/truck/add" , 'refresh');
			}
		}
	}

	public function trailer(){
		$this->data['page_name'] = "Trailer";
		$this->data['result']    =  $this->vehicle->get_trailer_list();
		$this->data['main_page'] = "backend/page/vehicle/trailer/view";
		$this->load->view('backend/master' , $this->data);
	}

	public function add_trailer(){

		$this->form_validation->set_rules('trailer_number'		, 'Trailer Number'	, 'trim|required');

		if ($this->form_validation->run() == FALSE){ 

			$this->data['page_name'] = "Add Trailer Information";
			$this->data['main_page'] = "backend/page/vehicle/trailer/add";
			$this->load->view('backend/master' , $this->data);

		}else{

			if($trailer_id = $this->vehicle->add_trailer()){
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Added a new Trailer');	

				redirect("app/vehicle/trailer/?trailer_id=".$this->hash->encrypt($trailer_id).'?submit=submit' , 'refresh');
			}else{
				$this->session->set_flashdata('status' , 'error');
				$this->session->set_flashdata('message' , 'Something went wrong');	

				redirect("app/vehicle/trailer/add" , 'refresh');
			}
		}
	}
}
