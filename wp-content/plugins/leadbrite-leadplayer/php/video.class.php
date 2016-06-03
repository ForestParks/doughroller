<?php
// This class handles all control of adding the videos to the wordpress pages
class LeadplayerVideo {

    // shortcode for adding a leadplayer vid to the page
    CONST VIDEO_SHORTCODE = 'leadplayer_vid';
    // path to local copy of plugin js (relative to plugin dir root)
    CONST LEADPLAYER_SCRIPT_PLUGIN_PATH = '//s3.amazonaws.com/cdn.leadbrite.com/leadplayer/r0038/js/leadplayer.js';
    // list of all onpage leadplayer video data, keyed by video ID
    private static $_onpage_vids = array();
    // nl html replace order
    private static $_order = array("\r\n", "\n", "\r");

    // add a specified leadplayer video to this page (callback from shortcode insertion)
    public static function insert_video($atts) {
        $video_id = $atts['id'];
        $vdata = LeadplayerPlugin::get_video_data($video_id);
        $data = self::compile_video_data($video_id, $vdata);
        return self::get_video_html($video_id, $data, true);
    }

    public static function get_video_html($video_id, $data, $full_linking = false) {
        if (is_string($data)) return $data; // we have some problems here
        // allow the same video to be inserted multiple times
        if (array_key_exists($video_id, self::$_onpage_vids)) {
            $video_id += base_convert(rand(10e16, 10e20), 10, 36);
        }
        // save video to cache
        self::$_onpage_vids[$video_id] = $data;
        $g = LeadplayerPlugin::get_player_settings();
        // youtube iframe for SEO
        $yti = '';
        if ($g['video_seo']) {
            // thumb
            $thumb = 'https://img.youtube.com/vi/' . $data['ym'] . '/hqdefault.jpg';
            if ($data['thumbnail']) {
                $thumb = $data['thumbnail'];
            }
            // link
            $yt_suffix = $data['ym'] . '?loop=0&amp;autoplay=0&amp;';
            $yt_suffix .= 'controls=' . ($data['show_timeline'] ? '1' : '0');
            $yt_suffix .= '&amp;modestbranding=1&amp;showsearch=0&amp;rel=0&amp;showinfo=0&amp;hd=' . ($data['enable_hd'] ? '1' : '0');
            $yt_link = 'http://www.youtube.com/embed/' . $yt_suffix;
            $yt_link_ssl = 'https://www.youtube.com/embed/' . $yt_suffix;
            $yti .= '<meta itemprop="embedUrl" content="' . $yt_link . '" />';
            $yti .= '<meta itemprop="name" content="' . $data['title'] . '" />';
            $yti .= '<meta itemprop="description" content="' . $data['description'] . '" />';
            $yti .= '<meta itemprop="width" content="' . $data['width'] . '" />';
            $yti .= '<meta itemprop="height" content="' . $data['height'] . '" />';
            $yti .= '<meta itemprop="thumbnailUrl" content="' . $thumb . '" />';
            // fallback yt iframe
            $yti .= '<iframe type="text/html" width="' . $data['width'] . '" height="' . $data['height'] . '" src="' . $yt_link_ssl . '" frameborder="0" allowfullscreen></iframe>';
        }
        $schema = ($full_linking) ? LeadPlayerAdmin::get_schema() : '';
        // finish and flush the html
        $h = '<!-- LeadPlayer video embed code start [ video: ' . $video_id . ' ] -->';
        $h .= '<div><script type="text/javascript" src="' . $schema . self::LEADPLAYER_SCRIPT_PLUGIN_PATH . '"></script></div>';
        $h .= '<div id="leadplayer_video_element_' . $video_id . '" style="width:' . $data['width'] . 'px;height:' . $data['height'] . 'px"';
        if ($g['video_seo']) $h .= ' itemscope itemtype="http://schema.org/VideoObject"';
        $h .= '>' . $yti . '</div>';
        $h .= '<div><script type="text/javascript">';
        $h .= 'jQLeadBrite("#leadplayer_video_element_' . $video_id . '").leadplayer(false, "' . base64_encode(json_encode($data)) . '"' . ");";
        $h .= '</script></div>';
        $h .= '<!-- LeadPlayer video embed code end [ video: ' . $video_id . ' ] -->';
        return $h;
    }

    public static function compile_video_data($id, $data) {
        if (is_string($data)) {
            return $data;
        }
        // video data array
        $d = array();
        // list options
        $list_data = LeadPlayerPlugin::get_email_list_data($data['email_list']);
        if ($list_data === false) {
            $data['opt_mode'] = 'n';
        }
        $ver = 'WP Plugin ' . LeadPlayerAdmin::get_full_ver();
        // player settings
        $p = LeadplayerPlugin::get_player_settings();
        $d['ga'] = $p['use_ga'] ? true : false;
        $d['overlay'] = $p['branding'] ? true : false;
        $d['powered_by'] = $p['powered_by'] ? true : false;
        $d['powered_by_link'] = htmlspecialchars($p['powered_by_link'], ENT_COMPAT);
        $d['color1'] = htmlspecialchars($p['color1'], ENT_COMPAT);
        $d['color2'] = htmlspecialchars($p['color2'], ENT_COMPAT);
        $d['color3'] = htmlspecialchars($p['color3'], ENT_COMPAT);
        $d['txt_submit'] = htmlspecialchars($p['txt_submit']);
        $d['txt_play'] = htmlspecialchars($p['txt_play']);
        $d['txt_eml'] = htmlspecialchars($p['txt_eml']);
        $d['txt_name'] = htmlspecialchars($p['txt_name']);
        $d['txt_invalid_eml'] = htmlspecialchars($p['txt_invalid_eml']);
        $d['txt_invalid_name'] = htmlspecialchars($p['txt_invalid_name']);
        $d['lp_source'] = htmlspecialchars($ver, ENT_COMPAT);
        // global options
        $g = LeadplayerPlugin::get_global_settings();
        if ($data['opt_mode'] == 'd') {
            if ($g['opt_mode'] == 'y') {
                $data['opt_mode'] = 'y';
                $data['opt'] = $g['opt'];
            } else {
                $data['opt_mode'] = 'n';
                $data['opt'] = false;
            }
        } else if ($data['opt_mode'] == 'n') {
            $data['opt'] = false;
        }
        if ($data['cta_mode'] == 'd') {
            if ($g['cta_mode'] == 'y') {
                $data['cta_mode'] = 'y';
                $data['cta'] = $g['cta'];
            } else {
                $data['cta_mode'] = 'n';
                $data['cta'] = false;
            }
        } else if ($data['cta_mode'] == 'n') {
            $data['cta'] = false;
        }
        // video settings
        $d['id'] = $id;
        $d['width'] = intval($data['width']);
        $d['height'] = intval($data['height']);
        $d['thumbnail'] = htmlspecialchars($data['thumb_image'], ENT_COMPAT);
        $d['title'] = htmlspecialchars($data['title'], ENT_COMPAT);
        $d['description'] = htmlspecialchars($data['description'], ENT_COMPAT);
        $d['autoplay'] = $data['autoplay'] ? true : false;
        $d['show_timeline'] = $data['show_timeline'] ? true : false;
        $d['enable_hd'] = $data['enable_hd'] ? true : false;
        if ($data['opt'] === false) {
            $d['opt'] = false;
        } else {
            $d['opt'] = array();
            if ($data['opt']['time'] == 'start' || $data['opt']['time'] == 'end') {
                $d['opt']['time'] = $data['opt']['time'];
            } else {
                $d['opt']['time'] = intval($data['opt']['time']);
            }
            $d['opt']['text1'] = htmlspecialchars($data['opt']['text1'], ENT_COMPAT);
            $d['opt']['text2'] = htmlspecialchars($data['opt']['text2'], ENT_COMPAT);
            $d['opt']['url'] = htmlspecialchars($data['opt']['url'], ENT_COMPAT);
            if ($data['opt']['skip'] === false) {
                $d['opt']['skip'] = false;
            } else {
                $d['opt']['skip'] = array('text' => htmlspecialchars($data['opt']['skip']['text'], ENT_COMPAT));
            }
            $d['opt']['form_provider'] = htmlspecialchars($list_data['provider'], ENT_COMPAT);
            $d['opt']['form_html'] = utf8_encode(htmlentities(str_replace(self::$_order, '', trim($list_data['html']))));
            $d['opt']['form_hash'] = htmlentities($list_data['hash']);
            $d['opt']['name_enabled'] = $data['opt']['name_enabled'] ? true : false;
        }
        if ($data['cta'] === false) {
            $d['cta'] = false;
        } else {
            $d['cta'] = array();
            if ($data['cta']['time'] == 'start' || $data['cta']['time'] == 'end') {
                $d['cta']['time'] = $data['cta']['time'];
            } else {
                $d['cta']['time'] = intval($data['cta']['time']);
            }
            $d['cta']['btext'] = htmlspecialchars($data['cta']['btext'], ENT_COMPAT);
            $d['cta']['url'] = htmlspecialchars($data['cta']['url'], ENT_COMPAT);
            $d['cta']['auto_follow'] = $data['cta']['auto_follow'] ? true : false;
            $d['cta']['new_window'] = $data['cta']['new_window'] ? true : false;
        }
        $d['ym'] = htmlspecialchars($data['ym'], ENT_COMPAT);
        return $d;
    }

    public static function write_wp_page_footer_elements() {
        // backward compatibility
    }
}
?>