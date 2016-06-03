<form action="" method="POST" class="form-horizontal" id="vid_form_<?php echo $video_action ?>">
    <input type="hidden" name="video_action" value="<?php echo $video_action ?>">
    <input type="hidden" name="action" value="post_video_settings">
    <input type="hidden" name="preview" value="false">
<?php if ($video_action == 'edit') { ?>
    <input type="hidden" name="video_id" value="<?php echo $vid_id ?>" >
<?php } ?>

    <div class="row">
        <div class="span10" style="margin-left: 15px;">
            <ul class="nav nav-pills">
              <li class="active"><a href="#vid_cfg_main" data-toggle="tab">Video</a></li> &nbsp;
              <li><a href="#vid_cfg_opt" data-toggle="tab">Opt-In</a></li> &nbsp;
              <li><a href="#vid_cfg_cta" data-toggle="tab">Call to Action</a></li> &nbsp;
            </ul>
        </div>
    </div>

    <div class="tab-content">
        <div class="tab-pane active" id="vid_cfg_main"> <!-- main tab -->

<?php if ($video_action != 'global') { ?>
            <fieldset class="control-group">
                <label class="control-label" for="title">Video Title</label>
                <div class="controls">
                    <input type="textbox" class="input-xxlarge" name="title" id="title" value="<?php echo $data['title']; ?>">
                    <a class="lp-help-ico" rel="popover" data-original-title="Video Title" data-content="This is used internally for you to identify the video, and to name it in Google Analytics - this will not appear on your website. This will also be used for Video SEO if enabled.">&nbsp;</a>
                </div>
            </fieldset>

            <fieldset class="control-group">
                <label class="control-label" for="description">Video Description</label>
                <div class="controls">
                    <input type="textbox" class="input-xxlarge" name="description" id="description" value="<?php echo $data['description']; ?>">
                    <a class="lp-help-ico" rel="popover" data-original-title="Video Description" data-content="This will only be used for SEO and only if Video SEO is enabled in player settings. It will not be displayed anywhere else.">&nbsp;</a>
                </div>
            </fieldset>

            <fieldset class="control-group">
                <label class="control-label" for="yt_url" >YouTube URL</label>
                <div class="controls">
                    <input type="textbox" class="input-xxlarge" name="yt_url" id="yt_url" value="<?php echo $data['yt_url']; ?>">
                    <a class="lp-help-ico" rel="popover" data-original-title="YouTube Video URL" data-content="The &lt;b&gt;full URL&lt;/b&gt; for the YouTube video that you wish to use. For example: &lt;br&gt;&lt;b&gt;http://www.youtube.com/watch?v=cNywiZraHS4&lt;/b&gt;">&nbsp;</a>
                </div>
            </fieldset>
<?php } else { ?>
                <p><span class="label label-success">Note</span> &nbsp;These options will be used only when adding new videos. Changing them won't affect existing videos.<br><br></p>
<?php } ?>

            <fieldset class="control-group">
                <label class="control-label">Video Size</label>
                <div class="controls">
                    <div class="input-append">
                        <input type="textbox" class="input-mini" name="width" id="width" value="<?php echo intval($data['width']); ?>"><span class="add-on">px</span>
                    </div>
                    &nbsp;<span style="font-size:20px;vertical-align:middle;font-weight:bold;display:inline-block">×</span>&nbsp;
                    <div class="input-append">
                        <input type="textbox" class="input-mini" name="height" id="height" value="<?php echo intval($data['height']); ?>"><span class="add-on">px</span>
                    </div>
                    <div class="btn-group drop-inline" id="video-ratio-tool">
                      <button class="btn btn-small dropdown-toggle" data-toggle="dropdown"><span id="ratio-label">16:9</span> <span class="caret"></span></button>
                      <ul class="dropdown-menu">
                        <li><a id="ratio-169" data-value="w" href="">16:9</a>
                        <li><a id="ratio-43" data-value="s" href="">4:3</a>
                        <li><a id="ratio-custom" data-value="c" href="">Custom</a>
                      </ul>
                    </div>
                    <a class="lp-help-ico" rel="popover" data-original-title="Dimensions of the Video" data-content="The minimum for the player to render with all the components is 400x350. You can choose from the drop-down menu an option to maintain your video's aspect ratio.">&nbsp;</a>
                </div>
            </fieldset>

            <div class="control-group">
                <label class="control-label">Autoplay</label>
                <div class="controls">
                    <div class="btn-group onoff subsection sub-inverted" data-subsection="thumb" data-toggle="buttons-radios" data-target="autoplay">
                        <button class="btn btn-on" data-value="1">Yes</button>
                        <button class="btn btn-off" data-value="0">No</button>
                    </div>
                    <a class="lp-help-ico check-style" rel="popover" data-original-title="Autoplay Video" data-content="Should the video start playing as soon as it is loaded? (To be able to use the thumbnail feature you must disable autoplay.)">&nbsp;</a>
                    <a class="lp-warn-ico check-style" rel="popover" data-original-title="Important Note!" data-content="Use of this feature may affect your YouTube viewcount (if autoplay is enabled).">&nbsp;</a>
                    <input type="checkbox" style="display:none" id="autoplay" name="autoplay" value="1" <?php echo ($data['autoplay'] == 1) ? 'checked="checked"' : ''?>/>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Show Timeline</label>
                <div class="controls">
                    <div class="btn-group onoff" data-toggle="buttons-radios" data-target="show_timeline">
                        <button class="btn btn-on" data-value="1">Yes</button>
                        <button class="btn btn-off" data-value="0">No</button>
                    </div>
                    <a class="lp-help-ico check-style" rel="popover" data-original-title="Show Video Timeline" data-content="Should the player timeline be displayed (which shows current time in video and total time)?">&nbsp;</a>
                    <input type="checkbox" style="display:none" id="show_timeline" name="show_timeline" value="1" <?php echo ($data['show_timeline'] == 1) ? 'checked="checked"' : ''?>/>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Enable HD Video</label>
                <div class="controls">
                    <div class="btn-group onoff" data-toggle="buttons-radios" data-target="enable_hd">
                        <button class="btn btn-on" data-value="1">Yes</button>
                        <button class="btn btn-off" data-value="0">No</button>
                    </div>
                    <a class="lp-help-ico check-style" rel="popover" data-original-title="Video in HD" data-content="Enable video playback in HD. Video will play in HD only if the original video on YouTube has the necessary quality.">&nbsp;</a>
                        <a class="lp-warn-ico check-style" rel="popover" data-original-title="Important Note!" data-content="Enabling HD Video means that on some devices the user may have to wait longer for the video to load. If you enable HD Video, you may be sacrificing quick load time of the video in exchange for having a high quality HD Video on some devices.">&nbsp;</a>
                    <input type="checkbox" style="display:none" id="enable_hd" name="enable_hd" value="1" <?php echo ($data['enable_hd'] == 1) ? 'checked="checked"' : ''?>/>
                </div>
            </div>

<?php if ($video_action != 'global') { ?>
            <div style="display:none" class="subsection_thumb">
                <div class="control-group">
                    <label class="control-label" for="thumb_image">Thumbnail image</label>

                    <div class="controls">
                        <p class="help-block" style="color: #ffa500">This feature is no longer supported! Please use: <a href="https://support.google.com/youtube/answer/72431?hl=en" target="_blank">YouTube video thumbnails</a>.</p>
                    </div>
                </div>
            </div>
<?php } ?>

        </div>

        <div class="tab-pane" id="vid_cfg_opt"> <!-- opt in tab -->

            <div class="control-group">
                <label class="control-label">Include Opt-In Box</label>
                <div class="controls">
                    <div class="btn-group onoffdef subsection" data-subsection="optin" data-target="opt_mode">
                        <button class="btn btn-on" data-value="y">Yes</button>
                        <button class="btn btn-off" data-value="n">No</button>
<?php if ($video_action != 'global') { ?>
                        <button class="btn btn-def" data-value="d" data-target="default_optin_subsection">Default</button>
<?php } ?>
                    </div>
                    <a class="lp-help-ico check-style" rel="popover" data-original-title="Video Opt-In Box" data-content="Do you wish to display an opt-in box during the video?">&nbsp;</a>
                    <input type="hidden" name="opt_mode" class="opt_mode" value="<?php echo $data['opt_mode'] ?>" />
                </div>
            </div>

            <div style="display:none" class="default_optin_subsection">
<?php if ($g['opt'] === false) { ?>
                <p><span class="label label-warning">Note</span> &nbsp;Opt-in is disabled by default!</p>
<?php } else { ?>
                <fieldset class="control-group">
                    <label class="control-label">Email List Integration</label>
                    <div class="controls">
                        <span class="def-field-value">
                        <?php echo ($def['email_list'] ? $def['email_list']['name'] . '   (' . $def['email_list']['provider'] . ')': 'Disabled') ?>
                        </span>
                    </div>
                </fieldset>

                <fieldset class="control-group">
                    <label class="control-label">Headline</label>
                    <div class="controls">
                        <span class="def-field-value"><?php echo $g['opt']['text1'] ?></span>
                    </div>
                </fieldset>

                <fieldset class="control-group">
                    <label class="control-label">Text</label>
                    <div class="controls">
                        <span class="def-field-value"><?php echo $g['opt']['text2'] ?></span>
                    </div>
                </fieldset>

                <fieldset class="control-group">
                    <label class="control-label">Location</label>
                    <div class="controls lp-opt-time-sel" >
                        <span class="def-field-value">
                        <?php if ($g['opt']['time'] ==='start') { ?>
                            Beginning
                       <?php } else if ($g['opt']['time'] === 'end') { ?>
                            End
                       <?php } else { ?>
                            Custom:
                            <?php echo floor($g['opt']['time'] / 60) ?> min
                            <?php echo ($g['opt']['time'] % 60) ?> sec
                      <?php } ?>
                        </span>
                    </div>
                </fieldset>

                <fieldset class="control-group">
                    <label class="control-label">URL</label>
                    <div class="controls">
                        <span class="def-field-value"><?php echo $g['opt']['url'] ?></span>
                    </div>
                </fieldset>

                <fieldset class="control-group">
                    <label class="control-label">Allow Skipping</label>
                    <div class="controls">
                        <span class="def-field-value"><?php echo $g['opt']['skip'] ? 'Yes' : 'No' ?></span>
                    </div>
                </fieldset>

                <?php if ($g['opt']['skip']) { ?>
                <fieldset class="control-group">
                    <label class="control-label">Skip Text</label>
                    <div class="controls">
                        <span class="def-field-value"><?php echo $g['opt']['skip']['text'] ?></span>
                    </div>
                </fieldset>
                <?php } ?>
<?php } ?>
            </div>

            <!-- opt-in only options -->
            <div style="display:none" class="subsection_optin">

                    <fieldset class="control-group">
                        <label class="control-label">Ask For Name</label>
                        <div class="controls">
                            <div class="btn-group onoff subsection" data-subsection="opt_name_enabled" data-toggle="buttons-radios" data-target="opt_name_enabled">
                                <button class="btn btn-on" data-value="1">Yes</button>
                                <button class="btn btn-off" data-value="0">No</button>
                            </div>
                            <a class="lp-help-ico check-style" rel="popover" data-original-title="Ask For Name" data-content="If this is turned on, an additional 'first name' field will appear.">&nbsp;</a>
                            <a class="lp-warn-ico check-style" rel="popover" data-original-title="Important Note!" data-content="This must be supported by your email list service provider and the form code you're using to collect email addresses with this video. Please check your email list management system's documentation (i.e. the documentation from AWeber, MailChimp, InfusionSoft, etc.) and make sure the name field is included in your form code and is properly formatted.">&nbsp;</a>
                            <input type="checkbox" style="display:none" id="opt_name_enabled" name="opt_name_enabled" value="1" <?php echo (($data['opt']!==false && $data['opt']['name_enabled']) || $def['opt']['name_enabled']) ? 'checked="checked"' : ''?>/>
                        </div>
                    </fieldset>

                <div class="control-group">
<?php if ($video_action == 'global') { ?>
                    <label class="control-label" for="lp_video">Defaul Email List</label>
<?php } else { ?>
                    <label class="control-label" for="lp_video">Email List Integration</label>
<?php } ?>
                    <div class="controls">
                        <select class="email_list input-xlarge" name="email_list" id="email_list_select" data-default="<?php echo $data['email_list'] ?>"></select>
                        <a id="new-eml-list-single" style="display:none" class="btn btn-inverse" href=""><i class="icon-plus icon-white"></i>&nbsp;New email list</a>
                        <div class="btn-group drop-inline" id="drop-down-eml-list">
                          <button class="btn btn-inverse btn-small dropdown-toggle" data-toggle="dropdown"><i class="icon-cog icon-white"></i> Options <span class="caret"></span></button>
                          <ul class="dropdown-menu">
                            <li><a id="new-eml-list" href=""><i class="icon-plus"></i>&nbsp;New email list</a>
                            <li class="divider"></li>
                            <li><a id="edit-eml-list" href=""><i class="icon-pencil"></i>&nbsp;Edit selected list</a>
                            <li><a id="kill-eml-list" href=""><i class="icon-trash"></i>&nbsp;Remove selected list</a>
                          </ul>
                        </div>
                        <a class="lp-help-ico" rel="popover" data-original-title="Email List" data-content="Select the list you want to use for opt-in. Or add a new one.">&nbsp;</a>
                    </div>
                    <input type="hidden" name="select_this_list" id="select_this_list" value="<?php echo $data['email_list'] ?>">
                </div>

                <fieldset class="control-group">
                    <label class="control-label">Headline</label>
                    <div class="controls">
                        <input type="textbox" class="input-xxlarge" name="optin_text1" id="optin_text1" value="<?php echo $data['opt']!==false ? $data['opt']['text1'] : $def['opt']['text1'] ?>">
                        <a class="lp-help-ico" rel="popover" data-original-title="Opt-In Headline Text" data-content="This is the bold, headline text that is displayed in your video's opt-in box.">&nbsp;</a>
                    </div>
                </fieldset>

                <fieldset class="control-group">
                    <label class="control-label">Text</label>
                    <div class="controls">
                        <input type="textbox" class="input-xxlarge" name="optin_text2" id="optin_text2" value="<?php echo ($data['opt']!==false ? $data['opt']['text2'] : $def['opt']['text2']); ?>">
                        <a class="lp-help-ico" rel="popover" data-original-title="Opt-In Body Text" data-content=" This is the normal body text that is displayed in your video's opt-in box - try to keep it short and to the point!">&nbsp;</a>
                    </div>
                </fieldset>

                <fieldset class="control-group">
                    <label class="control-label">Location</label>
                    <div class="controls lp-opt-time-sel" >
                        <div class="btn-group but-nums-otime" data-target="opt-time">
                            <button class="btn btn-info" value="start">Beginning</button>
                            <button class="btn btn-info" value="time">Custom</button>
                            <button class="btn btn-info" value="end">End</button>
                        </div>
                        <a class="lp-help-ico check-style" rel="popover" data-original-title="Opt-In Box Location" data-content="Where during the video playback should the opt-in box be displayed?">&nbsp;</a>
                        <br>
                        <div class="opt_timev_wrap" style="position:relative;vertical-align:top;display:none">
                            <div class="input-append">
                                <input type="textbox" class="input-mini" name="optin_timevm" id="optin_timevm" value="<?php echo (($data['opt']!==false && $data['opt']['time']!=='start' && $data['opt']['time']!=='end') ? $data['opt']['timem'] : $def['opt']['timem']); ?>"><span class="add-on">min</span>
                            </div>
                            <div class="input-append">
                                <input type="textbox" class="input-mini" name="optin_timevs" id="optin_timevs" value="<?php echo (($data['opt']!==false && $data['opt']['time']!=='start' && $data['opt']['time']!=='end') ? $data['opt']['times'] : $def['opt']['times']); ?>"><span class="add-on">sec</span>
                            </div>
                            <a class="lp-help-ico check-style" rel="popover" data-original-title="Opt-In Box Time" data-content="At what point in the video should the opt-in box be displayed? The time must fall during the length of the video in minutes and seconds.">&nbsp;</a>
                        </div>
                    </div>
                    <input type="hidden" name="optin_time" id="optin_time" value="<?php echo ($data['opt']!==false ? ($data['opt']['time']!='start' && $data['opt']['time']!='end' ? 'time' : $data['opt']['time']) : $def['opt']['time']); ?>">
                    <input type="hidden" name="optin_timev" id="optin_timev" value="">
                </fieldset>

                <fieldset class="control-group opt_redir_url" style="display:none">
                    <label class="control-label">URL</label>
                    <div class="controls">
                        <input type="textbox" class="input-xxlarge" name="optin_url" id="optin_url" value="<?php echo ($data['opt']!==false ? $data['opt']['url'] : $def['opt']['url']); ?>">
                        <a class="lp-help-ico" rel="popover" data-original-title="Opt-In Redirect URL" data-content="The URL the user is redirected to after they have filled out the opt-in at the end of the video.">&nbsp;</a>
                    </div>
                </fieldset>

                <div style="display:none" class="subsection_optin_main_skip">

                    <fieldset class="control-group">
                        <label class="control-label">Allow Skipping</label>
                        <div class="controls">
                            <div class="btn-group onoff subsection" data-subsection="optin_skip" data-toggle="buttons-radios" data-target="include_optin_skip">
                                <button class="btn btn-on" data-value="1">Yes</button>
                                <button class="btn btn-off" data-value="0">No</button>
                            </div>
                            <a class="lp-help-ico check-style" rel="popover" data-original-title="Allow Skipping Opt-In" data-content="Do you want to allow viewers to be able to skip the opt-in step?">&nbsp;</a>
                            <input type="checkbox" style="display:none" id="include_optin_skip" name="include_optin_skip" value="1" <?php echo (($data['opt']!==false && $data['opt']['skip']) || $def['opt']['skip']) ? 'checked="checked"' : ''?>/>
                        </div>
                    </fieldset>

                    <!-- opt-in skip only options -->
                    <div style="display:none" class="subsection_optin_skip">
                        <fieldset class="control-group">
                            <label class="control-label">Opt-In Skip Text</label>
                            <div class="controls">
                                <input type="textbox" class="input-xlarge" name="optin_skip_text" id="optin_skip_text" value="<?php echo ($data['opt']!==false && $data['opt']['skip'] ? $data['opt']['skip']['text'] : ($def['opt']['skip'] ? $def['opt']['skip']['text'] : 'skip this step')); ?>">
                                <a class="lp-help-ico" rel="popover" data-original-title="Opt-In Skip Text" data-content="This is the text that is displayed to skip the opt-in - try to keep it short and to the point!">&nbsp;</a>
                            </div>
                        </fieldset>
                    </div>

                </div>
            </div>
        </div>

        <div class="tab-pane" id="vid_cfg_cta"> <!-- cta tab -->

            <div class="cta_wrap">
                <div class="control-group">
                    <label class="control-label">Include Call to Action</label>
                    <div class="controls">
                        <div class="btn-group onoffdef subsection" data-subsection="cta" data-target="cta_mode">
                            <button class="btn btn-on" data-value="y">Yes</button>
                            <button class="btn btn-off" data-value="n">No</button>
    <?php if ($video_action != 'global') { ?>
                            <button class="btn btn-def" data-value="d" data-target="default_cta_subsection">Default</button>
    <?php } ?>
                        </div>
                        <a class="lp-help-ico check-style" rel="popover" data-original-title="Video Call to Action" data-content="Do you wish this video to have a call to action message &amp; button displayed when it is done playing?">&nbsp;</a>
                        <input type="hidden" name="cta_mode" class="cta_mode" value="<?php echo $data['cta_mode'] ?>" />
                    </div>
                </div>

                <div style="display:none" class="default_cta_subsection">
<?php if ($g['cta'] === false) { ?>
                    <p><span class="label label-warning">Note</span> &nbsp;Call to action is disabled by default!</p>
<?php } else { ?>
                    <fieldset class="control-group">
                        <label class="control-label">Button Text</label>
                        <div class="controls">
                            <span class="def-field-value"><?php echo $g['cta']['btext'] ?></span>
                        </div>
                    </fieldset>

                    <fieldset class="control-group">
                        <label class="control-label">URL</label>
                        <div class="controls">
                            <span class="def-field-value"><?php echo $g['cta']['url'] ?></span>
                        </div>
                    </fieldset>

                    <div class="control-group">
                        <label class="control-label">Open Link In</label>
                        <div class="controls">
                            <span class="def-field-value"><?php echo ($g['cta']['new_window']) ? 'New Window' : 'Same Window' ?></span>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Automatic Redirect</label>
                        <div class="controls">
                            <span class="def-field-value"><?php echo ($g['cta']['auto_follow']) ? 'Yes' : 'No' ?></span>
                        </div>
                    </div>

                    <fieldset class="control-group">
                        <label class="control-label">Location</label>
                        <div class="controls lp-cta-time-sel" >
                            <span class="def-field-value">
                            <?php if ($g['cta']['time'] ==='start') { ?>
                                Beginning
                           <?php } else if ($g['cta']['time'] === 'end') { ?>
                                End
                           <?php } else { ?>
                                Custom:
                                <?php echo floor($g['cta']['time'] / 60) ?> min
                                <?php echo ($g['cta']['time'] % 60) ?> sec
                          <?php } ?>
                            </span>
                        </div>
                    </fieldset>
<?php } ?>
                </div>

                <!-- cta only options -->
                <div style="display:none" class="subsection_cta">

                    <fieldset class="control-group">
                        <label class="control-label">Button Text</label>
                        <div class="controls">
                            <input type="textbox" class="input-xxlarge" name="cta_btext" id="cta_btext" value="<?php echo ($data['cta']!==false ? $data['cta']['btext'] : $def['cta']['btext']); ?>">
                            <a class="lp-help-ico" rel="popover" data-original-title="Call to Action Button Text" data-content="This is the text displayed inside the call to action button.">&nbsp;</a>
                        </div>
                    </fieldset>

                    <fieldset class="control-group">
                        <label class="control-label">URL</label>
                        <div class="controls">
                            <input type="textbox" class="input-xxlarge" name="cta_url" id="cta_url" value="<?php echo ($data['cta']!==false ? $data['cta']['url'] : $def['cta']['url']); ?>">
                            <a class="lp-help-ico" rel="popover" data-original-title="Call to Action URL" data-content="This is the website URL the viewer will be taken to when they click on the call to action button.">&nbsp;</a>
                        </div>
                    </fieldset>

                    <div class="control-group">
                        <label class="control-label">Open CTA Link In</label>
                        <div class="controls">
                            <div class="btn-group onoff" data-toggle="buttons-radios" data-target="cta_new_window">
                                <button class="btn btn-on" data-value="1">New Window</button>
                                <button class="btn btn-off" data-value="0">Same Window</button>
                            </div>
                        <a class="lp-help-ico check-style" rel="popover" data-original-title="Open Link In New Window" data-content="Do you want the link to open in new or the same window when clicked?">&nbsp;</a>
                        <input type="checkbox" style="display:none" id="cta_new_window" name="cta_new_window" value="1" <?php echo (($data['cta']!==false && $data['cta']['new_window']) || $def['cta']['new_window']) ? 'checked="checked"' : ''?>/>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Automatic Redirect</label>
                        <div class="controls">
                            <div class="btn-group onoff" data-toggle="buttons-radios" data-target="cta_auto_follow">
                                <button class="btn btn-on" data-value="1">Yes</button>
                                <button class="btn btn-off" data-value="0">No</button>
                            </div>
                        <a class="lp-help-ico check-style" rel="popover" data-original-title="Automatic Redirect" data-content="Do you want the viewer to be automatically redirected at the end of the video to the call to action URL? (Note: this redirect will occur in the user's current window and &lt;b&gt;will not&lt;/b&gt; open in a new window).">&nbsp;</a>
                        <input type="checkbox" style="display:none" id="cta_auto_follow" name="cta_auto_follow" value="1" <?php echo (($data['cta']!==false && $data['cta']['auto_follow']) || $def['cta']['auto_follow']) ? 'checked="checked"' : ''?>/>
                        </div>
                    </div>

                    <fieldset class="control-group">
                        <label class="control-label">Location</label>
                        <div class="controls lp-cta-time-sel" >
                            <div class="btn-group but-nums-ctime" data-target="cta-time">
                                <button class="btn btn-info" value="start">Beginning</button>
                                <button class="btn btn-info" value="time">Custom</button>
                                <button class="btn btn-info" value="end">End</button>
                            </div>
                            <a class="lp-help-ico check-style" rel="popover" data-original-title="Call to Action Box Location" data-content="Where during the video playback should the call to action box be dislayed?">&nbsp;</a>
                            <br>
                            <div class="cta_timev_wrap" style="position:relative;vertical-align:top;display:none">
                                <div class="input-append">
                                    <input type="textbox" class="input-mini" name="cta_timevm" id="cta_timevm" value="<?php echo ($data['cta']!==false && $data['cta']['time']!=='start' && $data['cta']['time']!=='end') ? $data['cta']['timem'] : $def['cta']['timem']; ?>"><span class="add-on">min</span>
                                </div>
                                <div class="input-append">
                                    <input type="textbox" class="input-mini" name="cta_timevs" id="cta_timevs" value="<?php echo ($data['cta']!==false && $data['cta']['time']!=='start' && $data['cta']['time']!=='end') ? $data['cta']['times'] : $def['cta']['times']; ?>"><span class="add-on">sec</span>
                                </div>
                                <a class="lp-help-ico check-style" rel="popover" data-original-title="Call to Action Box Time" data-content="At what point in the video should the call to action box be displayed? The time must be less than the length of the video. Enter the number in seconds and minutes.">&nbsp;</a>
                            </div>
                        </div>
                        <input type="hidden" name="cta_time" id="cta_time" value="<?php echo ($data['cta']!==false ? ($data['cta']['time']!='start' && $data['cta']['time']!='end' ? 'time' : $data['cta']['time']) : $def['cta']['time']); ?>">
                    <input type="hidden" name="cta_timev" id="cta_timev" value="">
                    </fieldset>

                </div><!-- cta opts -->
            </div><!-- cta wrap -->
        </div><!-- cta tab-->

</form>

<!-- email list modal -->
<div class="modal fade" id="email_list_modal" style="display:none">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3 class="modal-title">Email List Integration</h3>
  </div>
  <div class="modal-body">
        <fieldset class="control-group">
            <p>Your email list provider</p>
            <select class="input-xlarge" name="email_list_provider" id="email_list_provider">
                <option value="aweber">AWeber</option>
                <option value="infusionsoft">InfusionSoft</option>
                <option value="icontact">iContact</option>
                <option value="mailchimp">MailChimp</option>
                <option value="getresponse">GetResponse</option>
                <option value="officeautopilot">OfficeAutoPilot</option>
                <option value="1shoppingcart">1ShoppingCart</option>
                <option value="trafficwave">TrafficWave</option>
                <option value="campaignmonitor">CampaignMonitor</option>
            </select>
        </fieldset>
        <fieldset class="control-group">
            <p>Your list name</p>
            <input type="textbox" class="input-xlarge" name="name" id="email_list_name" value="" /><br>
            <p style="display:none" id="enter-list-name" class="mod-err"><span class="label label-important">Empty list name!</span> &nbsp;Please enter your name for the list for easier identification!</p>
        </fieldset>
        <fieldset class="control-group">
            <p>Form embed HTML code</p>
            <textarea class="input-xxlarge" id="email_list_embed_code" name="embed_code" rows="5"></textarea>
            <p><span class="label label-info">Note</span> &nbsp;Please insert only raw HTML version of your embed code. <br/>And disable video on your 'thank you' page (i.e. GetResponse).</p>
            <p style="display:none" id="enter-embed-code-please" class="mod-err"><span class="label label-important">Invalid code!</span> &nbsp;The code you've entered seems invalid; please try again!</p>
        </fieldset>
  </div>
  <div class="modal-footer">
    <a href="" class="btn" id="close-modal">Close</a>
    <a href="" class="btn btn-primary" id="save-eml-list-modal" data-loading-text="Saving &hellip;">Save list</a>
  </div>
</div>
<!-- modal end -->


<!--BLOCK_SEPARATOR-->

<a href="" class="btn btn-primary btn-large" data-loading-text="Saving &hellip;" id="save-video-btn">
<?php if ($video_action == 'add') { ?>
Save New Video
<?php } else if ($video_action == 'global') { ?>
Save Settings
<?php } else if ($video_action == 'edit') { ?>
Update Video
<?php } ?>
</a>
<?php if ($video_action != 'global') { ?>
 &nbsp; <a href="" class="btn btn-success btn-large" id="preview-video-btn"> Preview Video </a>
 &nbsp; <a href="" class="btn btn-large" id="cancel-video-btn"> Back to videos </a>
<?php } ?>
