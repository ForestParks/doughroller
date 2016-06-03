<?php

if (!class_exists('ConstantContact', true)){
	require_once('ConstantContactz.php');
}

$username = strtolower($username); //weird bug in CC - requires lowercase username?
$password = $password;
$apikey = str_replace(' ', '', $apikey);
$cc = new ConstantContact($apikey, $username, $password);

if(isset($_POST['name'])){
	$name = $_POST['name'];
}else{
	$name = '';
}
if(isset($_POST[$custom1name])){
	$custom1 = $_POST[$custom1name];
}else{
	$custom1 = '';
}
if(isset($_POST[$custom2name])){
	$custom2 = $_POST[$custom2name];
}else{
	$custom2 = '';
}

function getErrorMessage($result){
	if (strstr($result, '409')){
		echo ''; //Contact already exists within mailing list.
	} else if (strstr($result, '400')) {
		echo str_ireplace('Error 400:','',strip_tags($result));
	} else if (strstr($result, '401')) {
		echo '{error: "Authentication problem"}';
	} else {
		echo strip_tags($result);
	}
}

$result = $cc->addContactToMailingList($_POST['email'], $name, $custom1, $custom2, $custom1name, $custom2name, $listid);
$error = strstr($result, 'Error');
if($error){
	getErrorMessage($result);
}else{
	echo '';
}
?>