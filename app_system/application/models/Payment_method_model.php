<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class payment_method_model extends AR_Model    
{
    
    public function __construct()
    {
        parent::__construct();
        //$this->allow_insert = 'name';
        //$this->allow_update = 'name';
		$this->tblName = "payment_methods";
        $this->tblId = "id";
    }

}
?>
