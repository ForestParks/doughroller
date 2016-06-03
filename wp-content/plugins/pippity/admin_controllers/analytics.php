<?php

/*
 * Controller for Analytics Admin Page
 ***********************************/

global $pty, $wpdb;
$col1 = false;
$col2 = false;
if (isset($_GET['popup'])) {
	$col1 = new Pty_Theme(null, null, $_GET['popup'], array('imps' => true));
	$col1 = !$col1->failed ? $col1 : 'Not a valid popup';
}
$popups = $wpdb->get_results("SELECT * FROM " . $pty->t('popups'));
$pCount = count($popups);
$temp = array('active' => array(), 'inactive' => array());
$plist = array();
foreach ($popups as $p) {
	$plist[$p->popupid] = $p->label;
	$popup = new Pty_Theme(null, null, $p->popupid, array('imps' => true));
	if ($p->status) {
		$temp['active'][] = $popup;
	}
	else {													
		$temp['inactive'][] = $popup;
	}
}


// If col1 isn't set yet or if cal1 is set and its active
// then we want col2 to also be an active popup
if ($col1 === false || (is_object($col1) && $col1->status)) {
	foreach ($temp['active'] as $v) {
		if ($col1 === false) {
			$col1 = $v;
		}
		else if ($v->popupid != $col1->popupid) {
			$col2 = $v;
		}
	} 
}

// If we still are missing columns, use the inactive 
// popups, but not if col1 is set and is active
if ((!$col1 || !$col2) && !(is_object($col1) && $col1->status)) {
	foreach ($temp['inactive'] as $p) {
		if ($col1 === false) {
			$col1 = $p;
		}
		else {													
			$col2 = $p;
		}
	}
}
