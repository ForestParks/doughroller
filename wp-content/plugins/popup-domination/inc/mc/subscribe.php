<?php
if (!class_exists('MCAPI', true)){
	require_once('MCAPI.class.php');
}
	if (!empty($_POST['apikey']))
	{
		$apikey = $_POST['apikey'];
	}
	// grab an API Key from http://admin.mailchimp.com/account/api/
	$api = new MCAPI($apikey);
	
	// grab your List's Unique Id by going to http://admin.mailchimp.com/lists/
	// Click the "settings" link for the list - the Unique Id is at the bottom of that page.
	
	
	if(isset($_POST['name']) && !empty($_POST['name'])){
		$name = $_POST['name'];
	}else{
		$name = '';
	}
	if(isset($_POST['custom1']) && !empty($_POST['custom1'])){
		$custom1 = $_POST['custom1'];
	}else{
		$custom1 = '';
	}
	if(isset($_POST['custom2']) && !empty($_POST['custom2'])){
		$custom2 = $_POST['custom2'];
	}else{
		$custom2 = '';
	}
	//echo $_POST['email'].$_POST['name'].$_POST['listid'];
	
	if($api->listSubscribe($listid, $_POST['email'], array('FNAME' => $name, 'MERGE2' => $custom1, 'MERGE3' => $custom2)) === true || $api->errorCode == 214) { //error 214 = already subscribed - http://apidocs.mailchimp.com/api/1.3/exceptions.field.php
		// It worked!
	}else{
		// An error ocurred, return error message	
		
		if ($api->errorCode == 502) echo $api->errorMessage; //502 -> invalid email address
		elseif ($api->errorCode == 250) echo $api->errorMessage . " Please contact the website owner to inform them of an issue with their setup."; //250 -> merge field required but not supplied
		else {
		  echo 'Error: ' . $api->errorCode . "=>". $api->errorMessage;	
        }
    }