<?php
class Payment_confirmation_model extends AR_Model {

    public function __construct(){
        parent::__construct();
        $this->allow_insert = "user_id,payment_type,member_id,payment_date,bank_id_transfer,bank_id_detination,amount,description,receipt,id_request_type,cancel_reason,no_payment";
        $this->allow_update = "user_id,payment_type,member_id,payment_date,bank_id_transfer,bank_id_detination,amount,description,receipt,id_request_type,cancel_reason,no_payment";
        $this->tblName = "payment_confirmation";
        $this->tblId = "id";
        $this->search_field = "a.payment_date,a.description,a.amount,a.created_at,c.first_name,c.last_name,b.first_name,b.last_name,b.username,approve.full_name,h.name,i.name,a.no_payment";
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
        // member id =recive, user_id = send
        $where .= $additional_criteria != "" ? $additional_criteria : "";
        $query = "SELECT a.*, CONCAT_WS(' ',c.first_name,c.last_name) as member_recieve, c.email as member_recieve_email,a.approve_by,
                  approve.full_name as admin,  CONCAT_WS(' ',b.first_name,b.last_name) as member_send,  b.email as member_send_email,
                  h.name as bank_transfer, i.name as bank_destination ,
                  a.ip_buyer,a.ip_seller,a.amount_less,
                  f.name as payment_type
        		      FROM payment_confirmation a 
        		      LEFT JOIN users b ON a.user_id = b.id
                  LEFT JOIN users c ON a.member_id = c.id
                  LEFT JOIN user approve ON a.approve_by = approve.id
                  LEFT JOIN member_account_bank d ON a.bank_id_transfer = d.id
                  LEFT JOIN member_account_bank e ON a.bank_id_detination = e.id
                  LEFT JOIN payment_type f ON a.payment_type = f.id
                  LEFT JOIN master_bank h ON d.bank_id = h.id
                  LEFT JOIN master_bank i ON e.bank_id = i.id
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

    public function getDetailData($id)
    {

        $query = "SELECT a.id, a.payment_date, a.amount as recieve_saldo, a.path_receipt,a.fee,a.amount,a.received_amount,a.no_payment,
                  CONCAT_WS(' ',c.first_name,c.last_name) as send_full_name, c.username as send_username, a.received_amount as send_saldo, c.last_login as send_last_login, a.ip_buyer as send_ip,
                  CONCAT_WS(' ',b.first_name,b.last_name) as recieve_full_name, b.username as recieve_username, b.last_login as recieve_last_login,a.ip_seller as receive_ip,
                  h.name as bank_from, i.name as bank_destination , g.name_status as status,
                  approve.full_name as admin,a.amount_less,
                  f.name as payment_type
                  FROM payment_confirmation a 
                  LEFT JOIN users b ON a.member_id = b.id
                  LEFT JOIN users c ON a.user_id = c.id
                  LEFT JOIN user approve ON a.approve_by = approve.id
                  LEFT JOIN member_account_bank d ON a.bank_id_transfer = d.id
                  LEFT JOIN member_account_bank e ON a.bank_id_detination = e.id
                  LEFT JOIN payment_type f ON a.payment_type = f.id
                  LEFT JOIN request_type g ON a.id_request_type = g.id
                  LEFT JOIN master_bank h ON d.bank_id = h.id
                  LEFT JOIN master_bank i ON e.bank_id = i.id
                  WHERE a.id = $id
                  LIMIT 1
                ";


        $row = $this->executeQuery($query)->row_array();
        
        return $row;
    }

}
