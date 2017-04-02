<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends AR_Controller {

	function __construct(){
		parent::__construct();
	}

	public function index()
	{
		$adminData = $this->session->userdata("adminData");
		$id = $adminData['id'];

		$data['title'] = "DASHBOARD";
		$data['latest_sales'] = $this->latest_sales($id);
		$data['invoice_last_data'] = $this->invoice_last_data($id);
		$this->template->loadView('admin/dashboard',$data,"admin");
	}

	private function latest_sales($id){
		$data = $this->db->select('s.*, c.name as client_name')
						->join('client c','c.id = s.client_id','left')
						->where(array("s.user_id"=>$id))
						->get('sales s')
						->result();
		return $data;
	}

	private function invoice_last_data($id){
		$where = array("i.due_date <="=>"DATE(CURDATE())", "i.balance !="=>0);
		$data = $this->db->select('i.*, c.name as client_name')
						->join('client c','c.id = i.client_id','left')
						->where($where)
						->get('invoice i')
						->result();
		return $data;
	}
}
