<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PrefixController extends AR_Controller {
	var $prefix_model;
	public function __construct(){
		parent::__construct();
		$this->load->model("master_model");
		$this->prefix_model = new master_model('prefix');
	}
	public function prefixList()
	{
	
		$data['title'] = "Prefix Setting"; //title to show up in last part of breadcrumb and title of a page
		$data['prefix_purchase'] = $this->prefix_model->find('1');//prefix_purcase
		$data['prefix_quotation'] = $this->prefix_model->find('2');//prefix_quotation
		$data['prefix_sales'] = $this->prefix_model->find('3');//prefix_quotation
		$data['prefix_invoice'] = $this->prefix_model->find('4');//prefix_invoice
		//END ADD ACTION
		$this->template->loadView('admin/prefixList',$data,"admin");//show user list
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */