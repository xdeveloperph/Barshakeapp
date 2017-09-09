<?php
class GoogleAPI
{
    public $ApiKey;
    public function __construct()
    {
        $this->ApiKey ="AIzaSyAUwWL8t5TF0FvNXzz3OGuUMdVouCQqizU";
    }
    public function GetGeoapi($city,$state,$country){
        $city =str_replace(" ","+",$city);
        $state =str_replace(" ","+",$state);
        $country =str_replace(" ","+",$country);
        $url="https://maps.googleapis.com/maps/api/geocode/json?address=".$city.",".$state.",".$country."&sensor=false&key=".$this->ApiKey;
        $content = file_get_contents($url);
        $jsonreply=json_decode($content);
        if($jsonreply->results[0]->geometry->location != null){
            return  $jsonreply->results[0]->geometry->location;
        }else{
            return null;
        }
    }
}