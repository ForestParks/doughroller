<?php
	/*
	plugin main settings page
    */

	
	// Prevent loading this file directly - Busted!
	if ( ! class_exists( 'WP' ) )
	{
		header( 'Status: 403 Forbidden' );
		header( 'HTTP/1.1 403 Forbidden' );
		exit;
	}

	// ***************************************
	// start general settings ****************
	// ***************************************
?>

        <div class="postbox">
            <h3 style="cursor:default;">General Settings</h3>
            <div id="wpar" class="inside" style="padding:0px 6px 0px 6px;">

			<!-- Table Structure Containing Form Controls -->
			<!-- Each Plugin Option Defined on a New Table Row -->
			<table class="form-table">
            
            	<!-- Checkbox Buttons One -->
				<tr valign="top">
					<th scope="row">Display settings</th>
					<td>
						<!-- First checkbox button -->
						<label><input name="wpar_options[wpar_chk_rating_home_display]" type="checkbox" value="1" <?php if (isset($options['wpar_chk_rating_home_display'])) { checked('1', $options['wpar_chk_rating_home_display']); } ?> /> Display rating stars on home page?</label><br />


					</td>
				</tr>
                	<!-- Checkbox Buttons two -->
				<tr valign="top">
					<th scope="row"></th>
					<td>
						<!-- First checkbox button -->
						<label><input name="wpar_options[wpar_chk_rating_after_post_display]" type="checkbox" value="1" <?php if (isset($options['wpar_chk_rating_after_post_display'])) { checked('1', $options['wpar_chk_rating_after_post_display']); } ?> /> Display rating below single post?</label><br />


					</td>
				</tr>
                </tr>
                
                
                <tr><th></th><td><hr /></td></tr>
                
                
                <tr valign="top">
					<th scope="row">Custom Post Types</th>
					<td>
						<?php
						$args=array(
							'public'   => true,
							'_builtin' => false
						); 
						$output = 'names'; // names or objects, note names is the default
						$operator = 'and'; // 'and' or 'or'
						$post_types = get_post_types($args,$output,$operator); 
						foreach ($post_types  as $post_type ) {
							?>
							<label><input name="wpr_options[wpar_chk_post_type_<?php echo $post_type; ?>]" type="checkbox" value="1" <?php if (isset($options['wpar_chk_post_type_'.$post_type])) { checked('1', $options['wpar_chk_post_type_'.$post_type]); } ?> /> <?php echo '<span style="margin-right:10px;">'.$post_type.'</span>'; ?> </label>
                    		<?php
						}
						?>
                        <p>
                        <span class="description">Reviews are enabled on posts and pages by default, here you can enable it on specific custom post types if presented.</span>
                        </p>

					</td>
				</tr>
                
  				<tr><th></th><td><hr /></td></tr>
                
				<tr>
					<th scope="row">Disclose Paid Reviews</th>
					<td>
						<input type="text" size="30" name="wpar_options[wpar_disclaimer_title]" value="<?php if (isset($options['wpar_disclaimer_title'])) { echo $options['wpar_disclaimer_title'];}
						else {echo 'Disclaimer';} ?>" />
                         <p>
                        <span class="description">Link title (example: <span class="icon-exclamation-sign" style="font-size:14px;line-height:14px;"></span> <?php if (isset($options['wpar_disclaimer_title'])) { echo $options['wpar_disclaimer_title'];}
						else {echo 'Disclaimer';} ?>)</span>
                        </p>
					</td>
				</tr>
                
                <tr>
					<th scope="row"></th>
					<td>
						<input type="text" size="60" name="wpar_options[wpar_disclaimer_url]" value="<?php if (isset($options['wpar_disclaimer_url'])) { echo $options['wpar_disclaimer_url'];} else {echo '';} ?>" />
                        
                        <p>
                        <span class="description">Enter your Disclaimer Page full URL, this will display an icon with a link to the Disclaimer Page in the review box (this field is required for the Disclaimer link to show).</span>
                        </p>
                        
					</td>
				</tr>
                
				<!--
                <tr><td colspan="2"><div style="margin-top:10px;"></div></td></tr>
				<tr valign="top" style="border-top:#dddddd 1px solid;">
					<th scope="row">Database Options</th>
					<td>
						<label><input name="wpar_options[wpar_chk_default_options_db]" type="checkbox" value="1" <?php if (isset($options['wpar_chk_default_options_db'])) { checked('1', $options['wpar_chk_default_options_db']); } ?> /> Restore defaults upon plugin deactivation/reactivation</label>
						<br /><span style="color:#666666;margin-left:2px;">Only check this if you want to reset plugin settings upon Plugin reactivation</span>
					</td>
				</tr>
                -->
			</table>
            
			<p>
            	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>">
            	<!-- &nbsp;
            	<input class="button-secondary" value="Secondary Button"> -->
			</p>
		        
               
            </div>
 
        </div><!-- end of general settings -->

