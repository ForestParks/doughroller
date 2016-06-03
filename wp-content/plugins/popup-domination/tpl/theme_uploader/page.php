<?php
/*
* page.php
*
* Main page which holds all the information for the Theme Uploader option
*/
?>
<div class="wrap with-sidebar" id="popup_domination">
<?php
$header_link = 'Back to Campaign Management';
$header_url = 'admin.php?page=popup-domination/campaigns';
include $this->plugin_path.'tpl/header.php';
?>
    <div style="display:none" id="popup_domination_hdn_div"><?php echo $fields?></div>
    <div class="clear"></div>
    <div id="popup_domination_container" class="has-left-sidebar">
        <div style="display:none" id="popup_domination_hdn_div2"></div>

<?php include $this->plugin_path.'tpl/theme_uploader/tabs.php'; ?>

        <div class="notices" style="display:none;">
            <p class="message"></p>
        </div>
        <div class="flotation-device">
<?php include("uploader.php"); ?>
<?php include("themebuy.php");?>
<?php include("theme_remove.php");?>
        </div>
<?php
include $this->plugin_path.'tpl/footer.php'; ?>
    </div>
</div>