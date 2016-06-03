<?php

if (!isset($apikey)){
	die("API Key not found in database.");
}

if (!class_exists('jsonRPCClient', true)){
	require_once 'jsonRPCClient.php';
}


$api_url = 'http://api2.getresponse.com';
$client = new jsonRPCClient($api_url);
$result = NULL;

if(isset($_POST['custom1name'])){
 	$custom1name = $_POST['custom1name'];
 	$custom1 = $_POST[$custom1name];
}else{
 	$custom1name = '';
 	$custom1 = '';
}

if(isset($_POST['custom2name'])){
 	$custom2name = $_POST['$custom2name'];
 	$custom2 = $_POST[$custom2name];
}else{
	$custom2name = '';
 	$custom2 = '';
}

try {
	$customs = array();
	
	if(!empty($custom1) && !empty($custom1name)){
		$customs[] = array('name' => $custom1name, 'content' => $custom1);
	}
	if(!empty($custom2) && !empty($custom2name)){
		$customs[] = array('name' => $custom2name, 'content' => $custom2);
	}
	
  if (!empty($_POST['name']))
  {
    $result = $client->add_contact($apikey,
      array (
        'campaign' => $_POST['listid'],
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'customs' => $customs,
        'cycle_day' => '0'
      )
    );
  }
  else {
    $result = $client->add_contact($apikey,
      array (
        'campaign' => $_POST['listid'],
        'email' => $_POST['email'],
        'customs' => $customs,
        'cycle_day' => '0'
      )
    );
  }
	
	if(is_array($result)){
		echo '';
	}else{
		echo $results;
	}
}
catch (Exception $e) {
    die($e->getMessage());
}

?>