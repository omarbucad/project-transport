<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {

	public function __construct() {
       parent::__construct();
       $this->load->model('report_model', 'reports');
       $this->load->model('accounts_model', 'accounts');
       $this->load->model('checklist_model', 'checklist');
       $this->load->model('vehicle_model', 'vehicle');
       if(!(isset($this->session->userdata('user')->expired) && !$this->session->userdata('user')->expired)){
			redirect("app/dashboard");
		}
    }
	public function daily(){
		$this->data['page_name'] = "Daily Report";
		$this->data['main_page'] = "backend/page/report/view";
		$this->data['plan_type'] = $this->data['session_data']->title;
		
		$role = $this->data['session_data']->role;

		if($this->input->get("export")){
			$this->data['result'] = $this->reports->get_reports_list();
			$this->download_csv($this->data['result']);
		}else{

			$this->data['result'] = $this->reports->get_reports_list();
			$this->load->view('backend/master' , $this->data);
		}
	}

	public function weekly(){

		$this->data['page_name'] = "Daily Report";
		$this->data['main_page'] = "backend/page/report/view_weekly";

		$this->form_validation->set_data($this->input->get());

		$this->form_validation->set_rules('date' , 'Start Date Report'	, 'trim|required');
		$this->form_validation->set_rules('checklist_type' , 'Checklist Type'	, 'trim|required');
		$this->form_validation->set_rules('driver' , 'Driver Name'	, 'trim|required');
		$this->form_validation->set_rules('vehicle' , 'Vehicle Registration Number'	, 'trim|required');

		if ($this->form_validation->run() == FALSE){ 

			$this->data['driver_list'] = $this->accounts->get_driver();
			$this->data['vehicle_list'] = $this->vehicle->get_vehicle_list();
			$this->data['checklist_type'] = $this->checklist->get_checklist_list();			
			$this->data['result'] = [];
			$this->load->view('backend/master' , $this->data);

		}else{

			$this->data['driver_list'] = $this->accounts->get_driver();
			$this->data['vehicle_list'] = $this->vehicle->get_vehicle_list();
			$this->data['checklist_type'] = $this->checklist->get_checklist_list();
			$this->data['checklist_items'] = $this->checklist->get_checklist_items($this->hash->encrypt($this->input->get('checklist_type')));

			$this->data['result'] =	$this->reports->weekly_report();

			if($this->input->get("export")){
				if(empty($this->data['result'])){
					$this->load->view('backend/master');
				}else{			
					$this->download_csv($this->data['result']);
				}
			}else{
				$this->load->view('backend/master' , $this->data);
			}
		}		
	}

	public function view($report_id){

			$report_id = $this->hash->decrypt($report_id);

			$this->data['page_name'] = "Report";
			$this->data['result']    =  $this->reports->get_report_by_id($report_id);
			$this->data['main_page'] = "backend/page/report/view_report";

			$this->load->view('backend/master' , $this->data);
		
	}
	
	public function pdf($report_id){

		$report_id = $this->hash->decrypt($report_id);
		$report = $this->reports->get_report_by_id($report_id);

		$pdf = $this->pdf->create_report_checklist($report , "F");

		$update = $this->db->where("report_id" , $report->report_id)->update("report" , [
			"pdf_path"			=> $pdf['path'],
			"pdf_file" 			=> $pdf['filename']
		]);
		
		if($update){
			$this->session->set_flashdata('status' , 'success');	
			$this->session->set_flashdata('message' , 'Successfully Generated PDF!');

			redirect("app/report/daily" , 'refresh');
		}else{
			$this->session->set_flashdata('status' , 'error');	
			$this->session->set_flashdata('message' , 'Something went wrong.');

			redirect("app/report/daily" , 'refresh');
		}
	
	}

	public function pdf_weekly(){

		$report = $this->reports->weekly_report();
		$this->pdf->create_report_weekly($report , "I");
	
	}

	public function report_status($report_id){
		if($status_info = $this->reports->get_status_info($report_id)){
			echo json_encode(["status" => "true","data" => $status_info]);
		}else{

			$this->session->set_flashdata('status' , 'error');	
			$this->session->set_flashdata('message' , 'Something went wrong.');
			redirect("app/report/daily" , 'refresh');
		}
	}

	public function update($report_id){
		$this->form_validation->set_rules('notes' , 'Notes'	, 'trim|required');

		if ($this->form_validation->run() == FALSE){ 
			$this->session->set_flashdata('status' , 'error');	
			$this->session->set_flashdata('message' , 'Note is required.');

			redirect("app/report/daily" , 'refresh');
		}else{
			if($delete_checklist = $this->reports->update_report($report_id)){

				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Updated Status');

				redirect("app/report/daily" , 'refresh');

			}else{
				$this->session->set_flashdata('status' , 'error');	
				$this->session->set_flashdata('message' , 'Something went wrong.');

				redirect("app/report/daily" , 'refresh');
			}
		}
	}

	private function download_csv($data){

		$export = array();

		foreach($data as $key => $row){
			$export[] = array(
				"Report Number" 	 			 => $row->report_number ,
				"Report By" 		 			 => $row->display_name	,
				"Vehicle Registration number" 	 => $row->vehicle_registration_number	,
				"Trailer Number" 				 => (isset($row->trailer_number)) ? $row->trailer_number : 'NA',
				"Checklist Type"	 			 => $row->checklist_name,
				"Start Mileage"		 			 => $row->start_mileage,
				"End Mileage"	 				 => $row->end_mileage,
				"Report Notes"					 => $row->report_notes,
 				"Status" 				  		 => $row->raw_status, 
				"Created"						 => $row->created
			);
		}

		download_send_headers('report_' . date("Y-m-d") . ".csv");
		echo array2csv($export);
	}
}
