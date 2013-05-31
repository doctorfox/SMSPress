<?php
require_once 'SMSGateway.php';
/**
 * THE SMSAPI Class is responsible for handling the Signing and Loggin in
 * it determines how to send the SMS as various Gateway may have different protocols
 * @author Remi
 *
 */
class SMSAPI implements SMSGateway {
	/**
	 * The Username of the Sender
	 * @var unknown_type
	 */
	protected $userid;
	/**
	 * The password of the Sender
	 * @var unknown_type
	 */
	protected $password;
	
	/**
	 * THis functions stores the username and password to be used for sending or logging in.
	 * @param unknown_type $username
	 * @param unknown_type $password
	 */
	/**
	 * This is the Base URL for sending the SMS via HTTP API
	 * @var unknown_type
	 */
	protected $url;
	/* (non-PHPdoc)
	 * @see SMSGateway::Post_SendMessage()
	 */
	
	protected $coreParams;
	protected $dataResponse=array();
	const HTTP_OK=200;
	const HTTP_ERROR=404;
	private $responseMessage;
	public function Pre_SendMessage() {
		// TODO Auto-generated method stub
	
	}
	public function getResponseMessage(){
		if($this->responseMessage):
		return $this->responseMessage;
		endif;
	}
	public function setResponseMessage($msg){
		$this->responseMessage=$msg;
	}
    public function setText($text){
    $this->message=$text;	
    }
	public function SendMessage() {
		// TODO Auto-generated method stub
		
	}
	public function __construct(){
	
	}
	public function setCrendentials($userid,$password)
	{
	$this->userid=$userid;
	$this->password=$password;
	return $this; //for chaining
	}
	public function Authentication_Required(){
		return false;
	}
	public function getHTMLFields(){
		return array();
	}
	
	protected function get($name,$class){
	return get_option($class."_".$name);

	}
	
	
	
}

?>