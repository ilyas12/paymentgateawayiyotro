<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Request_type_model extends AR_Model    
{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->allow_insert = 'name_status';
        $this->allow_update = 'name_status';
		$this->tblName = "request_type";
        $this->tblId = "id";
    }

}
?>
