<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class user_model extends AR_Model    
{

    public function __construct()
    {
        parent::__construct();
        $this->allow_insert = 'full_name,user_group_id,username,password,email,mobile';
        $this->allow_update = 'full_name,user_group_id,username,password,email,mobile';
		$this->tblName = "user";
        $this->tblId = "id";
    }


    public function list_data( $additional_criteria = "",$data_status = ""){
        $data = array();

        if($data_status == "")
        {   
            $where = " WHERE a.deleted_at IS NULL";
        }
        else if($data_status == "only_trash"){
            $where = " WHERE a.deleted_at IS NOT NULL";
        }
        else if($data_status == "with_trash"){
            $where = " WHERE 1";
        }
        
        $where .= $additional_criteria != "" ? $additional_criteria : "";
        $query = "SELECT a.*, b.name as group_name  FROM  user a LEFT JOIN user_group b ON a.user_group_id = b.id  "  . $where;
        $rows = $this->executeQuery($query);
        foreach ($rows->result_array() as $row) {
            foreach($row as $key=>$val){
                    $row[$key] = $val;
            }
            $data[] = $row;
        }
        
        return $data;
    }





}


