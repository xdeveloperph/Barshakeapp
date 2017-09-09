<?php
class Notificationdb extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    private  $table ="notification";
    private  $tablesub ="notification_type";

    function Insert($data){
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    function subscription($data,$date){

        $date = new DateTime($date);
        $now = new DateTime();
        $now->diff($date);

        var_dump($now->diff($date));
        $this->db->where('owner', $data);
        $this->db->where('remove',0);
        $this->db->from($this->table);
      //  $query =$this->db->get();
       // return $query->result_array();
    }
    function GetAllNotification($data){
        $this->db->where('owner', $data);
        $this->db->where('remove',0);
        $this->db->from($this->table);
        $query =$this->db->get();
        return $query->result_array();
    }
    function GetNotificationCount($data){
        $this->db->where('owner', $data);
        $this->db->where('remove',0);
        $this->db->from($this->table);
        $query =$this->db->count_all();
        return $query;
    }
}