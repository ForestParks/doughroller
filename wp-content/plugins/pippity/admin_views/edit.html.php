<?php
/*
Pippity

Create Popup Admin Page
*/
?>
<?php
global $pty;
echo '
<script type="text/javascript">
	jQuery(function(){ });
	PTY_ALLIMAGES = ' . json_encode($pty->allImages) . ';
	PTY_VARIMAGES = ' . json_encode($pty->variantImages) . ';
	PTY_ALLFONTS = ' . json_encode($pty->allFonts) . ';
	PTY_ALLFAMILIES = '. json_encode($pty->allFamilies) . ';
		PTY_PAGE = "EDIT";
</script>
<div class="wrap">
	<h2>' . (($create) ? 'Create a Popup' : 'Edit a Popup') . '</h2>
	<div id="poststuff" class="metabox-holder has-right-sidebar" style="direction:ltr">
		<div style="width:321px;float:left">
			<div style="width:320px;" id="side-sortables" class="meta-box-sortabless ui-sortable">
				<div class="postbox"  style="direction:ltr">
					<div class="slideNav-shell">
						<h3 class="hndle">
							<span id="sNav-heading">' . (($create) ? 'Available Themes' : 'Edit Popup Copy') . '</span> &nbsp;
							<div id="sNav-nav">
								<a href="#" class="disabled" id="sNav-back">◀</a>
								<a href="#" class="disabled" id="sNav-fwd">▶</a>
							</div>
						</h3>
						<div id="slideNav" class="slideNav">
							<div id="sNav-select" class="sNav-pane">
								<div class="sNav-heading">Available Themes</div>
								<div class="inside" id="pty_themeSide">
								</div>
							</div>
							<div id="sNav-style" class="sNav-pane">
								<div class="sNav-heading">Stylize</div>
								<div class="inside">
									<a href="#"  id="pty_toggleSettings-quickStyles" class="pty_settingsHead">Quick Styles<span class="pty_settingsToggleText">Hide</span></a>
									<div id="pty_quickStyles">
									</div>
									<div id="pty_styleAdvanced">
										<a href="#" id="pty_toggleSettings-positionShell" class="pty_settingsHead">Position <span class="pty_settingsToggleText">Show</span></a>
										<div id="pty_positionShell" class="pty_sideForm">
										</div>
										
										<a href="#" id="pty_toggleSettings-sImgsShell" class="pty_settingsHead">Customize Images <span class="pty_settingsToggleText">Show</span></a>
										<div id="pty_sImgsShell" class="pty_sideForm">
										</div>
										
										<a href="#" id="pty_toggleSettings-fontsShell" class="pty_settingsHead">Customize Fonts <span class="pty_settingsToggleText">Show</span></a>
										<div id="pty_fontsShell" class="pty_sideForm">
										</div>

										<a href="#" id="pty_toggleSettings-sCopyShell" class="pty_settingsHead">Customize Text Color  <span class="pty_settingsToggleText">Show</span></a>
										<div id="pty_sCopyShell" class="pty_sideForm">
										</div>

										<a href="#" id="pty_toggleSettings-overlayShell" class="pty_settingsHead">Overlay <span class="pty_settingsToggleText">Show</span></a>
										<div id="pty_overlayShell" class="pty_sideForm">
										</div>
										
										<a href="#" id="pty_toggleSettings-customCssShell" class="pty_settingsHead">Customize CSS (Advanced)  <span class="pty_settingsToggleText">Show</span></a>
										<div id="pty_customCssShell">
											<textarea id="pty_customCss" name="customCss"></textarea>
										</div>
									</div>
									<div class="pty_saveShell">
										<input id="pty_saveStyle" type="submit" href="#" class="sNav-next button-secondary" value="Save & Continue"/>
									</div>
								</div>
							</div>
							<div id="sNav-copy" class="sNav-pane">
								<div class="sNav-heading">Edit Popup Copy</div>
								<div class="inside">
									<form id="pty_copyForm" action="#" method="post" class="pty_sideForm">
									</form>
									<div class="pty_saveShell">
										<input id="pty_saveCopy" type="submit" href="#" class="sNav-next button-secondary" value="Save & Continue"/>
									</div>
								</div>
							</div>
							<div id="sNav-settings" class="sNav-pane">
								<div class="sNav-heading">Settings</div> 
								<div class="inside">
									<form id="pty_settingsForm" class="pty_sideForm" action="#" method="post">
										<a href="#" id="pty_toggleSettings-behaviorShell" class="pty_settingsHead">Popup Behavior <span class="pty_settingsToggleText">Hide</span></a>
										<div id="pty_behaviorShell" class="pty_sideForm">
											<div class="pty_tooltip pty_settingsContainer">
												<div class="pty_ttContent">
													<p>
														This is the time in seconds before the popup appears. 
													</p>
													<p>
														If you have \'Open at End of Article\' enabled, the time delay will only be used
														if an article is short enough to not require scrolling.
													</p>
												</div>

												<label class="pty_leftLabel" for="delay">Time before popup appears</label>
												<div class="pty_rightControls">
													<input type="text" value="60" name="delay" id="pty_delay" class="inp-text inp-hunds"/> seconds
												</div>
											</div>
											<div class="pty_clear"></div>
											<div class="pty_tooltip pty_settingsContainer">
												<div class="pty_ttContent">
													<p>
														A new visitor may not know your content well enough to subscribe. This allows
														them to read a few pages before asked to sign-up.
													</p>
													<p>
														0 popups it up on the first pageview.
													</p>
												</div>

												<label class="pty_leftLabel" for="visits">Pageviews Before Popup Appears</label>
												<div class="pty_rightControls">
													<input type="text" value="0" name="visits" id="pty_visits" class="inp-text inp-hunds"/> views
												</div>
											</div>
											<div class="pty_clear"></div>
											<div class="pty_tooltip pty_settingsContainer">
												<div class="pty_ttContent">
													<p>
														This is the number of days before your popup reappears. 0 pops up
														every pageview.	
													</p>
												</div>
												<label class="pty_leftLabel" for="expire">Don\'t Reshow Popup for</label>
												<div class="pty_rightControls">
													<input value="60" type="text" name="expire" id="pty_expire" class="inp-text inp-hunds"/> days
												</div>
											</div>  
											<div class="pty_clear"></div>
											<div class="pty_tooltip pty_settingsContainer">
												<div class="pty_ttContent">
													<p>
														Readers don\'t like being interrupted while reading.
													</p>
													<p>
														With this enabled, popups will appear immediately <strong>at the end</strong> of an article.	
													</p>
												</div>

												<label class="pty_leftLabel" for="trigger">Popup at End of Article</label>
												<div class="pty_rightControls">
													<input type="checkbox" name="trigger" id="pty_trigger" class="inp-slider" value="0"/>
												</div>
											</div>
											<div class="pty_tooltip pty_settingsContainer">
												<div class="pty_ttContent">
													<p>
														The popup will appear when the reader\'s cursor
														leaves the page.
													</p>
												</div>
												<label class="pty_leftLabel" for="trigger">Popup on Mouse-Out</label>
												<div class="pty_rightControls">
													<input type="checkbox" name="mouseout" id="pty_mouseout" class="inp-slider" value="0"/>
												</div>
											</div>
											<div class="pty_tooltip pty_settingsContainer">
												<div class="pty_ttContent">
													<p>
														The popup will appear if the reader switches
														tabs or windows.														
													</p>
												</div>
												<label class="pty_leftLabel" for="trigger">Popup on Window Change</label>
												<div class="pty_rightControls">
													<input type="checkbox" name="blur" id="pty_blur" class="inp-slider" value="0"/>
												</div>
											</div>
											<div class="pty_clear"></div>
											<div class="pty_tooltip pty_settingsContainer">
												<div class="pty_ttContent">
													<p>
														Quickly fade popup in and out.
													</p>
												</div>
												<label class="pty_leftLabel" for="trigger">Fade Popup</label>
												<div class="pty_rightControls">
													<input type="checkbox" name="fade" id="pty_fade" class="inp-slider" value="0"/>
												</div>
											</div>
											<div class="pty_clear"></div>
											<div class="pty_tooltip pty_settingsContainer" id="pty_animateSettingShell">
												<div class="pty_ttContent">
													<p>
														Animate Popup Entrance
													</p>
												</div>
												<label class="pty_leftLabel" for="trigger">Animate Popup</label>
												<div class="pty_rightControls">
													<input type="checkbox" name="animate" id="pty_animateSetting" class="inp-slider" value="0"/>
												</div>
											</div>
										</div>
										<a href="#" id="pty_toggleSettings-submitBehaviorShell" class="pty_settingsHead">Submit Behavior <span class="pty_settingsToggleText">Hide</span></a>
										<div id="pty_submitBehaviorShell" class="pty_sideForm">
											<div class="pty_tooltip pty_settingsContainer" id="pty_ajaxSubmitShell">
												<div class="pty_ttContent">
													<p>
														This submits the form without requiring the reader to leave the page
														then closes the popup.
													</p>
												</div>
												<label class="pty_leftLabel" for="trigger">Submit Form Via AJAX</label>
												<div class="pty_rightControls">
													<input type="checkbox" name="ajaxSubmit" id="pty_ajaxSubmit" class="inp-slider" value="0"/>
												</div>
											</div>
											<div class="pty_tooltip pty_settingsContainer" id="pty_new_pageSettingShell">
												<div class="pty_ttContent">
													<p>
														This opens a new window for your form submission
														so your reader doesn\'t lose their place on your site.
													</p>
												</div>
												<label class="pty_leftLabel" for="trigger">Submit Form in a New Page</label>
												<div class="pty_rightControls">
													<input type="checkbox" name="new_page" id="pty_new_page" class="inp-slider" value="0"/>
												</div>
											</div>
											<br/>
										</div>
										<a href="#" id="pty_toggleSettings-basicFiltersShell" class="pty_settingsHead">Basic Filters <span class="pty_settingsToggleText">Show</span></a>
										<div id="pty_basicFiltersShell" class="pty_sideForm" style="display:none;">
											<div id="pty_basicFiltersOff">
												Clear all power-filters to use basic filters.
											</div>
											<div id="pty_basicFiltersOn">
												<div class="pty_tooltip pty_settingsContainer">
													<div class="pty_ttContent">
														<p>
															Enable if you only want your popups to show on post pages.	
														</p>
													</div>
													<label class="pty_leftLabel" for="expire">Only Show on Posts</label>
													<div class="pty_rightControls">
														<input type="checkbox" name="postOnly" id="pty_postOnly" class="inp-slider pty_basicFilterToggle" value="0"/>
													</div>
												</div>  
												<div class="pty_clear"></div>
												<div class="pty_tooltip pty_settingsContainer">
													<div class="pty_ttContent">
														<p>
															The logged in status of users who should see this.
														</p>
													</div>
													<label class="pty_leftLabel" for="expire">Only Show Logged Out Users</label>
													<div class="pty_rightControls">
														<input type="checkbox" name="loggedOutOnly" id="pty_loggedOutOnly" class="inp-slider pty_basicFilterToggle" value="0"/>
													</div>
												</div>  
											</div>
											<div class="pty_clear"></div>
										</div>
										<a href="#" id="pty_toggleSettings-powerFiltersShell" class="pty_settingsHead">Power Filters <span class="pty_settingsToggleText">Show</span></a>
										<a href="#" id="pty_toggleSettings-powerSettingsShell" class="pty_settingsHead">Power Settings <span class="pty_settingsToggleText">Show</span></a>
										<div id="pty_powerSettingsShell" class="pty_sideForm" style="display:none;">
											<div class="pty_tooltip pty_settingsContainer">
												<div class="pty_ttContent">
													<p>
														Show popup based on a unique cookie. Use if you want to show this popup
														even if another has already appeared. Blank or \'visited\' uses the default cookie. <a href="http://pippity.com/how-to-filter-your-popups#goto_custom_cookie" target="_blank">More Info</a>
													</p>
												</div>
												<label class="pty_leftLabel" for="customCookie">Custom Cookie</label>
												<div class="pty_rightControls">
													<input value="" type="text" name="customCookie" id="pty_customCookie" class="inp-text inp-hunds"/>
												</div>
											</div>  
											<div class="pty_clear"></div>
											<div class="pty_tooltip pty_settingsContainer">
												<div class="pty_ttContent">
													<p>
														Popups with higher priority are shown over others. Those with the same priority show randomly. The default is 0. <a href="http://pippity.com/how-to-filter-your-popups#goto_priority" target="_blank">More Info</a>
													</p>
												</div>
												<label class="pty_leftLabel" for="Priority">Priority</label>
												<div class="pty_rightControls">
													<input value="0" type="text" name="priority" id="pty_priority" class="inp-text inp-hunds"/>
												</div>
											</div>  
											<div class="pty_clear"></div>
										</div>
										<a href="#" id="pty_toggleSettings-eventsShell" class="pty_settingsHead">Events <span class="pty_settingsToggleText">Show</span></a>
										<div id="pty_eventsShell" style="display:none">
											<label>On Popup Load</label>
											<textarea id="pty_event-loaded" name="loaded" class="pty_event_inp"></textarea>
											<label>On Popup Open</label>
											<textarea id="pty_event-open" name="open" class="pty_event_inp"></textarea>
											<label>On Popup Close</label>
											<textarea id="pty_event-closed" name="closed" class="pty_event_inp"></textarea>
											<label>On Popup Submit</label>
											<textarea id="pty_event-submitted" name="submitted" class="pty_event_inp"></textarea>
										</div>
									</form>
									<div class="pty_saveShell">
										<input id="pty_saveSettings" type="submit" href="#" class="sNav-next button-secondary" value="Save & Continue"/>
									</div>
								</div>
							</div>  
							<div id="sNav-newsletter" class="sNav-pane">
								<div class="sNav-heading">Newsletter Settings</div>
									<div class="inside">
										<form id="pty_newsletterForm" class="pty_sideForm" action="#" method="post">
											<a href="#" id="pty_toggleSettings-gravityConnectShell" class="pty_gravity_elm pty_settingsHead">Gravity Forms Connect<span class="pty_settingsToggleText">Hide</span></a>
											<div id="pty_gravityConnectShell" class="pty_sideForm">
													<div>
														<div id="pty_gravity_connect" class="pty_focusText pty_gravity_elm">
															<p>You Can Connect Your Gravity Forms to Pippity!</p>
															<p> Just select the gravity Form below. Pippity will take
															care of the rest!</p>
															<br/>
															<label>Automagic Connect</label>
															<select id="pty_gravity_pippity_forms" class="pty_gravity_elm"></select>

															<label>Custom</label>
															<select id="pty_gravity_custom_forms" class="pty_gravity_elm"></select>
															<div id="pty_gravity_custom_msg">You may want to add some custom CSS on the Style Settings Panel</div>
														</div>
													</div>
											</div>
											<a href="#" id="pty_toggleSettings-wysijaConnectShell" class="pty_wysija_elm pty_settingsHead">Mail Poet Connect<span class="pty_settingsToggleText">Hide</span></a>
											<div id="pty_wysijaConnectShell" class="pty_sideForm">
													<div>
														<div id="pty_wysija_connect" class="pty_focusText pty_wysija_elm">
															<p>You Can Connect Your Mail Poet Form to Pippity!</p>
															<p>Just select the Mail Poet Form below. Pippity will take
															care of the rest!</p>
															<br/><br/>
															<select id="pty_wysija_forms" class="pty_wysija_elm"></select>
														</div>
													</div>
											</div>
											<a href="#" id="pty_toggleSettings-serviceConnectShell" class="pty_settingsHead">Automagic <span class="pty_settingsToggleText">Hide</span></a>
											<div id="pty_serviceConnectShell" class="pty_sideForm">
													<div>
														<div class="pty_focusText pty_std_elm">
															<p>Connect your newsletter service
															with Pippity.</p>
															<p>
															Just paste the Webform HTML it provides below. Pippity will take
															care of the rest!
															<br/><br/>
															<a href="http://pippity.com/connecting-newsletter-services" target="_blank">Click here for more help</a>
															</p>
														</div>
														<label for="newsletterHtml" class="pty_std_elm">Newsletter HTML</label>
														<div id="pty_newsletterRsp"></div>
														<textarea name="newsletterHtml" class="pty_std_elm" id="pty_newsletterHtml"></textarea>
													</div>
												<div class="pty_clear"></div>
											</div>
											<a href="#" id="pty_toggleSettings-manualConnectShell" class="pty_settingsHead">Manual Connect<span class="pty_settingsToggleText">Show</span></a>
											<div id="pty_manualConnectShell" class="pty_sideForm" style="display:none;">
												<p id="pty_customFormMsg">
													Be sure to fill in then <i>name</i> attributes of the input fields below based on your form\'s HTML.
													<a href="http://pippity.com/connecting-to-a-custom-form" target="_blank">Need help?</a>
												</p>
												<label>Form Action</label>
												<input type="text" id="pty_formAction" name="formAction">
												<label>Form Inputs</label>
												<div id="pty_formInputs">
													<div data-field="name" id="pty_fieldShell-name" class="pty_formInputShell pty_formPartShell">
														<div class="pty_formInputMoveShell">
															<a href="#" class="pty_formInputMove" data-dir="up">▲</a>
															<a href="#" class="pty_formInputMove" data-dir="dn">▼</a>
														</div>
														<input type="text" id="pty_formInputName-name" class="pty_formPartInp pty_formPartLabel" name="formInputName[]" value="name"/>
														<input type="text" id="pty_formInputValue-name" class="pty_formInputName pty_formPartInp pty_formPartValue" name="formInputLabel[]"/>
														<a href="#" data-field="name" class="pty_formInputRemove pty_formPartRemove">x</a>
													</div>
													<div data-field="email" id="pty_fieldShell-email" class="pty_formInputShell pty_formPartShell">
														<div class="pty_formInputMoveShell">
															<a href="#" class="pty_formInputMove" data-dir="up">▲</a>
															<a href="#" class="pty_formInputMove" data-dir="dn">▼</a>
														</div>
														<input type="text" id="pty_formInputName-email" class="pty_formPartInp pty_formPartLabel" name="formInputName[]" value="email"/>
														<input type="text" id="pty_formInputValue-email" class="pty_formInputName pty_formPartInp pty_formPartValue" name="formInputLabel[]"/>
														<a href="#" data-field="email" class="pty_formInputRemove pty_formPartRemove">x</a>
													</div>
												</div>
												<a href="#" class="pty_formFieldAdd pty_formPartAdd">Add a Field</a>
												<label>Hidden Values</label>
												<div id="pty_formHiddens">
												</div>
												<a href="#" class="pty_formHiddenAdd pty_formPartAdd">Add a Hidden Value</a>
												<div class="pty_clear"></div>
											</div>
											<a href="#" id="pty_toggleSettings-advancedConnectShell" class="pty_settingsHead">Advanced<span class="pty_settingsToggleText">Show</span></a>
											<div id="pty_advancedConnectShell" class="pty_sideForm" style="display:none;">
												<div class="pty_checkShell">
													<input type="checkbox" id="pty_hideInput-name" class="pty_toggleInpVis"/><div class="pty_check_label">Hide Name Input</div>
												</div>
												<div class="pty_checkShell">
													<input type="checkbox" id="pty_hideInput-email" class="pty_toggleInpVis"/><div class="pty_check_label">Hide Email Input</div>
												</div>
												<label>Custom Form HTML</label>
												<p class="pty_labelInfo">This will completely replace the standard form.</p>
												<textarea id="pty_customFormHtml" name="customFormHtml"></textarea>
												<div class="pty_clear"></div>
											</div>
											<a href="#" id="pty_toggleSettings-notificationsShell" class="pty_settingsHead">Notifications<span class="pty_settingsToggleText">Show</span></a>
											<div id="pty_notificationsShell" class="pty_sideForm" style="display:none;">
													<label>Notification E-Mail Addresses</label>
													<div id="pty_notificationAddresses"> 
														<div class="pty_formPartShell">
															<input type="text" class="pty_formPartInp pty_notificationAddress"/>
															<a class="pty_formInputRemove pty_formPartRemove" href="#">x</a>
														</div>
													</div>
													<a href="#" class="pty_formNotificationAddressAdd pty_formPartAdd">Add a Another Address</a>
												<div class="pty_clear"></div>
											</div>
											<div id="pty_newsletterFinish">
												<!--<div class="pty_focusText pty_focusTextDisabled">
													<a href="' . PTY_ADM . '" class="button-secondary">Skip for Now</a>
												</div>-->
												<div class="pty_focusText">
													<a href="' . PTY_ADM . '" class="button-primary" id="pty_saveAndFinish">Save and Finish</a>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="has-sidebar"  style="width:100%;">
			<div id="post-body-content" class="has-sidebar-content"  style="margin-right:0; margin-left:340px;">
				<div class="meta-box-sortabless">
					<div class="postbox">
						<div class="handlediv"></div>
						<h3 class="hndle">Sample <span id="pty_popupStatus"></span> <a href="#" id="pty_toggleFullFrame">Show Full-Size</a></h3>
							<a href="#" id="pty_refreshTheme"><img src="'. PTY_URL. '/images/refresh.png"/></a>
						<div class="inside">
							<iframe id="pty_example" src="' . get_bloginfo('wpurl') . '/wp-admin/admin-ajax.php?incoming_ajax=true&action=pty_getLastPost"></iframe>
						</div>
					</div>
				</div>
			</div>  
		</div>   
	</div>
</div>

<div id="pty_filtersPkg">
	<div id="pty_filtersOverlay"></div>
	<div id="pty_filtersShell">
		<a href="#" id="pty_closeFilters">x</a>
		<div id="pty_filtersView-add" class="pty_filterView">
			<h4>Add a Filter</h4>
			<div id="pty_addFilterPrimary">
				<form action="#" id="pty_addFilterForm" method="post">
					<select id="pty_filtersIsSelect" class="pty_filterLargeSelect" name="isSelect">
						<option value="1"> &nbsp; Is &nbsp; </option>
						<option value="0"> &nbsp;  &nbsp; Isn\'t &nbsp;  &nbsp; </option>
					</select>
					<select id="pty_filtersType" class="pty_filterLargeSelect" name="typeSelect">
						<option value="">Select Filter Type</option>
						<option value="pagetype">page type</option>
						<option value="post">specific post</option>
						<option value="cat">in category</option>
						<option value="referred">referred by</option>
						<option value="loggedin"> user status </option>
						<option value="type">custom post type</option>
						<option value="url">URL</option>
						<option value="roles">User Role</option>
					</select>
				</form>
			</div>
			<div id="pty_addFilterSecondary"></div>
			<div class="clear"></div>
			<a href="#" id="pty_doAddFilter" class="pty_filterButton">Add Filter</a>
			<a href="#" id="pty_cancelAddFilter" class="pty_filterButton">Cancel</a>
			<div id="pty_filterError"></div>
		</div>
		<div id="pty_filtersView-filters" class="pty_filterView">
			<h4>Power Filters</h4>
			<div id="pty_filters"></div>
			<a href="#" id="pty_addAndFilter-and" class="pty_filterButton">Add a Filter</a>
			<a href="#" id="pty_filterDone" class="pty_filterButton">Done</a>
		</div>
	</div>
</div>
';      
