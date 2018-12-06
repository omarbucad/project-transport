<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Load the DOMPDF libary
require(APPPATH.'third_party/vendor/autoload.php');
ini_set('max_execution_time', 3000);


/*
 *  Braintree_lib
 *  This is a codeigniter wrapper around the braintree sdk, any new sdk can be wrapped around here
 *  License: MIT to accomodate braintree open source sdk license (BSD)
 *  Author: Clint Canada
 *  Library tests (and parameters for lower Braintree functions) are found in:
 *  https://github.com/braintree/braintree_php/tree/master/tests/integration
 */

/**
    General Usage:
        In Codeigniter controller
        function __construct(){
            parent::__construct();
            $this->load->library("braintree_lib");
        }

        function <function>{
            $token = $this->braintree_lib->create_client_token();
            $data['client_token'] = $token;
            $this->load->view('myview',$data);
        }

        In View section
        <script src="https://js.braintreegateway.com/v2/braintree.js"></script>
        <script>
              braintree.setup("<?php echo $client_token;?>", "<integration>", options);
        </script>

    For more information on javascript client: 
    https://developers.braintreepayments.com/javascript+php/sdk/client/setup
 */

class Braintree_lib extends Braintree{

	function __construct() {

        //parent::__construct();
        // We will load the configuration for braintree
        $CI = &get_instance();
        $CI->config->load('braintree', TRUE);
        $braintree = $CI->config->item('braintree');
        // Let us load the configurations for the braintree library
        Braintree_Configuration::environment($braintree['braintree_environment']);
		Braintree_Configuration::merchantId($braintree['braintree_merchant_id']);
		Braintree_Configuration::publicKey($braintree['braintree_public_key']);
		Braintree_Configuration::privateKey($braintree['braintree_private_key']);
    }

    function getAllPlan(){
        $plans = Braintree_Plan::all();

        return $plans;
    }

    // This function simply creates a client token for the javascript sdk
    function create_client_token(){
    	$clientToken = Braintree_ClientToken::generate();
    	return $clientToken;
    }

    // only create  customer
    function create_customer($data){
        if($data){
            $result = Braintree_Customer::create([
                'firstName' => $data->firstname,
                'lastName' => $data->lastname,
                'company' => $data->company,
                'email' => $data->email,
                'phone' => $data->phone,
                'fax' => '',
                'website' => ''
            ]);

            $result->success;
            # true

            $result->customer->id;
            # Generated customer id
        }else{

        }        
    }

    // create customer with payment method

    function create_customerWithPayment($data){
        $result = Braintree_Customer::create([
            'firstName' => $data->firstName,
            'lastName' => $data->lastName,
            'company' => $data->company,
            'email' => $data->email,
            'phone' => $data->phone,
            'paymentMethodNonce' => $data->paymentMethodNonce
        ]);
        if ($result->success) {
            // echo($result->customer->id);
            // echo($result->customer->paymentMethods[0]->token);
            // $subscription = [
            //     "paymentMethodToken" => $result->customer->paymentMethods[0]->token,
            //     "planId" => $data->planId
            // ];
            // $subscription = (object)$subscription;
            // $this->create_subscription($subscription);
            return $result;

        } else {
            $error = "";
            foreach($result->errors->deepAll() AS $error) {
                $error .= $error->code . ": " . $error->message . "\n";
                //echo($error->code . ": " . $error->message . "\n");
            }
            return $error;
        }
    }

    function create_subscription_token($data){
        $result = Braintree_Subscription::create([
          'paymentMethodToken' => $data->paymentMethodToken,
          'planId' => $data->planId
        ]);

        return $result;
    }

    function create_subscription_nonce($data){
        $result = Braintree_Subscription::create([
          'paymentMethodNonce' => $data->paymentMethodNonce,
          'planId' => $data->planId
        ]);

        return $result;
    }

    function cancel_subscription($subscription_id){
        $result = Braintree_Subscription::cancel($subscription_id);
    }


    function update_subscription($data){
        $result = Braintree_Subscription::update($data->subscription_id, [
            'id' => $data->id,
            'paymentMethodToken' => $data->newPaymentMethodToken,
            'planId' => $data->planId,
            'merchantAccountId' => $data->merchantAccountId
        ]);
    }
}