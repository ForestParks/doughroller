<?php
if(isset($_POST['api_key']) && isset($_POST['client_id'])){
	$apikey = $_POST['api_key'];
	$clientid = $_POST['client_id'];
	
	
	
	if (!class_exists('CS_REST_Clients', true)){
		require_once 'csrest_clients.php';
	}
	
	function getErrorMessage($result){
		$error = $result->response->Code;
		if($error == 100) {
			die('<p class="fatal-error">Incorrect API Key.</p>');
		} else if($error == 102) {
			die('<p class="fatal-error">Incorrect ClientID.</p>');
		}
	}
	
	$wrap = new CS_REST_Clients($clientid, $apikey);
	$result = $wrap->get_lists();
	
	if (!$result->was_successful()){
		getErrorMessage($result);
		return;
	} else{
		if (empty($result->response)){
			echo '<p class="no-lists">You don\'t have any lists yet</p>';
		} else {
			$var  = '<span class="mailing-list-small">Your Campaign Monitor Mailing Lists</span><select name="listsid" class="mailing_lists" >';
			foreach($result->response as $r){
				$var .= '<option name="cm_list_option" value="'.$r->ListID.'">'.$r->Name.'</option>';
			}
			$var .= '</select>';
			echo $var;
		}
	}
}else{
	echo 'Please enter all your details into the inputs above and please double check that they are correct.';
}


/*echo "Result of /api/v3/clients/{id}/lists\n<br />";
if($result->was_successful()) {
    echo "Got lists\n<br /><pre>";
    var_dump($result->response);
} else {
    echo 'Failed with code '.$result->http_status_code."\n<br /><pre>";
    var_dump($result->response);
}
echo '</pre>';*/