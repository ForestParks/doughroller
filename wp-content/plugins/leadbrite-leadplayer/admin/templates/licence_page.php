<?php if ($kiosk) { ?>
<div class="wrap bootstrap-lp-wpadmin container">
    <div class="row">
        <div class="span11 licpage">
            <div class="tab-content  tab-content-main row-fluid">
                <legend>Unauthorised access. Please contact your site administrator.</legend>
            </div>
            <div class="form-actions">
                <a class="btn btn-large" href="<?php echo admin_url('admin.php?page=leadplayer') ?>">Go back to Settings</a>
            </div>
        </div>
    </div>
    <div class="lb-footer">
        <a href="http://www.leadplayer.com/" target="_blank">LeadPlayer</a>&trade; powered by <a href="http://www.leadbrite.com" target="_blank">LeadBrite</a>&trade;
        &nbsp; <a class="footer-link" href="http://www.leadplayer.com/termsofservice/" target="_blank">Terms of service</a>
        &nbsp; <a class="footer-link" href="https://leadbrite.zendesk.com/" target="_blank">Support</a>
    </div>
</div>
<?php } else { ?>
<script type="text/javascript">
    jQLBLPAdmin(document).ready(function() {
        jQLBLPAdmin('#footer').hide();
        jQLBLPAdmin('a[rel=popover]').popover({'selector': 'a', 'trigger': 'manual'});
        jQLBLPAdmin('a[rel=popover]').mouseleave(function() { jQLBLPAdmin(this).popover('hide')});
        jQLBLPAdmin('a[rel=popover]').mouseover(function() { jQLBLPAdmin(this).popover('show')});
        jQLBLPAdmin('#save-licence').click(function() {
            jQLBLPAdmin('#invalid-licence-key').hide();
            jQLBLPAdmin('fieldset').removeClass('error')
            jQLBLPAdmin('#save-licence').button('loading');
            var key = jQLBLPAdmin('#lp_licence_key').val();
            var data = {
		        'action': 'lp_key_validator',
        		'lp_licence_key': key
	        };
	        jQLBLPAdmin.post(ajaxurl, data, function(response) {
                if (response.result == 'ok') {
                    showSuccessMessage(response.msg);
                    jQLBLPAdmin('fieldset').removeClass('error')
                    jQLBLPAdmin('#invalid-licence-key').hide();
                    window.location = '<?php echo admin_url('admin.php?page=leadplayer') ?>';
                } else {
                    jQLBLPAdmin('fieldset').addClass('error')
                    jQLBLPAdmin('#invalid-licence-key').show();
                    showErrorMessage(response.msg);
                }
                jQLBLPAdmin('#save-licence').button('reset');
	        });
	    });
        jQLBLPAdmin('#remove-licence').click(function() {
            jQLBLPAdmin('#remove-licence').button('loading');
            var data = {
		        'action': 'lp_key_remove'
	        };
	        jQLBLPAdmin.post(ajaxurl, data, function(response) {
                if (response.result == 'ok') {
                    jQLBLPAdmin('#remove-licence').button('reset');
                    window.location = '<?php echo admin_url('admin.php?page=leadplayer') ?>';
                } else {
                    showErrorMessage(response.msg);
                }
	        });
	    });
    });
</script>
<div class="wrap bootstrap-lp-wpadmin container">
    <div class="row" style="margin-top:15px">
        <div class="span11" style="overflow: hidden; height: 50px">
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
        <div class="span11 licpage">
            <div class="tab-content  tab-content-main row-fluid">
                <legend>Please enter your LeadPlayer&trade; License Key</legend>
                <fieldset class="control-group form-horizontal">
                  <label class="control-label" for="lp_licence_key">License Key</label>
                  <div class="controls">
                    <input type="password" style="height:30px" name="licence_key" id="lp_licence_key" class="input-xlarge" value="<?php echo LeadplayerPlugin::get_licence_key() ?>"/>
                    <a class="lp-help-ico" rel="popover" data-original-title="License Key" data-content="Please enter your license key which you have obtained after purchasing LeadPlayer&trade;. In case of any problems please contact support.">&nbsp;</a>
                  </div>
                  <br/>
                  <p style="margin-left:50px;display:none" id="invalid-licence-key"><span class="label label-important">Invalid license key!</span> &nbsp;The key you've entered seems invalid; please try again.</p>
                </fieldset>
                <p style="margin-left:50px">If you need support, <a href="http://leadbrite.zendesk.com/" target="_blank">we're here to help</a>.</p>
            </div>
            <div class="form-actions">
                <a class="btn btn-large btn-primary" id="save-licence" data-loading-text="Validating ..." >Save license key</a> &nbsp; &nbsp;
<?php if (LeadplayerPlugin::get_licence_key()) { ?>
                <a class="btn btn-large btn-danger" id="remove-licence" data-loading-text="Removing ..." >Remove license key</a> &nbsp; &nbsp;
                <a class="btn btn-large" href="<?php echo admin_url('admin.php?page=leadplayer') ?>">Go back to Settings</a>
<?php } ?>
            </div>
        </div>
    </div>
    <div class="lb-footer">
        <a href="http://www.leadplayer.com/" target="_blank">LeadPlayer</a>&trade; powered by <a href="http://www.leadbrite.com" target="_blank">LeadBrite</a>&trade;
        &nbsp; <i>version <?php echo LeadPlayerAdmin::get_full_ver(); ?></i>
        &nbsp; <a class="footer-link" href="http://www.leadplayer.com/termsofservice/" target="_blank">Terms of service</a>
        &nbsp; <a class="footer-link" href="https://leadbrite.zendesk.com/" target="_blank">Support</a>
    </div>
</div>
<?php } ?>
