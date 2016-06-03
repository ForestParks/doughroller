<?php

/*
 * Controller for Upgrade Page
 ***********************************/
if (!ini_get('safe_mode')) {
	set_time_limit (150);
}
global $pty;
$needCreds = false;
$success = false;
$error = false;
if (isset($_REQUEST['version'])) {
	check_admin_referer('pty-upgrade');
	$url = wp_nonce_url('admin.php?page=pty&pty_page=upgrade','pty-upgrade');
	$form_fields = array('version');
	$method = '';
	if (! (false === ($creds = request_filesystem_credentials($url, $method, false, false, $form_fields) ) ) ) {
		if ( ! WP_Filesystem($creds) ) {
			request_filesystem_credentials($url, $method, true, false, $form_fields);
		}
		else {
			$upgrade = $pty->upgrade($_REQUEST['version']);
			if (!is_array($upgrade)) {
				$success = true;
			}
			else{
				$error = $upgrade['error'];
			}
		}
	}
	else {
		$needCreds = true;
	}
}