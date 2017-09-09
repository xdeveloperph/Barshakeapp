<?php

error_reporting(0);
require_once('parse/autoload.php');
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseUser;
use Parse\ParseException;
use Parse\ParseFile;
use Parse\ParseGeoPoint;
use Parse\ParseInstallation;
use Parse\ParseSessionStorage;
use Parse\ParseClient;
session_start();
class ParseAPI{
    public $session;
    public $storage;
    public $accountType;
    public function __construct()
    {

        $app_id ="pFf1vJma5zjk7duy9llyDe8pjJk5nlG4OsyjtJxq";
        $rest_key ="n79jkZyDg4fcYF7t8Fg3aRwbeyp0S0zBbYOBuBdW";
        $master_key ="ILVh4b3cZxqzPbZUFNBu4CCkYQwc7SjK9EU9V0NR";
        Parse\ParseClient::initialize( $app_id, $rest_key, $master_key );


    }
    /// user login access


    public function SignUp($email,$pass){
        $user = new Parse\ParseUser();
        $user->set("username", $email);
        $user->set("password", $pass);
        $user->set("email", $email);
        try {
            $user->signUp();
            return $user->getObjectId();
        } catch (ParseException $ex) {

            return false;
        }
    }
    public function Login($username,$password)
    {
        try {
            $this->storage = new ParseSessionStorage();
            Parse\ParseClient::setStorage($this->storage);
            Parse\ParseUser::logIn($username, $password);
            return $this->getCurrentUser();
        } catch (ParseException $ex) {
            return null;
        }

    }
    public function getCurrentUser()
    {
        $user=Parse\ParseUser::getCurrentUser();
        if($user != null){
            $this->session =$user;
            return $user;
        }else{
            return null;
        }

    }
    public function Logout()
    {
        Parse\ParseUser::logOut();
    }

    public function installationCount()
    {
        $query = new Parse\ParseQuery("_Installation");
        $results = $query->find();


    }
    //_user class---------------------------------------------------------------------------------


    public  function UpdateUserLocation($username,$lat,$lon){

        try {
            $query = new ParseQuery("_User");
            $query->equalTo("username", $username);
            $result = $query->find();
            if(count($result)>0) {
                $user =$result[0];
                $point = new ParseGeoPoint($lat,$lon);
                $user->set("geolocation", $point);
                $user->save();
            }
        } catch (ParseException $ex) {
            echo $ex;

            // The token could not be validated.
        }

    }
    public  function UpdateUserPassword($username,$data){

        try {
            $user =Parse\ParseUser::logIn($username, $data['old']);
            $user->set("password", $data['new']);  // attempt to change username
            $user->save();
            return true;
            // The current user is now set to user.
        } catch (ParseException $ex) {

            return false;
            // The token could not be validated.
        }

    }
    public function CheckAvaiability($username)
    {
        $query = new Parse\ParseQuery("_User");
        $query->equalTo("username", $username);
        $results = $query->find();
        if(count($results)>0){
            return false;
        }else{
            return true;
        }
    }
    public function GetUserEmail($objectid)
    {
        $query = new Parse\ParseQuery("_User");
        $query->equalTo("objectId", $objectid);
        $results = $query->find();
        if(count($results)>0) {
            return $results[0]->get('username');
        }else{
            return null;
        }
    }

    //information class---------------------------------------------------------------------------------

    public function CheckAccountType()
    {
        $query = new Parse\ParseQuery("Information");
        $query->equalTo("owner", Parse\ParseUser::getCurrentUser());
        $results = $query->find();
        if(count($results)>0){
            return $results[0]->get('type');
        }else{
            return 0;
        }
    }
    public function CheckUserInfo(){
        $query = new ParseQuery("Information");
        $query->equalTo("owner", ParseUser::getCurrentUser());
        $results = $query->find();
        if(count($results)>0) {
            return false;
        }else{
            return true;
        }
    }
    public function UserInformation(){
        $query = new ParseQuery("Information");
        $query->equalTo("owner", ParseUser::getCurrentUser());
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){
            $tempgeo= $tempdata->get('geoloc');
            $tempimage=$tempdata->get('image');
            $tempowner=$tempdata->get('owner');
            $data[]=array(
                'firstName'=>$tempdata->get('firstName'),
                'lastName'=>$tempdata->get('lastName'),
                'mobile'=>$tempdata->get('mobile'),
                'address'=>$tempdata->get('address'),
                'state'=>$tempdata->get('state'),
                'city'=>$tempdata->get('city'),
                'country'=>$tempdata->get('country'),
                'type'=>$tempdata->get('type'),
                'zip'=>$tempdata->get('zip'),
                'lat'=>(!empty($tempgeo))?$tempgeo->getLatitude(): "",
                'lon'=>(!empty($tempgeo))?$tempgeo->getLongitude(): "",
                'image'=>(!empty($tempimage))?$tempimage->getURL() : "",
                'owner'=>$tempowner->getObjectId(),
            );
        }
        return $data;
    }
    public function GetUserInformationbyId($id){
        $query = new ParseQuery("Information");
        $query->equalTo("objectId", $id);
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){
            $tempgeo= $tempdata->get('geoloc');
            $tempimage=$tempdata->get('image');
            $tempowner=$tempdata->get('owner');
            $data[]=array(
                'firstName'=>$tempdata->get('firstName'),
                'lastName'=>$tempdata->get('lastName'),
                'mobile'=>$tempdata->get('mobile'),
                'address'=>$tempdata->get('address'),
                'state'=>$tempdata->get('state'),
                'type'=>$tempdata->get('type'),
                'city'=>$tempdata->get('city'),
                'country'=>$tempdata->get('country'),
                'zip'=>$tempdata->get('zip'),
                'lat'=>(!empty($tempgeo))?$tempgeo->getLatitude(): "",
                'lon'=>(!empty($tempgeo))?$tempgeo->getLongitude(): "",
                'image'=>(!empty($tempimage))?$tempimage->getURL() : "",
                'owner'=>$tempowner->getObjectId(),
            );
        }
        return $data;
    }
    public function GetAllUserInformation($limit,$skip,$sort,$search){
        $query = new ParseQuery("Information");
        if(isset($sort)){
            if($sort=='az'){
                $query->ascending("firstName");
            }else{
                $query->descending("lastName");
            }
        }
        if(isset($search)){
            if(isset($search['cat'])) {

            if($search['cat'] =='first'){
                $query->startsWith("firstName", $search['text']);
            }else{
                $query->startsWith("lastName", $search['text']);
            }
            }

        }
        $query->limit($limit);
        $query->skip($skip);
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){
            $tempgeo= $tempdata->get('geoloc');
            $tempimage=$tempdata->get('image');
            $tempowner=$tempdata->get('owner');
            $data[]=array(
                'objectId'=>$tempdata->getObjectId(),
                'firstName'=>$tempdata->get('firstName'),
                'lastName'=>$tempdata->get('lastName'),
                'mobile'=>$tempdata->get('mobile'),
                'address'=>$tempdata->get('address'),
                'state'=>$tempdata->get('state'),
                'city'=>$tempdata->get('city'),
                'country'=>$tempdata->get('country'),
                'created'=>$tempdata->getCreatedAt(),
                'type'=>$tempdata->get('type'),
                'zip'=>$tempdata->get('zip'),
                'lat'=>(!empty($tempgeo))?$tempgeo->getLatitude(): "",
                'lon'=>(!empty($tempgeo))?$tempgeo->getLongitude(): "",
                'image'=>(!empty($tempimage))?$tempimage->getURL() : "",
                'owner'=>$tempowner->getObjectId(),

            );
        }
        return $data;
    }
    public function AddUserInformation($data,$geo){

        $userdata = new ParseObject("Information");
        $userdata->set("owner",  ParseUser::getCurrentUser());
        $userdata->set("firstName", $data['firstName']);
        $userdata->set("lastName", $data['lastName']);
        $userdata->set("address",  $data['address']);
        $userdata->set("state", $data['state']);
        $userdata->set("city", $data['city']);
        $userdata->set("country", $data['country']);
        $userdata->set("zip", $data['zip']);
        $userdata->set("type", 0);
        $point = new ParseGeoPoint($geo->lat,$geo->lng);
        $userdata->set("geoloc",$point);
        try {
            $userdata->save();
            $this->UpdateUserLocation($data['username'],$geo->lat,$geo->lng);
            return true;
        } catch (ParseException $ex) {

            return false;
        }
    }
    public function SetUserInformation($data,$file,$geo){
        $set_file= null;
        if(isset($file['tmp_name'])){
            if(!empty($file['tmp_name'])){
                $set_file = ParseFile::createFromFile($file['tmp_name'] ,"photo");
                $set_file->save();
            }
        }
        $query = new ParseQuery("Information");
        $query->equalTo("owner", ParseUser::getCurrentUser());
        $results = $query->find();
        $userdata= null;
        if(count($results)>0){
            $userdata =$results[0];
            $userdata->set("firstName", $data['firstName']);
            $userdata->set("lastName", $data['lastName']);
            $userdata->set("mobile", $data['mobile']);
            $userdata->set("address",  $data['address']);
            $userdata->set("state", $data['state']);
            $userdata->set("city", $data['city']);
            $userdata->set("country", $data['country']);
            $userdata->set("zip", $data['zip']);
            $userdata->set("type", 0);
            $point = new ParseGeoPoint($geo->lat,$geo->lng);
            $userdata->set("geoloc",$point);
            if(!empty($set_file))$userdata->set("photo",$set_file);
        }else{
            $userdata = new ParseObject("Information");
            $userdata->set("owner",  ParseUser::getCurrentUser());
            $userdata->set("firstName", $data['firstName']);
            $userdata->set("lastName", $data['lastName']);
            $userdata->set("mobile", $data['mobile']);
            $userdata->set("address",  $data['address']);
            $userdata->set("state", $data['state']);
            $userdata->set("city", $data['city']);
            $userdata->set("country", $data['country']);
            $userdata->set("zip", $data['zip']);
            $userdata->set("type", 0);
            $point = new ParseGeoPoint($geo->lat,$geo->lng);
            $userdata->set("geoloc",$point);
            if(!empty($set_file))$userdata->set("photo",$set_file);
        }
        try {
            $userdata->save();
            $this->UpdateUserLocation($data['username'],$geo->lat,$geo->lng);
            return true;
        } catch (ParseException $ex) {

            // Execute any logic that should take place if the save fails.
            // error is a ParseException object with an error code and message.
            return false;
        }
    }
    public function UpdateAccount($id,$data,$file,$geo){
        $set_file= null;
        if(isset($file['tmp_name'])){
            if(!empty($file['tmp_name'])){
                $set_file = ParseFile::createFromFile($file['tmp_name'] ,"photo");
                $set_file->save();
            }
        }
        $query = new ParseQuery("Information");
        $query->equalTo("objectId",$id);
        $results = $query->find();
        $userdata= null;
        if(count($results)>0){
            $userdata =$results[0];
            $userdata->set("firstName", $data['firstName']);
            $userdata->set("lastName", $data['lastName']);
            $userdata->set("mobile", $data['mobile']);
            $userdata->set("address",  $data['address']);
            $userdata->set("state", $data['state']);
            $userdata->set("city", $data['city']);
            $userdata->set("country", $data['country']);
            $userdata->set("zip", $data['zip']);
            $userdata->set("type", (int)$data['type']);
            $point = new ParseGeoPoint($geo->lat,$geo->lng);
            $userdata->set("geoloc",$point);
            if(!empty($set_file))$userdata->set("photo",$set_file);
        }
        try {
            $userdata->save();
            $this->UpdateUserLocation($data['username'],$geo->lat,$geo->lng);
            return true;
        } catch (ParseException $ex) {
            echo $ex;
            // Execute any logic that should take place if the save fails.
            // error is a ParseException object with an error code and message.
            return false;
        }
    }
    public function GetInformationCount()
    {
        $query = new ParseQuery("Information");
        $results = $query->count();
        return $results;
    }

    //category class---------------------------------------------------------------------------------

    public function GetProducsCategory(){
        $query = new ParseQuery("Category");
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){
            $data[]=array(
                'id'=>$tempdata->getObjectId(),
                'category_name'=>$tempdata->get('category_name')
            );
        }
        return $data;
    }
    public function GetProducsCategoryById($id){
        $query = new ParseQuery("Category");
        $query->equalTo("objectId", $id);
        $results = $query->find();
        if(count($results)>0){
            return $results[0];
        }else{
            return null;
        }

    }
    public function SearchCategorybyName($search){
        $query = new ParseQuery("Category");
        $query->startsWith("category_name", $search);
        $results = $query->find();
        if(count($results)>0){
            return $results[0]->getObjectId();
        }else{
            return null;
        }

    }

    //drinks class---------------------------------------------------------------------------------


    public  function UpdateDrinks($data,$id,$file){

        $query = new ParseQuery("Drinks");
        $query->equalTo("objectId", $id);
        $result = $query->find();
        if(count($result)>0){
            $set_file= null;
            if(isset($file['tmp_name'])){
                if(!empty($file['tmp_name'])){
                    $set_file = ParseFile::createFromFile($file['tmp_name'] ,"photo");
                    $set_file->save();
                }
            }
            $dataDrinks =$result[0];
            $tag = explode(",", $data['tags']);
            $dataDrinks->set("drinkName", $data['drinkName']);
            //$dataDrinks->set("owner", $this->getCurrentUser());
            //$dataDrinks->set("glass", $data['glass']);
            $tempcategory=$this->GetProducsCategoryById($data['category']);
            $tag[]=$tempcategory->get('category_name');
            $dataDrinks->set("categoryId",$tempcategory);
            $tempflavors = $this->GetProductsFlavorById($data['flavor']);
            $tag[]=$tempflavors->get('flavor');
            $dataDrinks->set("flavor", $tempflavors);
            $drinktag= array_map('trim', array_unique($tag));
            $dataDrinks->setArray("tags",$drinktag);
            $dataDrinks->set("Ingredients", $data['ingredients']);
            $dataDrinks->set("disable", false);
            if($set_file != null)$dataDrinks->set("image",$set_file);
            try {
                $dataDrinks->save();
                return true;
            } catch (ParseException $ex) {

                // Execute any logic that should take place if the save fails.
                // error is a ParseException object with an error code and message.
                return false;
            }
        }
    }

    public  function AddDrinks($data,$file){
        $set_file= null;
        if(isset($file['tmp_name'])){
            if(!empty($file['tmp_name'])){
                $set_file = ParseFile::createFromFile($file['tmp_name'] ,"photo");
                $set_file->save();
            }
        }
        $tag = explode(",", $data['tags']);
        $dataDrinks = new ParseObject("Drinks");
        $tempprocedure="";
        if(isset($data['procedure']))$tempprocedure=$data['procedure'];
        $dataDrinks->set("drinkName", $data['drinkName']);
        $dataDrinks->set("owner", $this->getCurrentUser());
        $tempcategory=$this->GetProducsCategoryById($data['category']);
        $tag[]=$tempcategory->get('category_name');
        $dataDrinks->set("categoryId",$tempcategory);
        $tempflavors = $this->GetProductsFlavorById($data['flavor']);
        $tag[]=$tempflavors->get('flavor');
        $dataDrinks->set("flavor", $tempflavors);
        $drinktag= array_map('trim', array_unique($tag));
        $dataDrinks->setArray("tags",$drinktag);
        $dataDrinks->set("Ingredients", $data['ingredients']);
        $dataDrinks->set("procedure",$tempprocedure);
        $dataDrinks->set("disable", false);
        if(!empty($data['restaurant']))$dataDrinks->setArray("restaurant", ['__type' => "Pointer", 'className'=> "Restaurant", 'objectId' => $data['restaurant']]);
        if(!empty($set_file))$dataDrinks->set("image",$set_file);

        try {
            $dataDrinks->save();
            return true;
        } catch (ParseException $ex) {

            // Execute any logic that should take place if the save fails.
            // error is a ParseException object with an error code and message.
            return false;
        }
    }
    public  function BulkAddDrinks($data,$file,$index){
        $set_file= null;
        if(isset($file['tmp_name'][$index])){
            if(!empty($file['tmp_name'][$index])){
                $set_file = ParseFile::createFromFile($file['tmp_name'][$index] ,"photo");
                $set_file->save();
            }
        }
        $tag = explode(",", $data['tags']);
        $dataDrinks = new ParseObject("Drinks");
        $dataDrinks->set("drinkName", $data['drinkName']);
        $dataDrinks->set("owner", $this->getCurrentUser());
        //$dataDrinks->set("glass", $data['glass']);
        $tempcategory=$this->GetProducsCategoryById($data['category']);
        $tag[]=$tempcategory->get('category_name');
        $dataDrinks->set("categoryId",$tempcategory);
        $tempflavors = $this->GetProductsFlavorById($data['flavor']);
        $tag[]=$tempflavors->get('flavor');
        $dataDrinks->set("flavor", $tempflavors);
        $drinktag= array_map('trim', array_unique($tag));
        $dataDrinks->setArray("tags",$drinktag);
        $dataDrinks->set("Ingredients", $data['ingredients']);
        $dataDrinks->set("disable", false);
        if(!empty($data['restaurant']))$dataDrinks->setArray("restaurant", ['__type' => "Pointer", 'className'=> "Restaurant", 'objectId' => $data['restaurant']]);
        if(!empty($set_file))$dataDrinks->set("image",$set_file);

        try {
            $dataDrinks->save();
            return true;
        } catch (ParseException $ex) {

            // Execute any logic that should take place if the save fails.
            // error is a ParseException object with an error code and message.
            return false;
        }
    }
    public  function RemoveDrinks($id){

        $query = new ParseQuery("Drinks");
        $query->equalTo("objectId", $id);
        $result = $query->find();
        if(count($result)>0){
            $dataDrinks =$result[0];
            $dataDrinks->set("disable", true);
            try {
                $dataDrinks->save();
                return true;
            } catch (ParseException $ex) {
                // Execute any logic that should take place if the save fails.
                // error is a ParseException object with an error code and message.
                return false;
            }
        }

    }
    public  function RemoveDrinksByUser($id){

        $query = new ParseQuery("Drinks");
        $query->set("owner",  ParseUser::getCurrentUser());
        $query->equalTo("objectId", $id);
        $result = $query->find();
        if(count($result)>0){
            $dataDrinks =$result[0];
            $dataDrinks->set("disable", true);
            try {
                $dataDrinks->save();
                return true;
            } catch (ParseException $ex) {
                // Execute any logic that should take place if the save fails.
                // error is a ParseException object with an error code and message.
                return false;
            }
        }

    }
    public function GetUserProducts(){
        $allcat=$this->GetProducsCategory();
        $allflavor=$this->GetProductsFlavor();
        $query = new ParseQuery("Drinks");
        $query->equalTo("owner", Parse\ParseUser::getCurrentUser());
        $query->equalTo("disable", false);
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){
            // get category
            $tempcat= $tempdata->get('categoryId');
            $key = array_search($tempcat->getObjectId(), array_column($allcat, 'id'));
            $cattext="";
            if($key != false){
                $cattext= $allcat[$key]['category_name'];
            }
            $flavtext = "";
            $tempflavor = $tempdata->get('flavor');
            if (!empty($tempflavor)) {

                $key = array_search($tempflavor->getObjectId(), array_column($allflavor, 'id'));

                if ($key != false) {
                    $flavtext = $allflavor[$key]['flavor'];
                }
            }
            $tempimage=$tempdata->get('image');
            $data[]=array(
                'objectId'=>$tempdata->getObjectId(),
                'tags'=>$tempdata->get('tags'),
                'ingredients'=>$tempdata->get('Ingredients'),
                'drinkName'=>$tempdata->get('drinkName'),
                'glass'=>$tempdata->get('glass'),
                'category'=>$cattext,
                'flavor'=>$flavtext,
                'image'=>(!empty($tempimage))?$tempimage->getURL() : ""
            );
        }
        return $data;
    }
    public function GetUserProductsById($id){
        $allcat=$this->GetProducsCategory();
        $allflavor=$this->GetProductsFlavor();
        $query = new ParseQuery("Drinks");
        $query->equalTo("owner", Parse\ParseUser::getCurrentUser());
        $query->equalTo("disable", false);
        $query->equalTo("objectId", $id);
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){
            // get category
            $tempcat= $tempdata->get('categoryId');
            $key = array_search($tempcat->getObjectId(), array_column($allcat, 'id'));
            $cattext="";
            if($key != false){
                $cattext= $allcat[$key]['category_name'];
            }
            $flavtext = "";
            $tempflavor = $tempdata->get('flavor');
            if (!empty($tempflavor)) {

                $key = array_search($tempflavor->getObjectId(), array_column($allflavor, 'id'));

                if ($key != false) {
                    $flavtext = $allflavor[$key]['flavor'];
                }
            }

            $tempimage=$tempdata->get('image');
            $data[]=array(
                'objectId'=>$tempdata->getObjectId(),
                'tags'=>$tempdata->get('tags'),
                'ingredients'=>$tempdata->get('Ingredients'),
                'drinkName'=>$tempdata->get('drinkName'),
                'glass'=>$tempdata->get('glass'),
                'category'=>$cattext,
                'flavor'=>$flavtext,
                'image'=>(!empty($tempimage))?$tempimage->getURL() : ""
            );
        }
        return $data;
    }
    public function GetAllProductsById($id){
        $allcat=$this->GetProducsCategory();
        $allflavor=$this->GetProductsFlavor();
        $query = new ParseQuery("Drinks");
        $query->equalTo("objectId", $id);
        $query->equalTo("disable", false);
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata) {
            // get category
            $tempcat = $tempdata->get('categoryId');
            $key = array_search($tempcat->getObjectId(), array_column($allcat, 'id'));
            $cattext = "";
            if ($key != false) {
                $cattext = $allcat[$key]['category_name'];
            }
            // get flavor
            $tempflavor = $tempdata->get('flavor');
            $flavtext = "";
            if (!empty($tempflavor)) {

                $key = array_search($tempflavor->getObjectId(), array_column($allflavor, 'id'));

                if ($key != false) {
                    $flavtext = $allflavor[$key]['flavor'];
                }
            }
            // get image
            $tempimage=$tempdata->get('image');
            $data[]=array(
                'objectId'=>$tempdata->getObjectId(),
                'tags'=>$tempdata->get('tags'),
                'ingredients'=>$tempdata->get('Ingredients'),
                'drinkName'=>$tempdata->get('drinkName'),
                'glass'=>$tempdata->get('glass'),
                'category'=>$cattext,
                'flavor'=>$flavtext,
                'image'=>(!empty($tempimage))?$tempimage->getURL() : ""
            );
        }
        return $data;
    }
    public function SearchUserProductsByName($search){
        $allcat=$this->GetProducsCategory();
        $allflavor=$this->GetProductsFlavor();
        $query = new ParseQuery("Drinks");
        $query->equalTo("owner", Parse\ParseUser::getCurrentUser());
        $query->startsWith("drinkName", $search);
        $query->equalTo("disable", false);
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){
            $tempcat= $tempdata->get('categoryId');
            $key = array_search($tempcat->getObjectId(), array_column($allcat, 'id'));
            $cattext="";
            if($key != false){
                $cattext= $allcat[$key]['category_name'];
            }
            // get flavor
            $tempflavor = $tempdata->get('flavor');
            $flavtext = "";
            if (!empty($tempflavor)) {

                $key = array_search($tempflavor->getObjectId(), array_column($allflavor, 'id'));

                if ($key != false) {
                    $flavtext = $allflavor[$key]['flavor'];
                }
            }
            //get image
            $tempimage=$tempdata->get('image');
            $data[]=array(
                'objectId'=>$tempdata->getObjectId(),
                'tags'=>$tempdata->get('tags'),
                'ingredients'=>$tempdata->get('Ingredients'),
                'drinkName'=>$tempdata->get('drinkName'),
                'glass'=>$tempdata->get('glass'),
                'category'=>$cattext,
                'flavor'=>$flavtext,
                'image'=>(!empty($tempimage))?$tempimage->getURL() : ""
            );
        }
        return $data;
    }
    public function SearchProductsByName($search){
        $allcat=$this->GetProducsCategory();
        $allflavor=$this->GetProductsFlavor();
        $query = new ParseQuery("Drinks");
        $query->startsWith("drinkName", $search);
        $query->equalTo("disable", false);
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){
            $tempcat= $tempdata->get('categoryId');
            $key = array_search($tempcat->getObjectId(), array_column($allcat, 'id'));
            $cattext="";
            if($key != false){
                $cattext= $allcat[$key]['category_name'];
            }
            // get flavor
            $tempflavor = $tempdata->get('flavor');
            $flavtext = "";
            if (!empty($tempflavor)) {

                $key = array_search($tempflavor->getObjectId(), array_column($allflavor, 'id'));

                if ($key != false) {
                    $flavtext = $allflavor[$key]['flavor'];
                }
            }
            //get image
            $tempimage=$tempdata->get('image');
            $data[]=array(
                'objectId'=>$tempdata->getObjectId(),
                'tags'=>$tempdata->get('tags'),
                'ingredients'=>$tempdata->get('Ingredients'),
                'drinkName'=>$tempdata->get('drinkName'),
                'glass'=>$tempdata->get('glass'),
                'category'=>$cattext,
                'flavor'=>$flavtext,
                'image'=>(!empty($tempimage))?$tempimage->getURL() : ""
            );
        }
        return $data;
    }
    public function GetUserProductsLimit($limit,$skip){
        $allcat=$this->GetProducsCategory();
        $allflavor=$this->GetProductsFlavor();
        $query = new ParseQuery("Drinks");
        $query->equalTo("owner", Parse\ParseUser::getCurrentUser());
        $query->equalTo("disable", false);
        $query->limit($limit);
        $query->skip($skip);
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){
            $tempcat= $tempdata->get('categoryId');
            $key = array_search($tempcat->getObjectId(), array_column($allcat, 'id'));
            $cattext="";
            if($key != false){
                $cattext= $allcat[$key]['category_name'];
            }
            // get flavor
            $tempflavor = $tempdata->get('flavor');
            $flavtext = "";
            if (!empty($tempflavor)) {

                $key = array_search($tempflavor->getObjectId(), array_column($allflavor, 'id'));

                if ($key != false) {
                    $flavtext = $allflavor[$key]['flavor'];
                }
            }

            $tempimage=$tempdata->get('image');
            $data[]=array(
                'objectId'=>$tempdata->getObjectId(),
                'tags'=>$tempdata->get('tags'),
                'ingredients'=>$tempdata->get('Ingredients'),
                'drinkName'=>$tempdata->get('drinkName'),
                'glass'=>$tempdata->get('glass'),
                'category'=>$cattext,
                'flavor'=>$flavtext,
                'image'=>(!empty($tempimage))?$tempimage->getURL() : ""
            );
        }
        return $data;

    }
    public function GetUserProductsLimitByOwner($limit,$skip,$owner){
        $allcat=$this->GetProducsCategory();
        $allflavor=$this->GetProductsFlavor();
        $query = new ParseQuery("Drinks");
        $query->equalTo("owner",  ['__type' => "Pointer", 'className'=> "_User", 'objectId' => $owner]);
        $query->equalTo("disable", false);
        $query->limit($limit);
        $query->skip($skip);
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){
            $tempcat= $tempdata->get('categoryId');
            $key = array_search($tempcat->getObjectId(), array_column($allcat, 'id'));
            $cattext="";
            if($key != false){
                $cattext= $allcat[$key]['category_name'];
            }
            // get flavor
            $tempflavor = $tempdata->get('flavor');
            $flavtext = "";
            if (!empty($tempflavor)) {

                $key = array_search($tempflavor->getObjectId(), array_column($allflavor, 'id'));

                if ($key != false) {
                    $flavtext = $allflavor[$key]['flavor'];
                }
            }

            $tempimage=$tempdata->get('image');
            $data[]=array(
                'objectId'=>$tempdata->getObjectId(),
                'tags'=>$tempdata->get('tags'),
                'ingredients'=>$tempdata->get('Ingredients'),
                'drinkName'=>$tempdata->get('drinkName'),
                'glass'=>$tempdata->get('glass'),
                'category'=>$cattext,
                'flavor'=>$flavtext,
                'image'=>(!empty($tempimage))?$tempimage->getURL() : ""
            );
        }
        return $data;

    }
    public function GetUserProductsLimitByRestaurant($limit,$skip,$owner){
        $allcat=$this->GetProducsCategory();
        $allflavor=$this->GetProductsFlavor();
        $query = new ParseQuery("Drinks");
        $query->equalTo("restaurant",  ['__type' => "Pointer", 'className'=> "Restaurant", 'objectId' => $owner]);
        $query->equalTo("disable", false);
        $query->limit($limit);
        $query->skip($skip);
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){
            $tempcat= $tempdata->get('categoryId');
            $key = array_search($tempcat->getObjectId(), array_column($allcat, 'id'));
            $cattext="";
            if($key != false){
                $cattext= $allcat[$key]['category_name'];
            }
            // get flavor
            $tempflavor = $tempdata->get('flavor');
            $flavtext = "";
            if (!empty($tempflavor)) {

                $key = array_search($tempflavor->getObjectId(), array_column($allflavor, 'id'));

                if ($key != false) {
                    $flavtext = $allflavor[$key]['flavor'];
                }
            }

            $tempimage=$tempdata->get('image');
            $data[]=array(
                'objectId'=>$tempdata->getObjectId(),
                'tags'=>$tempdata->get('tags'),
                'ingredients'=>$tempdata->get('Ingredients'),
                'drinkName'=>$tempdata->get('drinkName'),
                'glass'=>$tempdata->get('glass'),
                'category'=>$cattext,
                'flavor'=>$flavtext,
                'image'=>(!empty($tempimage))?$tempimage->getURL() : ""
            );
        }
        return $data;

    }
    public function GetAllProductsLimit($limit,$skip,$sort){
        $allcat=$this->GetProducsCategory();
        $allflavor=$this->GetProductsFlavor();
        $query = new ParseQuery("Drinks");
        $query->equalTo("disable", false);
        $query->limit($limit);
        $query->skip($skip);

        if(isset($sort)){
            if($sort =='az'){
                $query->ascending("drinkName");
            }elseif($sort =='za'){
                $query->descending("drinkName");
            }
        }
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){
            //get category
            $tempcat= $tempdata->get('categoryId');
            $key = array_search($tempcat->getObjectId(), array_column($allcat, 'id'));
            $cattext="";
            if(isset($allcat[$key]['category_name'])){
                $cattext= $allcat[$key]['category_name'];
            }
            // get flavor
            $tempflavor = $tempdata->get('flavor');
            $flavtext = "";
            if (!empty($tempflavor)) {
                $key = array_search($tempflavor->getObjectId(), array_column($allflavor, 'id'));
                if (isset($allflavor[$key]['flavor'])) {
                    $flavtext = $allflavor[$key]['flavor'];
                }
            }
            //get image
            $tempimage=$tempdata->get('image');
            $data[]=array(
                'objectId'=>$tempdata->getObjectId(),
                'tags'=>$tempdata->get('tags'),
                'ingredients'=>$tempdata->get('Ingredients'),
                'drinkName'=>$tempdata->get('drinkName'),
                'glass'=>$tempdata->get('glass'),
                'category'=>$cattext,
                'flavor'=>$flavtext,
                'image'=>(!empty($tempimage))?$tempimage->getURL() : ""
            );
        }
        return $data;

    }
    public function GetProductsByCategory($category){
        $allcat=$this->GetProducsCategory();
        $allflavor=$this->GetProductsFlavor();
        $query = new ParseQuery("Drinks");
        $query->equalTo("categoryId", ['__type' => "Pointer", 'className'=> "Category", 'objectId' => $category]);
        $query->equalTo("disable", false);
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){
            //get category
            $tempcat= $tempdata->get('categoryId');
            $key = array_search($tempcat->getObjectId(), array_column($allcat, 'id'));
            $cattext="";
            if(isset($allcat[$key]['category_name'])){
                $cattext= $allcat[$key]['category_name'];
            }
            // get flavor
            $tempflavor = $tempdata->get('flavor');
            $flavtext = "";
            if (!empty($tempflavor)) {
                $key = array_search($tempflavor->getObjectId(), array_column($allflavor, 'id'));
                if (isset($allflavor[$key]['flavor'])) {
                    $flavtext = $allflavor[$key]['flavor'];
                }
            }
            //get image
            $tempimage=$tempdata->get('image');
            $data[]=array(
                'objectId'=>$tempdata->getObjectId(),
                'tags'=>$tempdata->get('tags'),
                'ingredients'=>$tempdata->get('Ingredients'),
                'drinkName'=>$tempdata->get('drinkName'),
                'glass'=>$tempdata->get('glass'),
                'category'=>$cattext,
                'flavor'=>$flavtext,
                'image'=>(!empty($tempimage))?$tempimage->getURL() : ""
            );
        }
        return $data;

    }
    public function GetUserProductsByCategory($category){
        $allcat=$this->GetProducsCategory();
        $allflavor=$this->GetProductsFlavor();
        $query = new ParseQuery("Drinks");
        $query->equalTo("owner", Parse\ParseUser::getCurrentUser());
        $query->equalTo("categoryId", ['__type' => "Pointer", 'className'=> "Category", 'objectId' => $category]);
        $query->equalTo("disable", false);
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){
            //get category
            $tempcat= $tempdata->get('categoryId');
            $key = array_search($tempcat->getObjectId(), array_column($allcat, 'id'));
            $cattext="";
            if(isset($allcat[$key]['category_name'])){
                $cattext= $allcat[$key]['category_name'];
            }
            // get flavor
            $tempflavor = $tempdata->get('flavor');
            $flavtext = "";
            if (!empty($tempflavor)) {
                $key = array_search($tempflavor->getObjectId(), array_column($allflavor, 'id'));
                if (isset($allflavor[$key]['flavor'])) {
                    $flavtext = $allflavor[$key]['flavor'];
                }
            }
            //get image
            $tempimage=$tempdata->get('image');
            $data[]=array(
                'objectId'=>$tempdata->getObjectId(),
                'tags'=>$tempdata->get('tags'),
                'ingredients'=>$tempdata->get('Ingredients'),
                'drinkName'=>$tempdata->get('drinkName'),
                'glass'=>$tempdata->get('glass'),
                'category'=>$cattext,
                'flavor'=>$flavtext,
                'image'=>(!empty($tempimage))?$tempimage->getURL() : ""
            );
        }
        return $data;

    }
    public function GetProductsByFlavor($flavor){
        $allcat=$this->GetProducsCategory();
        $allflavor=$this->GetProductsFlavor();
        $query = new ParseQuery("Drinks");
        $query->equalTo("flavor", ['__type' => "Pointer", 'className'=> "Flavor", 'objectId' => $flavor]);
        $query->equalTo("disable", false);
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){
            //get category
            $tempcat= $tempdata->get('categoryId');
            $key = array_search($tempcat->getObjectId(), array_column($allcat, 'id'));
            $cattext="";
            if(isset($allcat[$key]['category_name'])){
                $cattext= $allcat[$key]['category_name'];
            }
            // get flavor
            $tempflavor = $tempdata->get('flavor');
            $flavtext = "";
            if (!empty($tempflavor)) {
                $key = array_search($tempflavor->getObjectId(), array_column($allflavor, 'id'));
                if (isset($allflavor[$key]['flavor'])) {
                    $flavtext = $allflavor[$key]['flavor'];
                }
            }
            //get image
            $tempimage=$tempdata->get('image');
            $data[]=array(
                'objectId'=>$tempdata->getObjectId(),
                'tags'=>$tempdata->get('tags'),
                'ingredients'=>$tempdata->get('Ingredients'),
                'drinkName'=>$tempdata->get('drinkName'),
                'glass'=>$tempdata->get('glass'),
                'category'=>$cattext,
                'flavor'=>$flavtext,
                'image'=>(!empty($tempimage))?$tempimage->getURL() : ""
            );
        }
        return $data;

    }
    public function GetUserProductsByFlavor($flavor){
        $allcat=$this->GetProducsCategory();
        $allflavor=$this->GetProductsFlavor();
        $query = new ParseQuery("Drinks");
        $query->equalTo("owner", Parse\ParseUser::getCurrentUser());
        $query->equalTo("flavor", ['__type' => "Pointer", 'className'=> "Flavor", 'objectId' => $flavor]);
        $query->equalTo("disable", false);
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){
            //get category
            $tempcat= $tempdata->get('categoryId');
            $key = array_search($tempcat->getObjectId(), array_column($allcat, 'id'));
            $cattext="";
            if(isset($allcat[$key]['category_name'])){
                $cattext= $allcat[$key]['category_name'];
            }
            // get flavor
            $tempflavor = $tempdata->get('flavor');
            $flavtext = "";
            if (!empty($tempflavor)) {
                $key = array_search($tempflavor->getObjectId(), array_column($allflavor, 'id'));
                if (isset($allflavor[$key]['flavor'])) {
                    $flavtext = $allflavor[$key]['flavor'];
                }
            }
            //get image
            $tempimage=$tempdata->get('image');
            $data[]=array(
                'objectId'=>$tempdata->getObjectId(),
                'tags'=>$tempdata->get('tags'),
                'ingredients'=>$tempdata->get('Ingredients'),
                'drinkName'=>$tempdata->get('drinkName'),
                'glass'=>$tempdata->get('glass'),
                'category'=>$cattext,
                'flavor'=>$flavtext,
                'image'=>(!empty($tempimage))?$tempimage->getURL() : ""
            );
        }
        return $data;

    }
    public function GetProductsMatcheskey($search){
        $allcat=$this->GetProducsCategory();
        $allflavor=$this->GetProductsFlavor();
        $query = new ParseQuery("Drinks");
        $query->startsWith("Ingredients",$search);
        $query->equalTo("disable", false);
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){
            //get category
            $tempcat= $tempdata->get('categoryId');
            $key = array_search($tempcat->getObjectId(), array_column($allcat, 'id'));
            $cattext="";
            if(isset($allcat[$key]['category_name'])){
                $cattext= $allcat[$key]['category_name'];
            }
            // get flavor
            $tempflavor = $tempdata->get('flavor');
            $flavtext = "";
            if (!empty($tempflavor)) {
                $key = array_search($tempflavor->getObjectId(), array_column($allflavor, 'id'));
                if (isset($allflavor[$key]['flavor'])) {
                    $flavtext = $allflavor[$key]['flavor'];
                }
            }
            //get image
            $tempimage=$tempdata->get('image');
            $data[]=array(
                'objectId'=>$tempdata->getObjectId(),
                'tags'=>$tempdata->get('tags'),
                'ingredients'=>$tempdata->get('Ingredients'),
                'drinkName'=>$tempdata->get('drinkName'),
                'glass'=>$tempdata->get('glass'),
                'category'=>$cattext,
                'flavor'=>$flavtext,
                'image'=>(!empty($tempimage))?$tempimage->getURL() : ""
            );
        }
        return $data;

    }
    public function GetUserProductsMatcheskey($search){
        $allcat=$this->GetProducsCategory();
        $allflavor=$this->GetProductsFlavor();
        $query = new ParseQuery("Drinks");
        $query->equalTo("owner", Parse\ParseUser::getCurrentUser());
        $query->startsWith("Ingredients",$search);
        $query->equalTo("disable", false);
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){
            //get category
            $tempcat= $tempdata->get('categoryId');
            $key = array_search($tempcat->getObjectId(), array_column($allcat, 'id'));
            $cattext="";
            if(isset($allcat[$key]['category_name'])){
                $cattext= $allcat[$key]['category_name'];
            }
            // get flavor
            $tempflavor = $tempdata->get('flavor');
            $flavtext = "";
            if (!empty($tempflavor)) {
                $key = array_search($tempflavor->getObjectId(), array_column($allflavor, 'id'));
                if (isset($allflavor[$key]['flavor'])) {
                    $flavtext = $allflavor[$key]['flavor'];
                }
            }
            //get image
            $tempimage=$tempdata->get('image');
            $data[]=array(
                'objectId'=>$tempdata->getObjectId(),
                'tags'=>$tempdata->get('tags'),
                'ingredients'=>$tempdata->get('Ingredients'),
                'drinkName'=>$tempdata->get('drinkName'),
                'glass'=>$tempdata->get('glass'),
                'category'=>$cattext,
                'flavor'=>$flavtext,
                'image'=>(!empty($tempimage))?$tempimage->getURL() : ""
            );
        }
        return $data;

    }
    public function GetUserProductsCount()
    {
        $query = new ParseQuery("Drinks");
        $query->equalTo("owner", Parse\ParseUser::getCurrentUser());
        $query->equalTo("disable", false);
        $results = $query->count();
        return $results;
    }
    public function GetAllProductsCount()
    {
        $query = new ParseQuery("Drinks");
        $query->equalTo("disable", false);
        $results = $query->count();
        return $results;
    }


    //Flavor class---------------------------------------------------------------------------------


    public function GetProductsFlavor(){
        $query = new ParseQuery("Flavor");
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){
            $data[]=array(
                'id'=>$tempdata->getObjectId(),
                'flavor'=>$tempdata->get('flavor')
            );
        }
        return $data;
    }

    public function GetProductsFlavorById($id){
        $query = new ParseQuery("Flavor");
        $query->equalTo("objectId", $id);
        $results = $query->find();
        if(count($results)>0){
            return $results[0];
        }else{
            return null;
        }
    }
    public function GetProductsFlavorByName($search){
        $query = new ParseQuery("Flavor");
        $query->startsWith("flavor", $search);
        $results = $query->find();
        if(count($results)>0){
            return $results[0]->getObjectId();
        }else{
            return null;
        }
    }

    //glass class---------------------------------------------------------------------------------



    //restaurant class---------------------------------------------------------------------------------


    public  function AddRestaurant($data,$geo,$file){
        $set_logo= null;
        $set_backimg= null;
        if(isset($file['tmp_name'][0])){
            if(!empty($file['tmp_name'][0])) {
                $set_logo = ParseFile::createFromFile($file['tmp_name'][0], $file['name'][0]);
                $set_logo->save();
            }
        }
        if(isset($file['tmp_name'][1])){
            if(!empty($file['tmp_name'][1])) {
            $set_backimg = ParseFile::createFromFile($file['tmp_name'][1] ,$file['name'][1]);
            $set_backimg->save();
            }
        }
        $userdata = new ParseObject("Restaurant");
        $userdata->set("owner",  ParseUser::getCurrentUser());
        $userdata->set("barName",  $data['barName']);
        $userdata->set("street",  $data['street']);
        $userdata->set("state", $data['state']);
        $userdata->set("city", $data['city']);
        $userdata->set("country", $data['country']);
        $userdata->set("zip", $data['zip']);
        $userdata->set("disable", false);
        if($data['lat'] != 0 && $data['lat'] != "" && $data['lon'] != 0 && $data['lat'] != ""){
            $point = new ParseGeoPoint((float)$data['lat'],(float)$data['lon']);
            $userdata->set("geolocation",$point);
        }else{
            $point = new ParseGeoPoint($geo->lat,$geo->lng);
            $userdata->set("geolocation",$point);
        }
        if(!empty($set_logo))$userdata->set("logo",$set_logo);
        if(!empty($set_backimg))$userdata->set("backimg",$set_backimg);
        try {
            $userdata->save();
            return true;
        } catch (ParseException $ex) {

            return false;
        }
    }
    public function UpdateRestaurant($data,$geo,$id,$file){
        $query = new ParseQuery("Restaurant");
        $query->equalTo("owner", ParseUser::getCurrentUser());
        $query->equalTo("disable", false);
        $query->equalTo("objectId", $id);
        $results = $query->find();
        $userdata= null;
        if(count($results)>0){
            $set_logo= null;
            $set_backimg= null;
            if(isset($file['tmp_name'][0])){
                if(!empty($file['tmp_name'][0])) {
                    $set_logo = ParseFile::createFromFile($file['tmp_name'][0], $file['name'][0]);
                    $set_logo->save();
                }
            }
            if(isset($file['tmp_name'][1])){
                if(!empty($file['tmp_name'][1])) {
                    $set_backimg = ParseFile::createFromFile($file['tmp_name'][1], $file['name'][1]);
                    $set_backimg->save();
                }
            }
            $userdata =$results[0];
            $userdata->set("barName",  $data['barName']);
            $userdata->set("street",  $data['street']);
            $userdata->set("state", $data['state']);
            $userdata->set("city", $data['city']);
            $userdata->set("country", $data['country']);
            $userdata->set("zip", $data['zip']);
            $userdata->set("disable", false);
            if($data['lat'] != 0 && $data['lat'] != "" && $data['lon'] != 0 && $data['lat'] != ""){
                $point = new ParseGeoPoint((float)$data['lat'],(float)$data['lon']);
                $userdata->set("geolocation",$point);
            }else{
                $point = new ParseGeoPoint($geo->lat,$geo->lng);
                $userdata->set("geolocation",$point);
            }
            if(!empty($set_logo))$userdata->set("logo",$set_logo);
            if(!empty($set_backimg))$userdata->set("backimg",$set_backimg);
            try {
                $userdata->save();
                return true;
            } catch (ParseException $ex) {
                echo $ex;
                return false;
            }
        }else{
            return false;
        }


    }
    public function UpdateRestaurantByObjectId($data,$geo,$id,$file){
        $query = new ParseQuery("Restaurant");
        $query->equalTo("disable", false);
        $query->equalTo("objectId", $id);
        $results = $query->find();
        $userdata= null;
        if(count($results)>0){
            $set_logo= null;
            $set_backimg= null;
            if(isset($file['tmp_name'][0])){
                if(!empty($file['tmp_name'][0])) {
                    $set_logo = ParseFile::createFromFile($file['tmp_name'][0], $file['name'][0]);
                    $set_logo->save();
                }
            }
            if(isset($file['tmp_name'][1])){
                if(!empty($file['tmp_name'][1])) {
                    $set_backimg = ParseFile::createFromFile($file['tmp_name'][1], $file['name'][1]);
                    $set_backimg->save();
                }
            }
            $userdata =$results[0];
            $userdata->set("barName",  $data['barName']);
            $userdata->set("street",  $data['street']);
            $userdata->set("state", $data['state']);
            $userdata->set("city", $data['city']);
            $userdata->set("country", $data['country']);
            $userdata->set("disable", false);
            $userdata->set("zip", $data['zip']);
            if($data['lat'] != 0 && $data['lat'] != "" && $data['lon'] != 0 && $data['lat'] != ""){
                $point = new ParseGeoPoint((float)$data['lat'],(float)$data['lon']);
                $userdata->set("geolocation",$point);
            }else{
                $point = new ParseGeoPoint($geo->lat,$geo->lng);
                $userdata->set("geolocation",$point);
            }
            if(!empty($set_logo))$userdata->set("logo",$set_logo);
            if(!empty($set_backimg))$userdata->set("backimg",$set_backimg);
            try {
                $userdata->save();
                return true;
            } catch (ParseException $ex) {
                echo $ex;
                return false;
            }
        }else{
            return false;
        }


    }
    public function GetRestaurantByUser(){
        $query = new ParseQuery("Restaurant");
        $query->equalTo("owner", ParseUser::getCurrentUser());
        $query->equalTo("disable", false);
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){
            $tempgeo= $tempdata->get('geolocation');
            $tempphoto= $tempdata->get('logo');
            $data[]=array(
                'barName'=>$tempdata->get('barName'),
                'objectId'=>$tempdata->getObjectId(),
                'street'=>$tempdata->get('street'),
                'street'=>$tempdata->get('street'),
                'state'=>$tempdata->get('state'),
                'city'=>$tempdata->get('city'),
                'country'=>$tempdata->get('country'),
                'zip'=>$tempdata->get('zip'),
                'logo'=>(!empty($tempphoto))?$tempphoto->getURL(): "",
                'lat'=>(!empty($tempgeo))?$tempgeo->getLatitude(): "",
                'lon'=>(!empty($tempgeo))?$tempgeo->getLongitude(): "",
                'owner'=>$tempdata->get('owner'),
            );
        }
        return $data;
    }
    public function GetRestaurantCountByUser()
    {
        $query = new ParseQuery("Restaurant");
        $query->equalTo("owner", ParseUser::getCurrentUser());
        $query->equalTo("disable", false);
        $results = $query->count();
        $data=array();


    }
    public function GetRestaurantById($id){
        $query = new ParseQuery("Restaurant");
        $query->equalTo("owner", ParseUser::getCurrentUser());
        $query->equalTo("disable", false);
        $query->equalTo("objectId", $id);
        $results = $query->find();
        $data=array();
        if(count($results)>0){
            $tempdata =$results[0];
            $tempgeo= $tempdata->get('geolocation');
            $tempphoto= $tempdata->get('logo');
            $tempowner=$tempdata->get('owner');
            $data[]=array(
                'barName'=>$tempdata->get('barName'),
                'objectId'=>$tempdata->getObjectId(),
                'street'=>$tempdata->get('street'),
                'state'=>$tempdata->get('state'),
                'city'=>$tempdata->get('city'),
                'country'=>$tempdata->get('country'),
                'zip'=>$tempdata->get('zip'),
                'logo'=>(!empty($tempphoto))?$tempphoto->getURL(): "",
                'lat'=>(!empty($tempgeo))?$tempgeo->getLatitude(): "",
                'lon'=>(!empty($tempgeo))?$tempgeo->getLongitude(): "",
                'owner'=>$tempowner->getObjectId(),
            );
        }
        return $data;

    }
    public function GetRestaurantByObjectId($id){
        $query = new ParseQuery("Restaurant");
        $query->equalTo("disable", false);
        $query->equalTo("objectId", $id);
        $results = $query->find();
        $data=array();
        if(count($results)>0){
            $tempdata =$results[0];
            $tempgeo= $tempdata->get('geolocation');
            $tempphoto= $tempdata->get('logo');
            $tempowner=$tempdata->get('owner');
            $data[]=array(
                'barName'=>$tempdata->get('barName'),
                'objectId'=>$tempdata->getObjectId(),
                'street'=>$tempdata->get('street'),
                'state'=>$tempdata->get('state'),
                'city'=>$tempdata->get('city'),
                'country'=>$tempdata->get('country'),
                'zip'=>$tempdata->get('zip'),
                'logo'=>(!empty($tempphoto))?$tempphoto->getURL(): "",
                'lat'=>(!empty($tempgeo))?$tempgeo->getLatitude(): "",
                'lon'=>(!empty($tempgeo))?$tempgeo->getLongitude(): "",
                'owner'=>$tempowner->getObjectId(),
            );
        }
        return $data;

    }
    public function GetAllRestaurant($limit,$skip){
        $query = new ParseQuery("Restaurant");
        $query->equalTo("disable", false);
        $query->limit($limit);
        $query->skip($skip);
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){

            $tempgeo= $tempdata->get('geolocation');
            $tempphoto= $tempdata->get('logo');
            $tempowner=$tempdata->get('owner');
            $data[]=array(
                'barName'=>$tempdata->get('barName'),
                'objectId'=>$tempdata->getObjectId(),
                'street'=>$tempdata->get('street'),
                'state'=>$tempdata->get('state'),
                'city'=>$tempdata->get('city'),
                'country'=>$tempdata->get('country'),
                'created'=>$tempdata->getCreatedAt(),
                'zip'=>$tempdata->get('zip'),
                'logo'=>(!empty($tempphoto))?$tempphoto->getURL(): "",
                'lat'=>(!empty($tempgeo))?$tempgeo->getLatitude(): "",
                'lon'=>(!empty($tempgeo))?$tempgeo->getLongitude(): "",
                'owner'=>$tempowner->getObjectId(),
            );
        }
        return $data;

    }
    public function GetAllRestaurantAsc($limit,$skip){
        $query = new ParseQuery("Restaurant");
        $query->ascending("barName");
        $query->equalTo("disable", false);
        $query->limit($limit);
        $query->skip($skip);
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){

            $tempgeo= $tempdata->get('geolocation');
            $tempphoto= $tempdata->get('logo');
            $tempowner=$tempdata->get('owner');
            $data[]=array(
                'barName'=>$tempdata->get('barName'),
                'objectId'=>$tempdata->getObjectId(),
                'street'=>$tempdata->get('street'),
                'state'=>$tempdata->get('state'),
                'city'=>$tempdata->get('city'),
                'country'=>$tempdata->get('country'),
                'created'=>$tempdata->getCreatedAt(),
                'zip'=>$tempdata->get('zip'),
                'logo'=>(!empty($tempphoto))?$tempphoto->getURL(): "",
                'lat'=>(!empty($tempgeo))?$tempgeo->getLatitude(): "",
                'lon'=>(!empty($tempgeo))?$tempgeo->getLongitude(): "",
                'owner'=>$tempowner->getObjectId(),
            );
        }
        return $data;

    }
    public function GetAllRestaurantDes($limit,$skip){
        $query = new ParseQuery("Restaurant");
        $query->equalTo("disable", false);
        $query->descending("barName");
        $query->limit($limit);
        $query->skip($skip);
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){

            $tempgeo= $tempdata->get('geolocation');
            $tempphoto= $tempdata->get('logo');
            $tempowner=$tempdata->get('owner');
            $data[]=array(
                'barName'=>$tempdata->get('barName'),
                'objectId'=>$tempdata->getObjectId(),
                'street'=>$tempdata->get('street'),
                'state'=>$tempdata->get('state'),
                'city'=>$tempdata->get('city'),
                'country'=>$tempdata->get('country'),
                'created'=>$tempdata->getCreatedAt(),
                'zip'=>$tempdata->get('zip'),
                'logo'=>(!empty($tempphoto))?$tempphoto->getURL(): "",
                'lat'=>(!empty($tempgeo))?$tempgeo->getLatitude(): "",
                'lon'=>(!empty($tempgeo))?$tempgeo->getLongitude(): "",
                'owner'=>$tempowner->getObjectId(),
            );
        }
        return $data;

    }
    public function SearchAllRestaurant($search){
        $query = new ParseQuery("Restaurant");
        $query->startsWith("barName",$search);
        $query->equalTo("disable", false);
        $results = $query->find();
        $data=array();
        foreach($results as $tempdata){

            $tempgeo= $tempdata->get('geolocation');
            $tempphoto= $tempdata->get('logo');
            $tempowner=$tempdata->get('owner');
            $data[]=array(
                'barName'=>$tempdata->get('barName'),
                'objectId'=>$tempdata->getObjectId(),
                'street'=>$tempdata->get('street'),
                'state'=>$tempdata->get('state'),
                'city'=>$tempdata->get('city'),
                'country'=>$tempdata->get('country'),
                'created'=>$tempdata->getCreatedAt(),
                'zip'=>$tempdata->get('zip'),
                'logo'=>(!empty($tempphoto))?$tempphoto->getURL(): "",
                'lat'=>(!empty($tempgeo))?$tempgeo->getLatitude(): "",
                'lon'=>(!empty($tempgeo))?$tempgeo->getLongitude(): "",
                'owner'=>$tempowner->getObjectId(),
            );
        }
        return $data;

    }
    public function GetRestaurantCount()
    {
        $query = new ParseQuery("Restaurant");
        $query->equalTo("disable", false);
        $results = $query->count();
        return $results;
    }
    public function RemoveRestaurantByObjectId($id){
        $query = new ParseQuery("Restaurant");
        $query->equalTo("objectId", $id);
        $result = $query->find();
        if(count($result)>0){
            $dataDrinks =$result[0];
            $dataDrinks->set("disable", true);
            try {
                $dataDrinks->save();
                return true;
            } catch (ParseException $ex) {
                // Execute any logic that should take place if the save fails.
                // error is a ParseException object with an error code and message.
                return false;
            }
        }

    }
    public function RemoveUserRestaurantByObjectId($id){
        $query = new ParseQuery("Restaurant");
        $query->equalTo("objectId", $id);
        $query->equalTo("owner", Parse\ParseUser::getCurrentUser());
        $result = $query->find();
        if(count($result)>0){
            $dataDrinks =$result[0];
            $dataDrinks->set("disable", true);
            try {
                $dataDrinks->save();
                return true;
            } catch (ParseException $ex) {
                // Execute any logic that should take place if the save fails.
                // error is a ParseException object with an error code and message.
                return false;
            }
        }

    }
}
