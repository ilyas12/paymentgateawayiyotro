<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserGroup extends AR_Controller {

	protected $_post_data;

	public function __construct(){

		parent::__construct();
		
		$this->is_login();
		$this->set_session();

		$this->load->model("user_access_group_model");
		$this->load->model("admin_navigation_category_model");
		$this->load->model("admin_navigation_model");

		
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

		$fields = $this->user_access_group_model->allow_insert;
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
		$data = $this->user_access_group_model->all($where." ORDER BY name ASC $limit ");
		$total = $this->user_access_group_model->all($where." ORDER BY name ASC ");

		if(count($data) > 0)
		{
			$i=0;
			foreach($data as $value)
			{
				$i++;
				$records["data"][] = array(
					"id" => $value['id'],
					"name" => $value['name'],
				);
			}
		}
		$records["data"] = $data; 
		$records["total"] = count($total);

  
  		echo json_encode($records);

	}

	public function get($id)
	{

		$data = $this->user_access_group_model->find($id);

		$result['error'] = 0;

		$result['msg'] = 'Data has been saved';

		$result['data'] = $data;

		echo json_encode($result);

	}
	public function update($id)
	{

		$post_data = $this->input_data;

		$this->user_access_group_model->update($id,$post_data);

		$result['error'] = 0;

		$result['msg'] = 'Data has been saved';

		$result['data'] = null;

		echo json_encode($result);

	}

	public function insert()
	{

		$result = $this->result;

		$post_data = $this->input_data;

		$id = $this->user_access_group_model->add($post_data);

		if($id)
		{

			$result['error'] = 0;

			$result['msg'] = 'Data has been saved';

			$result['data'] = $id;

		}
		else
		{

			$result['msg'] = 'Failed';

		}

		echo json_encode($result);

	}

	public function delete($id)
	{

		$result = $this->result;

		$this->user_access_group_model->delete($id);

	}

	public function manage_link($id){
	
		$result = $this->result;
	
		$pages = array();
	
		$category = $this->admin_navigation_category_model->all();
		
		$userdata = $this->user_access_group_model->find($id);
		
		$privilege = explode(",",$userdata['access_right']);
		
		foreach($category as $id_category){
			
			$child = $this->admin_navigation_model->all(' AND admin_navigation_category_id = "'.$id_category['id'].'"');
			
			$pages[] = array("name"=>$id_category['label'],"child"=>$child);
			
		}
		
		$result['error'] = 0;
		
		$result['data'] = $pages;
		
		$result['privilege'] = $privilege;
		
		echo json_encode($result);
		
	}
	
	public function store_link($id){
	
		$post_data = $this->input_data;
		
		unset($post_data['user_id']);
		
		$param = array("access_right"=>implode(",",$post_data['access_right']));
		
		$this->user_access_group_model->update($id,$param);
	
	}

}

