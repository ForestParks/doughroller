<?php

# Get the parameters from the request
$tag = $_GET['t'];
$page = $_GET['p'];
$search = $_GET['s'];

# Open the database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'doughroll');
mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysql_select_db(DB_NAME);

# Use the tag to get the redirect target.
$result = mysql_query("select url from wp_goto_map where tag = '$tag'");
if (is_null($result) || mysql_num_rows($result) < 1) {
  $result = mysql_query("select offer_url from cc_box where alias = '$tag'");
}
if (is_null($result) || mysql_num_rows($result) < 1) {
  $target = null;
} else {
  $row = mysql_fetch_row($result);
  $target = $row[0];
}

mysql_close();

# If there is no target, send the browser back to the previous page.
if (is_null($target)) {
  $target = $_SERVER['HTTP_REFERER'];
}

# Replace tokens in the target
$target = str_replace('[TAG]', rawurlencode($tag), $target);
$target = str_replace('[PAGE]', rawurlencode($page), $target);
$target = str_replace('[SEARCH]', rawurlencode($search), $target);

# Perform the redirect
header("Location: {$target}");

?>
