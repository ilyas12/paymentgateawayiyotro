<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PaymentTerms extends AR_Controller {

	public function __construct(){

		parent::__construct();

		$this->load->model("payment_terms_model");

		$this->set_session();

	}

	public function index()
	{

		echo json_encode($this->result);

    }

	public function load()

	{

		$result = $this->result;

		$data = $this->payment_terms_model->all();

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

					"id" => $value['id'],

					"name" => $value['name'],

					"description" => $value['description']

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



		$data = $this->payment_terms_model->find($id);



		$result['error'] = 0;



		$result['msg'] = 'Data has been saved';



		$result['data'] = $data;



		echo json_encode($result);



	}

	public function update($id)

	{



		$post_data = $this->input_data;


		$this->payment_terms_model->update($id,$post_data);



		$result['error'] = 0;



		$result['msg'] = 'Data has been saved';



		$result['data'] = null;



		echo json_encode($result);



	}



	public function insert()

	{



		$result = $this->result;



		$post_data = $this->input_data;



		$id = $this->payment_terms_model->add($post_data);



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



		$this->payment_terms_model->delete($id);



	}







}



