<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Load the DOMPDF libary
require(APPPATH.'third_party/pdf/vendor/autoload.php');

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

		$this->folder = "/public/upload/pdf/".$year."/".$month;

		if (!file_exists(FCPATH.$this->folder)) {

			mkdir(FCPATH.$this->folder , 0777, true);
			create_index_html(FCPATH.$this->folder);
		}

	}
	public function create_invoice($data = array()){
		$this->html2pdf = new HTML2PDF('P','A4','en' , true , 'UTF-8' , $marges = array(10, 10, 10, 10));
		try{

			$filename = "INVOICE_".$data->invoice_no.'_'.time().'.pdf';
			$path = FCPATH.$this->folder.'/'.$filename;

			$this->html2pdf->writeHTML($this->CI->load->view("backend/pdf/plan_invoice" , $data , TRUE));
			$this->html2pdf->Output($path , 'F');

			return [
				"attachment" => FCPATH.$this->folder.'/'.$filename ,
				"file"		 => $this->folder.'/'.$filename
			];

		}catch (Html2PdfException $e) {
			$formatter = new ExceptionFormatter($e);
			echo $formatter->getHtmlMessage();
		}
	}
}