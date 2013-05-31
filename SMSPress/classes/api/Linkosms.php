<?php


class Linkosms extends SMSAPI  {
	/* (non-PHPdoc)
	 * @see SMSGateway::Post_SendMessage()
	 */
	
	/**
	 * This Holds the Array of URL Param that are vital for sending SMS
	 * @var Array of Strings
	 */
	protected $coreParams=array("recipient","username","password","sender","message");
	

	/* (non-PHPdoc)
	 * @see SMSGateway::Pre_SendMessage()
	 */
	public function Pre_SendMessage() {
$this->dataResponse[2904]="SMS Sending Failed";
$this->dataResponse[2905]="Invalid username/password combination";
$this->dataResponse[2906]="Credit exhausted";
$this->dataResponse[2907]="Gateway unavailable";
$this->dataResponse[2908]="Invalid schedule date format";
$this->dataResponse[2909]="Unable to schedule";
$this->dataResponse[2910]="Username is empty";
$this->dataResponse[2911]="Password is empty";
$this->dataResponse[2912]="Recipient is empty";
$this->dataResponse[2913]="Message is empty";
$this->dataResponse[2914]="Sender is empty";
$this->dataResponse[2915]="One or more required fields are empty";

	//Preparing the Data to be Sent
	$request=array(
		  "username"=>$this->get("username",__CLASS__),
		  "password"=>$this->get("password",__CLASS__),
		  "sender"=>$this->get("sender",__CLASS__),
		  "recipient"=>$_POST['mobile'],
		  "message"=>$_POST['message']
				   );
			
	 $tempArrayUnSet=array();
	 foreach($this->coreParams as $core):
	 if(!$request[$core] || !isset($request[$core])):  //Cores not Set 
	 $tempArrayUnSet[]="<p>{$core} was not Set please Set the Value</p>";
	 endif;
	 endforeach;
	
	 if(count($tempArrayUnSet)):
	 $messages="<h1>".__CLASS__." API was badly configured and failed to send due to the following reasons</h1><hr/>";
	 $messages.=implode($tempArrayUnSet);
	 wp_die($messages,__CLASS__."Configuration Error");
	 endif;	 
	 $this->itemRequest=$request;
	 }
	

	/* (non-PHPdoc)
	 * @see SMSGateway::SendMessage()
	 */
	public function getHTMLFields(){
	return array("username",
		  "password",
		  "sender",
		  "url");	
	}
	
	public function SendMessage() {
		// TODO Auto-generated method stub
		
		$wp=new WP_Http();
		//Auto Generate the Correct URL by adding the CoreParameters
		$url=get_option(__CLASS__."_url");  // This is the URL Prefix
		
		for($i=0; $lastElem=$i < count($this->coreParams); $i++):
		if($i === 0):
		/////////////////////////////////////////////////////////////////////////////////////
		
		$url.=$this->coreParams[$i]."=".$this->itemRequest[$this->coreParams[$i]];
		elseif($i === $lastElem):
		
		$url.=$this->coreParams[$i]."=".$this->itemRequest[$this->coreParams[$i]];		
		
		else:
		$url.="&".$this->coreParams[$i]."=".$this->itemRequest[$this->coreParams[$i]];
		
		/////////////////////////////////////////////////////////////////////////////////////
		endif;
		endfor;
		
		$response=$wp->request($url,array("method"=>"POST","body"=>$this->itemRequest));
		fb($url);
		if($response instanceof WP_Error):
		$messages="";
		foreach($response->errors as $k=>$v):
		if(is_array($v)):
		$messages.=implode($v);
		else:
		$messages.="<p>".$v."</p>";
		endif;
		endforeach;
		$this->setResponseMessage($messages);		
		else:
		if($response['response']['code'] === SMSAPI::HTTP_OK){
		//check that the response code exists in the dataResponse
		if(array_key_exists((int)$response['body'], $this->dataResponse)):
		$this->setResponseMessage($this->dataResponse[(int)$response['body']]);
		else:
		fb($response['body']);
		$this->setResponseMessage($response['body']);
		endif;
		}
		endif;
		
	}
	
	
	
	
	
	
	
	


	
}

?>