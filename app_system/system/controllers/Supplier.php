<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Supplier extends AR_Controller {
	var $supplier_model;
	public function __construct(){
		parent::__construct();
		$this->load->model("master_model");
		$this->supplier_model = new master_model('supplier');
		// $this->is_login('admin');
	}
	public function index()
	{
		$data['title'] = "Supplier List";
		$data['add_new_link'] = "supplier_list/add_new";
		$data['supplier'] = $this->supplier_model->all();
		$this->template->loadView('admin/supplierList',$data,"admin");
	}
	public function add_new(){
		$data['title'] = "Add New Supplier";
		//$data['getID'] = $this->supplier_model->getID('supplier','SP',2);
		//BEGIN ADD ACTION
		if(isset($this->input_data['btnSubmit'])){
			//BEGIN GETTING INPUT
				$supplier_detail = $this->input_data;
				
				$flag = 1;
				$reg_no = $supplier_detail['reg_no'];
				$name = $supplier_detail['name'];
				$supplier_detail['name'] = strtoupper($name);
				
				//validate all inputs
				// BEGIN EMPTY VALIDATION
				$required_fields = array("name","cp_name","cp_mobile");
				if($flag == 1 )
				{
					foreach($required_fields as $fields){
							if(!isset($supplier_detail[$fields]) || $supplier_detail[$fields] == ""){
								$flag = 0;
								$msg = "fields required";
								break;
							}
					}
				}
					
				//END EMPTY VALIDATION
				//BEGIN THROW EMPTY INPUT
					unset($supplier_detail['btnSubmit']);
				//END THROW EMPTY INPUT
			//END GETTING INPUT
			if($flag == 1)
			{
				$this->supplier_model->add($supplier_detail); //create new supplier

				$passData['msg'] = "success creating supplier ".$name;
				$passData['msg_status'] = " success";
				$this->session->set_userdata("temporary_data",$passData);
				redirect('supplier_list');
			}
			else{
				//show the user form with errors
				$data['supplier_data'] = $supplier_detail;
				$data['msg'] = $msg;
			}	
		}
		//END ADD ACTION
		$this->template->loadView('admin/supplierForm',$data,"admin");//show user form	
	}
	public function edit($id){
		$data['title'] = "Edit Supplier";
		$data['supplier_data'] = $this->supplier_model->find($id);
		//BEGIN EDIT ACTION
		if(isset($this->input_data['btnSubmit'])){
			//BEGIN GETTING INPUT
				$supplier_detail = $this->input_data;
				
				$flag = 1;
				$reg_no = $supplier_detail['reg_no'];
				$name = $supplier_detail['name'];
				$supplier_detail['name'] = strtoupper($name);

				//validate all inputs
				// BEGIN EMPTY VALIDATION
				$required_fields = array("name","cp_name","cp_mobile");
				if($flag == 1 )
				{
					foreach($required_fields as $fields){
						if(!isset($supplier_detail[$fields]) || $supplier_detail[$fields] == ""){
								$flag = 0;
								$msg = "fields required";
								break;
							}
						}
				}
				//END EMPTY VALIDATION
				//BEGIN THROW EMPTY INPUT
					unset($supplier_detail['btnSubmit']);
			//END THROW EMPTY INPUT]
			//END GETTING INPUT
			//if all validation is ok
			if($flag == 1){
				$this->supplier_model->update($id,$supplier_detail);
				$passData['msg'] = "success editing supplier ".$name;
				$passData['msg_status'] = " success";
				$this->session->set_userdata("temporary_data",$passData);
				redirect('supplier_list');
				
			}
			else{
				//show the user form with errors
				$data['supplier_data'] = $supplier_detail;
				$data['msg'] = $msg;
			}
		}
		//END ADD ACTION
		$this->template->loadView('admin/supplierForm',$data,"admin");

	}
	public function softDelete($id){
		$supplierData = $this->supplier_model->find($id);
		$this->supplier_model->delete($id);
		$passData['msg'] = "success deleting supplier ".$supplierData['name'];
		$passData['msg_status'] = " success";
		$this->session->set_userdata("temporary_data",$passData);
		redirect('supplier_list');

	}
	public function restore($id){
		$supplierData = $this->supplier_model->find($id);
		$this->supplier_model->restore($id);
		$passData['msg'] = "success restoring supplier ".$supplierData['name'];
		$passData['msg_status'] = " success";
		$this->session->set_userdata("temporary_data",$passData);
		redirect('supplier_list');

	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */