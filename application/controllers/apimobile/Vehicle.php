<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle extends CI_Controller {

	private $post;

	public function __construct( ) {
		parent::__construct();

		//http://stackoverflow.com/questions/18382740/cors-not-working-php
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
        
		//$this->post = json_decode(file_get_contents("php://input"));

		$this->post = (object)$this->input->post();
	}

	

	public function add(){
		$data = $this->post;

		$this->db->trans_start();

		$this->db->insert("vehicle",[
			"vehicle_registration_number" => $data->vehicle_registration_number,
			//"status" => 1,
			"vehicle_type_id" => $data->vehicle_type_id,
			"created" => time(),
			"store_id" => $data->store_id,
			"deleted" => NULL
		]);

		$this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            echo json_encode(["status" => false , "message" => "Failed", "action" => "add"]);
        }else{
            echo json_encode(["status" => true , "message" => "Added Successfully", "action" => "add"]);
        }
	}

	public function edit(){
		$data = $this->post;
		
		$this->db->trans_start();
		$this->db->where("store_id",$data->store_id);
		$this->db->where("vehicle_id", $data->vehicle_id);
		$this->db->update("vehicle",[
			"vehicle_registration_number" => $data->vehicle_registration_number,
			//"status" => $data->status,
			"vehicle_type_id" => $data->vehicle_type_id,
			"deleted" => NULL
		]);

		$this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            echo json_encode(["status" => false , "message" => "Failed", "action" => "edit"]);
        }else{
            echo json_encode(["status" => true , "message" => "Updated Successfully", "action" => "edit"]);
        }
	}

	// public function delete(){
	// 	$data = $this->post;
		
	// 	$this->db->trans_start();
	// 	$this->db->where("store_id",$data->store_id);
	// 	$this->db->where("vehicle_id", $data->vehicle_id);
	// 	$this->db->update("vehicle",[
	// 		"deleted" => time()
	// 	]);

	// 	$this->db->trans_complete();

 //        if ($this->db->trans_status() === FALSE){
 //            echo json_encode(["status" => false , "message" => "Failed", "action" => "delete"]);
 //        }else{
 //            echo json_encode(["status" => true , "message" => "Deleted Successfully", "action" => "delete"]);
 //        }
	// }

	public function multiple_delete(){
		$data = $this->post;
		//print_r_die($data);
		$vehicles = json_decode($data->vehicles);
		$this->db->trans_start();

		foreach ($vehicles as $key) {
			$this->db->where("store_id",$data->store_id);
			$this->db->where("vehicle_id", $key);
			$this->db->update("vehicle",[
				"deleted" => time()
			]);
		}			

		$this->db->trans_complete();

        if ($this->db->trans_status() === FALSE){
            echo json_encode(["status" => false , "message" => "Failed", "action" => "multiple_delete"]);
        }else{
            echo json_encode(["status" => true , "message" => "Deleted Successfully", "action" => "multiple_delete"]);
        }
	}
}
