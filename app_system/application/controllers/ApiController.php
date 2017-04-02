<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class ApiController extends AR_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->is_login();
		$this->set_session();
		
		$this->load->library("Access_management");
		$this->load->model("Company_model");
		$this->load->library("Selector_builder");
		
		
	}
	public function index(){
		$result = $this->result;
		echo json_encode($this->result);
	}
	
	public function get_company($id = 1)
	{
		$result = $this->result;
		$post_data = $this->input_data;
		$result['data'] = $this->Company_model->find($id);
		$result['error'] = 0;
		echo json_encode($result);
	}
	public function getsidebar(){
		$result = $this->result;
		$page = array();
		$userData = $this->session->userdata("adminData");
		$userData = isset($userData['id'])?$userData['user_access_group_id']:"";
		
		if($userData >0 ){
			$page =  $this->access_management->generate_admin_sidemenu_api($userData);	
		}else{
			$page = array();
		}

		$result['data']=$page;
		
		echo json_encode($result);
	}

	
	public function language(){
		/*
		$this->load->model("Lang_presentation_model");
		$this->load->model("Lang_list_model");
	
		$lang = isset($_GET['lang']) ? $_GET['lang'] : "en_US" ;
		$lang_list = $this->Lang_list_model->find($lang,"name");

		$field = isset($lang_list["id"])?$lang_list["abbrev"]:"en";
		
		$data = $this->Lang_presentation_model->all();
		$res = array();
		foreach($data as $value){
			$res[$value['template']] = $value[$field];
		}
		
		echo json_encode(array("load"=>array("page"=>$res)));
		*/
	}
	public function get_selector_builder($type = null){
		
		switch($type){
			case "task";
				$result = $this->selector_builder->isOptionViewTask();
			break;
			default : $result= array();
		}
		
		echo json_encode(array("data"=>$result));
		
	}
	
	public function master_setting(){
		$data = $this->master_setting_model->find(1);
		echo json_encode(array("data"=>$data));
	}
	public function store_master_setting(){
		$post_data = $this->input_data;
		$result = $this->master_setting_model->update(1,$post_data);
		echo json_encode(array("data"=>$result));
	}
	/*
	public function dashboard_query($type='')
	{
		$today = date('Y-m-d');
		$where = ' WHERE a.deleted_at IS NULL ';
		switch ($type) {
			case 'today':
				$where .= " AND DATE(a.created_at) = '$today' ";
				break;
			case 'incomplate':
				$where .= " AND a.id_request_type = 1 ";
				break;
			case 'pending':
				$where .= " AND a.id_request_type = 3 ";
				break;
			case 'ongoing':
				$where .= " AND a.id_request_type != 1 && a.id_request_type != 3 && a.id_request_type != 2 ";
				break;
			default:
				$where .= "";
				break;
		}

		$q = " 
			SELECT a.amount ,CONCAT_WS(' ',b.first_name,b.last_name) as member, 'payment' as type, a.created_at
				FROM payment_confirmation a 
				LEFT JOIN users b ON a.user_id = b.id 	
				$where
					UNION
			SELECT a.amount ,CONCAT_WS(' ',b.first_name,b.last_name) as member, 'top up' as type, a.created_at
				FROM topup_request a 
				LEFT JOIN users b ON a.user_id = b.id 
				$where
					UNION
			SELECT a.amount as amount, CONCAT_WS(' ',b.first_name,b.last_name) as member, 'withdraw' as type, a.created_at 
				FROM withdraw_request a
				LEFT JOIN users b ON a.user_id = b.id
				$where
			ORDER BY created_at DESC
			LIMIT 5
		";
		
		$q = $this->db->query($q)->result_array();
		return $q;
	}
	*/

	public function dashboard_on_going($type='')
	{
		$today = date('Y-m-d');
		$where = ' WHERE deleted_at IS NULL ';
		switch ($type) {
			case 'payment':
				$q = " 
					SELECT COUNT(id) as amount
						FROM payment_confirmation 
						$where
						AND (id_request_type = 3 || id_request_type = 5 || id_request_type = 6 || id_request_type =7)
				";
				break;
			case 'topup_request':
				$q = " 
					SELECT COUNT(id) as amount
						FROM topup_request 
						$where
						AND (id_request_type = 3 || id_request_type = 5 || id_request_type = 6 || id_request_type =7)
				";
				break;
			case 'withdraw':
				$q = " 
					SELECT COUNT(id) as amount
						FROM withdraw_request 
						$where
						AND (id_request_type = 3 || id_request_type = 5 || id_request_type = 6 || id_request_type =7)
				";
				break;
			case 'forum':
				$q = " 
					SELECT COUNT(id) as amount
						FROM forum_topics 
						$where
						AND forum_stat IS NULL
				";
				break;
			default:
				$where .= "";
				break;
		}
		
		$q = $this->db->query($q)->row_array();
		return $q['amount'];
	}

	public function dashboard_today($type='')
	{
		$today = date('Y-m-d');
		$where = ' WHERE deleted_at IS NULL AND created_at = "'.$today.'" ';
		switch ($type) {
			case 'payment':
				$q = " 
					SELECT COUNT(id) as amount
						FROM payment_confirmation 
						$where

				";
				break;
			case 'topup_request':
				$q = " 
					SELECT COUNT(id) as amount
						FROM topup_request 
						$where
				";
				break;
			case 'withdraw':
				$q = " 
					SELECT COUNT(id) as amount
						FROM withdraw_request 
						$where
				";
				break;
			case 'forum':
				$q = " 
					SELECT COUNT(id) as amount
						FROM forum_topics 
						$where
				";
				break;
			default:
				$where .= "";
				break;
		}

		$q = $this->db->query($q)->row_array();
		return $q['amount'];
	}

	public function dashboard_data()
	{
		$result = $this->result;

		$data['payment'] = $this->dashboard_on_going('payment');
		$data['topup_request'] = $this->dashboard_on_going('topup_request');
		$data['withdraw'] = $this->dashboard_on_going('withdraw');
		$data['forum'] = $this->dashboard_on_going('forum');

		$data['payment_today'] = $this->dashboard_today('payment');
		$data['topup_request_today'] = $this->dashboard_today('topup_request');
		$data['withdraw_today'] = $this->dashboard_today('withdraw');
		$data['forum_today'] = $this->dashboard_today('forum');

		$result['data'] = $data;
		$result['error'] = 0;

		echo json_encode($result);

	}
	public function test_active_user()
	{
		$query=$this->db->select('data')->get('ci_sessions')->result_array();
		foreach ($query as $key => $value) {		
			echo $value['data']; die();
		}
	}
}