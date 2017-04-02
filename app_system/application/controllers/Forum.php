<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forum extends AR_Controller {

	protected $_post_data;

	public function __construct(){

		parent::__construct();

		$this->set_session();
		$this->is_login();
		$this->load->model("forum_topics_model");
		$this->load->model("payment_confirmation_model");
		$this->load->model("forum_reply_model");


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

		$fields = $this->forum_topics_model->search_field;
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

		$where .= isset($input_data['topic']) && $input_data['topic']!="" ?" AND topic LIKE '%".trim($input_data["topic"])."%'":"";
		$where .= isset($input_data['code']) && $input_data['code']!="" ?" AND a.code LIKE '%".trim($input_data["code"])."%'":"";
		$where .= isset($input_data['member_id']) && $input_data["member_id"]!="" ?" AND user_id_post = '".trim($input_data["member_id"])."'":"";

		$limit = $per_page > 0 ?" LIMIT $offset,$per_page ":"";
		$data = $this->forum_topics_model->listData($where." ORDER BY a.created_at DESC $limit ");
		$total = $this->forum_topics_model->listData($where." ORDER BY a.created_at DESC  ");


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

		$fields = $this->forum_topics_model->search_field;
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
		$data = $this->forum_topics_model->listData($where." ORDER BY a.created_at DESC $limit ");
		$total = $this->forum_topics_model->listData($where." ORDER BY a.created_at DESC  ");


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

		$fields = $this->forum_topics_model->search_field;
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
		$where .=" AND (a.forum_stat IS NULL || a.forum_stat = 0 || a.forum_stat = 8 )"; //AND a.created_at < '$current_date' ";

		$limit = $per_page > 0 ?" LIMIT $offset,$per_page ":"";
		$data = $this->forum_topics_model->listData($where." ORDER BY a.created_at DESC $limit ");
		$total = $this->forum_topics_model->listData($where." ORDER BY a.created_at DESC  ");


		$records["data"] = $data; 
		$records["total"] = count($total);

  
  		echo json_encode($records);
	}


	public function getMemberSelector()
	{

		$post_data = $this->input_data;

		$where = "  ";
		if(isset($post_data['q']) && $post_data['q']!="" ){
			$q = trim($post_data['q']);
			$where .= " WHERE ( first_name LIKE '%$q%' || last_name LIKE '%$q%' || username LIKE '%$q%'  ) ";
		}

		$data = $this->db->query(" SELECT id, CONCAT(CONCAT_WS(' ',first_name,last_name),' - ', username) as name FROM users  $where  ORDER BY first_name ASC LIMIT 40  ")->result_array();

		echo json_encode($data);
	}

	public function close($id)
	{
		$result = $this->result;
		$data = $this->withdraw_model->find($id);	

		if(!isset($data['id']) || $id="" ){
			echo json_encode($result);
			die();
		}	

		$this->forum_topics_model->update($data['id'],array("forum_stat"=>2)); // 2 close
		
		$error = 0;
		$msg = " Data has been save";

		$result['error'] = $error;
		$result['msg'] = $msg;
		$result['data'] = null;
		echo json_encode($result);

	}

	public function get($id)
	{
		$result = $this->result;

		$data = $this->forum_topics_model->listData(" AND a.id = $id ");
		$data = $data[0];

		$data_detail = $this->forum_reply_model->listData(" AND topic_id = $id ");
		$data["detail"] = $data_detail;

		$result['data'] = $data;
		$result['error'] = 0;

		echo json_encode($result);
	}

	public function update($id)
	{
		$post_data = $this->input_data;

		$data = $this->forum_topics_model->find($id);
		if($post_data['type'] == 1){
			//conculsion
			$this->forum_topics_model->update($id,$post_data);
		}else{
			//reply
			$input_data = array();
			$input_data['topic_id'] = $id;
			$input_data['admin_id_reply'] = $post_data['user_id'];
			$input_data['reply_content'] = $post_data['admin_conclusion'];

			$this->forum_reply_model->add($input_data);

			$update_data = array();
			$update_data['reply'] = $data['reply']+1;

			$this->forum_topics_model->update($id,$update_data);
		}

		try {
			$payment_confirmation = $this->payment_confirmation_model->listData(" AND a.id =  ".$data['id_payment_confirm']);
			$email_1 = $payment_confirmation[0]['member_recieve_email']; 
			$email_2 = $payment_confirmation[0]['member_send_email'] ;

			$template_1 = $this->template_email($data['id'],$payment_confirmation[0]['member_recieve']);
			$template_2 = $this->template_email($data['id'],$payment_confirmation[0]['member_send']);

			send_mail($email_1,"Payment Getway - ".$data['topic'],$template_1);
			send_mail($email_2,"Payment Getway - ".$data['topic'],$template_2);

			$result['error'] = 0;
			$result['msg'] = 'Data has been saved';
			$result['data'] = null;
		} catch (Exception $e) {
			
			$result['error'] = 0;
			$result['msg'] = 'email not send';
			$result['data'] = null;
		}

		echo json_encode($result);
	}
	public function close_data($id)
	{
		$post_data = $this->input_data;
		$input_data['forum_stat'] = 9;
		$this->forum_topics_model->update($id,$input_data);

		$data = $this->forum_topics_model->find($id);
		try {
			$payment_confirmation = $this->payment_confirmation_model->listData(" AND a.id =  ".$data['id_payment_confirm']);
			$email_1 = $payment_confirmation[0]['member_recieve_email']; 
			$email_2 = $payment_confirmation[0]['member_send_email'] ;

			$template_1 = $this->template_email($data['id'],$payment_confirmation[0]['member_recieve']);
			$template_2 = $this->template_email($data['id'],$payment_confirmation[0]['member_send']);

			send_mail($email_1,"Payment Getway - ".$data['topic'],$template_1);
			send_mail($email_2,"Payment Getway - ".$data['topic'],$template_2);

			$result['error'] = 0;
			$result['msg'] = 'Data has been saved';
			$result['data'] = null;
		} catch (Exception $e) {
			
			$result['error'] = 0;
			$result['msg'] = 'email not send';
			$result['data'] = null;
		}
		echo json_encode($result);
	}
	public function template_email($id='',$to_name='')
	{
		$data = $this->forum_topics_model->listData(" AND a.id = $id ");
		$data = $data[0];

		$dd = $this->forum_reply_model->listData(" AND topic_id = $id  ORDER BY a.created_at DESC LIMIT 4 ");
		$dd_id = array();
		foreach ($dd as $key => $value) {
			$dd_id[] = $value['id'];
		}
		$dd_id = implode(",",$dd_id);
		$data_detail = $this->forum_reply_model->listData(" AND a.id IN ($dd_id)  ORDER BY a.created_at ASC  ");

		$data["detail"] = $data_detail;

		$data['to_name'] = $to_name;
		$result['data'] = $data;

        $template = $this->load->view('email/forum.php',$result,TRUE);

        //echo $template;
        return $template;
	}



}



