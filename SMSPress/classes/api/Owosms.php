<?php


class Owosms extends SMSAPI  {
	/* (non-PHPdoc)
	 * @see SMSGateway::Post_SendMessage()
	 */
	
	

	/* (non-PHPdoc)
	 * @see SMSGateway::Pre_SendMessage()
	 */
	public function Pre_SendMessage() {

$this->dataResponse['BAD_AUTH']="Invalid email or password";
$this->dataResponse['EMPTY_MESSAGE']="No message passed.";
$this->dataResponse['EMPTY_SENDER']="No sender ID";
$this->dataResponse['MESSAGE_SEND_ERROR']="API Error. Could not send message. Contact Provider.";
$this->dataResponse['ERROR_SPECIFY_NUMBER_OF_CREDITS']="Credits to be sent not specified";
$this->dataResponse['ERROR_INVALID_PARAMETER']="Invalid Credit Entered.";
$this->dataResponse['ERROR_INVALID_PARAMETER']="Invalid No of Vouchers entered.";
$this->dataResponse['BAD_AUTH']="Invalid email or password";
$this->dataResponse['EMAIL_REQUIRED']="Self descriptory.";
$this->dataResponse['PASSWORD_REQUIRED']="Self descriptory.";
$this->dataResponse['FIRSTNAME_REQUIRED']="Self descriptory.";
$this->dataResponse['LASTNAME_REQUIRED']="Self descriptory";
$this->dataResponse['MOBILE_REQUIRED']="Self descriptory";
$this->dataResponse['USER_EXISTS']="Email is already being used on the platform. Cannot create another one";
$this->dataResponse['ERROR_CREATING_USER']="Something went wrong when saving the user.";
$this->dataResponse['USER_CREATED']="Successfully created new user.";



	//Preparing the Data to be Sent
	$request=array(
		  "username"=>$this->userid,
		  "password"=>$this->password,
		  "sender"=>$this->name,
		  "recipient"=>$this->number,
		  "message"=>$this->message);
	$this->itemRequest=$request;
	
	}
	

	/* (non-PHPdoc)
	 * @see SMSGateway::SendMessage()
	 */
	public function getHTMLFields(){
	return array("email",
		  "password",
		  "sender",
		  "url");	
	}
	public function SendMessage() {
		// TODO Auto-generated method stub
		
		$wp=new WP_Http();
		$response=$wp->request($this->url,array("method"=>"POST","body"=>$this->itemRequest));
		if($response['response']['code'] === SMSAPI::HTTP_OK){
		//check that the response code exists in the dataResponse
		if(array_key_exists((int)$response['body'], $this->dataResponse)):
		$this->setResponseMessage($this->dataResponse[(int)$response['body']]);
		else:
		fb($response['body']);
		endif;
		}
		
	}
	
	
	
	public function Authentication_Required(){
		return true;
	}
	
	
	


	
}

?>