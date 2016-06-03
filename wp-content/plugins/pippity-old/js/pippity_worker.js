/*
 * Renders Popup HTML
 */

if (pty.theme.events !== undefined && pty.theme.events) {
	$j.each(pty.theme.events, function(ev, action){
		pty.add_hook(ev, function(){
			eval(_pty.stripSlashes(action));
		});
	});
}
pty.open_handler = function(){
	pty.open();
};
pty.build = function(){
	if (pty.theme.animate === undefined) {
		pty.theme.animate = 0;
	}
	pty.loadJs();
	$j('#pty_pkg').remove();
	$j('#pty_styles').remove();
	var popup = $j('<div id="pty_pkg" class="pty_pkg"/>')
		.hide()
		.prependTo('body');

	//Main HTML
	popup
		.append('<div id="pty_overlay"/>')
		.append('<div id="pty_popup"></div>');
	$j('#pty_popup', popup).html(_pty.stripSlashes(pty.theme.html));
	if (pty.theme.button !== undefined) {
		$j('#pty_submit', pty.frame).addClass(pty.theme.button+'-button');
	}

	if (PTY_AFFLINK !== "" && PTY_AFFTEXT !== "") {
		if (!$j('#pty_afflink').length) {
			$j('#pty_pkg').append('<a id="pty_afflink" href="#" target="_blank"></a>');
		}
		$j('#pty_afflink').attr('href', PTY_AFFLINK).html(PTY_AFFTEXT).show().css('display', 'inline');
	}
	else {
		$j('#pty_afflink', pty.frame).remove();
	}
	pty.buildForm();
	pty.addCss();
	pty.addCopy();
	pty.applyStyledImages();
	pty.applyStyledText();
	pty.applyCustomCss();
	pty.applyCustomForm();
	pty.position();
	pty.submitted = false;
	var submit = function() {
		if(!pty.submitted) {
			pty.do_hook('submit_start');
			if (!$j('#pty_form #pty_email:visible').length || $j('#pty_form #pty_email').val().indexOf('@') > -1) {
				pty.submitted = true;
				if (!pty.testLoad()) {
					pty.createCookie(pty.cookie(), 'visited', 1000);
				}
				if (pty.theme.notify !== undefined && pty.theme.notify.length) {
					var post = {
						fields: {
							email: $j('#pty_form #pty_email:visible').val(),
							name: $j('#pty_form #pty_name:visible').val()
						},
						popupid: pty.theme.popupid
					};
					_pty.send('notify', post);
				}
				pty.do_hook('submitted');
				pty.markImp('convertTime');
				pty.unload();
				if (pty.theme.settings.ajaxSubmit !== undefined && parseInt(pty.theme.settings.ajaxSubmit) > 0) {
					pty.ajaxSubmit();
					return false;
				}
				if(pty.theme.settings.new_page !== undefined && +pty.theme.settings.new_page){ 
					pty.close();
				}
			}
			else {
				em = $j('#pty_email');
				em.fadeTo(150, '.4', function(){
					em.fadeTo(150, '1', function(){
						em.fadeTo(150, '.4', function(){
							em.fadeTo(150, '1', function(){
							});
						});
					});
				});
				pty.do_hook('insufficient_values');
				return false;
			}
		}
	};
	$j('#pty_form').submit(submit);
	$j('.pty_submit, .pippity_submit')
		.submit(submit)
		.click(submit);
};
pty.ajaxSubmit = function() {
	var post = {};
	post = _pty.formToJson('pty_form');
	var url = pty.theme.form.action;
	if (pty.theme.form.type == 'wysija') {
		url = PTY_AJAX;
		post.action = 'pty_wysija_submit';
		post.incoming_ajax = true;
	}
	$j.post(url, post);
	em = $j('#pty_submit');
	em.fadeTo(450, '.4', function(){
		em.fadeTo(450, '1', function(){
			em.fadeTo(450, '.4', function(){
				em.fadeTo(450, '1', function(){
					em.val('✔').css('font-family', 'arial,helvetica,sans-serif');
					setTimeout(function() {
					pty.close();
					}, 750);
				});
			});
		});
	});
};
pty.testLoad = function() {
	if ((pty.now !== undefined && pty.now) || (pty.testing !== undefined && pty.testing)) {
		return true;
	}
	return false;
};
pty.loadJs = function() {
	if (pty.hooks === undefined) {
		pty.hooks = {};
	}
	if (pty.theme.js !== undefined && pty.theme.js.length > 0) {
		eval(pty.theme.js);
		pty.do_hook('js_loaded');
	}
	else {
		pty.do_hook('no_js');
	}
};
pty.loadPopupFonts = function(id) {
	var fonts = [];
	var theme = pty.themes[id];
	if (theme.fonts !== undefined) {
		$j.each(theme.fonts, function(i, v){
			if (!$j('#pty_font_'+v, pty.frame).length) {
				fonts.push(v);
			}
		});
		if (fonts.length) {
			fonts = fonts.join(',');
			var key = PTY_KEY;
			var fontUrl = 'https://pippity.com/get_f_asset.php?fonts='+fonts;
			if (PTY_URL.indexOf('https://') === 0) {
				fontUrl = fontUrl.replace(/http:\/\//g, 'https://');
			}
			var loaded = function(){
				pty.isReady('fonts-'+id);
				var c = 0;
				$j.each(theme.families, function(i, v){
					c++;
					$j('body').append('<div class="pty_fontload"  style="position:absolute; top:-9999px; left:-9999px;" id="pty_fontload_'+c+'" style="font-family:'+v+'">How many a man has thrown up his hands at a time when a little more effort, a little more patience would have achieved success. -Elbert Hubbard</div>');
				});
				setTimeout(function(){
					$j('.pty_fontload').hide();
				}, 1500);
			};
			var link = document.createElement('link');
				link.type = 'text/css';
				link.rel = 'stylesheet';
				link.href = fontUrl;
			document.getElementsByTagName('head')[0].appendChild(link);
			var img = document.createElement('img');
			img.onerror = function(){
				loaded();
				document.body.removeChild(img);
			};
			document.body.appendChild(img);
			img.src = fontUrl;
		}
		else {
			pty.isReady('fonts-'+id);
		}
	}
	else {
			pty.isReady('fonts-'+id);
		}
};
pty.preload = function() {
	$j('body').append('<div id="pty_preload"></div>');
	$j('#pty_preload').attr('style', 'position:absolute; top:-9999px; left:-9999px');
	if (pty.autoid !== undefined) {
		pty.preloadPopup(pty.autoid);
		pty.loadPopupFonts(pty.autoid);
	}
	$j.each(pty.themes, function(id, theme) {
		if (theme !== undefined && id !== pty.autoid) {
			pty.preloadPopup(id);
			pty.loadPopupFonts(id);
		}
	});
};
pty.preloadPopup = function(id) {
	$j.each(pty.themes[id].preload,
		function(i, v){
			$j('#pty_preload').append('<img src="'+v+'"/>');
		});
};
pty.buildForm = function() {
	if (pty.designing || pty.theme.form === undefined || !pty.theme.form) {
		return false;
	}
	if (pty.theme.form.action !== undefined) {
		$j('#pty_form')
			.attr('method', 'post')
			.attr('action', pty.theme.form.action);
	}
	if (pty.theme.form.name !== undefined) {
		$j('#pty_name').attr('name', pty.theme.form.name);
	}
	if (pty.theme.form.email !== undefined) {
		$j('#pty_email').attr('name', pty.theme.form.email);
	}
	if (pty.theme.form.stats !== undefined) {
		$j('body').append('<img src="'+pty.theme.form.stats+'" style="position:absolute; top:-9999px; left:-9999px"/>');
	}
	if (pty.theme.form.hidden !== undefined) {
		$j.each(pty.theme.form.hidden, function(i, v){
			$j('#pty_form').append('<input type="hidden" name="'+v.name+'" value="'+v.value+'"/>');
		});
	}
	if(pty.theme.settings.new_page !== undefined && +pty.theme.settings.new_page){
		$j('#pty_form').attr('target', '_blank');
	}
	pty.buildCustomFields();
};
pty.buildCustomFields = function() {
	var cstm = $j('#pty_custom_fields', pty.frame);
	if (pty.theme.form.custom_fields !== undefined && pty.theme.form.custom_fields.length) {
		var html = '';
		var fields = {};
		$j('.pty_custom_field', pty.frame).remove();
		if (!cstm.length) {
			cstm = $j('<div/>', pty.frame).attr('id', 'pty_custom_fields');
			cstm.insertBefore($j('input', pty.frame).eq(0));
		}
		$j.each(pty.theme.form.custom_fields, function(i, field) {
			if (field.label !== undefined) {
				var elm = $j('<input/>')
					.attr('type', 'text')
					.attr('class', 'pty_input pty_custom_field')
					.attr('id', 'pty_input-'+field.name)
					.attr('name', field.name)
					.labeledInput(field.label);
				elm.insertBefore(cstm);
				fields[field.name] = field.label;
			}
			else {
				var mv_field = $j('#pty_'+field.pre, pty.frame);
				var nw = mv_field.clone();
				nw.insertBefore(cstm);
				mv_field.remove();
			}
		});
	}
	cstm.remove();
};
pty.addCss = function() {
	pty.theme.css += "#pty_overlay{ position:fixed; z-index:100000000; height:100%; width:100%; top:0; } #pty_popup{ position:fixed; z-index:1000000001; }";
	if (PTY_URL.indexOf('https://') === 0) {
		pty.theme.css = pty.theme.css.replace(/http:\/\//g, 'https://');
	}
	var css = $j('<style id="pty_styles" type="text/css">'+pty.theme.css+'</style>');
	$j('head').append(css);
	$j('#pty_close').css('z-index', '10000');
	$j('#pty_form').css('z-index', '10000');
};
pty.addCopy = function() {
	var slashGex = new RegExp(/(\\+)'/);
	$j.each(pty.theme.copy, function(i, v){
		if (v.type == 'image') {
			if (v.src.length) {
				$j('#pty_'+i+'Shell', pty.frame).append(
					'<img src="'+v.src+'" id="pty_'+i+'"/>'
				);
			}
		}
		else if (v.type != 'submit' && v.type != 'field'){
			var copy = v.text !== undefined ? v.text : '';
			copy = _pty.stripSlashes(copy).replace(slashGex, "'");
			if (v.type == 'html') {
				copy = pty.parseCopy(copy);
			}
			if (typeof v.image == 'string') {
				copy = '<img src="'+v.image+'" id="pty_image-'+i+'" class="pty_image"/>' + copy;
			}
			$j('#pty_'+i, pty.frame).html(copy);
		}
	});
	$j('#pty_name').val('').labeledInput(_pty.stripSlashes(pty.theme.copy.name.text).replace(slashGex, "'"));
	$j('#pty_email').val('').labeledInput(_pty.stripSlashes(pty.theme.copy.email.text).replace(slashGex, "'"));
	$j('#pty_submit').val(_pty.stripSlashes(pty.theme.copy.submit.text).replace(slashGex, "'"));
	pty.do_hook('copy_added');
};
pty.parseCopy = function(copy) {
	var lines = copy.split('\n');
	var output = '';
	var inList = false;
	$j.each(lines, function(i, v){
		var isBullet = false;
		if (v.indexOf('*') === 0) {
			isBullet = true;
		}
		if (!inList && isBullet) {
			output += "<ul>";
			inList = true;
		}
		if (isBullet) {
			output += '<li class="pty_bullet">'+v.replace('*', '')+'</li>';
		}
		if (inList && !isBullet) {
			output += "</ul>";
			inList = false;
		}
		if (!inList) {
			output += v+'<br/>';
		}
	});
	return output;
};
pty.events = function() {
	//Events
	$j(document).on('click', '#pty_close', pty.close);
	$j(document).on('click', '.pty_close', pty.close);
	$j(document).on('click', '.pty_convert', pty.convertTrigger);
	$j(document).on('click', '#pty_popup-submit', pty.clickSubmitHandler);
	$j(document).on('click', '.pty_click', pty.clickHandler);
	$j(document).on('click', '#pty_overlay', pty.close);
	$j(window).blur(function(){pty.markImp('blurTime');});
	$j(window).focus(function(){pty.markImp('focusTime');});
	$j(window).keyup(pty.keywatch);
};
pty.clickSubmitHandler = function() {
	$j('#pty_form').submit();
	return false;
};
pty.clickHandler = function(){
	var id = pty.getID($j(this));
	pty.open(id);
	return false;
};

/*
 * Position popup
 */
pty.position = function(){
	var win = $j(window);
	$j('#pty_pkg').show();
	var dialogWidth = $j('#pty_popup', pty.frame).width();
	var dialogHeight = $j('#pty_popup', pty.frame).outerHeight();
	$j('#pty_pkg').hide();
	var x = pty.theme.x;
	var y = pty.theme.y;
	if (!parseInt(pty.theme.animate)) {
		pty.theme.startX = false;
		pty.theme.startY = false;
	}
	if (pty.theme.startX !== undefined && pty.theme.startX) {
		x = pty.theme.startX;
	}
	if (pty.theme.startY !== undefined && pty.theme.startY) {
		y = pty.theme.startY;
	}

	// Get dimensions
	var dialogLeft = pty.getPositionDim(x, win.width(), dialogWidth, 'x');
	var dialogTop = pty.getPositionDim(y, window.innerHeight, dialogHeight, 'y');
	if(pty.isIE6){
		dialogTop = $j(window).scrollTop() + (win[1]/2) - (dialogHeight/2);
		$j('#pty_popup', pty.frame).css('position', 'absolute');
	}
	if (dialogTop.pos < 10 && +pty.theme.hideFlash !== 0) {
		dialogTop.pos = 10 + $j(window).scrollTop();
		$j('#pty_popup', pty.frame).css('position', 'absolute');
	}
	$j('#pty_popup', pty.frame)
		.css(dialogTop['place'], dialogTop['pos'] + 'px')
		.css(dialogLeft['place'], dialogLeft['pos'] + 'px')
		.addClass('pty_pos_y_'+dialogTop['place'])
		.addClass('pty_pos_x_'+dialogLeft['place']);
};
pty.getPositionDim = function(position, winDim, boxDim, axis){
	var dim = 0;
	var place = '';
	switch(position){
		case 'center':
			dim = (winDim/2) - (boxDim/2);
			place = axis == 'x' ? 'left' : 'top';
			break;
		case 'left':
		case 'top':
			dim = ($j('#wpadminbar').length && position == 'top') ? 28 : 0;
			place = axis == 'x' ? 'left' : 'top';
			break;
		case 'right':
		case 'bottom':
			dim = 0;
			place = axis == 'x' ? 'right' : 'bottom';
			break;
		case 'wideleft':
		case 'above':
			dim = 0 - boxDim - 4;
			place = axis == 'x' ? 'left' : 'top';
			break;
		case 'wideright':
		case 'below':
			dim = 0 - boxDim - 4;
			place = axis == 'x' ? 'right' : 'bottom';
			break;
		default:
			dim = position.replace('px', '');
			place = axis == 'x' ? 'left' : 'top';
			break;
	}
	return {place: place, pos: dim};
};
pty.open = function(id) {
	if (id === undefined) {
		if (pty.autoid !== undefined) {
			id = pty.autoid;
		}
		else {
			return false;
		}
	}
	// Unbind all triggers
	$j(window).unbind('scroll', pty.trigger);
	$j(window).unbind('blur', pty.open_handler);
	$j('html').unbind('mouseleave', pty.open_handler);

	pty.theme = pty.themes[id];
	pty.popupid = id;
	pty.ready('fonts-'+id, function(){
		// Add to HTML
		pty.build();

		// Setup Overlay, it should work even if only set in CSS
		if (pty.theme.overlay === undefined) {
			pty.theme.overlay = {};
			pty.theme.overlay.background = $j('#pty_overlay').css('background-color');
			pty.theme.overlay.opacity = $j('#pty_overlay').css('opacity');
		}
		$j('#pty_overlay', pty.frame)
			.css('background-color', pty.theme.overlay.background)
			.fadeTo(0, pty.theme.overlay.opacity);

		// Fading In
		if (pty.theme.settings.fade !== undefined && parseInt(pty.theme.settings.fade) && pty.theme.overlay.opacity > 0) {
			$j('#pty_pkg #pty_overlay', pty.frame).css('opacity', 0);
			$j('#pty_pkg #pty_popup', pty.frame).css('opacity', 0);
			$j('#pty_pkg', pty.frame).show();
			if (pty.theme.noOverlay === undefined && !pty.theme.noOverlay) {
				var overlayOpacity = $j('#pty_pkg #pty_overlay', pty.frame).show().css('opacity', '');
				$j('#pty_overlay', pty.frame).animate( {opacity: pty.theme.overlay.opacity},
					{
						queue: false,
						duration: 100,
						complete: function(){
							pty.do_hook('overlayFadedIn');
						}
					}
				);
			}
			else {
				$j('#pty_overlay').hide();
			}
			$j('#pty_popup', pty.frame).animate( {opacity: 1},
				{
					queue: false,
					duration: 400,
					complete: function(){
						pty.do_hook('open');
					}
				}
			);
			pty.do_hook('fadeIn');
		}

		// Popping In
		else {
			$j('#pty_pkg', pty.frame).show();
			if ((pty.theme.noOverlay !== undefined && pty.theme.noOverlay) || +pty.theme.opacity === 0) {
				$j('#pty_overlay', pty.frame).hide();
			}
			pty.do_hook('open');
		}
		pty.animate();
		pty.addImp();
		if ( pty.theme.hideFlash === undefined) {
			pty.theme.hideFlash = 1;
		}
	});
	return false;
};
pty.animate = function(){
	var win = $j(window);
	var dialogWidth = $j('#pty_popup', pty.frame).width();
	var dialogHeight = $j('#pty_popup', pty.frame).outerHeight();
	var isAnimated = false;
	if (pty.theme.animate !== undefined && parseInt(pty.theme.animate)) {
		var animations = {};
		if (pty.theme.startX !== undefined && pty.theme.startX) {
			var dimX = pty.getPositionDim(pty.theme.x, win.width(), dialogWidth, 'x');
			animations[dimX['place']] = dimX['pos'];
			isAnimated = true;
		}
		if (pty.theme.startY !== undefined && pty.theme.startY) {
			var dimY = pty.getPositionDim(pty.theme.y, win.height(), dialogHeight, 'y');
			animations[dimY['place']] = dimY['pos'];
			isAnimated = true;
		}
		if (isAnimated) {
			pty.do_hook('animationStarted');
			$j('#pty_popup', pty.frame).animate(animations, {
				queue: false,
				duration: 300,
				complete: function(){
					pty.do_hook('animationDone');
				}
			});
		}
	}
	return true;
};
pty.close = function() {
	if (!pty.testLoad()) {
		pty.createCookie(pty.cookie(), 'visited', pty.theme.settings.expire);
	}
	if (pty.theme.settings.fade !== undefined && parseInt(pty.theme.settings.fade)) {
		$j('#pty_overlay', pty.frame).animate( {opacity: 0},
			{
				queue: false,
				duration: 100,
				complete: function(){
					pty.do_hook('overlayFadedOut');
				}
			}
		);
		$j('#pty_popup', pty.frame).animate( {opacity: 0},
			{
				queue: false,
				duration: 300,
				complete: function(){
					$j('#pty_pkg').hide();
					pty.do_hook('closed');
				}
			}
		);
		pty.do_hook('fadeOut');
	}
	else {
		$j('#pty_pkg', pty.frame).hide();
		pty.do_hook('closed');
	}
	pty.markImp('closeTime');
	return false;
};
pty.keywatch = function(e) {
	if (e.keyCode == 27) {
		pty.close();
	}
};

/*
 * Apply Style
 */
pty.applyStyledImages = function(){
	if (pty.theme.styleImgs !== undefined) {
		$j.each(pty.theme.styleImgs, function(i, v){
			var imgUrl= pty.theme.url + 'images/'+ i + '__' + v + '.png';
			if (PTY_URL.indexOf('https://') === 0) {
				imgUrl = imgUrl.replace(/http:\/\//g, 'https://');
			}
			$j('.pty_'+i, pty.frame).css('background-image', 'url('+imgUrl+')');
		});
	}
};
pty.applyStyledText = function(){
	if (pty.theme.styleCopy !== undefined) {
		$j.each(pty.theme.styleCopy, function(elm, props){
			$j.each(props, function(prop, val){
				prop = prop.replace('_', '-');
				if (prop == 'font-family') {
					val = '"'+val+'"';
				}
				if (prop == 'font-size') {
					$j('#pty_'+elm+ ' .pty_bullet', pty.frame).css(prop, val);
				}
				$j('#pty_'+elm, pty.frame).css(prop, val);
				if (elm == 'email') {
					$j('.pty_custom_field', pty.frame).css(prop, val);
				}
			});
		});
	}
};
pty.applyCustomCss = function(){
	if (pty.theme.customCss !== undefined) {
		$j('body').append('<style id="pty_customCss" type="text/css">'+_pty.stripSlashes(pty.theme.customCss)+'</style>');
	}
};
pty.applyCustomForm = function(){
	if (pty.theme.customForm!== undefined) {
		var html = _pty.stripSlashes(pty.theme.customForm);
		if (typeof html == 'string' && html.length) {
			var form = $j('#pty_form', pty.frame);
			if (html.length > 0) {
				html = $j('<div/>').attr('id', 'pty_customFormShell').html(html);
				innerForm = $j('form', html);
				if (!innerForm.hasClass('pippity_submit')) {
					innerForm.addClass('pippity_submit');
				}
				form.before(html);
				form.hide();
			}
		}
	}
};

/**
 * Determines whether to use timedOpen or if there are comments on the page
 */
pty.start = function(){

	if (pty.theme === undefined || !pty.theme) {
		return false;
	}
	// Don't Open if set to only post pages
	if (parseInt(pty.theme.settings.postOnly) && !PTY_ISPOST) {
		return false;
	}
	pty.cval = pty.readCookie(pty.cookie());

	// Don't Open if visited
	if (pty.cval === 'visited') {
		return false;
	}

	// Don't open if not enough visits
	var cval = (pty.cval) ? parseInt(pty.cval) + 1 : 1;
	if (cval < pty.theme.settings.visits) {
		pty.createCookie(pty.cookie(), cval, 365);
		return false;
	}
	pty.build();
	var trig = false;
	if ($j('#pty_trigger').length) {
		trig = pty.theme.settings.trigger*1;
		pty.endY = $j('#pty_trigger').offset().top;
	}
	var win = $j(document).height();
	if(pty.endY > win || !trig){
		pty.timedOpen(pty.theme.settings.delay * 1000);
	}
	else if(trig){
		$j(window).scroll(pty.trigger);
	}

	// Show popup on mouseout
	if(pty.theme.settings.mouseout * 1) {
		$j('html').mouseleave(pty.open_handler);
	}

	// Show popup on page blur
	if(pty.theme.settings.blur * 1) {
		$j(window).blur(pty.open_handler);
	}
};

/**
 * Pulls the trigger when user has scrolled to set point
 */
pty.trigger = function(){
	if($j(window).scrollTop() > (pty.endY - $j(window).height())){
		pty.open();
	}
};

/**
 * Opens popup based on passed milliseconds
 */
pty.timedOpen = function(s){
	setTimeout(function(){pty.open();}, s);
};

/*
 * Analytics
 */
pty.unloaded = false;
pty.initUnload = function(){
	var i = document.createElement('iframe');
	i.src='';
	i.style.cssText = 'position:absolute; left:-9999px; top:-9999px;';
	document.getElementsByTagName('body')[0].appendChild(i);
	pty.unload = function(){
		if (!pty.unloaded) {
			pty.unloaded = true;
			pty.markImp('leaveTime', function(){
				var imp = encodeURIComponent(JSON.stringify(pty.impression));
				var AJAX= new XMLHttpRequest();
				AJAX.onreadystatechange = function(){if (AJAX.readyState!=4) {return false;}};
				AJAX.open("GET", PTY_AJAX+"?incoming_ajax=true&action=pty_recordImpression&imp="+imp, false);
				AJAX.send(null);
			});
		}
	};
	if (navigator.userAgent.indexOf('AppleWebKit/') > -1) {
		i.contentWindow.window.onunload=pty.unload;
		window.onbeforeunload=function(){
			var x=document.getElementsByTagName('iframe')[0];
			x.parentNode.removeChild(x);
		};
	} else {
		i.contentWindow.window.onbeforeunload=pty.unload;
		window.onbeforeunload = pty.unload;
	}
};
pty.impression = {};
pty.addImp = function() {
	pty.impression = {popupid: pty.popupid, page: location.href};
	var now = Math.floor((new Date()).getTime() / 1000);
	pty.impression.startTime = now;
	pty.impression.closeTime = 0;
	pty.impression.leaveTime = 0;
	pty.impression.closeTime = 0;
	pty.impression.convertTime = 0;
	pty.impression.blurTime = 0;
	pty.initUnload();
};
pty.convertTrigger = function () {
	pty.createCookie(pty.cookie(), 'visited', 1000);
	pty.markImp('convertTime');
	pty.unload();
};
pty.markImp = function(type, cb) {
	var start = pty.impression.startTime;
	pty.impression.type = type;
	var now = Math.floor((new Date()).getTime() / 1000);
	switch(type) {
		case 'leaveTime':
			pty.impression.leaveTime = now - start;
			if (!pty.impression.closeTime) {
				pty.impression.closeTime = now - start;
			}
		break;
		case 'closeTime':
			pty.impression.closeTime = now - start;
		break;
		case 'blurTime':
			pty.blurStart = now;
		break;
		case 'focusTime':
			if(pty.blurStart > 0) {
				pty.impression.blurTime += (now - pty.blurStart);
				pty.blurStart = 0;
			}
		break;
		case 'convertTime':
			pty.impression.leaveTime = now - start;
			pty.impression.convertTime = now - start;
			pty.impression.closeTime = now - start;
		break;
	}
	if (cb !== undefined) {
		cb();
	}
};
pty.createCookie = function(name, value, days){
	var expires = '';
	if(days){
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		expires = "; expires="+date.toGMTString();
	}
	else{
		expires = "";
	}
	value = escape(value);
	document.cookie = name+"="+value+expires+"; path=/";
};
pty.readCookie = function(name){
	name = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(name) === 0) return(unescape(c.substring(name.length,c.length)));
	}
	return false;
};
pty.cookie = function() {
	return pty.theme.cookie !== undefined && pty.theme.cookie.length ? pty.theme.cookie : 'pty_visited';
};

/*
 * Readys
 */
pty.readys = {};
pty.ready = function(id, fnc) {
	if (pty.readys[id] === undefined) {
		pty.readys[id] = {ready: false, fnc: false};
	}
	if(typeof pty.readys[id] == 'function'){
		pty.readys[id].fnc = function(){
			pty.readys[id];
		};
	}
	else{
		pty.readys[id].fnc = function(){
			fnc();
		};
	}
	if (pty.readys[id].ready) {
		pty.doReady(id);
	}
};
pty.isReady = function(id) {
	if (pty.readys[id] !== undefined) {
		pty.readys[id].ready = true;
		pty.doReady(id);
	}
	else{
		pty.readys[id] = {ready: true, fnc: false};
	}
};
pty.doReady = function(id) {
	pty.readys[id].fnc();
	pty.readys[id].fnc = null;
};

/*
 * Globals
 */
pty.endY = 0;
pty.blurStart = 0;
pty.designing = false;
pty.frame = document;

/*
 * Smart label inputs
 */
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
	if(obj.val() === '' || opts.forceChange){
		obj.val(val);
		obj.addClass('labeledInput-clear');
		if(opts.init !== undefined){
			opts.init();
		}
	}
	obj
	.focus(function(){
		if(obj.val() == val){
			obj.val(opts.focusText);
			obj.removeClass('labeledInput-clear');
			obj.addClass('labeledInput-active');
			if(opts.activated !== undefined){
				opts.activated();
			}
		}
	})
	.blur(function(){
		if (obj.val() == opts.focusText) {
			obj.val(val);
			obj.addClass('labeledInput-clear');
			obj.removeClass('labeledInput-active');
			if (opts.cleared !== undefined) {
				opts.cleared();
			}
		}
	});
	return this;
  };
(function($j) {
	if ( $j.fn.on === undefined) {
		$j.fn.on = function(events, selector, data, handler){
			$j(this).delegate(selector, events, data, handler);
			return this;
		}
	}
})(jQuery);

/*
 * Utilities
 */
_pty = {
	ari : function(a, i){
		return a[i];
	},
	idData :  function(obj){
		if(typeof obj != 'string'){
			obj = $j(obj).attr('id');
		}
		return _pty.ari(obj.split('-'), 1);
	},
	jNewer : function(v) {
		if (v === undefined) {
			v = '1.4.0';
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
	isset: function(str){
		var a = arguments, l = a.length, i = 0, undef;
		if (l === 0) {
			throw new Error('Empty isset');
		}
		while (i !== l) {
			if (a[i] === undef || a[i] === null) {
				return false;
			}
			i++;
		}
		return true;
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
	send: function(action, data, callback, opts) {
		var completed = false;
		if(opts === undefined){
			opts = {silent : false, timeout : 10000};
		}
		//Add preset data
		data.action = 'pty_' + action;
		data.incoming_ajax = true;
		//Make Ajax Request	
		$j.ajax({
			url : PTY_AJAX,
			data : data,
			type : 'POST',
			dataType : 'json',
			complete : function(rsp, status){

				json = '';

				// completed makes sure we don't run request twice (jquery jsonp bug)
				if(!completed){
					clearTimeout(pty.errTimeout);
					if(rsp !== undefined){
						var text = rsp.responseText+'';
						if (!_pty.jNewer()) {
							text = text.substr(text.indexOf('{'), (text.length-text.indexOf('{')-1));
						}
						if(!opts.silent){
							if(text.indexOf('"suc"') > -1){
								json =	$j.parseJSON(text);
								if(_pty.isset(callback)){
									callback(json);
								}
							}
							else{
								if(rsp.responseText.indexOf('"err"') > -1){
									json = $j.parseJSON(text);
								}
							}
						}
					}
					completed = true;
				}
			}
		});
	},
	trim: function(str){
		return str.replace(/^\s+|\s+$/g,"");
	},
	now: function() {
		var now = new Date();
		return now.getTime()/1000;
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
	__end : true
};
pty.do_hook('loaded');

// Get things going
pty.events();
pty.preload();
if (pty.now !== undefined && pty.now) {
	pty.open();
} else {
	pty.start();
}