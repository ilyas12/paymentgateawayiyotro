<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client extends AR_Controller {

	var $user_id;
	var $client_model;
	var $client_group_model;
	var $prefix_model;
	var $client_address_model;

	function __construct(){
		parent::__construct();
		$adminData = $this->session->userdata("adminData");
		$this->user_id = $adminData['id'];

		//LOAD LIBRARY
        $this->load->library('form_validation');

		// LOAD MODEL
		$this->load->model('master_model');
		$this->client_model 		= new master_model('client');
		$this->client_group_model 	= new master_model('client_group');
		$this->prefix_model 		= new master_model('prefix');
		$this->client_address_model = new master_model('client_address');

	}

	public function index()
	{

		$data['title'] = "CLIENT LIST";
		$data['add_new_link'] = site_url('client_list/new_data');
		$data['datatable'] = $this->datatable();
		$this->template->loadView('admin/client',$data,"admin");
	}

	// DATATABLES
	function datatable(){
		$data = $this->db->select('a.*, b.name as group_name')
				->from('client a')
				->join('client_group b','b.id = a.client_group_id','left')
				->where(array("a.user_id"=>$this->user_id,"a.deleted_at"=>NULL))
				->get()->result();
		return $data;
	}

	// FORM VALIDATION
    function validation(){
        $this->form_validation->set_rules('client_code', 'client code', 'required|max_length[255]');
        $this->form_validation->set_rules('name', 'client name', 'required|max_length[255]');

        return $this->form_validation->run();
    }

    // DISPLAY NEW DATA PAGE
	function new_data(){
		$data['title'] = "ADD NEW CLIENT";
		$data['client_group'] = $this->client_group_model->all(' AND deleted_at IS NULL');
		$data['action_link'] = site_url('client_list/insert_data');
		$this->template->loadView('admin/client_form',$data,"admin");
	}

	// INSERTING DATA
	function insert_data(){
		$prefix = $this->prefix_model->all(" AND prefix_for = 'client' AND deleted_at IS NULL");
        if($this->validation() == true) {
            $crud_data = $this->input_data;
            unset($crud_data['btnSubmit']);
            $crud_data['user_id'] = $this->user_id;
            $crud_data['client_code'] = $prefix[0]['prefix'].sprintf('%0'.$prefix[0]['length'].'d',$crud_data['client_code']);
            $sql = $this->client_model->add($crud_data);

            redirect('client_list');
        }else{
            $error['error'] = validation_errors();
            $this->session->set_flashdata($error);
            redirect('client_list/new_data');
        }
	}

	// DISPLAY EDIT PAGE
	function edit_data($id){
		$data['title'] = "EDIT CLIENT";
		$data['client_group'] = $this->client_group_model->all(' AND deleted_at IS NULL');
		$data['action_link'] = site_url('client_list/update_data/'.$id);
		$data['datas'] = $this->client_model->find($id);
		$this->template->loadView('admin/client_form',$data,"admin");
	}

	// UPDATING DATA
	function update_data($id){
		$prefix = $this->prefix_model->all(" AND prefix_for = 'client' AND deleted_at IS NULL");

        if($this->validation() == true) {
            $crud_data = $this->input_data;
            unset($crud_data['btnSubmit']);
            $crud_data['user_id'] = $this->user_id;
            $crud_data['client_code'] = $prefix[0]['prefix'].sprintf('%0'.$prefix[0]['length'].'d',$crud_data['client_code']);
            $sql = $this->client_model->update($id,$crud_data);

            redirect('client_list');
        }else{
            $error['error'] = validation_errors();
            $this->session->set_flashdata($error);
            redirect('client_list/edit_data');
        }
    }

	// DELETING DATA
	function delete_data($id){
        $this->client_model->delete($id);
        redirect('client_list');
   	}

/**
	CLIENT ADDRESS
*/

	function client_address($id){
		$client = $this->client_model->find($id);
		$data['title'] = " CLIENT ADDRESS LIST - ".$client['name']." ";
		$data['add_new_link'] = site_url('client_list/new_address_data/'.$id);
		$data['datatable'] = $this->address_datatable($id);
		$this->template->loadView('admin/client_address',$data,"admin");
	}

	function address_datatable($id){
		$data = $this->db->select('a.*')
				->from('client_address a')
				->join('client b','b.id = a.client_id','left')
				->where(array("a.client_id"=>$id,"a.deleted_at"=>NULL))
				->get()->result();
		return $data;
	}
	// FORM VALIDATION
    function address_validation(){
        $this->form_validation->set_rules('address', 'client address', 'required');
        $this->form_validation->set_rules('outlet_code', 'outlet code', 'required|max_length[50]');

        return $this->form_validation->run();
    }

    // DISPLAY NEW DATA PAGE
	function new_address_data($id){
		$client = $this->client_model->find($id);
		$data['title'] = "ADD NEW CLIENT ADDRESS";
		$data['client_id'] = $id;
		$data['client_name'] = $client['name'];
		$data['action_link'] = site_url('client_list/insert_address_data');
		$this->template->loadView('admin/client_address_form',$data,"admin");
	}

	// INSERTING DATA
	function insert_address_data(){
        if($this->address_validation() == true) {
            $crud_data = $this->input_data;
            unset($crud_data['btnSubmit']);
            $sql = $this->client_address_model->add($crud_data);

            redirect('client_list/client_address/'.$crud_data['client_id']);
        }else{
            $error['error'] = validation_errors();
            $this->session->set_flashdata($error);
            redirect('client_list/insert_address_data');
        }
	}

	// DISPLAY EDIT PAGE
	function edit_address_data($id){
		$data['title'] = "EDIT CLIENT";
		$data['action_link'] = site_url('client_list/update_address_data/'.$id);
		$data['datas'] = $this->client_address_model->find($id);
		$client = $this->client_model->find($data['datas']['client_id']);
		$data['client_id'] = $client['id'];
		$data['client_name'] = $client['name'];
		$this->template->loadView('admin/client_address_form',$data,"admin");
	}

	// UPDATING DATA
	function update_address_data($id){
        if($this->address_validation() == true) {
            $crud_data = $this->input_data;
            unset($crud_data['btnSubmit']);
            $sql = $this->client_address_model->update($id,$crud_data);
            redirect('client_list/client_address/'.$crud_data['client_id']);
        }else{
            $error['error'] = validation_errors();
            $this->session->set_flashdata($error);
            redirect('client_list/edit_address_data/'.$id);
        }
    }

	// DELETING DATA
	function delete_address_data($id){
        $this->client_address_model->delete($id);
        redirect('client_list/client_address');
   	}
}
