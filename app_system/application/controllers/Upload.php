<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Upload extends AR_Controller {
	protected $_post_data;
	public function __construct(){
		parent::__construct();
		$this->load->model("upload_model");
		$this->set_session();
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
		$fields = $this->upload_model->allow_insert;
		$fields = explode(",", $fields);
		$where ="";
		$where = " AND deleted_at IS NULL ";
		if(isset($input_data["q"]) && $input_data["q"] !=""){
			$where .=" AND ( ";
			foreach ($fields as $key => $value) {
				$q = trim($input_data["q"]);
				$where .= $key == 0?$value." LIKE '%".$q."%' ":" || ".$value." LIKE '%".$q."%' ";
			}
			$where .=" ) ";
		}
		$limit = $per_page > 0 ?" LIMIT $offset,$per_page ":"";
		$data = $this->upload_model->all($where." ORDER BY title ASC $limit ");
		$total = $this->upload_model->all($where." ORDER BY title ASC ");
		$records["data"] = $data; 
		$records["total"] = count($total);
  
  		echo json_encode($records);
	}
	public function get($id)
	{
		$data = $this->upload_model->find($id);
		
		$result['error'] = 0;
		$result['msg'] = 'Data has been saved';
		$result['data'] = $data;
		echo json_encode($result);
	}
	public function update($id)
	{
		$post_data = $this->input_data;
		$this->upload_model->update($id,$post_data);
		$result['error'] = 0;
		$result['msg'] = 'Data has been saved';
		$result['data'] = null;
		echo json_encode($result);
	}
	public function insert()
	{
		$result = $this->result;
		$post_data = $this->input_data;
		if($post_data['thumnail'] == ''){
			unset($post_data['thumnail']);
		}
		$id = $this->upload_model->add($post_data);
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
		$this->upload_model->delete($id);
	}

	public function upload_thumbnail($value='')
	{
        $post_data = $this->input_data;

        if(!file_exists("./public/thumbnail/")){
                mkdir("./public/thumbnail/");
        }
        if(!file_exists("./public/thumbnail/_tmp")){
                mkdir("./public/thumbnail/_tmp");
        }

        $config['upload_path'] = './public/thumbnail/_tmp/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '4096';
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            $error = array('error' => $this->upload->display_errors());
            $error = $this->upload->display_errors();
            $error = str_ireplace("<p>", '*', $error);
            $error = str_ireplace("</p>", '', $error);
            $passData['msg'] = $error;
            $passData['error'] = true;

            echo json_encode($passData);
        } else {
            
            $file_data = $this->upload->data();
            $data = $file_data['full_path'];
            $passData['data'] = $data;
            $passData['msg'] = 'successfully import data';
            $passData['error'] = false;
            echo json_encode($passData);
            
        }
	}
}
