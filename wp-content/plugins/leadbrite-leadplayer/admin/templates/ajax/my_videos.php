<?php if (empty($all_videos)) { ?>
    <p>You do not have any existing videos.</p>
<?php } else { ?>
    <table class="table table-striped table-bordered table-condensed paginate">
    <tbody>
        <?php
            foreach ($all_video_ids as $vid_id){
                $v = $all_videos[$vid_id];
                echo '<tr class="lp-row"><td class="col-a">';
                echo '<a data-id="' . $vid_id . '" class="prev-video-btn" title="Preview this video">';
                echo '<img src="' . LEADPLAYER_ABS_URL . 'admin/img/menu_icon.png">&nbsp;';
                echo htmlspecialchars($v['title'], ENT_QUOTES);
                echo '</a>';
                echo '<div class="lp-tab-controls">';
                echo '  <div class="btn-group check-style clip-btn-grp" data-original-title="Shortcode was copied in the clipboard. Paste it in a WordPress post or a page." data-placement="left" data-trigger="manual" data-animation="true" id="grp_clip_' . $vid_id . '" >';
                echo '    <button data-id="' . $vid_id . '" class="btn btn-mini btn-success embed-video-btn" data-loading-text="Loading" title="Embed this video in WordPress post, page or get the HTML embed code for use outside of WordPress" >Embed</button>';
                echo '    <button data-copy="[' . LeadplayerPlugin::VIDEO_SHORTCODE . ' id=&quot;' . $vid_id . '&quot;]" id="clip_' . $vid_id . '" data-loading-text="&lt;i class=\'icon-resize-horizontal icon-white\'&gt;&lt;/i&gt;" class="btn btn-mini btn-success clip-btn"><i class="icon-resize-vertical icon-white"></i></button>';
                echo '  </div>';
                echo '  <div class="btn-group check-style lp-drop-down-controls">';
                echo '    <button data-id="' . $vid_id . '" class="btn btn-mini btn-primary edit-video-btn" title="Edit this video">Edit</button>';
                echo '    <button class="btn btn-mini btn-primary dropdown-toggle" data-toggle="dropdown" title="Clone or delete this video">';
                echo '      <span class="caret"></span>';
                echo '    </button>';
                echo '    <ul class="dropdown-menu">';
                echo '      <li><a href="" data-id="' . $vid_id . '" class="copy-video-btn" title="Duplicate this video">Clone video</a></li>';
                echo '      <li><a href="" data-id="' . $vid_id . '" class="del-video-btn" title="Delete this video">Delete video</a></li>';
                echo '    </ul>';
                echo '  </div>';
                echo '</div>';
                echo '</td></tr>';
            }
        ?>
    </tbody>
    </table>
<?php } ?>

<!--BLOCK_SEPARATOR-->

<a href="" class="btn btn-primary" id="add-new-video"><i class="icon-plus icon-white"></i>&nbsp;Add New Video</a>
