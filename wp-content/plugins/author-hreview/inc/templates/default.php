<?php
	/*
	default review template
	***************************/


	// Prevent loading this file directly - Busted!
	if ( ! class_exists( 'WP' ) )
	{
		header( 'Status: 403 Forbidden' );
		header( 'HTTP/1.1 403 Forbidden' );
		exit;
	}
	
			// rating box start here
			$box = '<div class="ta_rating_container_default ' . $ta_box_align_class . '" style="width:' . $rating_box_width .'px;">';
			$box .= '<div id="ta_rating">';
			$box .= '<div>';
			$box .= '<div>';
			$box .= 'Review of: <span class="item fn" itemprop="name">';
			
			//check if there is a url link
			if (get_post_meta($post->ID, "ta_post_review_url", TRUE)) {
				
			// set nofollow links
				if (get_post_meta($post->ID, "ta_post_review_dofollow", TRUE) == null) {
					
					$box .= '<a rel="nofollow" href="' . get_post_meta($post->ID, 'ta_post_review_url', TRUE) .'" title="' . get_post_meta($post->ID, 'ta_post_review_name', TRUE) .'" target="_blank">' . get_post_meta($post->ID, 'ta_post_review_name', TRUE) . '</a>';
					}
				else {
					
					$box .= '<a href="' . get_post_meta($post->ID, 'ta_post_review_url', TRUE) .'" title="' . get_post_meta($post->ID, 'ta_post_review_name', TRUE) .'" target="_blank">' . get_post_meta($post->ID, 'ta_post_review_name', TRUE) . '</a>';			
					}
			
			}
			else { // if there is no url link, just print the name
				$box .= get_post_meta($post->ID, 'ta_post_review_name', TRUE);
			}
			
			$box .= '</span>';
			$box .= '</div>';
			$box .= '<div class="clear"></div>';
								
						$box .= '<dl>';

						// check review type field
						if (get_post_meta($post->ID, 'ta_post_review_type', TRUE) && get_post_meta($post->ID, 'ta_post_review_author', TRUE)) {
				
                		$box .= '<dt>' . get_post_meta($post->ID, 'ta_post_review_type', TRUE) . ':	</dt>';
				
						}
						else
						{ // use "Product" as a default if author only is used
				
                			if (get_post_meta($post->ID, 'ta_post_review_author', TRUE)) $box .= '<dt>Product by: </dt>';
				
						}
						// end of check review type field
						
							// check review url field
							if (get_post_meta($post->ID, 'ta_post_review_author', TRUE)) {
								$box .= '<dd>';
								$box .= '<span>' . get_post_meta($post->ID, 'ta_post_review_author', TRUE) . '</span>';
								$box .= '</dd>';
								$box .= '</dl>';
								$box .= '<div class="clear"></div>';
							}
							// end of check review url field
							
						// check review version field
							if (get_post_meta($post->ID, 'ta_post_review_version', TRUE)) {
			
            					$box .= '<dt>Version:</dt>';
								$box .= '<dd>' . get_post_meta($post->ID, 'ta_post_review_version', TRUE) .'</dd>';
								$box .= '<div class="clear"></div>';
							}
						// end of check review version field
						
						// check review version field
							if (get_post_meta($post->ID, 'ta_post_review_price', TRUE)) {
			
            					$box .= '<dt>Price:</dt>';
								$box .= '<dd>' . get_post_meta($post->ID, 'ta_post_review_price', TRUE) .'</dd>';
								$box .= '<div class="clear"></div>';
							}
						// end of check review price field
						
						// check review screenshot field
							if (get_post_meta($post->ID, 'ta_post_review_image', TRUE)) {
								
								$image = stripslashes (get_post_meta( $post->ID, 'ta_post_review_image', TRUE ));
								
								//check if there is a url link
								if (get_post_meta($post->ID, "ta_post_review_url", TRUE)) {
									$box .='<a href="' . get_post_meta($post->ID, 'ta_post_review_url', TRUE) . '" title="' . get_post_meta($post->ID, 'ta_post_review_name', TRUE) . '" target="_blank" rel="nofollow">';
									$box .= '<img itemprop="image" class="review_screenshot photo" src="' . $image . '" alt="' . get_post_meta($post->ID, 'ta_post_review_name', TRUE) .'" />';
									$box .= '</a>';
								}
								else { // no link
									$box .= '<img itemprop="image" class="review_screenshot photo" src="' . $image . '" alt="' . get_post_meta($post->ID, 'ta_post_review_name', TRUE) .'" />';
								}
								
								$box .= '<div class="clear"></div>';
							}
						// end of check review screenshot field
            
            		$box .= '<div class="clear_space"></div><div class="hr"><hr /></div>';
            		$box .= '<div class="'.$reviewed_by_class.'">Reviewed by: <span class="reviewer author byline vcard hcard">';
					$box .= '<span class="author me fn" itemprop="author">' . $review_author .'</span></span></div>';	
					
					$box .= '<dl>';
					$box .= '<dt>Rating:</dt>';
					$box .= '<dd>';
					$box .= '<div class="ta_rating result rating" itemtype="http://schema.org/Rating" itemscope="" itemprop="reviewRating">';
					$box .= '<meta content="1" itemprop="worstRating">';
					$box .= '<meta content="' . $fb_rating . '" itemprop="ratingValue">';
					$box .= '<meta content="5" itemprop="bestRating">';
        			//$box .= '<meta content="1" itemprop="ratingCount">';
					
					$box .= '<div class="result" style="width:' . $fb_rating_star . '%;" title="' . $rating . '">' . $rating . '</div>';
					
					$box .= '</div>';
					$box .= '</dd></dl>';
					$box .= '<div class="clear"></div>';
            
            		$box .= '<div class="ta_headline_meta'.$review_date_class.'">On <span class="dtreviewed rating_date">';
            		$box .= '<span class="" title="' . get_the_time(get_option('date_format')) . '">' . get_the_time(get_option('date_format')) . '</span></span></div>';
        
					$box .= '<div class="ta_headline_meta'.$review_modified_date_class.'">Last modified:';
					$box .= '<span class="dtmodified rating_date" itemprop="dateModified">';
					$box .= '<span class="updated" title="'. get_the_modified_time(get_option('date_format')) . '">' . get_the_modified_time(get_option('date_format')) . '</span></span></div>';
					
					// disclaimer
					// @since 1.0.1.5
					if (wpar_getDisclaimer($post->ID) !=''){
						$box .= '<span>'.wpar_getDisclaimer($post->ID).'</span>';
					}
					
					$box .= '<div class="clear_space"></div>';
            
            				$box .= '<div class="hr"><hr /></div>';
            
    						$box .= '<h4>Summary:</h4>';
							$box .= '<div class="ta_description summary" itemprop="description">';
							$box .= '<p><span>' . get_post_meta($post->ID, 'ta_post_review_summary', TRUE) . '</span></p>';
							$box .= '</div>';
							$box .= '</div>';
							
					//check if there is a url link
					if (get_post_meta($post->ID, "ta_post_review_url", TRUE)) {
				
							// check if review button enabled
							//if (!get_post_meta($post->ID, 'ta_post_review_button_enable', TRUE) == null) {
								
								// check button text
								if (get_post_meta($post->ID, 'ta_post_review_button_text', TRUE) == null) {
									$review_text_button = $rating_box_btn_value; // use default 'More Details', or global
									}
								else {
									$review_text_button = get_post_meta($post->ID, 'ta_post_review_button_text', TRUE);
									}
							
			
				
							$box .= '<div class="rating_btn" align="'.$rating_btn_alignment.'">';
							
					// set nofollow links
					if (get_post_meta($post->ID, "ta_post_review_dofollow", TRUE) == null) {
					
						$box .= '<a itemprop="url" class="ar_button ar_' . $rating_box_btn_color . '" href="' . get_post_meta($post->ID, 'ta_post_review_url', TRUE) . '" title="' . get_post_meta($post->ID, 'ta_post_review_name', TRUE) . '" target="_blank" rel="nofollow">' . $review_text_button . '</a>';
					}
					else {
					
						$box .= '<a itemprop="url" class="ar_button ar_' . $rating_box_btn_color . '" href="' . get_post_meta($post->ID, 'ta_post_review_url', TRUE) . '" title="' . get_post_meta($post->ID, 'ta_post_review_name', TRUE) . '" target="_blank">' . $review_text_button . '</a>';
					}
							
						$box .= '</div>';
					
						$box .= '<div class="clear"></div>';
					} // end of review button check
				
			 	// end of display button
							
					$box .= '</div>';
					$box .= '</div>';
					// here ends our rating box
?>