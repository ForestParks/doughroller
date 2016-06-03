<?php
/******************* review meta box ********************/


	// Prevent loading this file directly - Busted!
	if ( ! class_exists( 'WP' ) )
	{
		header( 'Status: 403 Forbidden' );
		header( 'HTTP/1.1 403 Forbidden' );
		exit;
	}
	
	// set meta prefix	
	$wpar_prefix = 'ta_post_';
	global $ta_post_cpt;
	
	// get options
	// since 1.0.1.0
	$options = get_option('wpar_options');
	// set more details button value
	if ($options['wpar_more_details_btn_value'] != ''){$rating_box_btn_value = $options['wpar_more_details_btn_value'];} else {$rating_box_btn_value = 'More Details';}

	

// meta box
$wpar_meta_box = array(
	'id' => 'wpar_plugin_meta_box',
	'title' => 'Author Review Settings',
	'page' => 'post',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		
		
		// Open tab 1	//////////////////////////////////////////////
		array(
			'name'		=> 'Main',
			'id'		=> $wpar_prefix . 'tab1',
			'type'		=> 'start',
			'std' => ''
			),
		
		
		// Rating Select Box
		array(
			'name'		=> 'Select Rating (out of 5)',
			'id'		=> $wpar_prefix . 'review_rating',
			'type'		=> 'rating',
			// Array of 'key' => 'value' pairs for select box
			'options'	=> array(
				''		=> 'No Rating',
				'1'		=> '1',
				'1.5'	=> '1.5',
				'2'		=> '2',
				'2.5'	=> '2.5',
				'3'		=> '3',
				'3.5'	=> '3.5',
				'4'		=> '4',
				'4.5'	=> '4.5',
				'5'		=> '5'
				
			),
			// Select multiple values, optional. Default is false.
			'multiple'	=> false,
			// Default value, can be string (single value) or array (for both single and multiple values)
			// I've set that to 'No Rating' to avoid php notices
			'std'		=> array( 'No Rating' ),
			'desc'		=> 'Rate it!'
		),
		array(
			'name'	=> 'Screenshots (plupload)',
			'desc'	=> 'Screenshots of problems, warnings, etc.',
			'id'	=> "{$wpar_prefix}screenshot2",
			'type'	=> 'plupload_image',
			'max_file_uploads' => 1,
		),
		array(
			'name' => 'Name',
			'desc' => 'Enter name of the product you are reviewing (<span class="required">required</span>)',
			'id' => $wpar_prefix . 'review_name',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => 'Review Summary',
			'desc' => 'Enter review summary (<span class="required">required</span>)',
			'id' => $wpar_prefix . 'review_summary',
			'type' => 'textarea',
			'std' => ''
		),
		
		array(
			'name' => 'Disclaimer',
			'desc' => 'Is it a paid review?.',
			'id' => $wpar_prefix . 'review_disclaimer_enable',
			'type' => 'checkbox',
			'std' => ''
		),
		
		// Close tab 1
		array(
			'name'		=> 'Main',
			'id'		=> $wpar_prefix . 'tab1',
			'type'		=> 'close',
			'std' => ''
			),
		
		// Open tab 2	//////////////////////////////////////////////
		array(
			'name'		=> 'Details',
			'id'		=> $wpar_prefix . 'tab2',
			'type'		=> 'start',
			'std' => ''
			),
		
		
		array(
			'name' => 'Type',
			'desc' => 'Enter product type (example: product by, software by, plugin by, book by, author, ...etc) 
						(note: fill the author field below if you want to use option!)',
			'id' => $wpar_prefix . 'review_type',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => 'Author Name',
			'desc' => 'Enter the product author name here',
			'id' => $wpar_prefix . 'review_author',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => 'Version',
			'desc' => 'Enter product version',
			'id' => $wpar_prefix . 'review_version',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => 'Price',
			'desc' => 'Enter price of reviewed item, you may use currency sign before price (example: &#36;, &euro;, &pound; ..etc.)',
			'id' => $wpar_prefix . 'review_price',
			'type' => 'text',
			'std' => ''
		),
		
		// Close tab 2
		array(
			'name'		=> 'Details',
			'id'		=> $wpar_prefix . 'tab2',
			'type'		=> 'close',
			'std' => ''
			),	
			
		// Open tab 3	//////////////////////////////////////////////
		array(
			'name'		=> 'Display',
			'id'		=> $wpar_prefix . 'tab3',
			'type'		=> 'start',
			'std' => ''
			),
		
		array(
			'name'		=> 'Rating box template',
			'id'		=> $wpar_prefix . 'box_template',
			'type'		=> 'select',
			// Array of 'key' => 'value' pairs for select box
			'options'	=> array(
				''		=> 'default',
				'wide'	=> 'wide',
				'light'	=> 'light'
			),
			// Select multiple values, optional. Default is false.
			'multiple'	=> false,
			// Default value, can be string (single value) or array (for both single and multiple values)
			// I've set that to 'default' to avoid php notices
			'std'		=> array( 'default' ),
			'desc'		=> 'Change rating box template will override the main plugin settings.<br>(note: Leave this to "default" to use the template you set in the main plugin settings)'
		),
		
		array(
			'name' => 'Hide Rating box on this review?',
			'desc' => 'Tick to hide the rating box for this review.<br>(note: by default the rating box will appear on your review, you can force hide the box on all reviews within the plugin settings page)',
			'id' => $wpar_prefix . 'review_box_hide',
			'type' => 'checkbox',
			'std' => ''
		),
		
		array(
			'name' => 'Display rating box with shortcode?',
			'desc' => 'Simply use <span class="required" style="font-style:normal;"><b>[ratingbox]</b></span> shortcode ( anywhere in the post/page content) to display the rating box.<br>(note: shortcode template will be used to display the box)',
			'id' => $wpar_prefix . 'use_shortocde',
			'type' => 'info',
			'std' => ''
		),
		
		array(
			'name'	=> '',
			'desc'	=> '',
			'id'	=> $wpar_prefix . 'spacer',
			'type'	=> 'spacer',
			'std' => ''
		),
		
		array(
			'name'	=> 'Screenshot',
			'desc'	=> 'Choose Thumbnail (150 × 150) for best fit!',
			'id'	=> $wpar_prefix . 'review_image', // here is a special case as we use id in js.
			'type'	=> 'image',
			'std' => ''
		),
		
		array(
			'name'		=> 'Set image alignment to',
			'id'		=> $wpar_prefix . 'img_alignment',
			'type'		=> 'select',
			// Array of 'key' => 'value' pairs for select box
			'options'	=> array(
				''		=> 'default',
				'left'	=> 'left',
				'right'	=> 'right'
			),
			// Select multiple values, optional. Default is false.
			'multiple'	=> false,
			// Default value, can be string (single value) or array (for both single and multiple values)
			// I've set that to 'default' to avoid php notices
			'std'		=> array( 'default' ),
			'desc'		=> '(default: left)'
		),
			
		// Close tab 3
		array(
			'name'		=> 'Display',
			'id'		=> $wpar_prefix . 'tab3',
			'type'		=> 'close',
			'std' => ''
			),
		
		
		// Open tab 4	//////////////////////////////////////////////
		array(
			'name'		=> 'Link',
			'id'		=> $wpar_prefix . 'tab4',
			'type'		=> 'start',
			'std' => ''
			),
			
		array(
			'name' => 'URL',
			'desc' => 'Enter review URL or Affiliate Link (<span class="required">including http://</span>), This will enable a button on your review.',
			'id' => $wpar_prefix . 'review_url',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => 'Button Text',
			'desc' => 'Change the button text for this review. (default is: '.$rating_box_btn_value.')',
			'id' => $wpar_prefix . 'review_button_text',
			'type' => 'text',
			'std' => ''
		),
		
		array(
			'name'		=> 'Set button alignment to',
			'id'		=> $wpar_prefix . 'btn_alignment',
			'type'		=> 'select',
			// Array of 'key' => 'value' pairs for select box
			'options'	=> array(
				''		=> 'default',
				'left'	=> 'left',
				'center' => 'center',
				'right'	=> 'right'
			),
			// Select multiple values, optional. Default is false.
			'multiple'	=> false,
			// Default value, can be string (single value) or array (for both single and multiple values)
			// I've set that to 'default' to avoid php notices
			'std'		=> array( 'default' ),
			'desc'		=> '(default: right)'
		),
			
		/*array(
			'name' => 'Enable Button?',
			'desc' => 'Tick to enable the button for this review. (Note: <span class="required">Must include a URL</span> for the button to show)',
			'id' => $wpar_prefix . 'review_button_enable',
			'type' => 'checkbox',
			'std' => ''
		),*/
		array(
			'name' => 'Dofollow links?',
			'desc' => 'By default, links has rel="nofollow", tick to remove <em>nofollow</em> attribute from the link. (not recommended for affiliate links)',
			'id' => $wpar_prefix . 'review_dofollow',
			'type' => 'checkbox',
			'std' => ''
		),
		
		// Close tab 4
		array(
			'name'		=> 'Link',
			'id'		=> $wpar_prefix . 'tab4',
			'type'		=> 'close',
			'std' => ''
		),
	)
);



// Add meta box
function wpar_add_box() {
	global $wpar_meta_box;
	
	add_meta_box($wpar_meta_box['id'], $wpar_meta_box['title'], 'wpar_show_box', $wpar_meta_box['page'], $wpar_meta_box['context'], $wpar_meta_box['priority']);
	
	add_meta_box($wpar_meta_box['id'], $wpar_meta_box['title'], 'wpar_show_box', 'page', $wpar_meta_box['context'], $wpar_meta_box['priority']);
	
	
	// add to custom post types
    $post_types = get_post_types();
    foreach ( $post_types as $post_type ) {
		// just in case: don't include in core post types
		if ( $post_type != 'link' || $post_type !='attachment' || $post_type !='revision') {
        	add_meta_box( $wpar_meta_box['id'], $wpar_meta_box['title'], 'wpar_show_box', $post_type, $wpar_meta_box['context'], $wpar_meta_box['priority'] );
		}
	}
}

// Callback function to show fields in meta box
function wpar_show_box() {
	global $wpar_meta_box, $post, $wpar_prefix;
	
	// Use nonce for verification
	echo '<input type="hidden" name="wpar_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<ul class="wpar-metabox-tabs">
			<li class="wpar-tab '.$wpar_prefix.'tab1" id="main"><a class="active" href="javascript:void(null);">Main</a></li>
			<li class="wpar-tab '.$wpar_prefix.'tab2" id="details"><a href="javascript:void(null);">Details</a></li>
			<li class="wpar-tab '.$wpar_prefix.'tab3" id="display"><a href="javascript:void(null);">Display</a></li>
			<li class="wpar-tab '.$wpar_prefix.'tab4" id="link"><a href="javascript:void(null);">Link</a></li>
		</ul>';
	
	//echo '<table class="form-table">';

	foreach ($wpar_meta_box['fields'] as $field) {
		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);
		
		/*echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td>';
		*/
		// switching between field types
		switch ($field['type']) {
			
			case 'start':
				
				echo '<div class="'.$field['id'].'">';
				echo '<h4 class="heading">'.$field['name'].'</h4>';
				echo '<div class="tab-content">';
				echo '<table class="form-table">';
				echo '<tr>';
				break;
				
			case 'close':
				
				echo '</tr>';
				echo '</table>';
				echo '</div>';
				echo '</div>';
				break;
					
			case 'info':
				
				echo '<tr>';
				echo '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>';
				echo '<td>';
				echo $field['desc'];
				echo '</td></tr>';
				break;
				
			case 'spacer':
				
				echo '<tr><th style="width:20%"></th><td><hr /></td></tr>';
				break;
			
			case 'text':
				
				echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td>';
				
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />',
					'<br />', '<span class="description">'.$field['desc'].'</span>';
				
				echo 	'	</td>',
						'</tr>';
				break;
			
			case 'textarea':
				
				echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td>';
				
				echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>',
					'<br />', '<span class="description">'.$field['desc'].'</span>';
					
				echo 	'	</td>',
						'</tr>';
						
				break;
			
			case 'rating':
				
				echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td>';
				
				echo '<select name="', $field['id'], '" id="', $field['id'], '">';
				foreach ($field['options'] as $option) {
					echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
				}
				echo '</select>', ' ', '<span class="description">'.$field['desc'].'</span>';
				
				
				// get and display rating
				$custom = get_post_custom();
			
				// check if no rating, that means it's a new post, so do NOTHING!
				if (isset($custom["ta_post_review_rating"][0]) == null ) { 
					// no rating, or probably a new review
					$rating_star ='';
					$rating ='';
				} else {
					// if rating has a value, then calculate
					$rating = $custom["ta_post_review_rating"][0];
					$rating_star = $rating * 20;
				}
				
				$html	= '<div class="ta_rating result rating">';
				$html	.= '<div class="result" style="width:' . $rating_star . '%;" title="' . $rating . '">' . $rating . '</div>';
				$html	.= '</div>';
				
				// display the =stars	
				echo $html ;
				
				echo 	'	</td>',
						'</tr>';
				break;
			
			case 'select':
				
				echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td>';
				
				echo '<select name="', $field['id'], '" id="', $field['id'], '">';
				foreach ($field['options'] as $option) {
					echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
				}
				echo '</select>', ' ', '<span class="description">'.$field['desc'].'</span>';		
				echo '</td>',
						'</tr>';
				break;
				
			case 'radio':
				
				
				echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td>';
				
				foreach ($field['options'] as $option) {
					echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
				}
				break;
			
			
			case 'checkbox':
				
				echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td>';
				
				echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />', 
				' ', '<span class="description">'.$field['desc'].'</span>';
				
				echo 	'	</td>',
						'</tr>';
				break;
			
			case 'image':
				
				echo '<tr valign="top">
						<th scope="row">Upload Image</th>
							<td><label for="upload_image">
									<input id="', $field['id'], '" type="text" size="36" name="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" onchange="this.form.elements["thumb"].src = "', $meta, '" />
									<input id="upload_image_button" class="button" type="button" value="Upload Image" />',
									'<br />', '<span class="description">'.$field['desc'].'</span>', 
								'</label>',
								
								'<div class="wpar_preview">
									<img id="thumb" name="thumb" src="', $meta ? $meta : $field['std'], '">
									<br />', '<span class="description">Preview</span>
								</div>',
									
							'</td>
						</tr>';
		}
		/*echo 	'<td>',
			'</tr>';*/
	}
	
	echo '</table>';
}

// Save data from meta box
function wpar_save_data($post_id) {
	global $wpar_meta_box, $wpar_prefix;
	
	// verify nonce
	// commented this due release of WordPress Version 3.5.2
	// @since 1.0.1.6
	//if (!wp_verify_nonce(isset($_POST['wpar_nonce']), basename(__FILE__))) {
	//	return $post_id;
	//}

	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
	
	
	foreach ($wpar_meta_box['fields'] as $field) {
		
		
		$old = get_post_meta($post_id, $field['id'], true);
		
		// get rid of the empty and unset fields, which cause php notices 
		if ( isset($_POST[$field['id']]) ) { $new = $_POST[$field['id']];} else { $new =''; }
		
		// deal with "No Rating" in select box
		// so it won't create an unwanted custom field in a non review post
		if ( $new == 'No Rating') {$new ='';}
		//if ($_POST[$field['id']] == 'No Rating') {$new ='';}
		
		// deal with "defaul" value in select box
		// so it won't create an unwanted custom field in a non review post
		if ( $new == 'default') {$new ='';}
		
		// now, things are good, let's update, delete fields
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}

	function wpar_enqueue() {
		
		// get admin color
		$color = get_user_meta( get_current_user_id(), 'admin_color', true );

		wp_enqueue_style(	'wpar-metabox-tabs',	WPAR_CSS_URL.'metabox-tabs.css'			);
		wp_enqueue_style(	'wpar-color',			WPAR_CSS_URL.'metabox-'.$color.'.css'	);
		wp_enqueue_script(	'wpar-metabox-tabs',	WPAR_JS_URL.'metabox-tabs.js', 'jquery'	);
		wp_enqueue_script(	'wpar-image-upload',	WPAR_JS_URL.'image-upload.js', 'jquery'	);
	}
?>