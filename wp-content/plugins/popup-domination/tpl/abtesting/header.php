<div id="popup_domination_tabs" class="campaign-details">
	<div class="campaign-name-box">
		<label for="campname">A/B Split Name: </label>
		<input id="campname" name="campname" type="text" value="<?php echo isset($name) ? $name: ''; ?>" placeholder="Campaign Name..." />
		<div class="clear"></div>
		<p class="microcopy">e.g. Service Page Popup &#35;1</p>
		<img class="waiting" src="<?php echo $this->plugin_url; ?>/css/loading.gif" alt="loading" width="15" height="15" />
		<div class="clear"></div>
	</div>
	<div class="campaign-description">
		<label for="campdesc">Popup Description: </label>
		<input name="campaigndesc" type="text" value="<?php echo isset($desc) ? $desc : ''; ?>" placeholder="Campaign Description..." />
		<p class="microcopy">e.g. Testing incentive A to see how it converts.</p>
	</div>
	<div class="clear"></div>
</div>