<?php
/**
 * An SMS Class to handle 
 */
class SMS {
		
		
		
		private $loginDone;
		private $debugMode;
		private $data;
		private $error;
		private $gateway;
		public function __construct()
		{
			
			$this->loginDone=false;
			$this->debugMode=false;
			$this->data=array();
		}
		
		public function setApiGateway(SMSAPI $gateway)
		{
			if($gateway instanceof SMSAPI):			
			$this->gateway=$gateway;
			else:			
			wp_die("$gateway API does not exist in our Record Please Send your SMS API to Us");
			endif;
		}
		/**
		 * This function handles the Sending of Message via the SMSAPI
		 * @param Array of numbers or Int $number
		 * @param unknown_type $msg
		 */
		public function send()
		{
		$this->gateway->Pre_SendMessage();
		$this->gateway->SendMessage();
		}
		public function getLastError()
		{
			return $this->error;
	
		}
		
		public function getMessage(){
			return $this->gateway->getResponseMessage();
		}
		
		
		
	}
