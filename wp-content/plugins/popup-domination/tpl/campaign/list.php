<?php
/*
* list.php
*
* PHP file used to display all current campaigns
*/
?>
<div class="wrap" id="popup_domination">
	<?php
	$header_link = 'Campaign Management';
	$header_url = '#';
	include $this->plugin_path.'tpl/header.php';
	?>
	<div style="display:none" id="popup_domination_hdn_div"><?php echo $fields?></div>
	<div class="clear"></div>
	<div id="popup_domination_container" class="has-left-sidebar">
	<div style="display:none" id="popup_domination_hdn_div2"></div>
	<div class="mainbox" id="popup_domination_campaign_list">
	    <div class="popdom_contentbox the_help_box">
      <h3 class="help">Help</h3>
      <div class="popdom_contentbox_inside">
        <p>Use the buttons below to create new campaigns</p>
        <ul>
          <li>Popup campaigns provide a lightbox overlay to the page</li>
          <li>In-post campaigns appear at the end of your posts content if you choose the pages and/or anywhere you paste the shortcode in a post, page or sidebar widget</li>
          <!--<li>Sidebar campaigns provide a shortcode to paste into a text widget for your sidebar</li>-->
        </ul>
      </div>
      <div class="clear"></div>
    </div>
		<div class="newcampaign">
			<a class="green-btn" href="<?php echo 'admin.php?page='.$this->menu_url.'campaigns&action=create'; ?>"><span>Add New Popup Campaign</span></a>
		  <a class="green-btn" href="<?php echo 'admin.php?page='.$this->menu_url.'campaigns&action=create&type=inline'; ?>"><span>Add New In-post Campaign</span></a>
		  <!--<a class="green-btn" href="<?php echo 'admin.php?page='.$this->menu_url.'campaigns&action=create&type=sidebar'; ?>"><span>Add New Sidebar Campaign</span></a>-->


			<p class="campaign-notice">You have <span id="row_count"><?php echo $count; ?></span> campaign(s).</p>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<?php foreach ($campaigns as $campaign): ?>
			<div class="camprow" id="camprow_<?php echo $campaign['id']; ?>" title="<?php echo $campaign['id']; ?>">
				<div class="tmppreview">
					<div class="preview_crop">
						<div class="spacing">
							<div class="slider"><h2><?php echo (!empty($c)) ? $tempname[$c->id] : ''; ?></h2></div>
							<img class="img" id="test" src="<?php echo $campaign['previewurl']; ?>" height="<?php echo $campaign['height']; ?>" width="<?php echo $campaign['width']; ?>" />
						</div>
					</div>
				</div>
				<div class="namedesc">
				<?php 
				  $type = ($campaign['inpost']) ? "&type=inline" : "" ;
				?>
					<a href="<?php echo 'admin.php?page='.$this->menu_url.'campaigns&action=edit&id='.$campaign['id'].$type; ?>"><?php echo $campaign['name']; ?></a><br/>
					 <?php
					   $type = "Popup"; 
					   if (!empty($campaign['inpost'])) $type = "In-post";
					   if (!empty($campaign['sidebar'])) $type = "Sidebar";
					 ?>
					<p class="description"><?php echo $campaign['desc']; ?></p>
					<p class="campstyle">(<?php echo $type ?> campaign)</p>
					<?php if ($type == "In-post") { ?>
					   <p class="popdom-quick-shortcode">Shortcode: <input type="text" value="[popdom id='<?php echo $campaign['id']?>']" readonly style="border:none;text-align:center;"/></p>
					<?php } ?>
				</div>
				<ul class="actions">
					<li><a title="<?php echo $campaign['name']; ?>" class="view_analytics" href="admin.php?page=<?php echo $this->menu_url; ?>analytics&id=<?php echo $campaign['id'] ?>">Analytics</a></li>
					<li><a data-id="<?php echo $campaign['id']; ?>" title="<?php echo $campaign['name']; ?>" class="copy_button" href="#copy">Duplicate</a></li>
					<li><a data-id="<?php echo $campaign['id']; ?>" title="<?php echo $campaign['name']; ?>" class="toggle_button <?php echo ($campaign['active']) ? 'off':'on';?>" href="#toggle"><?php echo ($campaign['active']) ? 'ON | <span style="color:silver">OFF</span>':'<span style="color:silver">ON</span> | OFF';?></a></li>
					<li><a data-id="<?php echo $campaign['id']; ?>" title="<?php echo $campaign['name']; ?>" class="deletecamp thedeletebutton" href="#deletecamp">Delete</a></li>
				</ul>
				<div class="clear"></div>
			</div>
		<?php endforeach; ?>
		</div>
	<div class="clearfix"></div>
	<?php
	$page_javascript = '';
	$page_javascript = 'var popup_domination_delete_table = "campaigns", popup_domination_delete_stats = "";';
	include $this->plugin_path.'tpl/footer.php'; ?>
</div>
