<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class InventoryController extends AR_Controller {
	var $user_id;
    var $inventory_model;
    var $inventory_category_model;
    var $supplier_model;
    var $inventory_log_model;

	public function __construct(){
		parent::__construct();
		$this->load->model("master_model");
        $this->inventory_model          = new master_model('inventory');
        $this->inventory_category_model = new master_model('inventory_category');
        $this->supplier_model           = new master_model('supplier');
        $this->inventory_log_model      = new master_model('inventory_log');
        $this->supplier_model           = new master_model('supplier');

		$this->load->library('form_validation');
        $user = $this->session->userdata('adminData');
        $this->user_id = $user['id']; 

	}

	public function index(){
        $data['title'] = "Inventory List";
        $data['add_new_link'] = site_url('inventory_list/add_New_Inventory');
        $data['inv'] = self::datatables();
        $this->template->loadView('admin/inventoryList', $data, "admin");

	}

	private function datatables(){
        $data = $this->db->select('i.*, s.id as sid, s.name as sname')
                ->from('inventory i')
                ->join('supplier s','s.id = i.supplier_id','left')
                ->where('i.deleted_at',NULL)
                ->order_by('i.name','ASC')
                ->get()->result();
        return $data;
	}

	public function add_New_Inventory(){
        $data['title'] = "Add New Inventory";
        $data['action_url'] = site_url('inventory_list/storeInventory');
        $data['supplier']= $this->supplier_model->all();
        $data['datas'] = array();
        $data['categories'] = $this->inventory_category_model->all(" AND parent_categories != '0'");
        $this->template->loadView('admin/inventoryForm', $data, "admin");

	}

	public function editinventory($id){
        $data['title'] = "Edit Inventory";
        $data['supplier'] = $this->supplier_model->all();
        $data['categories'] = $this->inventory_category_model->all(" AND parent_categories != '0'");        
        $data['action_url'] = site_url('inventory_list/updateInventory/'.$id);
        $data['datas'] = $this->inventory_model->find($id);
        $this->template->loadView('admin/inventoryForm', $data, "admin");
	}

	function validation(){
        $this->form_validation->set_rules('code', 'Inventory Codes', 'required|max_length[45]');
        $this->form_validation->set_rules('name', 'Inventory Name', 'required|max_length[255]');
        $this->form_validation->set_rules('supplier_id', 'Supplier', 'required');
        $this->form_validation->set_rules('qty', 'Quantity', 'required|max_length[11]');
        $this->form_validation->set_rules('cost_price', 'Cost Price', 'required');
        $this->form_validation->set_rules('sell_price', 'Sell Price', 'required');

        return $this->form_validation->run();
	}

	public function storeInventory(){
        if($this->validation() == true) {
            $crud_data = $this->input_data;
            unset($crud_data['btnSubmit']);
            $inv = $this->inventory_model->add($crud_data);

            $inventory_log = array(
                        "inventory_id"=>$inv,
                        "inventory_name"=>$crud_data['name'],
                        "starting_qty"=>$crud_data['qty'],
                        "ending_qty"=> $crud_data['qty'],
                        "IN"=> 0,
                        "OUT"=> 0,
                        "user_id"=>$this->user_id,
                        "remark"=> "Update inventory data information"
                        );
            // INSERT INTO INVENTORY LOG FROM INVENTORY TABLE
            $this->inventory_log_model->add($inventory_log);

            redirect('inventory_list');
        }else{
        	$error['error'] = validation_errors();
        	$this->session->set_flashdata($error);
        	redirect('inventory_add');
        }		
	}

	public function updateInventory($id){
        if($this->validation() == true) {
            $crud_data = $this->input_data;
            
            unset($crud_data['btnSubmit']);
            
            $inventory_log = array(
                        "inventory_id"=>$id,
                        "inventory_name"=>$crud_data['name'],
                        "starting_qty"=>$crud_data['qty'],
                        "ending_qty"=> $crud_data['qty'],
                        "IN"=> 0,
                        "OUT"=> 0,
                        "user_id"=>$this->user_id,
                        "remark"=> "New inventory data, Added on ".date('Y-m-d')." from inventory"
                        );
            
            $this->inventory_model->update($id,$crud_data);

            // INSERT INTO INVENTORY LOG FROM INVENTORY TABLE
            $this->inventory_log_model->add($inventory_log);            
            
            redirect('inventory_list');
        }else{
        	$error['error'] = validation_errors();
        	$this->session->set_flashdata($error);
        	redirect('inventory_edit/'.$id);
        }				
	}

	public function softDelete($id){
		$inventoryData = $this->inventory_model->find($id);
		$this->inventory_model->delete($id);
		redirect('inventory_list');

	}
	public function restore($id){
		$inventoryData = $this->inventory_model->find($id);
		$this->inventory_model->restore($id);
		redirect('inventory_list');

	}			
}