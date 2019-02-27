<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {
	public function __construct() {
       parent::__construct();
       $this->load->model('register_model', 'register');

    }
	public function index(){
		
		$this->load->view('frontend/login' , $this->data);
	}
	public function do_login(){

		if($data = $this->register->signin($this->input->post())){
			$this->session->set_userdata("user" , $data);
			$role = $this->session->userdata('user')->role;
			// $ex = convert_timezone($this->session->userdata("user")->plan_expiration, true,true);
		 //    $exp = new DateTime($ex);
		 //    $expiration = $exp->format('M d Y H:i:s');
		 //    $today = convert_timezone(strtotime(date("Y-m-d H:i:s")) , true ,true);
		 //    $timezone = get_timezone();
		 //    $dt = new DateTime($today, new DateTimeZone( $timezone ));
		 //    $now = $dt->format('M d Y H:i:s');

		    if($role == "ADMIN PREMIUM" || $role == "MECHANIC"){
		    	if($this->session->userdata("user")->plan_expiration == ''){
		    		$expired = 0;
		    	}else{
		    		if($this->session->userdata("user")->plan_expiration > strtotime("now")){
				    	$expired = 0;
					}else{
						$expired = 1;
					}
		    	}
	    		
				$this->session->userdata('user')->expired = $expired;
		    	
		    }			


			if($role == "SUPER ADMIN"){
				$this->session->set_flashdata('status' , 'success');
				redirect('/admin/dashboard', 'refresh');
			}elseif($role == "DRIVER PREMIUM" || $role == "DRIVER" ){
				$this->session->set_flashdata('status' , 'failed');
				$this->session->set_flashdata('message' , 'Driver account is not allowed.');

				redirect('/login', 'refresh');
			}else{

				$this->session->set_flashdata('status' , 'success');
				redirect('/app/dashboard', 'refresh');
			}

		}else{
			$this->session->set_flashdata('status' , 'failed');
			$this->session->set_flashdata('message' , 'Incorrect Username or Password');

			redirect('/login', 'refresh');
		}
	}
	
	public function logout(){
		$this->session->set_flashdata('status' , 'success');
		$this->session->set_flashdata('message' , 'You have been Logged out');

		redirect('/login', 'refresh');
	}

	public function forgot_password(){

		$this->form_validation->set_rules('email', 'Email', 'trim|required');

		if ($this->form_validation->run() == FALSE){ 

			$this->data['title_page'] = "Vehicle Checklist | Forgotten Password";

			$this->load->view('frontend/forgot_password' , $this->data);

		}else{
			$email = $this->input->post("email");
			$this->db->where("deleted IS NULL");
			$info = $this->db->select("email_address,role")->where("email_address", $email)->get("user")->row();
			
			if($info){
				if($info->role != 'ADMIN PREMIUM'){
					$this->session->set_flashdata('status' , 'error');	
					$this->session->set_flashdata('message' , '');

					redirect('/login/forgot_password/?error=email_not_registered', 'refresh');
				}

				$time = time();
				$code = $this->hash->encrypt($email . "&time=".$time);

				$link = site_url("/login/change_password/".$code);	

				$this->db->where("email_address",$email);
				$this->db->update("user",[
					"link" => $link,
					"link_created" => $time
				]);

				$data['link'] = $link;

				$this->email->from('no-reply@trackerteer.com', 'Trackerteer | Vehicle Checklist');
				$this->email->to($email);
				$this->email->set_mailtype("html");
				$this->email->subject('Password Reset');
				$this->email->message($this->load->view('email/change_password_email', $data , true));

				$this->email->send();


				$this->session->set_flashdata('status' , 'success');	
				$this->session->set_flashdata('message' , 'Change Password Email Sent. Please check your email.');

				redirect('/login/forgot_password/?status=emailsent', 'refresh');

			}else{

				$this->session->set_flashdata('status' , 'error');	
				$this->session->set_flashdata('message' , 'The email doesn\'t exist.');

				redirect('/login/forgot_password/?error=email_not_registered', 'refresh');
			}

		}
	}

	public function change_password($code){

		if($code){
			$set = $this->hash->decrypt($code);
			$splitted = explode("&time=", $set);
			$email = $splitted[0]; 
			$created = $splitted[1];


			if($created < (time() - (30*60))){
				$this->session->set_flashdata('status' , 'error');	
				$this->session->set_flashdata('message' , 'Expired Link. Please request a new Change Password link.');

				redirect("/login/forgot_password/?error=expired_link", "refresh");
			}else{
				$this->form_validation->set_rules('password'		, 'Password'	    , 'trim|required|min_length[6]');
				$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');

				if ($this->form_validation->run() == FALSE){ 
					$this->data['code'] = $code;
					$this->data['customer_email'] = $email;
					$this->data['title_page'] = "Change Password";

					$this->load->view('frontend/change_password' , $this->data);

				}else{
					$this->db->where("deleted IS NULL");
					$result = $this->db->select("email_address")->where("email_address" , $email)->get("user")->row();

					if($result){
						$update = $this->db->where("email_address" , $result->email_address)->update("user" , [
							"password" => md5($this->input->post("password"))
						]);

						if($update){
							$data['email'] = $email;

							$this->email->from('no-reply@trackerteer.com', 'Trackerteer | Vehicle Checklist');
							$this->email->to($email);
							$this->email->set_mailtype("html");
							$this->email->subject('Password Updated');
							$this->email->message($this->load->view('email/password_updated', $data, true));

							$this->email->send();

							$this->session->set_flashdata('status' , 'success');	
							$this->session->set_flashdata('message' , 'Successfully Changed Password');

							redirect("/login/change_password/".$code."?success=change_password", "refresh");
						}else{
							$this->session->set_flashdata('status' , 'error');	
							$this->session->set_flashdata('message' , 'An error occurred. Please try again.');

							redirect("/login/change_password/".$code."?error=change_password", "refresh");
						}
						
					}else{
						$this->session->set_flashdata('status' , 'error');	
						$this->session->set_flashdata('message' , 'The email doesn\'t exist.');

						redirect('/login/forgot_password/?error=email_not_registered', 'refresh');
					}	
				}					
			}
		}else{
			$this->session->set_flashdata('status' , 'error');	
			$this->session->set_flashdata('message' , 'Invalid Link');

			redirect("/login/forgot_password/?error=invalid_link", "refresh");
		}
	}
}
