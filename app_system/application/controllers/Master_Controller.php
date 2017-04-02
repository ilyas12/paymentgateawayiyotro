<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Controller extends AR_Controller {
	public function index()
	{
		$this->load->model("company_model");
		$data['company'] = $this->company_model->find(1);
		$this->load->view("admin_template",$data);
	}
}
