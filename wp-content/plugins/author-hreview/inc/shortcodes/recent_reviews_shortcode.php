<?php
	/*
	recent reviews shortcode
	***************************/


// Prevent loading this file directly - Busted!
	if ( ! class_exists( 'WP' ) )
	{
		header( 'Status: 403 Forbidden' );
		header( 'HTTP/1.1 403 Forbidden' );
		exit;
	}
	
	// add shortcode
	add_shortcode('recent_reviews', 'wpar_get_recent_reviews');
	
	// start function
	function wpar_get_recent_reviews($atts, $content = null) {
		
		//$img ='';
		extract(shortcode_atts(array(
                "num" => '5',
                //"cat" => '',
				"images" => '',
				"summary" => ''
        ), $atts));
		
		global $wp, $post;
		$options = get_option('wpar_options');
		
		$recent_reviews = '';
		$review_summary = '';
		$url = '';
		$order = 'date';
		
		$r = new WP_Query(array(
									'posts_per_page' => $num,
									'no_found_rows' => true,
									'post_status' => 'publish',
									'meta_key'=> 'ta_post_review_rating',
									//'category' => $cat,
									'ignore_sticky_posts' => true,
									'orderby'=> $order,
									'order'=> 'DESC',
								));
								
		if ($r->have_posts()) :
		
		// echo before
		$recent_reviews .= '<div class="clear"></div>';
		$recent_reviews .= '<div id="wpar_related">';
		
		while ($r->have_posts()) : $r->the_post();
		
		// get custom vaules
		$custom = get_post_custom();
		
		// check rating
		if ((isset($custom["ta_post_review_name"][0]))) {$rating_name = $custom["ta_post_review_name"][0];}
		else {$rating_name = '';}	// get title
		
		// do the math
		$rating = $custom["ta_post_review_rating"][0];	// get rating
		$rating_star = $rating * 20;	// calculate rating
		
		// get post url
		$url = get_permalink($post->ID);
		
		// start displaying related reviews
        $recent_reviews .= '<div class="wpar_related_item">';
        	

			if ($images != '') {
			
				// get screenshot
				if ((isset($custom["ta_post_screenshot"][0]))) {$review_screenshot = $custom["ta_post_screenshot"][0];}
				else {$review_screenshot = '';}
			
				// check review screenshot field
				if ((isset($custom["ta_post_review_image"][0]))) {
								
					$imgurl = $custom["ta_post_review_image"][0];
					
					//check if there is a url link
					if (get_post_meta($post->ID, "ta_post_review_url", TRUE)) {
						$recent_reviews .= '<a href="'.$url.'" title="'.esc_attr(get_the_title($r->ID)).'" target="_blank" rel="nofollow">';
						$recent_reviews .= '<img src="' . $imgurl . '" alt="'.esc_attr(get_the_title($r->ID)).'" width="50" height="50" />';
						$recent_reviews .= '</a>';
					}
					else { // no link
						$recent_reviews .= '<img src="' . $imgurl . '" alt="'.esc_attr(get_the_title($r->ID)).'" width="50" height="50" />';
					}
								
				}
			// end of check review screenshot field
			}
			
			$recent_reviews .= '<p>';
			
        	$recent_reviews .= '<a href="'.$url.'" title="'.esc_attr(get_the_title()).'">'.$rating_name.'</a>';
            
			$recent_reviews .= '<br>';
			$recent_reviews .= '<span class="ta_rating result rating ta_widget_rating_img">';
			$recent_reviews .= '<span class="result" style="width:' . $rating_star . '%;" title="' . $rating . '"></span>';
			$recent_reviews .= '</span>';
			$recent_reviews .= '</p>';
			
			//check summary
			if ($summary != '') {
				if ((isset($custom["ta_post_review_summary"][0]))) {$review_summary = $custom["ta_post_review_summary"][0];}
				else {$review_summary = '';}	// get title
			
				$recent_reviews .= '<div class="clear"></div>';
				$recent_reviews .= '<p>';
				$recent_reviews .= wpar_short_content ($review_summary, 35). '...';
				$recent_reviews .= '</p>';
			}
			
        // close item div
		$recent_reviews .= '</div>';
		
		
		
		endwhile;
		
		// echo after........
		$recent_reviews .= '</div>';
		$recent_reviews .= '<div class="clear"></div>';
		
		endif;
		
		// reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();
		
		return $recent_reviews;		
	}
?>