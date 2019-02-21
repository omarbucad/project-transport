<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Notification {

	private $CI;

	function __construct( ) {
		$this->CI =& get_instance();
	}


	public function notify_admin($data){
		// 1 - REPORT
		// 2 - SUBSCRIPTION
		$this->CI->db->insert("notification" , [
			"description"	=> $data['description'],
			"type"	=> $data['type'],
			"isread" => 0,
			"ref_id" => $data['id'],
			"user_id" => $data['user_id'],
			"created"	=> time()
		]);
	}

	public function notify_read($notification_id){
		$this->CI->db->where("id" , $notification_id)->update("notification" , ["isread" => true]);
	}

	public function notify_list($all = false , $count = false){
		$this->CI->db->select('n.*, nt.name');
		$this->CI->db->join("notification_type nt", "nt.type_id = n.type");
		
		if(!$all){
			$this->CI->db->where("n.isread" , 0);
		}

		if(!$count AND !$all){
			$this->CI->db->limit(10);
		}
		
		$result =  $this->CI->db->order_by("n.created" , "DESC")->get("notification n")->result();

		foreach($result as $key => $row){
			if($row->type == "REPORT"){
				$result[$key]->url  = $this->CI->config->site_url("app/report/view/".$this->hash->encrypt($row->ref_id));
			}else{
				$result[$key]->url  = $this->CI->config->site_url("app/setup/account/view/".$row->ref_id);
			}
			$result[$key]->created = convert_timezone($row->created , true);

 		}
		return $result;
	}
}