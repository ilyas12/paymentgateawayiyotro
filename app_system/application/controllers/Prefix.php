<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Prefix extends AR_Controller {
	protected $_post_data;
	public function __construct(){
		parent::__construct();
		$this->load->model("prefix_model");
		
		$this->set_session();
	}
	public function index()
	{
		echo json_encode($this->result);
	}
	public function load()
	{
		$result = $this->result;
		$data = $this->prefix_model->all();
		$iTotalRecords = count($data);
		$iDisplayLength = isset($_REQUEST['length']) ? intval($_REQUEST['length']) : 0;
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
		$iDisplayStart = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;
		$sEcho = isset($_REQUEST['draw']) ? intval($_REQUEST['draw']) : 0;
		$records = array();
		$records["data"] = array(); 
		$end = $iDisplayStart + $iDisplayLength;
		$end = $end > $iTotalRecords ? $iTotalRecords : $end;
		if(count($data) > 0)
		{
			$i=0;
			foreach($data as $value)
			{
				$i++;
				$records["data"][] = array(
					"i" => $i,
					"id" => $value['id'],
					"prefix" => $value['prefix'],
					"description" => $value['description'],
					"running_number" => $value['running_number'] > 0?$value['running_number']:0,
                    "length" => $value['length'] > 0?$value['length']:0
				);
			}
		}
		
		$records["draw"] = $sEcho;
		$records["recordsTotal"] = $iTotalRecords;
		$records["recordsFiltered"] = $iTotalRecords;
  
  		echo json_encode($records);
	}
	public function get($id)
	{
		$data = $this->prefix_model->find($id);
		$result['error'] = 0;
		$result['msg'] = 'Data has been saved';
        
        if($data["running_number"] ==""){
            $data["running_number"] =0;
        }
		$result['data'] = $data;
		echo json_encode($result);
	}
	public function update($id)
	{
		$post_data = $this->input_data;
		$this->prefix_model->update($id,$post_data);
		$result['error'] = 0;
		$result['msg'] = 'Data has been saved';
		$result['data'] = null;
		echo json_encode($result);
	}
	public function insert()
	{
		$result = $this->result;
		$post_data = $this->input_data;
		$id = $this->prefix_model->add($post_data);
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
		$this->prefix_model->delete($id);
	}
}