<?php
if ( defined('WP_USE_THEMES') && constant('WP_USE_THEMES') ) {
	do_action('template_redirect');
	if ( is_feed() ) {
		include(ABSPATH . '/wp-feed.php');
		do_action('wp_end');
		exit;
	} else if ( is_trackback() ) {
		include(ABSPATH . '/wp-trackback.php');
		do_action('wp_end');
		exit;
	} else if ( is_404() && $template = get_404_template() ) {
		include($template);
		do_action('wp_end');
		exit;
	} else if ( is_search() && $template = get_search_template() ) {
		include($template);
		do_action('wp_end');
		exit;
	} else if ( is_home() && $template = get_home_template() ) {
		include($template);
		do_action('wp_end');
		exit;
	} else if ( is_attachment() && $template = get_attachment_template() ) {
		include($template);
		do_action('wp_end');
		exit;
	} else if ( is_single() && $template = get_single_template() ) {
		if ( is_attachment() )
			add_filter('the_content', 'prepend_attachment');
		include($template);
		do_action('wp_end');
		exit;
	} else if ( is_page() && $template = get_page_template() ) {
		if ( is_attachment() )
			add_filter('the_content', 'prepend_attachment');
		include($template);
		do_action('wp_end');
		exit;
	} else if ( is_category() && $template = get_category_template()) {
		include($template);
		do_action('wp_end');
		exit;		
	} else if ( is_author() && $template = get_author_template() ) {
		include($template);
		do_action('wp_end');
		exit;
	} else if ( is_date() && $template = get_date_template() ) {
		include($template);
		do_action('wp_end');
		exit;
	} else if ( is_archive() && $template = get_archive_template() ) {
		include($template);
		do_action('wp_end');
		exit;
	} else if ( is_comments_popup() && $template = get_comments_popup_template() ) {
		include($template);
		do_action('wp_end');
		exit;
	} else if ( is_paged() && $template = get_paged_template() ) {
		include($template);
		do_action('wp_end');
		exit;
	} else if ( file_exists(TEMPLATEPATH . "/index.php") ) {
		if ( is_attachment() )
			add_filter('the_content', 'prepend_attachment');
		include(TEMPLATEPATH . "/index.php");
		do_action('wp_end');
		exit;
	}
} else {
	// Process feeds and trackbacks even if not using themes.
	if ( is_feed() ) {
		include(ABSPATH . '/wp-feed.php');
		do_action('wp_end');
		exit;
	} else if ( is_trackback() ) {
		include(ABSPATH . '/wp-trackback.php');
		do_action('wp_end');
		exit;
	}
}

?>
