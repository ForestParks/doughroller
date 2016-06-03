<?php
	/*
	plugin technical settings page
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
            <h3 style="cursor:default;">Technical Settings</h3>
            <div id="wpar" class="inside" style="padding:0px 6px 0px 6px;">

			<!-- Table Structure Containing Form Controls -->
			<!-- Each Plugin Option Defined on a New Table Row -->
			<table class="form-table">
            
            	<!-- Checkbox Buttons -->
				<tr valign="top">
					<th scope="row">More snippets</th>
					<td>
						<!-- First checkbox button -->
						<label><input name="wpar_options[wpar_chk_more_snippet]" type="checkbox" value="1" <?php if (isset($options['wpar_chk_more_snippet'])) { checked('1', $options['wpar_chk_more_snippet']); } ?> /> Add more snippets?</label><br />
                        
                        <span class="description">Enable this option will add extra meta itemprop to the head section.
                        <br>(note: probably you won't need to enable this option, use it only when you are asked too)</span>


					</td>
				</tr>
                
                <!-- Checkbox Buttons -->
				<tr valign="top">
					<th scope="row">Stylesheet</th>
					<td>
						<!-- First checkbox button -->
						<label><input name="wpar_options[wpar_chk_no_css]" type="checkbox" value="1" <?php if (isset($options['wpar_chk_no_css'])) { checked('1', $options['wpar_chk_no_css']); } ?> /> Disable loading of stylesheet?</label><br />
                        
                        <span class="description">Use this if you do not want to load the stylesheet and combine the style to your own global style sheet.
                        <br>(note: use the content of the <code>/css/style.css</code> file)</span>


					</td>
				</tr>
                
			</table>
            
			<p>
            	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>">
            	
			</p>
		        
               
            </div>
 
        </div><!-- end of general settings -->

