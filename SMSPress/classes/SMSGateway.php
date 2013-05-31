<?php

/**
 * A Simple SMS GateWay that every API must extends to provide their own way of sending data
 * @author Remi
 *
 */
interface SMSGateway {
	/**
	 * Add this function to Prepare the variables and the neccessary for sending SMS
	 */
	public function Pre_SendMessage();
	/**
	 * Add this function to call this function after Message has been sent
	 */
	
}

?>