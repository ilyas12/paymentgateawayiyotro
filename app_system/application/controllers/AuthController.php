<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class AuthController extends AR_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("User_access_group_model");
	}
	public function index(){
	}
	public function login()
	{
		$result = $this->result;
		$result['error'] = 1;
		$result['msg'] = 'Login Failed';
		$post_data = $this->input_data;
		$username = $post_data['username'];
		$now = time();
		$userData = $this->user_model->find($username,"username"," AND deleted_at IS NULL ");
		$password = md5(strtolower($username).$post_data['password']);

		if(isset($userData['last_active'])){
			$last_active = $userData['last_active'];
			$now = time();
			$expiry =date('H:i:s',time()).'-'.date('H:i:s',$last_active);
			$expiry_2 = time() - $last_active;
			if($expiry_2 >= 600){
				//update is login
				$param = array('is_login'=>0);
				$this->user_model->update($userData['id'],$param);
			}
		}

		if(isset($userData['password']) && isset($userData["id"]) &&  $userData['password'] == $password){
			$userData['is_login'] = 0;
			if($userData['is_login'] == 1){
				$result['msg'] = 'twice login';
			}else{
				$User_access_group = $this->User_access_group_model->find($userData["user_access_group_id"]);
				if(isset($User_access_group["id"])){
					$userData["role"] = $User_access_group["access_right"];
				}else{
					$userData["role"] ='';
				}

				//update is login
				$param = array('is_login'=>1,'last_active'=>time());
				$this->user_model->update($userData['id'],$param);

				$this->session->set_userdata("adminData",$userData);
				$result['data'] = $userData;
				$result['error'] = 0;
				$result['msg'] = '';	
			}

		}
		
		echo json_encode($result);
	}
	
	public function logout(){
		$userData = $this->session->userdata("adminData");
		//update is login
		$param = array('is_login'=>0,'last_active'=>time());
		$this->user_model->update($userData['id'],$param);
		$this->session->unset_userdata('adminData');
		session_destroy();
	
	}
}