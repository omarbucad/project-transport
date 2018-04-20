<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {

	public function __construct() {
       parent::__construct();
       $this->load->model('report_model', 'reports');
    }
	public function index(){
		$this->data['page_name'] = "Report";
		$this->data['main_page'] = "backend/page/report/view";

		if($this->input->get("export")){
			$this->data['result'] = $this->reports->get_reports_list();
			$this->download_csv($this->data['result']);
		}else{

			$this->data['result']    =  $this->reports->get_reports_list();
			$this->load->view('backend/master' , $this->data);
		}
	}

	public function view($report_id){
			
			$this->data['page_name'] = "Report";
			$this->data['result']    =  $this->reports->get_report_by_id($report_id);
			$this->data['main_page'] = "backend/page/report/view_report";

			$this->load->view('backend/master' , $this->data);
		
	}

	public function report_status($report_id){
		if($status_info = $this->reports->get_status_info($report_id)){
			echo json_encode(["status" => "true","data" => $status_info]);
		}else{

			$this->session->set_flashdata('status' , 'error');	
			$this->session->set_flashdata('message' , 'Something went wrong.');
			redirect("app/report/" , 'refresh');
		}
	}

	public function update($report_id){
		$this->form_validation->set_rules('notes' , 'Notes'	, 'trim|required');

		if ($this->form_validation->run() == FALSE){ 
			$this->session->set_flashdata('status' , 'error');	
			$this->session->set_flashdata('message' , 'Note is required.');

			redirect("app/report/" , 'refresh');
		}else{
			if($delete_checklist = $this->reports->update_report($report_id)){

				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Successfully Updated Status');

				redirect("app/report/" , 'refresh');

			}else{
				$this->session->set_flashdata('status' , 'error');	
				$this->session->set_flashdata('message' , 'Something went wrong.');

				redirect("app/report/" , 'refresh');
			}
		}
	}

	public function create_pdf($report_id){
		$report = $this->reports->get_report_by_id($report_id);

		if($pdf = $this->pdf->create_report_checklist($report)){
			$id = $this->hash->decrypt($report_id);

			$this->db->where("report_id" , $id)->update("report" , [
				"report_pdf" => $pdf['file']
			]);
			
			//download_send_headers($pdf['attachment']);
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
				"Longitude"						 => $row->longitude,
				"Latitude"						 => $row->latitude,
				"Created"						 => $row->created
			);
		}

		download_send_headers('report_' . date("Y-m-d") . ".csv");
		echo array2csv($export);
	}
}
