<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales_detail_outlet extends AR_Controller {
    var $user_id;
    var $sales_model;
    var $sales_detail_outlet_model;
    var $prefix_model;
    var $client_model;
    var $client_address_model;

    public function __construct(){
        parent::__construct();
        $this->load->model("master_model");
        $this->sales_model                  = new master_model('sales');
        $this->sales_detail_outlet_model    = new master_model('sales_detail_outlet');
        $this->prefix_model                 = new master_model('prefix');
        $this->client_model                 = new master_model('client');
        $this->client_address_model         = new master_model('client_address');

        $this->load->library('form_validation');
        // $this->is_login('admin');
        $user = $this->session->userdata('adminData');
        $this->user_id = $user['id']; 

    }

    public function salesDetailOutletList($id){
        $sales_outlet   = $this->sales_detail_outlet_model->find($id);
        $client_address = $this->client_address_model->find($sales_outlet['client_address_id']);
        $client         = $this->client_model->find($client_address['client_id']);

        $data['title'] = "Sales Outlet List No ".$id;
        $data['add_new_link'] = site_url('sales_detail_outlet_add/'.$id);
        $data['client_name'] = $client['name'];
        $data['user_id'] = $this->user_id;
        $data['salesdata'] = self::datatables($id);
        $this->template->loadView('admin/salesDetailOutletList', $data, "admin");

    }

    private function datatables($id){
        $data = $this->db->select('s.*, c.sales_no, d.address')
                ->from('sales_detail_outlet s')
                ->join('sales c','c.id = s.sales_id','left')
                ->join('client_address d','d.id = s.client_address_id','left')
                ->where('s.sales_id',$id)
                ->where('s.deleted_at',NULL)
                ->order_by('s.sales_id','ASC')
                ->get()->result();
        return $data;
    }

    function client_address_option(){
        $id = $this->input_data;
        $data = $this->client_address_model->all(' AND client_id = "'.$id['id'].'"');
        echo json_encode($data);
    }

    public function addNewSalesDetailOutlet($id){
        $data_sales = $this->sales_model->find($id);
        $data['title'] = "Add New Sales Outlet";
        $data['sales_id'] = $id;
        $data['action_url'] = site_url('sales_outlet_store/'.$id);
        $data['sales_no']= $data_sales['sales_no'];
        $data['client_add']= $this->client_address_model->all(' AND client_id = "'.$data_sales['client_id'].'"');
        $data['datas'] = array();
        $this->template->loadView('admin/salesDetailOutletForm', $data, "admin");
    }

    public function editSalesDetailOutlet($id){
        $data['title'] = "Edit Sales Outlet";
        $data['client']= $this->client_model->all();
        $data['client_add'] = $this->client_address_model->all();        
        $data['action_url'] = site_url('sales_outlet_update/'.$id);
        $data['datas'] = $this->sales_detail_outlet_model->find($id);
        $data['sales_id'] = $data['datas']['sales_id'];
        
        $data_sales = $this->sales_model->find($data['datas']['sales_id']);
        $data['sales_no']= $data_sales['sales_no'];        
        $this->template->loadView('admin/salesDetailOutletForm', $data, "admin");
    }

    function validation(){
        $this->form_validation->set_rules('sales_id', 'Sales Id', 'required');
        $this->form_validation->set_rules('client_address_id', 'Client address', 'required');

        return $this->form_validation->run();
    }

    public function storeSalesOutlet($id){
        if($this->validation() == true) {
            $crud_data = $this->input_data;
            $sales = $this->sales_detail_outlet_model->add($crud_data);

            redirect('sales_detail_outlet_list/'.$id);
        }else{
            $error['error'] = validation_errors();
            $this->session->set_flashdata($error);
            redirect('sales_detail_outlet_add/'.$id);
        }       
    }

    public function updateSalesOutlet($id){
        if($this->validation() == true) {

            $crud_data = $this->input_data;
                        
            $this->sales_detail_outlet_model->update($id,$crud_data);
            
            redirect('sales_detail_outlet_list/'.$crud_data['sales_id']);
        }else{
            $error['error'] = validation_errors();
            $this->session->set_flashdata($error);
            redirect('sales_outlet_update/'.$id);
        }               
    }

    public function softDelete($id){
        $data = $this->sales_detail_outlet_model->find($id);        
        $this->sales_detail_outlet_model->delete($id);
        redirect('sales_detail_outlet_list/'.$data['sales_id']);

    }
    public function restore($id){
        $Data = $this->sales_detail_outlet_model->find($id);
        $this->sales_detail_outlet_model->restore($id);
        redirect('sales_detail_outlet_list/'.$id);

    }           
}