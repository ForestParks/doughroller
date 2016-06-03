<?php
	/*
	review shortcode
	***************************/


// Prevent loading this file directly - Busted!
	if ( ! class_exists( 'WP' ) )
	{
		header( 'Status: 403 Forbidden' );
		header( 'HTTP/1.1 403 Forbidden' );
		exit;
	}
	
	// add shortcode
	add_shortcode('ratingbox', 'wpar_get_box');
	
	function wpar_get_box($atts) {
		global $wp;
		global $post;
		$options = get_option('wpar_options');
		$custom = get_post_custom();
		
		if (get_post_meta($post->ID, "ta_post_review_rating", TRUE)) {
			
			$review_author = get_the_author();		// get the author name
			
			$rating = $custom["ta_post_review_rating"][0];
			$fb_rating = $rating;
			//$rating = $rating[0]; // commented this to enable half stars since 0.0.9.1
		
			if(!empty($fb_rating)) {
				
				$fb_rating_star = $fb_rating * 20;
				
				// set options
				$box_width = isset($options['wpar_box_width']);
				
				// set hide/show reviewed by on review box
				if (isset($options['wpar_chk_reviewed_by_hide']) != '') {$reviewed_by_class = ' ta_magic_review';} else {$reviewed_by_class = '';}
				
				// set hide/show date on review box
				if (isset($options['wpar_chk_rating_date_hide']) != '') {$review_date_class = ' ta_magic_review';} else {$review_date_class = '';}
				
				// set hide/show modified date on review box
				if (isset($options['wpar_chk_rating_modified_date_hide']) != '') {$review_modified_date_class = ' ta_magic_review';} else {$review_modified_date_class = '';}
				
				
				// set rating box width
				if (isset($options['wpar_box_width']) != '') {$rating_box_width = $options['wpar_box_width'];} else {$rating_box_width = '300';}
				
				// set more details button value
				if ($options['wpar_more_details_btn_value'] != '') {$rating_box_btn_value = $options['wpar_more_details_btn_value'];} else {$rating_box_btn_value = 'More Details';}
				
				// set button color
				if (isset($options['wpar_drp_button_color']) != '') {$rating_box_btn_color = $options['wpar_drp_button_color'];} else {$rating_box_btn_color = 'orange';}
				
				// set rating box alignment 
				if ($options['wpar_drp_box_align'] != '') {
					
		
					if ($options['wpar_drp_box_align'] == 'right') {
						$ta_box_align_class = "ta_box_right";
					}
					if ($options['wpar_drp_box_align'] == 'left') {
						$ta_box_align_class = "ta_box_left";
					}
					if ($options['wpar_drp_box_align'] == 'none') {
						$ta_box_align_class = "ta_box_align_none";
					}
				}
				else
				{
					$ta_box_align_class = "ta_box_right";		// set to right as default
				}
			}
			
			// set image alignment
				if (get_post_meta($post->ID, "ta_post_img_alignment", TRUE)) {
					$rating_img_alignment = get_post_meta($post->ID, "ta_post_img_alignment", TRUE);
				} else {$rating_img_alignment = 'left';} // set default to left
			
			// set button alignment
				if (get_post_meta($post->ID, "ta_post_btn_alignment", TRUE)) {
					$rating_btn_alignment = get_post_meta($post->ID, "ta_post_btn_alignment", TRUE);
				} else {$rating_btn_alignment = 'right';} // set default to right

		
			// include shortcode template
			include ('ratingbox_shortcode_template.php');
		
		//$beforeb_box = '<div class="review">';
		//$after_box = '</div>';
		return $box;
		}
		
	else {return;}
	}
 
?>