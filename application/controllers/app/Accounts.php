<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends MY_Controller {

	public function __construct() {
       parent::__construct();
       $this->load->model('accounts_model', 'accounts');

    }
	public function index(){

		$this->data['page_name'] = "Transport Accounts";
		$this->data['main_page'] = "backend/page/users/view";

		//PAGINATION
		$this->data['config']["base_url"] = base_url("app/accounts/") ;
		$this->data['config']["total_rows"] = $this->accounts->get_accounts_list(true);
		$this->pagination->initialize($this->data['config']);
		$this->data["links"] = $this->pagination->create_links();

		$this->data['result']	 = $this->accounts->get_accounts_list();
		$this->load->view('backend/master' , $this->data);
	}

	public function add(){

		$this->form_validation->set_rules('display_name'		, 'Name'			        , 'trim|required');
		$this->form_validation->set_rules('username'		    , 'UserName'			    , 'trim|required|is_unique[user.username]');
		$this->form_validation->set_rules('password'		    , 'Password'			    , 'trim|required|md5');
		$this->form_validation->set_rules('confirm_password'    , 'Confirm Password'	    , 'trim|required|matches[password]|md5');

		if ($this->form_validation->run() == FALSE){ 

			$this->data['page_name'] = "Transport Accounts";
			$this->data['main_page'] = "backend/page/users/add";
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
}
