function randomHash(length) {
    var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz'.split('');
    
    if (! length) {
        length = Math.floor(Math.random() * chars.length);
    }
    
    var str = '';
    for (var i = 0; i < length; i++) {
        str += chars[Math.floor(Math.random() * chars.length)];
    }
    return str;
}
API = {
	apiCall: function(options) {
		var def_settings = {
				data: {},
				async: true,
				type: 'POST',
				success: function(data){},
				complete: function(){},
				error: function(){}
			};
		var settings = jQLBLPAdmin.extend(def_settings,options);
		settings.data['rand_hash'] = randomHash(32);
		jQLBLPAdmin.ajax({
	  		type: settings.type,
			url: settings.url,
			data: settings.data,
			dataType: 'json',
			cache: false,
		  	success: function(data) {
		  		if (data.status != 'ok') {
		  			settings.success(data);
		  		} else {
		  			settings.error();
		  			API.ajaxError('', '');
		  		}
		  	},
		  	error: function() {
		  		settings.error();
		  		API.ajaxError('','');
		  	},
		  	complete: function() {
		  		settings.complete();
		  	}
		});
	},
	ajaxError: function(new_msg, new_title) {
		var msg = 'Oops, something went wrong. Please try again later.';
		if (new_msg) {
			msg = new_msg; 
		}
		var title = 'Oops, error';
		if (new_title) {
			title = new_title; 
		}
		var html = '<div class="modal fade" id="errorModal">';
		html = html + ' <div class="modal-header">';
		html = html + '    <a class="close" data-dismiss="modal">×</a>';
		html = html + '    <h3>'+title+'</h3>';
		html = html + '  </div>';
		html = html + '  <div class="modal-body">';
		html = html + '    <p>'+msg+'</p>';
		html = html + '  </div>';
		html = html + '  <div class="modal-footer">';
		html = html + '    <a href="#" class="btn" data-dismiss="modal">Close</a>';
		html = html + '  </div>';
		html = html + '</div>';
		jQLBLPAdmin(html).modal();
	}
}
function addAlertFromResponse(data) {
	var msg = '';
	if (data.header) {
		msg = '<strong>'+data.header+'</strong> ';
	} else if (data.status == 'success') {
		msg = '<i class="icon-ok"></i> ';
	}
	msg = msg+'<span>'+data.msg+'</span>';
	setTimeout(addAlert({status: data.status, message: msg, timeout: 2500}));
}
function addAlert(options) {
	var alrt = jQLBLPAdmin('<div class="alert fade in alert-'+options.status+'"><a class="close" data-dismiss="alert">&times;</a>'+options.message+'</div>');
	jQLBLPAdmin('#alert_placeholder').append(alrt);
	alrt.alert();
	alrt.hide();
	alrt.fadeIn('fast');
	var timeout = 6000;
	if (options.timeout) {
		timeout = options.timeout;
	}
	var tmr = setTimeout(function(){alrt.alert('close')},timeout);
}
