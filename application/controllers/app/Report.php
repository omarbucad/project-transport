<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {

	function __construct() {
        parent::__construct();
        $this->load->helper('download');
       	$this->load->library('zip');

       	$this->data['notification_list'] = $this->notification->notify_list();
       	
        $this->load->model('report_model', 'reports');
        $this->load->model('accounts_model', 'accounts');
        $this->load->model('checklist_model', 'checklist');
        $this->load->model('vehicle_model', 'vehicle');
        if($this->session->userdata('user')->expired == 1){
			redirect("app/dashboard");
		}
    }
	public function daily(){
		$this->data['page_name'] = "Manage Report";
		$this->data['main_page'] = "backend/page/report/view";
		$this->data['plan_type'] = $this->data['session_data']->title;
		
		$role = $this->data['session_data']->role;

		if($this->input->get("export")){
			$this->data['result'] = $this->reports->get_reports_list();
			$this->download_csv($this->data['result']);
		}else{
			$this->data['checklist_list'] = $this->checklist->get_checklist_dropdown();
			$this->data['result'] = $this->reports->get_reports_list();
			$this->load->view('backend/master' , $this->data);
		}
	}

	// public function weekly(){

	// 	$this->data['page_name'] = "Daily Report";
	// 	$this->data['main_page'] = "backend/page/report/view_weekly";

	// 	$this->form_validation->set_data($this->input->get());

	// 	$this->form_validation->set_rules('date' , 'Start Date Report'	, 'trim|required');
	// 	$this->form_validation->set_rules('checklist_type' , 'Checklist Type'	, 'trim|required');
	// 	$this->form_validation->set_rules('driver' , 'Driver Name'	, 'trim|required');
	// 	$this->form_validation->set_rules('vehicle' , 'Vehicle Registration Number'	, 'trim|required');

	// 	if ($this->form_validation->run() == FALSE){ 

	// 		$this->data['driver_list'] = $this->accounts->get_driver();
	// 		$this->data['vehicle_list'] = $this->vehicle->get_vehicle_list();
	// 		$this->data['checklist_type'] = $this->checklist->get_checklist_list();			
	// 		$this->data['result'] = [];
	// 		$this->load->view('backend/master' , $this->data);

	// 	}else{

	// 		$this->data['driver_list'] = $this->accounts->get_driver();
	// 		$this->data['vehicle_list'] = $this->vehicle->get_vehicle_list();
	// 		$this->data['checklist_type'] = $this->checklist->get_checklist_list();
	// 		$this->data['checklist_items'] = $this->checklist->get_checklist_items($this->hash->encrypt($this->input->get('checklist_type')));

	// 		$this->data['result'] =	$this->reports->weekly_report();

	// 		if($this->input->get("export")){
	// 			if(empty($this->data['result'])){
	// 				$this->load->view('backend/master');
	// 			}else{			
	// 				$this->download_csv($this->data['result']);
	// 			}
	// 		}else{
	// 			$this->load->view('backend/master' , $this->data);
	// 		}
	// 	}		
	// }

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

	public function pdf_all(){

		//$report_id = $this->hash->decrypt($report_id);
		$date = $this->input->post('date');
		$start = strtotime(trim($date.' 00:00'));
        $today = strtotime(trim(date("d-M-Y 00:00", time())));
        if($start == $today){
              $end = date("dMY",$start);
        }else{
            $end = strtotime(trim($date.' 23:59 + 6 days'));

           	$end = date("dMY",$end);
        }
		$reports = $this->reports->get_reports_to_pdf();
		if($reports){
			$this->load->helper('file');

			$data = array();
			foreach ($reports as $key => $v) {				
				array_push($data, $v->pdf_path . $v->pdf_file);
			}

			ob_start();
			foreach ($data as $d) {
				$this->zip->read_file($d);
			}
			$this->zip->archive("./public/upload/report/multiple/reports_".$start."_".$end.".zip");
			$this->zip->download("reports_".$start."_".$end.".zip");
			ob_clean();

			die();
		}else{
			$this->session->set_flashdata('status' , 'error');	
			$this->session->set_flashdata('message' , 'No reports available.');

			redirect("app/report/daily" , 'refresh');
		}

		

		// $update = $this->db->where("report_id" , $report->report_id)->update("report" , [
		// 	"pdf_path"			=> $pdf['path'],
		// 	"pdf_file" 			=> $pdf['filename']
		// ]);
		
		// if($update){
		// 	$this->session->set_flashdata('status' , 'success');	
		// 	$this->session->set_flashdata('message' , 'Successfully Generated PDF!');

		// 	redirect("app/report/daily" , 'refresh');
		// }else{
		// 	$this->session->set_flashdata('status' , 'error');	
		// 	$this->session->set_flashdata('message' , 'Something went wrong.');

		// 	redirect("app/report/daily" , 'refresh');
		// }
	
	}
}
