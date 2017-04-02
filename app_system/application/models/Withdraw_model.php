<?php
class Withdraw_model extends AR_Model {

    public function __construct(){
        parent::__construct();
        $this->allow_insert = "user_id,withdraw_code,amount,bank_id,approve_at,approve_by";
        $this->allow_update = "user_id,withdraw_code,amount,bank_id,approve_at,approve_by";
        $this->tblName = "withdraw_request";
        $this->tblId = "id";
        $this->search_field = "a.code,a.amount,a.created_at,b.first_name,b.last_name,b.username,d.name";
    }



    public function listData( $additional_criteria = "",$data_status = ""){
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
        $query = "SELECT a.*, CONCAT_WS(' ',b.first_name,b.last_name) as member, 
        		  d.name as bank
        		  FROM withdraw_request a 
        		  LEFT JOIN users b ON a.user_id = b.id
                  LEFT JOIN member_account_bank c ON a.bank_id = c.id
        		  LEFT JOIN master_bank d ON c.bank_id = d.id
        		".$where;
        
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
