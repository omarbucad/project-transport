<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle extends MY_Controller {

	public function __construct() {
	    parent::__construct();
	    $this->load->model('vehicle_model', 'vehicle');
	    

    }
    public function index(){
		$this->data['page_name'] = "Vehicles";
		$this->data['result']    =  $this->vehicle->get_type_list();
		$this->data['main_page'] = "backend/page/vehicle/vehicle_type/view";
		$this->data['session_data'] = $this->session->userdata('user');
		
		$vehicle = count($this->vehicle->get_type_list());
		$this->load->view('backend/master' , $this->data);
	}

	public function add(){
		if($type = $this->vehicle->add_type()){
			$this->data['session_data'] = $this->session->userdata('user');
			$this->session->set_flashdata('status' , 'success');	
			$this->session->set_flashdata('message' , 'Successfully Created Type');	

			redirect("admin/vehicle/types", 'refresh');
		}else{
			$this->data['session_data'] = $this->session->userdata('user');
			$this->session->set_flashdata('status' , 'error');
			$this->session->set_flashdata('message' , 'Something went wrong');	

			redirect("admin/vehicle/types" , 'refresh');
		}
	}

	public function edit($id){
		if($type = $this->vehicle->edit_type($id)){
			$this->data['session_data'] = $this->session->userdata('user');
			$this->session->set_flashdata('status' , 'success');	
			$this->session->set_flashdata('message' , 'Successfully Updated Type');	

			redirect("admin/vehicle/types", 'refresh');
		}else{
			$this->data['session_data'] = $this->session->userdata('user');
			$this->session->set_flashdata('status' , 'error');
			$this->session->set_flashdata('message' , 'Something went wrong');	

			redirect("admin/vehicle/types" , 'refresh');
		}
	}


	public function delete($id){
		$id = $this->vehicle->delete_type($id);
		if($id){

			$this->session->set_flashdata('status' , 'success');	
			$this->session->set_flashdata('message' , 'Successfully Deleted');

			redirect("admin/vehicle/types" , 'refresh');

		}
	}

	public function get_type_info($id){
		$result = $this->vehicle->get_type_info($id);

		echo json_encode(["status"=>true,"data" => $result]);
	}

}
