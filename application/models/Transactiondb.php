<<?php
class Transactiondb extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    private  $table ="transaction";

    function Insert($data){
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    function GetTransaction($id,$code){
        $this->db->select('*');
        $this->db->where('id', $id);
        $this->db->where('code', $code);
        $this->db->from($this->table);
        $query =$this->db->get();
        return $query->result_array();
    }
}