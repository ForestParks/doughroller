<?php
if (!class_exists('Icontact', true)){
	require_once( 'icontact.php' );
}


$icontact = new Icontact(
	'https://app.icontact.com/icp',
	$username,
	$password,
	$apikey
);

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

try {
	$account_id = $icontact->LookUpAccountId();
	$client_folder_id = $icontact->LookUpClientFolderId();
	$contact_id = $icontact->AddContact( array(
		'firstName' => $name,
		'email' => $_POST['email'],
		$custom1name => $custom1,
		$custom2name => $custom2
	));
	
	$contact_add_to_list = $icontact->SubscribeContactToList($contact_id, $listid);
} catch ( IcontactException $ex ) {	
	print_r( $ex->GetErrorData() );
}
?>