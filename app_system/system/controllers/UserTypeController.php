<?php
class UserTypeController extends AR_Controller{	
	public function __construct(){
		parent::__construct();
		/*AUTH*/
		
		$this->load->model("user_type_model");

		$this->title 		= 'User Type';
		$this->controller 	= 'UserTypeController';
		
		$this->view_prefix 	= 'userType';
			$this->view_form 	= $this->view_prefix.'Form';
			$this->view_list 	= $this->view_prefix.'List';

		$this->route_prefix = 'user_type';
			$this->route_list 	= $this->route_prefix.'_list';
			$this->route_add 	= $this->route_prefix.'_add';
			$this->route_edit 	= $this->route_prefix.'_edit';
			$this->route_delete = $this->route_prefix.'_delete';
			$this->route_restore= $this->route_prefix.'_restore';
	}

	public function userTypeList(){
		//die(var_dump($this->session->all_userdata()));
		/*INITIAL DATA*/
		$data['title'] 			= $this->title." List"; //title to show up in last part of breadcrumb and title of a page		
		$data['title_status']	= $this->title;
		$data['link_add'] 		= $this->route_add; // anchor link for add new data for this module		
		$data['link_edit'] 		= $this->route_edit;
		$data['link_delete'] 	= $this->route_delete;
		$data['link_restore']	= $this->route_restore;
		$data['C_name'] 		= $this->controller;
		$temp_data = $this->temporary_data;// Temp variable used to pass data session and err msg
		/*END INITIAL DATA*/
		
			/*[?]*/
			$data['msg'] = isset($temp_data['msg']) ? $temp_data['msg'] : "";
			$data['msg_status'] = isset($temp_data['msg_status']) ? $temp_data['msg_status'] : "";
			$data_status = "";

		//BEGIN SEARCH
			$data['txtSearch'] = isset($this->input_data['txtSearch']) ? $this->input_data['txtSearch'] : "";
			$search["name"] = "%".$data['txtSearch']."%";
		//END SEARCH
						
		//BEGIN SORTING
			$data['sort_by'] = isset($this->input_data['sort_by']) ? $this->input_data['sort_by'] : "created_at";
			$data['order'] = isset($this->input_data['order']) ? $this->input_data['order'] : "desc";
		//END SORTING

		//BEGIN PAGE LIMIT
			$data['limit'] = isset($this->input_data['limit']) ? $this->input_data['limit'] : "10";
			$data['page'] = (isset($this->input_data['page'])&&$this->input_data['page']!="") ? $this->input_data['page'] : "1";
		//END PAGE LIMIT

		//BEGIN DATA STATUS FILTER
		$data['data_status'] = isset($this->input_data['data_status']) ? $this->input_data['data_status'] : "";
		if($data['data_status'] == "DELETED"){
			$data_status = "only_trash";//if the status is deleted, then only pull deleted data
		}
		//END DATA STATUS FILTER
		
		/*GENERATE QUERY FOR SEARCHING [LIKE|ORDER BY|LIMIT]*/
		$searchString = generateSearchString($search,$data['sort_by'],$data['order'],$data['page'],$data['limit']);

		/*[DB] - GET DB DATA*/
		$data['db_data'] = $this->user_type_model->all($searchString,$data_status);
		
			/*
			 * HTTP BUILD QUERY
			 * USED FOR FAST SORTING
			 * TRIGGER: USER CLICK THE HEADER TITLE OF THE TABLE
			 */		
			$keys = array("limit","page","sort_by","order");
			foreach($keys as $key){
				$queryStringArray[$key] = $data[$key];
			}
			$queryString = http_build_query($queryStringArray);

		/*PAGINATION CONFIG*/
		$totalSearchString = generateSearchString($search);
		$data['total'] = $this->user_type_model->count($totalSearchString,$data_status);		
		$data['base_url_pagination'] = current_url()."?".$queryString;
		$data['pagination'] = generateBootstrapPagination($data['base_url_pagination'],$data['total'],$data['limit']); //call ar_helper pagination function
		/*END PAGINATION CONFIG*/

		/*DEFAULT VIEW*/
		$this->template->loadView('admin/'.$this->view_list,$data,"admin");//show user list
	}

	public function addUserType(){
		/*INITIAL DATA*/
		$data['title'] = 'Add New '.$this->title;		
		$data['link_cancel'] = $this->route_list;
		/*END INITIAL DATA*/	

		/*IF USER PRESS BTN SUBMIT*/
		if(isset($this->input_data['btnSubmit'])){			

			/*GET USER INPUT*/
			$input = $this->input_data;			
			/*END GET USER INPUT*/					
			
			/*	
			 * FLAG FOR INPUT VALIDATION
			 * 1 : DATA NOT NULL
			 * 0 : DATA NULL
			 */
			$flag = 1;

			/*BEGIN EMPTY VALIDATION*/
			$required_fields = array("name");
			if($flag == 1 )
			{
				foreach($required_fields as $fields){
					if(!isset($input[$fields]) || $input[$fields] == ""){
						$flag = 0;						
						$msg = ucfirst(str_replace("_", " ", $fields))." required";
						break;
					}
				}
			}
			/*END EMPTY VALIDATION*/	

			/*BEGIN THROW EMPTY INPUT*/
				unset($input['btnSubmit']);				
			/*END THROW EMPTY INPUT*/			

			/*IF ALL VALIDATION OK*/
			if($flag != 1){
				/*USER INPUT NOT VALID*/

				/*SET ERROR MSG*/
				$data['msg'] = $msg;
				$data['db_data'] = $input;
				
			}
			else{
				/*[DB] - ADD NEW DATA*/
				$newID = $this->user_type_model->add($input);
				
				/*SET ERROR MSG*/
				$passData['msg'] = 'Success add new data..';
				$this->session->set_userdata("temporary_data",$passData);

				redirect($this->route_list);				
			}
		}

		/*DEFAULT VIEW*/
		$this->template->loadView('admin/'.$this->view_form,$data,'admin');
	}

	public function editUserType($id){
		/*INITIAL DATA*/		
		$data['title'] = 'Edit '.$this->title;		
		$data['link_cancel'] = $this->route_list;	

		/*[DB] - GET DB DATA*/
		$data['db_data'] = $this->user_type_model->find($id);

		/*IF USER PRESS BTN SUBMIT*/
		if(isset($this->input_data['btnSubmit'])){

			/*GET USER INPUT*/
			$input = $this->input_data;
			/*END USER INPUT*/			

			/*	
			 * FLAG FOR INPUT VALIDATION
			 * 1 : DATA NOT NULL
			 * 0 : DATA NULL
			 */
			$flag = 1;
			
			// BEGIN EMPTY VALIDATION
				$required_fields = array("name");
				if($flag == 1 )
				{
					foreach($required_fields as $fields){
						if(!isset($input[$fields]) || $input[$fields] == ""){
							$flag = 0;
							$msg = ucfirst(str_replace("_", " ", $fields))." required";
							break;
						}
					}
				}
			//END EMPTY VALIDATION

			/*BEGIN THROW EMPTY INPUT*/
				unset($input['btnSubmit']);
				unset($data['msg']);
			/*END THROW EMPTY INPUT*/

			/*IF ALL VALIDATION OK*/
			if($flag != 1){
				/*USER INPUT NOT VALID*/

				/*SET ERROR MSG*/
				$data['msg'] = $msg;
				$data['db_data'] = $input;
				
			}
			else{
				/*[DB] - UPDATE DB DATA*/
				$this->user_type_model->update($id,$input);

				/*SET ERROR MSG*/
				$passData['msg'] = 'Update Success';
				$this->session->set_userdata("temporary_data",$passData);

				redirect($this->route_list);	
								
			}	
		}

		/*DEFAULT VIEW*/
		$this->template->loadView('admin/'.$this->view_form,$data,'admin');
	}

	public function softDelete($id){
		$db_data = $this->user_type_model->find($id);		
		$this->user_type_model->delete($id);
		$passData['msg'] = "success deleting ".$db_data['name'];
		$passData['msg_status'] = " success";
		$this->session->set_userdata("temporary_data",$passData);
		redirect($this->route_list);

	}

	public function restore($id){
		$db_data = $this->user_type_model->find($id);
		$this->user_type_model->restore($id);
		$passData['msg'] = "success restoring ".$db_data['name'];
		$passData['msg_status'] = " success";
		$this->session->set_userdata("temporary_data",$passData);
		redirect($this->route_list);

	}
}