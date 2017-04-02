<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PurchaseOrderStatus extends AR_Controller {
	
	var $purchase_order_status_model;

	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model("master_model");
		$this->purchase_order_status_model = new master_model('purchase_order_status');
	}
	public function index()
	{
	
		$data['title'] = "List of Purchase Order Status"; //title to show up in last part of breadcrumb and title of a page
		$data['datatables'] = self::datatables();
		$this->template->loadView('admin/purchaseOrderStatusList',$data,"admin");//show list of po status
	}

	public function new_data(){
        	$data['title'] = "New Purchase Order Status";
			$data['action_url']= site_url('po_status_list/store');
            $this->template->loadView('admin/purchaseOrderStatusForm',$data,"admin");
	}

	function edit($id){
		$data['title'] = "New Purchase Order Status";
		$data['action_url']= site_url('po_status_list/update/'.$id);
		$data['datas'] = $this->purchase_order_status_model->find($id);
		$this->template->loadView('admin/purchaseOrderStatusForm',$data,"admin");
	}

	function store(){
        if($this->validation() == true) {
            $crud_data = $this->input_data;
            unset($crud_data['btnSubmit']);
            $this->purchase_order_status_model->add($crud_data);
            redirect('po_status_list');
        }else{
        	$error['error'] = validation_errors();
        	$this->session->set_flashdata($error);
        	redirect('po_status_new');
        }		
	}
	function update($id){
        if($this->validation() == true) {
            $crud_data = $this->input_data;
            unset($crud_data['btnSubmit']);
            $this->purchase_order_status_model->update($id,$crud_data);
            redirect('po_status_list');
        }else{
        	$error['error'] = validation_errors();
        	$this->session->set_flashdata($error);
        	redirect('po_status_edit/'.$id);
        }		
	}
	function validation(){
        $this->form_validation->set_rules('status', 'Purchase Order Status', 'required|max_length[45]');
        return $this->form_validation->run();
	}

	function softDelete($id){
		$inventoryData = $this->purchase_order_status_model->find($id);
		$this->purchase_order_status_model->delete($id);
        $message['message'] = "Data Has Been Deleted";
        $this->session->set_flashdata($message);
		redirect('po_status_list');
	}

	function datatables(){
		$data = $this->purchase_order_status_model->all();
		foreach ($data as $key => $value) {
			$data[$key]['action'] = '<a href="'.site_url('po_status_list/edit/'.$value['id']).'" class="btn btn-warning">Edit</a>';
			$data[$key]['action'] .= '<a href="'.site_url('po_status_list/delete/'.$value['id']).'" class="btn btn-danger">Delete</a>';
		}
		return $data;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */