<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class company_model extends AR_Model    
{
    
    public function __construct()
    {
        parent::__construct();
        $this->allow_insert = 'name,company_address,postal_code,email,phone,fax';
        $this->allow_update = 'name,company_address,postal_code,email,phone,fax';
		$this->tblName = "company";
        $this->tblId = "id";
    }

}

