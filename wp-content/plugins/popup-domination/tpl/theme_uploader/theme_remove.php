<div class="mainbox" id="popup_domination_tab_themeremove" style="display:none;">
    <div class="inside twodivs">
        <div class="popdom_contentbox the_help_box">
            <h3 class="help">Help</h3>
            <div class="popdom_contentbox_inside">
                <p>Here you can remove installed themes from your PopUp Domination</p>
            </div>
            <div class="clear"></div>
        </div>
        <div class="popdom-inner-sidebar">
            <div class="postbox">
                <div class="popdom_contentbox the_content_box">
                    <h3>Theme Removal</h3>
                    <div class="popdom_contentbox_inside">
                        To remove a theme please select it from the dropdown and click "Remove"
                        <form action="<?php echo $this->opts_url?>#themeremove" method="post">
                            <select name='removetheme'>
                                <option value="" selected>Please select a theme</option>
                                <?php 
                                    foreach($this->themes as $theme)
                                    {
                                        $preview_img = $this->theme_url . $theme['colors'][0]['info'][2];
                                ?>
                                <option value="<?php echo $theme['theme']?>" style="padding:1px;background-image:url(<?php echo $preview_img ?>);background-size:contain;background-position:right;background-repeat:no-repeat;"><?php echo $theme['name']?> </option>
                                <?php
                                    }
                                ?>
                            </select>
                            <input class="green-btn" type="submit" value="Remove" />
                        </form>

                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
</div>