<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserGroupController extends AR_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("user_group_model");
		$this->load->model("admin_navigation_model");
        $this->load->model("admin_navigation_category_model");

	}

	public function userGroupList()
	{
	
		$data['title'] = "User Group"; //title to show up in last part of breadcrumb and title of a page
		$data['add_new_link'] = "user_group_add"; // anchor link for add new data for this module
		$temp_data = $this->temporary_data;
		
		$data['msg'] = isset($temp_data['msg']) ? $temp_data['msg'] : "";
		$data['msg_status'] = isset($temp_data['msg_status']) ? $temp_data['msg_status'] : "";
		$data_status = "";
		//BEGIN SEARCH
			$data['txtSearch'] = isset($this->input_data['txtSearch']) ? $this->input_data['txtSearch'] : "";
			$search["name"] = "%".$data['txtSearch']."%";
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
		$data['table_datas'] = $this->user_group_model->all($searchString,$data_status);
		$totalSearchString = generateSearchString($search);
		$data['total'] = $this->user_group_model->count($totalSearchString,$data_status);
		$keys = array("limit","page","sort_by","order");
		foreach($keys as $key){
			$queryStringArray[$key] = $data[$key];
		}

		$queryString = http_build_query($queryStringArray);
		$data['base_url_pagination'] = current_url()."?".$queryString;

		$data['pagination'] = generateBootstrapPagination($data['base_url_pagination'],$data['total'],$data['limit']); //call ar_helper pagination function

		$this->template->loadView('admin/userGroupList',$data,"admin");//show user list
	}
	public function addUserGroup(){
		$data['title'] = "Add New User Group";
		//BEGIN ADD ACTION
		$data['admin_navigation_categories'] = $this->admin_navigation_category_model->all();
		if(isset($this->input_data['btnSubmit'])){
			$flag = 1;
			$input_data = $this->input_data;
			if($input_data['name'] == ""){
				$flag = 0;
			}
			else{
				$input_array['name'] = $input_data['name'];
				$input_array['access_right'] = implode(",", $input_data['menu_item']);
			}
			if($flag == 1)
			{
				$this->user_group_model->add($input_array); //create new user

				$passData['msg'] = "success creating user group ".$input_data['name'];
				$passData['msg_status'] = " success";
				$this->session->set_userdata("temporary_data",$passData);
				redirect('user_group_list');
			}
			else{
				//show the user form with errors
				$data['user_data'] = $user_detail;
				$data['msg'] = $msg;
			}	
		}
		//END ADD ACTION
		$this->template->loadView('admin/userGroupForm',$data,"admin");//show user form
	}
	public function editUserGroup($id){
		$data['title'] = "Edit User Group";
		$data['user_group_data'] = $this->user_group_model->find($id);
		$data['admin_navigation_categories'] = $this->admin_navigation_category_model->all();
		//BEGIN EDIT ACTION
		if(isset($this->input_data['btnSubmit'])){
			$flag = 1;
			$input_data = $this->input_data;
			if($input_data['name'] == ""){
				$flag = 0;
			}
			else{
				$input_array['name'] = $input_data['name'];
				$input_array['access_right'] = implode(",", $input_data['menu_item']);
			}
			
			if($flag == 1){
				$this->user_group_model->update($id,$input_array);
				$passData['msg'] = "success editing user group ".$input_array['name'];
				$passData['msg_status'] = " success";
				$this->session->set_userdata("temporary_data",$passData);
				redirect('user_group_list');
				
			}
			else{
				//show the user form with errors
				$data['user_data'] = $user_detail;
				$data['msg'] = $msg;
			}
		}else{
		//END ADD ACTION
		$this->template->loadView('admin/userGroupForm',$data,"admin");//show user form
		}

	}
	public function checkUserAjax(){
		//this function is to check for duplicated data
		$user = $this->user_model->find($this->input_data['user_name'],"user_name");
		if(count($user) == 0){
			echo 1;
		}
		else
			echo 0;
	}
	public function softDelete($id){
			$userGroupData = $this->user_group_model->find($id);
			$this->user_group_model->delete($id);
			$passData['msg'] = "success deleting user group ".$userGroupData['name'];
			$passData['msg_status'] = " success";
			$this->session->set_userdata("temporary_data",$passData);
			redirect('user_group_list');

	}
	public function delete($id){
		$this->load->model("user_model");

		/*$userData = $this->user_model->find($id,"user_group_id");
		if(empty($userData))
		{*/
			$userGroupData = $this->user_group_model->find($id);
			$this->user_group_model->realDelete($id);
			$passData['msg'] = "success deleting user group ".$userGroupData['name'];
			$passData['msg_status'] = " success";
			$this->session->set_userdata("temporary_data",$passData);
			redirect('user_group_list');
		//}

	}
	public function restore($id){
		$userData = $this->user_group_model->find($id);
		$this->user_group_model->restore($id);
		$passData['msg'] = "success restoring user Group ".$userData['user_name'];
		$passData['msg_status'] = " success";
		$this->session->set_userdata("temporary_data",$passData);
		redirect('user_group_list');

	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */