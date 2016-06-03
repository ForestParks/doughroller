<?php
/*
* page.php
*
*
*/
?>

<?php if($this->success): ?>
<div id="message" class="updated"><p>Your Settings have been <strong>Saved</strong></p></div>
<?php endif; ?>
<div class="wrap with-sidebar" id="popup_domination">
	<?php
	$header_link = 'Back to Mailing List Management';
	$header_url = 'admin.php?page=popup-domination/mailinglist';
	include $this->plugin_path.'tpl/header.php';
	?>
	<div style="display:none" id="popup_domination_hdn_div"><?php echo $fields?></div>
	<div class="clear"></div>
	
	<form name="apidata" id="apiformdata" action="admin.php?page=popup-domination/mailinglist" method="post">
	<div id="popup_domination_container" class="has-left-sidebar">
		<div style="display:none" id="popup_domination_hdn_div2"></div>
		<?php include $this->plugin_path.'tpl/mailing/header.php'; ?>
		<?php include $this->plugin_path.'tpl/mailing/tabs.php'; ?>
		<div class="notices" style="display:none;">
			<p class="message"></p>
		</div>
		<div class="flotation-device">
			<?php include $this->plugin_path.'tpl/mailing/mailchimp.php'; ?>
			<?php include $this->plugin_path.'tpl/mailing/aweber.php'; ?>
			<?php include $this->plugin_path.'tpl/mailing/icontact.php'; ?>
			<?php include $this->plugin_path.'tpl/mailing/constantcontact.php'; ?>
			<?php include $this->plugin_path.'tpl/mailing/campaignmonitor.php'; ?>
			<?php include $this->plugin_path.'tpl/mailing/getresponse.php'; ?>
			<?php include $this->plugin_path.'tpl/mailing/email.php'; ?>
			<?php include $this->plugin_path.'tpl/mailing/htmlform.php'; ?>
			
			<div class="clear"></div>
			<div class="mainbox" id="popup_domination_tab_api">
				<div class="inside twodivs">
					<div class="popdom-inner-sidebar">
						
							<div id="mailingfeedback"></div>
							
							<!-- Generic configuration details shared for all providers i.e. PopDom features -->
							<div id="new_window">
								<h3>Submit to a new window?</h3>
								<ul id="submit_new_window">
				                    <li><input type="radio" name="new_window" id="new_window_yes" value="yes" <?php echo ($new_window == 'yes') ? 'checked="checked"': ''; ?> /><label for="new_window_yes">Yes</label></li>
				                    <li><input type="radio" name="new_window" id="new_window_no" value="no" <?php echo ($new_window != 'yes') ? 'checked="checked"': ''; ?> /><label for="new_window_no">No</label></li>
			                    </ul>
							</div>
		
		                    <div id="disable_name">
								<h3>Disable the name field?</h3>
			                    <ul id="disable_name_field">
				                    <li><input type="radio" name="disable_name" id="disable_name_yes" value="yes" <?php echo ($disable_name == 'yes') ? 'checked="checked"': ''; ?> /><label for="disable_name_yes">Yes</label></li>
				                    <li><input type="radio" name="disable_name" id="disable_name_no" value="no" <?php echo ($disable_name != 'yes') ? 'checked="checked"': ''; ?> /><label for="disable_name_no">No</label></li>
			                    </ul>
		                    </div>
		
		

		                    	<div id="mailing-redirect-check" <?php echo ($provider == 'form') ? 'style="display:none;"' : ''; ?>>
		                    		<h3>Re-direct user after Opt In?</h3>
									<ul id="redirect_user" <?php echo ($provider == 'form') ? 'disabled="disabled"' : ''; ?>>
					                    <li><input type="radio" name="redirect" id="redirect_user_yes" value="yes" <?php echo ($redirect == 'yes') ? 'checked="checked"': ''; ?> /><label for="redirect_user_yes">Yes</label></li>
					                    <li><input type="radio" name="redirect" id="redirect_user_no" value="no" <?php echo ($redirect != 'yes') ? 'checked="checked"': ''; ?> /><label for="redirect_user_no">No</label></li>
				                    </ul>
	                    		</div>
								<div id="mailing-redirect-url" <?php echo ($provider == 'form' || $redirect != 'yes') ? 'style="display:none;"' : ''; ?>>
									<h3>Re-direct URL:</h3>
									<input id="redirect_url" type="text" name="redirect_url" <?php echo ($provider == 'form' || $redirect != 'yes') ? 'disabled="disabled"' : ''; ?> value="<?php echo $redirect_url; ?>" placeholder="Enter the URL here..." />
								</div>
							
		                    <div id="custom_fields">
								<h3>Custom Fields</h3>
								<span class="example">How Many Extra Fields Would You Like? (this is limited by the template)</span>
								<select id="custom_select" class="custom_num" name="custom_fields">
									<option id="none" value="0">0</option>
									<option id="one" value="1" <?php echo $custom_fields == 1 ? 'selected="selected"': ''; ?> >1</option>
									<option id="two" value="2" <?php echo $custom_fields == 2 ? 'selected="selected"': ''; ?> >2</option>
								</select>
								
								<div id="general_custom_fields" <?php echo ($provider == 'cc' || $provider == 'form')  ? 'style="display:none;" disabled="disabled"' : ''; ?>>
									<div id="more_custom_fields">
										<div id="custom1" style="<?php echo $custom_fields >= 1 ? 'display:block;' : 'display:none;'; ?>">
											<span class="example">What is Your 1st Custom Field Name? (Need Help? <a target="_blank" href="http://popdom.desk.com/customer/portal/articles/367583-extra-custom-fields">Click Here</a>)</span>
											<input type="text" name="custom1name" value="<?php echo $custom1name; ?>"/>
										</div>
										<div id="custom2" style="<?php echo $custom_fields >= 2 ? 'display:block;' : 'display:none;'; ?>">
											<span class="example custom2" >What is Your 2nd Custom Field Name? (Need Help? <a target="_blank" href="http://popdom.desk.com/customer/portal/articles/367583-extra-custom-fields">Click Here</a>)</span>
											<input type="text" name="custom2name" value="<?php echo $custom2name; ?>"/>
										</div>
									</div>
								</div>
								<div id="cc_custom_fields" <?php echo $provider == 'cc' ? '' : 'style="display:none;"'; ?>>
					    			<select id="custom1" name="custom1name" <?php echo $provider == 'cc' ? '' : 'disabled="disabled"'; ?>  style="<?php echo $custom_fields >= 1 ? 'display:block;' : 'display:none;'; ?>">
					    				<option value="" >Please Select...</option>
					    				<option value="MiddleName" <?php if($custom1name == 'MiddleName'){ echo 'selected="selected"';} ?> >Middle Name</option>
										<option value="LastName" <?php if($custom1name == 'LastName'){ echo 'selected="selected"';} ?>>Last Name</option>
										<option value="HomePhone" <?php if($custom1name == 'HomePhone'){ echo 'selected="selected"';} ?>>Home Phone</option>
										<option value="Addr1" <?php if($custom1name == 'Addr1'){ echo 'selected="selected"';} ?>>Address</option>
										<option value="City" <?php if($custom1name == 'City'){ echo 'selected="selected"';} ?>>City</option>
										<option value="StateName" <?php if($custom1name == 'StateName'){ echo 'selected="selected"';} ?>>State/Province</option>
										<option value="PostalCode" <?php if($custom1name == 'PostalCode'){ echo 'selected="selected"';} ?>>Zip/Postal Code</option>
					    			</select>
					    			<select id="custom2" id="custom2name" <?php echo $provider == 'cc' ? '' : 'disabled="disabled"'; ?> style="<?php echo $custom_fields >= 2 ? 'display:block;' : 'display:none;'; ?>">
					    				<option value="" >Please Select...</option>
					               		<option value="MiddleName" <?php if($custom2name == 'MiddleName'){ echo 'selected="selected"';} ?> >Middle Name</option>
										<option value="LastName" <?php if($custom2name == 'LastName'){ echo 'selected="selected"';} ?>>Last Name</option>
										<option value="HomePhone" <?php if($custom2name == 'HomePhone'){ echo 'selected="selected"';} ?>>Home Phone</option>
										<option value="Addr1" <?php if($custom2name == 'Addr1'){ echo 'selected="selected"';} ?>>Address</option>
										<option value="City" <?php if($custom2name == 'City'){ echo 'selected="selected"';} ?>>City</option>
										<option value="StateName" <?php if($custom2name == 'StateName'){ echo 'selected="selected"';} ?>>State/Province</option>
										<option value="PostalCode" <?php if($custom2name == 'PostalCode'){ echo 'selected="selected"';} ?>>Zip/Postal Code</option>
					    			</select>
								</div>
								<div id="form_custom_fields" <?php echo $provider == 'form' ? '' : 'style="display:none;"'; ?>>
					    			<select id="custom1" name="custom1name" <?php echo $provider == 'form' ? '' : 'disabled="disabled"'; ?>  style="<?php echo $custom_fields >= 1 ? 'display:block;' : 'display:none;'; ?>"></select>
					    			<input type="hidden" name="custom1" id="custom1_selected" value="<?php echo $custom1name; ?>" />
					    			<select id="custom2" name="custom2name" <?php echo $provider == 'form' ? '' : 'disabled="disabled"'; ?> style="<?php echo $custom_fields >= 2 ? 'display:block;' : 'display:none;'; ?>"></select>
					    			<input type="hidden" name="custom2" id="custom2_selected" value="<?php echo $custom2name; ?>" />
								</div>
		                    </div>
							
							
							<!-- Data used to determine whether the information has been updated or user has just clicked save -->
							<input type="hidden" name="listid" id="listid" value="<?php echo !empty($provider) ? $listid : ''; ?>"/>
							<input type="hidden" name="listname" id="listname" value="<?php echo !empty($provider) ? $listname : ''; ?>" />
							<input type="hidden" name="provider" id="provider" value="<?php echo !empty($provider) ? $provider : 'mc'; ?>"/>
							<input type="hidden" name="newprovider" id="newprovider" value="<?php echo !empty($provider) ? $provider : 'mc'; ?>"/>
							<input type="hidden" name="newlistid" id="newlistid" value="<?php echo !empty($provider) ? $listid : ''; ?>" />
							<input type="hidden" name="newlistname" id="newlistname" value="<?php echo !empty($provider) ? $listname : ''; ?>" />
							
							
							<?php if(!empty($provider)): ?>
							<!-- Shows which mailing list provider the user is connected to -->
							<h3>Currently Connected</h3>
							<div id="connected-provider">
								<div id="current-connect">
									<p id="currently-connected">You are currently connected to:</p>
									<?php
										if($provider == 'mc'){
											$logo = '<img src="'.$this->plugin_url.'css/img/mailchimp.png" alt="mailchimp" />';
										}else if($provider == 'aw'){
											$logo = '<img src="'.$this->plugin_url.'css/img/aweber.png" style="margin-left:-13px;" alt="AWeber"/>';
										}else if($provider == 'ic'){
											$logo = '<img src="'.$this->plugin_url.'css/img/icontact.png" alt="iContact"/>';
										}else if($provider == 'cc'){
											$logo = '<img src="'.$this->plugin_url.'css/img/constant.png" alt="ConstantContact"/>';
										}else if($provider == 'cm'){
											$logo = '<img src="'.$this->plugin_url.'css/img/campaign.png" alt="Campaign Monitor"/>';
										}else if($provider == 'gr'){
											$logo = '<img src="'.$this->plugin_url.'css/img/response.png" alt="Get Response"/>';
										}else if($provider == 'nm'){
											$logo = '<img src="'.$this->plugin_url.'css/img/email.png" alt="Opt In to Email" />';
										}else if ($provider == 'form'){
											$logo = '<img src="'.$this->plugin_url.'css/img/htmlform.png" alt="HTML Form Code" />';
										} else {
											$logo = '';
										}
									?>
									<p id="mailing-provider"><?php echo $logo; ?></p>
									</div>
								<div id="connected-list" <?php echo ($provider == 'nm' || $provider == 'form') ? 'style="display:none;"': ''; ?>>
									<p id="connect-mailing-list">Mailing List you are currently using:</p>
									<p id="mailing-list"><?php echo $listname; ?></p>
								</div>
							</div>
							<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	<?php
	$save_button = '<input class="savecamp save-btn apisubmit" type="submit" value="Save Changes" name="update" style="display: inline;">';
	$footer_fields = '<input type="hidden" name="id" value="'.$id.'" />';
	if (!isset($custom_fields)) $custom_fields = 0;
	$page_javascript = "var website_url = '".site_url()."';";
	include $this->plugin_path.'tpl/footer.php'; ?>
	</div>
	</form>
	<div id="popup-domination-html-hidden-form" style="display:none;"></div>
</div>