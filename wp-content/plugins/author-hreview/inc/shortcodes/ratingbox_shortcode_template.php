<?php
	/*
	light review template
	***************************/


	// Prevent loading this file directly - Busted!
	if ( ! class_exists( 'WP' ) )
	{
		header( 'Status: 403 Forbidden' );
		header( 'HTTP/1.1 403 Forbidden' );
		exit;
	}
	
			// rating box start here
			$box = '<div class="clear"></div>';
			$box .= '<div class="ta_shortcode_container">';
			$box .= '<div id="ta_shortcode">';
			
			
			// check review screenshot field
							if (get_post_meta($post->ID, 'ta_post_review_image', TRUE)) {
									
								$image = stripslashes (get_post_meta( $post->ID, 'ta_post_review_image', TRUE ));
			
								//check if there is a url link
								if (get_post_meta($post->ID, "ta_post_review_url", TRUE)) {
									$box .='<a href="' . get_post_meta($post->ID, 'ta_post_review_url', TRUE) . '" title="' . get_post_meta($post->ID, 'ta_post_review_name', TRUE) . '" target="_blank" rel="nofollow">';
									$box .= '<img class="photo '.$rating_img_alignment.'" src="' . $image . '" alt="' . get_post_meta($post->ID, 'ta_post_review_name', TRUE) .'" />';
									$box .= '</a>';
								}
								else { // no link
									$box .= '<img class="photo '.$rating_img_alignment.'" src="' . $image . '" alt="' . get_post_meta($post->ID, 'ta_post_review_name', TRUE) .'" />';
								}
								
							}
						// end of check review screenshot field
			
			$box .= '<ul>';
						
			// review item
			$box .= '<li><span class="fn"><b>';
			
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
				$box .= get_post_meta($post->ID, 'ta_post_review_name', TRUE); // nothing require as no URL
			}
				
			$box .= '</b></span></li>';
			
			
			// check review screenshot field
							if (get_post_meta($post->ID, 'ta_post_review_image', TRUE)) {
									
								$image = stripslashes (get_post_meta( $post->ID, 'ta_post_review_image', TRUE ));
								
								//$box .= '<li class="review_screenshot">';
								//$box .= '<img class="photo" src="' . $image . '" alt="" />';
								//$box .= '</li>';
							}
						// end of check review screenshot field
						

			// rating stars			
			$box .= '<li>';
					
					$box .= '<span style="float:left; margin-right:10px;"><b>Rating:</b> </span>';
					//$box .= '<dd>';
					$box .= '<span class="ta_rating result rating">';
					$box .= '<meta content="1">';
					$box .= '<meta content="' . $fb_rating . '">';
					$box .= '<meta content="5">';
					$box .= '<span class="result" style="width:' . $fb_rating_star . '%;" title="' . $rating . '">' . $rating . '</span>';
					
					$box .= '</span>';
					//$box .= '</dd>';
					//$box .= '</dl>';
			
			$box .= '</li>';
						
						
						// review type

						// check review type field
						if (get_post_meta($post->ID, 'ta_post_review_type', TRUE) && get_post_meta($post->ID, 'ta_post_review_author', TRUE)) {
				
                		$box .= '<li>';
						$box .= '<b>'.get_post_meta($post->ID, 'ta_post_review_type', TRUE) . '</b>: ';
				
						}
						else
						{ // use "Product" as a default if author only is used
				
							if (get_post_meta($post->ID, 'ta_post_review_author', TRUE)) $box .= '<li><b>Product by:</b> ';
				
						}
						// end of check review type field
						
							// check review url field
							if (get_post_meta($post->ID, 'ta_post_review_author', TRUE)) {
								$box .= '<span>' . get_post_meta($post->ID, 'ta_post_review_author', TRUE) . '</span>';
								$box .= '</li>';
							}
							else {$box .= '</li>';}
						// end of check review url field
			
						
							
						// check review version field
							if (get_post_meta($post->ID, 'ta_post_review_version', TRUE)) {
			
            					$box .= '<li>';	
								$box .= '<b>Version</b>: ';
								$box .= '<span>' . get_post_meta($post->ID, 'ta_post_review_version', TRUE) .'</span>';
								$box .= '</li>';
							}
						// end of check review version field
				
						
						// check review version field
							if (get_post_meta($post->ID, 'ta_post_review_price', TRUE)) {
			
            					$box .= '<li>';
								$box .= '<b>Price</b>: ';
								$box .= '<span>' . get_post_meta($post->ID, 'ta_post_review_price', TRUE) .'</span>';
								$box .= '</li>';
							}
						// end of check review price field
			
			$box .= '<li class="'.$reviewed_by_class.'">';				

            			// reviewer
						$box .= '<b>Reviewed by</b>: ';
						$box .= '<span class="reviewer author byline vcard hcard">';
						$box .= '<span class="author me fn">' . $review_author .'</span>';
						$box .= '</span>';

			$box .= '</li>';
			
            
            		// published on
					$box .= '<li class="'.$review_date_class.'">';
					$box .= '<div class="ta_headline_meta">On <span class="dtreviewed rating_date">';
            		$box .= '<span class="" title="' . get_the_time(get_option('date_format')) . '">' . get_the_time(get_option('date_format'));
					$box .= '</span></span>';
					$box .= '</div>';
        			
					$box .= '</li>';
					
					// modified
					$box .= '<li class="'.$review_modified_date_class.'">';
					
					$box .= '<div class="ta_headline_meta">Last modified:';
					$box .= '<span class="dtmodified rating_date">';
					$box .= '<span class="updated" title="'. get_the_modified_time(get_option('date_format')) . '">' . get_the_modified_time(get_option('date_format')) . '</span></span>';
					$box .= '</div>';
					
					
					$box .= '</li>';
					
					$box .= '</ul>';
					// review button
						
					//check if there is a url link			
					if (get_post_meta($post->ID, "ta_post_review_url", TRUE)) {
								
								// check button text
								if (get_post_meta($post->ID, 'ta_post_review_button_text', TRUE) == null) {
									$review_text_button = $rating_box_btn_value; // user default 'More Details', or global
									}
								else {
									$review_text_button = get_post_meta($post->ID, 'ta_post_review_button_text', TRUE);
									}
			
								//////$box .= '<li>';
								$box .= '<div class="clear"></div>';
								$box .= '<div class="" align="'.$rating_btn_alignment.'">';
										
				// set nofollow links
				if (get_post_meta($post->ID, "ta_post_review_dofollow", TRUE) == null) {
					
					$box .= '<a class="ar_button ar_' . $rating_box_btn_color . '" href="' . get_post_meta($post->ID, 'ta_post_review_url', TRUE) . '" title="' . get_post_meta($post->ID, 'ta_post_review_name', TRUE) . '" target="_blank" rel="nofollow">' . $review_text_button . '</a>';
					}
				else {
					
					$box .= '<a class="ar_button ar_' . $rating_box_btn_color . '" href="' . get_post_meta($post->ID, 'ta_post_review_url', TRUE) . '" title="' . get_post_meta($post->ID, 'ta_post_review_name', TRUE) . '" target="_blank">' . $review_text_button . '</a>';
					}

							$box .= '</div>';
							
							////////$box .= '</li>';
					
							$box .= '<div class="clear"></div>';
						}
							// end of review button check
					
					// end of review button
					
					////////$box .= '</ul>';

				
				$box .= '</div>';
				$box .= '<div class="clear"></div>';    
            				//$box .= '<div class="hr"><hr /></div>';
			
			// close the rating box div
			$box .= '</div>';
    		
							//$box .= '<div class="clear"><br /></div>';
							//$box .= '<h4>Review Summary:</h4>';
							//$box .= '<div class="ta_description summary" itemprop="description">';
							//$box .= '<p><span>' . get_post_meta($post->ID, 'ta_post_review_summary', TRUE) . '</span></p>';
							//$box .= '</div>';
													
			$box .= '<div class="clear"></div>';
					
					// here ends our rating box

?>