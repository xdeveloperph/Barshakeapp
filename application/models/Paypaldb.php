<?php
class Paypaldb extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    private  $table ="paypal_ipn";

    function Insert($data){
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

}