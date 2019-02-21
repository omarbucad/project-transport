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

	public function subscription(){
		if (
		    isset($_POST["bt_signature"]) &&
		    isset($_POST["bt_payload"])
		) {
			//print_r_die($_POST);
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

}
