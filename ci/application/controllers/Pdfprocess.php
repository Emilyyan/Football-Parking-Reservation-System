<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdfprocess extends CI_Controller {

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
		$this->load->helper('url');
		$this->load->model('Transition_Model','transition');
	}
	
	public function index()
	{
		//sleep(8);
		//$this->transition->get_lastid();
		$this->load->view('pdfticket');
	}
	
	public function createPdf(){
		$temp=$_POST['pdf'];
		$pdf=str_replace(" ","+",$temp);
		$binary=base64_decode($pdf);
		file_put_contents('filePdf/test.pdf',$binary );
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
$receiver_email='378160466@qq.com';
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
