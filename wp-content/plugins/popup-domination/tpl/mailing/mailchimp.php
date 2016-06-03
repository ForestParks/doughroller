<div class="mainbox" id="popup_domination_tab_mailchimp">
	<div class="inside twodivs">
		<div class="popdom_contentbox the_help_box">
			<h3 class="help">Help</h3>
			<div class="popdom_contentbox_inside">
				<p>You can find your API Key under the Account link->API Keys. You may have to create a new API Key.</p>
				<img src="<?php echo $this->plugin_url;?>css/img/keys.jpg" width="450" height="99" alt="" />
			</div>
			<div class="clear"></div>
		</div>
		<div class="popdom-inner-sidebar">
			<h3>Please Fill in the Following Details:</h3>
			<div class="mc">
    			<span class="example">Mailchimp API Key:</span>
    			<input type="text" class="required" name="mc[apikey]" data-provider='mc' id="mc_apikey" placeholder="Enter Your Api key…" value="<?php if($provider == 'mc'){ echo $apikey; }else{ echo ''; } ?>" />
    			<h3>Please Select a Mailing List:</h3>
    			<a href="#" data-provider='mc_apikey' class="mc_getlist getlist"><span>Grab Mailing List</span></a><span class="mailing-ajax-waiting">waiting</span>
    			<div class="clear"></div>
    			<div class="mc_custom_fields">
					<input type="hidden" name="mc[name_box]" value="FNAME" />
					<input type="hidden" name="mc[email_box]" value="EMAIL" />
				</div>
			</div>
    	</div>
    </div>
</div>