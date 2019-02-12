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

    function webhook_subscription(){
        $webhookNotification = Braintree_webhookTesting::sampleNotification(
           Braintree_WebhookNotification::SUBSCRIPTION_TRIAL_ENDED,
           123
        );
        $signature = $webhookNotification["bt_signature"];
        $payload = $webhookNotification["bt_payload"];
        $webhookNotification = Braintree_WebhookNotification::parse(
            $webhookNotification["bt_signature"], $webhookNotification["bt_payload"]
        );

            $data = [
                "bt_signature" => $signature,
                "bt_payload" => $payload,
                "kind" => $webhookNotification->kind,
                "received" => time(),
                "bt_created" => strtotime($webhookNotification->timestamp->format('D M j G:i:s T Y'))
            ];
        return $data;
       //Braintree_WebhookNotification::SUBSCRIPTION_TRIAL_ENDED
        // if ( isset($_POST["bt_signature"]) && isset($_POST["bt_payload"]) ) 
        // {
        //     // $webhookNotification = Braintree_WebhookNotification::parse(
        //     //     $_POST["bt_signature"], $_POST["bt_payload"]
        //     // );
        //     $webhookNotification = Braintree_webhookTesting::sampleNotification(
        //         $_POST["bt_signature"], $_POST["bt_payload"]
        //     );

        //     // Example values for webhook notification properties
        //     $message = $webhookNotification->kind; // "subscription_went_past_due"
        //     $message = $webhookNotification->timestamp->format('D M j G:i:s T Y'); // "Sun Jan 1 00:00:00 UTC 2012"
        //     return $webhookNotification;

        //     // file_put_contents("/tmp/webhook.log", $message, FILE_APPEND);

        //     // header("HTTP/1.1 200 OK");
        // }else{
        //     return false;
        // }
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

    function check_customer($data){
        try{
            $exist = Braintree_Customer::find($data->username);
            $customer = new stdClass;
            $customer->customer = $exist->paymentMethods;
            return $customer;
        }
        catch(Braintree_Exception_NotFound $e){
            if($e){
                return false;
            }
        }
    }

    // if not exist : create customer with payment method
    // else : return customer data
    function get_customer_data($data){
        try{
            $exist = Braintree_Customer::find($data->username);
            
            $customer = new stdClass;
            $customer->customer = $exist->paymentMethods;
            // Status
            // 1 - created
            // 2 - exist
            $customer->exist = 2;
            return $customer;
        } catch(Braintree_Exception_NotFound $e){
            if($e){

                 $result = Braintree_Customer::create([
                    'id' => $data->username,
                    'firstName' => $data->firstname,
                    'lastName' => $data->lastname,
                    'company' => $data->company,
                    'email' => $data->email,
                    'phone' => $data->phone,
                    'paymentMethodNonce' => $data->paymentMethodNonce
                    // ,
                    // 'billing' => [
                    //     'countryCodeAlpha2' => $data->country
                    // ]
                ]);

                if ($result->success) {
                    try{
                        $address = Braintree_Address::create([
                            'customerId' => $data->username,
                            'countryCodeAlpha2' => $data->country
                        ]);
                    }catch(Braintree_Exception_NotFound $n){
                       
                    }
                    // 1 - created
                    // 2 - exist
                    $result->exist = 1;

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
        }
    }

    function create_payment_method($data){
        $result = Braintree_PaymentMethod::create([
            'customerId' => $data->username,
            'paymentMethodToken' => $data->paymentMethodToken,
            'options' => [
              'makeDefault' => true
            ]
        ]);
        if ($result->success) {
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

    function update_payment_method($data){
        $result = Braintree_PaymentMethod::update($data->paymentMethodToken,[
            'token' => $newToken,
            'options' => [
              'makeDefault' => true
            ]
        ]);

        if ($result->success) {
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

    function get_plan_name($id){
        $name = $this->getAllPlan();
        $data = array();
        foreach ($name as $key => $value) {
            if($id == $value->id){
                $data = [
                    "plan" => $value->name,
                    "description" => $value->description
                ];

                return $data; 
            }
        }
    }

    function create_subscription_token($data){
       // $now = convert_timezone(strtotime("now"), true, true,false,"m/d/Y h:i:s A");
        // $n = convert_timezone(strtotime("now"), true, true,false,"m/d/Y h:i:sP");
        // $now = new DateTime($n);
        // $now->format("m/d/Y h:i:sP");
        $plandata = $this->get_plan_name($data->planId);
        if($data->addOnId != ''){
            $result = Braintree_Subscription::create([
                'paymentMethodToken' => $data->paymentMethodToken,
                'planId' => $data->planId,
                'addOns' => [
                    'add' => [
                        [
                            'inheritedFromId' => $data->addOnId,
                            'neverExpires' => true,
                            'quantity' => $data->quantity
                        ]                       
                    ]                    
                ],
                'descriptor' => [
                    //'name' => "TRACKER*".$plandata['description']
                    'name' => "TRCKRTR*PLAN"
                ]
                // 'firstBillingDate' => $now,
                // 'options' => ['startImmediately' => true]
            ]);
            // if($result){
            //     return $result;
            // }else{
            //     // $error = "";
            //     // // foreach($result->errors->deepAll() AS $error) {
            //     // //     $error .= $error->code . ": " . $error->message . "\n";
            //     // //     //echo($error->code . ": " . $error->message . "\n");
            //     // // }
            //     return $error;
            // }
        }else{
            $result = Braintree_Subscription::create([
                'paymentMethodToken' => $data->paymentMethodToken,
                'planId' => $data->planId,
                 'descriptor' => [
                    //'name' => "TRACKER*".$plandata['description']

                    'name' => "TRCKRTR*PLAN"
                ]
                // 'firstBillingDate' => $now,
                // 'options' => ['startImmediately' => true]
            ]);
        }

        return $result;
    }


    function create_subscription_nonce($data){
        $plandata = $this->get_plan_name($data->planId);

        if($data->addOnId != ''){
            $result = Braintree_Subscription::create([
                'paymentMethodToken' => $data->paymentMethodNonce,
                'planId' => $data->planId,
                'addOns' => [
                    'existingId' => $data->addOnId,
                    'never_expires' => true,
                    'quantity' => $data->quantity
                ],
                'descriptor' => [
                     'name' => "TRCKRTR*PLAN"
                    // 'plan' => "Vehicle Checklist - ".$plandata['plan'],
                    // 'description' => $plandata['description']
                ]
                // 'firstBillingDate' => $now,
                // 'options' => ['startImmediately' => true]
            ]);
        }else{
            $result = Braintree_Subscription::create([
                'paymentMethodToken' => $data->paymentMethodNonce,
                'planId' => $data->planId,
                'descriptor' => [
                     'name' => "TRCKRTR*PLAN"
                    // 'plan' => "Vehicle Checklist - ".$plandata['plan'],
                    // 'description' => $plandata['description']
                ]
                // 'firstBillingDate' => $now,
                // 'options' => ['startImmediately' => true]
            ]);
        }

        return $result;
    }

    function get_active_subscription($data){
        $result = Braintree_Subscription::search([
            Braintree_SubscriptionSearch::id()->is($data->username),
            Braintree_SubscriptionSearch::status()->in([Braintree_Subscription::ACTIVE])
        ]);

        if($result){
            return $result;
        }else{
            $error = "";
            foreach($result->errors->deepAll() AS $error) {
                $error .= $error->code . ": " . $error->message . "\n";
                //echo($error->code . ": " . $error->message . "\n");
            }
            return $error;
        }
    }

    function cancel_subscription($data){
        $result = Braintree_Subscription::cancel($data->subscription_id);

        if($result->success){
            return $result;
        }else{
            return false;
        }
    }


    function update_subscription($data){
        if($data->addOnId != ''){
            $result = Braintree_Subscription::update($data->subscription_id, [
                //'id' => $data->id,
                'paymentMethodToken' => $data->newPaymentMethodToken,
                'planId' => $data->planId,
                //'merchantAccountId' => $data->merchantAccountId,
                'addOns' => [
                    'add' => [
                        [
                            'inheritedFromId' => $data->addOnId,
                            'neverExpires' => true,
                            'quantity' => $data->quantity
                        ]                       
                    ]                    
                ],
                'descriptor' => [
                     'name' => "TRCKRTR*PLAN"
                    // 'plan' => "Vehicle Checklist - ".$plandata['plan'],
                    // 'description' => $plandata['description']
                ]
            ]);
            if($result->success){
                return true;
            }else{
                return false;
            }
        }else{
            $result = Braintree_Subscription::update($data->subscription_id, [
                //'id' => $data->id,
                'paymentMethodToken' => $data->newPaymentMethodToken,
                'planId' => $data->planId,
                //'merchantAccountId' => $data->merchantAccountId
            ]);

            return $result;
        }
        
    }

    function update_addOn_subscription($data){
        $result = Braintree_Subscription::update($data->subscription_id, [
            'id' => $data->subscription_id,
            // 'paymentMethodToken' => $data->newPaymentMethodToken,
            // 'planId' => $data->planId,
            // //'merchantAccountId' => $data->merchantAccountId,
            'addOns' => [
                'update' => [
                    [
                        'existingId' => $data->addOnId,
                        'neverExpires' => true,
                        'quantity' => $data->quantity
                    ]                       
                ]                    
            ]
        ]);


        if($result->success){
            return true;
        }else{
            return false;
        }
    }



    //------------------ WEB -------------------------------------------
    // if not exist : create customer with payment method
    // else : return customer data
    function web_customer_data($data){
        try{
            $exist = Braintree_Customer::find($data['username']);
            
            $customer = new stdClass;
            $customer->customer = $exist->paymentMethods;
            return $customer;
        } catch(Braintree_Exception_NotFound $e){
            if($e){
                 $result = Braintree_Customer::create([
                    'id' => $data['username'],
                    'firstName' => $data['firstname'],
                    'lastName' => $data['lastname'],
                    'company' => $data['company'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'paymentMethodNonce' => $data['paymentMethodNonce']
                    //,
                    // 'billing' => [
                    //     'countryCodeAlpha2' => $data->country
                    // ]
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
        }
    }

    function create_websubscription_token($data){
        $plandata = $this->get_plan_name($data->planId);

        $result = Braintree_Subscription::create([
            'paymentMethodToken' => $data['paymentMethodToken'],
            'planId' => $data['planId'],
            'addOns' => [
                'existingId' => $data->addOnId,
                'never_expires' => true,
                'quantity' => $data->quantity
            ],
            'descriptor' => [
                'plan' => $plandata['plan'],
                'description' => $plandata['description']
            ]
            // 'firstBillingDate' => $now,
            // 'options' => ['startImmediately' => true]
        ]);

        return $result;
    }
}