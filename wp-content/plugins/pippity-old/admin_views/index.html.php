<?php
/*
Pippity Main Admin Page
	*/
	?>
	<?php
	echo '
	<script type="text/javascript">
		jQuery(function(){ });
		var PTY_PAGE = "main";
	</script>
	<div class="wrap">
	';
	echo '
		<h2>Pippity Panel</h2>
		<div id="poststuff" class="metabox-holder has-right-sidebar">';
	if ($popups || $incPopups) {
		echo '<div style="width:321px;" class="inner-sidebar">
				<div style="width:320px;" id="side-sortables" class="meta-box-sortabless ui-sortable">
					<div id="pty-inf-shell" class="postbox">
						<div>
							<h3 class="hndle">Popup Info</h3>
							<div class="inside"> 
								<div id="pty_popupInfo">
									<div id="pty_infoStartText">Mouse-over a popup for details.</div>
									<div id="pty_infoTabs">
										<ul>
											<li><a href="#" class="selected pty-inf-tab" id="pty_inf_show-settings">Settings</a></li>
											<li><a href="#" class="pty-inf-tab" id="pty_inf_show-copy">Copy</a></li>
											<li><a href="#" class="pty-inf-tab" id="pty_inf_show-style">Style</a></li>
										</ul>
										<div class="clear"></div>
										<div id="pty_inf-settings" class="pty-inf-panel">
											<label>Theme Name</label>
											<div id="pty_inf-name" class="pty_popupText"></div> 
											<label>Pageviews Before Popup</label>
											<div id="pty_inf-visits" class="pty_popupText"></div>
											<label>Popup Delay</label>
											<div id="pty_inf-delay" class="pty_popupText"></div>
											<label>Article-End Trigger</label>
											<div id="pty_inf-trig" class="pty_popupText"></div>
											<label>Popup Again After</label>
											<div id="pty_inf-expire" class="pty_popupText"></div>
										</div>
										<div id="pty_inf-copy" class="pty-inf-panel"></div>
										<div id="pty_inf-style" class="pty-inf-panel"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>';
    }          
	echo ' 	<div class="has-sidebar">
				<div style="margin-right:340px" id="post-body-content" class="has-sidebar-content">
					<div class="meta-box-sortabless">
						<div id="pty-main-content" class="postbox">
							<div class="handlediv"></div>
							<h3 class="hndle">Your Popups <a class="pty_headLink" href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=pty-edit">Create New Popup</a></h3>
							<div class="inside">';
							if ($popups || $incPopups) {
								echo '
									<div id="pty_rangeShell">
										<div id="pty_range"><span>All-time</span>▼</div>
										<div id="pty_rangeSelectShell">
											<form action="#" method="post" id="pty_rangeForm">
												<div class="pty_dateBlock">
													<label for="rangeStart">Start</label>
													<input type="text" id="pty_rangeStart" name="rangeStart" class="pty_dateInp"/>
												</div>
												<div class="pty_dateBlock">
													<label for="rangeEnd">End</label>
													<input type="text" id="pty_rangeEnd" name="rangeEnd" class="pty_dateInp"/>
												</div>
												<div class="clear"></div>
												<div id="pty_rangepicker"></div>
												<div id="pty_rangeSubmitShell">
													<input type="submit" value="Set" class="button-primary"/><div id="pty_quickTimeShell" class="right"><a href="#" style="border-left:0" id="pty_range-allTime" class="pty_quickTime">All-time</a><a href="#" id="pty_range-pastWeek" class="pty_quickTime">Past Week</a><a href="#" id="pty_range-pastMonth" class="pty_quickTime">Past Month</a></div>
												</div>
											</form>
										</div>
									</div>
									<table id="pty_popupList">
										<thead>
											<th class="pty_imageTd"></th>
											<th class="pty_numTd">Impressions</th>
											<th>Conversions</th>
											<th>Rate</th>
											<th>
											</th>
										</thead>';
								$lastStatus = 0;
								global $pty;
								foreach ($popups as $p) {
									$label = $pty->ifExists($p->label, 'Popup #' . $p->popupid);
									if ($lastStatus && !$p->status) {
										echo '
										   <!-- <tr class="pty_actveSpacer"><td colspan="100"></td></tr>
											<tr class="pty_actveDivider"><td colspan="100"></td></tr>
											<tr class="pty_actveSpacer"><td colspan="100"></td></tr> -->
										';
									}
									$lastStatus = $p->status;
									echo '
										<tr class="pty_label pty_showInfo' . (($p->status) ? ' pty_activeLabel' : '') . '" id="pty_label-' . $p->popupid . '">
											<td colspan="6">
												<span>
													<div class="pty_controls">
														<a href="#" id="pty_activate-' . $p->popupid . '" class="pty_action pty_' . ((!$p->status) ? 'activate">Activate' : 'deactivate">Deactivate') . '</a>
														<a href="#" id="pty_delete-' . $p->popupid . '" class="pty_action pty_delete">Delete</a>
														<a href="#" id="pty_clone-' . $p->popupid . '" class="pty_action pty_clone">Clone</a>
														<a href="#" id="pty_edit-' . $p->popupid . '" class="pty_action pty_edit">Edit</a>
														<a href="#" id="pty_rename-' . $p->popupid . '" class="pty_action pty_rename">Rename</a>
														<a href="' . $postUrl . '#pty_open_' . $p->popupid . '" id="pty_view-' . $p->popupid . '" class="pty_action pty_view" target="_blank">View</a>
													</div>
													 <h4>' . $label . '</h4>  
													 <div class="pty_actionDropdown"></div>
												</span>
											</td>
										</tr>
										</tr>
										<tr class="pty_themeRow pty_showInfo" id="pty_theme-' . $p->popupid . '">
											<td class="pty_imageTd">
											<img src="' . $p->imgUrl . '"/>				
											</td>
											<td class="pty_numTd pty_impressions">
												' . $p->a['impressions'] . '
											</td>
											<td class="pty_numTd pty_conversions">
												' . $p->a['conversions'] . '
											</td>
											<td class="pty_numTd pty_cRate">
												' . $p->a['cRate'] . '%
											</td>
											<td>
												<div id="summaryVis-' . $p->popupid . '" class="pty_summaryVis"></div>
											</td>
										</tr>
									';
								}
								foreach ($incPopups as $p) {
									$label = $pty->ifExists($p->label, 'Popup #' . $p->popupid) . ' (Incomplete)';
									if ($lastStatus && !$p->status) {
										echo '
										   <!-- <tr class="pty_actveSpacer"><td colspan="100"></td></tr>
											<tr class="pty_actveDivider"><td colspan="100"></td></tr>
											<tr class="pty_actveSpacer"><td colspan="100"></td></tr> -->
										';
									}
									$lastStatus = $p->status;
									echo '
										<tr class="pty_label" id="pty_label-' . $p->popupid . '">
											<td colspan="6">
												<span>
													<div class="pty_controls">
														<a href="#" id="pty_edit-' . $p->popupid . '" class="pty_action pty_complete pty_edit">Complete</a>
														<a href="#" id="pty_delete-' . $p->popupid . '" class="pty_action pty_delete">Delete</a>
													</div>
													 <h4>' . $label . '</h4>  
													 <div class="pty_actionDropdown"></div>
												</span>
											</td>
										</tr>
										</tr>
										<tr id="pty_theme-' . $p->popupid . '">
											<td class="pty_imageTd">
											<img src="' . $p->imgUrl . '"/>				
											</td>
											<td class="pty_numTd pty_incompleteMsg" colspan="4">
											This Popup is Incomplete
											</td>
										</tr>
									';
								}
								echo '
									</table>
								';
							}
							else{
								echo '	
									<div class="pty_empty_content">
										You haven\'t setup any popups. <a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=pty-edit">Create one now!</a>
									</div>
								';
							}
	echo '
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>';

	if ($popups) {
		echo '
			<div id="pty_infoArrow"></div>
		';       
	}
