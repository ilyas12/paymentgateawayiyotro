<?php
/**
 * User: gamalan
 * Date: 3/17/2015
 * Time: 10:06 AM
 */

class Company_bank_account_Model extends AR_Model {

    public function __construct(){
        parent::__construct();
        $this->allow_insert = "bank_id,account_name,account_number,branch,company_code,active,created_by,update_by";
        $this->allow_update = "bank_id,account_name,account_number,branch,company_code,active,update_by";
        $this->tblName = "company_bank_account";
        $this->tblId = "id";
    }

}
