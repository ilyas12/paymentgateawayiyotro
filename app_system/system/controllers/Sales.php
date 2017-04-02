<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales extends AR_Controller {
    var $user_id;
    var $sales_model;
    var $prefix_model;
    var $client_model;

    public function __construct(){
        parent::__construct();
        $this->load->model("master_model");
        $this->sales_model  = new master_model('sales');
        $this->prefix_model = new master_model('prefix');
        $this->client_model = new master_model('client');

        $this->load->library('form_validation');
        // $this->is_login('admin');
        $user = $this->session->userdata('adminData');
        $this->user_id = $user['id']; 

    }

    public function index(){
        $data['title'] = "Sales List";
        $data['add_new_link'] = site_url('sales_list/add');
        $data['salesdata'] = self::datatables();
        $this->template->loadView('admin/salesList', $data, "admin");

    }

    private function datatables(){
        $data = $this->db->select('s.*, c.id as cid, c.name as cname')
                ->from('sales s')
                ->join('client c','c.id = s.client_id','left')
                ->where('s.deleted_at',NULL)
                ->where('s.user_id',$this->user_id)
                ->order_by('s.sales_no','ASC')
                ->get()->result();
        return $data;
    }

    public function add(){
        $data['title'] = "Add New Sales";
        $data['action_url'] = site_url('sales_list/store');
        $data['sales']= $this->sales_model->all();
        $data['sales_prefix'] = self::sales_prefix();
        $data['client']= $this->client_model->all();
        $data['datas'] = array();
        $this->template->loadView('admin/salesForm', $data, "admin");

    }

    function sales_prefix(){
        $prefix = $this->prefix_model->find(1); // PREFIX SALES ID = 1
        $last_sales_activity = $this->sales_model->all(' ORDER BY id DESC LIMIT 1');
        if($last_sales_activity != null){
            $id = (int)$last_sales_activity[0]['id'] + 1;            
        }
        else{
            $id = 1;   
        }
        $newid = $prefix['prefix'].sprintf('%0'.$prefix['length'].'s',$id);
        return $newid;
    }

    public function edit($id){
        $data['title'] = "Edit Sales";
        $data['sales'] = $this->sales_model->all(' AND deleted_at IS NULL');
        $data['client']= $this->client_model->all(' AND deleted_at IS NULL');        
        $data['action_url'] = site_url('sales_list/update/'.$id);
        $data['datas'] = $this->sales_model->find($id);
        $this->template->loadView('admin/salesForm', $data, "admin");
    }

    function validation(){
        $this->form_validation->set_rules('client_id', 'Client', 'required');
        $this->form_validation->set_rules('sales_no', 'Sales Number', 'required|max_length[45]');

        return $this->form_validation->run();
    }

    public function store(){
        if($this->validation() == true) {
            $crud_data = $this->input_data;
            $crud_data['user_id'] = $this->user_id;
            $crud_data['sales_date'] = date('Y-m-d');
            $crud_data['status'] = "PENDING";
            $sales = $this->sales_model->add($crud_data);

            redirect('sales_list');
        }else{
            $error['error'] = validation_errors();
            $this->session->set_flashdata($error);
            redirect('sales_add');
        }       
    }

    public function update($id){
        if($this->validation() == true) {
            $crud_data = $this->input_data;
                        
            $this->sales_model->update($id,$crud_data);
            
            redirect('sales_list');
        }else{
            $error['error'] = validation_errors();
            $this->session->set_flashdata($error);
            redirect('sales_update/'.$id);
        }               
    }

    public function softDelete($id){
        $this->sales_model->delete($id);
        redirect('sales_list');

    }
    public function restore($id){
        $inventoryData = $this->inventory_model->find($id);
        $this->inventory_model->restore($id);
        redirect('sales_list');

    }           
}