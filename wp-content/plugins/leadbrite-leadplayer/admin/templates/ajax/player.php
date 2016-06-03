<?php
?>
<form action="" method="POST" class="form-horizontal" id="player_settings_form">
    <input type="hidden" name="action" value="post_player_settings">

            <div class="control-group">
                <label class="control-label">Video SEO</label>
                <div class="controls">
                    <div class="btn-group onoff" data-toggle="buttons-radios" data-target="video_seo">
                        <button class="btn btn-on" data-value="1">Enabled</button>
                        <button class="btn btn-off" data-value="0">Disabled</button>
                    </div>
                    <a class="lp-help-ico check-style" rel="popover" data-original-title="Video SEO" data-content="Enable Video SEO to have your videos indexed by Google and other search engines.">&nbsp;</a>
                    <a class="lp-warn-ico check-style" rel="popover" data-original-title="Important Note!" data-content="When 'enable video SEO' is turned on, the YouTube URL for this video can be more easily discovered.">&nbsp;</a>
                    <input type="checkbox" style="display:none" id="video_seo" name="video_seo" value="1" <?php echo ($data['video_seo']!==false) ? 'checked="checked"' : ''?>/>
                </div>
            </div>

<?php if ($data['extras']) { ?>
            <div class="control-group">
                <label class="control-label">LP&nbsp;Logo When Loading</label>
                <div class="controls">
                    <div class="btn-group onoff subsection" data-subsection="branding" data-toggle="buttons-radios" data-target="branding">
                        <button class="btn btn-on" data-value="1">Show</button>
                        <button class="btn btn-off" data-value="0">Hide</button>
                    </div>
                    <a class="lp-help-ico check-style" rel="popover" data-original-title="LeadPlayer&trade; Logo When Loading" data-content="Hide or show LeadPlayer&trade; branding in videos.">&nbsp;</a>
                    <input type="checkbox" style="display:none" id="branding" name="branding" value="1" <?php echo ($data['branding']!==false) ? 'checked="checked"' : ''?>/>
                </div>
            </div>
<?php } ?>

            <div class="control-group">
                <label class="control-label">Powered By LP Logo</label>
                <div class="controls">
                    <div class="btn-group onoff subsection" data-subsection="powered_by" data-toggle="buttons-radios" data-target="powered_by">
                        <button class="btn btn-on" data-value="1">Show</button>
                        <button class="btn btn-off" data-value="0">Hide</button>
                    </div>
                    <a class="lp-help-ico check-style" rel="popover" data-original-title="Powered By LeadPlayer&trade; Logo" data-content="Display a &quot;Powered By LeadPlayer&quot; link below your videos. This gives you a great opportunity to tell others about LeadPlayer (and link to your review of LeadPlayer &hellip; if you've written one).">&nbsp;</a>
                    <input type="checkbox" style="display:none" id="powered_by" name="powered_by" value="1" <?php echo ($data['powered_by']!==false) ? 'checked="checked"' : ''?>/>
                </div>
            </div>
            
            <fieldset class="control-group">
                <label class="control-label" for="powered_by_link">Powered By LP Link</label>
                <div class="controls">
                    <input type="textbox" class="input-xxlarge" name="powered_by_link" id="powered_by_link" value="<?php echo htmlspecialchars($data['powered_by_link'], ENT_COMPAT); ?>">
                    <a class="lp-help-ico" rel="popover" data-original-title="Powered By LeadPlayer&trade; Link" data-content="This is the URL people will go to when they click on your &quot;Powered By LeadPlayer&quot; link.">&nbsp;</a>
                </div>
            </fieldset>

            <div class="control-group">
                <label class="control-label">Google&nbsp;Analytics&nbsp;Events</label>
                <div class="controls">            
                    <div class="btn-group onoff" data-toggle="buttons-radios" data-target="use_ga">
                        <button class="btn btn-on" data-value="1">Enabled</button>
                        <button class="btn btn-off" data-value="0">Disabled</button>
                    </div>
                    <a class="lp-help-ico check-style" rel="popover" data-original-title="Enable GA Tracking" data-content="Do you wish information about videos (loads & actions) to be tracked in your Google Analytics account? To use Google Analytics to track your LeadPlayer video actions, you must have Google Analytics tracking set up on the page where you place your video(s) for the events to be successfully tracked. And you have to be using the asynchronous tracking code.">&nbsp;</a>
                    <input type="checkbox" class="input-xlarge" style="display:none" id="use_ga" name="use_ga" value="1" <?php echo ($data['use_ga']!==false) ? 'checked="checked"' : ''?>/>
                </div>
            </div>

            <fieldset class="control-group">
                <label class="control-label" for="color1">Opt-In Button Color</label>
                <div class="controls">
                    <input type="textbox" class="input-small color-picker" name="color1" id="color1" value="<?php echo htmlspecialchars($data['color1'], ENT_COMPAT); ?>">
                    <a class="lp-help-ico" rel="popover" data-original-title="Opt-In Button Color" data-content="This color will be the main background color used on opt-in button.">&nbsp;</a>
                </div>
            </fieldset>

            <fieldset class="control-group">
                <label class="control-label" for="color2">Opt-In Hover Color</label>
                <div class="controls">
                    <input type="textbox" class="input-small color-picker" name="color2" id="color2" value="<?php echo htmlspecialchars($data['color2'], ENT_COMPAT); ?>">
                    <a class="lp-help-ico" rel="popover" data-original-title="Opt-In Button Hover Color" data-content="This color will be the background color used on opt-in button when hovered over with the mouse.">&nbsp;</a>
                </div>
            </fieldset>
            
            <fieldset class="control-group">
                <label class="control-label" for="color3">Call To Action Color</label>
                <div class="controls">
                    <input type="textbox" class="input-small color-picker" name="color3" id="color3" value="<?php echo htmlspecialchars($data['color3'], ENT_COMPAT); ?>">
                    <a class="lp-help-ico" rel="popover" data-original-title="Call To Action Text " data-content="This color will be used on call to action text.">&nbsp;</a>
                </div>
            </fieldset>

            <fieldset class="control-group">
                <label class="control-label" for="txt_play">Play Button Text</label>
                <div class="controls">
                    <input type="textbox" class="input-medium" name="txt_play" id="txt_play" value="<?php echo htmlspecialchars($data['txt_play'], ENT_COMPAT); ?>">
                    <a class="lp-help-ico" rel="popover" data-original-title="Play Button Text" data-content="Text on the play button when opt-in is displayed at the beginning or at a custom point in the video.">&nbsp;</a>
                </div>
            </fieldset>
            
            <fieldset class="control-group">
                <label class="control-label" for="txt_submit">Submit Button Text</label>
                <div class="controls">
                    <input type="textbox" class="input-medium" name="txt_submit" id="txt_submit" value="<?php echo htmlspecialchars($data['txt_submit'], ENT_COMPAT); ?>">
                    <a class="lp-help-ico" rel="popover" data-original-title="Submit Button Text" data-content="Text on the submit button if opt-in is displayed at the end of the video.">&nbsp;</a>
                </div>
            </fieldset>

            <fieldset class="control-group">
                <label class="control-label" for="txt_name">Name Field Text</label>
                <div class="controls">
                    <input type="textbox" class="input-xlarge" name="txt_name" id="txt_name" value="<?php echo htmlspecialchars($data['txt_name'], ENT_COMPAT); ?>">
                    <a class="lp-help-ico" rel="popover" data-original-title="Name Field Text" data-content="Default value for name field (only if name field is enabled).">&nbsp;</a>
                </div>
            </fieldset>
            
            <fieldset class="control-group">
                <label class="control-label" for="txt_eml">Email Field Text</label>
                <div class="controls">
                    <input type="textbox" class="input-xlarge" name="txt_eml" id="txt_eml" value="<?php echo htmlspecialchars($data['txt_eml'], ENT_COMPAT); ?>">
                    <a class="lp-help-ico" rel="popover" data-original-title="Email Field Text" data-content="Default value for email field.">&nbsp;</a>
                </div>
            </fieldset>
            
            <fieldset class="control-group">
                <label class="control-label" for="txt_invalid_name">Invalid Name Message</label>
                <div class="controls">
                    <input type="textbox" class="input-xlarge" name="txt_invalid_name" id="txt_invalid_name" value="<?php echo htmlspecialchars($data['txt_invalid_name'], ENT_COMPAT); ?>">
                    <a class="lp-help-ico" rel="popover" data-original-title="Invalid Name Message" data-content="Error message text that is displayed when user does not insert name.">&nbsp;</a>
                </div>
            </fieldset>

            <fieldset class="control-group">
                <label class="control-label" for="txt_invalid_eml">Invalid Email Message</label>
                <div class="controls">
                    <input type="textbox" class="input-xlarge" name="txt_invalid_eml" id="txt_invalid_eml" value="<?php echo htmlspecialchars($data['txt_invalid_eml'], ENT_COMPAT); ?>">
                    <a class="lp-help-ico" rel="popover" data-original-title="Invalid Email Message" data-content="Error message text that is displayed when user inserts invalid email.">&nbsp;</a>
                </div>
            </fieldset>

</form>

<!--BLOCK_SEPARATOR-->

<a href="" class="btn btn-primary btn-large" data-loading-text="Saving &hellip;" id="save-player-btn">Save Player Options</a>
