<?php
class Top_up_model extends AR_Model {

    public function __construct(){
        parent::__construct();
        $this->allow_insert = "user_id,topup_number,amount,bank_id_destination,bank_id_from,receipt,id_request_status";
        $this->allow_update = "user_id,topup_number,amount,bank_id_destination,bank_id_from,receipt,id_request_status";
        $this->tblName = "topup_request";
        $this->tblId = "id";
        $this->search_field = "a.code,a.amount,a.transfer_date,b.first_name,b.last_name,b.username,f.name,e.name";
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
        		  e.name as bank_from, f.name as bank_destination
        		  FROM topup_request a 
        		  LEFT JOIN users b ON a.user_id = b.id
                  LEFT JOIN member_account_bank c ON a.bank_id_from = c.id
                  LEFT JOIN admin_account_bank d ON a.bank_id_destination = d.id
                  LEFT JOIN master_bank e ON c.bank_id = e.id
                  LEFT JOIN master_bank f ON d.bank_id = f.id
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
