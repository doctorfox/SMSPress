<?php
class ClickandTell extends SMSAPI  {
	private $coreParams=array("api_id","user","password","message","recipient");
	
	
	/* (non-PHPdoc)
	 * @see SMSGateway::Pre_SendMessage()
	 */
	public function Pre_SendMessage() {
	//Preparing the Data to be Sent
	
	

	
	$request=array(
			"password"=>$this->get("password",__CLASS__),
			"user"=>$this->get("user",__CLASS__),
			"api_id"=>$this->get("api_id",__CLASS__),
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
	public function SendMessage() {
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
		
		$this->setResponseMessage($response['body']);
		endif;
		}
		endif;
		
	}
	
	
	
	public function Authentication_Required(){
		return true;
	}
	public function getHTMLFields(){
	return array(
	"user",
	"password",
	"name",
	"api_id",
	"url"
	);
	}


	
}

?>