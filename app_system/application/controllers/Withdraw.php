<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Withdraw extends AR_Controller {

	protected $_post_data;

	public function __construct(){

		parent::__construct();

		$this->set_session();
		$this->is_login();
		$this->load->model("withdraw_model");
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

		$fields = $this->withdraw_model->search_field;
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

		$where .= isset($input_data['withdraw_code']) && $input_data['withdraw_code']!="" ?" AND a.code = '".trim($input_data["withdraw_code"])."'":"";

		$limit = $per_page > 0 ?" LIMIT $offset,$per_page ":"";
		if($limit == ''){
			$limit = " LIMIT 100 ";
		}
		$data = $this->withdraw_model->listData($where." ORDER BY a.created_at DESC $limit ");
		$total = $this->withdraw_model->listData($where." ORDER BY a.created_at DESC  ");

		$records["data"] = $data; 
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

		$fields = $this->withdraw_model->search_field;
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
		$data = $this->withdraw_model->listData($where." ORDER BY a.created_at DESC $limit ");
		$total = $this->withdraw_model->listData($where." ORDER BY a.created_at DESC  ");

		$records["data"] = $data; 
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

		$fields = $this->withdraw_model->search_field;
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
		$where .=" AND (a.id_request_type = 5 || a.id_request_type = 3 || a.id_request_type = 7 )"; //AND a.created_at < '$current_date' ";

		$limit = $per_page > 0 ?" LIMIT $offset,$per_page ":"";
		$data = $this->withdraw_model->listData($where." ORDER BY a.created_at DESC $limit ");
		$total = $this->withdraw_model->listData($where." ORDER BY a.created_at DESC  ");

		$records["data"] = $data; 
		$records["total"] = count($total);

  
  		echo json_encode($records);
	}

	public function holdData($id)
	{
		$result = $this->result;
		$data = $this->withdraw_model->find($id);	

		if(!isset($data['id']) || $id="" ){
			echo json_encode($result);
			die();
		}	

		$this->withdraw_model->update($data['id'],array("id_request_type"=>5));
		
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
		$data = $this->withdraw_model->find($id);	

		if(!isset($data['id']) || $id="" ){
			echo json_encode($result);
			die();
		}	
		$userData =$this->session->userdata("adminData");
		$update_data = array();
		$update_data["id_request_type"] = 7;
		$update_data["approve_by"] = $userData["id"];
		$update_data["approve_at"] = date('Y-m-d H:i:s');

		$this->withdraw_model->update($data['id'],$update_data);
		
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
		$data = $this->withdraw_model->find($id);	
		$post_data = $this->input_data;

		if(!isset($data['id']) || $id=="" || $post_data['user_id'] == ''  || $data['id_request_type'] ==2){
			echo json_encode($result);
			die();
		}

		$member_data = $this->users_model->find($data['user_id']);
		$new_saldo = $member_data['saldo']-$data['Amount'];

		if($new_saldo < 0){
			$result['msg'] = 'saldo tidak cupkup';
			$result['error'] = 1;
			echo json_encode($result);
			die();
		}

		$this->users_model->update($member_data['id'],array("saldo"=>$new_saldo));

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
		$this->withdraw_model->update($data['id'],$update_data);

		//insert transaction history
		$insert_history =array();
		$insert_history["member_id"] =$member_data["id"];
		$insert_history["transaction_date"] =date('Y-m-d');
		$insert_history["transaction_type"] =3;
		$insert_history["description"] ="WITHDRAW";
		$insert_history["start_balance"] =$member_data["saldo"];
		$insert_history["debit"] =0;
		$insert_history["credit"] =$data["Amount"];
		$insert_history["ending_balance"] =$new_saldo;		

		$this->transaction_history_model->add($insert_history);

		$email_data = array('user'=>$member_data['username'],
							'amount'=>$data['Amount'],
							'saldo'=>$new_saldo,
							'email_to'=>$member_data['email'],	
							);
		$msg = $this->template_email($email_data);

		try {
			
			send_mail($email_data['email_to'],"Payment Getway - Withdraw",$msg);
			$error = 0;
			$msg = " Data has been save";
			$result['error'] = $error;
			$result['msg'] = $msg;
			$result['data'] = null;

		} catch (Exception $e) {
			$error = 1;
			$msg = " email not send";
			$result['msg'] = $msg;
			$result['data'] = null;
			
		}
		echo json_encode($result);

	}

	public function template_email($data)
	{
		$tamplate = '
		<html>
			<body>
			  <div style="font-size: 9pt; width: 100%; color: black; font-weight: bold;font-family: goudy old style
			">
			 	<table border="0" style="text-align: left; width: 70%; border-top: 1px solid darkgray; border-bottom: 1px solid darkgray ">
			 		<tr>
			                    <td colspan="3"><h2 style="text-align: center">WITHDRAW REQUEST</h2></td>
			 		</tr>
			 		<tr>
			                    <td colspan="3"><h3 style="text-align: center;text-transform: uppercase">HELLO  '.$data['user'].'</h3></td>
			 		</tr>
			                <tr>
			                    <td colspan="3" style="text-align: center;">Your Withdraw Transaction Has Been Complate</td>
			 		</tr>
			 		<tr>
			 			<td colspan="3" style="text-align: center;text-decoration: underline">DETAILS </td>
			 		</tr>
			 		 <tr style="text-align: center;">
			 			<td>Withdraw Amount</td>
			 			<td>:</td>
			 			<td><span style="color: darkblue">Rp '.number_format($data['amount']).'</span></td>
			 		</tr>
			 		</tr>
			 		 <tr style="text-align: center;">
			 			<td>Saldo</td>
			 			<td>:</td>
			 			<td><span style="color: darkblue">Rp '.number_format($data['saldo']).'</span></td>
			 		</tr>
			 		<tr>
			 		 	<td colspan="3"></td>
			 		</tr>
			 		<tr style="text-align: center;">
			 			<td colspan="3"> <p>Thanks.</p></td>
			 		</tr>
			 	</table>  
			  </div>
			    
			</body>
			</html>
		';

		return $tamplate;

	}

}

