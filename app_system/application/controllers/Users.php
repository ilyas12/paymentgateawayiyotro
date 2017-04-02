<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users extends AR_Controller {
	public function __construct(){
		parent::__construct();
		$this->is_login();
		$this->set_session();
		
		$this->load->model("user_access_group_model");
		$this->load->model("users_model");
		
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

		$fields = $this->users_model->allow_insert;
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
		$data = $this->users_model->all($where." ORDER BY username ASC $limit ");
		$total = $this->users_model->all($where." ORDER BY username ASC ");

		if(count($data) > 0)
		{
			$i=0;
			foreach($data as $value)
			{

				$i++;
				$records["data"][] = array(
					"id" => $value['id'],
					"username" => $value['username'],
					"full_name" => $value['first_name']." ".$value['last_name'],
					"email"=>$value["email"],
					"phone"=>$value["phone"],
					"active"=>$value["active"],
					"last_login"=>$value["last_login"]
				);
			}
		}
		
		$records["total"] = count($total);

  
  		echo json_encode($records);
	}

	public function get($id)
	{
		$value = $this->users_model->find($id);

		$result['error'] = 0;
		$result['msg'] = 'Data has been saved';
		$result["data"] = $value;
		$result["data"]["full_name"] = $value["first_name"]." ".$value["last_name"];
		$result["data"]["birthday"] = view_date($value["birthday"]);
		$result["data"]["saldo"] = number_format($value["saldo"],2);

		//get provinsi
		if($value['provinsi']!=''){
			$provinsi = $this->db->query(" SELECT * FROM master_provinsi WHERE id =  ".$value["provinsi"])->row_array();
			$result["data"]["provinsi"] = isset($provinsi["id"])?$provinsi["name"]:"";	
		}

		//get kota kab
		if($value['kota']!=''){
			$kota = $this->db->query(" SELECT * FROM master_kokab WHERE id =  ".$value["kota"])->row_array();
			$result["data"]["kota"] = isset($kota["id"])?$kota["name"]:"";
		}

 		echo json_encode($result);
	}

	public function inactive_data($id)
	{
		$post_data = $this->input_data;
		$this->users_model->update($id,array("active"=>0));
		$result['error'] = 0;
		$result['msg'] = 'Data has been saved';
		$result['data'] = null;

		echo json_encode($result);
	}
	public function active_data($id)
	{
		$post_data = $this->input_data;
		$this->users_model->update($id,array("active"=>1));
		$result['error'] = 0;
		$result['msg'] = 'Data has been saved';
		$result['data'] = null;

		echo json_encode($result);
	}

}