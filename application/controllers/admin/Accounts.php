<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends MY_Controller {

	public function __construct() {
	    parent::__construct();
	    $this->load->model('accounts_model', 'accounts');
	    $this->load->model('checklist_model', 'checklist');
	    $this->load->model('invoice_model', 'invoice');

		$this->data['role'] = $this->session->userdata('user')->role;
		$this->data['session_data'] = $this->session->userdata('user');

    }
	public function index(){
		$this->data['page_name'] = "Manage Accounts";
		$this->data['main_page'] = "backend/page/admin/accounts/manage_accounts";

		//PAGINATION
		$this->data['config']["base_url"] = base_url("admin/accounts/") ;
		$this->data['config']["total_rows"] = $this->accounts->get_admin_accounts(true);
		$this->pagination->initialize($this->data['config']);
		$this->data["links"] = $this->pagination->create_links();

		$this->data['result']	 = $this->accounts->get_admin_accounts();
		// $driver = count($this->accounts->get_driver());
		// $mechanic = count($this->accounts->get_mechanic());
		// $admin = count($this->accounts->get_admin());
		// $this->data['plan_type']		= $this->data['session_data']->plan_type;
		// $this->data['total_accounts']	= $driver + $mechanic + $admin;

		$this->load->view('backend/master' , $this->data);
	}

	public function view($user_id){

		$this->form_validation->set_rules('display_name'		, 'Name'			        , 'trim|required');
		
		if($this->input->post("password") != ""){
			$this->form_validation->set_rules('password'		    , 'Password'		  , 'trim|required|md5');
			$this->form_validation->set_rules('confirm_password'    , 'Confirm Password'  , 'trim|required|matches[password]|md5');
		}		

		if ($this->form_validation->run() == FALSE){ 

			$this->data['page_name'] = "Vehicle Checklist Accounts";
			$this->data['main_page'] = "backend/page/admin/accounts/edit_info";
			$this->data['result'] = $this->accounts->user_data($user_id);
			$this->load->view('backend/master' , $this->data);

		}else{

			if($last_id = $this->accounts->admin_edit_user($user_id)){
				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Updated Account');	

				redirect("admin/accounts/", 'refresh');
			}else{
				$this->session->set_flashdata('status' , 'error');
				$this->session->set_flashdata('message' , 'Something went wrong');	

				redirect("admin/accounts/view/".$user_id , 'refresh');
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

	public function update_plan($user_id){

		if($invoice_id = $this->accounts->update_user_plan($user_id)){
			$this->create_invoice_pdf($invoice_id);
			$this->session->set_flashdata('status' , 'success');	
			$this->session->set_flashdata('message' , 'Updated User Plan and Invoice Email has been sent.');

			redirect("admin/accounts/" , 'refresh');
		}else{
			$this->session->set_flashdata('status' , 'error');	
			$this->session->set_flashdata('message' , 'Something went wrong.');

			redirect("admin/accounts/" , 'refresh');
		}
	}

	private function create_invoice_pdf($invoice_id){

		$invoice_information = $this->invoice->get_invoice($invoice_id);


		if($pdf = $this->pdf->create_invoice($invoice_information)){
			

			$result = $this->db->where("invoice_id" , $invoice_id)->update("invoice" , [
				"invoice_pdf" => $pdf['file']
			]);
			$this->send_email_invoice($invoice_information , $pdf['attachment']);

		}
	}

	private function send_email_invoice($invoice_information, $pdf_file){

		$this->email->from('no-reply@trackerteer.com', 'Trackerteer Inc');
		$this->email->to($invoice_information->email_address);

		$this->email->subject('Vehicle Checklist - Bill Statement');
		$this->email->message($this->load->view('backend/page/admin/email/send_invoice' , $invoice_information , TRUE));
		$this->email->attach($pdf_file);
		$this->email->set_mailtype('html');
		
		if($this->email->send()){
			echo json_encode(["status" => true , "message" => "Invoice Email has been sent"]);
		}else{
			echo json_encode(["status" => false , "message" => "Sending Failed"]);
		}
	}

	public function send_plan_notice($user_id){
		$data = $this->accounts->email_notice($user_id);
		if($data){
			$this->email->from('no-reply@trackerteer.com', 'Trackerteer Inc');
			$this->email->to($data->email);

			$this->email->subject('Vehicle Checklist - Plan Expiry Notice');
			$this->email->message($this->load->view('backend/page/admin/email/plan_notification' , $data , TRUE));
			$this->email->set_mailtype('html');

			if($this->email->send()){
				echo json_encode(["status" => true , "message" => "Email has been sent"]);
			}else{
				echo json_encode(["status" => false , "message" => "Sending Failed"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "Sending Failed"]); 
		}
	}


}
