<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authentication extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
	}
	public function index(){
		$data['title'] = 'Login';
		
		$this->template->loadView("admin/loginForm",$data,"");
	}

	public function login(){
		if($this->validation() == true){
			$this->load->model("user_model");		
			$user_name = $this->input->post('user_name',true);
			$password = $this->input->post('password',true);			
			$userData = $this->user_model->find($user_name,"user_name");
			$password = md5(strtolower($user_name).$password);
			if($userData['is_active'] == 1){				
				if(isset($userData['password']) && $userData['password'] == $password){
					$this->session->set_userdata("adminData",$userData);					
					redirect("dashboard");
				}
				else
				{
					$error['msg'] = "Incorrect login cridentials";
					$this->session->set_flashdata($error);
            		redirect('admin_login');
				}
			}else{
				$error['msg'] = "This user is not active";
				$this->session->set_flashdata($error);
            	redirect('admin_login');
			}
		}
		else{
            $error['msg'] = strip_tags(validation_errors());
            $this->session->set_flashdata($error);
            redirect('admin_login');
		}

	}

	public function validation(){
        $this->form_validation->set_rules('user_name', 'Username', 'required',array('required' => 'Please type username correctly'));
        $this->form_validation->set_rules('password', 'Password', 'required',array('required' => 'Please type password correctly'));

        return $this->form_validation->run();
   	}

	public function logout(){
		$this->session->sess_destroy();
		redirect("admin_login");
	}
}
