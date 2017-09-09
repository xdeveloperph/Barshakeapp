<?php
class Subscriptiondb extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    private  $table ="subscription";

    function Insert($data){
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    function Update($data,$itemid){
        $this->db->set('type', $itemid);
        $this->db->set('activated', date('Y-m-d H:i:s'));
        $this->db->where('email', $data);
        $this->db->update($this->table);
    }
    function VerifySubscription($data,$itemid,$objectId){
        $this->db->where('email', $data);
        $this->db->from($this->table);
        $result = $this->db->count_all_results();;
        if($result==0){
            $insertdata=array(
                'email'=>$data,
                'activatedate'=> date('Y-m-d H:i:s'),
                'type'=>$itemid,
                'remove'=>0,
                'objectId'=>$objectId,
            );
            $this->Insert($insertdata);
        }else{
            return false;
        }
    }
    function GetSubscription($data){
        $this->db->where('email', $data);
        $this->db->where('remove',0);
        $this->db->from($this->table);
        $query =$this->db->get();
        return $query->result_array();
    }
    function GetSubscriptionByObjectId($data){
        $this->db->where('objectId', $data);
        $this->db->where('remove',0);
        $this->db->from($this->table);
        $query =$this->db->get();
        return $query->result_array();
    }
    function GetemailByObjectId($data){
        $this->db->where('objectId', $data);
        $this->db->where('remove',0);
        $this->db->from($this->table);
        $query =$this->db->get();
        $result= $query->result_array();
        if(count($result)>0){
            return $result[0]['email'];
        }else{
            return null;
        }

    }
    function CurrentTrialAccounts($from, $to){
        $query=$this->db->query("SELECT * FROM `subscription` where `activatedate` BETWEEN date('$from') and date('$to') and `type`=0");
        return $query->num_rows();
    }
    function remove($data){
        $this->db->set('remove', 1);
        $this->db->where('id', $data);
        $this->db->update($this->table);
    }
    function GetExpireToday(){
        $query=$this->db->query("SELECT * FROM `subscription` where `type`='0' and  `activatedate` between DATE_SUB(Now(),INTERVAL 30 DAY) and DATE_SUB(Now(),INTERVAL 29 DAY) ");
        return $query->num_rows();
    }
}