<script type="text/javascript">
    var leadplayer_main_page = true;
    var LEADPLAYER_KIOSK_MODE = <?php echo ($kiosk) ? 'true' : 'false';?>;
    jQLBLPAdmin(document).ready(function() {
        jQLBLPAdmin('#footer').hide();
        jQLBLPAdmin('a[rel=popover]').popover({'selector': 'a', 'trigger': 'manual'});
        jQLBLPAdmin('a[rel=popover]').mouseleave(function() { jQLBLPAdmin(this).popover('hide')});
        jQLBLPAdmin('a[rel=popover]').mouseover(function() { jQLBLPAdmin(this).popover('show')});
    });
    var zclip_path = '<?php echo LEADPLAYER_ABS_URL; ?>admin/swf/ZeroClipboard.swf';
</script>
<div class="wrap bootstrap-lp-wpadmin container">
    <div class="row" style="margin-top:15px;margin-bottom: 7px">
        <div class="span11" style="overflow: hidden; height: 53px">
            <a class="brand" style="margin:5px 10px 15px 0px;float:left" href="http://www.leadplayer.com/" target="_blank"><img border="0" src="<?php echo LEADPLAYER_ABS_URL; ?>admin/img/lp_logo.png"></a>
            <div class="msg-space span8">
            <?php if ($error_msg !== false){ ?>
                <div class="alert alert-error" id="set-err">
                  <a class="close" data-dismiss="alert">×</a>
                  <strong>Error!</strong> <?php echo htmlspecialchars($error_msg, ENT_QUOTES); ?>
              </div>
            <?php } else if ($success_msg !== false){ ?>
                <div class="alert alert-success" id="set-save">
                  <a class="close" data-dismiss="alert">×</a>
                  <?php echo htmlspecialchars($success_msg, ENT_QUOTES); ?>
                </div>
            <?php } ?>
            </div>
        </div>
    </div>
    <div class="row">
            <div class="span11">
                <ul class="nav nav-tabs">
                  <li id="my_videos_tab" class="active"><a href="#my_videos" data-toggle="tab">My Videos</a></li>
                  <li id="video_settings_tab"><a href="#video_settings" data-toggle="tab">Default Video Settings</a></li>
                  <li id="player_settings_tab"><a href="#player_settings" data-toggle="tab">Player Options</a></li>
                  <li id="add_video_tab" style="display:none"><a href="#add_video" data-toggle="tab">&nbsp;</a></li>
                  <li id="edit_video_tab" style="display:none"><a href="#edit_video" data-toggle="tab">&nbsp;</a></li>
                </ul>
                <div class="tab-content tab-content-main row-fluid">
                    <div class="tab-pane active" id="my_videos">
                        <legend>My LeadPlayer Videos</legend>
                        <div class="ajax-content">
                            <p class="loading-txt">Loading &hellip;</p>
                        </div>
                    </div>
                    <div class="tab-pane" id="video_settings"> 
                        <legend>Default Video Settings</legend>
                        <div class="ajax-content">
                        </div>
                    </div>
                    <div class="tab-pane" id="player_settings"> 
                        <legend>Player Options</legend>
                        <div class="ajax-content">
                        </div>
                    </div>
                    <div class="tab-pane" id="add_video">
                        <legend>Add New Video</legend>
                        <div class="ajax-content">
                        </div>
                    </div>
                    <div class="tab-pane" id="edit_video">
                        <legend>Edit Video</legend>
                        <div class="ajax-content">
                        </div>
                    </div>
                </div>
            <div class="form-actions ajax-buttons">
                <!-- buttons belong here -->
            </div>
        </div>
    </div>
    <div class="lb-footer">
        <a href="http://www.leadplayer.com/" target="_blank">LeadPlayer</a>&trade; powered by <a href="http://www.leadbrite.com" target="_blank">LeadBrite</a>&trade;
<?php if (!$kiosk) { ?>
        &nbsp; <i>version <?php echo LeadPlayerAdmin::get_full_ver(); ?></i>
        &nbsp; <a class="footer-link" href="<?php echo admin_url('admin.php?page=leadplayer&amp;licence-key-settings=true') ?>">License Key settings</a>
        &nbsp; <a class="footer-link" href="" id="check_for_update">Check for updates</a>
<?php } ?>
        &nbsp; <a class="footer-link" href="http://www.leadplayer.com/termsofservice/" target="_blank">Terms of service</a>
        &nbsp; <a class="footer-link" href="https://leadbrite.zendesk.com/" target="_blank">Support</a>
    </div>
    
    <!-- preview modal -->
    <div class="modal fade" id="video-modal" style="display:none">
      <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3 class="modal-title"></h3>
      </div>
      <div class="modal-body">
      </div>
    </div>
    <!-- modal end -->

    <!-- embed modal -->
    <div class="modal fade" id="video-embed-modal" style="display:none">
      <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3 class="modal-title"></h3>
      </div>
      <div class="modal-body">
        <div class="shortcode-embed-code">
            <p><span class="label label-success">Shortcode</span> can be inserted in WordPress pages and posts. </p>
            <textarea class="lp-shortcode-code-area well" autocapitalize="off" autocorrect="off" autocomplete="off" spellcheck="false"></textarea>
        <br>
        <p>
            <a href="" class="show-lp-html-code">I want to use this video outside of WordPress.</a> 
            <br>( Please choose this option if you're using <a href="http://www.leadpages.net/?utm_source=LeadPlayer-Plugin&amp;utm_medium=LP-embed&amp;utm_campaign=LeadPlayer" target="_blank">LeadPages&trade;</a>, even if on a WordPress site. )
        <p/>
        </div>
        <div class="html-embed-code" style="display:none">
            <p><span class="label label-info">HTML</span> code below can be used to implement this video outside of WordPress.</p>
            <textarea class="lp-embed-code-area well" autocapitalize="off" autocorrect="off" autocomplete="off" spellcheck="false"></textarea>
            <br>
            <p><span class="label label-warning">Important note!</span> &nbsp;<b>Each and every time</b> you make change to your video settings that you want reflected on an HTML page, you must:<br> <span class="label">1</span>&nbsp;come back here, <span class="label">2</span>&nbsp;grab this code, and <span class="label">3</span>&nbsp;re-insert it into your HTML page.</p>
        </div>
      </div>
    </div>
    <!-- modal end -->

    <!-- help modal -->
    <div class="modal fade" id="yt-url-help-modal" style="display:none">
      <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3 class="modal-title">Stay Compliant With LeadPlayer Terms of Service</h3>
      </div>
      <div class="modal-body">
      </div>
    </div>
    <!-- modal end -->

</div>
