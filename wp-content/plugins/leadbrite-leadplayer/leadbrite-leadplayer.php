<?PHP
/*
Plugin Name: LeadPlayer
Plugin URI: http://www.leadplayer.com/
Description: LeadPlayer allows you to add YouTube videos to your site with an inline opt-in form and a call-to-action.
Version: 1.4.2.1
Author: LeadBrite
Author URI: http://www.leadbrite.com
*/

define('LEADPLAYER_ABS_URL', plugin_dir_url( __FILE__ ));
define('LEADPLAYER_BASE', plugin_dir_path( __FILE__ ));
define('LB_LP_PLUGIN_NAME', 'leadbrite-leadplayer'); // Plugin name
define('LEADPLAYER_KIOSK_MODE_ACTION', false);

require_once('php/admin.class.php');
require_once('php/video.class.php');
require_once('php/control.class.php');

// initialize the plugin
LeadplayerPlugin::setup();

?>