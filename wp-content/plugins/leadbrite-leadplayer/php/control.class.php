<?php

//This class contains all functionality for control/version info of the leadplayer plugin
class LeadplayerPlugin {
    CONST DB_GLOBAL_SETTINGS = 'LeadPlayerGlobalSettings';      // db field for global settings (JSON encoded)
    CONST DB_PLAYER_SETTINGS = 'LeadPlayerPlayerSettings';      // db field for global settings (JSON encoded)
    CONST DB_EMAIL_LISTS = 'LeadPlayerEmailIntegration';        // db field list of all email lists (JSON encoded) [name, provider, html]
    CONST DB_KEY_AVAILABLE_VIDS = 'LeadPlayerAvailableVids';    // db field that holds hashtable of available vids ids (JSON encoded)
    CONST DB_LICENCE_KEY = 'LeadPlayerLicenceKey';              // db field for storing licence key
    CONST DB_LICENCE_TYPE = 'LeadPlayerLicenceType';            // db field for storing licence type
    CONST DB_KIOSK_MODE = 'LeadPlayerKioskMode';                // db field for storing kiosk mode state

    // some variables
    CONST DB_KEY_VIDEO_PREFIX = 'LeadPlayerVid_';               // db field (prefix) for an indidual video data - (second part of key is video ID)
    CONST VIDEO_SHORTCODE = 'leadplayer_vid';

    CONST WP_MIN_VER = '3.3';
    CONST PHP_MIN_VER = '5.2';

    private static $_global_setings = false; // global settings cache
    private static $_player_setings = false; // player settings cache
    private static $_env_supported = true;   // does the current php/WP environment support needed features
    private static $_env_errors = array();   // human-readable list of issues/error with php environment
    private static $_licence_type = false;   // licence info
    private static $_vid_cache = array();    // list of all accessed videos - cached with data
    private static $_vid_ids = false;        // list of all videos ids in db

    // load all video ids
    private static function _get_vid_ids() {
        if (self::$_vid_ids !== false) return self::$_vid_ids;
        $vid_list_str = get_option(self::DB_KEY_AVAILABLE_VIDS , '[]');
        self::$_vid_ids = json_decode($vid_list_str, true);
        return self::$_vid_ids;
    }

    // save all video ids
    private static function _save_vid_ids($ids) {
        self::$_vid_ids = array_values($ids);
        update_option(self::DB_KEY_AVAILABLE_VIDS, json_encode(self::$_vid_ids));
    }

    // simple way to get single video data
    public static function get_video($id) {
        if (array_key_exists($id, self::$_vid_cache)) {
            return self::$_vid_cache[$id];
        }
        $vdata = get_option(self::DB_KEY_VIDEO_PREFIX . $id, false);
        if ($vdata === false) return false;
        $data = json_decode($vdata, true);
        foreach ($data as $key => &$val) {
            if (is_string($val)) $val = stripslashes($val);
        }
        // data migration
        if (!array_key_exists('email_list', $data)) {
            $data['email_list'] = 'default';
        }
        if (!array_key_exists('description', $data)) {
            $data['description'] = '';
        }
        // cta mode migration
        if (!array_key_exists('opt_mode', $data)) {
            if ($data['opt'] === false) {
                $data['opt_mode'] = 'n';
            } else if (is_array($data['opt'])) {
                $data['opt_mode'] = 'y';
            } else {
                $data['opt_mode'] = 'd';
            }
        }
        // opt mode migration
        if (!array_key_exists('cta_mode', $data)) {
            if ($data['cta'] === false) {
                $data['cta_mode'] = 'n';
            } else if (is_array($data['cta'])) {
                $data['cta_mode'] = 'y';
            } else {
                $data['cta_mode'] = 'd';
            }
        }
        // fix old def values
        if ($data['cta'] !== false && is_null($data['cta']['time'])) $data['cta']['time'] = 'end';
        if ($data['opt'] !== false && is_null($data['opt']['skip'])) $data['opt']['skip'] = false;
        // finally cache and return the data
        self::$_vid_cache[$id] = $data;
        return $data;
    }

    // get global settings
    public static function get_global_settings() {
        if (self::$_global_setings !== false) {
            return self::$_global_setings;
        }
        $glob = get_option(self::DB_GLOBAL_SETTINGS, false);
        if ($glob === false) {
            $data = array();
            $data['title'] = 'New Video Title';
            $data['description'] = '';
            $data['yt_url'] = '';
            $data['thumb_image'] = '';
            $data['email_list'] = '';
            $data['width'] = 640;
            $data['height'] = 360;
            $data['autoplay'] = false;
            $data['show_timeline'] = true;
            $data['enable_hd'] = false;
            $data['opt'] = false;
            $data['cta'] = false;
            $data['opt_mode'] = 'n';
            $data['cta_mode'] = 'n';
        } else {
            $data = json_decode($glob, true);
        }
        self::$_global_setings = $data;
        return $data;
    }

    // get player settings
    public static function get_player_settings() {
        if (self::$_player_setings !== false) {
            return self::$_player_setings;
        }
        $playr = get_option(self::DB_PLAYER_SETTINGS, false);
        if ($playr === false) {
            $data = array();
            $data['powered_by'] = false;
            $data['powered_by_link'] = 'http://www.leadplayer.com/';
            $data['branding'] = false;
            $data['use_ga'] = false;
            $data['video_seo'] = false;
            $data['color1'] = '#F5BB0C';
            $data['color2'] = '#1798CD';
            $data['color3'] = '#F5BB0C';
            $data['txt_submit'] = 'SUBMIT';
            $data['txt_play'] = 'PLAY';
            $data['txt_eml'] = 'Your Email Address';
            $data['txt_name'] = 'Your Name';
            $data['txt_invalid_eml'] = 'Please enter a valid email';
            $data['txt_invalid_name'] = 'Please enter your name';
            /// migration code
            // branding
            $b = get_option('LeadPlayerBranding', false);
            if ($b !== false) $data['branding'] = ($b === '1');
            // analytics
            $v = get_option('LeadPlayerUseAnalytics', false);
            if ($v !== false) $data['use_ga'] = ($v === '1');
        } else {
            $data = json_decode($playr, true);
        }
        /// migration code
        if (!array_key_exists('txt_name', $data)) {
            $data['txt_name'] = 'Your Name';
        }
        if (!array_key_exists('powered_by', $data)) {
            $data['powered_by'] = false;
            $data['powered_by_link'] = 'http://www.leadplayer.com/';
        }
        if (!array_key_exists('color3', $data)) {
            $data['color3'] = '#F5BB0C';
        }
        if (!array_key_exists('video_seo', $data)) {
            $seo = false;
            /*if (array_key_exists('obfuscate', $data) && $data['obfuscate'] === true) {
                $seo = false;
            }*/
            $data['video_seo'] = $seo;
        }
        if (!array_key_exists('txt_invalid_name', $data)) {
            $data['txt_invalid_name'] = 'Please enter your name';
        }
        $data['extras'] = self::_extra_branding_options();
        if (!$data['extras']) {
            $data['branding'] = 1;
        }
        self::$_player_setings = $data;
        return $data;
    }


    // get video data for other modules ( return errors )
    public static function get_video_data($id) {
        $ids = self::_get_vid_ids();
        if (!in_array($id, $ids)) {
            return '<b>Invalid LeadPlayer video - ID not found!</b>';
        }
        $vdata = self::get_video($id);
        if ($vdata === false) {
            return '<b>Invalid LeadPlayer video - Video not found!</b>';
        }
        // check youtube
        $yt_id = self::parse_yt_url($vdata['yt_url']);
        if ($yt_id === false) {
            return '<b>Invalid LeadPlayer video - YouTube URL is invalid</b>';
        }
        $vdata['ym'] = $yt_id;
        return $vdata;
    }

    public static function parse_yt_url($url) {
        $url_query = parse_url($url, PHP_URL_QUERY);
        parse_str($url_query, $query_params);
        return array_key_exists('v', $query_params) ? $query_params['v'] : false;
    }

    // get the current environment status
    public static function get_env_status() {
        return array(self::$_env_supported, self::$_env_errors);
    }

    // show extra branding options
    private static function _extra_branding_options() {
        $v = self::get_licence_type();
        return (strpos($v[0], 'a1c83691df6439fbd59d1') !== false);
    }

    public static function get_licence_type() {
        if (self::$_licence_type !== false) return self::$_licence_type;
        self::$_licence_type = get_option(self::DB_LICENCE_TYPE, array('', ''));
        return self::$_licence_type;
    }

    public static function set_licence_type($key, $label) {
        update_option(self::DB_LICENCE_TYPE, array($key, $label));
    }

    // get licence key (string return)
    public static function get_licence_key() {
        return get_option(self::DB_LICENCE_KEY, false);
    }

    // set licence key
    public static function set_licence_key($key) {
        update_option(self::DB_LICENCE_KEY, $key);
    }

    // get kiosk mode (bool return)
    public static function get_kiosk_mode() {
        $km = get_option(self::DB_KIOSK_MODE, false);
        return ($km == 'enabled');
    }

    // set kiosk mode
    public static function set_kiosk_mode($val) {
        update_option(self::DB_KIOSK_MODE, $val ? 'enabled' : 'disabled');
    }

    // DEPRACATED: kept for data migration
    // get the service integration info
    public static function get_service_integration() {
        // migration code
        $s = get_option('LeadPlayerServiceInt', false);
        if ($s !== false) { // existed in DB
            $s = json_decode($s , true);
            $s['form_html'] = stripslashes($s['form_html']);
        }
        return $s;
    }

    // order array of all videos
    public static function get_all_video_ids() {
        return self::_get_vid_ids();
    }

    // get list of all videos currently saved in the system
    public static function get_all_videos() {
        $vs = self::_get_vid_ids();
        $clean = true;
        foreach ($vs as $k => $id) {
            $r = self::get_video($id);
            if ($r === false) {
                // something is wrong, clean unavailable video
                unset(self::$_vid_cache[$id]);
                unset($vs[$k]);
                $clean = false;
            }
        }
        if (!$clean) {
            // save video list without the missing videos
            self::_save_vid_ids($vs);
        }
        return self::$_vid_cache;
    }

    // compatibility with WelcomeGate
    public static function get_video_list() {
        return self::get_all_videos();
    }

    // get single email list data
    public static function get_email_list_data($id) {
        if ($id == 'default') {
            $g = self::get_global_settings();
            $id = $g['email_list'];
        }
        if (empty($id)) {
            return false;
        }
        $ls = self::get_email_lists();
        if (!array_key_exists($id, $ls)) {
            return false;
        }
        $r = $ls[$id];
        $r['hash'] = md5($r['html']);
        return $r;
    }

    // get array of email lists
    public static function get_email_lists() {
        $el = get_option(self::DB_EMAIL_LISTS, false);
        if ($el === false) {
            // lists not set yet, try to load old settings
            $old = self::get_service_integration();
            $el = array();
            // MIGRATION!
            if ($old !== false) {
                // convert old format to new
                $id = self::_gen_new_id();
                $el[$id] = array(
                    'name' => 'My email list',
                    'provider' => $old['form_provider'],
                    'html' => $old['form_html']
                );
                self::save_email_lists($el);
                // create default list
                $g = self::get_global_settings();
                $g['email_list'] = $id;
                self::save_global_settings($g);
            }
            return $el;
        } else {
            return json_decode($el, true);
        }
    }

    // save array of email lists
    public static function save_email_lists($data) {
        update_option(self::DB_EMAIL_LISTS, json_encode($data));
    }

    // add new email list
    public static function add_email_list($name, $provider, $html) {
        $el = self::get_email_lists();
        $id = self::_gen_new_id();
        $el[$id] = array(
            'name' => $name,
            'provider' => $provider,
            'html' => $html
        );
        self::save_email_lists($el);
        return $id;
    }

    // edit email list
    public static function edit_email_list($id, $name, $provider, $html) {
        $el = self::get_email_lists();
        if (!array_key_exists($id, $el)) {
            return false;
        }
        $el[$id] = array(
            'name' => $name,
            'provider' => $provider,
            'html' => $html
        );
        self::save_email_lists($el);
        return true;
    }

    // delete email list
    public static function remove_email_list($id) {
        $el = self::get_email_lists();
        if (!array_key_exists($id, $el)) {
            return 'Invalid list ID. List does not exist!';
        }
        // test existing videos
        $vids = self::get_all_videos();
        foreach ($vids as $k => $v) {
            if (array_key_exists('email_list', $v) && $v['email_list'] == $id) {
                return 'This list can\'t be removed because it is used in this video: ' . $v['title'];
            }
        }
        // test global settings
        $g = self::get_global_settings();
        if (array_key_exists('email_list', $g) && $g['email_list'] == $id) {
            return 'This list can\'t be removed because it is used in global settings as the default list!';
        }
        // finally delete!
        unset($el[$id]);
        self::save_email_lists($el);
        return true;
    }

    // update player settings
    public static function save_player_settings($data) {
        update_option(self::DB_PLAYER_SETTINGS, json_encode($data));
        self::$_player_setings = false;
    }

    // update global settings
    public static function save_global_settings($data) {
        update_option(self::DB_GLOBAL_SETTINGS, json_encode($data));
        self::$_global_setings = false;
    }

    // add new video to db
    public static function add_video($data) {
        $id = self::_gen_new_id();
        $ids = self::_get_vid_ids();
        $ids[] = $id;
        self::_save_vid_ids($ids);
        update_option(self::DB_KEY_VIDEO_PREFIX . $id, json_encode($data));
        self::$_vid_cache[$id] = $data;
        return $id;
    }

    // update a video in the database
    public static function update_video($id, $data) {
        update_option(self::DB_KEY_VIDEO_PREFIX . $id, json_encode($data));
        self::$_vid_cache[$id] = $data;
    }

    // delete a video from the database (and cached video list)
    public static function delete_video($id) {
        $ids = self::_get_vid_ids();
        if (in_array($id, $ids)) {
            delete_option(self::DB_KEY_VIDEO_PREFIX . $id);
            unset(self::$_vid_cache[$id]);
            unset($ids[array_search($id, $ids)]);
            self::_save_vid_ids($ids);
        } else {
            return false;
        }
        return true;
    }

    // clone an existing video
    public static function clone_video($id) {
        $ids = self::_get_vid_ids();
        if (in_array($id, $ids)) {
            $data = self::get_video($id);
            $data['title'] = $data['title'] . ' (clone)';
            self::add_video($data);
        } else {
            return false;
        }
        return true;
    }

    // check that environment supports needed features, ensure jquery on each page, and bind filters/hooks
    public static function setup() {
		global $wp_version;
        // Check wordpress version
        if (version_compare(self::WP_MIN_VER, $wp_version, '>')) {
            self::$_env_supported = false;
            self::$_env_errors[] = 'LeadPlayer requires that you be using a Wordpress minimum version of ' . self::WP_MIN_VER;
        }

		if (version_compare(self::PHP_MIN_VER, phpversion(), '>')) {
            self::$_env_supported = false;
            self::$_env_errors[] = 'LeadPlayer requires that you be using a PHP minimum version of ' . self::PHP_MIN_VER;
        }

        self::_fix_kiosk_mode();
        self::_bind_plugin_filters();
    }

    // handle kiosk mode
    private static function _fix_kiosk_mode() {
        $kiosk = self::get_kiosk_mode();
        if (LEADPLAYER_KIOSK_MODE_ACTION == 'ENABLE') {
            // save to db once
            if ($kiosk === false) {
                self::set_kiosk_mode(true);
            }
        }
        if (LEADPLAYER_KIOSK_MODE_ACTION == 'DISABLE') {
            // save to db once
            if ($kiosk === true) {
                self::set_kiosk_mode(false);
            }
        }
    }

    // generate new unique video id
    private static function _gen_new_id() {
        return strtoupper(uniqid());
    }

	//add filters to put admin menu in backend, read shortcodes, and put needed data in footer/header if present
    private static function _bind_plugin_filters() {
		add_filter('admin_menu', array('LeadplayerAdmin', 'build_admin_menu'));
        if (self::$_env_supported && !is_admin()) { // filters/actions for non-admin pages
            add_shortcode(self::VIDEO_SHORTCODE , array('LeadplayerVideo', 'insert_video'));
        }
    }
}
?>
