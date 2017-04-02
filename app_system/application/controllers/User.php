<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends AR_Controller {
	public function __construct(){
		parent::__construct();
		$this->is_login();
		$this->set_session();
		
		$this->load->model("user_access_group_model");
		$this->load->model("user_model");
		
	}
	public function index()
	{
		echo json_encode($this->result);
	}
	public function load()
	{
		$result = $this->result;

		$input_data = $this->input_data;

		$page = isset($input_data["page"]) && trim($input_data["page"])!=""?$input_data["page"]:1;
		$per_page = isset($input_data["per_page"]) && trim($input_data["per_page"])!=""?$input_data["per_page"]:0;
		$offset = ($page - 1) * $per_page;

		$fields = $this->user_model->allow_insert;
		$fields = explode(",", $fields);

		$where ="";
		$where = " AND deleted_at IS NULL ";
		if(isset($input_data["q"]) && $input_data["q"] !=""){
			$where .=" AND ( ";
			foreach ($fields as $key => $value) {
				$q = trim($input_data["q"]);
				$where .= $key == 0?$value." LIKE '%".$q."%' ":" || ".$value." LIKE '%".$q."%' ";
			}
			$where .=" ) ";
		}

		$limit = $per_page > 0 ?" LIMIT $offset,$per_page ":"";
		$data = $this->user_model->all($where." ORDER BY username ASC $limit ");
		$total = $this->user_model->all($where." ORDER BY username ASC ");

		if(count($data) > 0)
		{
			$i=0;
			foreach($data as $value)
			{
				$group_name = $this->user_access_group_model->find($value['user_access_group_id']);
				$group_name = isset($group_name['id'])?$group_name['name']:"";
				$i++;
				$records["data"][] = array(
					"id" => $value['id'],
					"full_name" => $value['full_name'],
					"group_name" => $group_name,
					"username" => $value['username'],
					"email"=>$value["email"],
					"mobile"=>$value["mobile"]
				);
			}
		}
		
		$records["total"] = count($total);

  
  		echo json_encode($records);
	}

	public function get($id)
	{
		$value = $this->user_model->find($id);

		$result['error'] = 0;
		$result['msg'] = 'Data has been saved';
		$user_group = $this->user_access_group_model->find($value['user_access_group_id']);
		$group_name 		= (isset($user_group['name'])) ? $user_group['name'] : "";
		$group_id			= $value['user_access_group_id'];
		$result["data"] = array(
			"id" 				=> $value['id'],
			"username" 		=> $value['username'],
			"full_name" 		=> $value['full_name'],
			"email"				=> $value['email'],
			"mobile"				=> $value['mobile'],
			"group_name" 		=> $group_name,
			"id_group" 			=> $group_id,
		);
		echo json_encode($result);
	}
	public function update($id)
	{
		$post_data = $this->input_data;
		$this->user_model->update($id,$post_data);
		$result['error'] = 0;
		$result['msg'] = 'Data has been saved';
		$result['data'] = null;
		echo json_encode($result);
	}
	public function insert()
	{
		$result = $this->result;
		$post_data = $this->input_data;
		
		
		$post_data['password'] = md5(strtolower($this->input_data['username']).$this->input_data['password']);
 		
		$id = $this->user_model->add($post_data);
		if($id)
		{
			$result['error'] = 0;
			$result['msg'] = 'Data has been saved';
			$result['data'] = $id;
		}
		else
		{
			$result['error'] = 1;
			$result['msg'] = 'Failed';
		}
		echo json_encode($result);
	}
	public function delete($id)
	{
		$result = $this->result;
		$this->user_model->delete($id);
	}
	public function get_data_by_session()
	{
		$user_data = $this->session->userdata('adminData');
		$value = $this->user_model->find($user_data["id"]);
		$result['error'] = 0;
		$result['msg'] = 'Data has been saved';

		$result["data"] = array(
			"id" 				=> $value['id'],
			"username" 		=> $value['username'],

		);

		echo json_encode($result);
	}
	public function change_pwd($id)
	{
		$post_data = $this->input_data;
		$user_data = $this->user_model->find($id);


		$result['error'] = 0;
		$result['msg'] = 'Data has been saved';

		$new_password = md5(strtolower($user_data['username']).$this->input_data['password']);
		$this->user_model->update($user_data["id"],array("password"=>$new_password));
		

		echo json_encode($result);
	}
	public function change_password()
	{
		$post_data = $this->input_data;
		$user_data = $this->session->userdata('adminData');
		$value = $this->user_model->find($user_data["id"]);

		$post_data['old_password'] = md5(strtolower($user_data['username']).$this->input_data['old_password']);


		$result['error'] = 0;
		$result['msg'] = 'Data has been saved';

		if($post_data["old_password"] !=$user_data["password"]){
			
			$result['error'] = 1;
			$result['msg'] = 'old password wrong';			
		}else{

			$new_password = md5(strtolower($user_data['username']).$this->input_data['new_password']);
			$this->user_model->update($user_data["id"],array("password"=>$new_password));
		}

		echo json_encode($result);
	}

	public function check_unique($user_id="")
	{
		$response = [
            'isValid' => TRUE,
            'message' => 'GOOD'
        ];
		$post_data = $this->input_data;
		$username = $post_data['value'];
		$rules = $post_data['rules'];
		foreach( $rules AS $rule ){
	        if( in_array($rule,['unique']) ){
	            /// unique username
	            if($user_id == ""){
	            	$user_data = $this->user_model->find($username,'username',' AND deleted_at IS NULL ');
	            }else{
	            	$user_data = $this->user_model->find($username,'username','  AND deleted_at IS NULL AND user_id !=  '.$user_id);
	            }
	            if(isset($user_data['id'])){
	            	$response = [
			            'isValid' => FALSE,
			            'message' => 'username is not unique'
			        ];

	            }

	        }
	    }

	    echo json_encode($response);

	}
}