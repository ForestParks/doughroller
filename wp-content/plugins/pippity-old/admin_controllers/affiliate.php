<?php

/*
 * Affiliate Controller
 */
$updateSuccess = false;
if (isset($_POST['pty_save_aff'])) {
	update_option('pty_afflink', $_POST['pty_afflink']);
	update_option('pty_afftext', $_POST['pty_afftext']);
	$updateSuccess = true;
}