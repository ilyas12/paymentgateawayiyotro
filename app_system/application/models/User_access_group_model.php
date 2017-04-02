<?php
class User_access_group_model extends AR_Model    
{

    public function __construct()
    {
        parent::__construct();
        $this->tblName = "user_access_group";
        $this->tblId = "id";
        $this->allow_insert = "name,access_right"; //,all_project
        $this->allow_update = "name,access_right"; //,all_project
    }
    
    
}
?>
