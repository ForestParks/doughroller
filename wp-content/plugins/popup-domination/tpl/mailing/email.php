<div class="mainbox" id="popup_domination_tab_email" style="display:none;">
	<div class="inside twodivs">
		<div class="popdom_contentbox the_help_box">
			<h3 class="help">Help</h3>
			<div class="popdom_contentbox_inside">
				<p>Please just enter the email address to which you want all opt-in data to be sent to.</p>
			</div>
			<div class="clear"></div>
		</div>
		<div class="popdom-inner-sidebar">
			<h3>Please Fill in the Following Details:</h3>
			<div class="nm">
				<span class="example">The Email Address You Wish to Send Opt-In Details to:</span>
				<input class="required" type="text" name="nm[username]" data-provider='nm' id="nm_emailadd" placeholder="Enter Your Email Address..." value="<?php if($provider == 'nm'){ echo $username; }else{ echo ''; } ?>" />
			</div>
			<div class="nm_custom_fields">
				<input type="hidden" name="nm[form_action]" value="<?php echo $this->plugin_url; ?>inc/email.php" />
			</div>
		</div>
	</div>
</div>