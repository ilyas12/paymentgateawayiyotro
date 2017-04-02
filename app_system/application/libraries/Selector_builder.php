<?php

class Selector_builder {
    
    public function isOptionProductDetail(){
        $out[0] = 
			array(
				"id" => 0,
				"name" => "Not Check"
			);
		$out[1] =
			array(
				"id" => 1,
				"name" => 'Check'
			);
        
        return $out;
    }
	
	public function isOptionViewTask($option = "all"){
		$out = array();
		if($option == "all"){
			$out[] = array("id"=>0,"name"=>"Active Task");
		}
		
		$out[] = array("id"=>2,"name"=>"Pending");
		
		if($option == "assign" || $option == "all"){
			$out[] = array("id"=>3,"name"=>"Ready to check");
		}
		
		if($option == "owner" || $option == "all"){
			$out[] = array("id"=>1,"name"=>"Completed Task");
		}
		return $out;
	}
	
	public function isOptionStatusCustomer(){
		$out[0] = 
			array(
				"id" => 'Active',
				"name" => "Active"
			);
		$out[1] =
			array(
				"id" => 'Inactive',
				"name" => 'Inactive'
			);
        
        return $out;
	}
	
	public function isOptionStatusCustomerJob(){
		
		$out = array(
			array("id"=>"Draft","name"=>"Draft"),
			array("id"=>"Working","name"=>"Working"),
			array("id"=>"Pending","name"=>"Pending"),
			array("id"=>"Completed","name"=>"Completed")
		);
        
        return $out;
	}

}