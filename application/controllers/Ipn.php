<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipn extends CI_Controller {
    public $hdata= array(
        'errornotice'=>'',
        'successnotice'=>'',
        'errorlogin'=>'',
        'successlogin'=>''
    );
    private $parse;
    public function __construct()
    {
        parent::__construct();
        require_once('lib/parse.php');
        $this->parse = new ParseAPI();

    }

    public function index()
    {

        header('HTTP/1.1 200 OK');
        $insert = array();
        $req = 'cmd=_notify-validate';               // Add 'cmd=_notify-validate' to beginning of the acknowledgement

        foreach ($_POST as $key => $value) {         // Loop through the notification NV pairs
            $value = urlencode(stripslashes($value));  // Encode these values
            $req  .= "&$key=$value";                  // Add the NV pairs to the acknowledgement
            $insert[$key]= urldecode($value);
        }



        $header  = "POST /cgi-bin/webscr HTTP/1.1\r\n";                    // HTTP POST request
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

        // Open a socket for the acknowledgement request
        $fp = fsockopen('tls://wwwpaypal.com', 443, $errno, $errstr, 30);

        // Send the HTTP POST request back to PayPal for validation
        fputs($fp, $header . $req);

        while (!feof($fp)) {                     // While not EOF
            $res = fgets($fp, 1024);               // Get the acknowledgement response
            if (strcmp ($res, "VERIFIED") == 0) {  // Response contains VERIFIED - process notification

                // Send an email announcing the IPN message is VERIFIED



                // Authentication protocol is complete - OK to process notification contents

                // Possible processing steps for a payment include the following:

                // Check that the payment_status is Completed
                // Check that txn_id has not been previously processed
                // Check that receiver_email is your Primary PayPal email
                // Check that payment_amount/payment_currency are correct
                // Process payment

            }
            else if (strcmp ($res, "INVALID") == 0) {
                            ///Response contains INVALID - reject notification

                  // Authentication protocol is complete - begin error handling

                  // Send an email announcing the IPN message is INVALID

            }
        }

        try {

            $this->load->database();

            /// insert paypal transaction
            $this->load->model('Paypaldb');
            $this->Paypaldb->insert($insert);


            $this->load->model('Transactiondb');
            $transresult= $this->Transactiondb->GetTransaction($insert['invoice'],$insert['custom']);

            if(isset($transresult[0]['useremail'])){

                //add data to subscription
                $this->load->model('Subscriptiondb');
                $this->Subscriptiondb->VerifySubscription($transresult[0]['useremail'],$insert['item_number']);

                // activate user account
                $this->load->model('Userdb');
                $result = $this->Userdb->GetDatabyCode($insert['custom']);

                if(isset($result[0]['email']) &&isset($result[0]['password'])) {
                    $parseaccount = $this->parse->CheckAvaiability($result[0]['email']);
                    if ($parseaccount) {

                        /// data verification process

                        if ($result[0]['verify'] == 0) {
                            ;
                            $this->Userdb->VerifyAccount($insert['custom']);
                            $response = $this->parse->SignUp($result[0]['email'], $result[0]['password']);
                        }
                    }
                }

            }
        } catch (Exception $e) {

            file_put_contents("paypal.txt", $e);
        }


    }

}
