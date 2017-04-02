<?php
class Kabkot_Model extends AR_Model {

    public function __construct(){
        parent::__construct();
        $this->allow_insert = "provinsi_id,name,created_by,update_by";
        $this->allow_update = "provinsi_id,name,update_by";
        $this->tblName = "master_kokab";
        $this->tblId = "id";
    }
	
}
