<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Users_model extends AR_Model    
{

    public function __construct()
    {
        parent::__construct();
        $this->allow_insert = 'username,email,first_name,last_name,company,phone,address,birthday,saldo';
        $this->allow_update = 'username,email,first_name,last_name,company,phone,address,birthday,saldo';
		$this->tblName = "users";
        $this->tblId = "id";
    }


}


