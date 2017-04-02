<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends AR_Controller {
	public function __construct(){
		parent::__construct();
		//$this->load->model("AR_Model");
		$this->load->model("user_model");
		$this->load->model("user_group_model");
		$this->load->model("user_type_model");
		// $this->is_login('admin');
	}
	public function index()
	{
	
		$data['title'] = "User List"; //title to show up in last part of breadcrumb and title of a page
		$data['add_new_link'] = "user_list/add_new"; // anchor link for add new data for this module
		$temp_data = $this->temporary_data;
		
		$data['msg'] = isset($temp_data['msg']) ? $temp_data['msg'] : "";
		$data['msg_status'] = isset($temp_data['msg_status']) ? $temp_data['msg_status'] : "";
		$data_status = "";
		//BEGIN SEARCH
			$data['txtSearch'] = isset($this->input_data['txtSearch']) ? $this->input_data['txtSearch'] : "";
			$search["f_name"] = "%".$data['txtSearch']."%";
			$search["l_name"] = "%".$data['txtSearch']."%";
			$search["email"] = "%".$data['txtSearch']."%";
			$search["mobile"] = "%".$data['txtSearch']."%";
		//END SEARCH
		//BEGIN SORTING
			$data['sort_by'] = isset($this->input_data['sort_by']) ? $this->input_data['sort_by'] : "created_at";
			$data['order'] = isset($this->input_data['order']) ? $this->input_data['order'] : "desc";
		//END SORTING
		//BEGIN PAGE LIMIT
			$data['limit'] = isset($this->input_data['limit']) ? $this->input_data['limit'] : "10";
			$data['page'] = (isset($this->input_data['page'])&&$this->input_data['page']!="") ? $this->input_data['page'] : "1";
		//END PAGE LIMIT
		//BEGIN DATA STATUS FILTER
		$data['data_status'] = isset($this->input_data['data_status']) ? $this->input_data['data_status'] : "";
		if($data['data_status'] == "DELETED"){
			$data_status = "only_trash";//if the status is deleted, then only pull deleted data
		}
		//END DATA STATUS FILTER
		$searchString = generateSearchString($search,$data['sort_by'],$data['order'],$data['page'],$data['limit']);
		$data['users'] = $this->user_model->all($searchString,$data_status);
		$totalSearchString = generateSearchString($search);
		$data['total'] = $this->user_model->count($totalSearchString,$data_status);
		$keys = array("limit","page","sort_by","order");
		foreach($keys as $key){
			$queryStringArray[$key] = $data[$key];
		}

		$queryString = http_build_query($queryStringArray);
		$data['base_url_pagination'] = current_url()."?".$queryString;

		$data['pagination'] = generateBootstrapPagination($data['base_url_pagination'],$data['total'],$data['limit']); //call ar_helper pagination function

		$this->template->loadView('admin/userList',$data,"admin");//show user list
	}
	public function add_new(){
		$data['title'] = "Add New User";
		
		$data['user_group_data'] = $this->user_group_model->all();
		$data['user_type_data'] = $this->user_type_model->all();
				
		//BEGIN ADD ACTION
		if(isset($this->input_data['btnSubmit'])){
			//BEGIN GETTING INPUT
				$user_detail = $this->input_data;
				$user_name = $user_detail['user_name'];
				$password = $user_detail['password'];
				$flag = 1;
				if($user_detail["password"] != $user_detail["conf_password"]){
					$flag = 0;
					$msg = "password mismatch";
				}
				$user_detail['user_name'] = $user_name;
				$user_detail['password'] = md5(strtolower($user_name).$password);
				$user_detail['is_active'] = isset($user_detail['active']) ? 1 : 0 ;

				//validate all inputs
				// BEGIN EMPTY VALIDATION
					$required_fields = array("user_group_id","user_type_id","f_name","l_name","password","user_name","mobile","email");
					if($flag == 1 )
					{
						foreach($required_fields as $fields){
							if(!isset($user_detail[$fields]) || $user_detail[$fields] == ""){
								$flag = 0;
								$msg = "fields required";
								break;
							}
						}
					}
				//END EMPTY VALIDATION
				//BEGIN THROW EMPTY INPUT

					unset($user_detail['btnSubmit']);
					unset($user_detail['conf_password']);
					unset($user_detail['active']);
				//END THROW EMPTY INPUT
			//END GETTING INPUT
			if($flag == 1)
			{
				$new_user_id = $this->user_model->add($user_detail); //create new user
				
				
				$passData['msg'] = "success creating user ".$user_name;
				$passData['msg_status'] = " success";
				$this->session->set_userdata("temporary_data",$passData);
				redirect('user_list');
			}
			else{
				//show the user form with errors
				$data['user_data'] = $user_detail;
				$data['msg'] = $msg;
			}	
		}
		// //END ADD ACTION
		$this->template->loadView('admin/userForm',$data,"admin");//show user form
	}
	public function edit($id){
		$data['title'] = "Edit User";
		$data['user_data'] = $this->user_model->find($id);
		$data['user_group_data'] = $this->user_group_model->all();
		$data['user_type_data'] = $this->user_type_model->all();
		//BEGIN EDIT ACTION
		if(isset($this->input_data['btnSubmit'])){
			//BEGIN GETTING INPUT
				$user_detail = $this->input_data;

				$user_name = $user_detail['user_name'];
				$password = $user_detail['password'];
				$flag = 1;
				$user_detail['user_name'] = $user_name;
				$user_detail['password'] = md5(strtolower($user_name).$password);
				$user_detail['is_active'] = isset($user_detail['active']) ? 1 : 0 ;

				
				//check if password field is empty or not
				if($password != "" ){
					$user_detail['password'] = md5(strtolower($user_name).$password);
					if($_POST["password"] != $user_detail["conf_password"]){
						$flag = 0;
						$msg = "password mismatch";
					}
				}else{
					unset($user_detail['password']);
				}
				// BEGIN EMPTY VALIDATION
					$required_fields = array("user_group_id","user_type_id","f_name","l_name","user_name","mobile","email");
					if($flag == 1 )
					{
						foreach($required_fields as $fields){
							if(!isset($user_detail[$fields]) || $user_detail[$fields] == ""){
								$flag = 0;
								$msg = "fields required";
								break;
							}
						}
					}
				//END EMPTY VALIDATION
				//BEGIN THROW EMPTY INPUT
					unset($user_detail['btnSubmit']);
					unset($user_detail['conf_password']);
					unset($user_detail['active']);
				//END THROW EMPTY INPUT]
			//END GETTING INPUT
			//if all validation is ok
			if($flag == 1){				
				$this->user_model->update($id,$user_detail);
				$passData['msg'] = "success editing user ".$user_name;
				$passData['msg_status'] = " success";
				$this->session->set_userdata("temporary_data",$passData);
				redirect('user_list');
				
			}
			else{
				//show the user form with errors
				$data['user_data'] = $user_detail;
				$data['msg'] = $msg;
			}
		}
		//END ADD ACTION
		$this->template->loadView('admin/userForm',$data,"admin");//show user form

	}
	public function profile(){
		$data['title'] = "Profile";
		$adminData = $this->session->userData("adminData");
		$id= $adminData['id'];
		$data['user_data'] = $this->user_model->find($adminData['id']);
		$data['user_group_data'] = $this->user_group_model->all();
		//BEGIN EDIT ACTION
		if(isset($this->input_data['btnSubmit'])){
			//BEGIN GETTING INPUT
				$user_detail = $this->input_data;				
				$user_name = $user_detail['user_name'];
				$password = $user_detail['password'];
				$flag = 1;
				$user_detail['user_name'] = $user_name;
				$user_detail['password'] = md5(strtolower($user_name).$password);
				$user_detail['is_active'] = isset($user_detail['active']) ? 1 : 0 ;

				
				//check if password field is empty or not
				if($password != "" ){
					$user_detail['password'] = md5(strtolower($user_name).$password);
					if($_POST["password"] != $user_detail["conf_password"]){
						$flag = 0;
						$msg = "password mismatch";
					}
				}else{
					unset($user_detail['password']);
				}
				// BEGIN EMPTY VALIDATION
					$required_fields = array("f_name","l_name","user_name","mobile","email");
					if($flag == 1 )
					{
						foreach($required_fields as $fields){
							if(!isset($user_detail[$fields]) || $user_detail[$fields] == ""){
								$flag = 0;
								$msg = "fields required";
								break;
							}
						}
					}
				//END EMPTY VALIDATION
				//BEGIN THROW EMPTY INPUT
					unset($user_detail['btnSubmit']);
					unset($user_detail['conf_password']);
					unset($user_detail['active']);
				//END THROW EMPTY INPUT]
			//END GETTING INPUT
			//if all validation is ok
			if($flag == 1){
				$this->user_model->update($id,$user_detail);
				$data['msg'] = "success update profile ".$user_name;
				$data['msg_status'] = "success";
				
				
			}
			else{
				//show the user form with errors
				$data['user_data'] = $user_detail;
				$data['msg'] = $msg;
				$data['msg_status'] = " success";
			}
		}
		$id= $adminData['id'];
		$data['user_data'] = $this->user_model->find($adminData['id']);
		
		//END ADD ACTION
		$this->template->loadView('admin/userProfile',$data,"admin");//show user form

	}
	public function softDelete($id){
		$userData = $this->user_model->find($id);
		$this->user_model->delete($id);
		$passData['msg'] = "success deleting user ".$userData['user_name'];
		$passData['msg_status'] = " success";
		$this->session->set_userdata("temporary_data",$passData);
		redirect('user_list');

	}
	public function delete($id){
		$userData = $this->user_model->find($id);
		$this->user_model->real_delete($id);
		$passData['msg'] = "success deleting user ".$userData['user_name'];
		$passData['msg_status'] = "success";
		$this->session->set_userdata("temporary_data",$passData);
		redirect('user_list');

	}
	public function restore($id){
		$userData = $this->user_model->find($id);
		$this->user_model->restore($id);
		$passData['msg'] = "success restoring user ".$userData['user_name'];
		$passData['msg_status'] = "success";
		$this->session->set_userdata("temporary_data",$passData);
		redirect('user_list');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */