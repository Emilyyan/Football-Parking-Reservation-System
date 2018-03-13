<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	
	public function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->load->model('Transition_Model','transition');
		$this->load->helper('url');
	}
	
	public function index()
	{
		 echo $this->uri->segment(3);

	}
	
	public function processTransition(){
	 
	 
	 // STEP 1: read POST data
// Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
// Instead, read raw POST data from the input stream.
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
  $keyval = explode ('=', $keyval);
  if (count($keyval) == 2)
    $myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
$req = 'cmd=_notify-validate';
if (function_exists('get_magic_quotes_gpc')) {
  $get_magic_quotes_exists = true;
}
foreach ($myPost as $key => $value) {
  if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
    $value = urlencode(stripslashes($value));
  } else {
    $value = urlencode($value);
  }
  $req .= "&$key=$value";
}

// Step 2: POST IPN data back to PayPal to validate
//$ch = curl_init('https://www.paypal.com/cgi-bin/webscr');
$ch = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
// In wamp-like environments that do not come bundled with root authority certificates,
// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set
// the directory path of the certificate as shown below:
// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
if ( !($res = curl_exec($ch)) ) {
  // error_log("Got " . curl_error($ch) . " when processing IPN data");
  curl_close($ch);
  exit;
}
curl_close($ch);

if (strcmp ($res, "VERIFIED") == 0) {
  // The IPN is verified, process it:
  // check whether the payment_status is Completed
  // check that txn_id has not been previously processed
  // check that receiver_email is your Primary PayPal email
  // check that payment_amount/payment_currency are correct
  // process the notification
  // assign posted variables to local variables
  $item_name = $_POST['item_name'];
  $item_number = $_POST['item_number'];
  $payment_status = $_POST['payment_status'];
  $payment_amount = $_POST['mc_gross'];
  $payment_currency = $_POST['mc_currency'];
  $txn_id = $_POST['txn_id'];
  $receiver_email = $_POST['receiver_email'];
  $payer_email = $_POST['custom'];
  // IPN message values depend upon the type of notification sent.
  // To loop through the &_POST array and print the NV pairs to the screen:
  //$string="aa";
  $data[0]=$txn_id;
  $data[1]=$item_number;
  $data[2]=$payer_email;
  $data[3]=$payment_status;
  file_put_contents('test.txt',$_POST['custom']);
  $this->transition->insert_txn_id($data);
  self::sendEmail();
  foreach($_POST as $key => $value) {
    $temp=$key . " = " . $value . "\n";
     $string.= $temp;
  }
  //file_put_contents('123.txt',$string);
} else if (strcmp ($res, "INVALID") == 0) {
  // IPN invalid, log for manual investigation
  echo "The response from IPN was: <b>" .$res ."</b>";
}
}
	
	
	
	public function test(){
	 $this->load->model('Transition_Model','transition');
	 $this->transition->test();
	}
	
	
	
	public function sendEmail(){
		// The mail sending protocol.
$config['protocol'] = 'smtp';
// SMTP Server Address for Gmail.
$config['smtp_host'] = 'ssl://smtp.googlemail.com';
// SMTP Port - the port that you is required
$config['smtp_port'] = 465;
// SMTP Username like. (abc@gmail.com)
$sender_email='mizzougroup11@gmail.com';
$config['smtp_user'] = $sender_email;
// SMTP Password like (abc***##)
$config['smtp_pass'] = 'Group111';
// Load email library and passing configured values to email library
$this->load->library('email', $config);
// Sender email address
$this->email->set_newline("\r\n");  
$username='mizzougroup11';
$this->email->from($sender_email, $username);
// Receiver email address.for single email
$receiver_email='liuqintai@gmail.com';
$this->email->to($receiver_email);
//send multiple email
//$this->email->to(abc@gmail.com,xyz@gmail.com,jkl@gmail.com);
// Subject of email
$subject='subject';
$this->email->subject($subject);
$message='this is test message';
// Message in email
$this->email->message($message);
// It returns boolean TRUE or FALSE based on success or failure
$this->email->attach('filePdf/test.pdf');
if($this->email->send()){
	echo "s";
}else{
	show_error($this->email->print_debugger());
}
	}
	
	
}
?>