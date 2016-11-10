<?php
/**
 * Author: Ole Fredrik Lie
 * URL: http://olefredrik.com
 *
 * FoundationPress functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

function remove_redirect_guess_404_permalink( $redirect_url ) {
	if ( is_404() && !isset($_GET['p']) )
		return false;
	return $redirect_url;
}
add_filter( 'redirect_canonical', 'remove_redirect_guess_404_permalink' );

// Changing excerpt length
//function new_excerpt_length($length) {
//return 10;
//}
//add_filter('excerpt_length', 'new_excerpt_length');

// Changing excerpt more
//function new_excerpt_more($more) {
//return '...';
//}
//add_filter('excerpt_more', 'new_excerpt_more');

function custom_excerpt($new_length = 10, $new_more = '...') {
  add_filter('excerpt_length', function () use ($new_length) {
    return $new_length;
  }, 999);
  add_filter('excerpt_more', function () use ($new_more) {
    return $new_more;
  });
  $output = get_the_excerpt();
  $output = apply_filters('wptexturize', $output);
  $output = apply_filters('convert_chars', $output);
  $output = '<p>' . $output . '</p>';
  echo $output;
}


/* new thumbnail */
if ( function_exists( 'add_image_size' ) ) {
add_image_size( 'related-thumb', 150, 85, true ); //(cropped)
}
add_filter('image_size_names_choose', 'my_image_sizes');
function my_image_sizes($sizes) {
$addsizes = array(
"related-thumb" => __( "Related Thumb")
);
$newsizes = array_merge($sizes, $addsizes);
return $newsizes;
}


/** Various clean up functions */
require_once( 'library/cleanup.php' );

/** Required for Foundation to work properly */
require_once( 'library/foundation.php' );

/** Register all navigation menus */
require_once( 'library/navigation.php' );

/** Add menu walkers for top-bar and off-canvas */
require_once( 'library/menu-walkers.php' );

/** Create widget areas in sidebar and footer */
require_once( 'library/widget-areas.php' );

/** Return entry meta information for posts */
require_once( 'library/entry-meta.php' );

/** Enqueue scripts */
require_once( 'library/enqueue-scripts.php' );

/** Add theme support */
require_once( 'library/theme-support.php' );

/** Add Nav Options to Customer */
require_once( 'library/custom-nav.php' );

/** Change WP's sticky post class */
require_once( 'library/sticky-posts.php' );

/** If your site requires protocol relative url's for theme assets, uncomment the line below */
// require_once( 'library/protocol-relative-theme-assets.php' );


/** ROBS FUNCTIONS **/

function qs_deposit_table() {
	return "<script type='text/javascript'>
                        function getQueryStringVariable(variable) { var query = window.location.search.substring(1);
                        var vars = query.split('&');
                        for (var i=0;i<vars.length;i++) {
                        var pair = vars[i].split('=');
                        if (pair[0] == variable) {
                        return pair[1];}}}
                        </script>
                        <script type='text/javascript'>
                        var ni_ad_client = '188094';
                        ni_var1 = 'function';
                        var ni_rp = '11';
                        var ni_amt = '49999';
                        var ni_display_width = '600';
                        var ni_rpt = '15';
                        </script>
                        <script id='shmktpl_retrieve' src='http://www.nextinsure.com/ListingDisplay/Retrieve/?cat=11&src=188094' type='text/javascript'></script>";
}

add_shortcode( 'qs_deposit', 'qs_deposit_table' );

function qs_deposit_table_bop() {
	return "<script type='text/javascript'>
		function getQueryStringVariable(variable) { var query = window.location.search.substring(1);
		var vars = query.split('&');
		for (var i=0;i<vars.length;i++) {
		var pair = vars[i].split('=');
		if (pair[0] == variable) {
		return pair[1];}}}
		</script>
		<script type='text/javascript'>
		var ni_ad_client = '610260';
		ni_var1 = 'function';
		var ni_rp = '7';
		var ni_amt = '9999';
		var ni_display_width = '600';
		var ni_rpt = '15';
		</script>
		<script id='shmktpl_retrieve' src='http://www.nextinsure.com/ListingDisplay/Retrieve/?cat=11&src=610260' type='text/javascript'></script>";
}

add_shortcode( 'qs_deposit_bop' , 'qs_deposit_table_bop' );

function graphiq_brokers() {
	return "<div style='width:100%;margin:10px 0;'><iframe src='https://w.graphiq.com/w/didjiNJ3PQF' width='630' height='290' frameborder='0' scrolling='no' style='position:static;vertical-align:top;margin:0 auto;display:block;width:630px !important;max-width:100%;min-height:290px;'></iframe></div>";
}

add_shortcode( 'brokers', 'graphiq_brokers' );

function mortgage_table() {
	return "<script type='text/javascript'>
function getQueryStringVariable(variable) {
var query = window.location.search.substring(1);
var vars = query.split('&');
for (var i=0;i<vars.length;i++) {
var pair = vars[i].split('=');
if (pair[0] == variable) {
return pair[1];}}}

ni_ad_client = '179352';
ni_zc = getQueryStringVariable('zipcode');
ni_str_state_code = getQueryStringVariable('statecode');
ni_rp = getQueryStringVariable('rp');
ni_loanamt = getQueryStringVariable('la');
ni_deflv = 2;
ni_var1 = 'doughroller';
ni_display_width = 800;
</script>

<script type='text/javascript' id='shmktpl_retrieve' src='http://www.nextinsure.com/ListingDisplay/Retrieve/?cat=10&src=179352'></script>";
}

add_shortcode( 'mortgage_rates', 'mortgage_table' );

function banking_deal() {
	return "<p><strong><span style='color: #ff0000;'>Deal of the Day</span></strong>: Earn 1.00% APY on an FDIC-insured savings account at <a href='http://www.doughroller.net/go.php?id=Ally_Bank_Savings'><strong>Ally Bank</strong></a>.</p>";
}

add_shortcode( 'bank_deal', 'banking_deal' );

function auto_insurance() {
	return "<script type='text/javascript'>
function getQueryStringVariable(variable) {
var query = window.location.search.substring(1);
var vars = query.split('&');
for (var i=0;i<vars.length;i++) {
var pair = vars[i].split('=');
if (pair[0] == variable) {
return pair[1];}}}

ni_ad_client = '637150';
ni_res_id = 2;
ni_alt_url = 'https://www.shmktpl.com/search.asp';
ni_zc = getQueryStringVariable('zipcode');
ni_str_state_code = getQueryStringVariable('statecode');
ni_var1 = 'Table';
ni_display_width = 600;
ni_display_height = 1000;
ni_color_border = '';
ni_color_bg = '';
ni_color_link = '';
ni_color_url = '';
ni_color_text = '';
</script>
<script type='text/javascript' id='shmktpl_retrieve' src='http://www.nextinsure.com/ListingDisplay/Retrieve/?cat=1&src=637150'></script>";
}

add_shortcode( 'auto_insurance_table', 'auto_insurance' );

function pl_table() {
	return "<div id='qs-offers' class='qs-listings cachedWidget' style='width:993px'></div>
	<script async='true' data-fallback-tag='' src='https://sl-qmp.quinstreet.com/listings?widgetId=3344'></script>";
}

add_shortcode( 'personal_loans', 'pl_table' );

function cc_bubble_widget() {
	return "<script language='JavaScript'>
var zflag_nid='203'; var zflag_cid='1621830/485404/223057'; var zflag_sid='7273'; var zflag_width='600'; var zflag_height='300'; var zflag_sz='59'; 
 var zflag_param='src%3D637216';
</script>
<script language='JavaScript' src='http://e1.cdn.qnsr.com/jsc/e1/fo.js'></script>
<noscript> <a href='http://o1.qnsr.com/cgi/r?;n=203;c=1621830/485404/223057;s=7273;x=15104;f=201610041357330;u=j;z=TIMESTAMP' target='_blank'><img border='0' width='600' height='200' src='http://o1.qnsr.com/cgi/x?;n=203;c=1621830/485404/223057;s=7273;x=15104;u=j;z=TIMESTAMP' alt='Click here'></a> </noscript>";
}

add_shortcode( 'qs_cc_bubble_widget', 'cc_bubble_widget' );

function bt_banner() {
	return "<script language='JavaScript'>
var zflag_nid='203'; var zflag_cid='1621809/485404/223057'; var zflag_sid='7273'; var zflag_width='300'; var zflag_height='250'; var zflag_sz='9'; 
 var zflag_param='src%3D637217';
</script>
<script language='JavaScript' src='http://e1.cdn.qnsr.com/jsc/e1/fl.js'></script>
<noscript> <a href='http://o1.qnsr.com/cgi/r?;n=203;c=1621809/485404/223057;s=7273;x=2304;f=201610041020230;u=j;z=TIMESTAMP' target='_blank'><img border='0' width='300' height='250' src='http://o1.qnsr.com/cgi/x?;n=203;c=1621809/485404/223057;s=7273;x=2304;u=j;z=TIMESTAMP' alt='Click here'></a> </noscript>";
}

add_shortcode( 'qs_bt_banner', 'bt_banner' );

function life_insurance_table() {
	return "<script type='text/javascript'>
function getQueryStringVariable(variable) {
var query = window.location.search.substring(1);
var vars = query.split('&');
for (var i=0;i<vars.length;i++) {
var pair = vars[i].split('=');
if (pair[0] == variable) {
return pair[1];}}}

ni_ad_client = '637154';
ni_res_id = 2;
ni_alt_url = 'https://www.shmktpl.com/search.asp';
ni_zc = getQueryStringVariable('zipcode');
ni_str_state_code = getQueryStringVariable('statecode');
ni_var1 = 'DR';
ni_display_width = 600;
ni_display_height = 1000;
ni_color_border = '';
ni_color_bg = '';
ni_color_link = '';
ni_color_url = '';
ni_color_text = '';
</script>

<script type='text/javascript' id='shmktpl_retrieve' src='http://www.nextinsure.com/ListingDisplay/Retrieve/?cat=5&src=637154'></script>";
}

add_shortcode( 'life_insurance', 'life_insurance_table' );

function amex_widget() {
	return "<div id='poweredby5003'  style='padding-left:80px;'></div><script type='text/javascript' id='widget_5003' src='//widgets.quinstreet.com/5003/check/117'></script><noscript>This widget requires JavaScript to run. <a href='//widgets.quinstreet.com/termsAndConditions'>Visit Site for more</a>...</noscript>";
}

add_shortcode ( 'amexwidget', 'amex_widget' );
