<?php
/*
 * Controller for 'Main' Admin Page
 ***********************************/

global $wpdb, $pty;
$recent_args = $pty->wpNewer('3.1.0') ? array("number_posts" => 1, "post_status" => "publish") : 1;
$post = current(wp_get_recent_posts($recent_args));
$postUrl = get_permalink($post['ID']);
$lastStep = 3;
$popups = $wpdb->get_results("SELECT * FROM " . $pty->t('popups') . " WHERE step >= '$lastStep'");
$pty->log('Getting Popups', array("popups" => $popups));
$pty->logSqlErr('Getting Popups Query');
$incPopups = array();
$incPopups = $wpdb->get_results("SELECT * FROM " . $pty->t('popups') . " WHERE step < '$lastStep'");
$pty->log('Getting Incomplete Popups', array("Inc Popups" => $popups));
$pty->logSqlErr('Getting Incomplete Popups Query');
$temp = array('active' => array(), 'inactive' => array());
$incPopupsJson = array();
echo '<script type="text/javascript"> pty.themes = [];';
foreach ($popups as $p) {
	$popup = new Pty_Theme(null, null, $p->popupid, array('imps' => true));
	if (!$popup->failed) {
		if ($p->status) {
			$temp['active'][] = $popup;
		}
		else {													
			$temp['inactive'][] = $popup;
		}
		echo 'pty.themes[' . $p->popupid . '] = ' . $popup->getJson() . ';'; 
	}
}
foreach ($incPopups as $i => $ip) {
   $popup = new Pty_Theme(null, null, $ip->popupid); 
   if (!$popup->failed) {
	   $incPopups[$i] = $popup;
	   $incPopupsJson[$i] = $popup->getJson();
   }
}
echo '
	pty.incThemes = ' . json_encode($incPopupsJson) . ';
</script>
';
$popups = array_merge($temp['active'], $temp['inactive']);
