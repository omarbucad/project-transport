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
		$this->data['page_name'] = "Transport Invoices";
		$this->data['main_page'] = "backend/page/admin/invoice/invoice";

		//PAGINATION
		$this->data['config']["base_url"] = base_url("admin/invoice/") ;
		$this->data['config']["total_rows"] = $this->invoice->get_invoice_list(true);
		$this->pagination->initialize($this->data['config']);
		$this->data["links"] = $this->pagination->create_links();
		$this->data['result']	 = $this->invoice->get_invoice_list();

		$this->load->view('backend/master' , $this->data);
	}

	private function send_email_invoice($invoice_id){
		$result = $this->invoice->get_invoice($invoice_id);

		if($result){
			$this->email->from('no-reply@trackerteer.com', 'Trackerteer Inc');
			$this->email->to($invoice_information->email);

			$this->email->subject('Transport Checklist Statement');
			$this->email->message($this->load->view('backend/page/admin/email/send_invoice' , $invoice_information , TRUE));
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
		}else{
			echo json_encode(["status" => false , "message" => "Sending Failed"]);
		}
	}

	public function resend_invoice($user_id){
		$result = $this->invoice->user_invoice($user_id);

		if($result){
			$this->email->from('no-reply@trackerteer.com', 'Trackerteer Inc');
			$this->email->to($result->email_address);

			$this->email->subject('Transport Checklist - Bill Statement');
			$this->email->message($this->load->view('backend/page/admin/email/send_invoice' , $result , TRUE));
			$this->email->attach($result->invoice_pdf);
		
			if($this->email->send()){
				echo json_encode(["status" => true , "message" => "Invoice Email has been sent"]);
			}else{
				echo json_encode(["status" => false , "message" => "Sending Failed"]);
			}
		}else{
			echo json_encode(["status" => false , "message" => "Sending Failed"]);
		}
	}
}
