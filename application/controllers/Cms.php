<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cms extends CI_Controller {
    public $hdata= array(
        'errornotice'=>'',
        'successnotice'=>'',
        'username'=>'',
        'usersubscription'=>'',
        'hproduct'=>'',
        'hprofile'=>'',
        'hpupgrade'=>'',
        'haccount'=>'',
        'hbars'=>'',
        'hdashboard'=>'',
        'userdata'=>'',
        'pagination'=>''
    );
    private $parse;
    private $google;
    public function __construct()
    {
        parent::__construct();
        require_once('lib/parse.php');
        require_once('lib/google.php');
        $this->parse = new ParseAPI();
        $this->google = new GoogleAPI();
        $this->session_user = $this->parse->getCurrentUser();
        if($this->session_user == null){
            Redirect(base_url()."Registration", false);
        };


        // set user email
        $this->hdata['username']=$this->session_user->getCurrentUser()->getEmail();

        // set user type 0 guest 1 for adminsitrator
        $this->hdata['userType']=$this->parse->CheckAccountType();

        //load database
        $this->load->database();

        //get user subscription
        $this->load->model('Subscriptiondb');
        $usersub = $this->Subscriptiondb->GetSubscription($this->hdata['username']);
        $this->hdata['usersubscription'] = $usersub;

        //set subscription notification
        $this->load->model('Userdb');
        $userdata=$this->Userdb->GetDatabyEmail($this->hdata['username']);
        $this->hdata['userdata'] = $userdata;

        ///get days of user activation
        $this->load->model('Userdb');
        $daysreg=$this->Userdb->GetDaysfromActivation($this->hdata['username']);
        $this->hdata['free_days'] =$daysreg[0]['DiffDate'];

        //set return url php

        if (!empty($_POST)) {
            if(isset($_SESSION['returnUrl'])){
                $this->hdata['returnUrl'] =$_SESSION['returnUrl'];
            }
        }else{
            $_SESSION['returnUrl'] = (isset($_SERVER['HTTP_REFERER']))? $_SERVER['HTTP_REFERER'] : "";
            $this->hdata['returnUrl'] = $_SESSION['returnUrl'];
        }


    }

    public function index()
    {
        $this->FreeTrialValidator();
        if($this->parse->CheckUserInfo()){
            Redirect(base_url()."cms/info", false);
        }

        $skip =0;
        if($this->hdata['userType'] ==1){

        }else{
            $this->hdata['pagination']=$this->Pagination(base_url().'cms/products/',$this->parse->GetUserProductsCount(),20);
            $this->hdata['result']= $this->parse->GetUserProductsLimit(20,$skip);
        }


        if($this->hdata['userType'] == 1){
            $day = date('w');
            $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
            $week_end = date('Y-m-d', strtotime('+'.(6-$day).' days'));
            $this->hdata['hdashboard']='active';
            $this->load->model('Userdb');
            $this->load->model('Subscriptiondb');
            $this->hdata['result']= $this->Userdb->GetWeekSignupData();
            $this->hdata['totalsignup']= $this->Userdb->GetSignupCount($week_start,$week_end);
            $this->hdata['Trialaccounts']= $this->Subscriptiondb->CurrentTrialAccounts($week_start,$week_end);
            $this->hdata['TotalExpireToday']= $this->Subscriptiondb->GetExpireToday();
            $this->load->view('cms_header',$this->hdata);
            $this->load->view('cms_dashboard');
        }else{
            $this->hdata['hproduct']='active';
            $this->load->view('cms_header',$this->hdata);
            $this->load->view('cms_products',$this->hdata);
        }

        $this->load->view('cms_footer');

    }
    public function bars()
    {

        $this->load->model('Subscriptiondb');
        $this->FreeTrialValidator();
        if($this->parse->CheckUserInfo()){
            Redirect(base_url()."cms/info", false);
        }
        $this->hdata['hbars']='active';
        $this->load->view('cms_header',$this->hdata);

        // get query string
        $set_func=$this->uri->segment(3);
        $set_id=$this->uri->segment(4);

        // set query string
        $this->hdata['action'] = $set_func;
        $this->hdata['reference'] = $set_id;

        // file upload
        $config['upload_path'] = './upload/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '250';
        $this->load->library('upload', $config);

        $this->form_validation->set_rules('user[street]', 'Address', 'trim|required');
        $this->form_validation->set_rules('user[state]', 'State', 'trim|required');
        $this->form_validation->set_rules('user[barName]', 'Bar Name', 'trim|required');
        $this->form_validation->set_rules('user[city]', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('user[country]', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('user[zip]', 'Last Name', 'trim|required');
        if($set_func =="edit") {
            ///file validation

            if ($this->form_validation->run() == true) {
                $tempdata = $this->input->post("user");
                $temgeo = $this->google->GetGeoapi($tempdata['city'],$tempdata['state'],$tempdata['country']);
                if (!empty($tempdata)) {
                    if($temgeo != null){
                        $tempfile = null;
                        if (isset($_FILES['photo'])) {
                            $tempfile = $_FILES['photo'];
                        }
                        $result = $this->parse->UpdateRestaurantByObjectId($tempdata,$temgeo,$set_id,$tempfile);
                        if ($result) {
                            $this->hdata['successnotice'] = 'Successfully updated restaurant information.';
                        } else {
                            $this->hdata['errornotice'] = 'Unable to save data.';
                        }
                    }else{
                        $this->hdata['errornotice'] = 'Invalid Address.';
                    }
                }
            }

            $this->hdata['data']= $this->parse->GetRestaurantByObjectId($set_id);
            $this->load->view('cms_bar_mod', $this->hdata);
        }elseif($set_func =="search") {
            $tempdata = $this->input->post("search");
            $this->hdata['result']= $this->parse->SearchAllRestaurant($tempdata['text']);
            $this->load->view('cms_restaurant', $this->hdata);
        }elseif($set_func =="Ascending") {
            $skip =0;
            $this->hdata['result']= $this->parse->GetAllRestaurantAsc(20,$skip);
            $this->hdata['pagination']=$this->Pagination(base_url().'cms/restaurant/Ascending/',$this->parse->GetRestaurantCount(),20);
            $this->load->view('cms_restaurant',$this->hdata);
        }elseif($set_func =="Descending") {
            $skip =0;
            $this->hdata['result']= $this->parse->GetAllRestaurantDes(20,$skip);
            $this->hdata['pagination']=$this->Pagination(base_url().'cms/restaurant/Descending/',$this->parse->GetRestaurantCount(),20);
            $this->load->view('cms_restaurant',$this->hdata);
        }elseif($set_func =="delete") {
            $skip =0;
            $this->parse->RemoveRestaurantByObjectId($set_id);
            $this->hdata['result']= $this->parse->GetAllRestaurantDes(20,$skip);
            $this->hdata['pagination']=$this->Pagination(base_url().'cms/restaurant/Descending/',$this->parse->GetRestaurantCount(),20);
            $this->load->view('cms_restaurant',$this->hdata);
        }
        else{
            $skip =0;
            $this->hdata['result']= $this->parse->GetAllRestaurant(20,$skip);
            $this->hdata['pagination']=$this->Pagination(base_url().'cms/restaurant/',$this->parse->GetRestaurantCount(),20);
            $this->load->view('cms_restaurant',$this->hdata);
        }
        $this->load->view('cms_footer');
    }
    public function Dashboard()
    {
        $day = date('w');
        $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
        $week_end = date('Y-m-d', strtotime('+'.(6-$day).' days'));
        $this->hdata['hdashboard']='active';
        $this->load->model('Userdb');
        $this->load->model('Subscriptiondb');
        $this->hdata['result']= $this->Userdb->GetWeekSignupData();
        $this->hdata['totalsignup']= $this->Userdb->GetSignupCount($week_start,$week_end);
        $this->hdata['Trialaccounts']= $this->Subscriptiondb->CurrentTrialAccounts($week_start,$week_end);
        $this->hdata['TotalExpireToday']= $this->Subscriptiondb->GetExpireToday();
        $this->load->view('cms_header',$this->hdata);
        $this->load->view('cms_dashboard',$this->hdata);
        $this->load->view('cms_footer');
    }
    public function searchproducts(){
        $tempdata = $this->input->post("search");
        $this->hdata['hproduct']='active';
        $this->load->view('cms_header',$this->hdata);
        if(!empty($tempdata)){
            if($tempdata['tab'] == 'products'){
                ///set header


                ///check selected search
                switch($tempdata['cat']){
                    case 'Drinks':
                        $tempresult=null;
                        if($this->hdata['userType'] ==1){

                            $this->hdata['result']= $this->parse->SearchProductsByName($tempdata['text']);
                        }else{
                            $this->hdata['result']= $this->parse->SearchUserProductsByName($tempdata['text']);
                        }
                        $this->load->view('cms_products',$this->hdata);

                        break;
                    case 'Category':
                        $datastring = $this->parse->SearchCategorybyName($tempdata['text']);
                        $this->hdata['result']= null;
                        if($datastring !=""){
                            if($this->hdata['userType'] ==1){
                                $this->hdata['result']= $this->parse->GetProductsByCategory($datastring);
                            }else{
                                $this->hdata['result']= $this->parse->GetUserProductsByCategory($datastring);
                            }
                        }
                        $this->load->view('cms_products',$this->hdata);

                        break;
                    case 'Flavor':
                        $datastring = $this->parse->GetProductsFlavorByName($tempdata['text']);
                        $this->hdata['result']= null;
                        if($datastring !=""){
                            if($this->hdata['userType'] ==1){
                                $this->hdata['result']= $this->parse->GetProductsByFlavor($datastring);
                            }else{
                                $this->hdata['result']= $this->parse->GetUserProductsByFlavor($datastring);
                            }
                        }
                        $this->load->view('cms_products',$this->hdata);


                        break;
                    case 'Ingredients':
                        if($this->hdata['userType'] ==1){
                            $this->hdata['result']= $this->parse->GetProductsMatcheskey($tempdata['text']);
                        }else{
                            $this->hdata['result']= $this->parse->GetUserProductsMatcheskey($tempdata['text']);
                        }
                        $this->load->view('cms_products',$this->hdata);
                        break;
                    default:
                        $skip =0;

                        if($this->hdata['userType'] ==1){
                            $this->hdata['pagination']=$this->Pagination(base_url().'cms/products/',$this->parse->GetAllProductsCount(),20);
                            $this->hdata['result']= $this->parse->GetAllProductsLimit(20,$skip);
                        }else{
                            $this->hdata['pagination']=$this->Pagination(base_url().'cms/products/',$this->parse->GetUserProductsCount(),20);
                            $this->hdata['result']= $this->parse->GetUserProductsLimit(20,$skip);
                        }
                        $this->load->view('cms_products',$this->hdata);

                        break;
                }


                $this->load->view('cms_footer');
            }

        }else{
            $skip =0;

            if($this->hdata['userType'] ==1){
                $this->hdata['pagination']=$this->Pagination(base_url().'cms/products/',$this->parse->GetAllProductsCount(),20);
                $this->hdata['result']= $this->parse->GetAllProductsLimit(20,$skip);
            }else{
                $this->hdata['pagination']=$this->Pagination(base_url().'cms/products/',$this->parse->GetUserProductsCount(),20);
                $this->hdata['result']= $this->parse->GetUserProductsLimit(20,$skip);
            }
            $this->load->view('cms_products',$this->hdata);
        }
        $this->load->view('cms_footer');

    }
    public function products()
    {
        $this->FreeTrialValidator();
        if($this->parse->CheckUserInfo()){
            Redirect(base_url()."cms/info", false);
        }
        $this->hdata['hproduct']='active';
        $this->load->view('cms_header',$this->hdata);

        // get query string
        $set_func=$this->uri->segment(3);
        $set_id=$this->uri->segment(4);

        // set query string
        $this->hdata['action'] = $set_func;
        $this->hdata['reference'] = $set_id;
        //load data


        // file upload
        $config['upload_path'] = './upload/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '250';
        $this->load->library('upload', $config);

        ///file validation


        if($set_func =="new"){
            $this->form_validation->set_rules('drink[drinkName]', 'Name', 'trim|required');
            $this->form_validation->set_rules('drink[tags]', 'Tags', 'trim|required');
            $this->form_validation->set_rules('drink[category]', 'Category', 'trim|required');
            $this->form_validation->set_rules('drink[ingredients]', 'Ingredients', 'trim|required');

            if( $this->form_validation->run() ==true){
                $tempdata = $this->input->post("drink");
                var_dump($tempdata);
                if(!empty($tempdata) && false){
                    $tempfile= null;
                    if(isset($_FILES['photo'])){
                        $tempfile =$_FILES['photo'];
                    }
                    $result=$this->parse->AddDrinks($tempdata,$tempfile);
                    if($result){
                        $this->hdata['successnotice']='Successfully added new Drink.';
                    }else{
                        $this->hdata['errornotice']='Unable to save data.';
                    }

                }
            }
            $this->hdata['category']=$this->parse->GetProducsCategory();
            $this->hdata['flavor']=$this->parse->GetProductsFlavor();
            $this->hdata['restolist']=$this->parse->GetRestaurantByUser();
            $this->load->view('cms_products_mod',$this->hdata);
        }elseif($set_func =="edit"){
            $this->form_validation->set_rules('drink[drinkName]', 'Name', 'trim|required');
            $this->form_validation->set_rules('drink[tags]', 'Tags', 'trim|required');
            $this->form_validation->set_rules('drink[category]', 'Category', 'trim|required');
            $this->form_validation->set_rules('drink[ingredients]', 'Ingredients', 'trim|required');

            if( $this->form_validation->run() ==true){
                $tempdata = $this->input->post("drink");
                if(!empty($tempdata)){
                    $tempfile= null;
                    if(isset($_FILES['photo'])){
                        $tempfile =$_FILES['photo'];
                    }
                    $tempid = $this->input->post("reference");
                    $result=$this->parse->UpdateDrinks($tempdata,$tempid,$tempfile);
                    if($result){
                        $this->hdata['successnotice']='Successfully updated Drink.';
                    }else{
                        $this->hdata['errornotice']='Unable to save data.';
                    }

                }
            }
            $this->hdata['flavor']=$this->parse->GetProductsFlavor();
            $this->hdata['category']=$this->parse->GetProducsCategory();
            $this->hdata['restolist']=$this->parse->GetRestaurantByUser();
            ///if($this->hdata['userType'] ==1){
               /// $this->hdata['data']=$this->parse->GetAllProductsById($set_id);
            //}else{
                $this->hdata['data']=$this->parse->GetUserProductsById($set_id);
            //}
            $this->load->view('cms_products_mod',$this->hdata);
        }elseif($set_func =="bulk"){
            $errornotice="";
            $tempdata = $this->input->post("drink");
            $tempimage= null;
            if(isset($_FILES['image'])){
                $tempimage =$_FILES['image'];
            }
            if($tempdata != null){
                for ($x=0; $x < count($tempdata); $x++) {
                    if(isset($tempdata[$x])){

                        $result=$this->parse->BulkAddDrinks($tempdata[$x],$tempimage,$x);
                        if($result == false){
                            if($errornotice == ""){
                                $errornotice ="Fail to add row number ";
                            }
                            $errornotice.=' '+($x+1);
                        }
                    }
                }
                if($errornotice == "")$this->hdata['successnotice']='Successfully added Drinks.';

                $this->hdata['errornotice']=$errornotice;
            }
            $this->hdata['flavor']=$this->parse->GetProductsFlavor();
            $this->hdata['category']=$this->parse->GetProducsCategory();
            $this->hdata['restolist']=$this->parse->GetRestaurantByUser();
            $this->load->view('cms_products_bulk',$this->hdata);
        }elseif($set_func =="delete"){

            $skip =0;
            if($set_func != ""){
                $skip =$set_func;
            }
            if($this->hdata['userType'] ==1){
                $this->parse->RemoveDrinks($set_id);
                $this->liheader['successnotice']='Successfully remove data.';
                $this->hdata['pagination']=$this->Pagination(base_url().'cms/products/',$this->parse->GetAllProductsCount(),20);
                $this->hdata['result']= $this->parse->GetAllProductsLimit(20,$skip);
            }else{
                $this->parse->RemoveDrinksByUser($set_id);
                $this->liheader['successnotice']='Successfully remove data.';
                $this->hdata['pagination']=$this->Pagination(base_url().'cms/products/',$this->parse->GetUserProductsCount(),20);
                $this->hdata['result']= $this->parse->GetUserProductsLimit(20,$skip);
            }
            $this->load->view('cms_products',$this->hdata);
        }elseif($set_func =="Ascending"){
            $skip =0;
            $this->hdata['sortaz']="az";
            if($this->uri->segment(5) != ""){
                $skip =$this->uri->segment(5);
            }
            if($this->hdata['userType'] ==1){
                $this->parse->RemoveDrinks($set_id);
                $this->liheader['successnotice']='Successfully remove data.';
                $this->hdata['pagination']=$this->Pagination(base_url().'cms/products/Ascending/page/',$this->parse->GetAllProductsCount(),20);
                $this->hdata['result']= $this->parse->GetAllProductsLimit(20,$skip,'az');
            }else{
                //$this->parse->RemoveDrinksByUser($set_id);
                //$this->liheader['successnotice']='Successfully remove data.';
                $this->hdata['pagination']=$this->Pagination(base_url().'cms/products/Ascending/page/',$this->parse->GetUserProductsCount(),20);
                $this->hdata['result']= $this->parse->GetUserProductsLimit(20,$skip,'az');
            }
            $this->load->view('cms_products',$this->hdata);
        }elseif($set_func =="view"){
            $this->hdata['sortaz']="za";
            $skip =0;
            $ownerid =$this->uri->segment(4);
            if($this->uri->segment(7) != ""){

                $skip =$this->uri->segment(7);
            }

                //$this->parse->RemoveDrinksByUser($set_id);
                //$this->liheader['successnotice']='Successfully remove data.';
                $this->hdata['pagination']=$this->Pagination(base_url().'cms/products/view/'.$ownerid.'/page/',$this->parse->GetUserProductsCount(),20);
                $this->hdata['result']= $this->parse->GetUserProductsLimitByOwner(20,$skip,$ownerid);

            $this->load->view('cms_products',$this->hdata);
        }elseif($set_func =="restaurant"){
            $this->hdata['sortaz']="za";
            $skip =0;
            $ownerid =$this->uri->segment(4);
            if($this->uri->segment(7) != ""){

                $skip =$this->uri->segment(7);
            }


                //$this->parse->RemoveDrinksByUser($set_id);
                //$this->liheader['successnotice']='Successfully remove data.';
                $this->hdata['pagination']=$this->Pagination(base_url().'cms/products/view/'.$ownerid.'/page/',$this->parse->GetUserProductsCount(),20);
                $this->hdata['result']= $this->parse->GetUserProductsLimitByOwner(20,$skip,$ownerid);

            $this->load->view('cms_products',$this->hdata);
        }elseif($set_func =="Ascending"){
            $skip =0;
            $this->hdata['sortaz']="az";
            if($this->uri->segment(5) != ""){
                $skip =$this->uri->segment(5);
            }
            if($this->hdata['userType'] ==1){
               $this->parse->RemoveDrinks($set_id);
               $this->liheader['successnotice']='Successfully remove data.';
               $this->hdata['pagination']=$this->Pagination(base_url().'cms/products/Ascending/page/',$this->parse->GetAllProductsCount(),20);
               $this->hdata['result']= $this->parse->GetAllProductsLimit(20,$skip,'az');
            }else{
            $this->parse->RemoveDrinksByUser($set_id);
            $this->liheader['successnotice']='Successfully remove data.';
            $this->hdata['pagination']=$this->Pagination(base_url().'cms/products/Ascending/page/',$this->parse->GetUserProductsCount(),20);
            $this->hdata['result']= $this->parse->GetUserProductsLimit(20,$skip,'az');
             }
            $this->load->view('cms_products',$this->hdata);
        }else{
            $skip =0;
            if($set_func != ""){
                $skip =$set_func;
            }

            if($this->hdata['userType'] ==1){
                $this->hdata['pagination']=$this->Pagination(base_url().'cms/products/',$this->parse->GetAllProductsCount(),20);
                $this->hdata['result']= $this->parse->GetAllProductsLimit(20,$skip);
            }else{
                $this->hdata['pagination']=$this->Pagination(base_url().'cms/products/',$this->parse->GetUserProductsCount(),20);
                $this->hdata['result']= $this->parse->GetUserProductsLimit(20,$skip);
            }
            $this->load->view('cms_products',$this->hdata);

        }
        $this->load->view('cms_footer');
    }
    public function Pagination($url,$total,$perpage){
        $config['base_url'] = $url;
        $config['total_rows'] = $total;
        $config['per_page'] = $perpage;
        $config['full_tag_open'] = '<nav><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = ' </li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['num_links'] = 10;
        $this->pagination->initialize($config);
        return  $this->pagination->create_links();
    }
    public function Profile()
    {
        $this->FreeTrialValidator();
        if($this->parse->CheckUserInfo()){
            Redirect(base_url()."cms/info", false);
        }

        //check user subscription for restaurant limit
        $this->hdata['restaurant_limit'] =  $this->parse->GetRestaurantCount();



        $this->hdata['hprofile']='active';
        $this->load->view('cms_header',$this->hdata);

        // get query string
        $set_func=$this->uri->segment(3);
        $set_id=$this->uri->segment(4);

        // set query string
        $this->hdata['action'] = $set_func;
        $this->hdata['reference'] = $set_id;

        // file upload
        $config['upload_path'] = './upload/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '250';
        $this->load->library('upload', $config);


        if($set_func =="edit") {
            ///file validation
            $this->form_validation->set_rules('user[address]', 'Address', 'trim|required');
            $this->form_validation->set_rules('user[state]', 'State', 'trim|required');
            $this->form_validation->set_rules('user[firstName]', 'First Name', 'trim|required');
            $this->form_validation->set_rules('user[lastName]', 'Last Name', 'trim|required');
            if ($this->form_validation->run() == true) {
                $tempdata = $this->input->post("user");
                $tempdata['username']=$this->hdata['username'];
                $temgeo = $this->google->GetGeoapi($tempdata['city'],$tempdata['state'],$tempdata['country']);
                if (!empty($tempdata)) {
                    if($temgeo != null){
                        $tempfile = null;
                        if (isset($_FILES['photo'])) {
                            $tempfile = $_FILES['photo'];
                        }
                        $result = $this->parse->SetUserInformation($tempdata, $tempfile,$temgeo);
                        if ($result) {
                            $this->hdata['successnotice'] = 'Successfully updated user information.';
                        } else {
                            $this->hdata['errornotice'] = 'Unable to save data.';
                        }
                    }else{
                        $this->hdata['errornotice'] = 'Invalid Address.';
                    }
                }
            }
            $this->hdata['data'] = $this->parse->UserInformation();
            $this->load->view('cms_profile_mod', $this->hdata);
        }elseif($set_func =="password"){
            $this->form_validation->set_rules('user[old]', 'Old Password', 'trim|required');
            $this->form_validation->set_rules('user[new]', 'New Password', 'trim|required');
            $this->form_validation->set_rules('user[ret]', 'Retype Password', 'trim|required');
            if ($this->form_validation->run() == true) {
                $tempdata = $this->input->post("user");

                if($tempdata['new']==$tempdata['ret']){
                    if (!empty($tempdata)) {
                        $result = $this->parse->UpdateUserPassword($this->hdata['username'],$tempdata);
                        if ($result) {
                            $this->hdata['successnotice'] = 'Successfully updated user password.';
                        } else {
                            $this->hdata['errornotice'] = 'Unable to save data.';
                        }
                    }
                }else{
                    $this->hdata['errornotice'] = 'Password do not match.';
                }
            }
            $this->load->view('cms_profile_pass', $this->hdata);
        }elseif($set_func =="delres"){
            $this->parse->RemoveUserRestaurantByObjectId($set_id);
            $this->hdata['restaurant']= $this->parse->GetRestaurantByUser();
            $this->hdata['data']= $this->parse->UserInformation();
            $this->load->view('cms_profile',$this->hdata);
        }else{

            $this->hdata['restaurant']= $this->parse->GetRestaurantByUser();
            $this->hdata['data']= $this->parse->UserInformation();
            $this->load->view('cms_profile',$this->hdata);
        }
        $this->load->view('cms_footer');
    }
    public function restaurant()
    {
        $this->FreeTrialValidator();
        if($this->parse->CheckUserInfo()){
            Redirect(base_url()."cms/info", false);
        }
        if($this->hdata['usersubscription'][0]['type'] == '0' && $this->parse->GetRestaurantCount() <=1){
            Redirect(base_url()."cms/profile", false);
        }


        $this->hdata['hprofile']='active';
        $this->load->view('cms_header',$this->hdata);

        // get query string
        $set_func=$this->uri->segment(3);
        $set_id=$this->uri->segment(4);

        // set query string
        $this->hdata['action'] = $set_func;
        $this->hdata['reference'] = $set_id;

        $this->form_validation->set_rules('user[street]', 'Address', 'trim|required');
        $this->form_validation->set_rules('user[state]', 'State', 'trim|required');
        $this->form_validation->set_rules('user[barName]', 'Bar Name', 'trim|required');
        $this->form_validation->set_rules('user[city]', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('user[country]', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('user[zip]', 'Last Name', 'trim|required');
        if($set_func =="new"){
            if ($this->form_validation->run() == true) {
                $tempdata = $this->input->post("user");
                $temgeo = $this->google->GetGeoapi($tempdata['city'],$tempdata['state'],$tempdata['country']);
                if (!empty($tempdata)) {
                    if($temgeo != null){
                        $tempfile = null;
                        if (isset($_FILES['photo'])) {
                            $tempfile = $_FILES['photo'];
                        }
                        $result = $this->parse->AddRestaurant($tempdata,$temgeo,$tempfile);
                        if ($result) {
                            $this->hdata['successnotice'] = 'Successfully added new restaurant information.';
                        } else {
                            $this->hdata['errornotice'] = 'Unable to save data.';
                        }
                    }else{
                        $this->hdata['errornotice'] = 'Invalid Address.';
                    }
                }
            }
            $this->hdata['geolocdata']=$this->GetLocation();
            $this->load->view('cms_restaurant_mod', $this->hdata);
        }elseif($set_func =="edit") {
            ///file validation

            if ($this->form_validation->run() == true) {
                $tempdata = $this->input->post("user");
                $temgeo = $this->google->GetGeoapi($tempdata['city'],$tempdata['state'],$tempdata['country']);
                if (!empty($tempdata)) {
                    if($temgeo != null){
                        $tempfile = null;
                        if (isset($_FILES['photo'])) {
                            $tempfile = $_FILES['photo'];
                        }
                        $result = $this->parse->UpdateRestaurant($tempdata,$temgeo,$set_id,$tempfile);
                        if ($result) {
                            $this->hdata['successnotice'] = 'Successfully updated restaurant information.';
                        } else {
                            $this->hdata['errornotice'] = 'Unable to save data.';
                        }
                    }else{
                        $this->hdata['errornotice'] = 'Invalid Address.';
                    }
                }
            }
            $this->hdata['data']= $this->parse->GetRestaurantById($set_id);
            $this->load->view('cms_restaurant_mod', $this->hdata);
        }
        $this->load->view('cms_footer');
    }
    public function info()
    {
        $this->form_validation->set_rules('user[firstName]', 'First Name', 'trim|required');
        $this->form_validation->set_rules('user[lastName]', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('user[address]', 'Address', 'trim|required');
        $this->form_validation->set_rules('user[city]', 'City', 'trim|required');
        $this->form_validation->set_rules('user[state]', 'State', 'trim|required');
        $this->form_validation->set_rules('user[country]', 'Country', 'trim|required');
        $this->form_validation->set_rules('user[zip]', 'Zip', 'trim|required');

        if ($this->form_validation->run() == true) {
            $tempdata = $this->input->post("user");
            $tempdata['username']=$this->hdata['username'];
            $temgeo = $this->google->GetGeoapi($tempdata['city'],$tempdata['state'],$tempdata['country']);
            if (!empty($tempdata)) {
                if($temgeo != null){

                    $result = $this->parse->AddUserInformation($tempdata,$temgeo);

                    if ($result) {
                            Redirect(base_url()."cms/", false);
                    } else {
                        $this->hdata['errornotice'] = 'Unable to save data.';
                    }
                }else{
                    $this->hdata['errornotice'] = 'Invalid Address.';
                }
            }
        }

        $this->load->view('basic_info',$this->hdata);
    }
    public function Accounts(){
        $this->FreeTrialValidator();
        if($this->parse->CheckUserInfo()){
            Redirect(base_url()."cms/info", false);
        }
        $this->hdata['haccount']='active';
        $this->load->view('cms_header',$this->hdata);

        // get query string
        $set_func=$this->uri->segment(3);
        $set_id=$this->uri->segment(4);

        // set query string
        $this->hdata['action'] = $set_func;
        $this->hdata['reference'] = $set_id;

        // file upload
        $config['upload_path'] = './upload/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '250';
        $this->load->library('upload', $config);


        if($set_func =="edit") {
            ///file validation
            $this->form_validation->set_rules('user[address]', 'Address', 'trim|required');
            $this->form_validation->set_rules('user[state]', 'State', 'trim|required');
            $this->form_validation->set_rules('user[firstName]', 'First Name', 'trim|required');
            $this->form_validation->set_rules('user[lastName]', 'Last Name', 'trim|required');
            if ($this->form_validation->run() == true) {
                $tempdata = $this->input->post("user");
                $tempdata['username']=$this->hdata['username'];
                $temgeo = $this->google->GetGeoapi($tempdata['city'],$tempdata['state'],$tempdata['country']);
                if (!empty($tempdata)) {
                    if($temgeo != null){
                        $tempfile = null;
                        if (isset($_FILES['photo'])) {
                            $tempfile = $_FILES['photo'];
                        }
                        $result = $this->parse->UpdateAccount($set_id,$tempdata, $tempfile,$temgeo);
                        if ($result) {
                            $this->hdata['successnotice'] = 'Successfully updated user information.';
                        } else {
                            $this->hdata['errornotice'] = 'Unable to save data.';
                        }
                    }else{
                        $this->hdata['errornotice'] = 'Invalid Address.';
                    }
                }
            }
            $this->hdata['data'] = $this->parse->GetUserInformationbyId($set_id);
            $this->load->view('cms_account_mod', $this->hdata);
        }elseif($set_func =="search"){
            $skip =0;
            $tempdata = $this->input->post("search");
            $this->hdata['result']= $this->parse->GetAllUserInformation(20,$skip,'',$tempdata);
            $this->hdata['pagination']=$this->Pagination(base_url().'cms/accounts/',$this->parse->GetInformationCount(),20);
            $this->load->view('cms_accounts',$this->hdata);
        }elseif($set_func =="Ascending"){
            $skip =0;
            $this->hdata['result']= $this->parse->GetAllUserInformation(20,$skip,'az','');
            $this->hdata['pagination']=$this->Pagination(base_url().'cms/accounts/Ascending/',$this->parse->GetInformationCount(),20);
            $this->load->view('cms_accounts',$this->hdata);
        }elseif($set_func =="Descending"){
            $skip =0;
            $this->hdata['result']= $this->parse->GetAllUserInformation(20,$skip,'za','');
            $this->hdata['pagination']=$this->Pagination(base_url().'cms/accounts/Descending/',$this->parse->GetInformationCount(),20);
            $this->load->view('cms_accounts',$this->hdata);
        }else{
            $skip =0;
            $this->hdata['result']= $this->parse->GetAllUserInformation(20,$skip,'','');
            $this->hdata['pagination']=$this->Pagination(base_url().'cms/accounts/',$this->parse->GetInformationCount(),20);
            $this->load->view('cms_accounts',$this->hdata);
        }
        $this->load->view('cms_footer');
    }
    public function Upgrade()
    {
        if($this->parse->CheckUserInfo()){
            Redirect(base_url()."cms/info", false);
        }

        $this->hdata['hpupgrade']='active';

        // get query string
        $set_func=$this->uri->segment(3);
        $set_id=$this->uri->segment(4);

        // set query string
        $this->hdata['action'] = $set_func;
        $this->hdata['reference'] = $set_id;

        $this->load->view('cms_header',$this->hdata);


        if($set_func =="basic"){
            $this->hdata['upgradeTo'] = "Basic";
            $this->hdata['amount'] = "$50";
            $this->load->view('cms_payment_form',$this->hdata);
        }elseif($set_func =="premium"){
            $this->hdata['upgradeTo'] = "Premium";
            $this->hdata['amount'] = "$65";
            $this->load->view('cms_payment_form',$this->hdata);
        }else{
            $this->load->view('innersubscription',$this->hdata);
        }

        $this->load->view('cms_footer');
    }
    public function Logout()
    {
        $this->parse->Logout();
        Redirect(base_url()."Registration", false);
    }
    public function Basic(){

        $this->load->database();

        // get user code
        $this->load->model('Userdb');
        $result=$this->Userdb->GetDatabyEmail($this->hdata['username']);
        $code = $result[0]['code'];
        // create new transaction

        $this->load->model('Transactiondb');
        $insertdata=array(
            'useremail'=>$this->hdata['username'],
            'code'=>$code,
            'remove'=>0,
        );
        $transid= $this->Transactiondb->insert($insertdata);

        $data["hidden"] =array(
            'notify_url' => "BuyNow",
            'business' => "ELRBUBZRLUWWE",
            'amount' => "50",
            'item_name' => "Basic",
            'item_number' => "1",
            'quantity' => "1",
            'custom' => $code,
            'invoice' => $transid,
            'cmd' => "_xclick",
            'notify_url' => "http://barshakeapp.com/subscription/ipn/",
            'return' => "http://barshakeapp.com/subscription/cms/",

        );
        $this->load->view('paypal',$data);
    }
    public function Premium(){
        $this->load->database();

        // get user code
        $this->load->model('Userdb');
        $result=$this->Userdb->GetDatabyEmail($this->hdata['username']);
        $code = $result[0]['code'];

        // create new transaction

        $this->load->model('Transactiondb');
        $insertdata=array(
            'useremail'=>$this->hdata['username'],
            'code'=>$code,
            'remove'=>0,
        );
        $transid= $this->Transactiondb->insert($insertdata);
        $amount=65;
        $data=$this->uri->segment(4);
        if($data="upgrade"){
            $amount =15;
        }
        echo $data;
        $data["hidden"] =array(
            'notify_url' => "BuyNow",
            'business' => "ELRBUBZRLUWWE",
            'amount' => $amount,
            'item_name' => "Premium",
            'item_number' => "2",
            'quantity' => "1",
            'custom' => $code,
            'invoice' => $transid,
            'cmd' => "_xclick",
            'notify_url' => "http://barshakeapp.com/subscription/ipn/",
            'return' => "http://barshakeapp.com/subscription/cms/",
        );
        $this->load->view('paypal',$data);
    }

    private function GetClientIpAddress(){
        $ip = getenv('HTTP_CLIENT_IP')?:
            getenv('HTTP_X_FORWARDED_FOR')?:
                getenv('HTTP_X_FORWARDED')?:
                    getenv('HTTP_FORWARDED_FOR')?:
                        getenv('HTTP_FORWARDED')?:
                            getenv('REMOTE_ADDR');
        return $ip;
    }

    private function GetLocation(){
        $ip = $this->GetClientIpAddress();
        $resp = file_get_contents("http://freegeoip.net/json/$ip");
        if (!empty($resp)) {
            return json_decode($resp);
        } else {
            return false;
        }

    }
    private  function FreeTrialValidator(){
        //check free user account if their account has expired

        if($this->hdata['free_days'] >= 30 && $this->hdata['usersubscription'][0]['type'] == 0 && $this->hdata['userType'] == 0 ){
            Redirect(base_url()."cms/upgrade", false);
        };
    }
}
