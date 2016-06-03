var showErrorMessage = function(text) {
    var msg = '<div class="alert alert-error" id="set-err"><a class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> &nbsp;' + text + '</div>';
    jQLBLPAdmin('.msg-space').html(msg);
    jQLBLPAdmin('#set-err').hide().slideDown(300).delay(6000).slideUp(300);
    jQLBLPAdmin('.modal').modal('hide');
};
var showSuccessMessage = function(text, permanent) {
    if (text === '') return;
    var msg = '<div class="alert alert-success" id="set-save"><a class="close" data-dismiss="alert">&times;</a>' + text + '</div>';
    jQLBLPAdmin('.msg-space').html(msg);
    if (typeof(permanent) != 'undefined' && permanent === true) return;
    jQLBLPAdmin('#set-save').hide().slideDown(300).delay(6000).slideUp(300);
};
var reqFail = function() { showErrorMessage('Operation failed &hellip; please check your connection and try again.'); };
var serverFail = function() { showErrorMessage('Server error. If problem persist, please contact support.'); };

var showMyVids = function() {
    jQLBLPAdmin('#my_videos_tab a').tab('show');
};

var cancel_to_vids = function() {
    // cancel button
    jQLBLPAdmin('#cancel-video-btn').click(function(e) {
        showMyVids();
        return false;
    });
};

var edit_video_by_id = function(id) {
    leadplayer_edit_video_id = id;
    jQLBLPAdmin('#edit_video_tab a').tab('show');
    loadSection('edit_video');
};

var new_video_controls = function() {

    // new video button
    jQLBLPAdmin('#add-new-video').click(function(e) {
        jQLBLPAdmin('#add_video_tab a').tab('show');
        return false;
    });
};

var edit_video_controls = function() {

    // edit video button
    jQLBLPAdmin('.edit-video-btn').click(function(e) {
        edit_video_by_id(jQLBLPAdmin(this).attr('data-id'));
    });
};

var leadplayer_edit_video_id;
var leadplayer_email_list_cache;

var populate_email_lists = function(form, sel_new, default_choice) {
    var sl = jQLBLPAdmin('#email_list_select').val();
    if (typeof(sel_new) != 'undefined' && sel_new !== false) {
        sl = sel_new;
    }
    default_choice = (typeof(default_choice) != 'undefined' && default_choice !== false);
    var dat = {'action': 'email_lists'};
    jQLBLPAdmin.ajax({
        type: 'POST',
        url: ajaxurl,
        data: dat,
        dataType: 'json',
        error: reqFail,
        success: function(r) {
            if (r.result == 'ok') {
                var s = form.find('select.email_list');
                var def_id = s.attr('data-default');
                var h = '';
                leadplayer_email_list_cache = r.data;
                if (default_choice) {
                    h += '<option value="default">Default email list</option>';
                }
                var grp = {};
                jQLBLPAdmin.each(r.data, function(id, item) {
                    if (!(item.provider in grp))
                        grp[item.provider] = {};
                    grp[item.provider][id] = item;
                });
                jQLBLPAdmin.each(grp, function(provider, items) {
                    h += '<optgroup label="' + provider + '">';
                    jQLBLPAdmin.each(items, function(id, item) {
                        h += '<option value="' + id + '">' + item.name + '</option>';
                    });
                    h += '</optgroup>';
                });
                s.html(h);
                if (s.find('option').length === 0) {
                    jQLBLPAdmin('#new-eml-list-single').show();
                    jQLBLPAdmin('#drop-down-eml-list').hide();
                } else {
                    jQLBLPAdmin('#new-eml-list-single').hide();
                    jQLBLPAdmin('#drop-down-eml-list').show();
                    s.val(sl);
                }
            } else {
                showErrorMessage(r.msg);
            }
        }
    });
};

// general form controls
var form_controls = function() {

    function toggle_subsec(elem, active) {
        var sub = elem.attr('data-subsection');
        elem.closest('form').find(".subsection_" + sub).css('display', active ? 'block' : 'none' );
    }

    // enable on/off
    jQLBLPAdmin('.btn-group.onoff').each(function() {
        var enabled = jQLBLPAdmin(this).closest('.control-group').find('input[name="' + jQLBLPAdmin(this).attr('data-target') + '"]').prop('checked');
        jQLBLPAdmin(this).find(enabled ? '.btn-on' : '.btn-off').addClass('active ' + (enabled ? 'btn-success' : 'btn-danger'));
        if (jQLBLPAdmin(this).hasClass('sub-inverted'))
            enabled = !enabled;
        if (jQLBLPAdmin(this).hasClass('subsection'))
            toggle_subsec(jQLBLPAdmin(this), enabled);
    });

    // on/off click actions
    jQLBLPAdmin('.btn-group.onoff button').click(function() {
        var slf = jQLBLPAdmin(this);
        var mum = slf.parent();
        mum.children().removeClass('active btn-success btn-danger');
        slf.addClass('active');
        var activated = slf.attr('data-value') == '1';
        slf.addClass(activated ? 'btn-success' : 'btn-danger');
        slf.closest('.control-group').find('input[name="' + mum.attr('data-target') + '"]').prop('checked',activated);
        var bgroup = jQLBLPAdmin(this).closest('.btn-group');
        if (bgroup.hasClass('subsection')) {
            if (bgroup.hasClass('sub-inverted'))
                activated = !activated;
            toggle_subsec(bgroup, activated);
        }
        return false;
    });

    var update_onoffdef = function(value, target) {
        target.children().removeClass('active btn-success btn-danger btn-info');
        var inpt = target.attr('data-target');
        var tgt;
        var cls;
        var enabled;
        var dt = target.find('.btn-def').attr('data-target');
        var dts = target.closest('.tab-pane').find('.' + dt);
        dts.hide();
        switch (value) {
            case 'y':
                tgt = 'btn-on';
                cls = 'btn-success';
                enabled = true;
                break;
            case 'n':
                tgt = 'btn-off';
                cls = 'btn-danger';
                enabled = false;
                break;
            // case 'd':
            default:
                tgt = 'btn-def';
                cls = 'btn-info';
                enabled = false;
                dts.show();
                break;
        }
        target.find('.' + tgt).addClass('active ' + cls);
        target.parent().find('.' + inpt).val(value);
        if (target.hasClass('subsection'))
            toggle_subsec(target, enabled);
    };

    // on/off/def
    jQLBLPAdmin('.btn-group.onoffdef').each(function() {
        var value = jQLBLPAdmin(this).closest('.control-group').find('input[name="' + jQLBLPAdmin(this).attr('data-target') + '"]').val();
        update_onoffdef(value, jQLBLPAdmin(this));
    });

    // on/off/def click actions
    jQLBLPAdmin('.btn-group.onoffdef button').click(function() {
        var slf = jQLBLPAdmin(this);
        var value = slf.attr('data-value');
        update_onoffdef(value, slf.parent());
        return false;
    });

    // multiple buttons
    jQLBLPAdmin('.btn-group.but-nums button').click(function() {
        var slf = jQLBLPAdmin(this);
        var mum = slf.parent();
        mum.children().removeClass('active');
        slf.addClass('active');
        var val = slf.attr('value');
        jQLBLPAdmin(this).closest('form').find('input[name=' + mum.attr('data-target')+']').removeAttr('checked');
        jQLBLPAdmin(this).closest('form').find('input[name=' + mum.attr('data-target')+'][value=' + val + ']').attr('checked', 'checked');
        return false;
    });
};

// video settings
var video_controls = function(video_action) {

    var def_c = false; // default choice in list select
    var target_field; // used for image urls
    var email_list_modal_type; // add or edit list
    var email_list_modal_data; // temp edit data - ajax loaded
    var tform = '#vid_form_' + video_action;
    var slc = jQLBLPAdmin('#select_this_list').val(); // saved pre-selected list
    var video_id; // id of video currently being edited
    if (typeof(leadplayer_edit_video_id) == 'undefined') {
        video_id = false;
    } else {
        video_id = leadplayer_edit_video_id;
    }

    if (video_action != 'global') {
        def_c = true;
        cancel_to_vids();
    }
    if (video_action == 'add') {
        slc = false;
    }

    // load email list options
    populate_email_lists(jQLBLPAdmin(tform), slc, def_c);

    // don't show edit/delete options for default list
    jQLBLPAdmin('#drop-down-eml-list').click(function() {
        var sl = jQLBLPAdmin('#email_list_select').val();
        var dl = jQLBLPAdmin('#drop-down-eml-list');
        if (sl == 'default') {
            dl.find('.dropdown-menu li').hide();
            dl.find('.dropdown-menu li:first').show();
        } else {
            dl.find('.dropdown-menu li').show();
        }
    });

    // video ratio tool
    var ratio_mode;
    var wid = parseInt(jQLBLPAdmin('input[name=width]').val(), 10);
    var hei = parseInt(jQLBLPAdmin('input[name=height]').val(), 10);
    var rat = wid / hei;
    var rat_txt;

    if (rat == 16/9) {
        ratio_mode = 'w'; // wide
        rat_txt = '16:9';
    } else if (rat == 4/3) {
        ratio_mode = 's'; // sd
        rat_txt = '4:3';
    } else {
        ratio_mode = 'c'; // custom
        rat_txt = 'Custom';
    }

    var calculate_w_ratio = function() {
        var h = parseInt(jQLBLPAdmin('input[name=height]').val(), 10);
        var v = parseInt(jQLBLPAdmin('input[name=width]').val(), 10);
        if (ratio_mode == 'w') {
            v = Math.round((v / 16) * 9);
        } else if (ratio_mode == 's') {
            v = Math.round((v / 4) * 3);
        } else if (ratio_mode == 'c') {
            return;
        }
        if (isNaN(v)) {
            v = h;
        }
        jQLBLPAdmin('input[name=height]').val(v);
    };

    var calculate_h_ratio = function() {
        var v = parseInt(jQLBLPAdmin('input[name=width]').val(), 10);
        var h = parseInt(jQLBLPAdmin('input[name=height]').val(), 10);
        if (ratio_mode == 'w') {
            h = Math.round((h / 9) * 16);
        } else if (ratio_mode == 's') {
            h = Math.round((h / 3) * 4);
        } else if (ratio_mode == 'c') {
            return;
        }
        if (isNaN(h)) {
            h = v;
        }
        jQLBLPAdmin('input[name=width]').val(h);
    };


    jQLBLPAdmin('#video-ratio-tool').find('#ratio-label').text(rat_txt);

    jQLBLPAdmin('input[name=width]').keyup(calculate_w_ratio);
    jQLBLPAdmin('input[name=height]').keyup(calculate_h_ratio);

    jQLBLPAdmin('#video-ratio-tool ul li a').click(function(e) {
        ratio_mode = jQLBLPAdmin(this).attr('data-value');
        rat_txt = jQLBLPAdmin(this).text();
        jQLBLPAdmin('#video-ratio-tool').find('#ratio-label').text(rat_txt);
        jQLBLPAdmin('#video-ratio-tool').removeClass('open');
        if (ratio_mode == 'w') {
            calculate_w_ratio();
        } else if (ratio_mode == 's') {
            calculate_w_ratio();
        }
        return false;
    });

    // ratio tool end

    // new eml list modal
    jQLBLPAdmin('#new-eml-list, #new-eml-list-single').click(function(e) {
        email_list_modal_type = 'add';
        jQLBLPAdmin('#drop-down-eml-list').removeClass('open');
        jQLBLPAdmin('#email_list_modal').modal('show');
        return false;
    });

    // edit eml list modal
    jQLBLPAdmin('#edit-eml-list').click(function(e) {
        var sl = jQLBLPAdmin('#email_list_select').val();
        email_list_modal_type = 'edit';
        if (sl in leadplayer_email_list_cache) {
            email_list_modal_data = leadplayer_email_list_cache[sl];
            email_list_modal_data.id = sl;
        }
        jQLBLPAdmin('#drop-down-eml-list').removeClass('open');
        jQLBLPAdmin('#email_list_modal').modal('show');
        return false;
    });

    // delete eml list modal
    jQLBLPAdmin('#kill-eml-list').click(function(e) {
        jQLBLPAdmin('#drop-down-eml-list').removeClass('open');
        var sl = jQLBLPAdmin('#email_list_select').val();
        var r = confirm('Do you really want to remove email list "' + leadplayer_email_list_cache[sl].name + '"?');
        if (r !== true) { return false; }
        var dat = {
            'action': 'post_email_list',
            'mode': 'delete',
            'id': sl
        };
        jQLBLPAdmin.ajax({
            type: 'POST',
            url: ajaxurl,
            data: dat,
            dataType: 'json',
            error: reqFail,
            success: function(res) {
                if (res.result == 'ok') {
                    populate_email_lists(jQLBLPAdmin(tform), res.data, def_c);
                    showSuccessMessage(res.msg);
                    jQLBLPAdmin('#email_list_modal').modal('hide');
                } else {
                    showErrorMessage(res.msg);
                }
            }
        });
        return false;
    });

    // on modal hide
    jQLBLPAdmin('#email_list_modal').on('hide', function() {
        var mod = jQLBLPAdmin('#email_list_modal');
        mod.find('.error').removeClass('error');
        mod.find('.mod-err').hide();
        mod.find('#email_list_provider').val('');
        mod.find('#email_list_name').val('');
        mod.find('#email_list_embed_code').val('');
        jQLBLPAdmin('#save-eml-list-modal').button('reset');
        jQLBLPAdmin('#drop-down-eml-list').removeClass('open');
    });

    // on modal show
    jQLBLPAdmin('#email_list_modal').on('show', function() {
        if (email_list_modal_type == 'edit') {
            var mod = jQLBLPAdmin('#email_list_modal');
            mod.find('#email_list_provider').val(email_list_modal_data.provider);
            mod.find('#email_list_name').val(email_list_modal_data.name);
            mod.find('#email_list_embed_code').val(email_list_modal_data.html);
        }
    });

    // close modal
    jQLBLPAdmin('#close-modal').click(function(e) {
        e.preventDefault();
        jQLBLPAdmin('#email_list_modal').modal('hide');
        return false;
    });

    // email list action
    jQLBLPAdmin('#save-eml-list-modal').click(function() {
        jQLBLPAdmin('#save-eml-list-modal').button('loading');
        var mod = jQLBLPAdmin('#email_list_modal');
        mod.find('.error').removeClass('error');
        mod.find('.mod-err').hide();
        var showCodeBug = function(kind) {
            if (typeof(kind) == 'undefined') kind = 'code';
            switch (kind) {
            case 'service':
                mod.find('#select-service-please').show();
                break;
            case 'name':
                mod.find('#email_list_name').closest('fieldset').addClass('error');
                mod.find('#enter-list-name').show();
                break;
            case 'code':
                mod.find('#email_list_embed_code').closest('fieldset').addClass('error');
                mod.find('#enter-embed-code-please').show();
                break;
            }
            jQLBLPAdmin('#save-eml-list-modal').button('reset');
            return false;
        };
        var findInp = function(code, name) {
            var m = code.match('<input.+name="' + name + '"[^>]*>');
            if (m == null)
                m = code.match("<input.+name='" + name + "'[^>]*>");
            var val = (m != null) ? m[0] : undefined;
            if (typeof(val) != 'undefined') {
                m = val.match(/value="([^"]+)"/);
                if (m == null)
                    m = val.match(/value='([^']+)'/);
                val = (m != null) ? m[1] : undefined;
            }
            return (typeof(val) != 'undefined') ? val : null;
        };
        var type = mod.find('#email_list_provider').val();
        var name = mod.find('#email_list_name').val();
        var code = mod.find('#email_list_embed_code').val();
        if (type.length == 0) return showCodeBug('service');
        if (name.length == 0) return showCodeBug('name');
        if (code.length == 0) return showCodeBug();
        switch (type) {
            case 'infusionsoft':
                // no validation, too many different codes
                break;
            case 'aweber':
                var nam = findInp(code, 'listname');
                var meta = findInp(code, 'meta_adtracking');
                if (meta == null || nam == null) return showCodeBug();
                break;
            case 'mailchimp':
                var m = code.match(/<form.+action="([^"\)]+)".*>/);
                var url = (m != null && typeof(m[1]) != 'undefined') ? m[1] : null;
                if (url == null) return showCodeBug();
                break;
            case 'icontact':
                var lid = findInp(code ,'listid'),
                    cid = findInp(code, 'clientid');
                if (lid == null || cid == null) return showCodeBug();
                break;
            case 'getresponse':
                var wfid = findInp(code, 'webform_id');
                if (wfid == null) return showCodeBug();
                break;
            case 'officeautopilot':
                var uid = findInp(code, 'uid');
                if (uid == null) return showCodeBug();
                break;
            case '1shoppingcart':
                var uid = findInp(code, 'merchantid');
                if (uid == null) return showCodeBug();
                break;
            case 'trafficwave':
                var uid = findInp(code, 'trwvid');
                if (uid == null) return showCodeBug();
                break;
            case 'campaignmonitor':
                var field = jQLBLPAdmin(code).find('input[name^="cm-"]')
                if (field.length == 0) return showCodeBug();
                break;
        }
        var dat = {
            'action': 'post_email_list',
            'mode': email_list_modal_type,
            'provider': type,
            'name': name,
            'id': ((email_list_modal_type == 'edit') ? email_list_modal_data.id : false),
            'code': code
        };
        jQLBLPAdmin.ajax({
            type: 'POST',
            url: ajaxurl,
            data: dat,
            dataType: 'json',
            error: reqFail,
            success: function(res) {
                if (res.result == 'ok') {
                    var new_selection = false;
                    if (email_list_modal_type == 'edit') {
                        new_selection = res.data.id;
                    } else if (email_list_modal_type == 'add') {
                        new_selection = res.data;
                    }
                    populate_email_lists(jQLBLPAdmin(tform), new_selection, def_c);
                    showSuccessMessage(res.msg);
                    jQLBLPAdmin('#email_list_modal').modal('hide');
                } else {
                    showErrorMessage(res.msg);
                }
            }
        });
        return false;
    });

    // end list modal

    jQLBLPAdmin('a.img-upload').click(function() {
        target_field = '#' + jQLBLPAdmin(this).attr('id').replace('_upload', '');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        return false;
    });

    jQLBLPAdmin('a.img-reset').click(function() {
        jQLBLPAdmin('#' + jQLBLPAdmin(this).attr('id').replace('_reset', '')).val('');
        return false;
    });

    window.send_to_editor = function(html) {
        imgurl = jQLBLPAdmin('img', html).attr('src');
        jQLBLPAdmin(target_field).val(imgurl);
        tb_remove();
    };

    jQLBLPAdmin('.lp-help-ico').popover({'placement': 'right'});
    jQLBLPAdmin('.lp-warn-ico').popover({'placement': 'right'});

    // help video modal

    jQLBLPAdmin('#yt-url-help-modal').on('show', function() {
        jQLBLPAdmin('#yt-url-help-modal').find('.modal-body').html('<div id="yt-url-help-modal-vid" style="width:500px;height:281px;margin:0 auto"></div>');
        jQLeadBrite("#yt-url-help-modal-vid").leadplayer("cNywiZraHS4",{"id": "5020BFF116499","width": 500,"height": 281,"title": "Stay Compliant With LeadPlayer Terms of Service","autoplay": true,"overlay": true,"enable_pause": false,"show_timeline": false,"thumbnail": false,"ga": false,"opt": false,"cta": false});
    });

    jQLBLPAdmin('#yt-url-help-modal').on('hide', function() {
        jQLBLPAdmin('#yt-url-help-modal').find('.modal-body').html('');
    });

    jQLBLPAdmin('.yt-warn-video').click(function(e) {
        e.preventDefault();
        jQLBLPAdmin('#yt-url-help-modal').modal('show');
        return false;
    });

    // end help video modal

    form_controls();

    // opt in
    jQLBLPAdmin('.but-nums-otime').each(function() {
        var bg = jQLBLPAdmin(this);
        var val = bg.closest('form').find('input[name="optin_time"]').val();
        bg.closest('form').find('.opt_redir_url,.opt_timev_wrap').hide();
        switch(val){
            case 'start':
                bg.find('button[value="start"]').addClass('active');
                bg.closest('form').find('.subsection_optin_main_skip').show();
                break;
            case 'end':
                bg.find('button[value="end"]').addClass('active');
                bg.closest('form').find('.opt_redir_url').show();
                bg.closest('form').find('.subsection_optin_main_skip').hide();
                break;
            default:
                bg.find('button[value="time"]').addClass('active');
                bg.closest('form').find('.opt_timev_wrap').css('display', 'inline-block');
                bg.closest('form').find('.subsection_optin_main_skip').show();
            break;
        }
    });

    // opt in buttons
    jQLBLPAdmin('.but-nums-otime button').click(function() {
        var slf = jQLBLPAdmin(this);
        var bg = slf.closest('.but-nums-otime');
        var val = slf.attr('value');
        var inp = slf.closest('form').find('input[name="optin_time"]');
        bg.find('button').removeClass('active');
        slf.addClass('active');
        bg.closest('form').find('.opt_redir_url').hide();
        inp.attr('value', val);
        switch (val) {
            case 'start':
                bg.closest('form').find('.subsection_optin_main_skip').show();
                bg.closest('form').find('.opt_timev_wrap').hide();
                break;
            case 'end':
                bg.closest('form').find('.opt_redir_url').show();
                bg.closest('form').find('.subsection_optin_main_skip').hide();
                bg.closest('form').find('.opt_timev_wrap').hide();
                break;
            default:
                inp.attr('value', 'time');
                bg.closest('form').find('.opt_timev_wrap').show();
                bg.closest('form').find('.subsection_optin_main_skip').show();
                break;
        }
        return false;
    });

    // cta
    jQLBLPAdmin('.but-nums-ctime').each(function() {
        var bg = jQLBLPAdmin(this);
        var val = bg.closest('form').find('input[name="cta_time"]').val();
        bg.closest('form').find('.cta_timev_wrap').hide();
        switch (val) {
            case 'start':
                bg.find('button[value="start"]').addClass('active'); break;
            case 'end':
                bg.find('button[value="end"]').addClass('active'); break;
            default:
                bg.find('button[value="time"]').addClass('active');
                bg.closest('form').find('.cta_timev_wrap').css('display', 'inline-block');
                break;
        }
    });

    // cta buttons
    jQLBLPAdmin('.but-nums-ctime button').click(function() {
        var slf = jQLBLPAdmin(this),
            bg = slf.closest('.but-nums-ctime'),
            val = slf.attr('value'),
            inp = slf.closest('form').find('input[name="cta_time"]');
        bg.find('button').removeClass('active');
        slf.addClass('active');
        inp.attr('value', val);
        switch (val) {
            case 'start':
                bg.closest('form').find('.cta_timev_wrap').hide();
                break;
            case 'end':
                bg.closest('form').find('.cta_timev_wrap').hide();
                break;
            default:
                inp.attr('value', 'time');
                bg.closest('form').find('.cta_timev_wrap').show();
                break;
        }
        return false;
    });

    // validate
    var clean_video_form = function() {
        var valid = true;
        var form = jQLBLPAdmin(tform);
        form.find('.val-errs').remove();
        form.find('fieldset').removeClass('error');
        form.find('input[type="textbox"]').each(function(ind, elem) {
            if (jQLBLPAdmin(elem).attr('name') == 'thumb_image') return;
            if (jQLBLPAdmin(elem).attr('name') == 'description') return;
            if (jQLBLPAdmin(elem).attr('name') == 'name') return;
            if (jQLBLPAdmin(this).val().length === 0) {
                valid = false;
                var h = '<p class="val-errs"><span class="label label-important">Invalid!</span> Field cannot be empty &hellip;</p>';
                jQLBLPAdmin(elem).closest('.controls').append(h);
                jQLBLPAdmin(elem).closest('fieldset').addClass('error');
                jQLBLPAdmin('a[href=#' + jQLBLPAdmin(elem).closest('div.tab-pane').attr('id')+']').tab('show');
            }
        });
        // format time values ( min > sec )
        var s = parseInt(form.find('input[name=cta_timevm]').val(), 10) * 60;
        s += parseInt(form.find('input[name=cta_timevs]').val(), 10);
        form.find('input[name=cta_timev]').val(s);
        var ctat = form.find('input[name=cta_time]').attr('value');
        if (jQLBLPAdmin.inArray(ctat, ['start', 'end']) == -1) {
            form.find('input[name=cta_time]').val('time');
        }
        s = parseInt(form.find('input[name=optin_timevm]').val(), 10) * 60;
        s += parseInt(form.find('input[name=optin_timevs]').val(), 10);
        form.find('input[name=optin_timev]').val(s);
        var optt = form.find('input[name=optin_time]').attr('value');
        if (jQLBLPAdmin.inArray(optt, ['start', 'end']) == -1) {
            form.find('input[name=optin_time]').val('time');
        }
        if (!valid) {
            jQLBLPAdmin('.form-err').show();
            return false;
        }
        return true;
    };

    // preview the video
    jQLBLPAdmin('#preview-video-btn').click(function(e) {
        var res = clean_video_form();
        if (res === false) return false;
        var form = jQLBLPAdmin(tform);
        form.find('input[name="preview"]').val('true');
        var dataString = jQLBLPAdmin(tform).serialize();
        form.find('input[name="preview"]').val('false');
        // send data to be fixed on server
        jQLBLPAdmin('#preview-video-btn').button('loading');
        jQLBLPAdmin.ajax({
            type: 'POST',
            url: ajaxurl,
            data: dataString,
            dataType: 'json',
            error: reqFail,
            success: function(res) {
                if (res.result == 'ok') {
                    show_video_modal(res.data);
                } else {
                    showErrorMessage(res.msg);
                }
                jQLBLPAdmin('#preview-video-btn').button('reset');
            }
        });
        return false;
    });

    // save the form!
    jQLBLPAdmin('#save-video-btn').click(function(e) {
        var res = clean_video_form();
        if (res === false) return false;
        var dataString = jQLBLPAdmin(tform).serialize();
        // send data
        jQLBLPAdmin('#save-video-btn').button('loading');
        jQLBLPAdmin.ajax({
            type: 'POST',
            url: ajaxurl,
            data: dataString,
            dataType: 'json',
            error: reqFail,
            success: function(data) {
                if (data.result == 'ok') {
                    showSuccessMessage(data.msg);
                    if (video_action == 'global') {
                        preloadSection('add_video');
                        preloadSection('video_settings');
                    } else if (video_action == 'add' || video_action == 'edit') {
                        preloadSection('my_videos');
                    }
                    if (video_action == 'add') {
                        edit_video_by_id(data.data.video_id);
                    }
                } else {
                    showErrorMessage(data.msg);
                }
                jQLBLPAdmin('#save-video-btn').button('reset');
            }
        });
        return false;
    });
}

// player settings
var player_controls = function() {

    var tform = '#player_settings_form';

    // mini colors
    jQLBLPAdmin("input.color-picker").miniColors({ });

    // popover
    jQLBLPAdmin('.lp-help-ico').popover({'placement': 'right'});
    jQLBLPAdmin('.lp-warn-ico').popover({'placement': 'right'});

    form_controls();

    // validate
    var clean_player_form = function() {
        var valid = true;
        var form = jQLBLPAdmin(tform);
        form.find('.val-errs').remove();
        form.find('fieldset').removeClass('error');
        form.find('input[type="textbox"]').each(function(ind, elem) {
            if (jQLBLPAdmin(this).val().length == 0) {
                valid = false;
                var h = '<p class="val-errs"><span class="label label-important">Invalid!</span> Field cannot be empty &hellip;</p>';
                jQLBLPAdmin(elem).closest('.controls').append(h);
                jQLBLPAdmin(elem).closest('fieldset').addClass('error');
                jQLBLPAdmin('a[href=#' + jQLBLPAdmin(elem).closest('div.tab-pane').attr('id')+']').tab('show');
            }
        });
        if (!valid) {
            jQLBLPAdmin('.form-err').show();
            return false;
        }
        return true;
    }

    // save the form!
    jQLBLPAdmin('#save-player-btn').click(function(e) {
        var res = clean_player_form();
        if (res === false) return false;
        var dataString = jQLBLPAdmin(tform).serialize();
        // send data
        jQLBLPAdmin('#save-player-btn').button('loading');
        jQLBLPAdmin.ajax({
            type: 'POST',
            url: ajaxurl,
            data: dataString,
            dataType: 'json',
            error: reqFail,
            success: function(data) {
                if (data.result == 'ok') {
                    showSuccessMessage(data.msg);
                    preloadSection('player_settings');
                } else {
                    showErrorMessage(data.msg);
                }
                jQLBLPAdmin('#save-player-btn').button('reset');
            }
        });
        return false;
    });
}


var fixFlashButtons = function() {
    // fix flash buttons
    jQLBLPAdmin('a[data-toggle="tab"]').on('shown', function (e) {
        var tgts = jQLBLPAdmin('div[style*="position: absolute"] embed, div[style*="position: absolute"] object');
        if (jQLBLPAdmin(e.target).attr('href') == '#my_videos') {
            tgts.parent().show();
        } else if (jQLBLPAdmin(e.relatedTarget).attr('href') == '#my_videos') {
            tgts.parent().hide();
        }
    });
};

var table_controls = function() {

    // popover
    jQLBLPAdmin('.lp-help-ico').popover({'placement': 'right'});
    jQLBLPAdmin('.lp-warn-ico').popover({'placement': 'right'});

    // copy buttons

    var clip;

    // show only one at the time
    jQLBLPAdmin('.lp-row').hover(
        function() {
            if (typeof(clip) != 'undefined') clip.destroy();

            var btn = jQLBLPAdmin(this).find('.clip-btn');

            clip = new ZeroClipboard.Client();
            var txt = btn.attr('data-copy');
            var bid = btn.attr('id');

            clip.addEventListener('mouseDown', function (client) {
                clip.setText(txt);
                jQLBLPAdmin('.clip-btn-grp[id!=grp_' + bid + ']').tooltip('hide');
                jQLBLPAdmin('.clip-btn[id!=' + bid + ']').button('reset');
                jQLBLPAdmin('#grp_' + bid).tooltip('show');
                jQLBLPAdmin('#' + bid).button('loading');
                setTimeout(function() {
                    jQLBLPAdmin('#grp_' + bid).tooltip('hide');
                    jQLBLPAdmin('#' + bid).button('reset');
                }, 3000);
            });

            clip.glue(bid);
        }, function(e) {
    });

    // hide copy button
    jQLBLPAdmin('body').mousemove(function(e) {
        var t = jQLBLPAdmin(e.target);
        var a = jQLBLPAdmin('#my_videos .ajax-content > table');
        if (t.closest(a).length <= 0) {
            if (t.find('embed, object').length > 0) return;
            if (e.target.tagName.toLowerCase() == 'embed') return;
            if (e.target.tagName.toLowerCase() == 'object') return;
            if (typeof(clip) != 'undefined') clip.destroy();
        }
    });

    // delete video
    jQLBLPAdmin('.del-video-btn').click(function(e) {
        jQLBLPAdmin('.lp-drop-down-controls').removeClass('open');
        if (!confirm("Are you sure you wish to delete this video?")) return false;
        var vid_id = jQLBLPAdmin(this).attr('data-id');
        var dat = {
            'action': 'video_delete',
            'id': vid_id
        };
        jQLBLPAdmin.ajax({
            type: 'POST',
            url: ajaxurl,
            data: dat,
            dataType: 'json',
            error: reqFail,
            success: function(data) {
                if (data.result == 'ok') {
                    showSuccessMessage(data.msg);
                    forceLoadSection('my_videos');
                } else {
                    showErrorMessage(data.msg);
                }
            }
        });
        return false;
    });

    // copy video
    jQLBLPAdmin('.copy-video-btn').click(function() {
        jQLBLPAdmin('.lp-drop-down-controls').removeClass('open');
        if (!confirm("Are you sure you wish to clone this video?")) return false;
        var vid_id = jQLBLPAdmin(this).attr('data-id');
        var dat = {
            'action': 'video_clone',
            'id': vid_id
        };
        jQLBLPAdmin.ajax({
            type: 'POST',
            url: ajaxurl,
            data: dat,
            dataType: 'json',
            error: reqFail,
            success: function(data) {
                if (data.result == 'ok') {
                    showSuccessMessage(data.msg);
                    forceLoadSection('my_videos');
                } else {
                    showErrorMessage(data.msg);
                }
            }
        });
        return false;
    });

    // preview video
    jQLBLPAdmin('.prev-video-btn').click(function(e) {
        var vid_id = jQLBLPAdmin(this).attr('data-id');
        var dat = {
            'action': 'video_embed',
            'id': vid_id
        };
        jQLBLPAdmin.ajax({
            type: 'POST',
            url: ajaxurl,
            data: dat,
            dataType: 'json',
            error: reqFail,
            success: function(res) {
                if (res.result == 'ok') {
                    show_video_modal(res.data);
                } else {
                    showErrorMessage(res.msg);
                }
            }
        });
        return false;
    });

    // embed video
    jQLBLPAdmin('.embed-video-btn').click(function(e) {
        var btn = jQLBLPAdmin(this);
        var vid_id = btn.attr('data-id');
        var dat = {
            'action': 'video_embed',
            'id': vid_id
        };
        jQLBLPAdmin.ajax({
            type: 'POST',
            url: ajaxurl,
            data: dat,
            dataType: 'json',
            error: reqFail,
            success: function(res) {
                if (res.result == 'ok') {
                    show_video_embed_modal(res.data);
                } else {
                    showErrorMessage(res.msg);
                }
                btn.button('reset');
            }
        });
        btn.button('loading');
        return false;
    });
};

// video preview

var video_preview_data;

var show_video_modal = function(vdata) {
    video_preview_data = vdata;
    if (vdata.video.opt !== false && typeof(leadplayer_utils) != 'undefined') {
        leadplayer_utils.set_cookie('leadbrite_leadplayer_optin' + vdata.video.opt.form_hash, '', -100);
    }
    jQLBLPAdmin('#video-modal').modal('show');
}

// video embed

var video_embed_data;

var show_video_embed_modal = function(vdata) {
    video_embed_data = vdata;
    jQLBLPAdmin('#video-embed-modal').modal('show');
}

// end video preview

var my_videos = function() {
    table_controls();
    new_video_controls();
    edit_video_controls();
    paginate()
}

var paginate = function() {
    jQLBLPAdmin('table.paginate').each(function() {
        var currentPage = 0;
        var numPerPage = 16;
        var jQLBLPAdmintable = jQLBLPAdmin(this);
        jQLBLPAdmintable.bind('repaginate', function() {
            jQLBLPAdmintable.find('tbody tr').hide().slice(currentPage * numPerPage, (currentPage + 1) * numPerPage).show();
        });
        jQLBLPAdmintable.trigger('repaginate');
        var numRows = jQLBLPAdmintable.find('tbody tr').length;
        var numPages = Math.ceil(numRows / numPerPage);
        var jQLBLPAdminpager = jQLBLPAdmin('<div class="pager"></div>');
        for (var page = 0; page < numPages; page++) {
            jQLBLPAdmin('<span class="page-number btn"></span>&nbsp;').text(page + 1).bind('click', {
                newPage: page
            }, function(event) {
                currentPage = event.data['newPage'];
                jQLBLPAdmintable.trigger('repaginate');
                jQLBLPAdmin(this).addClass('active').siblings().removeClass('active');
            }).appendTo(jQLBLPAdminpager).addClass('clickable');
        }
        jQLBLPAdminpager.insertAfter(jQLBLPAdmintable).find('span.page-number:first').addClass('active');
    });
}

var check_for_update = function(silent) {
    silent = (typeof(silent) != 'undefined' && silent === true);
    var data = {};
    data.action = 'silent_update_check';
    jQLBLPAdmin.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data,
        success: function(response) {
            if (silent) return;
            if (response.result == 'ko') {
                showErrorMessage(response.msg);
            } else {
                if (response.data) {
                    showSuccessMessage(response.msg, true);
                } else {
                    showSuccessMessage(response.msg, false);
                }
            }
        }
    });
}

var section_cache = {

};

var setSectionCache = function(section, content, buttons) {
    section_cache[section] = [content, buttons];
}

var getSectionCache = function(section) {
    return (section in section_cache) ? section_cache[section] : false;
}

var clearSectionCache = function(section) {
    if (section in section_cache) delete section_cache[section];
}

var displaySection = function(section, content, buttons, force) {
    force = (typeof(force) != 'undefined' && force === true);
    if (section != 'edit_video') {
        setSectionCache(section, content, buttons);
    }
    jQLBLPAdmin('#' + section).find('.ajax-content').html(content);
    jQLBLPAdmin('.ajax-buttons').html(buttons);
    switch (section) {
        case 'my_videos':
            my_videos();
            break;
        case 'video_settings':
            video_controls('global');
            break;
        case 'add_video':
            video_controls('add');
            break;
        case 'edit_video':
            video_controls('edit');
            break;
        case 'player_settings':
            player_controls();
            break;
    }
}

var forceLoadSection = function(section) {
    loadSection(section, true);
}

// main section load function
var loadSection = function(section, force) {
    force = (typeof(force) != 'undefined' && force === true);
    var data = {};
    data.action = section;
    if (section == 'edit_video') {
        data.video_id = leadplayer_edit_video_id;
    } else if (!force) {
        var cache = getSectionCache(section);
        if (cache !== false) {
            displaySection(section, cache[0], cache[1]);
            return;
        }
    }
    jQLBLPAdmin.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data,
        error: reqFail,
        success: function(response) {
            var r = response.split('<!--BLOCK_SEPARATOR-->');
            displaySection(section, r[0], r[1], force);
        }
    });
}

var preloadSection = function(section) {
    clearSectionCache(section);
    var data = {};
    data.action = section;
    jQLBLPAdmin.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data,
        success: function(response) {
            var r = response.split('<!--BLOCK_SEPARATOR-->');
            setSectionCache(section, r[0], r[1]);
        }
    });
};

jQLBLPAdmin(document).ready(function() {

    var v = jQLBLPAdmin.fn.jquery.split('.');
    v = parseFloat(v[0] + '.' + v[1]);
    if (v < 1.7) {
        alert('You are using old version of jQuery. Please upgrade to newer version of WordPress 3.3 or later (which includes required version of jQLBLPAdmin).');
        return false;
    }

    // is this main page?
    if (!(typeof(leadplayer_main_page) != 'undefined' && leadplayer_main_page === true)) {
        return;
    }

    window.LEADPLAYER_PREVENT_OPT_IN_COOKIE = true;

    // fix for absolute positioned elements
    fixFlashButtons();

    // video preview modal
    jQLBLPAdmin('#video-modal').on('show', function() {
        var vd = video_preview_data.video;
        var mv = jQLBLPAdmin('#video-modal');
        mv.find('.modal-title').html(vd.title);
        mv.css('margin-left', ((parseInt(vd.width, 10) / -2) - 50));
        mv.css('width', parseInt(vd.width, 10) + 50);
        var mb = mv.find('.modal-body');
        mb.html('<div></div>');
        mb.css('max-height', 500);
        mb.css('padding-top', 20);
        mb.css('padding-bottom', 20);
    });
    jQLBLPAdmin('#video-modal').on('shown', function() {
        var mv = jQLBLPAdmin('#video-modal');
        var mb = mv.find('.modal-body');
        mb.find('div').replaceWith(jQLBLPAdmin(video_preview_data.html));
    });
    jQLBLPAdmin('#video-modal').on('hide', function() {
        jQLBLPAdmin('#video-modal').find('.modal-body').html('');
    });

    // video embed

    jQLBLPAdmin('#video-embed-modal').on('show', function() {
        var vd = video_embed_data;
        var mv = jQLBLPAdmin('#video-embed-modal');
        mv.find('.modal-title').html('Embed video: ' + vd.video.title);
        var mb = mv.find('.modal-body');
        mb.find('.lp-embed-code-area').val(vd.html.trim());
        mb.find('.lp-shortcode-code-area').val(vd.shortcode);
        mb.css('max-height', 500);
        mb.css('padding-top', 20);
        mb.css('padding-bottom', 20);
        mb.find('a.show-lp-html-code').click(function(){
            jQLBLPAdmin(this).hide();
            mb.find('.html-embed-code').show();
            return false;
        });
    });
    jQLBLPAdmin('#video-embed-modal').on('shown', function() {
    });
    jQLBLPAdmin('#video-embed-modal').on('hide', function() {
        jQLBLPAdmin('#video-embed-modal').find('.modal-body').find('.lp-shortcode-code-area, .lp-embed-code-area').html('');
    });
    jQLBLPAdmin('#video-embed-modal').find('.lp-shortcode-code-area, .lp-embed-code-area').click(function() {
        jQLBLPAdmin(this).select();
    }).mouseup(function(e){
        e.preventDefault();
    });

    // zclip setup
    if (typeof(zclip_path) != 'undefined') {
        ZeroClipboard.setMoviePath(zclip_path);
    }

    // hashed links
    jQLBLPAdmin('.navbar li a').click(function() {
        window.location.hash = jQLBLPAdmin(this).attr('href');
    });

    // tab actions
    jQLBLPAdmin('a[data-toggle="tab"]').on('show', function(e) {
        jQLBLPAdmin('.form-err').hide();
        var d = e.target.hash;
        var section = d.substr(1);
        if (typeof(e.relatedTarget) != 'undefined') {
            var old_section = e.relatedTarget.hash.substr(1);
            // clear old
            var htm = ['<p class="loading-txt">Loading &hellip;</p>', ''];
            var rt = jQLBLPAdmin(e.relatedTarget.hash);
            var cntnt = rt.find('.ajax-content');
            var btnsz = jQLBLPAdmin('.ajax-buttons');
            cntnt.html(htm[0]);
            btnsz.html(htm[1]);
        }
        if (section == 'edit_video') return;
        loadSection(section);
    });

    // hide notifications and errors
    jQLBLPAdmin('#set-save, #set-err').hide().slideDown(300).delay(6000).slideUp(300);

    // create default cache
    var main_content = jQLBLPAdmin('#my_videos').find('.ajax-content').html();
    var main_buttons = jQLBLPAdmin('.ajax-buttons').html();
    setSectionCache('my_videos', main_content, main_buttons);

    // add default actions for the table
    //my_videos();
    forceLoadSection('my_videos');

    // preload
    preloadSection('video_settings');
    preloadSection('player_settings');
    preloadSection('add_video');

    // silent check for update
    if (!LEADPLAYER_KIOSK_MODE) {
        check_for_update();
    }

    jQLBLPAdmin('#check_for_update').click(function() {
        check_for_update();
        return false;
    });

});
