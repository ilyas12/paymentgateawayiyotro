<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Price extends AR_Controller {

	protected $_post_data;

	public function __construct(){

		parent::__construct();
		$this->load->model("price_model");
		$this->set_session();

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

		$fields = $this->price_model->allow_insert;
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
		$data = $this->price_model->all($where." ORDER BY fee ASC $limit ");
		$total = $this->price_model->all($where." ORDER BY fee ASC ");

		$records["data"] = $data; 
		$records["total"] = count($total);

  
  		echo json_encode($records);

	}


	public function get($id)
	{

		$data = $this->price_model->find($id);
		
		$result['error'] = 0;
		$result['msg'] = 'Data has been saved';
		$result['data'] = $data;

		echo json_encode($result);
	}

	public function update($id)
	{

		$post_data = $this->input_data;
		$this->price_model->update($id,$post_data);
		$result['error'] = 0;
		$result['msg'] = 'Data has been saved';
		$result['data'] = null;

		echo json_encode($result);

	}



	public function insert()
	{

		$result = $this->result;
		$post_data = $this->input_data;
		$id = $this->price_model->add($post_data);

		if($id)
		{
		
			$result['error'] = 0;
			$result['msg'] = 'Data has been saved';
			$result['data'] = $id;

		}else{

			$result['msg'] = 'Failed';

		}

		echo json_encode($result);

	}



	public function delete($id)
	{

		$result = $this->result;
		$this->price_model->delete($id);

	}


}



