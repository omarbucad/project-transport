<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle extends MY_Controller {

	public function __construct() {
	    parent::__construct();
	    $this->load->model('vehicle_model', 'vehicle');
	    
	    $this->data['plan_type'] = $this->data['session_data']->title;
	    if($this->session->userdata('user')->role != "ADMIN PREMIUM" && $this->session->userdata('user')->role != "MANAGER" ){
			redirect("app/dashboard");					
		}
		if($this->session->userdata('user')->expired == 1){
			redirect("app/dashboard");
		}

    }
    public function index(){
    	
		$this->data['page_name'] = "Vehicles";
		$this->data['result']    =  $this->vehicle->get_vehicle_list();
		$this->data['main_page'] = "backend/page/vehicle/truck/view";
		
		$vehicle = count($this->vehicle->get_vehicle_list());
		$this->data['types'] = $this->vehicle->vehicle_type_list();
		$this->data['totalvehicle'] = $vehicle;
		$this->load->view('backend/master' , $this->data);
	}

	// public function truck(){
	// 	$this->data['page_name'] = "Truck";
	// 	$this->data['result']    =  $this->vehicle->get_vehicle_list();
	// 	$this->data['main_page'] = "backend/page/vehicle/truck/view";
		
	// 	$vehicle = count($this->vehicle->get_vehicle_list());
	// 	$trailer = count($this->vehicle->get_trailer_list());
	// 	$this->data['totalvehicle'] = $vehicle + $trailer;
	// 	$this->load->view('backend/master' , $this->data);
	// }
	public function add(){
		

		$this->form_validation->set_rules('registration_number'		, 'Registration Number'	, 'trim|required');

		if($this->input->post("type") == ""){
			$this->form_validation->set_rules('type'		, 'Vehicle'	, 'trim|required');
		}

		if ($this->form_validation->run() == FALSE){ 

			$this->data['page_name'] = "Vehicles";
			$this->data['result']    =  $this->vehicle->get_vehicle_list();
			$this->data['main_page'] = "backend/page/vehicle/truck/view";
			
			$vehicle = count($this->vehicle->get_vehicle_list());
			$this->data['types'] = $this->vehicle->vehicle_type_list();
			$this->data['totalvehicle'] = $vehicle;
			$this->load->view('backend/master' , $this->data);

		}else{

			if($vehicle_id = $this->vehicle->add_truck()){
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Added Successfully');	

				redirect("app/vehicle/", 'refresh');
			}else{
				$this->session->set_flashdata('status' , 'error');
				$this->session->set_flashdata('message' , 'Something went wrong');	

				redirect("app/vehicle/" , 'refresh');
			}
		}
	}

	// public function add_truck(){
		

	// 	$this->form_validation->set_rules('registration_number'		, 'Registration Number'	, 'trim|required');

	// 	if ($this->form_validation->run() == FALSE){ 

	// 		$this->data['page_name'] = "Add Truck Information";
	// 		$this->data['main_page'] = "backend/page/vehicle/truck/add";
	// 		$this->load->view('backend/master' , $this->data);

	// 	}else{

	// 		if($vehicle_id = $this->vehicle->add_truck()){
	// 			$this->session->set_flashdata('status' , 'success');	
	// 			$this->session->set_flashdata('message' , 'Successfully Added a new Truck');	

	// 			redirect("app/vehicle/truck/?vehicle_id=".$this->hash->encrypt($vehicle_id), 'refresh');
	// 		}else{
	// 			$this->session->set_flashdata('status' , 'error');
	// 			$this->session->set_flashdata('message' , 'Something went wrong');	

	// 			redirect("app/vehicle/truck/add" , 'refresh');
	// 		}
	// 	}
	// }

	public function get_vehicle_info($vehicle_id){
		if($vehicle = $this->vehicle->get_truck($vehicle_id)){
			echo json_encode(["status" => true,"data" => $vehicle]);
		}else{
			echo json_encode(["status" => false, "message" => "No data available"]);
		}
	}
	public function edit($vehicle_id){
		
		if($vehicle_id = $this->vehicle->edit_truck($vehicle_id)){
			$this->session->set_flashdata('status' , 'success');	
			$this->session->set_flashdata('message' , 'Successfully Updated Truck');	

			redirect("app/vehicle/", 'refresh');
		}else{
			$this->session->set_flashdata('status' , 'error');
			$this->session->set_flashdata('message' , 'Something went wrong');	

			redirect("app/vehicle/" , 'refresh');
		}
	}

	// public function edit_truck($vehicle_id){
	// 	$this->form_validation->set_rules('vehicle_registration_number'	, 'Vehicle Registration Number'	, 'trim|required');
	// 	$this->form_validation->set_rules('description'	, 'Description'	, 'trim|required');
	// 	$this->form_validation->set_rules('status'		, 'Status'	, 'trim|required');

	// 	if ($this->form_validation->run() == FALSE){ 

	// 		$this->data['page_name'] = "Edit Truck Information";
	// 		$this->data['main_page'] = "backend/page/vehicle/truck/edit";
	// 		$this->data['result'] = $this->vehicle->get_truck($vehicle_id);
	// 		$this->load->view('backend/master' , $this->data);

	// 	}else{

	// 		if($vehicle_id = $this->vehicle->edit_truck($vehicle_id)){
	// 			$this->session->set_flashdata('status' , 'success');	
	// 			$this->session->set_flashdata('message' , 'Successfully Updated Truck');	

	// 			redirect("app/vehicle/truck/?vehicle_id=".$this->hash->encrypt($vehicle_id), 'refresh');
	// 		}else{
	// 			$this->session->set_flashdata('status' , 'error');
	// 			$this->session->set_flashdata('message' , 'Something went wrong');	

	// 			redirect("app/vehicle/truck/edit" , 'refresh');
	// 		}
	// 	}
	// }
	public function delete($vehicle_id){
		$delete_truck = $this->vehicle->delete_truck($vehicle_id);
		if($delete_truck){

			$this->session->set_flashdata('status' , 'success');	
			$this->session->set_flashdata('message' , 'Successfully Deleted Truck');

			redirect("app/vehicle" , 'refresh');

		}else{
			$this->session->set_flashdata('status' , 'error');	
			$this->session->set_flashdata('message' , 'Something went wrong');

			redirect("app/vehicle" , 'refresh');
		}
	}

	// public function delete_truck($vehicle_id){
	// 	$delete_truck = $this->vehicle->delete_truck($vehicle_id);
	// 	if($delete_truck){

	// 		$this->session->set_flashdata('status' , 'success');	
	// 		$this->session->set_flashdata('message' , 'Successfully Deleted Truck');

	// 		redirect("app/vehicle/trailer" , 'refresh');

	// 	}
	// }
	// public function trailer(){
	// 	$this->data['page_name'] = "Trailer";
	// 	$this->data['result']    =  $this->vehicle->get_trailer_list();
	// 	$this->data['main_page'] = "backend/page/vehicle/trailer/view";
	// 	$vehicle = count($this->vehicle->get_vehicle_list());
	// 	$trailer = count($this->vehicle->get_trailer_list());
	// 	$this->data['totalvehicle'] = $vehicle + $trailer;
	// 	$this->load->view('backend/master' , $this->data);
	// }

	// public function add_trailer(){

	// 	$this->form_validation->set_rules('trailer_number'		, 'Trailer Number'	, 'trim|required');

	// 	if ($this->form_validation->run() == FALSE){ 

	// 		$this->data['page_name'] = "Add Trailer Information";
	// 		$this->data['main_page'] = "backend/page/vehicle/trailer/add";
	// 		$this->load->view('backend/master' , $this->data);

	// 	}else{

	// 		if($trailer_id = $this->vehicle->add_trailer()){
	// 			$this->session->set_flashdata('status' , 'success');	
	// 			$this->session->set_flashdata('message' , 'Successfully Added a new Trailer');	

	// 			redirect("app/vehicle/trailer/?trailer_id=".$this->hash->encrypt($trailer_id), 'refresh');
	// 		}else{
	// 			$this->session->set_flashdata('status' , 'error');
	// 			$this->session->set_flashdata('message' , 'Something went wrong');	

	// 			redirect("app/vehicle/trailer/add" , 'refresh');
	// 		}
	// 	}
	// }

	// public function edit_trailer($trailer_id){
	// 	$this->form_validation->set_rules('trailer_number'		, 'Trailer Number'	, 'trim|required');
	// 	$this->form_validation->set_rules('description'		, 'Description'	, 'trim|required');
	// 	$this->form_validation->set_rules('status'		, 'Status'	, 'trim|required');

	// 	if ($this->form_validation->run() == FALSE){ 

	// 		$this->data['page_name'] = "Edit Trailer Information";
	// 		$this->data['main_page'] = "backend/page/vehicle/trailer/edit";
	// 		$this->data['result'] = $this->vehicle->get_trailer($trailer_id);
	// 		$this->load->view('backend/master' , $this->data);

	// 	}else{

	// 		if($trailer_id = $this->vehicle->edit_trailer($trailer_id)){
	// 			$this->session->set_flashdata('status' , 'success');	
	// 			$this->session->set_flashdata('message' , 'Successfully Updated Trailer');	

	// 			redirect("app/vehicle/trailer/?trailer_id=".$this->hash->encrypt($trailer_id), 'refresh');
	// 		}else{
	// 			$this->session->set_flashdata('status' , 'error');
	// 			$this->session->set_flashdata('message' , 'Something went wrong');	

	// 			redirect("app/vehicle/trailer/edit" , 'refresh');
	// 		}
	// 	}
	// }

	// public function delete_trailer($trailer_id){
	// 	$delete_trailer = $this->vehicle->delete_trailer($trailer_id);
	// 	if($delete_trailer){

	// 		$this->session->set_flashdata('status' , 'success');	
	// 		$this->session->set_flashdata('message' , 'Successfully Deleted Trailer');

	// 		redirect("app/vehicle/trailer" , 'refresh');

	// 	}
	// }

	// Vehicle Type
	// public function type(){
	// 	$this->form_validation->set_rules('name'		, 'Vehicle Type'	, 'trim|required');

	// 	if ($this->form_validation->run() == FALSE){ 
	// 		$this->data['page_name'] = "Vehicle Type";
	// 		$this->data['result']    =  $this->vehicle->get_type_list();
	// 		$this->data['main_page'] = "backend/page/vehicle/vehicle_type/view";
	// 		$this->load->view('backend/master' , $this->data);
	// 	}else{

	// 		if($type_id = $this->vehicle->add_type()){
	// 			$this->session->set_flashdata('status' , 'success');	
	// 			$this->session->set_flashdata('message' , 'Successfully Added a new Vehicle Type');	

	// 			redirect("app/vehicle/type/?type_id=".$this->hash->encrypt($type_id), 'refresh');
	// 		}else{
	// 			$this->session->set_flashdata('status' , 'error');
	// 			$this->session->set_flashdata('message' , 'Something went wrong');	

	// 			redirect("app/vehicle/type/" , 'refresh');
	// 		}
	// 	}
	// }

	// public function edit_type($id){
	// 	$this->form_validation->set_rules('name'		, 'Vehicle Type'	, 'trim|required');

	// 	if ($this->form_validation->run() == FALSE){ 
	// 		$this->data['page_name'] = "Truck";
	// 		$this->data['result']    =  $this->vehicle->get_type_info($id);
	// 		$this->data['main_page'] = "backend/page/vehicle/vehicle_type/edit";
	// 		$this->load->view('backend/master' , $this->data);
	// 	}else{

	// 		if($this->vehicle->edit_type($id)){
	// 			$this->session->set_flashdata('status' , 'success');	
	// 			$this->session->set_flashdata('message' , 'Successfully Updated Vehicle Type');	

	// 			redirect("app/vehicle/type/?type_id=".$id, 'refresh');
	// 		}else{
	// 			$this->session->set_flashdata('status' , 'error');
	// 			$this->session->set_flashdata('message' , 'Something went wrong');	

	// 			redirect("app/vehicle/type/" , 'refresh');
	// 		}
	// 	}
	// }
}
