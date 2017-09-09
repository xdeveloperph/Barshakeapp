<?php
class Userdb extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    private  $table ="user_registration";

    function Insert($data){
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    function checkregistered($data){

        $this->db->where('email', $data);
        $this->db->from($this->table);
        $result = $this->db->count_all_results();;
        if($result==0){
            return true;
        }else{
            return false;
        }
    }

    function CheckVerifyAccount($data)
    {
        $this->db->where('email', $data);
        $this->db->where('verify', 0);
        $this->db->from($this->table);
        $result = $this->db->count_all_results();;
        if($result==0){
            return true;
        }else{
            return false;
        }
    }
    function VerifyAccount($verify){
        $this->db->set('verify', 1);
        $this->db->set('activated', date('Y-m-d H:i:s'));
        $this->db->where('code', $verify);
        $this->db->update($this->table);
    }
    function GetDatabyCode($verify){
        $this->db->select('*');
        $this->db->where('code', $verify);
        $this->db->from($this->table);
        $query =$this->db->get();
        return $query->result_array();
    }
    function GetDatabyEmail($email){
        $this->db->select('*');
        $this->db->where('email', $email);
        $this->db->from($this->table);
        $query =$this->db->get();
        return $query->result_array();
    }
    function GetCountSignupByDate($tempdate){
        $query=$this->db->query("SELECT * FROM `user_registration` where `regdate` LIKE '%$tempdate%'");
        return $query->num_rows();
    }
    function GetSignupCount($from, $to){
        $query=$this->db->query("SELECT * FROM `user_registration` where `regdate` BETWEEN date('$from') and date('$to')");
        return $query->num_rows();
    }

    function GetWeekSignupData(){
        $array=array();
        $date = date("Y/m/d");
        $ts = strtotime($date);
        $dow = date('w', $ts);
        $offset = $dow - 1;
        if ($offset < 0) {
            $offset = 6;
        }
        $ts = $ts - $offset*86400;
        for ($i = 0; $i < 7; $i++, $ts += 86400){
            $array[]=  $this->GetCountSignupByDate(date("Y-m-d", $ts));
        }

        return $array;
    }
    function GetDaysfromActivation($from){
        $query=$this->db->query("SELECT DATEDIFF(now(),date(activated)) AS DiffDate FROM `user_registration` where `email`='$from'");
        return $query->result_array();
    }

}