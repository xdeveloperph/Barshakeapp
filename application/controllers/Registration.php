<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration extends CI_Controller {
    public $hdata= array(
        'errornotice'=>'',
        'successnotice'=>'',
        'errorlogin'=>'',
        'successlogin'=>''
    );
    private $parse;
    private $session_user;
    public function __construct()
    {
        parent::__construct();
        require_once('lib/parse.php');
        $this->parse = new ParseAPI();
        if($this->parse->getCurrentUser() != null){
            $this->session_user = $this->parse->session;
            if($this->session_user->getCurrentUser()->getSessionToken() != null){
                Redirect(base_url()."cms", false);
            };
        }

    }

    public function index()
    {

        $this->load->view('header',$this->hdata);
        $this->load->view('subscription',$this->hdata);
        $this->load->view('footer');
    }
    public function login()
    {
        $data = $this->input->post("log");
        if(!empty($data)){
            $userdata=$this->parse->Login($data['user'],$data['pass']);
            if(!empty($userdata)){
                $this->session_user = $userdata;
                if($this->session_user->getCurrentUser()->getSessionToken() != null){
                    Redirect(base_url()."cms", false);
                };
            }else{
                $this->hdata['errorlogin']="Username or Password is invalid.";
                $this->load->view('header',$this->hdata);
                $this->load->view('subscription',$this->hdata);
                $this->load->view('footer');
            }
        }
    }
    public function Logout()
    {
        $this->parse->Logout();
        $this->load->view('header',$this->hdata);
        $this->load->view('subscription',$this->hdata);
        $this->load->view('footer');
    }
    public function user()
    {

        $this->form_validation->set_rules('userform[email]', 'Username', 'trim|required|valid_email');
        $this->form_validation->set_rules('userform[password]', 'Password', 'trim|required|matches[retpass]');
        $this->form_validation->set_rules('retpass', 'Password Confirmation', 'required');
        if($this->form_validation->run()==true){

            $userdata = $this->input->post("userform");
            if(!empty($userdata)){

                /// check user account availability
                $this->load->database();
                $this->load->model('Userdb');
                $localccount = $this->Userdb->checkregistered($userdata['email']);
                $parseaccount=$this->parse->CheckAvaiability($userdata['email']);


                if($parseaccount && $localccount){
                    $userdata["code"] =md5($userdata['password']."_".date("YmdHis"));
                    $this->Userdb->insert($userdata);;
                    $urllink=base_url()."registration/verify/".$userdata["code"];
                    $this->sendMail($userdata["email"],$urllink);
                    $this->hdata['successnotice']='Successfully create new account. Please check your mail to complete the process.';
                }else{
                    $this->hdata['errornotice']='Username is not available.';
                }


            }
        }
        $this->load->view('header');
        $this->load->view('subscription',$this->hdata);
        $this->load->view('footer');

    }
    public function signup()
    {

        $this->form_validation->set_rules('userform[email]', 'Username', 'trim|required|valid_email');
        $this->form_validation->set_rules('userform[password]', 'Password', 'trim|required|matches[retpass]');
        $this->form_validation->set_rules('retpass', 'Password Confirmation', 'required');
        if($this->form_validation->run()==true){

            $userdata = $this->input->post("userform");
            if(!empty($userdata)){

                /// check user account availability
                $this->load->database();
                $this->load->model('Userdb');
                $localccount = $this->Userdb->checkregistered($userdata['email']);
                $parseaccount=$this->parse->CheckAvaiability($userdata['email']);


                if($parseaccount && $localccount){
                    $userdata["code"] =md5($userdata['password']."_".date("YmdHis"));
                    $this->Userdb->insert($userdata);;
                    $urllink=base_url()."registration/verify/".$userdata["code"];
                    $this->sendMail($userdata["email"],$urllink);
                    $this->hdata['successnotice']='Your account has been successfully created. Please check your e-mail to complete the verification process.';
                }else{
                    $this->hdata['errornotice']='Username is not available.';
                }


            }
        }
        $this->load->view('header',$this->hdata);
        $this->load->view('signup',$this->hdata);
        $this->load->view('footer');

    }
    public function verify($code)
    {
        ///$this->hdata['code']=$code;
        ///$this->load->view('header',$this->hdata);
        ///$this->load->view('step2',$this->hdata);
        ///$this->load->view('footer');

        $this->load->database();
        $this->load->model('Userdb');
        $result = $this->Userdb->GetDatabyCode($code);

        if(isset($result[0]['email']) &&isset($result[0]['password'])){
            $parseaccount=$this->parse->CheckAvaiability($result[0]['email']);
            if($parseaccount){

                /// data verification process
                $response = "";
                if($result[0]['verify']==0){;
                    //add data to subscription
                    $this->Userdb->VerifyAccount($code);
                    $this->load->model('Subscriptiondb');

                    //add data to parse
                    $response = $this->parse->SignUp($result[0]['email'],$result[0]['password']);
                    $this->Subscriptiondb->VerifySubscription($result[0]['email'],0,$response);
                }

                /// result notification
                if($response =="valid"){
                    $this->hdata['successnotice']='Successfully activated the account you can now login to the app.';
                }else
                {
                    $this->hdata['errornotice']="Unable to activate account.";
                }

            }else{
                $this->hdata['errornotice']="Account is already activated.";
            }

        }else{
            $this->hdata['errornotice']='Account not valid.';
        }

        $this->load->view('step3',$this->hdata);
        $this->load->view('footer');
    }


    public function free($code){
        $this->load->database();
        $this->load->model('Userdb');
        $result = $this->Userdb->GetDatabyCode($code);

        if(isset($result[0]['email']) &&isset($result[0]['password'])){
            $parseaccount=$this->parse->CheckAvaiability($result[0]['email']);
            if($parseaccount){

                /// data verification process
                $response = "";
                if($result[0]['verify']==0){;
                    //add data to subscription
                    $this->Userdb->VerifyAccount($code);
                    $this->load->model('Subscriptiondb');


                    //add data to parse
                    $response = $this->parse->SignUp($result[0]['email'],$result[0]['password']);

                    $this->Subscriptiondb->VerifySubscription($result[0]['email'],0,$response);
                }

                /// result notification
                if($response =="valid"){
                    $this->hdata['successnotice']='Successfully activated the account you can now login to the app.';
                }else
                {
                    $this->hdata['errornotice']="Unable to activate account.";
                }

            }else{
                $this->hdata['errornotice']="Account is already activated.";
            }

        }else{
            $this->hdata['errornotice']='Account not valid.';
        }

        $this->load->view('header',$this->hdata);
        $this->load->view('step3',$this->hdata);
        $this->load->view('footer');
    }
    public function basic($code){
        $this->load->database();

        // get user code
        $this->load->model('Userdb');
        $result=$this->Userdb->GetDatabyCode($code);

        // create new transaction

        $this->load->model('Transactiondb');
        $insertdata=array(
            'useremail'=>$result[0]['email'],
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
            'return' => "http://barshakeapp.com/subscription/registration/success",
        );
        $this->load->view('paypal',$data);
    }
    public function Premium($code){
        $this->load->database();

        // get user code
        $this->load->model('Userdb');
        $result=$this->Userdb->GetDatabyCode($code);

        // create new transaction

        $this->load->model('Transactiondb');
        $insertdata=array(
            'useremail'=>$result[0]['email'],
            'code'=>$code,
            'remove'=>0,
        );
        $transid= $this->Transactiondb->insert($insertdata);

        $data["hidden"] =array(
            'notify_url' => "BuyNow",
            'business' => "ELRBUBZRLUWWE",
            'amount' => "65",
            'item_name' => "Premium",
            'item_number' => "2",
            'quantity' => "1",
            'custom' => $code,
            'invoice' => $transid,
            'cmd' => "_xclick",
            'notify_url' => "http://barshakeapp.com/subscription/ipn/",
            'return' => "http://barshakeapp.com/subscription/registration/success",
        );
        $this->load->view('paypal',$data);
    }
    public function success()
    {
        $this->hdata['successnotice']='Successfully activated the account you can now login to the app.';
        $this->load->view('header',$this->hdata);
        $this->load->view('step3',$this->hdata);
        $this->load->view('footer');

    }
    function sendMail($email,$url){

        //mainchimp

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'smtp.mandrillapp.com'; //change thisfair
        $config['smtp_port'] = '587';
        $config['smtp_user'] = 'barshakeapp@gmail.com'; //change this
        $config['smtp_pass'] = 'kXo6m6IldPQKqIECkahV0g'; //change this
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n";
        $this->email->initialize($config);
        $this->email->from('barshakeapp@gmail.com', 'Bar Shake');
        $this->email->to($email);
        $this->email->subject('Account Verification');
        $this->email->message('Please click the link bellow to activate account:  <br> <a href="'.$url.'">'.$url.'<a>');
        if (!$this->email->send())
        {
            $this->hdata['errornotice']='Fail to send mail.';
        }
    }
}
