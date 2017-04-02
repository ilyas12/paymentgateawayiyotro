<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//template
//created by : Julius Michael Rinaldo
//updated by : Samuel
class Access_management
{
	
	private $function_name = '';
	public function check_access($function_name,$redirect=0){
		$this->function_name = $function_name;
		$ci =& get_instance();  
		$ci->load->model("user_access_group_model");
		$ci->load->model("user_access_model");
		$ci->load->model("access_item_model");
		
		$user_data = $ci->session->userdata('adminData');
		if($user_data['username'] == "roasot"){
			return array("access_type"=>"admin");
		}else{
			$access_item = $ci->access_item_model->find($function_name,"code");
			if(isset($access_item['id'])){
				$user_access_id = $user_data['user_access_id'];
				$user_access = $ci->user_access_model->find($user_access_id,"user_access_group_id", " AND access_item_id =".$access_item['id']);
				if(!isset($user_access['id']) || $user_access['access_type'] == "none"){
					//check route
					if($access_item["route"] !="" || $redirect==1){
					// header("location:".base_url("error/301")); 
					}else{
						return 0;
					}
				}else{
					return $user_access;
				}
			}else{
			
				//header("location:".base_url("error/301"));
			}
		}
	}

    public function generate_admin_sidemenu_api($userGroupId=""){
        $result = array();
        $ci =& get_instance();  
        //load all necessary controller
        $ci->load->model("user_access_group_model");
        $ci->load->model("admin_navigation_model");
        $ci->load->model("admin_navigation_category_model");
        $ci->load->model("payment_confirmation_model");
        $ci->load->model("top_up_model");
        $ci->load->model("withdraw_model");
        $ci->load->model("forum_topics_model");
        //find user access right
        $userGroupData = $ci->user_access_group_model->find($userGroupId);
        $accessRightString = $userGroupData['access_right'];
        $userAccess = explode(",", $userGroupData['access_right']);
        
        $categoryIds = $ci->admin_navigation_model->get_categories($accessRightString);
        
        $categoryQuery =" AND deleted_at IS NULL AND ";      
        $in_id = array();
        foreach($categoryIds as $key=>$val){
            if($val!=""){
                $in_id[] =$val; 
            }
        }
      
        $in_id = implode(",", $in_id);
        $categoryQuery .= "  id IN ($in_id) ";
        $categoryQuery .= "  ORDER BY display_order ASC";
        
        if($userGroupId==""){
          $categoryQuery = "";
        }


        $categories = $ci->admin_navigation_category_model->all($categoryQuery);
        $badge = '';
        $current_date = date('Y-m-d');
        foreach($categories as $category)
        {
          $child = array();
          $badge_total = 0;
          if($category['display'] == 1)
          {
            $menus = $ci->admin_navigation_model->all(" AND admin_navigation_category_id = ".$category['id']." AND display = 1 ORDER BY display_order ASC");
            if(count($menus) != 0){
    
              foreach($menus as $menu){
    
                if(in_array($menu['id'], $userAccess)){
                  if($menu['display'] == 1)
                  {
                    $badge = '';
                    switch ($menu['label']) {
                      case 'Withdraw On Going':
                        $menu['label'] = 'On Going';
                        $where =" AND (a.id_request_type = 5 || a.id_request_type = 3 || a.id_request_type = 7 )"; //AND a.created_at < '$current_date' ";
                        $t = count($ci->withdraw_model->listData($where." ORDER BY a.created_at DESC  "));
                        $badge = $t;
                        $badge_total += $badge;
                        break;
                      case 'Withdraw Today':
                        $menu['label'] = 'Today';
                        $where =" AND DATE(a.created_at) = '$current_date' ";
                        $t = count($ci->withdraw_model->listData($where." ORDER BY a.created_at DESC  "));
                        $badge = $t;
                        $badge_total += $badge;
                        break;
                      case 'Forum Today':
                        $menu['label'] = 'Today';
                        $where =" AND DATE(a.created_at) = '$current_date' ";
                        $t = count($ci->forum_topics_model->listData($where." ORDER BY a.created_at DESC  "));
                        $badge = $t;
                        $badge_total += $badge;
                        break;
                      case 'Forum On Going':
                        $menu['label'] = 'On Going';
                        $where =" AND (a.forum_stat IS NULL || a.forum_stat = 0 || a.forum_stat = 8 )"; //AND a.created_at < '$current_date' ";
                        $t = count($ci->forum_topics_model->listData($where." ORDER BY a.created_at DESC  "));
                        $badge = $t;
                        $badge_total += $badge;
                        break;
                      case 'Top Up Today':
                        $where =" AND DATE(a.created_at) = '$current_date' ";
                        $t = count($ci->top_up_model->listData($where." ORDER BY a.created_at DESC  "));
                        $badge = $t;
                        $badge_total += $badge;
                        break;
                      case 'Top Up On Going':
                        $menu['label'] = 'On Going';
                        $where .=" AND (a.id_request_type = 5 || a.id_request_type = 3 )"; //AND a.created_at < '$current_date' ";
                        $t = count($ci->top_up_model->listData($where." ORDER BY a.created_at DESC  "));
                        $badge = $t;
                        $badge_total += $badge;
                        break;
                      case 'Today':
                        $where =" AND DATE(a.created_at) = '$current_date' ";
                        $t = count($ci->payment_confirmation_model->listData($where." ORDER BY a.created_at DESC  "));
                        $badge = $t;
                        $badge_total += $badge;
                        break;
                      case 'On Going':
                        $where =" AND (a.id_request_type != 2 && a.id_request_type != 1 && a.id_request_type != 4 )"; //AND a.created_at < '$current_date' ";
                        $t = count($ci->payment_confirmation_model->listData($where." ORDER BY a.created_at DESC  "));
                        $badge = $t;
                        $badge_total += $badge;
                        break;
                      default:
                        $badge = 0;
                        $badge_total += $badge;
                        break;
                    }

                    $child[] = array(
                      "label" => $menu['label'],
                      "routes" => $menu['routes'],
                      "badge" => $badge>0?$badge:''
                    );
                  }
                
                }
    
              }
    
            }
            $badge_total = $badge_total > 0?$badge_total:'';
            $result[] = array(
              "label" => $category['label'],
              "child_count" => count($child),
              "routes" => $category['routes'],
              "icon"  => $category['icon'],
              "badge"  => $badge_total,
			        "menu_active" => $category['label'],
              "child" => $child
              
            );
          }
        }

      return $result;
    }

}