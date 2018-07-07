<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends MY_Controller {

	public function __construct() {
       parent::__construct();
       $this->load->model('accounts_model', 'accounts');
       $this->load->model('checklist_model', 'checklist');

    }
	public function index(){
		$this->data['role'] = $this->session->userdata('user')->role;

		$this->data['page_name'] = "Transport Accounts";
		$this->data['main_page'] = "backend/page/users/view";

		//PAGINATION
		$this->data['config']["base_url"] = base_url("app/accounts/") ;
		$this->data['config']["total_rows"] = $this->accounts->get_accounts_list(true);
		$this->pagination->initialize($this->data['config']);
		$this->data["links"] = $this->pagination->create_links();

		$this->data['result'] = $this->accounts->get_accounts_list();
		$driver = count($this->accounts->get_driver());
		$mechanic = count($this->accounts->get_mechanic());
		$admin = count($this->accounts->get_admin());
		$manager = count($this->accounts->get_manager());
		$this->data['plan_type'] = $this->session->userdata('user')->title;
		$this->data['total_accounts']	= $driver + $mechanic + $admin + $manager;

		$this->load->view('backend/master' , $this->data);
	}

	public function add(){

		$this->form_validation->set_rules('display_name'		, 'Name'			        , 'trim|required');
		$this->form_validation->set_rules('email'				, 'Email Address'			, 'trim|required');
		$this->form_validation->set_rules('username'		    , 'UserName'			    , 'trim|required|is_unique[user.username]');
		$this->form_validation->set_rules('password'		    , 'Password'			    , 'trim|required|md5');
		$this->form_validation->set_rules('confirm_password'    , 'Confirm Password'	    , 'trim|required|matches[password]|md5');

		if($this->session->userdata('user')->role != "ADMIN" && $this->session->userdata('user')->role != "MANAGER"){
			$this->form_validation->set_rules('checklist[]'		, 'Checklist'			    , 'required');
		}

		if ($this->form_validation->run() == FALSE){ 

			$this->data['page_name'] = "Transport Accounts";
			$this->data['main_page'] = "backend/page/users/add";
			$this->data['checklist_list'] = $this->checklist->get_checklist_dropdown();
			$this->load->view('backend/master' , $this->data);

		}else{
			if($last_id = $this->accounts->add_users()){
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Added a User');	

				redirect("app/accounts/?user_id=".$this->hash->encrypt($last_id).'?submit=submit' , 'refresh');
			}else{
				$this->session->set_flashdata('status' , 'error');
				$this->session->set_flashdata('message' , 'Something went wrong');	

				redirect("app/accounts/add" , 'refresh');
			}
		}
	}

	public function edit($user_id){
		$id = $this->hash->decrypt($user_id);

		$this->form_validation->set_rules('display_name'		, 'Name'			        , 'trim|required');
		
		if($this->input->post("password") != ""){
			$this->form_validation->set_rules('password'		    , 'Password'		  , 'trim|required|md5');
			$this->form_validation->set_rules('confirm_password'    , 'Confirm Password'  , 'trim|required|matches[password]|md5');
		}		
		if($this->session->userdata('user')->role != "ADMIN" && $this->session->userdata('user')->role != "MANAGER"){
			$this->form_validation->set_rules('checklist[]'		, 'Checklist'			    , 'required');
		}

		if ($this->form_validation->run() == FALSE){ 

			$this->data['page_name'] = "Transport Accounts";
			$this->data['main_page'] = "backend/page/users/edit";
			$this->data['result']    = $this->accounts->get_account_info($id);
			$this->data['checklist_list'] = $this->checklist->get_checklist_dropdown();

			$this->load->view('backend/master' , $this->data);

		}else{

			if($last_id = $this->accounts->edit_user($id)){
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Added a User');	

				redirect("app/accounts/?user_id=".$this->hash->encrypt($last_id).'?submit=submit' , 'refresh');
			}else{
				$this->session->set_flashdata('status' , 'error');
				$this->session->set_flashdata('message' , 'Something went wrong');	

				redirect("app/accounts/edit/".$user_id , 'refresh');
			}
		}
	}

	public function delete($user_id){
		$id = $this->hash->decrypt($report_id);
			
		if($delete_checklist = $this->reports->delete_report($id)){

			$this->session->set_flashdata('status' , 'success');	
			$this->session->set_flashdata('message' , 'Successfully Deleted User');

			redirect("app/accounts/" , 'refresh');

		}else{
			$this->session->set_flashdata('status' , 'error');	
			$this->session->set_flashdata('message' , 'Something went wrong.');

			redirect("app/accounts/".$user_id , 'refresh');
		}
	}
}
