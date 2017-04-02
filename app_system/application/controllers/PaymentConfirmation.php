<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PaymentConfirmation extends AR_Controller {

	protected $_post_data;

	public function __construct(){

		parent::__construct();

		$this->set_session();
		$this->is_login();
		$this->load->model("payment_confirmation_model");
		$this->load->model("users_model");
		$this->load->model("price_model");


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
		$per_page = isset($input_data["per_page"]) && trim($input_data["per_page"])!=""?$input_data["per_page"]:1000;
		$offset = ($page - 1) * $per_page;

		$fields = $this->payment_confirmation_model->search_field;
		$fields = explode(",", $fields);

		$where ="";
		$where = " AND a.deleted_at IS NULL ";
		if(isset($input_data["q"]) && $input_data["q"] !=""){
			$where .=" AND ( ";
			foreach ($fields as $key => $value) {
				$q = trim($input_data["q"]);
				$where .= $key == 0?$value." LIKE '%".$q."%' ":" || ".$value." LIKE '%".$q."%' ";
			}
			$where .=" ) ";
		}
		

		$limit = $per_page > 0 ?" LIMIT $offset,$per_page ":"";
		if($limit == ''){
			$limit = " LIMIT 100 ";
		}
		$data = $this->payment_confirmation_model->listData($where." ORDER BY a.created_at DESC $limit ");
		$total = $this->payment_confirmation_model->listData($where." ORDER BY a.created_at DESC  ");
		$r = array();
		foreach ($data as $key => $value) {
			$value['amount'] = number_format($value['amount']);
			if($value['amount_less'] > 0){
				$value['amount'] = $value['amount'].' ('.number_format($value['amount_less']).')';
				$tf = $this->payment_confirmation_model->find($value['no_payment'],'transaction_ref');
				$value['st'] = isset($tf['id'])?'no: '.$tf['no_payment']:null;
				$value['row_child'] = false;
				$value['detail'] = $this->payment_confirmation_model->listData(" AND a.transaction_ref = '".$value['no_payment']."' ");
			}
			$value['created_at'] = date('H:i d/m/Y', strtotime($value['created_at']));

			$r[] = $value;
		}

		$records["data"] = $r; 
		$records["total"] = count($total);

  
  		echo json_encode($records);
	}

	public function load_today()
	{

		$result = $this->result;

		$input_data = $this->input_data;

		$page = isset($input_data["page"]) && trim($input_data["page"])!=""?$input_data["page"]:1;
		$per_page = isset($input_data["per_page"]) && trim($input_data["per_page"])!=""?$input_data["per_page"]:0;
		$offset = ($page - 1) * $per_page;

		$fields = $this->payment_confirmation_model->search_field;
		$fields = explode(",", $fields);

		$where ="";
		$where = " AND a.deleted_at IS NULL ";
		if(isset($input_data["q"]) && $input_data["q"] !=""){
			$where .=" AND ( ";
			foreach ($fields as $key => $value) {
				$q = trim($input_data["q"]);
				$where .= $key == 0?$value." LIKE '%".$q."%' ":" || ".$value." LIKE '%".$q."%' ";
			}
			$where .=" ) ";
		}
		$current_date = date('Y-m-d');
		$where .=" AND DATE(a.created_at) = '$current_date' ";

		$limit = $per_page > 0 ?" LIMIT $offset,$per_page ":"";
		$data = $this->payment_confirmation_model->listData($where." ORDER BY a.created_at DESC $limit ");
		$total = $this->payment_confirmation_model->listData($where." ORDER BY a.created_at DESC  ");

		$r = array();
		foreach ($data as $key => $value) {
			$value['amount'] = number_format($value['amount']);
			if($value['amount_less'] > 0){
				$value['amount'] = $value['amount'].' ('.number_format($value['amount_less']).')';
				$tf = $this->payment_confirmation_model->find($value['no_payment'],'transaction_ref');
				$value['st'] = isset($tf['id'])?'no: '.$tf['no_payment']:null;
				$value['row_child'] = false;
				$value['detail'] = $this->payment_confirmation_model->listData(" AND a.transaction_ref = '".$value['no_payment']."' ");
			}
			$value['created_at'] = date('H:i d/m/Y', strtotime($value['created_at']));
			$r[] = $value;
		}

		$records["data"] = $r; 
		$records["total"] = count($total);

  
  		echo json_encode($records);
	}

	public function load_on_going()
	{

		$result = $this->result;

		$input_data = $this->input_data;

		$page = isset($input_data["page"]) && trim($input_data["page"])!=""?$input_data["page"]:1;
		$per_page = isset($input_data["per_page"]) && trim($input_data["per_page"])!=""?$input_data["per_page"]:0;
		$offset = ($page - 1) * $per_page;

		$fields = $this->payment_confirmation_model->search_field;
		$fields = explode(",", $fields);

		$where ="";
		$where = " AND a.deleted_at IS NULL ";
		if(isset($input_data["q"]) && $input_data["q"] !=""){
			$where .=" AND ( ";
			foreach ($fields as $key => $value) {
				$q = trim($input_data["q"]);
				$where .= $key == 0?$value." LIKE '%".$q."%' ":" || ".$value." LIKE '%".$q."%' ";
			}
			$where .=" ) ";
		}
		$current_date = date('Y-m-d');
		$where .=" AND transaction_ref IS NULL AND (a.id_request_type != 2 && a.id_request_type != 1 && a.id_request_type != 4 )"; //AND a.created_at < '$current_date' ";

		$limit = $per_page > 0 ?" LIMIT $offset,$per_page ":"";
		$data = $this->payment_confirmation_model->listData($where." ORDER BY a.created_at DESC $limit ");
		$total = $this->payment_confirmation_model->listData($where." ORDER BY a.created_at DESC  ");

		$r = array();
		foreach ($data as $key => $value) {
			$value['amount'] = number_format($value['amount']);
			if($value['amount_less'] > 0){
				$value['amount'] = $value['amount'].' ('.number_format($value['amount_less']).')';
				$tf = $this->payment_confirmation_model->find($value['no_payment'],'transaction_ref');
				$value['st'] = isset($tf['id'])?'no: '.$tf['no_payment']:null;
				$value['row_child'] = false;
				$value['detail'] = $this->payment_confirmation_model->listData(" AND a.transaction_ref = '".$value['no_payment']."' ");
			}
			$value['created_at'] = date('H:i d/m/Y', strtotime($value['created_at']));
			$r[] = $value;
		}

		$records["data"] = $r; 
		$records["total"] = count($total);

  
  		echo json_encode($records);
	}

	public function load_my()
	{

		$result = $this->result;

		$input_data = $this->input_data;

		$page = isset($input_data["page"]) && trim($input_data["page"])!=""?$input_data["page"]:1;
		$per_page = isset($input_data["per_page"]) && trim($input_data["per_page"])!=""?$input_data["per_page"]:0;
		$offset = ($page - 1) * $per_page;

		$fields = $this->payment_confirmation_model->search_field;
		$fields = explode(",", $fields);

		$where ="";
		$where = " AND a.deleted_at IS NULL ";
		if(isset($input_data["q"]) && $input_data["q"] !=""){
			$where .=" AND ( ";
			foreach ($fields as $key => $value) {
				$q = trim($input_data["q"]);
				$where .= $key == 0?$value." LIKE '%".$q."%' ":" || ".$value." LIKE '%".$q."%' ";
			}
			$where .=" ) ";
		}

		$adminData = $this->session->userdata("adminData");
		$adminData = $adminData["id"];
		$where .=" AND (a.approve_by = $adminData)"; //AND a.created_at < '$current_date' ";
		
		$limit = $per_page > 0 ?" LIMIT $offset,$per_page ":"";
		$data = $this->payment_confirmation_model->listData($where." ORDER BY a.created_at DESC $limit ");
		$total = $this->payment_confirmation_model->listData($where." ORDER BY a.created_at DESC  ");

		$r = array();
		foreach ($data as $key => $value) {
			$value['amount'] = number_format($value['amount']);
			if($value['amount_less'] > 0){
				$value['amount'] = $value['amount'].' ('.number_format($value['amount_less']).')';
				$tf = $this->payment_confirmation_model->find($value['no_payment'],'transaction_ref');
				$value['st'] = isset($tf['id'])?'no: '.$tf['no_payment']:null;
			}
			$value['created_at'] = date('H:i d/m/Y', strtotime($value['created_at']));
			$r[] = $value;
		}

		$records["data"] = $r;  
		$records["total"] = count($total);

  
  		echo json_encode($records);
	}



	public function holdData($id)
	{
		$result = $this->result;
		$data = $this->payment_confirmation_model->find($id);	

		if(!isset($data['id']) || $id="" ){
			echo json_encode($result);
			die();
		}	

		$this->payment_confirmation_model->update($data['id'],array("id_request_type"=>5));
		
		$error = 0;
		$msg = " Data has been save";

		$result['error'] = $error;
		$result['msg'] = $msg;
		$result['data'] = null;
		echo json_encode($result);

	}

	public function approveData($id)
	{
		$result = $this->result;
		$data = $this->payment_confirmation_model->find($id);	
		
		if(!isset($data['id']) || $id="" ){
			echo json_encode($result);
			die();
		}	
		$userData =$this->session->userdata("adminData");
		$update_data = array();
		$update_data["id_request_type"] = 7;
		$update_data["approve_by"] = $userData["id"];
		$update_data["approve_at"] = date('Y-m-d H:i:s');

		$this->payment_confirmation_model->update($data['id'],$update_data);
		//check header
		if($data['transaction_ref']!=''){
			$header = $this->payment_confirmation_model->find($data['transaction_ref'],'no_payment');

			$child = $this->db->query(" SELECT SUM(received_amount) as received_amount FROM payment_confirmation WHERE deleted_at IS NULL 
										AND transaction_ref = '".$data['transaction_ref']."' AND id_request_type = 7 ")->row_array(); // 
			$child_amount = isset($child['received_amount'])?$child['received_amount']:0;

			$amount_less = $header['amount_less']-$child_amount;

			if($amount_less <= 0){
				$update_data = array();
				$update_data["id_request_type"] = 7;
				$update_data["approve_by"] = $userData["id"];
				$update_data["approve_at"] = date('Y-m-d H:i:s');
				$this->payment_confirmation_model->update($header['id'],$update_data);
			}

		}

		$error = 0;
		$msg = " Data has been save";

		$result['error'] = $error;
		$result['msg'] = $msg;
		$result['data'] = null;
		echo json_encode($result);

	}

	public function approveToData($id)
	{
		$result = $this->result;
		$data = $this->payment_confirmation_model->find($id);	

		if(!isset($data['id']) || $id="" ){
			echo json_encode($result);
			die();
		}	
		$userData =$this->session->userdata("adminData");
		$update_data = array();
		if($data['id_request_type'] == 3){
			//$update_data["id_request_type"] = 7;
		}
		$update_data["approve_by"] = $this->input_data['approve_user_id'];
		$this->payment_confirmation_model->update($data['id'],$update_data);
		
		$error = 0;
		$msg = " Data has been save";

		$result['error'] = $error;
		$result['msg'] = $msg;
		$result['data'] = null;
		echo json_encode($result);

	}


	public function complateData($id)
	{
		$this->load->model("transaction_history_model");
		$result = $this->result;
		$data = $this->payment_confirmation_model->find($id);	
		$post_data = $this->input_data;


		if(!isset($data['id']) || $id=="" || $post_data['user_id'] == '' || $data['id_request_type'] ==2 ){
			echo json_encode($result);
			die();
		}

		try {
			/* transaction start */
			$this->db->trans_begin();

			$ip = '';
			if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			    $ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
			    $ip = $_SERVER['REMOTE_ADDR'];
			}

				$update_data =array(
										"id_request_type"=>2,
										"completed_by"=>$post_data['user_id'],
										"completed_at"=>date('Y-m-d H:i:s'),
										"completed_ip"=>$ip,
									);

				$this->payment_confirmation_model->update($data['id'],$update_data);

				if($data['transaction_ref']!=''){
					$parent = $this->payment_confirmation_model->find($data['transaction_ref'],'no_payment');
					$update_parent = array('amount_less'=>$parent['amount_less']-$data['amount']);
					$this->payment_confirmation_model->update($parent['id'],$update_parent);
				}

				//type saldo penerima
				if($data["payment_type"] == 1){
					$this->load->model("users_model");
					//insert transaction history
					$member_data = $this->users_model->find($data['user_id']);

					$new_saldo = $member_data['saldo']-$data['amount'];
					$this->users_model->update($member_data['id'],array("saldo"=>$new_saldo));

					$insert_history =array();
					$insert_history["member_id"] =$member_data["id"];
					$insert_history["transaction_date"] =date('Y-m-d');
					$insert_history["transaction_type"] =2;
					$insert_history["description"] ="Payment: ".$data["description"];
					$insert_history["start_balance"] =$member_data["saldo"];
					$insert_history["debit"] =0;
					$insert_history["credit"] =$data["amount"];
					$insert_history["ending_balance"] =$new_saldo;		
					$this->transaction_history_model->add($insert_history);
				}

				//insert transaction history
				$member_data = $this->users_model->find($data['member_id']);
				//$new_amount = $this->price_model->get_new_price($data['amount']);
				$new_amount = $data['received_amount'];
				$new_saldo = $member_data['saldo']+$new_amount;
				$this->users_model->update($member_data['id'],array("saldo"=>$new_saldo));

				$insert_history =array();
				$insert_history["member_id"] =$member_data["id"];
				$insert_history["transaction_date"] =date('Y-m-d');
				$insert_history["transaction_type"] =2;
				$insert_history["description"] ="Payment: ".$data["description"];
				$insert_history["start_balance"] =$member_data["saldo"];
				$insert_history["debit"] =$new_amount;
				$insert_history["credit"] =0;
				$insert_history["ending_balance"] =$new_saldo;		
				$this->transaction_history_model->add($insert_history);


				$error = 0;
				$msg = " Data has been save";
			$this->db->trans_commit();
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$error = 1;
			$msg = sprintf('%s : %s : DB transaction failed. Error no: %s, Error msg:%s, Last query: %s', __CLASS__, __FUNCTION__, $e->getCode(), $e->getMessage(), print_r($this->main_db->last_query(), TRUE));
			
		}

		$result['error'] = $error;
		$result['msg'] = $msg;
		$result['data'] = null;
		echo json_encode($result);

	}

	public function get($id)
	{

		$result = $this->result;
		$data = $this->payment_confirmation_model->getDetailData($id);


		$data['amount'] = number_format($data['amount']);
		if($data['amount_less'] > 0){
			$data['amount'] = $data['amount'].' ('.number_format($data['amount_less']).')';
			$tf = $this->payment_confirmation_model->find($data['no_payment'],'transaction_ref');
			$data['st'] = isset($tf['id'])?'no: '.$tf['no_payment']:null;
		}
		
		$result['error'] = 0;
		$result['msg'] = '';
		$result['data'] = $data;
		echo json_encode($result);

	}


	public function cancelData($id)
	{
		$this->load->model("transaction_history_model");
		$result = $this->result;
		$data = $this->payment_confirmation_model->find($id);	


		if(!isset($data['id']) || $id="" ){
			echo json_encode($result);
			die();
		}

		try {
			/* transaction start */
			$this->db->trans_begin();
			// 1 saldo
			// 2 transfer
			// 3 S & T

			// request type
			if($data['payment_type'] == 1){
				if($data['id_request_type'] != 4 && $data['id_request_type'] != 3 ){

						if($data['transaction_ref']!=null && $data['transaction_ref']!=''){
				
						}else{
						//type saldo penerima
							$this->load->model("users_model");
							//insert transaction history
							$child = $this->payment_confirmation_model->find($data['no_payment'],'transaction_ref');
							$member_data = $this->users_model->find($data['user_id']);

							if( isset($child['id']) && $child['id_request_type'] != 4 && $child['id_request_type']!=3){
								$uang_kembali = $data["amount"];//-$data["amount_less"];	
							}else{
								if($data['amount_less_status'] == 0){
									$uang_kembali = $data["amount"];//-$data["amount_less"];	
								}else{
									$uang_kembali = $data["amount"]-$data["amount_less"];
								}
							}
							$new_saldo = $member_data['saldo']+$uang_kembali;

							$this->users_model->update($member_data['id'],array("saldo"=>$new_saldo));

							$insert_history =array();
							$insert_history["member_id"] =$member_data["id"];
							$insert_history["transaction_date"] =date('Y-m-d');
							$insert_history["transaction_type"] =4;
							$insert_history["description"] ="Payment: ".$data["description"];
							$insert_history["start_balance"] =$member_data["saldo"];
							$insert_history["debit"] =0;
							$insert_history["credit"] =$data["amount"];
							$insert_history["ending_balance"] =$new_saldo;		
							$this->transaction_history_model->add($insert_history);
						}
				}

			}elseif ($data['payment_type']==2) {

					if($data['id_request_type'] != 4 && $data['id_request_type'] != 3 ){
						if($data['transaction_ref']!=null){
							
						}else{
						//type saldo penerima
							$this->load->model("users_model");
							//insert transaction history
							$child = $this->payment_confirmation_model->find($data['no_payment'],'transaction_ref');
							$member_data = $this->users_model->find($data['user_id']);
							if( isset($child['id']) && $child['id_request_type'] != 4 && $child['id_request_type']!=3){
								$uang_kembali = $data["amount"];//-$data["amount_less"];	
							}else{
								$uang_kembali = $data["amount"]-$data["amount_less"];
							}
							$new_saldo = $member_data['saldo']+$uang_kembali;
							$this->users_model->update($member_data['id'],array("saldo"=>$new_saldo));

							$insert_history =array();
							$insert_history["member_id"] =$member_data["id"];
							$insert_history["transaction_date"] =date('Y-m-d');
							$insert_history["transaction_type"] =4;
							$insert_history["description"] ="Payment: ".$data["description"];
							$insert_history["start_balance"] =$member_data["saldo"];
							$insert_history["debit"] =0;
							$insert_history["credit"] =$data["amount"];
							$insert_history["ending_balance"] =$new_saldo;		
							$this->transaction_history_model->add($insert_history);
						}
					}
			}else{

					if($data['id_request_type'] != 4 && $data['id_request_type']!=3 ){
						if($data['transaction_ref']!=null && $data['transaction_ref']!=''){
							
						}else{
						//type saldo penerima
							$child = $this->payment_confirmation_model->find($data['no_payment'],'transaction_ref');

							$this->load->model("users_model");
							//insert transaction history
							$member_data = $this->users_model->find($data['user_id']);

							if( isset($child['id']) && $child['id_request_type'] != 4 && $child['id_request_type']!=3){
								$uang_kembali = $data["amount"];//-$data["amount_less"];	
							}else{
								if($data['amount_less_status'] == 0){
									$uang_kembali = $data["amount"];//-$data["amount_less"];	
								}else{
									$uang_kembali = $data["amount"]-$data["amount_less"];
								}
							}
							
							$new_saldo = $member_data['saldo']+$uang_kembali;
							$this->users_model->update($member_data['id'],array("saldo"=>$new_saldo));

							$insert_history =array();
							$insert_history["member_id"] =$member_data["id"];
							$insert_history["transaction_date"] =date('Y-m-d');
							$insert_history["transaction_type"] =4;
							$insert_history["description"] ="Payment: ".$data["description"];
							$insert_history["start_balance"] =$member_data["saldo"];
							$insert_history["debit"] =0;
							$insert_history["credit"] =$data["amount"];
							$insert_history["ending_balance"] =$new_saldo;		
							$this->transaction_history_model->add($insert_history);
							

						}
					}

			}
			
			$this->payment_confirmation_model->update($data['id'],array("id_request_type"=>4));
			// update child
			$this->db->query(" UPDATE payment_confirmation SET id_request_type = 4 WHERE transaction_ref =  '".$data['no_payment']."' ");
			$error = 0;
			$msg = " Data has been save";

			$this->db->trans_commit();
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$error = 1;
			$msg = sprintf('%s : %s : DB transaction failed. Error no: %s, Error msg:%s, Last query: %s', __CLASS__, __FUNCTION__, $e->getCode(), $e->getMessage(), print_r($this->main_db->last_query(), TRUE));
			
		}

		$result['error'] = $error;
		$result['msg'] = $msg;
		$result['data'] = null;
		echo json_encode($result);

	}

	public function edit_fee($id)
	{
		$this->load->helper('download');
		$post_data = $this->input_data;

		$data = $this->payment_confirmation_model->find($id);

		$update_data = array('received_amount'=>$post_data['received_amount'],'fee'=>$post_data['fee']);
		$this->payment_confirmation_model->update($id,$update_data);

		$result['error'] = 0;
		$result['msg'] = 'Data has been save';
		$result['data'] = null;
		echo json_encode($result);
	}

	public function checkIncomplate()
	{
		$current_date = date('Y-m-d', strtotime(date('Y-m-d'). "+3 days"));
		$data = $this->payment_confirmation_model->all(" AND id_request_type =3 AND DATE(created_at) <= '$current_date' ");

		foreach ($data as $key => $value) {
			// send email 


			//

		}
	}


}



