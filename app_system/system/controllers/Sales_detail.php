<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales_detail extends AR_Controller {
    var $user_id;
    var $sales_model;
    var $sales_detail_model;
    var $prefix_model;
    var $inventory_model;
    var $inventory_log_model;
    var $sales_detail_outlet_model;

    public function __construct(){
        parent::__construct();
        $this->load->model("master_model");
        $this->sales_model  = new master_model('sales');
        $this->sales_detail_model  = new master_model('sales_detail');
        $this->prefix_model = new master_model('prefix');
        $this->inventory_model = new master_model('inventory');
        $this->inventory_log_model = new master_model('inventory_log');

        $this->sales_detail_outlet_model = new master_model('sales_detail_outlet');

        $this->load->library('form_validation');
        // $this->is_login('admin');
        $user = $this->session->userdata('adminData');
        $this->user_id = $user['id']; 

    }

    public function index($sales_detail_outlet_id){
        $data_outlet_sales = $this->sales_detail_outlet_model->find($sales_detail_outlet_id);
        $data_sales        = $this->sales_model->find($data_outlet_sales['sales_id']);


        $data['title'] = "Sales Detail List";
        $data['add_new_link'] = site_url('sales_detail/add/'.$data_sales['id'].'/'.$sales_detail_outlet_id);
        $data['salesdata'] = self::datatables($sales_detail_outlet_id);
        $this->template->loadView('admin/sales_detail', $data, "admin");
    }

    private function datatables($sales_detail_outlet_id){
        $data = $this->db->select('a.*, c.name as inv_name')
                ->from('sales_detail a')
                ->join('sales_detail_outlet b','b.id = a.sales_detail_outlet_id','left')
                ->join('inventory c','c.id = a.inventory_id','left')
                ->where('a.deleted_at',NULL)
                ->where('a.sales_detail_outlet_id',$sales_detail_outlet_id)
                ->get()->result();
        return $data;
    }

    public function add($sales_id,$outlet_id){
        $data['title'] = "Add New Sales Detail Order";
        $data['sales_id'] = $sales_id;
        $data['sales_detail_outlet_id'] = $outlet_id;
        $data['inventory_data'] = $this->inventory_model->all(' AND deleted_at IS NULL AND qty > 10 ');

        $data['action_url'] = site_url('sales_detail/store');
        $data['datas'] = array();
        $this->template->loadView('admin/sales_detail_form', $data, "admin");

    }

    public function edit($id){
        $data['title'] = "Edit Sales";
        $data['sales'] = $this->sales_model->all();
        $data['action_url'] = site_url('sales_detail_list/update/'.$id);
        $data['datas'] = $this->sales_model->find($id);
        $data['inventory_data'] = $this->inventory_model->all(' AND deleted_at IS NULL AND qty > 10 ');
        $this->template->loadView('admin/sales_detail_form', $data, "admin");
    }

    public function store(){
            $crud_data = $this->input_data;
            foreach ($crud_data['inventory_id'] as $key => $value) {
                $inv = $this->inventory_model->find($value);
                $input_data = array(
                    "sales_id"=>$crud_data['sales_id'],
                    "sales_detail_outlet_id"=>$crud_data['sales_detail_outlet_id'],
                    "inventory_id"=>$value,
                    "cost_price"=>$inv['cost_price'],
                    "sell_price"=>$crud_data['price'][$key],
                    "qty"=>$crud_data['qty'][$key],
                    "total_price"=>$crud_data['total'][$key]
                );
                
                //INSERT SALES DETAIL OUTLET
                $this->sales_detail_model->add($input_data);


                // UPDATE INVENTORY TABLE
                $last_qty = abs($inv['qty'] - $crud_data['qty'][$key]);
                $this->inventory_model->update($value,array("qty"=>$last_qty));

                // SET DATA TO BEGIN INSERTING INVENTORY LOG TABLE
                $inventory_log = array(
                            "inventory_id"=>$value,
                            "inventory_name"=>$inv['name'],
                            "starting_qty"=>$inv['qty'],
                            "ending_qty"=> $last_qty,
                            "IN"=> 0,
                            "OUT"=> $crud_data['qty'][$key],
                            "user_id"=> $this->user_id,
                            "remark"=> "Client Order"
                            );

                // INSERT INVENTORY LOG TABLE
                $this->inventory_log_model->add($inventory_log);
            
            }

            redirect('sales_detail/'.$crud_data['sales_detail_outlet_id']);
     
    }

    public function update($id){
        if($this->validation() == true) {
            $crud_data = $this->input_data;
                        
            $this->sales_model->update($id,$crud_data);
            
            redirect('sales_detail_list');
        }else{
            $error['error'] = validation_errors();
            $this->session->set_flashdata($error);
            redirect('sales_update/'.$id);
        }               
    }

    public function softDelete($id){
        $this->sales_model->delete($id);
        redirect('sales_detail_list');

    }
    public function restore($id){
        $inventoryData = $this->inventory_model->find($id);
        $this->inventory_model->restore($id);
        redirect('sales_detail_list');

    }           
}