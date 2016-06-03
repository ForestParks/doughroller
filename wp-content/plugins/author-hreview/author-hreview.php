<?php
	/*
	Plugin Name: Author hReview
    Plugin URI: http://authorhreview.com
    Description: Add support for hReview and AggregateRating based on schema.org.
    Version: 1.0.1.6
    Author: Hesham Zebida
    Author URI: http://zebida.com
    Last Version update : 22 June 2013
    */


	// Prevent loading this file directly - Busted!
	if ( ! class_exists( 'WP' ) )
	{
		header( 'Status: 403 Forbidden' );
		header( 'HTTP/1.1 403 Forbidden' );
		exit;
	}
	
	// plugin version, used to add version for scripts and styles
	define( 'WPAR_VER', '1.0.1.6' );

	// define plugin URLs, for fast enqueuing scripts and styles
	if ( ! defined( 'WPAR_URL' ) )
		define( 'WPAR_URL', plugin_dir_url( __FILE__ ) );
	define( 'WPAR_INC_URL', trailingslashit( WPAR_URL . 'inc' ) );
	define( 'WPAR_JS_URL', trailingslashit( WPAR_URL . 'js' ) );
	define( 'WPAR_CSS_URL', trailingslashit( WPAR_URL . 'css' ) );
	define( 'WPAR_MISC_URL', trailingslashit( WPAR_INC_URL . 'misc' ) );

	// set some defaults
    $thesisreviewspost_plugin_url = trailingslashit ( WP_PLUGIN_URL . '/' . dirname ( plugin_basename ( __FILE__ ) ) );
    $thesisreviewpost_widget_show = false;
	$pluginname = 'Author hReview';
	$ta_post_cpt = array( 'post', 'page' );
	$ta_post_cpt = get_post_types( array( 'public' => true ) );
	

	// include files
	require_once ('inc/admin/settings.php');							// load admin settings
	// functions
	require_once ('inc/admin/functions.php');							// load global functions
	require_once ('inc/review_meta_box.php');							// load meta box
	require_once ('inc/review_template.php');							// load review template
	// shortcodes
	require_once ('inc/shortcodes/ratingbox_shortcode.php');			// load review shortcode
	require_once ('inc/shortcodes/related_reviews_shortcode.php');		// load related reviews shortcode
	require_once ('inc/shortcodes/recent_reviews_shortcode.php');		// load related reviews shortcode
	// widgets
	require_once ('inc/widgets/review_widget.php');						// load widget
	require_once ('inc/widgets/review_widget_img.php');					// load widget with images
	// other
	require_once ('inc/review_column_preview.php');						// load column preview
	require_once ('inc/review_list.php');								// load reviews list
	require_once ('inc/misc/news.php');									// load blog news


	// set up plugin actions
	add_action( 'admin_init', 'wpar_wp_reviews_active' );				// check if WP Reviews is active
    add_action( 'admin_init', 'wpar_requires_wordpress_version' );		// check WP version 3.0+
	add_action( 'admin_init', 'wpar_admin_init' );						// to register admin styles and scripts
	add_action( 'wp_enqueue_scripts', 'wparp_stylesheet' );				// load styles
	
	// filters
	add_filter( 'plugin_action_links', 'wpar_add_settings_link', 10, 2 );	// add setting link in plugins page
	
	
	
		
	// ------------------------------------------------------------------------
	// check if Author hReview is active
	// ------------------------------------------------------------------------
	function wpar_wp_reviews_active() {
		$xplugin = 'wp-reviews/wp-reviews.php';
		//$plugin = plugin_basename( __FILE__ );
		$plugin = 'author-hreview/author-hreview.php';
		if( is_plugin_active($xplugin) ) {
			deactivate_plugins( $plugin );
			//deactivate_plugins( $xplugin );
			wp_die( "It is not possible to have both (Author hReview and WP Reviews) plugins active at the same time.<br/><br /><span style='color:rgb(44,155,29);'>The Author hReview plugin has been deactivated!</span><br /><br />Back to <a href='".admin_url()."'>WordPress Dashboard</a> > <a href='".admin_url()."plugins.php'>Plugins</a>" );
		}
	}
	
	// ------------------------------------------------------------------------
	// require minimum version of WordPress
	// ------------------------------------------------------------------------
	function wpar_requires_wordpress_version() {
		global $wp_version;
		$plugin = plugin_basename( __FILE__ );
		$plugin_data = get_plugin_data( __FILE__, false );

		if ( version_compare($wp_version, "3.0", "<" ) ) {
			if( is_plugin_active($plugin) ) {
				deactivate_plugins( $plugin );
				wp_die( "'".$plugin_data['Name']."' requires WordPress 3.0 or higher, and has been deactivated! Please upgrade WordPress and try again.<br /><br />Back to <a href='".admin_url()."'>WordPress admin</a>." );
			}
		}
	}

	// ------------------------------------------------------------------------
	// add admin init                                           
	// ------------------------------------------------------------------------
	function wpar_admin_init() {
		
		global $thesisreviewspost_plugin_url;
		$file_dir=get_bloginfo('template_directory');
		
		// *** add scripts here to admin page if required
		$file_dir=get_bloginfo('template_directory');
		
		wp_enqueue_style ( 'wpar-style', $thesisreviewspost_plugin_url."/css/style_admin.css" );
	}

	// ------------------------------------------------------------------------
	// load our css                                           
	// ------------------------------------------------------------------------
	function wparp_stylesheet() {
        $myStyleUrl = plugins_url('css/style.css', __FILE__); // Respects SSL, Style.css is relative to the current file
        $myStyleFile = WP_PLUGIN_DIR . '/author-hreview/css/style.css';
        
		global $thesisreviews_widget_show;
		global $post;
		
		$options = get_option('wpar_options');
		
		// check if file exists, and also if no CSS setting is not true
		if ( file_exists($myStyleFile) && isset($options['wpar_chk_no_css']) != true ) {

            	wp_register_style('wpar', $myStyleUrl);
            	wp_enqueue_style( 'wpar');
        }
    }
	

	// ------------------------------------------------------------------------
	// add settings link to plugins page                                              
	// ------------------------------------------------------------------------
	function wpar_add_settings_link($links, $file) {
		static $this_plugin;
		if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
 
		if ($file == $this_plugin){
			$settings_link = '<a href="admin.php?page=authorhreview">'.__("Settings", "author-hreview").'</a>';
			array_unshift($links, $settings_link);
		}
	
		return $links;
	}



/**
 * Adds [whatever] to the global debug array
 *
 * @param mixed  $input
 * @param string $print_or_export
 *
 * @return array
 */
function wpar_debug( $input, $print_or_export = 'print' )
{
	global $wpar_debug;

	$html = 'print' === $print_or_export ? print_r( $input, true ) : var_export( $input, true );

	return $wpar_debug[] = $html;
}

/**
 * Prints or exports the content of the global debug array at the 'shutdown' hook
 *
 * @return void
 */
function wpar_debug_print()
{
	global $wpar_debug;
	if ( ! $wpar_debug || ( is_user_logged_in() && is_user_admin() ) )
		return;

	$html  = '<h3>' . __( 'Author_hReview Debug:', 'wpar' ) . '</h3><pre>';
	foreach ( $wpar_debug as $debug )
	{
		$html .= "{$debug}<hr />";
	}
	$html .= '</pre>';

	die( $html );
}

add_action( 'shutdown', 'wpar_debug_print', 999 );
