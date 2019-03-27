<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

	private $post;

	public function __construct( ) {
		parent::__construct();

		//http://stackoverflow.com/questions/18382740/cors-not-working-php
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        //kapag ionic ito ung gamitin
		//$this->post = json_decode(file_get_contents("php://input"));

		//kapag hindi
		//$this->post = (object)$this->input->post();
	}

	public function trial_ended_email(){
		$this->db->trans_start();
			$this->db->select("webhook_id, subscription_id, kind");
			$this->db->where("kind","subscription_trial_ended");
			$webhooks = $this->db->where("email_sent",0)->get("webhooks")->result();
		$this->db->trans_complete();		

		foreach($webhooks as $key => $value){
			$this->email->from('no-reply@trackerteer.com', 'Trackerteer | Vehicle Checklist');
			$this->email->to($data->email);
			$this->email->set_mailtype("html");
			$this->email->subject('Subscription trial ends');
			$this->email->message($this->load->view('email/trial_ended', $data , true));

			if($this->email->send()){
				$this->db->trans_start();
					$this->db->where("webhook_id", $value->webhook_id);
					$this->db->where("email_sent",0)->update("webhooks",[
						"email_sent" => 1
					]);
				$this->db->trans_complete();
			}			            
		}
	}

	public function subscription_charged_failed(){
		$this->db->trans_start();
			$this->db->select("webhook_id, subscription_id, kind");
			$this->db->where("kind","subscription_charged_unsuccessfully");
			$webhooks = $this->db->where("email_sent",0)->get("webhooks")->result();
		$this->db->trans_complete();		

		foreach($webhooks as $key => $value){
			$this->email->from('no-reply@trackerteer.com', 'Trackerteer | Vehicle Checklist');
			$this->email->to($data->email);
			$this->email->set_mailtype("html");
			$this->email->subject('Subscription payment failed');
			$this->email->message($this->load->view('email/payment_declined', $data , true));

			if($this->email->send()){
				$this->db->trans_start();
					$this->db->where("webhook_id", $value->webhook_id);
					$this->db->where("email_sent",0)->update("webhooks",[
						"email_sent" => 1
					]);
				$this->db->trans_complete();
			}			            
		}
	}

	public function subscription_expired(){
		$this->db->trans_start();
			$this->db->select("webhook_id, subscription_id, kind");
			$this->db->where("kind","subscription_charged_unsuccessfully");
			$webhooks = $this->db->where("email_sent",0)->get("webhooks")->result();

			$this->db->where("active",1);
			$this->db->select("store_id")->where("subscription_id",$webhooks->subscription_id)->;
		$this->db->trans_complete();		

		foreach($webhooks as $key => $value){
			$this->email->from('no-reply@trackerteer.com', 'Trackerteer | Vehicle Checklist');
			$this->email->to($data->email);
			$this->email->set_mailtype("html");
			$this->email->subject('Subscription payment failed');
			$this->email->message($this->load->view('email/subscription_expired', $data , true));

			if($this->email->send()){
				$this->db->trans_start();
					$this->db->where("webhook_id", $value->webhook_id);
					$this->db->where("email_sent",0)->update("webhooks",[
						"email_sent" => 1
					]);
				$this->db->trans_complete();
			}			            
		}
	}

}
