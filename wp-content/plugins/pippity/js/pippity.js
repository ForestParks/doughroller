pippity_start = function() 
{
	window.$j = jQuery;
	$j(function(){
		var url = location.href;
		pty.wmode();
		pty.testing = url.indexOf('pty_test') > -1;
		pty.now = url.indexOf('pty_open') > -1;
		pty.ePops = pty.eventPopups();
		if(!(($j('#pty_nopop').length || pty.m()))) {
			pty.load(url);
		}
	});
	window.pty = {
		load: function(url){
			pty.do_hook('loading');
			var loadPops = '';
			if (pty.ePops.length) {
				loadPops = '&loadPopups='+pty.ePops.join(',');
			}
			var href = encodeURIComponent(location.href.replace(location.hash, '').replace('http://', '').replace('https://', ''));
			var ref = encodeURIComponent(document.referrer.replace('http://', '').replace('https://', ''));
			var hash = location.hash.substr(1).length ? location.hash.substr(1) : 'empty';
			var url = PTY_AJAX+'?action=pty_getActive&incoming_ajax=true&ref='+ref+'&href='+href+'&url='+hash+loadPops;
			var s = document.createElement('script');
			s.id = 'pty_w';
			s.src = url;
			s.text = 'text/javascript';
			document.getElementsByTagName('head')[0].appendChild(s);
		},
		eventPopups: function(){
			var ids = [];
			$j('.pty_click, .pty_click a').each(function(){
				var id = pty.getID($j(this));
				if (id !== undefined) {
					ids.push(id);
				}
			});
			return ids;
		},
		getID: function(elm){
			if (!elm.hasClass('pty_click')) {
				elm = elm.closest('.pty_click');
			}
			var classes = elm.attr('class').split(' ');
			var id = false;
			$j.each(classes, function(i, v){
				if (v.indexOf('pty_id') > -1) {
					var b = v.split('_');
					id = b[2];
				}
			});
			return id;
		},
		add_hook: function(hook, fnc) {
			if (pty.hooks[hook] == undefined) {
				pty.hooks[hook] = function(){fnc();};
			}
			else {
				var existing = pty.hooks[hook];
				pty.hooks[hook] = function() {existing();fnc();};
			}
		},
		do_hook: function(hook) {
			if (pty.hooks[hook] != undefined) {
				pty.hooks[hook]();	
			}
		},
		m: function(){
			var uagent = navigator.userAgent.toLowerCase();
			var agents = ['ipad', 'iphone', 'ipod', 'android', 'iemobile', 'symbian', 'kindle', 'silk'];
			var ismobile = false;
			$j.each(agents, function(i, v){
				if (uagent.indexOf(v) > -1) {
					ismobile = true;
				}
			});
			return ismobile;
		},
		wmode: function() {
			$j('iframe').each(function(){
				var src = $j(this).attr('src');
				if (src !== undefined && src.indexOf('youtube.') > -1 && src.indexOf('wmode') < 0) {
					var pre = '?';
					if (src.indexOf('?') > -1) {
						pre = '&';
					}
					$j(this).attr('src', src+pre+'wmode=transparent');
				}
			});
			$j('embed').each(function(){
				var $t = $j(this);
				if ($t !== undefined && $t.length) {
					var attr = $t.attr('wmode');
					if (attr === undefined || !attr) {
						$t
							.attr('wmode', 'transparent')
							.css('position', 'relative')
							.css('z-index', '1')
							.css('display', 'block')
					}
				}
			});
		},
		hooks: {}
	}
}()