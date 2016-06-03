<?php

/*
 * Controller for Theme Upload Page 
 ***********************************/

global $pty;
$needCreds = false;
$uploadSuccess = false;
$maxPost = ini_get('post_max_size')*1;
$maxFile = ini_get('upload_max_filesize')*1;
$errors = array();
if (count($_FILES) === 0 && isset($_GET['pty_up'])) {
	$errors[] = "Your file should be less than " . $maxPost . "MB";
}
if (isset($_POST['uploadTheme']) && !count($errors)) {
	check_admin_referer('pty-upload');
	$updir = wp_upload_dir();

	// We start by moving the uploaded file so we have it for later
	if (isset($_POST['justUploaded'])) {
		$tmp_name = $_FILES['themePack']['tmp_name'];
		$new_name = $_FILES["themePack"]["name"];
		if ($_FILES["themePack"]["error"] === 1) {
			$errors[] = "Your file should be less than " . $maxFile . "MB";
		}
		else {
			if (!is_dir(PTY_TEMP)) {
				mkdir(PTY_TEMP, 0777);
				chmod(PTY_TEMP, 0777);
			}
			move_uploaded_file($tmp_name, PTY_TEMP  . '/' . $new_name);
		}
	}

	if (!count($errors)) {
    
		// Now we prepare to unzip it, but we need to setup WP_Filesystem first
		// 'uploadTheme' is passed but 'justUploaded' isn't
		$url = wp_nonce_url('admin.php?page=pty-upload','pty-upload');
		$form_fields = array('themePack', 'uploadTheme');
		$method = '';
		if (! (false === ($creds = request_filesystem_credentials($url, $method, false, false, $form_fields) ) ) ) {
			if ( ! WP_Filesystem($creds) ) {
				request_filesystem_credentials($url, $method, true, false, $form_fields);
			}
			else {
				global $wp_filesystem;
				foreach (scandir(PTY_TEMP) as $file) {
					if ($file != '.' && $file != '..') {
						chmod(PTY_TEMP, 0777);
						chmod(PTY_TEMP . '/' . $file, 0777);
						unzip_file(PTY_TEMP . '/' . $file,  PTY_TEMP);
						$wp_filesystem->delete(PTY_TEMP . '/' . $file, true);
					}
				}
				foreach (scandir(PTY_TEMP) as $file) {
					if ($file != '.' && $file != '..') {
						foreach (scandir(PTY_TEMP . '/' . $file) as $inner) {
							$from = PTY_TEMP . '/' . $file . '/' . $inner;
							$to = PTY_DIR . '/themes/' . $inner;
							$pty->movedir($from, $to, $wp_filesystem, true);
							$wp_filesystem->chmod($from, 0777, true);
						}
						
					}
				}
				$uploadSuccess = true;
				$wp_filesystem->chmod(PTY_DIR . '/themes', 0755, true);
				$wp_filesystem->delete(PTY_TEMP, true);
			}
		}
		else {
			$needCreds = true;
		}
	}
}