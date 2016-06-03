<?php
	/*
	plugin main options page
    */

	
	// Prevent loading this file directly - Busted!
	if ( ! class_exists( 'WP' ) )
	{
		header( 'Status: 403 Forbidden' );
		header( 'HTTP/1.1 403 Forbidden' );
		exit;
	}

	// ***************************************
	// start rating box settings *************
	// ***************************************
?>

          <div class="postbox">
            <h3 style="cursor:default;">Rating Box Settings</h3>
            <div id="wpar" class="inside" style="padding:0px 6px 0px 6px;">

			<!-- Table Structure Containing Form Controls -->
			<!-- Each Plugin Option Defined on a New Table Row -->
			<table class="form-table">
            
				<tr valign="top">
					<th scope="row">Display settings</th>
					<td>
						<!-- checkbox button -->
						<label><input name="wpar_options[wpar_chk_rating_box_hide]" type="checkbox" value="1" <?php if (isset($options['wpar_chk_rating_box_hide'])) { checked('1', $options['wpar_chk_rating_box_hide']); } ?> /> Hide rating box on all reviews?</label><br />


					</td>
				</tr>
                <tr valign="top">
					<th scope="row"></th>
					<td>
						<!-- checkbox button -->
						<label><input name="wpar_options[wpar_chk_reviewed_by_hide]" type="checkbox" value="1" <?php if (isset($options['wpar_chk_reviewed_by_hide'])) { checked('1', $options['wpar_chk_reviewed_by_hide']); } ?> /> Hide 'Reviewed by: Author' on all reviews?</label><br />


					</td>
				</tr>
                
                <tr valign="top">
					<th scope="row"></th>
					<td>
						<!-- checkbox button -->
						<label><input name="wpar_options[wpar_chk_rating_date_hide]" type="checkbox" value="1" <?php if (isset($options['wpar_chk_rating_date_hide'])) { checked('1', $options['wpar_chk_rating_date_hide']); } ?> /> Hide date on all reviews?</label><br />


					</td>
				</tr>
                
                <tr valign="top">
					<th scope="row"></th>
					<td>
						<!-- checkbox button -->
						<label><input name="wpar_options[wpar_chk_rating_modified_date_hide]" type="checkbox" value="1" <?php if (isset($options['wpar_chk_rating_modified_date_hide'])) { checked('1', $options['wpar_chk_rating_modified_date_hide']); } ?> /> Hide modified date on all reviews?</label><br />


					</td>
				</tr>
                
                <tr><th></th><td><hr /></td></tr>
                
                 <!-- Textbox Control -->
				<tr>
					<th scope="row">Review box width (default: 300px)</th>
					<td>
						<input type="text" size="5" name="wpar_options[wpar_box_width]" value="<?php echo $options['wpar_box_width']; ?>" />
                        <span style="color:#666666;margin-left:2px;">px</span>
					</td>
				</tr>
           
           			<!-- Select Drop-Down Control -->
				<tr>
					<th scope="row">Review box alignment (default: right)</th>
					<td>
						<select name='wpar_options[wpar_drp_box_align]'>
							<option value='right' <?php selected('right', $options['wpar_drp_box_align']); ?>>right</option>
							<option value='left' <?php selected('left', $options['wpar_drp_box_align']); ?>>left</option>
							<option value='none' <?php selected('none', $options['wpar_drp_box_align']); ?>>none</option>
						</select>
						<span style="color:#666666;margin-left:2px;">Select alignment type.</span>
					</td>
				</tr>
                
				<!-- Select Drop-Down Control -->
				<tr>
					<th scope="row">Rating Box Style</th>
					<td>
						<select name='wpar_options[wpar_drp_rating_box_style]'>
							<option value='default' <?php selected('default', $options['wpar_drp_rating_box_style']); ?>>default</option>
							<option value='wide' <?php selected('wide', $options['wpar_drp_rating_box_style']); ?>>wide</option>
                            <option value='light' <?php selected('light', $options['wpar_drp_rating_box_style']); ?>>light</option>
						</select>
						<span style="color:#666666;margin-left:2px;">Select rating box style.
                        <?php if (isset($options['wpar_drp_rating_box_style'])) {echo $options['wpar_drp_rating_box_style'];} else {echo 'default';} ?></span>
					</td>
				</tr>
                
                <!-- Select Drop-Down Control -->
				<tr>
					<th scope="row">Button color</th>
					<td>
						<select name='wpar_options[wpar_drp_button_color]'>
							<option value='blue' <?php selected('blue', $options['wpar_drp_button_color']); ?>>blue</option>
							<option value='orange' <?php selected('orange', $options['wpar_drp_button_color']); ?>>orange</option>
							<option value='green' <?php selected('green', $options['wpar_drp_button_color']); ?>>green</option>
							<option value='red' <?php selected('red', $options['wpar_drp_button_color']); ?>>red</option>
							<option value='yellow' <?php selected('yellow', $options['wpar_drp_button_color']); ?>>yellow</option>
							<option value='white' <?php selected('white', $options['wpar_drp_button_color']); ?>>White</option>
							<option value='purple' <?php selected('purple', $options['wpar_drp_button_color']); ?>>Purple</option>
							<option value='gray' <?php selected('gray', $options['wpar_drp_button_color']); ?>>Gray</option>
						</select>
						<span style="color:#666666;margin-left:2px;">Select button color.</span>
					</td>
				</tr>
                
                 <!-- "More Details" Button Value -->
				<tr>
					<th scope="row">More Details Button Value</th>
					<td>
						<input type="text" size="20" name="wpar_options[wpar_more_details_btn_value]" value="<?php echo $options['wpar_more_details_btn_value']; ?>" />
                        <span style="color:#666666;margin-left:2px;">default: More Details</span>
					</td>
				</tr>
                
			</table>
            
			<p>
            	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>">
			</p>
		
		</div>
           
	</div><!-- end of rating box settings -->