<?php
class Bank_Model extends AR_Model {

    public function __construct(){
        parent::__construct();
        $this->allow_insert = "name,created_by,update_by";
        $this->allow_update = "name,update_by";
        $this->tblName = "master_bank";
        $this->tblId = "id";
    }

}
