<?php
class Forum_reply_model extends AR_Model {

    public function __construct(){
        parent::__construct();
        $this->allow_insert = "topic_id,user_id_reply,reply_content";
        $this->allow_update = "topic_id,user_id_reply,reply_content";
        $this->tblName = "forum_reply";
        $this->tblId = "id";
        $this->search_field = "a.created_at,b.first_name,b.last_name,b.username";
    }



    public function listData( $additional_criteria = "",$data_status = ""){
        $data = array();

        if($data_status == "")
        {   
            $where = " WHERE a.deleted_at IS NULL";
        }
        else if($data_status == "only_trash"){
            $where = " WHERE a.deleted_at IS NOT NULL";
        }
        else if($data_status == "with_trash"){
            $where = " WHERE 1";
        }
        
        $where .= $additional_criteria != "" ? $additional_criteria : "";
        $query = "SELECT a.*, 
                  IF(b.id IS NOT NULL,'Member','Admin') as type,
                  CONCAT_WS(' ',b.first_name,b.last_name) as member
                  ,c.full_name as admin
        		  FROM forum_reply a 
        		  LEFT JOIN users b ON a.user_id_reply = b.id
                  LEFT JOIN user c ON a.admin_id_reply = c.id
        		".$where;
        
        $rows = $this->executeQuery($query);
        
        foreach ($rows->result_array() as $row) {
            foreach($row as $key=>$val){
                    $row[$key] = $val;
            }
            $data[] = $row;
        }
        
        return $data;
    }

}
