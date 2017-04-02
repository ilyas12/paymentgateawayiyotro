<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CompanyController extends AR_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("company_model");
	}
	public function profile()
	{
	
		$data['title'] = "Company Profile"; //title to show up in last part of breadcrumb and title of a page
		$data['company_data'] = $this->company_model->find('1');//prefix_invoice
		//die(var_dump($data['company_data']));
		
		//BEGIN EDIT ACTION
		if(isset($this->input_data['btnSubmit'])){
			//BEGIN GETTING INPUT
				$company_detail = $this->input_data;
				$company_detail["company_name"] = strtoupper($company_detail["company_name"]);
				
				$flag = 1;
				
				//validate all inputs
				// BEGIN EMPTY VALIDATION
				$required_fields = array("company_name");
				if($flag == 1 )
				{
						foreach($required_fields as $fields){
							if(!isset($company_detail[$fields]) || $company_detail[$fields] == ""){
								$flag = 0;
								$msg = "fields required";
								break;
							}
						}
				}
				//END EMPTY VALIDATION
				//BEGIN THROW EMPTY INPUT
					unset($company_detail['btnSubmit']);
			//END THROW EMPTY INPUT
			//DELETE LOGO
			if($flag == 1)
			{
				if( isset($company_detail["delete_logo"]) )
				{
					$old_file_path = $data["company_data"]["logo"];
				
					if($old_file_path != ""){
                        if(file_exists($old_file_path)){
                            unlink($old_file_path);
                        }
                        $parts = explode(".",$old_file_path);
                        $old_thumb_path = $parts[0]."_thumb.".$parts[1];
                        if(file_exists($old_thumb_path)){
                            unlink($old_thumb_path);
                        }
                        $company_detail["logo"] = "";
                    } 
					
				}

				unset($company_detail["delete_logo"]);
			}
			//END DELETE LOGO
			//UPLOAD PROSES
			if($flag ==1)
			{
				if($_FILES['logo']['name'] != '')
				{
					
					$file_name 	= 'logo';
					$new_name	= 'logo'.time();
					$new_path	= 'public/images/logo/';
					if($data['company_data']['logo'] != "" || $data['company_data']['logo'] != "NULL" ){
						$old_file_path = $data['company_data']['logo'];
					}else{
						$old_file_path = "";
					}
					$image 		= upload_handler($file_name,$new_name,$new_path, $required = true,$old_file_path);
					
					if($image['status'] == 0){
						//false
						$flag 	= 0;
						$msg 	= $image['error'];
					}else{
						//true
						$company_detail['logo'] = $image['file_path'];
					}
				}
			}
			//END UPLOAD PROSES
			//END GETTING INPUT
			//if all validation is ok
			if($flag == 1){
				$adminData = $this->session->userData("adminData");
				$company_detail["updated_by"] = $adminData["id"];
				$this->company_model->update(1,$company_detail);
				$data['msg'] = "success update company profile ";
				$data['msg_status'] = "success";
				
			}
			else{
				//show the user form with errors
				$data['company_data'] = $company_detail;
				$data['msg'] = $msg;
				$data['msg_status'] = "danger";
			}
		}
		//END ADD ACTION
		$data['company_data'] = $this->company_model->find('1');//prefix_invoice
		
		$this->template->loadView('admin/companyProfile',$data,"admin");//show user list
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */