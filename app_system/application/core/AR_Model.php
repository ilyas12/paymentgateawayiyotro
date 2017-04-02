<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AR_Model extends CI_Model {
    protected $tblName;
    protected $_user_id;
    protected $_username;
    public function __construct(){
        parent::__construct();
        $this->load->database();
        $userData = $this->session_management->get_user_session();
        if(isset($userData['id'])){
            $this->_user_id = $userData['id'];
            $this->_username = $userData['username'];
        }else{
            $this->_user_id = 0;
            $this->_username = 'Guest';
        }
    }
    public function __call($method, $args){
        $return = call_user_func_array(array($this, $method), $args);
        return $return;
    }
    private function executeQuery($query){
        $res =  $this->db->query($query);
        return $res;
    }
    private function readAllRows($table_name, $additional_criteria = "",$data_status = ""){
        $data = array();
        if($data_status == "")
        {   
            $where = " WHERE deleted_at IS NULL";
        }
        else if($data_status == "only_trash"){
            $where = " WHERE deleted_at IS NOT NULL";
        }
        else if($data_status == "with_trash"){
            $where = " WHERE 1";
        }
        $where .= $additional_criteria != "" ? $additional_criteria : "";
        $query = "SELECT * FROM " . $table_name . $where;
        $rows = $this->executeQuery($query);
        return $rows->result_array();
    }
    private function readRowInfo($table_name, $id, $by = "", $additional_criteria = ""){
        $data = array();
        if ($by == "") $by = "id";
        $where = ($id != "") ? " WHERE $by = '$id'" : "";
        $where .= $additional_criteria != "" ? $additional_criteria : "";
        $query = "SELECT * FROM " . $table_name . $where;
        $rows = $this->executeQuery($query);
        return $rows->row_array();
    }

    private function getInsertId(){
            return $this->db->insert_id();
    }
    private function insert($tblName,$data,$log = 1){    
        foreach($data as $key=>$val){
            if ($this->db->field_exists($key, $this->tblName)){
                $insert[$key] = $val;
            }   
        }
        $insert["created_at"] = isset($insert['created_at']) ? $insert['created_at'] : date("Y-m-d H:i:s");
		$insert['created_by'] = isset($insert['created_by']) ? $insert['created_by'] : $this->_user_id;
        $insert["updated_at"] = isset($insert['updated_at']) ? $insert['updated_at'] : date("Y-m-d H:i:s");
        $insert['updated_by'] = isset($insert['updated_by']) ? $insert['updated_by'] : $this->_user_id;
        $this->db->insert($tblName, $insert); 
        $insert_id = $this->db->insert_id();
        $query = $this->db->last_query();
        $this->log($tblName,$data,$insert_id,"insert",$query);
        return $insert_id;
    }
    private function updateRow($data,$tblName,$id,$by,$log = 1){
        foreach($data as $key=>$val){
            if ($this->db->field_exists($key, $this->tblName)){
                $update[$key] = $val;
            }
        }
        $update["updated_at"] = isset($update['updated_at']) ? $update['updated_at'] : date("Y-m-d H:i:s");
        $update['updated_by'] = isset($update['updated_by']) ? $update['updated_by'] : $this->_user_id;    
        $this->db->where($by,$id);
        $this->db->update($tblName, $update); 
        $query = $this->db->last_query();
        if($log == 1){
            $this->log($tblName,$data,$id,"update",$query);
        }


    }

    private function countRows($table_name, $additional_criteria = "",$data_status = ""){
        if($data_status == "")
        {   
            $where = " WHERE deleted_at IS NULL";
        }
        else if($data_status == "only_trash"){
            $where = " WHERE deleted_at IS NOT NULL";
        }
        else if($data_status == "with_trash"){
            $where = " WHERE 1";
        }
        $where .= $additional_criteria != "" ? $additional_criteria : "";
        $query = "SELECT COUNT(*) as cnt FROM " . $table_name . $where;
        $rows = $this->executeQuery($query);
        foreach ($rows->result_array() as $row)
        {
            $count = isset($row['cnt']) ? $row['cnt'] : "";
        }
        return $count;
    }
    private function deleteRow($tblName,$id,$by,$log = 1){
        $data["deleted_at"] = date("Y-m-d H:i:s");
        $data['deleted_by'] = $this->_user_id;
        $this->db->where($by,$id);
        $this->db->update($tblName, $data);
        $query = $this->db->last_query();
        $data = $this->readRowInfo($tblName,$id);
        if($log == 1){
            $this->log($tblName,$data,$id,"deactive",$query);
        }
    }
    private function restoreRow($tblName,$id,$by,$log = 1){
        $data["deleted_at"] = NULL;
        $this->db->where($by,$id);
        $this->db->update($tblName, $data);
        $query = $this->db->last_query();
        $data = $this->readRowInfo($tblName,$id);
        if($log == 1){
            $this->log($tblName,$data,$id,"restore",$query);
        }
    }
    private function realDeleteRow($tblName,$id,$data = NULL,$log = 1){
        $this->db->where('id', $id);
        $data = $this->readRowInfo($tblName,$id);
        $this->db->delete($tblName);
        $query = $this->db->last_query();    
        if($log == 1){
            $this->log($tblName,$data,$id,"restore",$query);
        }

    }
    private function log($table,$data,$id,$action,$query){
        $month = date("m-Y");
        $ip = $this->input->ip_address();
        $userData = $this->session->userdata("adminData");
        if(isset($userData['id'])){
            $user_id = $userData['id'];
            $user_name = $userData['username'];
        }else{
            $user_id = 0;
            $user_name = "guest";
        }
        $insert["created_at"] = date("Y-m-d H:i:s");
        $insert['ip'] = $ip;
        $insert['user_id'] = $this->_user_id;
        $insert['user_name'] = $this->_username;
        $insert['affected_table'] = $table;
        $insert['affected_id'] = $id;
        $insert['query'] = $query;
        $insert['action'] = $action;
        $insert['data'] = json_encode($data);
        $insert['wording_log'] = $user_name." ".$action." ".$table;
        
        $this->db->insert('log', $insert);
        //$txt = implode(";",$insert);
        //$myfile = file_put_contents('logs/'.$month.'.txt', $txt.PHP_EOL , FILE_APPEND);
    }

    private function add($data)
    {
        return $this->insert($this->tblName,$data);
    }
    private function all($custom_where="",$data_status=""){
        $rows = $this->readAllRows($this->tblName,$custom_where,$data_status);
        return $rows;
    }
    private function find($id,$by='id',$custom_where="") 
    {
        $rows = $this->readRowInfo($this->tblName, $id ,$by,$custom_where);
        return $rows;
    }
    private function count($custom_where="")
    {
        $num = $this->countRows($this->tblName, $custom_where);
        return $num;
    }
    private function update($id,$data,$by = null)
    {
        if($by == null){
            $by = $this->tblId;
        }
        $this->updateRow($data, $this->tblName,$id,$by);
    }
    private function archive($id,$by = null){
        $by = $by == null ? $this->tblId : $by;
        $this->deleteRow($this->tblName,$id,$by);
    }
    private function delete($id,$by = null){
        $by = $by == null ? $this->tblId : $by;
        $this->realDeleteRow($this->tblName,$id,$this->tblId);
    }
    private function unarchive($id){
        $this->restoreRow($this->tblName,$id,$this->tblId);
    }

}