<div id="popup_domination_tabs_main" class="campaign-details">
	<div id="mailing-info">
		<div id="mailing-name-box">
			<label for="config_name">Configuration Name:</label>
			<input id="config_name" name="config_name" type="text" value="<?php echo isset($config_name) ? $config_name :'';?>" placeholder="Campaign Name..." />
			<div class="clear"></div>
			<p class="microcopy">e.g. PopUp Domination list</p>
		</div>
		<div id="mailing-description">
			<label for="config_desc">Mailing Configuration Description:</label>
			<input id="config_desc" type="text" name="config_desc" value="<?php echo isset($config_desc) ? $config_desc : '';?>" placeholder="Campaign Description..." />
			<p class="microcopy">e.g. List used to track all users who have opted in from PopUp Domination</p>
		</div>
	</div>
	<div class="clear"></div>
</div>