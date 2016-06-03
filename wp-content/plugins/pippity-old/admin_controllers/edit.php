<?php

/*
 * Controller for 'Main' Admin Page
 ***********************************/

global $wpdb, $pty;
$create = true;
   
//This is for the post beneath our sample popup
$recent_args = $pty->wpNewer('3.1.0') ? array("number_posts" => 1, "post_status" => "publish") : 1;
$post = current(wp_get_recent_posts($recent_args));
$postUrl = get_permalink($post['ID']);
$tmp = explode('/', str_replace(array('http://', 'https://'), '', $postUrl));
$postUrl = str_replace($tmp[0], $_SERVER['HTTP_HOST'], $postUrl);
$postUrl .= (strpos($postUrl, '?') === false ? '?' : '&') . 'nopty=true';
if(PTY_WYSIJA) {
	$wysija_form_tbl = $wpdb->prefix . 'wysija_form';
	$raw_forms = $wpdb->get_results("SELECT * FROM $wysija_form_tbl");
	$wysija_forms = array();
	foreach($raw_forms as $form) {
		$wysija_forms[$form->form_id] = array(
			"form_id" => $form->form_id,
			"name" => $form->name,
			"data" => unserialize(base64_decode($form->data))
		);
	}
}
if(PTY_GRAVITY) {
	$gr_form_tbl = $wpdb->prefix . 'rg_form';
	$gr_meta_tbl = $wpdb->prefix . 'rg_form_meta';
	$raw_forms = $wpdb->get_results("
		SELECT * FROM $gr_form_tbl as t
		LEFT JOIN $gr_meta_tbl as m
		ON t.id = m.form_id
	");
	$gravity_forms = array();
	foreach($raw_forms as $form) {
		$type = 'standard';
		$data = (object)unserialize($form->display_meta);
		if (strpos(strtolower($data->title), 'pippity') !== false) {
			if (count($data->fields) > 1) {
				$hasEmail = false;
				$hasName = false;
				foreach($data->fields as $inx => $field) {
					$field = (object)$field;
					if (!$hasName && $field->type == 'text') {
						$hasName = $field;
					}
					if (!$hasEmail && $field->type == 'email') {
						$hasEmail = $field;
					}
					if ($hasEmail && $hasName) {
						$data->name_field = $hasName;
						$data->email_field = $hasEmail;
						$type = 'pippity';
					}
					else if ($hasEmail) {
						$data->email_field = $hasEmail;
						$type = 'pippity';
					}
				}
			}
		}
		$gravity_forms[$form->form_id] = array(
			"form_id" => $form->form_id,
			"name" => $form->title,
			"data" => $data,
			'type' => $type,
		);
	}
	//var_dump($gravity_forms);
}
echo '
	<script type="text/javascript">
		$j(function(){snav.panes = ["select", "style", "copy", "settings", "newsletter"];
';
//Are we editing or creating?
if (isset($_GET['popupid'])) {     
	$popup = new Pty_Theme(null, null, $_GET['popupid']);
	echo '
		pty.popupid = ' . $popup->popupid . ';
		pty.theme = ' . $popup->getJson() . ';
		pty.editing = true;
		pty.popupWhenReady = true;
		snav.next(true);
	';
	$create = false;	
}
else { 													
	echo 'pty.editing = false;';
}
if (isset($wysija_forms) && count($wysija_forms)) {
	echo '
		pty.wysija_forms = ' . json_encode($wysija_forms) . ';
	';
}
if (isset($gravity_forms) && count($gravity_forms)) {
	echo '
		pty.gravity_forms = ' . json_encode($gravity_forms) . ';
	';
}
	//Get theme list
	$themes = array("installed" => $pty->getInstalledTemplates());
	$themeJson = array();
	$themeMeta = array();
	foreach ($themes['installed'] as $theme) {
		$theme = new Pty_Theme($theme);
		$themeJson[] = $theme->getPkg();
		$themeMeta[$theme->file] = $theme->getMeta();
	}    
	echo '
	pty.themes = ' . json_encode($themeJson) . ';
	pty.themeMeta = ' . json_encode($themeMeta) . ';
	pty.loadThemes();
				});</script>
				';  
