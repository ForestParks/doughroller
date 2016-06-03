_pty = {
	ari : function(a, i){
		return a[i];
	}, 
	idData :  function(obj, opts){
		if (opts === undefined) {
			opts = { "combine": false}
		}
		if(typeof obj != 'string'){
			obj = $j(obj).attr('id');
		}
		var bits = obj.split('-');
		if (bits.length > 2 && opts.combine) {
			_pty.rRemove(bits, 0);
			return bits.join('-');
		}
		else {
			return bits[1];
		}
	},
	rRemove: function(r, from, to) {
	  var rest = r.slice((to || from) + 1 || r.length);
	  r.length = from < 0 ? r.length + from : from;
	  return r.push.apply(r, rest);
	},
	getForm : function(id){
		for(var i in document.forms){
			if(document.forms[i].id == id){
				return document.forms[i];
			}
		}
	},
	formToJson : function(id){
		var data = {};
		var form = _pty.getForm(id);
		for(var i in form.elements){
			if (form.elements[i].name !== undefined && typeof form.elements[i] == 'object') {
				data[form.elements[i].name] = form.elements[i].value+'';
			}
		}
		return data;
	},
	ucfirst: function(str){
		return str.substr(0,1).toUpperCase() + str.substr(1,str.length);
	},
	unSlug: function(str){
		str = str.split('-');
		$j.each(str, function(i, v){
			str[i] = _pty.ucfirst(v);
		});
		return str.join(' ');
	},
	makeSlug: function(str){
		return str.replace(/ /g, '-').toLowerCase();
	},
	rgbToHex: function(color) {
		if (color === undefined) {
			return;
		}
		if (color.substr(0, 1) === '#' || color === "transparent") {
			return color;
		}
		var digits = [];
		if (color.indexOf('rgba') > -1) {
			digits = /(.*?)rgba\((\d+), (\d+), (\d+), (\d+)\)/.exec(color);
		}
		else {
			digits = /(.*?)rgb\((\d+), (\d+), (\d+)\)/.exec(color);
		}
		var red = parseInt(digits[2]);
		var green = parseInt(digits[3]);
		var blue = parseInt(digits[4]);
		var rgb = blue | (green << 8) | (red << 16);
		rgb = rgb.toString(16);
		while (rgb.length < 6) {
			rgb = '0' + rgb;
		}
		return '#' + rgb;
	},
	count: function(obj){
		var count = 0;
		for (var k in obj) {
			if (obj.hasOwnProperty(k)) {
				++count;
			}
		}
		return count;
	},
	jNewer : function(v) {
		if (v === undefined) {
			v = '1.5.0';
		}
		var newer = true;
		var e = $j().jquery.split('.');
		v = v.split('.');
		$j.each(e, function(i, eV) {
			if (eV > v[i]) {
				newer = true;
				return false;
			}
			else if ( eV < v[i]) {
				newer = false;
				return false;
			}
		});
		return newer;
	},
	to5: function(x){
		return (x % 5) >= 2.5 ? parseInt(x / 5) * 5 + 5 : parseInt(x / 5) * 5;
	},
	clearTimeout: function(timo) {
		if (timo !== undefined) {
			clearTimeout(timo);
		}
	},
	px: function(str){
		return str + 'px';
	},
	trim: function(str){
		return str.replace(/^\s+|\s+$/g,"");
	},
	ltrim: function(str){
		return str.replace(/^\s+/,"");
	},
	rtrim: function(str){
		return str.replace(/\s+$/,"");
	},
	phptrim: function (str, charlist) {
		var whitespace, l = 0,
			i = 0;
		str += '';

		if (!charlist) {
			// default list
			whitespace = " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
		} else {
			// preg_quote custom list
			charlist += '';
			whitespace = charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
		}

		l = str.length;
		for (i = 0; i < l; i++) {
			if (whitespace.indexOf(str.charAt(i)) === -1) {
				str = str.substring(i);
				break;
			}
		}

		l = str.length;
		for (i = l - 1; i >= 0; i--) {
			if (whitespace.indexOf(str.charAt(i)) === -1) {
				str = str.substring(0, i + 1);
				break;
			}
		}

		return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
	},
	addSlashes: function(str){
		return (str+'').replace(/([\\"'])/g, "\\$1").replace(/\u0000/g, "\\0");
	},
	stripSlashes: function(str){
		var slashSingleGex = new RegExp(/(\\+)'/g);
		var slashDoubleGex = new RegExp(/(\\+)"/g);
		str = (str+'').replace(/\\(.?)/g, function (s, n1) {
			switch (n1) {
				case '\\':
					return '\\';
				case '0':
					return '\0';
				case '':
					return '';
				default:
					return n1;
			}
		});
		str = str.replace(slashSingleGex, "'");
		str = str.replace(slashDoubleGex, '"');
		return str;
	},
	subText: function(str, size, end)
	{
		if (end === undefined) {
			end = '...';
		}
		if (str.length - 3 > size) {
			str = str.substr(0, size)+'...';
		}
		return str;
	},
	objEquals: function(o, x){
		for(var p in o) {
			if(typeof(x[p])=='undefined') {return false;}
		}

		for(p in o) {
			if (o[p])
			{
				switch(typeof(o[p]))
				{
					case 'object':
						if (!dpl.objEquals(o[p], x[p])) {
							return false;
						}
						break;
					case 'function':
							if (typeof(x[p])=='undefined' || (p != 'equals' && o[p].toString() != x[p].toString())) { return false; } break;
					default:
							if (o[p] != x[p]) { return false; }
				}
			}
			else
			{
				if (x[p])
				{
					return false;
				}
			}
		}

		for(p in x) {
			if(typeof(o[p])=='undefined') {return false;}
		}

		return true;
	},
	a : {
		setup: function() {
			_pty.a.url = PTY_AJAX;
		},
		send: function(action, data, callback, opts) {
			var completed = false;
			if(opts === undefined){
				opts = {silent : false, timeout : 10000};
			}
			if(!opts.silent){
				_pty.a.loading();
			}

			//Add preset data
			data.action = 'pty_' + action;
			data.incoming_ajax = true;
			if (!opts.silent) {
				$j('#pty_ajaxMsg').remove();
				$j('body').append('<div id="pty_ajaxMsg">Loading...</div>');
			}
			_pty.a.placeMsg();
			//Make Ajax Request	
			$j.ajax({
				url : _pty.a.url,
				data : data,
				type : 'POST',
				dataType : 'json',
				complete : function(rsp, status){

					// completed makes sure we don't run request twice (jquery jsonp bug)
					if(!completed){
						clearTimeout(pty.errTimeout);
						if(rsp !== undefined){
							var text = rsp.responseText+'';
							var json = '';
							if(text.indexOf('"suc"') > -1){
								json =	$j.parseJSON(text);
								if(!opts.silent){
									_pty.a.success(json.msg);
								}
								if(callback !== undefined){
									callback(json);
								}
							}
							else{
								if(text.indexOf('"err"') > -1){
									json =	$j.parseJSON(text);
									if(!opts.silent){
										if (json.msg !== undefined) {
											_pty.a.error(json.msg);
										}
										else {
											_pty.a.mayBeError();
										}
										if (opts.errFnc !== undefined) {
											opts.errFnc();
										}
									}
								}
								else{
									if(!opts.silent){
										if (opts.errFnc !== undefined) {
											opts.errFnc();
										}
										_pty.a.mayBeError();
									}
								}
							}
						}
						completed = true;
					}
				}
			});
		},
		loading: function(inout) {
			if(inout !== undefined){
				$j('#pty_ajaxMsg').fadeIn();
				inout = false;
			}
			else if (!inout){
				$j('#pty_ajaxMsg').animate({opacity: 0.6}, _pty.a.speed);
				inout = true;
			}
			else{
				$j('#pty_ajaxMsg').animate({opacity: 1}, _pty.a.speed);
				inout = false;
			}
			_pty.loadingTimeout = setTimeout(function(){
					_pty.a.loading(inout);
			},_pty.a.speed+10);
		},
		success : function(msg)
		{
			clearTimeout(_pty.loadingTimeout);
			$j('#pty_ajaxMsg').stop().html(msg).css('background','#E5F7E5').animate({opacity: 1}, _pty.a.speed);
			_pty.a.placeMsg();
			_pty.a.reset(1500);
		},
		error : function(msg)
		{
			clearTimeout(_pty.loadingTimeout);
			$j('#pty_ajaxMsg').stop().html(msg).css('background', '#F8E3E3').animate({opacity: 1}, _pty.a.speed);
			_pty.a.placeMsg();
			_pty.a.reset(5000);

		},
		reset : function(s)
		{
			setTimeout(function(){
				$j('#pty_ajaxMsg').fadeOut(750, function(){
				});
			},s);
		},
		placeMsg: function() {
			var dims = $j(window).width();
			$j('#pty_ajaxMsg').css('left', _pty.px((dims/2) - ($j('#pty_ajaxMsg').outerWidth()/2)));
		},
		mayBeError : function()
		{
			_pty.a.error('It seems there may have been a server error. Refresh the page & try again. If you keep having trouble '
			+ '<a href="http://pippity.com/help" target="_blank">please get in touch</a>.');
		},
		speed : 750
	}
};

(function($j) {
  $j.fn.labeledInput = function(val, opts){
	$j(obj).unbind('focus').unbind('blur');
	if(opts === undefined){
		opts = {};
	}
	if (opts.focusText === undefined){
		opts.focusText = '';
	}
	if (typeof opts.focusText == 'function'){
		opts.focusText = opts.focusText();
	}
	var obj = this;
	if(obj.val() == '' || opts.forceChange){
		obj.val(val);
		obj.addClass('labeledInput-clear');
		if(opts.init != undefined){
			opts.init();
		}
	}
	obj
	.focus(function(){
		if(obj.val() == val){
			obj.val(opts.focusText);
			obj.removeClass('labeledInput-clear');
			obj.addClass('labeledInput-active');
			if(opts.activated != undefined){
				opts.activated();
			}
		}
	})
	.blur(function(){
		if (obj.val() == opts.focusText) {
			obj.val(val);
			obj.addClass('labeledInput-clear');
			obj.removeClass('labeledInput-active');
			if (opts.cleared != undefined) {
				opts.cleared();
			}
		}
	});
	return this;
  }
})(jQuery);
(function($j) {
	if ( $j.fn.on === undefined) {
		$j.fn.on = function(events, selector,handler){
			$j(selector, $j(this)).live(events, handler);
			return this;
		}
	}
})(jQuery);