<?php
/*
* list.php
*
* PHP file used to display all current campaigns
*/
?>

<div class="wrap" id="popup_domination">
	<?php
	$header_link = 'Mailing Configurations';
	$header_url = '#';
	include $this->plugin_path.'tpl/header.php';
	?>
	<div style="display:none" id="popup_domination_hdn_div"><?php echo $fields?></div>
	<div class="clear"></div>
	<div id="popup_domination_container" class="has-left-sidebar">
	<div style="display:none" id="popup_domination_hdn_div2"></div>
	<div class="mainbox" id="popup_domination_campaign_list">
		<div class="newcampaign">
			<a class="green-btn" href="<?php echo 'admin.php?page='.$this->menu_url.'mailinglist&amp;action=create'; ?>"><span>Create New Mailing Configuration</span></a>
			<p class="campaign-notice">You have <span id="row_count"><?php echo $count; ?></span> mailing provider(s).</p>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<?php foreach ($mailing_configs as $config): ?>
			<div class="camprow" id="camprow_<?php echo $config['id']; ?>" title="<?php echo $config['name']; ?>">
				<div class="tmppreview">
					<div class="preview_crop">
						<div class="spacing">
						<?php if($config['provider'] == 'mc'){
								$logo = $this->plugin_url.'css/img/mailchimp.png';
							}else if($config['provider'] == 'aw'){
								$logo = $this->plugin_url.'css/img/aweber.png';
							}else if($config['provider'] == 'ic'){
								$logo = $this->plugin_url.'css/img/icontact.png';
							}else if($config['provider'] == 'cc'){
								$logo = $this->plugin_url.'css/img/constant.png';
							}else if($config['provider'] == 'cm'){
								$logo = $this->plugin_url.'css/img/campaign.png';
							}else if($config['provider'] == 'gr'){
								$logo = $this->plugin_url.'css/img/response.png';
							}else if($config['provider'] == 'nm'){
								$logo = $this->plugin_url.'css/img/email.png';
								$alt = 'Send to Email';
							}else if ($config['provider'] == 'form'){
								$logo = $this->plugin_url.'css/img/htmlform.png';
								$alt = 'HTML Form Code';
							} else {
								$logo = '#';
								$alt = "Could not find a logo";
							} ?>
							<div class="slider"><h2><?php echo $config['provider']; ?></h2></div>
							<img class="img" id="logo_<?php echo $config['id']; ?>" src="<?php echo $logo; ?>" alt="<?php echo $alt; ?>" />
						</div>
					</div>
				</div>
				<div class="namedesc">
					<a href="<?php echo 'admin.php?page='.$this->menu_url.'mailinglist&amp;action=edit&amp;id='.$config['id']; ?>"><?php echo $config['name']; ?></a><br/>
					<p class="description"><?php echo $config['description']; ?></p>
				</div>
				<ul class="actions">
					<li><a href="#copy" class="copy_button" title="<?php echo $config['name']; ?>" data-id="<?php echo $config['id']; ?>">Duplicate</a></li>
					<li><a data-id="<?php echo $config['id']; ?>" title="<?php echo $config['name']; ?>" class="deletecamp thedeletebutton" href="#deletecamp">Delete</a></li>
				</ul>
				<div class="clear"></div>
			</div>
		<?php endforeach; ?>
		</div>
	<div class="clearfix"></div>
	<?php
	$page_javascript = 'var popup_domination_delete_table = "mailing", popup_domination_delete_stats = "";';
	include $this->plugin_path.'tpl/footer.php'; ?>
</div>
