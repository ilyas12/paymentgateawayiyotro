<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//template
//created by : Julius Michael Rinaldo

class Session_management
{
	private $container = "admin_data";

	public function store_user_session(){

	}
	public function get_user_session(){
		$ci =& get_instance(); 
		$user_data = $ci->session->userdata('adminData');
		return $user_data;
	}
}