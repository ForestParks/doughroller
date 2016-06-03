<?php if($this->success != false): ?>
<div id="message" class="updated"><p><?php echo $theme_message?></p></div>
<?php endif; ?>
<div class="mainbox" id="popup_domination_tab_themebuy">
    <div class="inside twodivs">
        <div class="popdom_contentbox the_help_box">
            <h3 class="help">Help</h3>
            <div class="popdom_contentbox_inside">
                <p>Here you can purchase and install additional themes to your PopUp Domination</p>
                <p>Simply purchase the chosen theme below then paste your order number into the box below.</p>
            </div>
            <div class="clear"></div>
        </div>
        <div class="popdom-inner-sidebar">
            <div class="postbox">
                <div class="popdom_contentbox the_content_box">
                    <h3>Theme Installer</h3>
                    <div class="popdom_contentbox_inside">
                        If you have an order number for a theme please paste it here to install your theme.
                        <form action="<?php echo $this->opts_url?>#themebuy" method="post" id="popup_domination_form">
                            <input type="text" name='themenumber' placeholder="Order number from your ClickBank receipt" />
                            <input class="green-btn" type="submit" value="Install theme" />
                        </form>
<?php 
$pd_theme_url = "http://popupdomination.com/3/theme-uploader-themes/";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $pd_theme_url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_exec($ch);
curl_close($ch);
?>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
</div>