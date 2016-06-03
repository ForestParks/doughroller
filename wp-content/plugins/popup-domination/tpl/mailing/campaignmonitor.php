<div class="mainbox" id="popup_domination_tab_campaignmonitor" style="display:none;">
	<div class="inside twodivs">
		<div class="popdom_contentbox the_help_box">
			<h3 class="help">Help</h3>
			<div class="popdom_contentbox_inside">
				<p>You will first need to locate your API Key which can be found under account settings.</p>
				<img src="<?php echo $this->plugin_url;?>css/img/apikey.jpg" alt="" />
				<p>The you will need to get the ClientId of the client you want to collect the list from. This can be found under Client Settings in the client's overview area.</p>
				<img src="<?php echo $this->plugin_url;?>css/img/clientid.jpg" alt="" />
				<p>Once you have these, just enter them into the fields below.</p>
			</div>
			<div class="clear"></div>
		</div>
		<div class="popdom-inner-sidebar">
		<h3>Please Fill in the Following Details:</h3>
			<div class="cm">
				<span class="example">Campaign Monitor ClientId</span>
				<input class="required" type="text" name="cm[username]" data-provider='cm' placeholder="Enter Your Client Id…" value="<?php if($provider == 'cm'){ echo $username; }else{ echo ''; } ?>" id="cm_clientid" />
				<span class="example">Campaign Monitor API key</span>
    			<input class="required" type="text" name="cm[apikey]" data-provider='cm' placeholder="Enter Your Api key…" value="<?php if($provider == 'cm'){ echo $apikey; }else{ echo '';} ?>" id="cm_apikey" />
    			<h3>Please Select a Mailing List:</h3>
    			<a href="#" data-provider='cm_apikey' class="cm_getlist getlist"><span>Grab Mailing List</span></a><span class="mailing-ajax-waiting">waiting</span>
    			<div class="cm_custom_fields">
    			</div>
			</div>
		</div>
	</div>
</div>
