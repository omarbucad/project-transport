<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webhooks extends CI_Controller {

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

	public function subscription(){
		if (
		    isset($_POST["bt_signature"]) &&
		    isset($_POST["bt_payload"])
		) {
			$signature = $_POST["bt_signature"];
			$payload = $_POST["bt_payload"];
		    $webhookNotification = Braintree_WebhookNotification::parse(
		        $_POST["bt_signature"], $_POST["bt_payload"]
		    );

		    $data = [
				"subscription_id" => $webhookNotification->subscription->id,
				"kind" => $webhookNotification->kind,
				"received" => time(),
				"bt_created" => strtotime($webhookNotification->timestamp->format('Y-m-d H:i:s'))
			];

			$insert = $this->save_webhook($data);

			$message =
		        "[Webhook Received " 
		        . $webhookNotification->timestamp->format('Y-m-d H:i:s') . "] "
		        . "Kind: " . $webhookNotification->kind . " | "
		        . "Subscription: " . $webhookNotification->subscription->id . "\n";
			
		    $filename = $webhookNotification->timestamp->format('Y-m-d').".log";
			file_put_contents("./application/webhook_logs/".$filename, $message, FILE_APPEND);

			// if($insert){
			// 	file_put_contents("/tmp/webhook.log", $message, FILE_APPEND);

			//header("HTTP/1.1 200 OK");
			// }

		    // Example values for webhook notification properties
		    /*$message = $webhookNotification->kind; // "subscription_went_past_due"
		    $message = $webhookNotification->timestamp->format('D M j G:i:s T Y'); // "Sun Jan 1 00:00:00 UTC 2012"*/

		    /*file_put_contents("/tmp/webhook.log", $message, FILE_APPEND);

		    header("HTTP/1.1 200 OK");*/

		}
	}

	public function save_webhook($data){
		$insert = $this->db->insert("webhooks",[
			"subscription_id" => $data['subscription_id'],
			"kind" => $data['kind'],
			"received" => $data['received'],
			"bt_created" => $data['bt_created'],
			"email_Sent" => 0
		]);
		if($insert){
			$this->process_webhook($data['kind'],$data);
			return true;
		}else{
			return false;
		}
	}

	public function process_webhook($kind,$data){
		$this->db->select("u.user_id");
		$this->db->join("user_plan up","up.store_id = u.store_id");
		$user = $this->db->limit(1)->order_by("u.created", "DESC")->get("user u")->row();

		switch ($kind) {
			case 'subscription_charged_successfully':
				$this->notification->notify_admin([
					"description"	=> "Subscription - Charged",
					"type"	=> 2,
					"isread" => 0,
					"ref_id" => $data['id'],
					"user_id" => $user->user_id
				]);
				break;
			case 'subscription_charged_unsuccessfully':
				$this->notification->notify_admin([
					"description"	=> "Subscription - Unsuccessful charge",
					"type"	=> 2,
					"isread" => 0,
					"ref_id" => $data['id'],
					"user_id" => $user->user_id
				]);
				break;
			case 'subscription_expired':
				$this->notification->notify_admin([
					"description"	=> "Subscription - Expired",
					"type"	=> 2,
					"isread" => 0,
					"ref_id" => $data['id'],
					"user_id" => $user->user_id
				]);
				break;
			case 'subscription_canceled':
				$this->notification->notify_admin([
					"description"	=> "Subscription - Cancelled",
					"type"	=> 2,
					"isread" => 0,
					"ref_id" => $data['id'],
					"user_id" => $user->user_id
				]);
				break;
			case 'subscription_trial_ended':
				$this->notification->notify_admin([
					"description"	=> "Subscription - Trial ended",
					"type"	=> 2,
					"isread" => 0,
					"ref_id" => $data['id'],
					"user_id" => $user->user_id
				]);
				break;
			case 'subscription_went_active':				
				$this->notification->notify_admin([
					"description"	=> "Subscription - Started",
					"type"	=> 2,
					"isread" => 0,
					"ref_id" => $data['id'],
					"user_id" => $user->user_id
				]);
				break;
			case 'subscription_went_past_due':
				$this->notification->notify_admin([
					"description"	=> "Subscription - Past due",
					"type"	=> 2,
					"isread" => 0,
					"ref_id" => $data['id'],
					"user_id" => $user->user_id
				]);
				break;
		}
	}
}
