<div class="mainbox" id="popup_domination_tab_constantcontact" style="display:none;">
	<div class="inside twodivs">
		<div class="popdom_contentbox the_help_box">
			<h3 class="help">Help</h3>
			<div class="popdom_contentbox_inside">
				<p>Click on the "Connect" Button, enter your login details and follow the steps on screen. Once Completed and returned to this screen, click the Get Mailing List link to get your mailing lists.</p>
			</div>
			<div class="clear"></div>
		</div>
		<div class="popdom-inner-sidebar">
		<h3>Please Fill in the Following Details:</h3>
			<div class="cc">
			    <h3>Please Select a Mailing List</h3>
			    <input type="hidden" name="cc[username]" value="<?php if($provider == 'cc'){ echo $username; }else{ echo '';}?>" id="cc_username" />
				<input type="hidden" name="cc[password]" value="<?php if($provider == 'cc'){ echo $password; }else{ echo '';} ?>" id="cc_password" />
				<input type="hidden" name="cc[apikey]" value="<?php if($provider == 'cc'){ echo $apiextra; }else{ echo '';} ?>" id="cc_apikey" />
				<input type="hidden" name="cc[usersecret]" value="<?php if($provider == 'cc'){ echo $apikey; }else{ echo '';} ?>" id="cc_usersecret" />
				<a href="<?php echo $this->plugin_url."inc/concon/constantcon.php"; ?>" data-provider='cc_apikey' class="connect-to getlist fancybox"><span>Connect to Constant Contact</span></a>
            	<a href="#" data-provider='cc_apikey' class="cc_getlist getlist" style="display:none;"><span>Grab Mailing List</span></a><span class="mailing-ajax-waiting">waiting</span>
    			<div class="clear"></div>
    			<div class="cc_custom_fields">
    			</div>
			</div>
		</div>
	</div>
</div>
