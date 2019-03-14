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
		
		// if($this->session->userdata('user')->expired == 1){
		// 	redirect("app/dashboard");
		// }
		// if($this->session->userdata('user')->role != 'ADMIN PREMIUM'){
		// 	redirect('app/dashboard');
		// }
		$user_id = $this->data['session_data']->user_id;

		$this->form_validation->set_rules('display_name'	, 'Display Name'	, 'trim|required');
		$this->form_validation->set_rules('firstname'		, 'First Name'		, 'trim|required');
		$this->form_validation->set_rules('lastname'		, 'Last Name'		, 'trim|required');
		$this->form_validation->set_rules('phone'			, 'Phone Number'	, 'trim|required');

		$this->form_validation->set_rules('physical[street1]'	, 'Street 1'	, 'trim|required');
		$this->form_validation->set_rules('physical[city]'		, 'City'	, 'trim|required');
		$this->form_validation->set_rules('physical[state]'		, 'State'	, 'trim|required');
		$this->form_validation->set_rules('physical[country]'	, 'Country'	, 'trim|required');
		$this->form_validation->set_rules('physical[countryCode]'	, 'Country'	, 'trim|required');

		if($this->input->post("password") != ""){
			$this->form_validation->set_rules('password'		    , 'Password'		  , 'trim|required|min_length[5]');
			$this->form_validation->set_rules('confirm_password'    , 'Confirm Password'  , 'trim|required|matches[password]');
		}	

		if ($this->form_validation->run() == FALSE){ 
			$this->data['page_name'] = "Profile";
			$this->data['result']    =  $this->profile->get_profile();
			$this->data['main_page'] = "backend/page/profile/view";
			$this->data['countries_list'] = $this->countries_list();

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
		echo json_encode(["token" => $this->braintree_lib->create_client_token(), "status" => "true", "message" => "OK"]);
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
				"paymentMethodToken" => $customer->customer[0]->token,
				"addOnId" => $_POST['add_on_id'],
				"quantity" => $_POST['vehicle_count']
			]);

			if($result->success){
				if($_POST['planId'] == 'sandbox_custom_monthly'){
					$billing = 'MONTHLY';
				}else{
					$billing = 'YEARLY';
				}
				$data = [
					"store_id" => $this->session->userdata("user")->store_id,
					"user_id" => $this->session->userdata("user")->user_id,
					"planId" => $_POST['planId'],
					"vehicle_limit" => $_POST['vehicle_count'],
					"subscription_id" => $result->subscription->id,
					"billing_type" => $billing
				];
				$this->update_subscription($data);
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Subscribed Successfully');

				redirect("app/setup/account/pricing", 'refresh');
			}else{
				$this->session->set_flashdata('status' , 'error');	
				$this->session->set_flashdata('message' , $result);

				redirect("app/setup/profile/pricing", 'refresh');
			}

		}

		if ($result->success === true) {
		    redirect('/app/setup/account/manage', 'refresh');
		}
		else{
			print_r($result->errors);
		    die();
		}

	}

	public function update_subscription($data){

		$this->db->trans_start();
        
        $this->db->where("store_id", $data['store_id']);
        $this->db->where("active", 1);
        $deactivated = $this->db->update("user_plan",[
            "active" => 0,
            "updated" => time(),
            "who_updated" => NULL
        ]);

        if($deactivated){
            if($data['billing_type'] == 'MONTHLY'){
                $billing = " + 1 month";
            }else if($data['billing_type'] == 'YEARLY'){
                $billing = " + 1 year";
            }else{
                $billing = "N/A";
            }
            $expiration = 0;
            $created = date("d/M/Y H:i:s A", time());

            $new = $this->db->insert("user_plan",[
                "store_id" => $data['store_id'],
                "plan_id" => $data['planId'],
                "subscription_id" => $data['subscription_id'],
                "vehicle_limit" => $data['vehicle_limit'],
                "plan_created" => strtotime(str_replace("/"," ",$created)),
                "plan_expiration" => ($billing == 'N/A') ? NULL : strtotime(trim(str_replace("/"," ",$created) . $billing)),
                "billing_type" => $data['billing_type'],
                "who_updated" => NULL,
                "active" => 1,
                "updated" => NULL
            ]);
            if($new){
            	$users_list = $this->db->where("store_id",$data['store_id'])->get("user")->result();

	            foreach ($users_list as $key => $value) {
	            	// if(($data->planId == 'custom_monthly') || ($data->planId == 'custom_yearly') || ($data->planId == 'premium_monthly') || ($data->planId == 'premium_yearly')){
	                if(($data['planId'] == 'sandbox_custom_monthly') || ($data['planId'] == 'sandbox_custom_yearly') || ($data['planId'] == 'sandbox_premium_monthly') || ($data['planId'] == 'sandbox_premium_yearly')){
	                    switch ($value->role) {
	                        case 'DRIVER':
	                            $this->db->where("user_id",$value->user_id)->update("user",["role" => "ADMIN PREMIUM"]);
	                            continue;
	                        case 'ADMIN FREE':
	                            $this->db->where("user_id",$value->user_id)->update("user",["role" => "ADMIN PREMIUM"]);
	                            continue;
	                    }
	                // }elseif($data->planId == 'free_trial'){
	                }elseif($data->planId == 'sandbox_free_trial'){
	                    switch ($value->role) {
	                        case 'DRIVER':
	                            $this->db->where("user_id",$value->user_id)->update("user",["role" => "ADMIN FREE"]);
	                            continue;
	                        // case 'DRIVER FREE':
	                        //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "DRIVER PREMIUM"]);
	                        //     continue;
	                    }
	                }elseif($data->planId == 'sandbox_basic_plan_trial_3'){
	                	switch ($value->role) {
	                        case 'DRIVER':
	                            $this->db->where("user_id",$value->user_id)->update("user",["role" => "ADMIN FREE"]);
	                            continue;
	                        // case 'DRIVER FREE':
	                        //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "DRIVER PREMIUM"]);
	                        //     continue;
	                    }
	                }else{
	                    switch ($value->role) {
	                        case 'ADMIN PREMIUM':
	                            $this->db->where("user_id",$value->user_id)->update("user",["role" => "DRIVER"]);
	                            continue;
	                        // case 'DRIVER PREMIUM':
	                        //     $this->db->where("user_id",$value->user_id)->update("user",["role" => "DRIVER FREE"]);
	                        //     continue;
	                    }
	                }
	            }
            }else{
            	return false;
            }            
        }
        $this->db->trans_complete();

        $this->db->select("u.user_id , u.username, u.display_name ,u.firstname, u.lastname, u.email_address , u.role , u.store_id , u.image_path , u.image_name, u.phone, u.verified");
        $this->db->select("s.store_name, s.logo_image_path, s.logo_image_name, a1.*, up.plan_expiration, up.user_plan_id, up.subscription_id, up.vehicle_limit, p.planId, p.title");

        $this->db->join("store s" , "s.store_id = u.store_id");
        $this->db->join("store_address a1" , "a1.store_address_id = s.address_id");
        $this->db->join("user_plan up" , "up.store_id = u.store_id");
        $this->db->join("plan p","p.planId = up.plan_id");
        $this->db->where("user_id",$data['user_id']);
        $this->db->where("up.active" , 1);
        $this->db->where("u.deleted IS NULL");
        $info = $this->db->get("user u")->row();
        if($info){
            if($info->logo_image_name != ''){
                if($info->logo_image_path == 'public/img/'){
                    $info->company_logo = $this->config->site_url($info->logo_image_path.$info->logo_image_name);
                }else{
                    $info->company_logo = $this->config->site_url("public/upload/company/".$info->logo_image_path.$info->logo_image_name);
                }                
            }
        }
        $this->session->set_userdata("user" , $info);
        
        if ($this->db->trans_status() === FALSE){
            return false;
        }else{
            return $info;
        }
	}
	// END OF ACCOUNT SECTION

	// 	NOTIFICATION SECTION
	public function notifications(){

		$this->data['page_name'] 		= "Notifications";
		$this->data['result']    		=  $this->notification->notify_list(true);
		$this->data['main_page'] 		= "backend/page/notification/view";

		$this->load->view('backend/master' , $this->data);
	
	}

	public function mark_all_read(){
		$this->db->where("user_id", $this->session->userdata("user")->user_id);
		$updated = $this->db->where("isread" , 0)->update("notification" , [ "isread" => 1 ]);

		if($updated){

			echo json_encode(["status" => true, "data" => "0", "message" => "OK"]);
		}else{
			echo json_encode(["status" => false, "message" => "Failed"]);
		}

	}

	public function read_notif(){
		$this->db->where("user_id", $this->session->userdata("user")->user_id);
		$updated = $this->db->where("id" , $this->input->post("id"))->update("notification" , [ "isread" => 1 ]);

		if($updated){

			echo json_encode(["status" => true, "message" => "OK"]);
		}else{
			echo json_encode(["status" => false, "message" => "Failed"]);
		}

	}
	// END OF NOTIFICATION SECTION
}
