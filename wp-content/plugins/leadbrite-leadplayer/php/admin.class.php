<?php

// This class handles all control administrative/backend stuff
class LeadplayerAdmin {

    CONST LEADPLAYER_MENU_ICON = 'admin/img/menu_icon.png';

    private static $_error_msg = false;     //error message to display
    private static $_success_msg = false;   //success message to display
    private static $_is_licence = false;    //currently looking at a licence key settings?
    private static $_modes = array('y', 'n', 'd');

    // generate the page content
    public static function generate_admin_page() {
        list($env_supported, $env_errors) = LeadplayerPlugin::get_env_status();
        if (!$env_supported)
            return require_once(LEADPLAYER_BASE . 'admin/templates/not_supported.php');

        $valid = LeadplayerPlugin::get_licence_key();
        $kiosk = LeadplayerPlugin::get_kiosk_mode();

        $error_msg = self::$_error_msg;
        $success_msg = self::$_success_msg;

        ob_start();
        if ($valid && !self::$_is_licence) {
            require_once(LEADPLAYER_BASE . 'admin/templates/admin_page.php');
        } else {
            require_once(LEADPLAYER_BASE . 'admin/templates/licence_page.php');
        }
        ob_end_flush();
    }

    // returns https or http
    public static function get_schema() {
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
            return 'https:';
        }
        return 'http:';
    }

    // initialization function, run from load hook for menu page (so only when on menu page)
    public static function initialize() {
        self::_setup_html_assets();
        if (self::$_success_msg === false) {
            if (array_key_exists('licence_key_saved', $_GET)) {
                self::$_success_msg = 'Licence key was successfully saved!';
            } else if (array_key_exists('licence_key_removed', $_GET)) {
                self::$_success_msg = 'Licence key was removed!';
            }
        }
        if (array_key_exists('licence-key-settings', $_GET)) {
            self::$_is_licence = true;
        }
    }

    private static function clean_video_form($form, $action) {
        // fields that need to have an included val in $_POST
        $req = array (
            'width' => 'video width',
            'height' => 'video height'
        );
        if ($action != 'global') {
            $req['title'] = 'video title';
            $req['yt_url'] = 'youTube URL';
        }
        $vals = array();
        foreach ($req as $field => $text) {
            $val = array_key_exists($field, $form) ? $form[$field] : false;
            if ($val === false) return 'Invalid ' . $text;
            $vals[$field] = stripslashes($val);
        }
        if ($action != 'global') {
            $yt_id = LeadPlayerPlugin::parse_yt_url($vals['yt_url']);
            if ($yt_id === false) {
                return 'YouTube URL is invalid!';
            }
        }
        if ($action == 'edit') {
            $video_id = (array_key_exists('video_id', $form) ? $form['video_id'] : false);
            if ($video_id === false) return 'An error occured processing your request. Video ID is missing.';
        }
        // all fields that are optionally included in $form (checkboxes - if not included, assume they are false)
        $opt_fields = array(
            'autoplay',
            'enable_hd',
            'show_timeline'
        );
        foreach ($opt_fields as $field) {
            $vals[$field] = array_key_exists($field, $form) ? true : false;
        }
        $vals['opt_mode'] = $form['opt_mode'];
        if (!in_array($vals['opt_mode'], self::$_modes)) {
            return 'Corrupted form data!';
        }
        $vals['opt'] = false;
        if ($vals['opt_mode'] == 'y') {
            if (in_array($form['optin_time'], array('start', 'end'))) {
                $optime = $form['optin_time'];
            } else {
                $optime = $form['optin_timev'];
            }
            $vals['opt'] = array(
                'time'          => $optime,
                'text1'         => stripslashes($form['optin_text1']),
                'text2'         => stripslashes($form['optin_text2']),
                'url'           => stripslashes($form['optin_url']),
                'name_enabled'  => false,
                'skip'          => false
            );
            if (array_key_exists('include_optin_skip', $form) && $form['include_optin_skip']) {
                $vals['opt']['skip'] = array(
                    'text' => stripslashes($form['optin_skip_text'])
                );
            }
            if (array_key_exists('opt_name_enabled', $form) && $form['opt_name_enabled']) {
                $vals['opt']['name_enabled'] = true;
            }
        }
        $vals['cta_mode'] = $form['cta_mode'];
        if (!in_array($vals['cta_mode'], self::$_modes)) {
            return 'Corrupted form data!';
        }
        $vals['cta'] = false;
        if ($vals['cta_mode'] == 'y') {
            if (in_array($form['cta_time'], array('start', 'end'))) {
                $ctatime = $form['cta_time'];
            } else {
                $ctatime = $form['cta_timev'];
            }
            $vals['cta'] = array(
                'time'        => $ctatime,
                'btext'       => stripslashes($form['cta_btext']),
                'auto_follow' => false,
                'new_window'  => false,
                'url'         => stripslashes($form['cta_url'])
            );
            if (array_key_exists('cta_auto_follow', $form) && $form['cta_auto_follow']) {
                $vals['cta']['auto_follow'] = true;
            }
            if (array_key_exists('cta_new_window', $form) && $form['cta_new_window']) {
                $vals['cta']['new_window'] = true;
            }
        }
        $vals['email_list'] = array_key_exists('email_list', $form) ? stripslashes($form['email_list']) : false;
        if ($action != 'global') {
            // check if default list even exists!
            $g = LeadPlayerPlugin::get_global_settings();
            if (($vals['opt_mode'] == 'd' && $g['opt_mode'] == 'y') || ($vals['opt_mode'] == 'y' && $vals['email_list'] == 'default')) {
                $el = LeadPlayerPlugin::get_email_list_data('default');
                if ($el === false) {
                    return 'Default email list is not set! Please update default opt-in settings.';
                }
            }
            //$vals['thumb_image'] = array_key_exists('thumb_image', $form) ? stripslashes($form['thumb_image']) : false;
            $vals['thumb_image'] = false;
            $vals['description'] = array_key_exists('description', $form) ? stripslashes($form['description']) : '';
        } else {
            $vals['thumb_image'] = false;
            $vals['description'] = '';
        }
        return $vals;
    }

    private static function clean_player_form($form) {
        // fields that need to have an included val in $_POST
        $req = array ();
        $req['color1'] = 'opt-in button color';
        $req['color2'] = 'opt-in button hover color';
        $req['color3'] = 'call to action text color';
        $req['powered_by_link'] = 'powered by logo link';
        $req['txt_submit'] = 'submit button text';
        $req['txt_play'] = 'play button text';
        $req['txt_eml'] = 'email field text';
        $req['txt_name'] = 'name field text';
        $req['txt_invalid_eml'] = 'invalid email message text';
        $req['txt_invalid_name'] = 'invalid name message text';
        $vals = array();
        foreach ($req as $field => $text) {
            $val = array_key_exists($field, $form) ? $form[$field] : false;
            if ($val === false) return 'Invalid ' . $text;
            $vals[$field] = stripslashes($val);
        }
        $opt_fields = array();
        $opt_fields[] = 'branding';
        $opt_fields[] = 'video_seo';
        $opt_fields[] = 'use_ga';
        $opt_fields[] = 'powered_by';
        foreach ($opt_fields as $field) {
            $vals[$field] = array_key_exists($field, $form) ? true : false;
        }
        return $vals;
    }

    private static function clean_email_list_form($form, $mode) {
        $req = array(
            'provider' => 'email service provider',
            'name' => 'list name',
            'code' => 'embed code'
        );
        if ($mode == 'delete') {
            $req = array();
        }
        if ($mode != 'add') {
            $req['id'] = 'list id';
        }
        $vals = array();
        foreach ($req as $field => $text) {
            $val = array_key_exists($field, $form) ? $form[$field] : false;
            if ($val === false) return 'Invalid ' . $text;
            $vals[$field] = stripslashes($val);
        }
        return $vals;
    }

    private static function _setup_html_assets() {
        $v = self::get_url_ver();
        $admin_url = LEADPLAYER_ABS_URL.'admin/';
        wp_register_script('leadplayer_jquery', $admin_url . 'js/jquery_lp.js' . $v);
        wp_register_script('leadplayer_bootstrap', $admin_url . 'js/twitter_bootstrap_lp.js' . $v, array('leadplayer_jquery'));
        wp_register_script('leadplayer_zclip', $admin_url . 'js/zero_lp.js' . $v, array('leadplayer_jquery'));
        wp_register_script('leadplayer_plugin', self::get_schema() . LeadplayerVideo::LEADPLAYER_SCRIPT_PLUGIN_PATH, array('leadplayer_jquery'), null);
        wp_register_script('leadplayer_admin_api', $admin_url . 'js/api_lp.js' . $v, array('leadplayer_jquery', 'media-upload', 'thickbox', 'leadplayer_zclip'));
        wp_register_script('leadplayer_admin', $admin_url . 'js/admin_lp.js' . $v, array('leadplayer_jquery', 'media-upload', 'thickbox', 'leadplayer_zclip', 'leadplayer_admin_api'));
        wp_register_script('leadplayer_color_picker', $admin_url . 'minicolor/mini_colors_lp.js' . $v, array('leadplayer_jquery'));
        wp_enqueue_script('leadplayer_plugin');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('leadplayer_zclip');
        wp_enqueue_script('leadplayer_admin_api');
        wp_enqueue_script('leadplayer_admin');
        wp_enqueue_script('leadplayer_bootstrap');
        wp_enqueue_script('leadplayer_color_picker');
        wp_register_style('leadplayer_bootstrap', $admin_url . 'css/twitter_bootstrap_lp.css' . $v);
        wp_register_style('leadplayer_admin', $admin_url . 'css/admin_lp.css' . $v);
        wp_register_style('leadplayer_color_picker', $admin_url . 'minicolor/mini_colors_lp.css' . $v);
        wp_enqueue_style('thickbox');
        wp_enqueue_style('leadplayer_bootstrap');
        wp_enqueue_style('leadplayer_admin');
        wp_enqueue_style('leadplayer_color_picker');
    }

    // add the needed admin menu to the backend
    public static function build_admin_menu() {
        $hook_suffix = add_menu_page(
			'LeadPlayer',
			'LeadPlayer',
			'manage_options',
			'leadplayer',
			array('LeadplayerAdmin','generate_admin_page'),
			LEADPLAYER_ABS_URL . self::LEADPLAYER_MENU_ICON
		);
        add_action('load-' . $hook_suffix , array('LeadplayerAdmin', 'initialize'));
    }

    function print_js($status, $message, $data=array()) {
        $out = array('result' => $status ? 'ok' : 'ko', 'msg' => $message, 'data' => $data);
        header('Content-Type: application/json');
        echo json_encode($out);
        exit();
    }

    // AJAX ACTIONS

    function user_not_logged_json() {
        return self::print_js(false, 'You are not logged in!');
    }

    function user_not_logged_html() {
        echo '<b style="color:red">You are not logged in inside WordPress!</b> <script type="text/javascript">showErrorMessage("You are not logged in inside WordPress!");window.location.reload();</script>';
        exit();
    }

    // video settings global/add/edit (json)
    function post_video_settings() {
        $action = array_key_exists('video_action', $_POST) ? $_POST['video_action'] : false;
        $preview = array_key_exists('preview', $_POST) ? $_POST['preview'] == 'true' : false;
        if ($action === false) return self::print_js(false, 'An error occured processing your request. (code s1)');
        $res = self::clean_video_form($_POST, $action);
        if (!is_array($res)) {
             return self::print_js(false, $res);
        }
        // only preview the video
        if ($preview) {
            $res['ym'] = LeadplayerPlugin::parse_yt_url($res['yt_url']);
            $vd = LeadplayerVideo::compile_video_data('preview', $res);
            $html = LeadplayerVideo::get_video_html('preview', $vd);
            return self::print_js(true, '', array('video' => $vd, 'html' => $html));
        }
        switch ($action) {
            case 'global':
                LeadplayerPlugin::save_global_settings($res);
                return self::print_js(true, 'Your video settings have been successfully saved!');
            case 'add':
                $vid = LeadplayerPlugin::add_video($res);
                return self::print_js(true, 'Your video has been successfully saved!', array('video_id' => $vid));
            case 'edit':
                $id = $_POST['video_id'];
                LeadplayerPlugin::update_video($id, $res);
                return self::print_js(true, 'Your video has been successfully updated!');
        }
        return self::print_js(false, 'An error occured processing your request. (code s4)');
    }

    // load and flish videos
    function load_all_videos() {
        $all_video_ids = array_reverse(LeadplayerPlugin::get_all_video_ids());
        $all_videos = LeadplayerPlugin::get_all_videos();
        include(LEADPLAYER_BASE . 'admin/templates/ajax/my_videos.php');
    }

    // table with all videos (html)
    function my_videos() {
        self::load_all_videos();
        exit();
    }

    // get template contents on server
    function include_my_videos() {
        ob_start();
        self::load_all_videos();
        $out = ob_get_contents();
        ob_end_clean();
        return explode('<!--BLOCK_SEPARATOR-->', $out);
    }

    // video settings page (html)
    function video_settings() {
        $action = array_key_exists('action', $_POST) ? $_POST['action'] : false;
        if ($action === false) {
            echo 'Invalid action parameter! <script type="text/javascript">showErrorMessage("Invalid action parameter!")</script>';
            exit();
        }
        if ($action == 'video_settings') $video_action = 'global';
        else if ($action == 'add_video') $video_action = 'add';
        else if ($action == 'edit_video') $video_action = 'edit';
        $vid_id = false;
        $g = LeadPlayerPlugin::get_global_settings();
        if ($video_action == 'edit') {
            $vid_id = array_key_exists('video_id', $_POST) ? $_POST['video_id'] : false;
            if ($vid_id === false) {
                echo 'Invalid video id parameter! <script type="text/javascript">showErrorMessage("Invalid video id parameter!")</script>';
                exit();
            }
            $data = LeadPlayerPlugin::get_video($vid_id);
        } else {
            $data = $g;
            if ($video_action == 'add') {
                // use default options by default
                $data['opt_mode'] = 'd';
                $data['cta_mode'] = 'd';
            }
        }
        // fix for old def value
        if ($data['cta'] !== false && is_null($data['cta']['time'])) $data['cta']['time'] = 'end';
        // calculate mins & secs
        if ($data['cta'] !== false && !in_array($data['cta']['time'], array('start', 'end'))) {
            $t = intval($data['cta']['time']);
            $data['cta']['timem'] = floor($t / 60);
            $data['cta']['times'] = $t % 60;
        }
        if ($data['opt'] !== false && !in_array($data['opt']['time'], array('start', 'end'))) {
            $t = intval($data['opt']['time']);
            $data['opt']['timem'] = floor($t / 60);
            $data['opt']['times'] = $t % 60;
        }
        // default values
        $def = self::htmlspecialchars_deep(self::get_def_values($g));
        if ($video_action == 'edit') {
            $def['opt']['name_enabled'] = false;
            $def['opt']['skip'] = false;
            $def['cta']['new_window'] = false;
            $def['cta']['auto_follow'] = false;
        }
        // sanitize
        $data = self::htmlspecialchars_deep($data);
        // the template
        include(LEADPLAYER_BASE . 'admin/templates/ajax/video.php');
        exit();
    }

    function get_def_values($g) {
        $x = array();
        $x['email_list'] = LeadPlayerPlugin::get_email_list_data('default');
        $x['opt'] = array(
            'time'          => 'start',
            'text1'         => 'Join my Mailing List!',
            'text2'         => 'Receive free news & updates',
            'url'           => 'www.leadplayer.com',
            'name_enabled'  => false,
            'skip'          => array('text' => 'skip this step')
        );
        $x['cta'] = array(
            'time'        => 'start',
            'btext'       => 'Instant Access',
            'auto_follow' => false,
            'new_window'  => false,
            'url'         => 'www.leadplayer.com'
        );
        if ($g['opt'] !== false) {
            $x['opt']['time'] = $g['opt']['time'];
            $x['opt']['text1'] = $g['opt']['text1'];
            $x['opt']['text2'] = $g['opt']['text2'];
            $x['opt']['url'] = $g['opt']['url'];
            $x['opt']['skip'] = $g['opt']['skip'];
            $x['opt']['name_enabled'] = $g['opt']['name_enabled'];
        }
        if ($g['cta'] !== false) {
            $x['cta']['time'] = $g['cta']['time'];
            $x['cta']['btext'] = $g['cta']['btext'];
            $x['cta']['url'] = $g['cta']['url'];
            $x['cta']['auto_follow'] = $g['cta']['auto_follow'];
            $x['cta']['new_window'] = $g['cta']['new_window'];
        }
        // default min & sec
        $t = 10;
        $s = 10;
        // calculate mins & secs
        if (!in_array($x['cta']['time'], array('start', 'end'))) {
            $t = intval($x['cta']['time']);
        }
        if (!in_array($x['opt']['time'], array('start', 'end'))) {
            $s = intval($x['opt']['time']);
        }
        $x['opt']['timem'] = floor($s / 60);
        $x['opt']['times'] = $s % 60;
        $x['cta']['timem'] = floor($t / 60);
        $x['cta']['times'] = $t % 60;
        return $x;
    }

    function htmlspecialchars_deep($mixed, $quote_style = ENT_QUOTES, $charset = 'UTF-8') {
        if (is_array($mixed)) {
            foreach($mixed as $key => $value) {
                $mixed[$key] = self::htmlspecialchars_deep($value, $quote_style, $charset);
            }
        } elseif (is_string($mixed)) {
            $mixed = htmlspecialchars(htmlspecialchars_decode($mixed, $quote_style), $quote_style, $charset);
        }
        return $mixed;
    }

    // player settings page (html)
    function player_settings() {
        $data = LeadPlayerPlugin::get_player_settings();
        include(LEADPLAYER_BASE . 'admin/templates/ajax/player.php');
        exit();
    }

    // player settings (json)
    function post_player_settings() {
        $res = self::clean_player_form($_POST);
        if (!is_array($res)) {
             return self::print_js(false, $res);
        }
        LeadplayerPlugin::save_player_settings($res);
        return self::print_js(true, 'Player settings have been successfully saved!');
    }

    function video_embed() {
        $id = array_key_exists('id', $_POST) ? $_POST['id'] : false;
        if ($id === false) {
            echo 'Invalid id parameter! <script type="text/javascript">showErrorMessage("Invalid id parameter!")</script>';
            exit();
        }
        $data = LeadPlayerPlugin::get_video_data($id);
        $vd = LeadplayerVideo::compile_video_data($id, $data);
        $html = LeadplayerVideo::get_video_html($id, $vd);
        $sc = '[' . LeadplayerPlugin::VIDEO_SHORTCODE . ' id="' . $id . '"]';
        self::print_js(true, '', array(
            'video' => $vd,
            'html' => $html,
            'shortcode' => $sc
        ));
    }

    function video_clone() {
        $id = array_key_exists('id', $_POST) ? $_POST['id'] : false;
        if ($id === false) {
            echo 'Invalid id parameter! <script type="text/javascript">showErrorMessage("Invalid id parameter!")</script>';
            exit();
        }
        $r = LeadPlayerPlugin::clone_video($id);
        self::print_js($r, $r ? 'Video successfully cloned!' : 'Error while cloning video!');
    }

    function video_delete() {
        $id = array_key_exists('id', $_POST) ? $_POST['id'] : false;
        if ($id === false) {
            echo 'Invalid id parameter! <script type="text/javascript">showErrorMessage("Invalid id parameter!")</script>';
            exit();
        }
        $r = LeadPlayerPlugin::delete_video($id);
        self::print_js($r, $r ? 'Video successfully deleted!' : 'Error while deleting video!');
    }

    // list of videos (json)
    function email_lists() {
        $data = LeadPlayerPlugin::get_email_lists();
        self::print_js(true, 'Your email lists.', $data);
    }

    // adding or updating email lists (json)
    function post_email_list() {
        $mode = array_key_exists('mode', $_POST) ? $_POST['mode'] : false;
        if ($mode === false) return self::print_js(false, 'An error occured processing your request. (code e1)');
        $res = self::clean_email_list_form($_POST, $mode);
        if (!is_array($res)) {
            return self::print_js(false, $res);
        }
        switch ($mode) {
            case 'add':
                $id = LeadPlayerPlugin::add_email_list($res['name'], $res['provider'], $res['code']);
                return self::print_js(true, 'Email list successfully added!', $id);
                break;
            case 'edit':
                $r = LeadPlayerPlugin::edit_email_list($res['id'], $res['name'], $res['provider'], $res['code']);
                if ($r) {
                    return self::print_js(true, 'Email list successfully saved!', $res);
                } else {
                    return self::print_js(false, 'An error occured processing your request. (code e2)');
                }
                break;
            case 'delete':
                $r = LeadPlayerPlugin::remove_email_list($res['id']);
                if ($r === true) {
                    $g = LeadPlayerPlugin::get_global_settings();
                    $id = $g['email_list'];
                    return self::print_js(true, 'Email list successfully removed!', $id);
                } else {
                    return self::print_js(false, $r . ' (code e3)');
                }
                break;
        }
        return self::print_js(false, 'An error occured processing your request. (code e4)');
    }


    function lp_key_validator() {
        $key = trim($_POST['lp_licence_key']);
        $result = self::lb_api_call('update-check', $key);
        $response = $result[0];
        if ($response === false) {
            self::print_js(false, 'Unable to validete the licence key! Message: ' . $result[1], false);
        } else {
            LeadplayerPlugin::set_licence_key($key);
            self::print_js(true, 'Licence key validated!', true);
        }
    }

    function lp_key_remove() {
        LeadplayerPlugin::set_licence_key(NULL);
        LeadplayerPlugin::set_licence_type(NULL, '');
        self::print_js(true, 'Licence key removed!');
    }

    public static function register_actions() {
        // ajax service for checking key validity
        add_action('wp_ajax_lp_key_validator', array('LeadPlayerAdmin', 'lp_key_validator'));
        add_action('wp_ajax_lp_key_remove', array('LeadPlayerAdmin', 'lp_key_remove'));
        // video list
        add_action('wp_ajax_my_videos', array('LeadPlayerAdmin', 'my_videos'));
        // video settings
        add_action('wp_ajax_edit_video', array('LeadPlayerAdmin', 'video_settings'));
        add_action('wp_ajax_add_video', array('LeadPlayerAdmin', 'video_settings'));
        add_action('wp_ajax_video_settings', array('LeadPlayerAdmin', 'video_settings'));
        add_action('wp_ajax_post_video_settings', array('LeadPlayerAdmin', 'post_video_settings'));
        add_action('wp_ajax_video_clone', array('LeadPlayerAdmin', 'video_clone'));
        add_action('wp_ajax_video_delete', array('LeadPlayerAdmin', 'video_delete'));
        add_action('wp_ajax_video_embed', array('LeadPlayerAdmin', 'video_embed'));
        // player settings
        add_action('wp_ajax_player_settings', array('LeadPlayerAdmin', 'player_settings'));
        add_action('wp_ajax_post_player_settings', array('LeadPlayerAdmin', 'post_player_settings'));
        // email lists
        add_action('wp_ajax_email_lists', array('LeadPlayerAdmin', 'email_lists'));
        add_action('wp_ajax_post_email_list', array('LeadPlayerAdmin', 'post_email_list'));
        // silent check for update
        add_action('wp_ajax_silent_update_check', array('LeadPlayerAdmin', 'silent_update_check'));
        // show warning if not logged in:
        add_action('wp_ajax_nopriv_update_check', array('LeadPlayerAdmin', 'user_not_logged_json'));
        add_action('wp_ajax_nopriv_player_settings', array('LeadPlayerAdmin', 'user_not_logged_html'));
        add_action('wp_ajax_nopriv_post_player_settings', array('LeadPlayerAdmin', 'user_not_logged_json'));
        add_action('wp_ajax_nopriv_lp_key_validator', array('LeadPlayerAdmin', 'user_not_logged_json'));
        add_action('wp_ajax_nopriv_lp_key_remove', array('LeadPlayerAdmin', 'user_not_logged_json'));
        add_action('wp_ajax_nopriv_my_videos', array('LeadPlayerAdmin', 'user_not_logged_html'));
        add_action('wp_ajax_nopriv_edit_video', array('LeadPlayerAdmin', 'user_not_logged_html'));
        add_action('wp_ajax_nopriv_add_video', array('LeadPlayerAdmin', 'user_not_logged_html'));
        add_action('wp_ajax_nopriv_video_settings', array('LeadPlayerAdmin', 'user_not_logged_html'));
        add_action('wp_ajax_nopriv_post_video_settings', array('LeadPlayerAdmin', 'user_not_logged_json'));
        add_action('wp_ajax_nopriv_video_embed', array('LeadPlayerAdmin', 'user_not_logged_json'));
        add_action('wp_ajax_nopriv_video_clone', array('LeadPlayerAdmin', 'user_not_logged_json'));
        add_action('wp_ajax_nopriv_video_delete', array('LeadPlayerAdmin', 'user_not_logged_json'));
        add_action('wp_ajax_nopriv_email_lists', array('LeadPlayerAdmin', 'user_not_logged_json'));
        add_action('wp_ajax_nopriv_post_email_list', array('LeadPlayerAdmin', 'user_not_logged_json'));
        // end ajax
        // plugin update information
        add_filter('plugins_api', array('LeadplayerAdmin', '_update_information'), 9, 3);
        // exclude from official updates
        add_filter('http_request_args', array('LeadplayerAdmin', '_updates_exclude'), 5, 2);
        // check for update twice a day (same schedule as normal WP plugins)
        add_action('lp_check_event', array('LeadplayerAdmin', '_check_for_update'));
        add_filter("transient_update_plugins", array('LeadplayerAdmin', 'pro_check_update'));
        add_filter("site_transient_update_plugins", array('LeadplayerAdmin', 'pro_check_update'));
        // check and schedule next update
        if (!wp_next_scheduled('lp_check_event')) {
            wp_schedule_event(current_time('timestamp'), 'twicedaily', 'lp_check_event');
        }
        // remove cron task upon deactivation
        register_deactivation_hook(LEADPLAYER_BASE . 'leadbrite-leadplayer.php', array('LeadplayerAdmin', '_check_deactivation'));
    }

    function _check_deactivation() {
        wp_clear_scheduled_hook('lp_check_event');
    }

    /**
     * Exclude from WP updates
     **/
    public static function _updates_exclude($r, $url) {
        if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check'))
            return $r; // Not a plugin update request. Bail immediately.
        $plugins = unserialize($r['body']['plugins']);
        unset($plugins->plugins[LB_LP_PLUGIN_NAME]);
        unset($plugins->active[array_search(LB_LP_PLUGIN_NAME, $plugins->active)]);
        $r['body']['plugins'] = serialize($plugins);
        return $r;
    }

    function get_url_ver() {
        return '?lp-ver=' . self::_plugin_get('Version');
    }

    function get_full_ver() {
        $v = LeadplayerPlugin::get_licence_type();
        return self::_plugin_get('Version') . ' ' . ($v[1]);
    }

    function _plugin_get($i) {
        if (!function_exists('get_plugins'))
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        $plugin_folder = get_plugins('/' . LB_LP_PLUGIN_NAME);
        return $plugin_folder[LB_LP_PLUGIN_NAME . '.php'][$i];
    }

    function silent_update_check() {
        $result = self::_check_for_update(true);
        $response = $result[0];
        if ($response === false) {
            self::print_js(false, 'Error while checking for update. Message: ' . $result[1], false);
        }
        $nv = $response->version;
        $url = $response->url;
        $current_version = self::_plugin_get('Version');
        if ($current_version == $nv || version_compare($current_version, $nv, '>')) {
            self::print_js(true, 'You are using the latest version of LeadPlayer!', false);
        }
        $plugin_file = 'leadbrite-leadplayer/leadbrite-leadplayer.php';
        $upgrade_url = wp_nonce_url('update.php?action=upgrade-plugin&amp;plugin=' . urlencode($plugin_file), 'upgrade-plugin_' . $plugin_file);
        $message = 'There is a new version of LeadPlayer available! ( ' . $nv . ' )<br>You can <a href="' . $upgrade_url . '">update</a> to the latest version automatically or <a href="' . $url . '">download</a> the update and install it manually.';
        self::print_js(true, $message, true);
    }

    function pro_check_update($option, $cache=true){
        $response = get_site_transient('leadbrite_leadplayer_latest_version');
        if (!$response) {
            $result = self::lb_api_call('update-check');
            $response = $result[0];
            if ($response === false) {
                return $option;
            }
        }
        if (!isset($response->version)) {
            return $option;
        }
        $current_version = self::_plugin_get('Version');
        if ($current_version == $response->version) return $option;
        if (version_compare($current_version, $response->version, '>')) {
            return $option; // you have the latest version
        }
        $plugin_path = 'leadbrite-leadplayer/leadbrite-leadplayer.php';
        if(empty($option->response[$plugin_path]))
            $option->response[$plugin_path] = new stdClass();
        $option->response[$plugin_path]->url = self::_plugin_get('AuthorURI');
        $option->response[$plugin_path]->slug = LB_LP_PLUGIN_NAME;
        $option->response[$plugin_path]->package = $response->url;
        $option->response[$plugin_path]->new_version = $response->version;
        $option->response[$plugin_path]->id = "0";
        return $option;
    }

    function _check_for_update($full = false) {
        if (defined('WP_INSTALLING')) return false;
        $result = self::lb_api_call('update-check');
        $response = $result[0];
        if ($full === true) {
            return $response;
        }
        if ($response === false) {
            return false;
        }
        $current_version = self::_plugin_get('Version');
        if ($current_version == $response->version) return false;
        if (version_compare($current_version, $response->version, '>')) {
            return false; // you have the latest version
        }
        return $response->version;
    }

    function _update_information($false, $action, $args) {
        // Check if this plugins API is about this plugin
        if ($args->slug != LB_LP_PLUGIN_NAME) {
            return $false;
        }
        $result = self::lb_api_call('info');
        $response = $result[0];
        if ($response === false) {
            return false;
        }
        $response->slug = LB_LP_PLUGIN_NAME;
        $response->plugin_name = LB_LP_PLUGIN_NAME;
        return $response;
    }

    function lb_api_call($service, $licence_key=NULL) {
        $url = 'http://leadbrite.appspot.com/service/leadplayer/' . $service . '/';
        $current_ver = self::_plugin_get('Version');
        if ($licence_key == NULL) {
            $licence_key = LeadplayerPlugin::get_licence_key();
        }
        $v = LeadplayerPlugin::get_licence_type();
        $licence_type = $v[0];
        $response = wp_remote_post(
            $url,
            array(
                'method' => 'POST',
                'timeout' => 70,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'body' => array(
                    'version' => $current_ver,
                    'licence_key' => $licence_key,
                    'licence_type' => $licence_type
                ),
                'cookies' => array()
            )
        );
        if (is_wp_error($response)) {
            return array(false, $response->get_error_message());
        }
        if (isset($response['response']['code'])) {
            $code_char = substr($response['response']['code'], 0, 1);
        } else {
            $code_char = '5';
        }
        if ($code_char == '5' || $code_char == '4') {
            return array(false, $response['response']['message']);
        }
        $res = json_decode($response['body'], true);
        if (!is_array($res)) {
            return array(false, 'Unexpected response. Failed to decode JSON.');
        }
        if (isset($res['result']) && $res['result'] == 'ko') {
            return array(false, $res['message']);
        }
        $r = new stdClass;
        foreach ($res as $key => $val) {
            $r->$key = $val;
        }
        if (isset($r->licence_type)) {
            LeadplayerPlugin::set_licence_type($r->licence_type, $r->licence_label);
        }
        if ($service == 'update-check') {
            set_site_transient('leadbrite_leadplayer_latest_version', $r, 60 * 60 * 12);
        }
        return array($r, 'Everything is good!');
    }

}
LeadplayerAdmin::register_actions();
?>