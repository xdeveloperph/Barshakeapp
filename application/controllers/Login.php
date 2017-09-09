<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
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
        'errornotice'=>'',
        'successnotice'=>'',
        'errorlogin'=>'',
        'successlogin'=>''
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

    }

    public function index()
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
                $this->load->view('main_login',$this->hdata);
                $this->load->view('footer');
            }
        }else{

            $this->load->view('header',$this->hdata);
            $this->load->view('main_login',$this->hdata);
            $this->load->view('footer');
        }


    }


}