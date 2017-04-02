<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Transaction_history_model extends AR_Model    
{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->allow_insert = 'member_id,transaction_date,transaction_type,description,start_balance,debit,credit,ending_balance';
        $this->allow_update = 'member_id,transaction_date,transaction_type,description,start_balance,debit,credit,ending_balance';
		$this->tblName = "transaction_history";
        $this->tblId = "id";
    }
    

}
?>
