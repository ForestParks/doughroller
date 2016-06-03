<?php
	/*
	reviews template
	***************************/


// Prevent loading this file directly - Busted!
	if ( ! class_exists( 'WP' ) )
	{
		header( 'Status: 403 Forbidden' );
		header( 'HTTP/1.1 403 Forbidden' );
		exit;
	}
	
// start rating
add_filter('the_content', 'ta_post_rating');
function ta_post_rating($content) {
	
	global $wp;
	global $post;
	
	//--------------------------------------------------------------
	// include $post->ID in condition so rating box will show only on its own single post or page.
	// @sice 1.0.1.1
	//--------------------------------------------------------------
	if (is_single($post->ID) || is_page($post->ID)) {
		
		$options = get_option('wpar_options');
		$custom = get_post_custom();
		
		if (get_post_meta($post->ID, "ta_post_review_rating", TRUE) == null) { return $content;} else {
		
			$review_author = get_the_author();		// get the author name
			
			$rating = $custom["ta_post_review_rating"][0];
			$fb_rating = $rating;
			//$rating = $rating[0]; // commented this to enable half stars since 0.0.9.1
		
			if(!empty($fb_rating)) {
				
				$fb_rating_star = $fb_rating * 20;
				
				// set options
				$box_width = $options['wpar_box_width'];
				
				// set hide/show reviewed by on review box
				if (isset($options['wpar_chk_reviewed_by_hide']) != '') {$reviewed_by_class = ' ta_magic_review';} else {$reviewed_by_class = '';}
				
				// set hide/show date on review box
				if (isset($options['wpar_chk_rating_date_hide']) != '') {$review_date_class = ' ta_magic_review';} else {$review_date_class = '';}
				
				// set hide/show modified date on review box
				if (isset($options['wpar_chk_rating_modified_date_hide']) != '') {$review_modified_date_class = ' ta_magic_review';} else {$review_modified_date_class = '';}
				
				// set rating box width
				if (isset($options['wpar_box_width']) != '') {$rating_box_width = $options['wpar_box_width'];} else {$rating_box_width = '300';}
				
				// set image alignment
				if (isset($options['wpar_img_alignment']) != '') {$rating_img_alignment = $options['wpar_img_alignment'];} else {$rating_img_alignment = 'left';}
				
				// set more details button value
				if ($options['wpar_more_details_btn_value'] != '') {$rating_box_btn_value = $options['wpar_more_details_btn_value'];} else {$rating_box_btn_value = 'More Details';}
				
				// set button color
				if ($options['wpar_drp_button_color'] != '') {$rating_box_btn_color = $options['wpar_drp_button_color'];} else {$rating_box_btn_color = 'orange';}
				
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

			
			// set image alignment
				if (get_post_meta($post->ID, "ta_post_img_alignment", TRUE)) {
					$rating_img_alignment = get_post_meta($post->ID, "ta_post_img_alignment", TRUE);
				} else {$rating_img_alignment = 'left';} // set default to left
			
			// set button alignment
				if (get_post_meta($post->ID, "ta_post_btn_alignment", TRUE)) {
					$rating_btn_alignment = get_post_meta($post->ID, "ta_post_btn_alignment", TRUE);
				} else {$rating_btn_alignment = 'right';} // set default to right

			//-----------------------------------------------------
			// things are good....
			// let's do it........
			// start our hReview main container div
			
			// $before_box = '<div class="review">';
			// I've replaced this with post class
			//-----------------------------------------------------
			
			$before_box = '<div class="review">';
			$before_box .= '<div itemtype="http://schema.org/Product" itemscope="">';
			$before_box .= '<div itemtype="http://schema.org/Review" itemscope="">';

			
			//-----------------------------------------------------
			// rating box switch starts here
			//-----------------------------------------------------
			
			// let's check specific review settings first
			if (get_post_meta($post->ID, "ta_post_box_template", TRUE)) {
					
				$rating_box_template = get_post_meta($post->ID, "ta_post_box_template", TRUE);
				// remember, we done save the "default" value in custom meta
				// so, sellecting "default", you are telling the plugin to use the template decided in plugin main settings
			
			} else { // if is set to default, check plugin settings
				
			$rating_box_template = isset($options['wpar_drp_rating_box_style']);
			
			if ( isset($options['wpar_drp_rating_box_style']) ) {
				$rating_box_template = $options['wpar_drp_rating_box_style'];
				}
			else {
				$rating_box_template = 'default';
				}
			
			} // now, I think we got the rating box template value, let's switch
    
				switch ( $rating_box_template ) :
        			case 'default' :
            			include ('templates/default.php');	
            			break;
        			case 'wide' :
            			include ('templates/wide.php');
            			break;
        			case 'light' :
            			include ('templates/light.php');
            			break;
    			endswitch;
			
			// here ends our rating box switch
			
			
			//-----------------------------------------------------
			// let's get the thing
			// @since 1.0.1.2
			//-----------------------------------------------------
			$ar_thing_name = get_post_meta($post->ID, 'ta_post_review_name', TRUE);
			$ar_thing = '<div itemtype="http://schema.org/Thing" itemscope="" itemprop="itemReviewed">';
			$ar_thing .= '<meta content="'.$ar_thing_name.'" itemprop="name">';
			$ar_thing .= '</div>';
			
			//-----------------------------------------------------
			// now, let's decide whither to display the rating box
			// of simply hide it
			// in this case we will 
			// add meta into the post
			//-----------------------------------------------------
			
			$hideit = (isset($options['wpar_chk_rating_box_hide']));
			$hidethisreview = get_post_meta($post->ID, 'ta_post_review_box_hide', TRUE);
			
			if ($hideit || $hidethisreview) {	// hide rating box
				
				//check if WP touch plugin is active, add extra styles
				if (class_exists('WPtouchPlugin')) {
					$content = $before_box.'<div class="ta_magic_review" style="position:absolute;left:-9999px;">'.$box.'</div><div itemprop="reviewBody">'.$ar_thing.$content.'</div></div></div></div>';
				} else {
					$content = $before_box.'<div class="ta_magic_review">'.$box.'</div><div itemprop="reviewBody">'.$ar_thing.$content.'</div></div></div></div>';
				}
			} 
			
			else { // show the rating box
				
				$content = $before_box . $box . '<div itemprop="reviewBody">' . $ar_thing . $content . '</div>' . '</div></div></div>';
			
			}
			
			// display box below post
			
			
			$options = get_option('wpar_options');		// get options

			// display rating box below posts
			$wpar_rating_after_single_display = (isset($options['wpar_chk_rating_after_post_display']));
			//if ($options['wpar_chk_rating_after_post_display'] !='') {$wpar_rating_after_single_display = $options['wpar_chk_rating_after_post_display'];}
			
			if ($wpar_rating_after_single_display) {
	
				$box_below = '<div class="clear"></div>';
				$box_below .= '<div id="ta_post_review_after">';
				$box_below .= '<div>' . get_post_meta($post->ID, 'ta_post_review_summary', TRUE) . '</div>';
				$box_below .= '<div class="clear_space"></div>';
				$box_below .= '<ul>';
			
				// check review version field
							if (get_post_meta($post->ID, 'ta_post_review_price', TRUE)) {
			
            					$box_below .= '<li class="price">';
								//$box_below .= '<dt>Price:</dt>';
								$box_below .= '<span>' . get_post_meta($post->ID, 'ta_post_review_price', TRUE) .'</span>';
								//$box_below .= '<span>Only</span>';
								$box_below .= '</li>';
							}
						// end of check review version field
			
				$box_below .= '<li class="after_rating">';
				$box_below .= '<div class="ta_rating result rating">';
				$box_below .= '<div class="result" style="width:' . $fb_rating_star . '%;">' . $rating . '</div>';
				$box_below .= '</div>';
				$box_below .= '<span>editor rating</span>';
				$box_below .= '</li>';
				
							//check if there is a url link
							if (get_post_meta($post->ID, "ta_post_review_url", TRUE)) {
								
								// check button text
								if (get_post_meta($post->ID, 'ta_post_review_button_text', TRUE) == null) {
									$review_text_button = 'More Details';
									}
								else {
									$review_text_button = get_post_meta($post->ID, 'ta_post_review_button_text', TRUE);
									}
			
				$box_below .= '<li class="after_button">';
				$box_below .= '<div class="rating_btn">';
				
				
				// set nofollow links
				if (get_post_meta($post->ID, "ta_post_review_dofollow", TRUE) == null) {
					
					$box_below .= '<a itemprop="url" class="ar_button ar_' . $rating_box_btn_color . '" href="' . get_post_meta($post->ID, 'ta_post_review_url', TRUE) . '" title="' . get_post_meta($post->ID, 'ta_post_review_name', TRUE) . '" target="_blank" rel="nofollow">' . $review_text_button .'</a>';
					}
				else {
					
					$box_below .= '<a itemprop="url" class="ar_button ar_' . $rating_box_btn_color . '" href="' . get_post_meta($post->ID, 'ta_post_review_url', TRUE) . '" title="' . get_post_meta($post->ID, 'ta_post_review_name', TRUE) . '" target="_blank">' . $review_text_button .'</a>';
					}
					
					
				$box_below .= '</div>';
				$box_below .= '</li>';
							} // end of check review button
							
				$box_below .= '</ul>';
				$box_below .= '<div class="clear"></div>';
				$box_below .= '</div>';
				$box_below .= '<div class="clear"></div>';
			
				$content = $content . $box_below; // add the after post box
				}
			}
		}
	}
	return $content; // return the review content
}


//add +snippets to meta tags
function ta_post_add_more_snippet() {
	
	global $wp;
	global $ta_post_cpt;
	$options = get_option('wpar_options');
		
	if (isset($options['wpar_chk_more_snippet']) != '') {
		
		if (is_single() || is_page()) {
		
			global $post;
			$custom = get_post_custom();
		
			if (!get_post_meta($post->ID, "ta_post_review_rating", TRUE) == null) {
				$rating = get_post_meta($post->ID, "ta_post_review_rating", TRUE);
				//$rating = $custom["ta_post_review_rating"][0];
			
				$rating = $rating[0];
		
				// check review image field
				if (!get_post_meta($post->ID, "ta_post_review_image", TRUE) == null) {
					$image = get_post_meta($post->ID, "ta_post_review_image", TRUE);
				}
?>

		<!-- Start Of Script Generated by Author hReview Pro Plugin <?php echo WPAR_VER; ?> by http://authorhreview.com -->
		<meta itemprop="name" content="<?php echo get_post_meta($post->ID, 'ta_post_review_name', TRUE); ?>">
		<meta itemprop="description" content="<?php echo get_post_meta($post->ID, 'ta_post_review_summary', TRUE); ?>">
		<meta itemprop="summary" content="<?php echo get_post_meta($post->ID, 'ta_post_review_summary', TRUE); ?>">
		<meta itemprop="ratingValue" content="<?php echo $rating; ?>">
		<meta itemprop="itemreviewed" content="<?php echo get_post_meta($post->ID, 'ta_post_review_name', TRUE); ?>">
		<meta itemprop="url" content="<?php echo get_post_meta($post->ID, 'ta_post_review_url', TRUE); ?>">
<?php if ($image) { ?>
		<meta property="og:image" content="<?php echo $image; ?>">
<?php } ?>
		<!-- End Of Script Generated by Author hReview Pro Plugin <?php echo WPAR_VER; ?> by http://authorhreview.com -->

<?php		}
		}
	} else { // display only version info ?>

	<!-- This site is optimized with the Author hReview Pro Plugin <?php echo WPAR_VER; ?> by http://authorhreview.com -->

<?php	}
}
add_action('wp_head', 'ta_post_add_more_snippet', 99);

// show the stars on home page and archives
function ta_post_home_page_stars($content) {
	
	if ( is_home() || is_archive() ) {
		
		// define variable
		$rating = '';
		$ID = '';
		$ta_post_review_rating = '';
		$custom = '';
		$rating_star ='';
	
		global $wp;
		global $post;
		
		$custom = get_post_custom($ID);								// get custom data
		
		if (isset($custom["ta_post_review_rating"]) == null) {
			// do nothing
		} else {
			
			$rating = $custom["ta_post_review_rating"][0];			// get rating
			$rating_star = $rating * 20;							// calculate rating
		
		
			if ($rating) { // if it's a review, then show the stars
		
				$home_rating = '<p class="ar_headline_meta_home">';
				$home_rating .= '<span class="ar_rating_home ar_result_home ar_rating_home_span">';
				$home_rating .= '<span class="ar_result_home" style="width:' . $rating_star . '%;" title="' . $rating . '"></span>';
				$home_rating .= '</span>';
				$home_rating .= '</p>';
				
				$content = $home_rating . $content;
			}
		}
	}
		return $content;
}

// ------------------------------------------------------------------------------
// DISPLAY OPTIONS
// ------------------------------------------------------------------------------
	$options = get_option('wpar_options');		// get options

	// display rating stars on home page and archives
	$wpar_rating_home_display = (isset($options['wpar_chk_rating_home_display']));
	if ($wpar_rating_home_display) {
		add_filter('the_content', 'ta_post_home_page_stars');	//display rating stars on home page full content
		add_filter('the_excerpt', 'ta_post_home_page_stars');	//display rating stars on home page excerpt
	}


// ------------------------------------------------------------------------------
// add post class
// ------------------------------------------------------------------------------	
	function wpar_post_class($classes, $class, $post_id) {
  		global $post;
		$review = get_post_meta($post->ID, 'ta_post_review_rating', true);
		if ($review) {
			$classes[] = 'review';
		}
		return $classes;
	}
	//add_filter('post_class','wpar_post_class',10,3);
?>