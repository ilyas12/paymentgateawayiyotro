<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pos_system extends AR_Controller {
	var $adminData;
	var $invoice_detail_model;
	var $inventory_category_model;
	var $inventory_model;
	var $client_model;
	var $invoice_model;
	var $gst_model;
	var $register_model;

	function  __construct(){
		parent:: __construct();
		// $this->is_login('admin');
		$this->load->model('master_model');
		$this->invoice_detail_model 	= new master_model('invoice_detail');
		$this->inventory_category_model = new master_model('inventory_category');
		$this->inventory_model 			= new master_model('inventory');
		$this->client_model 			= new master_model('client');
		$this->invoice_model 			= new master_model('invoice');
		$this->gst_model 				= new master_model('system_settings');
		$this->register_model 			= new master_model('register');
		$this->adminData 				= $this->session->userdata("adminData");		
	}

	public function index()
	{
		$reg_status = self::register_status();
		if($reg_status[0]->register_status == "OPEN"){
			$data['pos_title'] = "POS SYSTEMS";
			$data['balance'] = self::get_opening_balance();
			$data['client'] = $this->client_model->all();
			$data['inventory_cat_list'] = $this->inventory_category_model->all(" AND parent_categories = '0'");
			$data['inventory_categories'] = $this->inventory_category_model->all(" AND parent_categories != '0'");
			$data['inventory_list'] = $this->inventory_model->all();
			$data['tax'] = $this->gst_model->all(' AND sales IS NULL AND cpf_deduction IS NULL AND cpf_extra IS NULL AND start_using <= "'.date('Y-m-d H:i:s').'" ORDER BY id DESC LIMIT 1');
			$data['head'] = 'template/includes/admin-head';
			$this->load->view('admin/pos_system',$data);			
		}
		else{
			$data['head'] = 'template/includes/admin-head';			
			$this->load->view('admin/pos_system_register',$data);
		}

	}

	function pos_register(){
		$crud_data = $this->input_data;
		$last_invoice_register = $this->db->order_by('id','desc')->limit(1)->get('register')->result();
		$id = $last_invoice_register[0]->invoice_id;
		$crud_data['publish_date'] = date('Y-m-d');
		$crud_data['closing_balance'] = $crud_data['opening_balance'];
		$crud_data['register_status'] = "OPEN";
		$crud_data['invoice_id'] = $id + 1;
		$this->register_model->add($crud_data);
		redirect('pos_system');
		// echo json_encode($crud_data);
	}

	// get opening balance
	// if last register_status has CLOSE then activate new opening balance
	function get_opening_balance(){
		$data = $this->db->from('register')
						->order_by('id','desc')
						->limit(1)
						->get()->result();
		$sumdata = count($data);
		if($sumdata == null or $data[0]->register_status == "CLOSE"){
			$balance = array("open"=>0.00,"close"=>0.00,"readonly"=>"");
		}
		else{
			$balance = array("open"=>$data[0]->opening_balance,"close"=>$data[0]->closing_balance,"readonly"=>"readonly");
		}
		// echo json_encode($balance);
		return $balance;
	}

	// retrieve ajax function
	function display_item(){
		$input = $this->input_data;
		// if display type on button click function has cat val then display item categories
		if($input['type'] == "cat"){
			if($input['id'] == 0){
				$data = $this->inventory_category_model->all(" AND parent_categories != '".$input['id']."'");
			}
			else{
				$data = $this->inventory_category_model->all(" AND parent_categories = '".$input['id']."'");				
			}
				$countlist = count($data);
				$numrows = ceil($countlist / 6);
				$html ='';
				for($i = 0 ; $i < $numrows; $i++){
					$html .= '<div class="btn-group btn-group-justified" role="group" aria-label="...">';
					$array = array_slice($data, ($i*6),6);
				  	foreach ($array as $key => $value) {
				  		$html .= '<div class="btn-group" role="group" style="padding:5px;">';
				  			$html .= '<button type="button" class="btn btn-warning" onclick="displayItem(\'item\',\''.$value['id'].'\')">'.$value['name'].'</button>';
				  		$html .= '</div>';
				  	}					
					$html .= '</div>';
				}
			 $ajax = array("type"=>"categories", "list"=>$html);			
		}
		// if display type on button click function has item then display list item from each categories
		else{
			$data = $this->inventory_model->all(' AND category_id = "'.$input['id'].'" ');

				$countlist = count($data);
				$numrows = ceil($countlist / 6);
				$html ='';
				for($i = 0 ; $i < $numrows; $i++){
						$array = array_slice($data, ($i*6),6);
						$html .= '<div class="btn-group btn-group-justified" role="group" aria-label="...">';
					  	foreach ($array as $key => $value) {
					  		$html .= '<div class="btn-group" role="group" style="padding:5px;">';
					  			$html .= '<button type="button" class="btn btn-success item-list" style="white-space:normal; min-height:70px;" 
					  				item-id="'.$value['id'].'" 
					  				item-code="'.$value['code'].'" 
					  				item-name="'.$value['name'].'" 
					  				item-price="'.number_format((float)$value['sell_price'], 2, '.', '').'">'.$value['name'].
					  				'<br/> $ '.number_format((float)$value['sell_price'], 2, '.', '').'</button>';
					  		$html .= '</div>';
					  	}
					  	$html .= '</div>';
				 }
			 $ajax = array("type"=>"navigation", "list"=>$html);			
		}
		echo json_encode($ajax);
	}

	// STORE DATA FROM AJAX HTTPREQUEST
	function store(){
		//retrive post data form
		$data = $this->input_data;
		$query = array();
		// splitting data form to table invoice and invoice detail
		foreach ($data as $key => $value) {
			if(is_array($value)){
				foreach ($value as $k => $v) {
					$query['invoice_detail'][$k][$key] = $v;
				}
			}
			else{
				$query['invoice'][$key] = $value;
			}
		}

		// unsetting opening balance and closing balance from invoice because we dont need this on invoice table
		unset($query['invoice']['opening_balance']);
		unset($query['invoice']['closing_balance']);

		// set publish date for invoice table
		$query['invoice']['publish_date'] = date('Y-m-d');

		// if balance is surplus then balance has 0 value
		if($query['invoice']['balance'] >= 0){
			$query['invoice']['balance'] = 0;
		}


		//STORE DATA TO TABLE INVOICE
		$id = $this->invoice_model->add($query['invoice']);

		// INSERT INVOICE DETAIL
		foreach ($query['invoice_detail'] as $key => $value) {
			$query['invoice_detail'][$key]['invoice_id'] = $id;

			//inserting table invoice detail with invoice detail query
			$this->invoice_detail_model->add($query['invoice_detail'][$key]);
		}

		// set query for table register
		$query['register'] = array(
							"invoice_id"=>$id,
							"opening_balance"=>$data['opening_balance'],
							"closing_balance"=>$data['closing_balance'],
							"publish_date"=>date('Y-m-d'),
							"register_status"=>"OPEN"
							);

		// inserting register query to table register
		$this->register_model->add($query['register']);
		echo json_encode(array("success"=>true,"message"=>"Data has been stored"));
		// echo json_encode($query);
	}

	function register_status(){
		$data = $this->db->where(array("publish_date <="=>date('Y-m-d')))
				->order_by('id','desc')
				->limit(1)
				->get('register')->result();
		return $data;
	}

	function closing_balance(){
		$data = self::register_status();
		$id = $data[0]->id;
		$this->register_model->update($id, array("register_status"=>"CLOSE"));
		redirect('pos_system');		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */