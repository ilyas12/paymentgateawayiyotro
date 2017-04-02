<?php
class admin_navigation_category_model extends AR_Model    
{

    public function __construct()
    {
        parent::__construct();
        $this->tblName = "admin_navigation_category";
        $this->tblId = "id";
    }
    

}
?>


