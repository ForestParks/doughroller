<?php
	/*
	related reviews shortcode
	***************************/


// Prevent loading this file directly - Busted!
	if ( ! class_exists( 'WP' ) )
	{
		header( 'Status: 403 Forbidden' );
		header( 'HTTP/1.1 403 Forbidden' );
		exit;
	}
	
	// add shortcode
	add_shortcode('related_reviews', 'wpar_get_related_reviews');
	
	// start function
	function wpar_get_related_reviews( $atts ) {
 
		extract(shortcode_atts(array(
	    	"num" => '5',
			"images" => '',
			"summary" => ''
		), $atts));
 
		global $wpdb, $post, $table_prefix;
		$related_reviews = '';
		$review_summary = '';
		$url= '';
 
		if ($post->ID) {
 
			$retval = '<ul>';
 
			// Get tags
			$tags = wp_get_post_tags($post->ID);
			$tagsarray = array();
			foreach ($tags as $tag) {
				$tagsarray[] = $tag->term_id;
			}
			$tagslist = implode(',', $tagsarray);
 
			// Do the query
			$q = "
				SELECT p.*, count(tr.object_id) as count
				FROM $wpdb->term_taxonomy AS tt, $wpdb->term_relationships AS tr, $wpdb->posts AS p
				WHERE tt.taxonomy ='post_tag'
					AND tt.term_taxonomy_id = tr.term_taxonomy_id
					AND tr.object_id  = p.ID
					AND tt.term_id IN ($tagslist)
					AND p.ID != $post->ID
					AND p.post_status = 'publish'
					AND p.post_date_gmt < NOW()
				GROUP BY tr.object_id
				ORDER BY count DESC, p.post_date_gmt DESC
				LIMIT $num;";
 
			$related = $wpdb->get_results($q);
 
			if ( $related ) {
				
				// echo before
				$related_reviews .= '<div class="clear"></div>';
				$related_reviews .= '<div id="wpar_related">';
				
				foreach($related as $r) {
					
					// get custom vaules
					$custom = get_post_custom($r->ID);
					
					// check rating
					if ((isset($custom["ta_post_review_name"][0]))) {$rating_name = $custom["ta_post_review_name"][0];}
					else {$rating_name = '';}	// get title
		
					// do the math
					$rating = $custom["ta_post_review_rating"][0];	// get rating
					$rating_star = $rating * 20;	// calculate rating
		
					// get post url
					$url = get_permalink($r->ID);
		
					// start displaying related reviews
        			$related_reviews .= '<div class="wpar_related_item">';
					
					if ($images != '') {
			
						// get screenshot
						if ((isset($custom["ta_post_screenshot"][0]))) {$review_screenshot = $custom["ta_post_screenshot"][0];}
						else {$review_screenshot = '';}
			
						// check review screenshot field
						if ((isset($custom["ta_post_review_image"][0]))) {
								
							$imgurl = $custom["ta_post_review_image"][0];
							//check if there is a url link
							if (get_post_meta($post->ID, "ta_post_review_url", TRUE)) {
								$related_reviews .= '<a href="'.$url.'" title="'.esc_attr(get_the_title($r->ID)).'" target="_blank" rel="nofollow">';
								$related_reviews .= '<img src="' . $imgurl . '" alt="'.esc_attr(get_the_title($r->ID)).'" width="50" height="50" />';
								$related_reviews .= '</a>';
							}
							else { // no link
								$related_reviews .= '<img src="' . $imgurl . '" alt="'.esc_attr(get_the_title($r->ID)).'" width="50" height="50" />';
							}
						}
					// end of check review screenshot field
					}
			
				$related_reviews .= '<p>';
			
        		$related_reviews .= '<a href="'.$url.'" title="'.esc_attr(get_the_title($r->ID)).'">'.$rating_name.'</a>';
            
				$related_reviews .= '<br>';
				$related_reviews .= '<span class="ta_rating result rating ta_widget_rating_img">';
				$related_reviews .= '<span class="result" style="width:' . $rating_star . '%;" title="' . $rating . '"></span>';
				$related_reviews .= '</span>';
				$related_reviews .= '</p>';
				
				//check summary
				if ($summary != '') {
					if ((isset($custom["ta_post_review_summary"][0]))) {$review_summary = $custom["ta_post_review_summary"][0];}
					else {$review_summary = '';}	// get title
				
					$related_reviews .= '<br><div class="clear"></div>';
					$related_reviews .= '<p>';
					$related_reviews .= wpar_short_content ($review_summary, 35). '...';
					$related_reviews .= '</p>';
				}
			
        	// close item div
			$related_reviews .= '</div>';

			}
			} else {
				$related_reviews .= '<li>No related reviews found</li>';
			}
			
			// echo after........
			$related_reviews .= '</div>';
			$related_reviews .= '<div class="clear"></div>';
		
			return $related_reviews;
		}
		return;
	}
?>