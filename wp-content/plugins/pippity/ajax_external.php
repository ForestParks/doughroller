<?php

$path_bits = explode('/', __FILE__);
$path = '';
foreach($path_bits as $bit){
	if ($bit == 'wp-content') {
		break;
	}
	else {
		$path .= $bit . '/';
	}
}
require_once($path . 'wp-config.php');

switch($_REQUEST['do']) {
	case 'gravity':
		$pippity_on_submit = ".load( function(){
			setTimeout(function(){
				pty.close();
			}, 2000);
		";
		$output = do_shortcode(str_replace('\\', '', ($_REQUEST['html'])));
		$output = str_replace('/wp-content/plugins/pippity/ajax_external.php', '/', $output);
		$output = str_replace("\'", '"', $output);
		$output = str_replace(".load( function(){", $pippity_on_submit, $output);
		echo $output;
		exit;
	break;
	case 'shortcode':
		$output = do_shortcode(str_replace('\\', '', ($_REQUEST['shortcode'])));
		echo $output;
		exit;
	break;
}

