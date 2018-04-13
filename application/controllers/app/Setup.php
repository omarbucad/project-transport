<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends MY_Controller {

	public function __construct() {
       parent::__construct();
       $this->load->model('checklist_model', 'checklist');
       $this->load->model('profile_model', 'profile');
       $this->load->model('accounts_model', 'account');

    }
    // CHECKLIST SECTION
	public function checklist(){
		$this->form_validation->set_rules('checklist_name'		, 'Checklist Name'	, 'trim|required');

		if ($this->form_validation->run() == FALSE){ 
			$this->data['page_name'] = "Checklist";
			$this->data['result']    =  $this->checklist->get_checklist_list();
			$this->data['main_page'] = "backend/page/checklist/view";
			$this->load->view('backend/master' , $this->data);
		}else{

			if($checklist_id = $this->checklist->add_checklist_name()){
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Added a new Checklist');	

				redirect("app/setup/checklist/item/".$this->hash->encrypt($checklist_id) , 'refresh');
			}else{
				$this->session->set_flashdata('status' , 'error');
				$this->session->set_flashdata('message' , 'Something went wrong');	

				redirect("app/setup/checklist/" , 'refresh');
			}
		}
	}

	public function add_checklist_item($checklist_id){
		
		if (empty($this->input->post("item"))){ 
			$this->data['page_name'] = "Checklist Item";
			$this->data['main_page'] = "backend/page/checklist/add_item";
			$this->data['result'] = $this->checklist->get_checklist($checklist_id);
			$this->load->view('backend/master' , $this->data);
		}
		else{

			if($result = $this->checklist->add_checklist_item()){
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Added Checklist Items');	

				redirect("app/setup/checklist/?checklist_id=".$checklist_id, 'refresh');

			}else{
				$this->session->set_flashdata('status' , 'error');
				$this->session->set_flashdata('message' , 'Something went wrong');
			}
		}
		
	}

	public function edit_checklist($checklist_id){
		if (empty($this->input->post("items"))){ 
			$this->data['page_name'] = "Checklist Information";
			$this->data['main_page'] = "backend/page/checklist/edit";
			$this->data['result'] = $this->checklist->get_checklist($checklist_id);
			$this->data['checklist_items'] = $this->checklist->get_checklist_items($checklist_id);

			$this->load->view('backend/master' , $this->data);
		}
		else{
			if($result = $this->checklist->edit_checklist($checklist_id)){
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Updated Checklist');	

				redirect("app/setup/checklist/?checklist_id=".$checklist_id, 'refresh');

			}else{
				$this->session->set_flashdata('status' , 'error');
				$this->session->set_flashdata('message' , 'Something went wrong');
			}
		}
		
	}

	public function delete_checklist($checklist_id){
		$delete_checklist = $this->checklist->delete_checklist($checklist_id);
		if($delete_checklist){

			$this->session->set_flashdata('status' , 'success');	
			$this->session->set_flashdata('message' , 'Successfully Deleted Checklist');

			redirect("app/setup/checklist" , 'refresh');

		}
	}

	public function delete_checklist_item($item_id){
		$delete_item = $this->checklist->delete_checklist_item($item_id);
		if($delete_item){
			echo json_encode(["status" => true , "message" => "Checklist Item Deleted"]);
		}else{
			echo json_encode(["status" => false , "message" => "Something went wrong. Please try again."]);
		}
	}

	public function view_checklist($checklist_id){
		if($view_list = $this->checklist->get_checklist_items($checklist_id)){
			echo json_encode(["status" => true, "data" => $view_list]);
		}else{
			echo json_encode(["status" => false, "message" => "Something went wrong. Try again."]);
		}
	}
	// END OF CHECKLIST SECTION


	// PROFILE SECTION
	public function profile(){

		$user_id = $this->data['session_data']->user_id;

		$this->form_validation->set_rules('display_name'	, 'Display Name'	, 'trim|required');
		$this->form_validation->set_rules('first_name'		, 'First Name'		, 'trim|required');
		$this->form_validation->set_rules('last_name'		, 'Last Name'		, 'trim|required');
		$this->form_validation->set_rules('phone'			, 'Phone Number'	, 'trim|required');

		$this->form_validation->set_rules('physical[street1]'	, 'Street 1'	, 'trim|required');
		$this->form_validation->set_rules('physical[city]'		, 'City'	, 'trim|required');
		$this->form_validation->set_rules('physical[state]'		, 'State'	, 'trim|required');
		$this->form_validation->set_rules('physical[country]'	, 'Country'	, 'trim|required');

		if ($this->form_validation->run() == FALSE){ 
			$this->data['page_name'] = "Profile";
			$this->data['result']    =  $this->profile->get_profile();
			$this->data['main_page'] = "backend/page/profile/view";

			$this->load->view('backend/master' , $this->data);
		}else{
			if($update_profile = $this->profile->update_profile($user_id)){
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Updated Profile');

				redirect("app/setup/profile", 'refresh');
			}
			else{
				$this->session->set_flashdata('status' , 'error');
				$this->session->set_flashdata('message' , 'Something went wrong');	

				redirect("app/setup/profile" , 'refresh');
			}
		}
	}

	// END OF PROFILE SECTION


	// ACCOUNT SECTION
	public function account($type){
		$user_id = $this->data['session_data']->user_id;

		if($type == ""){
			redirect('/app/setup/account/manage', 'refresh');
		}

		$this->data['website_title'] = "Setup - Account | ".$this->data['application_name'];
		$this->data['page_name'] = "Account";
		$this->data['main_page'] = "backend/page/account/view";
		$this->data['result'] = $this->profile->get_userplan($user_id);
		$this->data['setup_page'] = $type; 
		$this->load->view('backend/master' , $this->data);
	}

	// END OF ACCOUNT SECTION
}