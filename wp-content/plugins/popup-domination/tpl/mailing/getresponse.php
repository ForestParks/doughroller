<div class="mainbox" id="popup_domination_tab_getresponce" style="display:none;">
	<div class="inside twodivs">
		<div class="popdom_contentbox the_help_box">
			<h3 class="help">Help</h3>
			<div class="popdom_contentbox_inside">
				<p>You will need to collect your API Key from you account. You can find the page to do so under the My Account link. You may have to create an API Key. </p>
				<img src="<?php echo $this->plugin_url;?>css/img/grkeys.png" alt="" />
			</div>
			<div class="clear"></div>
		</div>
		<div class="popdom-inner-sidebar">
		<h3>Please Fill in the Following Details:</h3>
			<div class="gr">
			<span class="example">GetResponse API Key</span>
				<input class="required" type="text" name="gr[apikey]" data-provider='gr' placeholder="Enter Your Api key…" value="<?php if($provider == 'gr'){ echo $apikey; }else{ echo ''; } ?>" id="gr_apikey" />
				<h3>Please Select a Mailing List:</h3>
				<a href="#" data-provider='gr_apikey' class="gr_getlist getlist"><span>Grab Mailing List</span></a><span class="mailing-ajax-waiting">waiting</span>
    			<div class="clear"></div>
    			<div class="gr_custom_fields">
    			</div>
			</div>
		</div>
	</div>
</div>