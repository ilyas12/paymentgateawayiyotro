<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_group extends AR_Controller {

	var $user_id;
	var $client_group_model;

	function __construct(){
		parent::__construct();
		$adminData = $this->session->userdata("adminData");
		$this->user_id = $adminData['id'];

		//LOAD LIBRARY
        $this->load->library('form_validation');

		// LOAD MODEL
		$this->load->model('master_model');
		$this->client_group_model = new master_model('client_group');
	}

	public function index()
	{

		$data['title'] = "CLIENT GROUP";
		$data['add_new_link'] = site_url('client_group_list/new_data');
		$data['datatable'] = $this->datatable();
		$this->template->loadView('admin/client_group',$data,"admin");
	}

	// DATATABLES
	function datatable(){
		$where = " AND deleted_at IS NULL";
		$data = $this->client_group_model->all($where);
		return $data;
	}

	// FORM VALIDATION
    function validation(){
        $this->form_validation->set_rules('name', 'group name', 'required|max_length[45]');

        return $this->form_validation->run();
    }

    // DISPLAY NEW DATA PAGE
	function new_data(){
		$data['title'] = "ADD NEW CLIENT GROUP";
		$data['action_link'] = site_url('client_group_list/insert_data');
		$this->template->loadView('admin/client_group_form',$data,"admin");
	}

	// INSERTING DATA
	function insert_data(){
        if($this->validation() == true) {
            $crud_data = $this->input_data;
            unset($crud_data['btnSubmit']);
            $sales = $this->client_group_model->add($crud_data);

            redirect('client_group_list');
        }else{
            $error['error'] = validation_errors();
            $this->session->set_flashdata($error);
            redirect('client_group_list/new_data');
        }
	}

	// DISPLAY EDIT PAGE
	function edit_data($id){
		$data['title'] = "EDIT CLIENT GROUP";
		$data['action_link'] = site_url('client_group_list/update_data/'.$id);
		$data['clientGroup_data'] = $this->client_group_model->find($id);
		$this->template->loadView('admin/client_group_form',$data,"admin");
	}

	// UPDATING DATA
	function update_data($id){
        if($this->validation() == true) {
            $crud_data = $this->input_data;
            unset($crud_data['btnSubmit']);
            $sales = $this->client_group_model->update($id,$crud_data);

            redirect('client_group_list');
        }else{
            $error['error'] = validation_errors();
            $this->session->set_flashdata($error);
            redirect('client_group_list/edit_data');
        }
    }

	// DELETING DATA
	function delete_data($id){
        $this->client_group_model->delete($id);
        redirect('client_group_list');
   	}

}
