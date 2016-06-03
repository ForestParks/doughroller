<div class="mainbox" id="popup_domination_tab_submissions" style="display:none;">
	<div class="popdom_contentbox the_help_box">
		<h3 class="help">Help</h3>
		<div class="popdom_contentbox_inside">
			<p><strong>Set up your campaign with alternative mailing lists.</strong></p>
			<p>If you are experiencing problems with your popup, please have a look at our help articles at:</p>
			<p><a href="https://popdom.assistly.com/">our Assistly Help Area.</a></p>
		</div>
	</div>
	<div class="inside twomaindivs">
		<div class="the_content_box">
			<div class="popdom_contentbox" style="margin-left:0px;">
				<div class="popdom_contentbox_inside" id="submissions_tab">
					<div id="mailing_lists">
						<h3>Please select the Mailing List you would like to configure the pop up with</h3>
						<?php $lists = $this->get_mailing_lists(); ?>
						<select id="mailing_list" name="popup_domination_mailing[mailing_option]">
							<option value="-1">None required</option>
							<?php foreach($lists as $id => $name):
									if (!empty($mailing['id']) && $id == $mailing['id']):?>
							<option value="<?php echo $id; ?>" selected="selected"><?php echo $name; ?></option>
									<?php else: ?>
							<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
									<?php endif; ?>
							<?php endforeach; ?>
						</select>
						<span class="example">
						  You will need to select a mailing list before you can start receiving opt-ins.
						  <br/>Need a new mailing list? <a href="/wp-admin/admin.php?page=popup-domination/mailinglist&action=create" target="_blank">Set one up.</a>
						</span>
					</div>
					<div id="redirect_option" <?php echo (!empty($mailing['id']) && $mailing['id'] != -1) ? 'style="display:none;"' : ''; ?>>
						<h3>Where would you like submissions to be directed to?</h3>
						<input type="text" id="submit_new_window" name="popup_domination_mailing[redirect_url]" value="<?php echo (!empty($mailing['redirect_url'])) ? $mailing['redirect_url'] : '';?>" placeholder="Enter the URL here..." />
					</div>
					<div id="mail_notify_option" >
  					<br/>
						<label for="mail_notify">Enter an email address here if you'd like to receive an email on each opt-in:</label>
						<input type="text" id="mail_notify" name="popup_domination_mailing[mail_notify]" value="<?php echo (!empty($mailing['mail_notify'])) ? $mailing['mail_notify']:'';?>" />
						<!--<pre><?php print_r($mailing) ?></pre>-->
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>