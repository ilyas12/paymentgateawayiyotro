<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Price_model extends AR_Model    
{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->allow_insert = 'fee,start,end';
        $this->allow_update = 'fee,start,end';
		$this->tblName = "price";
        $this->tblId = "id";
    }

    public function get_new_price($price){
    	$q = " SELECT fee FROM price WHERE  $price BETWEEN start AND end LIMIT 1";
    	$q = $this->db->query($q)->row_array();

    	$fee = isset($q["fee"])?$q["fee"]:0;
    	$new_price = $price - $fee;
    	return $new_price;


    }

}
?>
