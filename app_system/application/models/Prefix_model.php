<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class prefix_model extends AR_Model    
{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->allow_insert = 'prefix,running_number,length,description';
        $this->allow_update = 'prefix,running_number,length,description';
		$this->tblName = "prefix";
        $this->tblId = "id";
    }

}
?>
