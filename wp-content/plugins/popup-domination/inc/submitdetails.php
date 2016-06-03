<?php
	$provider = $_POST['provider'];
	$campaignID = $_POST['campaignid'];
	$campname = $_POST['campname'];
	$mailing_list_id = $_POST['mailingid'];
	
	if ($provider != 'form')
	{
    	$mailing_info = unserialize(base64_decode($this->option('formhtml')));
    	if ( empty($mailing_info) || ( !empty($mailing_info['listid']) && $mailing_info['listid'] != $_POST['listid'] ) )
    	{
    		$mailinglists = $this->get_db('popdom_mailing');
    		if (is_array($mailinglists))
    		{
    			foreach ($mailinglists as $mailinglist)
    			{
    				if ($mailinglist->id == $mailing_list_id)
    				{
    					$mailing_info = unserialize(base64_decode($mailinglist->settings));
    				}
    			}
    		}
    	}
    	//print_r($mailing_info);
    
    	if (!empty($mailing_info) && !empty($provider)){
    		// set up variables used by every provider
    		foreach($mailing_info as $key => $value){
    			$$key = $value;
    			//print_r("$key => $value\n");
    		}
    
    		if (gettype($provider_details) == "array"){
    			// create all variables required for each provider
    			foreach($provider_details as $key => $value){
    				$$key = $value;
    		    //print_r("$key => $value\n");
    
    			}
    			$formhtml = stripslashes($provider_details['formhtml']);
    			$hidden_fields = '';
    			if (isset($hidden)){
    				foreach($hidden as $key => $value){
    					$$key = $value;
    				  //print_r("$key => $value\n");
    				}
    			}
    		}
    	}
    /*
         $maildetail = print_r($_POST,true);
    		 die($maildetail);
    */
    	$provider = $_POST['provider'];
    }
	if (!empty($_POST['mailnotify']) || $provider == 'nm')
	{
	    $to = $bcc = '';
	    $to = (!empty($_POST['master'])) ? $_POST['master'] : $_POST['mailnotify'];
	    $bcc = (!empty($_POST['master'])) ? $_POST['mailnotify'] : '';
      	$subject = "PopUp Domination Sign Up";
      	$name_field = (!empty($_POST['name'])) ? $_POST['name'] : '';
      	$email_field = $_POST['email'];
      	$custom1 = $_POST['custom1'];
      	$custom2 = $_POST['custom2'];
      	$body = "Name: $name_field\nE-Mail: $email_field\nCustom Input1: $custom1\nCustom Input2: $custom2\n";
      	$body .= "\r\nThis opt-in came via popup {$campaignID} - {$campname}";
      	$headers = 'From: PopUpDomination' . "\r\n";
      	$headers .="Bcc: {$bcc}\r\n";
        $headers .="Reply-To: $email_field" . "\r\n";
        $headers .='X-Mailer: PHP/' . phpversion();
        wp_mail($to, $subject, $body, $headers);
	}
	if($provider == 'mc'){
		include_once 'mc/subscribe.php';
	}
	elseif($provider == 'cm'){
		include_once 'campmon/subscribe.php';
	}
	elseif($provider == 'aw'){
		include_once 'aweber_api/subscribe.php';
	}
	elseif($provider == 'cc'){
		include_once 'concon/subscribe.php';
	}
	elseif($provider == 'ic'){
		include_once 'icon/subscribe.php';
	}
	elseif($provider == 'gr'){
		include_once 'getre/subscribe.php';
	}
	elseif( $provider == 'form' || $provider == 'aw'){
		die('formcode');
	}

	die();
?>
