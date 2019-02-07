<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends MY_Controller {

	function __construct() {
	    parent::__construct();

    	ini_set('max_execution_time',7000);
    	$this->data['notification_list'] = $this->notification->notify_list();
    	
	    $this->load->model('checklist_model', 'checklist');
	    $this->load->model('vehicle_model', 'vehicle');
	    $this->load->model('profile_model', 'profile');
	    $this->load->model('accounts_model', 'account');
	    if($this->session->userdata('user')->role != "ADMIN PREMIUM" && $this->session->userdata('user')->role != "MANAGER" && $this->session->userdata('user')->role != "SUPER ADMIN"){
			redirect("app/dashboard");					
		}

    }
    // CHECKLIST SECTION
	public function checklist(){
		if($this->session->userdata('user')->role != "SUPER ADMIN"){
				if(!(isset($this->session->userdata('user')->expired) && !$this->session->userdata('user')->expired)){
				redirect("app/dashboard");
			}
		}

		$this->data['page_name'] 		= "Checklist";
		$this->data['result']    		=  $this->checklist->get_checklist_list();
		$this->data['checklist_list']    		=  $this->checklist->get_checklist_dropdown();
		$this->data['accounts_list']    =  $this->checklist->get_mech_and_driver_list();
		$this->data['main_page'] 		= "backend/page/checklist/view";
		$this->data['plan_type']		= $this->data['session_data']->title;
		$this->data['types'] = $this->vehicle->vehicle_type_list();

		$this->load->view('backend/master' , $this->data);
	}

	public function add_checklist(){

		if($this->input->post()){

			$this->form_validation->set_rules('checklist_name'	, 'Checklist Name'	, 'trim|required');
			if($this->input->post('type') == ''){
				$this->form_validation->set_rules('type'	, 'Vehicle Type'	, 'trim|required');	
			}
			
			$this->form_validation->set_rules('item[name][0]'	, 'Item'	, 'trim|required');

			if ($this->form_validation->run() == FALSE){ 
				$this->data['page_name'] = "Checklist Item";
				$this->data['main_page'] = "backend/page/checklist/add_item";
				$this->data['accounts_list']    =  $this->checklist->get_mech_and_driver_list();
				$this->data['post']		 = $this->input->post();
				$this->data['types'] = $this->vehicle->vehicle_type_list();
				$this->load->view('backend/master' , $this->data);
			}else{

				if($checklist_id = $this->checklist->add_checklist_item()){
					$this->session->set_flashdata('status' , 'success');	
					$this->session->set_flashdata('message' , 'Successfully Added Checklist Items');	

					redirect("app/setup/checklist/?checklist_id=".$this->hash->encrypt($checklist_id), 'refresh');

				}else{
					$this->session->set_flashdata('status' , 'error');
					$this->session->set_flashdata('message' , 'Something went wrong');

					redirect("app/setup/checklist/add", 'refresh');
				}
			}

			
		}else{
			redirect("app/setup/checklist/" , 'refresh');
		}
		
	}

	public function add_checklist_item($checklist_id){
		
		if (empty($this->input->post("item"))){ 
			$this->data['page_name'] = "Checklist Item";
			$this->data['main_page'] = "backend/page/checklist/add_item";
			$this->data['result'] = $this->checklist->get_checklist($checklist_id);
			$this->data['types'] = $this->vehicle->vehicle_type_list();
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

			$this->form_validation->set_rules('checklist_name'	, 'Checklist Name'	, 'trim|required');
			if($this->input->post('type') == ''){
				$this->form_validation->set_rules('type'	, 'Vehicle Type'	, 'trim|required');	
			}

			if($this->form_validation->run() == FALSE){ 
				$this->data['page_name'] = "Checklist Information";
				$this->data['main_page'] = "backend/page/checklist/edit";
				$this->data['result'] = $this->checklist->get_checklist($checklist_id);
				$this->data['accounts_list']    =  $this->checklist->get_mech_and_driver_list();
				$this->data['user_checklist'] = $this->checklist->get_userchecklist($checklist_id);
				$this->data['checklist_items'] = $this->checklist->get_checklist_items($checklist_id);
				$this->data['types'] = $this->vehicle->vehicle_type_list();

				$this->load->view('backend/master' , $this->data);
			}else{

       			//print_r_die($this->input->post());
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

	public function delete_item_image($item_id){
		$delete_item = $this->checklist->delete_item_image($item_id);
		if($delete_item){
			echo json_encode(["status" => true , "message" => "Checklist Item Image Deleted"]);
		}else{
			echo json_encode(["status" => false , "message" => "Something went wrong. Please try again."]);
		}
	}

	public function delete_item_helpimage($item_id){
		$delete_item = $this->checklist->delete_help_image($item_id);
		if($delete_item){
			echo json_encode(["status" => true , "message" => "Checklist Item Help Image Deleted"]);
		}else{
			echo json_encode(["status" => false , "message" => "Something went wrong. Please try again."]);
		}
	}


	public function upload_helpimage($item_id){
		$upload = $this->checklist->upload_item_help_image($item_id);
		if($upload){
			$this->session->set_flashdata('status' , 'success');	
			$this->session->set_flashdata('message' , 'Successfully Uploaded');

			redirect('app/setup/checklist/edit/'.$this->input->post('checklistid'));
		}else{
			$this->session->set_flashdata('status' , 'error');	
			$this->session->set_flashdata('message' , 'Upload failed. Try again');

			redirect('app/setup/checklist/edit/'.$this->input->post('checklistid'));
		}
	}

	public function upload_item_image($item_id){
		$upload = $this->checklist->upload_item_image($item_id);
		if($upload){
			$this->session->set_flashdata('status' , 'success');	
			$this->session->set_flashdata('message' , 'Successfully Uploaded');
			
			redirect('app/setup/checklist/edit/'.$this->input->post('checklistid'));
		}else{
			$this->session->set_flashdata('status' , 'error');	
			$this->session->set_flashdata('message' , 'Upload failed. Try again');

			redirect('app/setup/checklist/edit/'.$this->input->post('checklistid'));
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
		
		if($this->session->userdata('user')->expired == 1){
			redirect("app/dashboard");
		}
		if($this->session->userdata('user')->role != 'ADMIN PREMIUM'){
			redirect('app/dashboard');
		}
		$user_id = $this->data['session_data']->user_id;

		$this->form_validation->set_rules('display_name'	, 'Display Name'	, 'trim|required');
		$this->form_validation->set_rules('first_name'		, 'First Name'		, 'trim|required');
		$this->form_validation->set_rules('last_name'		, 'Last Name'		, 'trim|required');
		$this->form_validation->set_rules('phone'			, 'Phone Number'	, 'trim|required');

		$this->form_validation->set_rules('physical[street1]'	, 'Street 1'	, 'trim|required');
		$this->form_validation->set_rules('physical[city]'		, 'City'	, 'trim|required');
		$this->form_validation->set_rules('physical[state]'		, 'State'	, 'trim|required');
		$this->form_validation->set_rules('physical[country]'	, 'Country'	, 'trim|required');

		if($this->input->post("password") != ""){
			$this->form_validation->set_rules('password'		    , 'Password'		  , 'trim|required|min_length[5]');
			$this->form_validation->set_rules('confirm_password'    , 'Confirm Password'  , 'trim|required|matches[password]');
		}	

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
		if($this->session->userdata('user')->role != "ADMIN PREMIUM"){
			redirect("app/dashboard");					
		}
		$user_id = $this->data['session_data']->user_id;

		$this->data['website_title'] = "Setup - Plan | ".$this->data['application_name'];
		$this->data['page_name'] = "Plan";
		$this->data['main_page'] = "backend/page/account/view";
		$this->data['result'] = $this->profile->get_userplan($user_id);
		$this->data['user_data'] = $this->account->user_data($user_id);

		$this->data['setup_page'] = $type;
		$this->data['user_plans'] = $this->db->get("plan")->result();
		$this->data['plan_ids'] = $this->braintree_lib->getAllPlan();

		//print_r_die($this->session->userdata("user"));

		if($type == ""){
			redirect('/app/setup/account/manage', 'refresh');
		}
		$this->load->view('backend/master' , $this->data);
	}

	public function get_client_token(){
		echo json_encode($this->braintree_lib->create_client_token());
	}

	public function pay(){
		$data = $this->session->userdata("user");
		if(empty($_POST['payment_method_nonce'])){
			header('location: '.site_url('app/setup/account/pricing'));

			$this->session->set_flashdata('status' , 'error');
			$this->session->set_flashdata('message' , 'Something went wrong');	

			redirect("app/setup/profile" , 'refresh');

		}
		$details = [
			"username" => $data->username,
			"firstname" => $data->firstname,
			"lastname" => $data->lastname,
			"email" => $data->email_address,
			"company" => $data->store_name,
			"phone" => $data->phone,
			"paymentMethodNonce" => $_POST['payment_method_nonce']
		];
		$customer = $this->braintree_lib->web_customer_data($details);

		if(!empty($customer)){
			$result = $this->braintree_lib->create_websubscription_token([
				"planId" => $_POST['planId'],
				"paymentMethodToken" => $customer->customer[0]->token
			]);

			//print_r_die($result);
			if($result->success){
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Subscribed Successfully');

				redirect("app/setup/account/pricing", 'refresh');
			}else{
				$this->session->set_flashdata('status' , 'error');	
				$this->session->set_flashdata('message' , $result);

				redirect("app/setup/profile/pricing", 'refresh');
			}

		}

		// $result = Braintree_Transaction::sale([
		//   'amount' => $_POST['amount'], 
		//   'paymentMethodNonce' => $_POST['payment_method_nonce'],
		//   'customer' => [
		//     'firstName' => $data->firstname,
		//     'lastName' => $data->lastname,    
		//   ], 
		//   'options' => [
		//     'submitForSettlement' => true
		//   ]
		// ]);

		if ($result->success === true) {
		    redirect('/app/setup/account/manage', 'refresh');
		}
		else{
			print_r($result->errors);
		    die();
		}

	}
	// END OF ACCOUNT SECTION
}
