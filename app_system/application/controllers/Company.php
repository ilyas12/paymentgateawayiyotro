<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Company extends AR_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->is_login();
		$this->set_session();

		$this->load->model("company_model");
		
	}
	public function load()
	{
		$result = $this->result;
		$data = $this->company_model->find(1);
		if(isset($data['id'])){
			$result['error'] 	= 0;
			$result['msg']	 	= 'success';
			$result['data'] 	= $data;
		}else{
			$result['error'] 	= 1;
			$result['msg']	 	= 'Failed';
			$result['data'] 	= array();
		}
		echo json_encode($result);
	}
	public function update($id)
	{
		$post_data = $this->input_data;
		$this->company_model->update(1,$post_data);
		$result['error'] = 1;
		$result['msg'] = 'Data has been saved';
		$result['data'] = null;
		echo json_encode($result);
	}
	public function insert()
	{
		$result = $this->result;
		$post_data = $this->input_data;
		$id = $this->company_model->add($post_data);
		if($id)
		{
			$result['error'] = 1;
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
		//$this->company_model->delete($id);
	}
}