<?php
/*
Pippity

Activate Page
*/
?>

<?php

echo '
<script type="text/javascript"> var PTY_PAGE = "ANALYTICS"; 
';
if (is_object($col1)) {
	echo 'anl.col1 = ' . $col1->getJson() . ';';
}
if (is_object($col2)) {
	echo 'anl.col2 = ' . $col2->getJson() . ';';
}



echo '
</script>
<div class="wrap">
	<h2>Pippity Analytics</h2>
	<div id="poststuff" class="metabox-holder has-right-sidebar">
		<div style="max-width:960px">
			<div id="side-sortables" class="meta-box-sortabless ui-sortable">
				<div class="postbox" id="pty_analyticsContent">
					<div>
						<h3 class="hndle">Pippity Analytics</h3>
						<div class="inside"> 
						 <div id="pty_rangeShell">
										<div id="pty_range"><span>All-time</span><cite>▼</cite></div>
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
									<div id="pty_chartTypeShell"><h1>Conversions<span class="right">▼</span></h1><dl class="pty_chartTypeList"></dl></div>
									<div class="clear"></div>
						<div id="pty_chartLoading"><img src="/wp-includes/js/tinymce/themes/advanced/skins/default/img/progress.gif"/></div>
						<div id="pty_mainChart"></div>
						<div id="pty_noData">Pippity needs to collect more data before analytics are available for these popups.</div>
						<div class="clear"></div>
						<table>
							<tr>
							<td id="pty_col1" class="pty_anl_col">' . (($pCount > 0) ? pty_colTemplate($col1->getPkg()) : pty_createPopup(1)) . '</td>
								<td id="pty_col2" class="pty_anl_col">' . ((is_object($col2) && $pCount > 1) ? pty_colTemplate($col2->getPkg()) : pty_createPopup(2)) . '</td>
							</tr>
						</table>
						</div>
					</div>
				</div>
			</div>
		</div>  
	</div>
</div>
';       

function pty_createPopup($col) {
	if ($col == 1) {
		$msg = 'You need to <a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=pty-edit">create a popup</a> to begin collecting analytics.';
	}
	if ($col == 2) {
		$msg = 'You can compare popups once you\'ve <a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=pty-edit">created</a> and activated more than one.';
	}
	return '<div class="pty_noPopupForAnalytics">' . $msg  . '</div>';
}
function pty_colTemplate($pkg) { 
	$html = '
		<div class="pty_pListShell">
			<h1>' . $pkg->label . ' <span class="right">▼</span></h1><dl class="pty_popupList"></dl>
		</div>
		<dl class="pty_pDataRow">
			<dt>Impressions</dt>
			<dd class="pty_data-imps">' . $pkg->a['impressions'] . '</dd>
		</dl>
		<dl class="pty_pDataRow">
			<dt>Conversions</dt>
			<dd class="pty_data-convs">' . $pkg->a['conversions'] . '</dd>
		</dl>
		<dl class="pty_pDataRow">
			<dt>Conversion Rate</dt>
			<dd class="pty_data-cRate">' . $pkg->a['cRate'] . '%</dd>
		</dl>
		<dl class="pty_pDataRow">
			<dt>Avg. Time on Popup</dt>
			<dd class="pty_data-closeTime">' . $pkg->a['closeTime'] . ' seconds</dd>
		</dl>
		<dl class="pty_pDataRow">
			<dt>Avg. Time on Page</dt>
			<dd class="pty_data-leaveTime">' . $pkg->a['leaveTime'] . ' seconds</dd>
		</dl>
	';
	return $html;
}
