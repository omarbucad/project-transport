<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends MY_Controller {

	public function __construct() {
       parent::__construct();
       $this->load->model('invoice_model', 'invoice');
       $this->load->model('accounts_model', 'accounts');

    }
	public function index(){
		$this->data['role'] = $this->session->userdata('user')->role;
		$this->data['session_data'] = $this->session->userdata('user');
		$this->data['page_name'] = "Transport Accounts";
		$this->data['main_page'] = "backend/page/admin/invoice";

		//PAGINATION
		$this->data['config']["base_url"] = base_url("admin/invoice/") ;
		$this->data['config']["total_rows"] = $this->accounts->get_admin_accounts(true);
		$this->pagination->initialize($this->data['config']);
		$this->data["links"] = $this->pagination->create_links();

		$this->data['result']	 = $this->accounts->get_admin_accounts();
		$driver = count($this->accounts->get_driver());
		$mechanic = count($this->accounts->get_mechanic());
		$admin = count($this->accounts->get_admin());
		$this->data['plan_type']		= $this->data['session_data']->plan_type;
		$this->data['total_accounts']	= $driver + $mechanic + $admin;

		$this->load->view('backend/master' , $this->data);
	}

	private function create_invoice($user_id){

		$invoice_information = $this->invoice->get_invoice_by_id($invoice_id);

		if($pdf = $this->pdf->create_invoice($invoice_information)){

			$this->db->where("invoice_id" , $invoice_id)->update("invoice" , [
				"invoice_pdf" => $pdf['file']
			]);

			$this->send_email_invoice($invoice_information , $pdf['attachment']);
		}
	}

	private function send_email_invoice($invoice_information , $pdf_file , $ajax = false){

		$this->email->from('no-reply@trackerteer.com', 'Trackerteer Inc');
		$this->email->to($invoice_information->email);

		$this->email->subject('Gravybaby Bill Statement');
		$this->email->message($this->load->view('backend/email/send_invoice' , $invoice_information , TRUE));
		$this->email->attach($pdf_file);
		$this->email->set_mailtype('html');

		if($ajax){
			if($this->email->send()){
				echo json_encode(["status" => true , "message" => "Email has been sent"]);
			}else{
				echo json_encode(["status" => false , "message" => "Sending Failed"]);
			}
		}else{
			$this->email->send();
		}
	}
}
