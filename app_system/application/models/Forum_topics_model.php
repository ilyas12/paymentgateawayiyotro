<?php
class Forum_topics_model extends AR_Model {

    public function __construct(){
        parent::__construct();
        $this->allow_insert = "topic,topic_detail,user_id_post,reply,like,unlike,forum_stat,admin_conclusion";
        $this->allow_update = "topic,topic_detail,user_id_post,reply,like,unlike,forum_stat,admin_conclusion";
        $this->tblName = "forum_topics";
        $this->tblId = "id";
        $this->search_field = "a.topic,a.topic_detail,a.admin_conclusion,a.created_at,b.first_name,b.last_name,b.username,a.forum_stat,a.code";
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
        $query = "SELECT a.*, CONCAT_WS(' ',b.first_name,b.last_name) as member, DATE(a.created_at) as created_at, a.created_at as created_date
        		  FROM forum_topics a 
        		  LEFT JOIN users b ON a.user_id_post = b.id
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
