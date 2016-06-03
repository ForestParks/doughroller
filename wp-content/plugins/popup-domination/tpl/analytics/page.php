<?php

/**
* page.php
*
* Template - This template is used to display all the analytic data for a campaign.
*/

?>

<div class="wrap wider" id="popup_domination">
	<?php
	$header_link = 'Back to Analytics Menu';
	$header_url = 'admin.php?page=popup-domination/analytics';
	include $this->plugin_path.'tpl/header.php';
	?>
	<div style="display:none" id="popup_domination_hdn_div"><?php echo $fields?></div>
	<div class="clear"></div>
	<div id="popup_domination_container" class="has-left-sidebar">
		<div style="display:none" id="popup_domination_hdn_div2"></div>
		<div id="graph-wrapper">
			<div class="chart">
			<br/><br/>
				<h2>Current Month's Analytic Data for Campaign : <?php echo $campaign_name; ?></h2>
				<br/>
				<table style="display:none" id="data-table" border="1" cellpadding="10" cellspacing="0" summary="Current Month's Analytic Data for Campaign :">
					<tbody>
						<tr>
							<th scope="row">Views</th>
							<td><?php echo intval($months_views); ?></td>
						</tr>
						<tr>
							<th scope="row">Conversions</th>
							<td><?php echo intval($months_conversions); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php $has_previous = true;
		       	$yearcount = count($analytics);
		       	if ($yearcount == 1){
			       	$monthcount = count($analytics[$year]);
			       	if ($monthcount == 1){
				       	$has_previous = false;
			       	}
		       	}
		       	if($has_previous && false):
		       		$monthsviews = 0; $monthsconv = 0 ?>
				<div class="charttwo">
				<br/><br/>
					<h2>Last 5 Month's Analytic Data for Campaign : <?php echo $campaign_name; ?></h2>
					<br/>
					<table id="data-table-two" style="display:none;" border="1" cellpadding="10" cellspacing="0" summary="Current Month's Analytic Data for Campaign :">
						<thead>
							<tr>
								<th></th>
							<?php foreach($previous_data as $month => $stats): ?>
								<th scope="col"><?php echo $month; ?></th>
							<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th scope="row">Views</th>
								<?php foreach($previous_data as $stats): ?>
									<td><?php echo intval($stats['views']); ?></td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<th scope="row">Conversions</th>
								<?php $avg = 0; $c = 0; foreach($previous_data as $stats): ?>
									<td><?php echo intval($stats['conversions']); ?></td>
									<?php $monthsconv = $monthsconv + intval($stats['conversions']);
									$monthsviews = $monthsviews + intval($stats['views']);
									$lstavg = (intval($monthsviews) != 0) ? round((intval($monthsconv) / intval($monthsviews)) * 100) : 0;
									$avg += $lstavg; $c++;
									endforeach; ?>
							</tr>
						</tbody>
					</table>
				</div>
			<?php endif; ?>
			
			
			<?php
				$all_months = array(); $all_views = array(); $all_conversions = array();
		       	$has_previous = false;
		       	$yearcount = count($analytics);
		       	if ($yearcount >= 1){
			       	$monthcount = count($analytics[$year]);
			       	if ($monthcount > 1){
				       	$has_previous = true;
			       	}
		       	}
		       	$i = 1; $total = 0;
		       	$recorded = 1;
		       	if($has_previous){
		       		while($i <= 5){
		       			$time = strtotime("-$i month");
		       			$month = date('m', $time);
		       			$all_months[] = date('F', $time);
			       		if ($month != 12){
			       			$views = 0; $conversions = 0;
				       		if (is_array($analytics[$year]) && array_key_exists($month, $analytics[$year])){
				       			$views = $analytics[$year][$month]['views'];
				       			$conversions = $analytics[$year][$month]['conversions'];
				       			if ($views != 0){
					       			$total += abs(round(100 * intval($conversions) / intval($views)));
					       			if ($i == 1){
						       			$lastmonth = $total;
					       			}
				       			}
				       			$recorded = $i;
				       		}
				       		$all_views[] = $views;
				       		$all_conversions[] = $conversions;
				       	} else {
			       			$time = strtotime('-1 year');
				       		$year = date('Y', $time);
				       		//$i--;
			       		}
			       		$i++;
		       		}
		       		$average = abs(round($total / $recorded)); ?>
		       	
		       	<div class="charttwo">
					<br/><br/>
					<h2>Last 5 Month's Analytic Data for Campaign : <?php echo $campaign_name; ?></h2>
					<br/>
					<table id="data-table-two" style="display:none;" border="1" cellpadding="10" cellspacing="0" summary="Current Month's Analytic Data for Campaign :">
						<thead>
							<tr>
								<th></th>
							<?php foreach($all_months as $month): ?>
								<th scope="col"><?php echo $month; ?></th>
							<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th scope="row">Views</th>
								<?php foreach($all_views as $view): ?>
									<td><?php echo $view; ?></td>
								<?php endforeach; ?>
							</tr>
							<tr>
								<th scope="row">Conversions</th>
								<?php foreach($all_conversions as $conversion): ?>
									<td><?php echo $conversion; ?></td>
								<?php endforeach; ?>
							</tr>
						</tbody>
					</table>
				</div>
		       	<?php } ?>
		       	
				
				
				
				
				
			<div class="averages">
				<div class="percent">
					<?php $math = (intval($months_views) == 0) ? 0: round((intval($months_conversions) / intval($months_views)) * 100); ?>
					<h2>Conversion Percentage:</h2>
					<?php $class = $math <= $lastmonth ? 'red': 'green'; ?>
					<h1 class="<?php echo $class; ?>"><?php echo $math.'%'; ?></h1>
				</div>
			<?php if($has_previous): ?>
				<div class="lst-average">
					<h2>Last Month's Conversion Percentage</h2>
					<center><h1><?php echo round($lastmonth).'%';?></h1></center>
				</div>
				<div class="average-percent">
					<h2>Last 5 Months Average Conversion</h2>
					<center><h1><?php echo $average.'%';?></h1></center>
				</div>
			<?php endif; ?>
			</div>
		</div>
	<?php include $this->plugin_path . 'tpl/footer.php'; ?>
</div>