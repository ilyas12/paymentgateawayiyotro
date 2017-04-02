<?php
class admin_navigation_model extends AR_Model    
{

    public function __construct()
    {
        parent::__construct();
        $this->tblName = "admin_navigation";
        $this->tblId = "id";
    }
    
    public function get_categories($accessRightString){
        $data = array();
        $query = "SELECT DISTINCT admin_navigation_category_id FROM ".$this->tblName;

        $accessRightArray = explode(",", $accessRightString);
        
        $i=0;
        foreach($accessRightArray as $key=>$val){
            if($val!=""){
                if($i == 0){
                    $query .= " WHERE";
                }
                else{
                    $query .= " OR";
                }
                $query .= " id = $val";
                $i++;
            }

        }
    
        $rows = $this->executeQuery($query);
        
        foreach ($rows->result_array() as $row) {
            
                $data[] = $row['admin_navigation_category_id'];
        }
        
        return $data;
    }

    public function get_all_categories(){
        $data = array();
        $query = "SELECT * FROM admin_navigation_category ";
    
        $rows = $this->executeQuery($query);
        
        foreach ($rows->result_array() as $row) {
            
                $data[] = $row['id'];
        }
        
        return $data;
    }
   
}
?>
