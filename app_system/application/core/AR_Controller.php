<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class AR_Controller extends CI_Controller {
	protected $input_data = array();
	protected $temporary_data;
	protected $data;
	protected $controller_name;
	protected $module_name;
	protected $function_name;
	protected $result;
	public function __construct(){
		parent::__construct();

		if($_POST){
			$this->input_data = $this->input->post();
		}	
		else if($_GET){
			$this->input_data= $this->input->get();
		} else {
			
		
			$this->input_data = json_decode(file_get_contents("php://input"), true);
		}

	}

	public function set_session(){
		$this->load->model("user_model");
		$post_data = $this->input_data;
		
		if(isset($_GET['user_login']) || isset($_POST['user_id']) ){
			$user_id = isset($_GET['user_login'])?$_GET['user_login']:$post_data['user_id'];
			$userData = $this->user_model->find($user_id);
			if(isset($userData['id'])){
				//update is login
				$param = array('is_login'=>1,'last_active'=>time());
				$this->user_model->update($userData['id'],$param);

				$this->session->set_userdata("adminData",$userData);
			}else{
				$this->is_login();
			}
			
		}
	}

	public function is_login()
	{
		$userData =$this->session->userdata("adminData");
		if(!isset($userData['id'])){
			echo json_encode(array('error'=>404,'msg'=>"server not found"));
			die();
		}
	}
	

	
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */