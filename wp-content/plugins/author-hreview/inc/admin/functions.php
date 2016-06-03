<?php
	
	/**********************************************************************
    	include global functions that is required by the plugin
    /**********************************************************************/
	
	// Prevent loading this file directly - Busted!
	if ( ! class_exists( 'WP' ) )
	{
		header( 'Status: 403 Forbidden' );
		header( 'HTTP/1.1 403 Forbidden' );
		exit;
	}
	
	
	/**********************************************************************
    	shorten content, example wpar_short_content ($text, $limit);
		@since 1.0.0.6
    /**********************************************************************/
	function wpar_short_content ($text, $limit) {
  		$text_array = explode(' ', $text, $limit - 1);
  		$shot_text = implode(' ', $text_array);
  		// manipulate the string if you need to
  		return $shot_text;
	}


	/**********************************************************************
    get Paid Review Disclaimer
	@since 1.0.1.5
    /**********************************************************************/
	function wpar_getDisclaimer($id) {
		$options = get_option('wpar_options');
		$custom = get_post_custom($id);
		if (get_post_meta($id, "ta_post_review_disclaimer_enable", TRUE) == null ) return '';
		$disclaimer_title = $options['wpar_disclaimer_title'];
		$disclaimer_url = $options['wpar_disclaimer_url'];
		if ( !$disclaimer_url ) return '';
		return '<span><a href="'.$disclaimer_url.'" title="Disclaimer">'.$disclaimer_title.' <span class="icon-exclamation-sign"></span></a></span>';
	}
	