<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Load the DOMPDF libary
require(APPPATH.'third_party/pdf/vendor/autoload.php');
ini_set('max_execution_time', 3000);

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

class Pdf {

	private $html2pdf = array();
	private $CI;
	private $folder;
	private $result;

	function __construct( ) {
		
		$this->CI =& get_instance();

		$year = date("Y");
		$month = date("m");

		$this->folder = "public/upload/pdf/".$year."/".$month;

		if (!file_exists(FCPATH.$this->folder)) {

			mkdir(FCPATH.$this->folder , 0777, true);
			create_index_html(FCPATH.$this->folder);
		}

	}


	public function create_report_checklist($data = array() , $output = "D"){
		$this->html2pdf = new HTML2PDF('P','A4','en' , true , 'UTF-8' , $marges = array(5, 5, 5, 5));

		try{
			$filename = "report_".$data->report_number.'_'.time().'.pdf';
			$path = FCPATH.$this->folder.'/'.$filename;

			$this->html2pdf->writeHTML($this->CI->load->view("backend/page/pdf/report_checklist_two" , $data , TRUE));
			$this->html2pdf->Output($path , "F");

			return [
				"attachment" => FCPATH.$this->folder.'/'.$filename ,
				"file"		 => $this->folder.'/'.$filename,
				"filename"	 => $filename,
				"path" 		 => $this->folder.'/'
			];

		}catch (Html2PdfException $e) {
			$formatter = new ExceptionFormatter($e);
			echo $formatter->getHtmlMessage();
		}
	}

	public function create_report_weekly($data = array() , $output = "D"){
		$this->html2pdf = new HTML2PDF('P','A4','en' , true , 'UTF-8' , $marges = array(10, 10, 10, 10));

		$start = date("Y-m-d",strtotime($data['header'][0]->startrange));
		$end = date("Y-m-d",strtotime($data['header'][0]->endrange));

		try{
			$filename = 'report_('.$start.'_'.$end.')_'.time().'.pdf';
			$path = FCPATH.$this->folder.'/'.$filename;

			$this->html2pdf->writeHTML($this->CI->load->view("backend/page/pdf/weekly_report" , ["data" => $data] , TRUE));
			$this->html2pdf->Output($path , $output);

			return [
				"attachment" => FCPATH.$this->folder.'/'.$filename ,
				"file"		 => $this->folder.'/'.$filename
			];

		}catch (Html2PdfException $e) {
			$formatter = new ExceptionFormatter($e);
			echo $formatter->getHtmlMessage();
		}
	}

	public function create_invoice($data = array()){
		$this->html2pdf = new HTML2PDF('P','A4','en' , true , 'UTF-8' , $marges = array(10, 10, 10, 10));
		try{
			$filename = "INVOICE_".$data->invoice_no.'_'.time().'.pdf';
			$path = FCPATH.$this->folder.'/'.$filename;

			$this->html2pdf->writeHTML($this->CI->load->view("backend/page/pdf/plan_invoice" , $data , TRUE));
			$this->html2pdf->Output($path , "F");

			return	[
				"attachment" => FCPATH.$this->folder.'/'.$filename ,
				"file"		 => $this->folder.'/'.$filename
			];

		}catch (Html2PdfException $e) {
			$formatter = new ExceptionFormatter($e);
			echo $formatter->getHtmlMessage();
		}
	}

	public function create_multiple_report($data = array() , $output = "D", $start = "", $end = ""){
		$this->html2pdf = new HTML2PDF('P','A4','en' , true , 'UTF-8' , $marges = array(10, 10, 10, 10));
		$reports = array();
		$reports = ["data" => $data];
		


		try{
			if($start != "" && $end != ""){
				$filename = "report_".$start."-".$end.'.pdf';	
			}else{
				$filename = "multiple_reports.pdf";	
			}
			
			$path = FCPATH.$this->folder.'/'.$filename;

			$this->html2pdf->writeHTML($this->CI->load->view("backend/page/pdf/multiple_report" , $reports , TRUE));
			$this->html2pdf->Output($filename , $output);
			ob_clean();
			die();
		}catch (Html2PdfException $e) {
			$formatter = new ExceptionFormatter($e);
			echo $formatter->getHtmlMessage();
		}
	}
}