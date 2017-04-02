<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class CompanyBank extends AR_Controller {

	protected $_post_data;

	public function __construct(){

		parent::__construct();
		$this->load->model("company_bank_account_model");
		$this->load->model("bank_model");
		$this->load->model("company_model");
		$this->set_session();

	}


	public function index()
	{

		echo json_encode($this->result);

	}

	public function load()
	{

		$result = $this->result;

		$data = $this->company_bank_account_model->all();
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

				$bank_name = '';
				$bank = $this->bank_model->find($value['bank_id']);
				if(count($bank)>0){ $bank_name	= $bank['name']; }

				$company_name = '';
				$company = $this->company_model->find($value['company_code']);
				if(count($company)>0){ $company_name	= $company['name']; }


				$records["data"][] = array(
					"id" => $value['id'],
					"company_name"=>$company_name,
					"bank_name"=>$bank_name,
					"account_name" => $value['account_name'],
					"account_number"	=> $value['account_number'],
					"branch"	=> $value['branch'],
					"active"	=> $value['active'],
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

		$data = $this->company_bank_account_model->find($id);

		$bank = $this->bank_model->find($data['bank_id']);

		$data['bank_name'] = $bank ['name'];
		$data['bank_id'] = $data['bank_id'];

		$result['error'] = 0;
		$result['msg'] = 'Data has been saved';
		$result['data'] = $data;

		echo json_encode($result);
	}

	public function update($id)
	{

		$post_data = $this->input_data;

		$post_data['company_code'] = 1;
		$this->company_bank_account_model->update($id,$post_data);
		$result['error'] = 0;
		$result['msg'] = 'Data has been saved';
		$result['data'] = null;

		echo json_encode($result);

	}



	public function insert()
	{

		$result = $this->result;
		$post_data = $this->input_data;

		$post_data['company_code'] = 1;
		$id = $this->company_bank_account_model->add($post_data);

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
		$this->company_bank_account_model->delete($id);

	}


}



