<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Upload_model extends AR_Model    
{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->allow_insert = 'content,title';
        $this->allow_update = 'content,title';
		$this->tblName = "upload";
        $this->tblId = "id";
    }
    

}
?>
