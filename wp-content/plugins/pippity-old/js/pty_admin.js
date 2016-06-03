var $j = jQuery;
var anl = false;
var slashGex = new RegExp(/(\\+)'/);
$j(function(){
	_pty.a.setup();
	pty.setup();
	snav.setup();
	pty_tooltip.setup();
	slider.setup();
});
pty = {
	changing_inp: false,
	setup: function(){

		// Events
		$j(document).on('change', '.pty_sImgSelect', pty.changeSImg);
		$j(document).on('change', '.pty_color', pty.changeTextColor);
		$j(document)
			.on('click', '.pty_fontViewShell', pty.showFontList);
		$j(document).on('click', '.pty_fontButton', pty.selectFontFromList_Click);
		$j(document).on('click', '.pty_styleButton', pty.selectStyle);
		$j(document).on('keyup', '#pty_copyForm .inp-text-change', pty.copyChange);
		$j(document).on('change', '.pty_fontSelect', pty.changeFont);
		$j(document).on('change', '.pty_fontsizeSelect', pty.changeFontSize);
		$j(document).on('click', '.pty_action', pty.doAction);
		$j(document)
			.on('keyup', '.inp-image-url', pty.changeImageInp)
			.on('paste', '.inp-image-url', pty.changeImageInp)
			.on('change', '.inp-image-url', pty.changeImageInp)
			.on('blur', '.inp-image-url', pty.blurImageInp);
		$j(document)
			.on('keyup', '.inp-imageType-url', pty.changeImageTypeInp)
			.on('paste', '.inp-imageType-url', pty.changeImageTypeInp)
			.on('change', '.inp-imageType-url', pty.changeImageTypeInp);
		$j(document).on('click', '.pty-inp-removeImg', pty.removeImage);
		$j(document).on('click', 'a.pty-addImg', pty.openImageInp);
		$j('#pty_toggleFullFrame').click(pty.toggleFullFrame);
		$j('#pty_refreshTheme').click(pty.refreshTheme);
		$j(document).on('click', '.pty_toggleVariants', pty.toggleVariants);
		$j('#pty_activateForm').submit(pty.activate);
		$j('#pty_supportForm').submit(pty.requestSupport);
		$j(document).on('mouseover', '.pty_showInfo', pty.showInfo);
		$j(document).on('click', '.pty-inf-tab', pty.showInfTab);
		$j(document).on('mouseover', '#pty_rangeShell', pty.showRangeSelect);
		$j(document).on('mouseout', '#pty_rangeShell', pty.hideRangeSelect);
		$j('#pty_rangeForm').submit(pty.rangeSubmit);
		$j('#pty_rangeStart').focus(pty.selectRangeInput);
		$j('#pty_rangeEnd').focus(pty.selectRangeInput);
		$j('.pty_quickTime').click(pty.selectQuickRange);
		$j('#pty_moreChangeInfo').click(pty.toggleChangeInfo);
		$j(document).on('click', '#pty_ignoreUpdate', pty.ignoreVersion);
		$j(document).on('click', '.pty_settingsHead', pty.toggleSettingsSection);
		$j(document)
			.on('keyup', '#pty_customCss', pty.updateCustomCss)
			.on('change', '#pty_customCss', pty.updateCustomCss);
		$j('#pty_customCss')
			.bind('paste', pty.updateCustomCss);
		$j(document)
			.on('keyup', '#pty_customFormHtml', pty.updateCustomForm)
			.on('change', '#pty_customFormHtml', pty.updateCustomForm)
			.bind('paste', pty.updateCustomForm);
		$j(document).on('change', '.pty_setPosition', pty.changePosition);
		$j(document).on('change', '#pty_animateSetting', pty.changeAnimateSetting);
		$j(window).resize(pty.resize);
		$j(document).click(pty.hideFontLists);
		//Settings
		$j('#pty_saveStyle').click(pty.saveStyle);
        $j('#pty_saveCopy').click(pty.saveCopy);
		$j('#pty_saveSettings').click(pty.saveSettings);
		$j('.pty_event_inp').bind('keyup', pty.updateEvent);
		$j('#pty_newsletterHtml')
			.bind('paste', pty.processNewsletterHtml);
		$j(document).on('keyup', '.pty_notificationAddress', pty.updateNotificationAddress);
		$j(document).on('blur', '.pty_notificationAddress', pty.updateNotificationAddress);
		$j(document).on('change', '.pty_notificationAddress', pty.updateNotificationAddress);
		$j(document)
			.on('keyup', '#pty_newsletterHtml', pty.processNewsletterHtml)
			.on('change', '#pty_newsletterHtml', pty.processNewsletterHtml);
		$j('#pty_saveNewsletter').click(pty.saveNewsletter);
		$j(document).on('change', '#pty_fade', pty.toggleFade);
		$j(document).on('change', '#pty_overlayOpacity', pty.changeOverlayOpacity);
			pty.getFonts();
		//Main Page
		if (PTY_PAGE == 'main') {
			var processing = false;
			$j.each(pty.themes, function(i, v){
				if (v !== undefined) {
					if (!processing && v.rawForm !== undefined && v.rawForm) {
						pty.theme = v;
						pty.popupid = pty.theme.popupid;
						processing = true;
						$j('body').append('<textarea id="pty_newsletterHtml"></textarea>');
						$j('#pty_newsletterHtml').val(v.rawForm);
						pty.processNewsletterHtml(1);
						$j('#pty_newsletterHtml').hide();
					}
				}
			});
			pty.initMainColumns();
			pty.initCharts();
			pty.initRange();
			pty.rangeSubmitEvent = function() {
				var popupids = [];
				$j.each(pty.themes, function(i, v) {
					if (v !== undefined) {
						popupids.push(i);
					}
				});
				popupids = popupids.join(',');
				_pty.a.send('getSetAnalytics', {popups : popupids, start: pty.rangeStart, end: pty.rangeEnd}, pty.processAnalytics);
			};
			$j('.pty_label').each(function(i, v) {
				var id = _pty.idData($j(this).attr('id'));
				var active = $j(this).hasClass('pty_activeLabel');
				if (pty.themes[id] !== undefined) {
					pty.themes[id].active = active;
				}
			});
			pty.positionItems();
		}
		if (PTY_PAGE == 'EDIT') {
			// Sample Frame
			var winheight = $j(window).height();
			$j("#pty_example")
				.load(function(){
					$j(document).on('mouseover', '.loadSampleTheme', function(){
						var $this = $j(this);
						clearTimeout(pty.showThemeTimo);
						pty.showThemeTimo = setTimeout(function(){
							pty.showTheme($this.data('theme'));
						}, 80);
					});
					// Select a sample theme
					$j(document).on('click', '.selectSampleTheme', function(){
						var theme = $j(this).data('theme');
						pty.select(theme);
						snav.next();
					});
					pty.frame = this.contentDocument;
					pty.frameWin = this.contentWindow;
					$j('head', pty.frame).append('<style id="pty_fonts" type="text/css"></style>');
					if (pty.popupWhenReady !== undefined) {
						pty.loadPopup();
					}
					else {
						$j('body', pty.frame).append(''+
							'<div id="pty_pkg"><div id="pty_overlay"></div>' +
							'	<div id="pty_create_intro">' +
							'		<img src="' + PTY_URL + '/images/guide_image.png"/>' +
							'	</div>' +
							'</div>' +
							'<style type="text/css" id="pty_smp_styles">' +
							'	#pty_overlay{z-index:100000; position:fixed; top:0; left:0; width:100%; height:100%; background:#fff; opacity:.93; filter:alpha(opacity=93);} ' +
							'	#pty_create_intro { z-index:1000001; position:fixed; top:0px; left:0px; width:742px; font-size:20pt; line-height:150%; }' +
							'	#pty_create_arrow { display:block; font-size:300pt; margin-top:75px; text-shadow:4px 0 6px #a4A1A1; }' +
							'</style>'
						);
					}
					$j.each(PTY_ALLIMAGES, function(i, v) {
						$j('body', pty.frame).append('<img src="'+v+'" style="display:none;"/>');
					});
					pty.loadFonts();
					pty.initSMsg();
					$j('#pty_formInputValue-name').labeledInput('HTML Name');
					$j('#pty_formInputValue-email').labeledInput('HTML Name');
					pty.initWysija();
					pty.initGravity();
				})
				.css('height', (winheight-218)+'px');
			$j('.slideNav-shell').css('height', (winheight-205)+'px');
			$j('#pty_themeSide').css('height', (winheight-221)+'px');
			pty.initFilters();
			$j(document).on('click', '.pty_formPartAdd', pty.addFormInps);
			$j(document).on('click', '.pty_formPartRemove', pty.removeFormInp);
			$j(document).on('click', '#pty_notificationAddresses .pty_formPartRemove', pty.updateNotificationAddress);
			$j(document).on('click', '.pty_toggleInpVis', pty.toggleInputVisibility_CLHandler);
			$j(document).on('click', '#pty_saveAndFinish', pty.saveNewsletterAndFinish);
			$j(document).on('keyup', '.pty_formInputShell .pty_formPartLabel', pty.updateCustomField);
			$j(document).on('keyup', '.pty_formInputShell .pty_formPartName', pty.updateCustomField);
			$j(document).on('click', '.pty_formInputMove', pty.moveInput);
			$j(document).on('keyup', '#pty_formInputName-name,#pty_formInputName-email', function(){
				if (snav.on.id == 4) {
					var $t = $j(this);
					var name = _pty.idData($t);
					$j('#inp-'+name).val($t.val()).keyup();
				}
			});
		}
		if (PTY_PAGE == 'ANALYTICS') {
			pty.initRange();
			setTimeout(function(){$j('#pty_rangeSelectShell').attr('style', '');}, 0);
			pty.selectRangeInput('#pty_rangeStart');
			pty.rangeSubmitEvent = function() {anl.updateChart(anl.currentType);anl.updatePopupData();};
			anl.init();
		}
		pty.updateAnalytics();
	},
	updateAnalytics: function(){
		var popupids = [];
		$j.each(pty.themes, function(i, v) {
			if (v != undefined) {
				popupids.push(i);
			}
		});
		popupids = popupids.join(',');
		var now = new Date();
		now = now.getTime()/1000;
		if (popupids.length) {
			_pty.a.send('getSetAnalytics', {popups:popupids, start:0, end:now}, pty.processAnalytics,{silent:true});
		}
	},
	processAnalytics: function(rsp){
		if (PTY_PAGE !== 'main') return;
		$j('#pty_popupList').css('display', 'table');
		$j.each(rsp.analytics, function(i, v){
			var thm = $j('#pty_theme-'+i);
			$j('.pty_impressions', thm).html(v.impressions);
			$j('.pty_conversions', thm).html(v.conversions);
			$j('.pty_cRate', thm).html(v.cRate+'%');      
		});
		if (PTY_NEWPOPUP) {
			pty.pnt_newPopup(PTY_NEWPOPUP);
		}
	},
	/*
	 * Start Charts
	 */
	initCharts: function() {
		$j.each(pty.themes, function(i, v){
			if (v != undefined) {
				var vis = $j('#summaryVis-'+i);
				pty.vis = vis
				var antcs = {};
				var convs = 0;
				v = v.a;
				if (v.conversions > 0 && _pty.count(v.daily) > 1) {
					var data = new google.visualization.DataTable();
					data.addColumn('string', 'Date');
					data.addColumn('number', 'C%');
					//data.addColumn('number', 'Imps');
					$j.each(v.daily, function(i, v){
						var crate = v.convs / v.imps * 100;
						v.date = new Date(v.date * 1000);
						data.addRows([ [v.date.toDateString(), v.convs] ]);
					});
					setTimeout(function(){
					var chart = new google.visualization.AreaChart(document.getElementById(vis.attr('id')));
					chart.draw(data, {
						width: pty.vis.width(),
						height: vis.height()+20, title: '',
						hAxis: {title: '', textStyle: {color: '#FFF'}},
						vAxis: {textStyle: {color: '#FFF'}},
						legend: 'none'
					});           
					vis.append(''+
						'<a href="'+PTY_ADM+'pty-analytics&popup='+i+'" class="pty_analyticsLink">Click for Analytics</a>'
					);
					$j('.pty_analyticsLink', vis.parent()).show();
				},1);
				}  
				else{
					vis
					.addClass('pty_not_enough_data')
					.html('Not Enough Data for Analytics');
				}
			}
		});
	},
	initMainColumns: function() {
		if (!$j('.pty_label').length) {
			return;
		}
		var mdims = $j('#pty-main-content').offset();
		var posInfBox = function(){
			var elm = $j('#pty-inf-shell');
			var dims = elm.offset();
			var diff = dims.top - mdims.top;
			if (pty.infTop === undefined) {
				elm.css('position', 'relative').css('top', '0px');
				pty.infTop = dims.top;
			}
			var scrH = $j(window).height();
			pty.infSideH = $j('#pty-main-content').height() - diff - 1;
			elm.css('height', _pty.px(pty.infSideH));
			if ($j(window).scrollTop() > pty.infTop - 12) {
				elm.css('position', 'fixed').css('top', '10px');
			}
			else {
				elm.css('position', 'relative').css('top', '0');
			}
		};
		posInfBox();
		$j(window).scroll(function(){
			posInfBox();
		});
	},
	initWysija: function() {
		$j(function(){
			$j('.pty_wysija_elm').attr('style', 'display:none !important');
			if (pty.wysija_forms !== undefined) {
				$j('#pty_toggleSettings-serviceConnectShell').click();
				$j('.pty_wysija_elm').show();
				var formHtml = '<option value="">Select a Form</option>';
				$j.each(pty.wysija_forms, function(inx, form) {
					formHtml += '<option value="'+form.form_id+'">'+form.name+'</option>';
				});
				$j('#pty_wysija_forms').html(formHtml).change(function(){

					// Setup the form object
					form = pty.wysija_forms[$j(this).val()];
					body = JSON.stringify(form.data.body);
					var cstm = false;
					if (pty.theme !== undefined && pty.theme.form !== undefined && pty.theme.form.custom_fields !== undefined) {
						cstm = pty.theme.form.custom_fields;
					}
					pty.theme.form = {};
					pty.theme.form.type = 'wysija';
					pty.theme.form.form_id = form.form_id;
					pty.theme.form.act = '#wysija';
					pty.theme.settings.ajaxSubmit = "1";
					if (cstm) {
						pty.theme.form.custom_fields = cstm;
					}
					$j('#pty_formAction').val(pty.theme.form.act);
					pty.theme.form.hidden = [
						{form_id: form.form_id}
					];
					$j('#pty_formHiddens').empty();
					$j.each(pty.theme.form.hidden, function(i, pair) {
						$j.each(pair, function(inx, val) {
							pty.addFormInps('hidden', inx, val);
						});
					});

					// Only show name input if it's part of the form
					if (body.indexOf('firstname') > -1 || body.indexOf('lastname') > -1) {
						pty.theme.form.name = 'name';
						pty.addFormInps('input', 'name', 'name');
						$j('#pty_hideInput-name').attr('checked', false);
					}
					else {
						$j('#pty_hideInput-name').attr('checked', 'checked');
					}
					pty.toggleInputVisibility('name');

					pty.theme.form.email = 'email';
					pty.addFormInps('input', 'email', 'email');
					pty.saveForm();

					// Increase step
					var data = {settings: pty.theme, popupid: pty.popupid};
					data = pty.step(data, 4);
				});

				if (pty.theme !== undefined && pty.theme.form !== undefined && pty.theme.form.form_id !== undefined && pty.theme.form.type =='wysija') {
					$j('#pty_wysija_forms').val(pty.theme.form.form_id).change();
				}
			}
		});
	},
	initGravity: function() {
		$j(function(){
			$j('.pty_gravity_elm').attr('style', 'display:none !important');
			if (pty.gravity_forms !== undefined) {
				$j('#pty_toggleSettings-serviceConnectShell').click();
				$j('.pty_gravity_elm').show();
				var pippityFormHtml = '<option value="">Select a Form</option>';
				var customFormHtml = '<option value="">Select a Form</option>';
				$j.each(pty.gravity_forms, function(inx, form) {
					if (form.type == 'pippity') {
						pippityFormHtml += '<option value="'+form.form_id+'">'+form.name+'</option>';
					}
					else {
						customFormHtml += '<option value="'+form.form_id+'">'+form.name+'</option>';
					}
				});
				var cstm = false;
				if (pty.theme !== undefined && pty.theme.form !== undefined && pty.theme.form.custom_fields !== undefined) {
					cstm = pty.theme.form.custom_fields;
				}
				$j('#pty_gravity_pippity_forms').html(pippityFormHtml).change(function(){

					// Setup the form object
					form = pty.gravity_forms[$j(this).val()];
					if (form) {
						$j('#pty_gravity_custom_forms').val('').change();
						pty.theme.form = {};
						pty.theme.form.type = 'gravity_pippity';
						pty.theme.form.form_id = form.form_id;
						pty.theme.form.act = '/';
						pty.theme.settings.ajaxSubmit = "1";
						if (cstm) {
							pty.theme.form.custom_fields = cstm;
						}
						$j('#pty_formAction').val(pty.theme.form.act);
						pty.theme.form.hidden = [
							{gform_submit: form.form_id}
						];
						var eobj = {};
						eobj['is_submit_'+form.form_id] = 1;
						pty.theme.form.hidden.push(eobj);
						$j('#pty_formHiddens').empty();
						$j.each(pty.theme.form.hidden, function(i, pair) {
							$j.each(pair, function(inx, val) {
								pty.addFormInps('hidden', inx, val);
							});
						});

						// Only show name input if it's part of the form
						if (form.name_field !== false) {
							pty.theme.form.name = 'input_'+form.data.name_field.id;
							pty.addFormInps('input', 'name', 'input_'+form.data.name_field.id);
							$j('#pty_hideInput-name').attr('checked', false);
						}
						else {
							$j('#pty_hideInput-name').attr('checked', 'checked');
						}
						pty.toggleInputVisibility('name');

						pty.theme.form.email = 'input_'+form.data.email_field.id;
						pty.addFormInps('input', 'email', 'input_'+form.data.email_field.id);
						pty.saveForm();

						// Increase step
						var data = {settings: pty.theme, popupid: pty.popupid};
						data = pty.step(data, 4);
					}
					else {
						pty.theme.form = {};
					}
				});
				$j('#pty_gravity_custom_forms').html(customFormHtml).change(function(){
					var code = $j(this).val();
					if (code) {
						var form = pty.gravity_forms[code];
						$j('#pty_gravity_pippity_forms').val('').change();
						html = '[gravityform id="'+code+'" ajax=true title=false description=false]';
						_pty.a.send('getGravityForm', {html: html}, function(rsp){
							pty.theme.form = {};
							pty.theme.form.type = 'gravity_custom';
							pty.theme.form.form_id = form.form_id;
							$j('#pty_gravity_custom_msg').show();
							$j('#pty_customFormHtml').val(rsp.form).change();
							$j('#pty_newsletterRsp').empty().append('<span>Connected to '+pty.serviceName[pty.theme.form.type]+'!</span>');
							$j('#pty_toggleSettings-advancedConnectShell').click();
						});
					}
					else {
						$j('#pty_gravity_custom_msg').hide();
						$j('#pty_customFormHtml').val('').change();
					}
				});

				if (pty.theme !== undefined && pty.theme.form !== undefined && pty.theme.form.form_id !== undefined) {
					if (pty.theme.form.type =='gravity_pippity') {
						$j('#pty_gravity_pippity_forms').val(pty.theme.form.form_id).change();
					}
					if (pty.theme.form.type =='gravity_custom') {
						$j('#pty_gravity_custom_forms').val(pty.theme.form.form_id).change();
					}
				}
			}
		});
	},

	/*
	 * Main page
	 */
	doAction: function() {
		var $this = $j(this);
		var val = $this.text().toLowerCase();
		var popupid = _pty.idData($this.closest('.pty_label').attr('id'));
		switch(val) {
			case 'activate':
			case 'deactivate':
			_pty.a.send('changeStatus', {popupid : popupid, status : val}, function(rsp) {
				var act = 'activate';
				$j('#pty_label-'+popupid).removeClass('pty_activeLabel');
				if (val == 'activate') {
					act = 'deactivate';
					$j('#pty_label-'+popupid).addClass('pty_activeLabel');
				}
				$this
				.attr('class', 'pty_action pty_'+act)
				.text(_pty.ucfirst(act));
				$j('.pty_status', $j('#pty_theme-'+popupid))
				.html((act=="activate") ? 'Inactive' : 'Active');
			});
			$this.val("none");
			break;
			case 'rename':
				var drop = $j('.pty_actionDropdown', $this.closest('.pty_label'));
				if (drop.is(':visible')) {
					drop.html('').hide();
				}
				else{                        
					drop
						.html(''
							+ '<form id="pty_renameForm">'
							+ '		<input type="text" id="pty_renameInput" value="'+$j('h4', $this.closest('.pty_label')).html()+'"/>'
							+ '		<input type="submit" class="button-secondary" value="Save"/>'
							+ '</form>'
						)
						.show();
					$j('#pty_renameForm').submit(function() {
						var label = $j('#pty_renameInput').val(); 
						if (label != '') {
							_pty.a.send('rename', {popupid : popupid, label : label}, function() {
								drop.html('').hide();
							});		
						}
						return false;
					});
					$j('#pty_renameInput').keyup(function() {
						$j('h4', $this.closest('.pty_label')).html($j(this).val());
					});
				}
			break;
			case 'complete':
			case 'edit':
				location.href = PTY_ADM+'pty-edit&popupid='+popupid;
				break;
			case 'clone':
				var orig = $j(this).closest('tr');
				var id = _pty.idData(orig);
				var origTheme = $j('#pty_theme-'+id);
				var origImg  = $j('.pty_imageTd', origTheme);
				var clone = orig.clone();  
				$j('.pty_status', clone).html('Inactive');			
				var table = $j(this).closest('table');
				var cloneOf = $j('h4', clone.closest('.pty_label')).text();
				_pty.a.send('makeClone', {popupid : popupid, cloneOf : cloneOf}, function(rsp) {    
					$j('h4', clone.closest('.pty_label')).html(cloneOf+' (Clone)');
					clone.attr('id', 'pty_label-'+rsp.id).removeClass('pty_activeLabel');
					$j('.pty_deactivate', clone)
						.html('Activate')
						.removeClass('pty_deactivate')
						.addClass('pty_activate');
					origTheme.after(clone);
					clone.after(''
						+ '<tr id="pty_theme-'+rsp.id+'" class="pty_themeRow pty_showInfo">'
						+ '		<td class="pty_imageTd">'+origImg.html()+'</td>'
						+ '		<td class="pty_numTd pty_impressions">0</td>'
						+ '		<td class="pty_numTd pty_conversions">0</td>'
						+ '		<td class="pty_numTd pty_cRate">0%</td>'
						+ '		<td></td>'
						+ '</tr>'
					);
					pty.themes[rsp.id] = eval('('+rsp.theme+')');
				});
				break;
			case 'delete':
				var drop = $j('.pty_actionDropdown', $this.closest('.pty_label'));
				if (drop.is(':visible')) {
					drop.html('').hide();
				}
				else{                        
					drop
						.html(''
							+ '<form id="pty_deleteForm">'
							+ 'Are you sure? '
							+ '<input type="submit" id="pty_doDelete" class="button-secondary" value="Yes, delete"/>'
							+ '<input type="submit" id="pty_noDelete" class="button-secondary" value="No"/>'
							+ '</form>'
						)
						.show();
					$j('#pty_doDelete').click(function() {
						_pty.a.send('delete', {popupid : popupid}, function(rsp) {
							$j('#pty_label-'+popupid).remove();
							$j('#pty_theme-'+popupid).remove(); 
							return false;
						});
						return false;
					});
					$j('#pty_noDelete').click(function() {
						drop.hide();
						return false;
					});
				}
			break;
			case 'view':
				return true;
			break;
		}
		return false;
	},
	showInfo: function() {
		if (!$j('.pty_label').length) {
			return;
		}
		var id = _pty.idData($j(this).attr('id'));
		var theme = pty.themes[id];
		var html = '';            

		// Settings Tab
		pty.infoPair('name', theme.name);
		if (theme.settings.visits != undefined) {
			pty.infoPair('visits', theme.settings.visits);
		}
		pty.infoPair('delay', theme.settings.delay+' seconds');
		pty.infoPair('trig', ((parseInt(theme.settings.trigger)) ? 'On' : 'Off'));
		pty.infoPair('expire', theme.settings.expire+ ' days');
		pty.infoPair('new_page', ((parseInt(theme.settings.trigger)) ? 'Yes' : 'No'));

		// Copy Tab
		$j.each(theme.copy, function(i, v){
			html += '<label>'+v.label+'</label>';
			var copy = pty.parseCopy(v.text);
			if (copy == undefined) {
				copy = 'Not Set';
			}
			html += '<div class="pty_popupText">'+copy+'<div class="clear"></div></div>';
		});
		$j('#pty_inf-copy').html(html);

		// Style Tab
		html = '';
		html += '<h2>Style Images</h2>';
		if (theme.styleImgs !== undefined) {
			$j.each(theme.styleImgs, function(i, v){
				html += '<label>'+_pty.unSlug(i)+' Image</label>';
				html += '<div class="pty_popupText">'+_pty.unSlug(v)+'</div>';
			});
		}
		html += '<h2>Font Color</h2>';
		if (theme.styleCopy !== undefined) {
			$j.each(theme.styleCopy, function(i, v){
				html += '<label>'+_pty.unSlug(i)+'</label>';
				html += '<div class="pty_popupText" style="background:'+v.color+'">'+v.color+'</div>';
			});
		}
		$j('#pty_inf-style').html(html);

		// Position arrow
		var row = $j('#pty_theme-'+id);
		$j('#pty_infoArrow')
			.css('top', (row.offset().top - (row.height()/2))+ 20 + 'px').show();
		
		$j('#pty_infoTabs').show();
		$j('#pty_infoStartText').hide();
	},
	infoPair: function(i, v) {
		$j('#pty_inf-'+i).html(v);
	},
	showInfTab: function() {
		var $this = $j(this);
		var id = _pty.idData($this);
		$j('.pty-inf-tab').removeClass('selected');
		$this.addClass('selected');
		$j('.pty-inf-panel').hide();
		$j('#pty_inf-'+id).show();
		return false;
	},
	ignoreVersion: function(){
		$j('#pty_updateBox').fadeTo(500, .5);
		_pty.a.send('ignoreVersion', {v: $j('#pty_newVersion').text()}, function(){
			$j('#pty_updateBox').remove();
		}, {errFnc: function(){
			$j('#pty_updateBox').fadeTo(500, 1);
		}});
	},
	positionItems: function(){
		//pty_range
		// arrow
	},
	
	/*                                 
	 * Theme Sample Display
	 */
	build : function(){
		pty.loadJs();
        pty.loadFonts();

		var popup = $j('<div id="pty_pkg" class="pty_pkg"/>')
			.hide()
			.prependTo(pty.frame.body);

		//Main HTML
		popup
			.append('<div id="pty_overlay"/>')
			.append('<div id="pty_preload"/>')
			.append('<div id="pty_popup">'+pty.theme.html+'</div>');
		if (pty.theme.button != undefined) {
			$j('#pty_submit', pty.frame).addClass(pty.theme.button+'-button');
		}
		if (PTY_AFFLINK !== "" && PTY_AFFTEXT !== "") {
			$j('#pty_afflink', pty.frame)
				.attr('href', PTY_AFFLINK)
				.attr('target', '_blank')
				.html(PTY_AFFTEXT)
				.show()
				.css('display', 'inline')
				.fadeTo(10, '.8')
				.bind('mouseover', function() { $j(this).stop().fadeTo(500, '1')})
				.bind('mouseout', function() { $j(this).stop().fadeTo(500, '.8')});
		}
		else {
			$j('#pty_afflink', pty.frame).remove();
		}
	},
	loadJs: function() {
		pty.hooks = {};
		$j('body', pty.frame).append('<script type="text/javascript">'+pty.theme.js+'</script>');
		pty.do_hook('js_loaded');
	},
	loadFonts: function() {
		var fonts = [];
		if (PTY_ALLFAMILIES != undefined) {
			$j.each(PTY_ALLFAMILIES, function(i, v){
				if (!$j('#pty_font_'+v, pty.frame).length) {
					fonts.push(v);	
				}
			});
			if (fonts.length) {
				fonts = fonts.join(',');
				pty.loadFont(fonts);
			}
		}
		var c = 0;
		$j.each(PTY_ALLFONTS, function(i, v){
			c++;
			$j('body', pty.frame).append('<div class="pty_fontload" style="position:absolute; top:-9999px; left:-9999px;" id="pty_fontload_'+c+'" style="font-family:'+v+'">How many a man has thrown up his hands at a time when a little more effort, a little more patience would have achieved success. -Elbert Hubbard</div>');
		});
		setTimeout(function(){
			$j('.pty_fontload').remove();
		}, 1500);
		pty.do_hook('fonts_loaded');
	},
	loadFont: function(font){
		var key = PTY_KEY;
		var fontUrl = 'http://pippity.com/font?fonts='+font+'&key='+key;
		$j('head', pty.frame).append('<link media="screen" id="pty_fontLink" rel="stylesheet" href="'+fontUrl+'" type="text/css"/>');
		$j('#pty_fontLink').load(function(){
			var c = 0;
			$j.each(pty.theme.families, function(i, v){
				c++;
				$j('body', pty.frame).append('<div class="pty_fontload"  style="position:absolute; top:-9999px; left:-9999px;" id="pty_fontload_'+c+'" style="font-family:'+v+'">How many a man has thrown up his hands at a time when a little more effort, a little more patience would have achieved success. -Elbert Hubbard</div>');
			});
			setTimeout(function(){
				$j('.pty_fontload').hide();
			}, 1500);
		});
	},
	showTheme: function(theme, force, noform) {
		if (pty.selected !== theme.name || (force !== undefined && force)) {
			$j('#pty_smp_styles', pty.frame).remove();
			$j('#pty_sImgStyles', pty.frame).remove();
			$j('#pty_customCssStyle', pty.frame).remove();
			$j('#pty_pkg', pty.frame).remove();
			pty.select(theme, force);
			var styles = pty.theme.css;
			$j('<style id="pty_smp_styles" type="text/css">'+styles+'</style>').prependTo(pty.frame.body);
			pty.build();
			pty.addCopy();
			if (noform === undefined) {
				pty.buildCopyForm();
			}
			pty.ready('fontjson', function(){
				pty.buildStylePage();
			});
			if (pty.theme.settings === undefined) {
				pty.theme.settings = {};
			}
			if (pty.theme.fade && pty.theme.settings.fade === undefined) {
				pty.theme.settings.fade = 1;
			}
			if (pty.theme.settings.fade !== undefined && pty.theme.settings.fade) {
				$j('#pty_fade').attr('checked', true);
			}
			if (pty.theme.status !== undefined && pty.theme.status) {
				$j('#pty_popupStatus').html(' (This Popup is Active!)').attr('class', 'pty_popupIsActive');
			}
			else {
				$j('#pty_popupStatus').html(' (This Popup is Inactive)').attr('class', 'pty_popupIsInactive');
			}
			if (pty.theme.step !== undefined && pty.theme.step && pty.theme.step < 4) {
				snav.goTo((1*pty.theme.step)+1);
			}
			if (pty.theme.startX !== undefined || pty.theme.startY !== undefined) {
				$j('#pty_animateSettingShell').show();
			}
			else {
				$j('#pty_animateSettingShell').hide();
			}
			pty.position();
			setTimeout(function(){
				pty.applyStyledText();
			}, 30);
			pty.open();
		}
	},
	applyStyledText: function(){
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
	},
	position : function(){
		var win = $j("#pty_example");
		pty.open();
		var dialogWidth = $j('#pty_popup', pty.frame).width();
		var dialogHeight = $j('#pty_popup', pty.frame).outerHeight();
		pty.close();
		var x = pty.theme.x;
		var y = pty.theme.y;
		if (pty.theme.startX !== undefined) {
			x = pty.theme.startX;
		}
		if (pty.theme.startY !== undefined) {
			y = pty.theme.startY;
		}

		// Get dimensions
		var dialogLeft = pty.getPositionDim(x, win.width(), dialogWidth, 'x');
		var dialogTop = pty.getPositionDim(y, win.height(), dialogHeight, 'y');
		if(pty.isIE6){
			dialogTop = pty.scrollY() + (win[1]/2) - (dialogHeight/2);
			$j('#pty_popup', pty.frame).css('position', 'absolute');
		}
		$j('#pty_popup', pty.frame)
			.css(dialogTop['place'], dialogTop['pos'] + 'px')
			.css(dialogLeft['place'], dialogLeft['pos'] + 'px')
			.addClass('pty_pos_y_'+dialogTop['place'])
			.addClass('pty_pos_x_'+dialogLeft['place']);
	},
	getPositionDim: function(position, winDim, boxDim, axis){
		var dim = 0;
		var place = '';
		switch(position){
			case 'center':
				dim = (winDim/2) - (boxDim/2);
				place = axis == 'x' ? 'left' : 'top'; 
				break;
			case 'left':
			case 'top':
				dim = 0;
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
	},
	open: function(){
		// Setup Overlay, it should work even if only set in CSS
		if (pty.theme.overlay == undefined) {
			pty.theme.overlay = {};
			pty.theme.overlay.background = $j('#pty_overlay', pty.frame).css('background-color');
			pty.theme.overlay.opacity = $j('#pty_overlay', pty.frame).css('opacity');
		}
		$j('#pty_overlay', pty.frame)
			.css('background-color', pty.theme.overlay.background)
			.fadeTo(0, pty.theme.overlay.opacity);

		if (pty.theme.settings.fade !== undefined && parseInt(pty.theme.settings.fade)) {
			$j('#pty_pkg #pty_overlay', pty.frame).fadeTo(0, 0);
			$j('#pty_pkg #pty_popup', pty.frame).fadeTo(0, 0);
			$j('#pty_pkg', pty.frame).show().css('display', 'block');
			if (pty.theme.noOverlay == undefined && !pty.theme.noOverlay) {
				var overlayOpacity = $j('#pty_pkg #pty_overlay', pty.frame).show().css('opacity', '');
				$j('#pty_overlay', pty.frame).animate( {opacity: .8}, 
					{ 
						queue: false,
						duration: 100,
						complete: function(){
							pty.do_hook('overlayFadedIn');
						}
					}
				)
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
		else {
			$j('#pty_pkg', pty.frame).show().css('display', 'block');
			if (pty.theme.noOverlay !== undefined && pty.theme.noOverlay) {
				$j('#pty_overlay', pty.frame).remove();
			}
			pty.do_hook('open');
		}
		pty.animate();
		return false;
	},
	animate: function(){
		var win = $j("#pty_example");
		var dialogWidth = $j('#pty_popup', pty.frame).width();
		var dialogHeight = $j('#pty_popup', pty.frame).outerHeight();
		var isAnimated = false;
		if (pty.theme.animate === undefined || pty.theme.animate) {
			var animations = {};
			if (pty.theme.startX !== undefined) {
				var dimX = pty.getPositionDim(pty.theme.x, win.width(), dialogWidth, 'x');
				animations[dimX['place']] = dimX['pos']; 
				isAnimated = true;
			}
			if (pty.theme.startY !== undefined) {
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
				})
			}
		}
	},
	close: function(){
		$j('#pty_popup').hide();
		return false;
	},                           	
	select: function(theme, force){
		if (pty.selected != theme.name || (force != undefined && force)) {
			pty.theme =  theme;
			pty.selected = pty.theme.name;
			pty.theme.html = pty.theme.html.replace(/\n/g, '').replace(/\t/g, '');
			pty.theme.css = pty.theme.css.replace(/\n/g, '').replace(/\t/g, ''); 
			$j.each(pty.theme.copy, function(i, v){
				if (pty.temp.copy[i] !== undefined) {
					pty.theme.copy[i] = pty.temp.copy[i];
				}
			});
			if (pty.temp.settings !== undefined && pty.temp.settings !== false) {
				pty.theme.settings = pty.temp.settings;
			}
		}
	},
	loadPopup: function(){
		pty.showTheme(pty.theme);
		pty.loadPopupData();
		snav.maxOn = snav.panes.length - 1;
		snav.nav();
		pty.temp.copy = pty.theme.copy;
		pty.temp.settings = pty.theme.settings;
		if (pty.theme.filters !== undefined && pty.theme.filters.length) {
			pty.getFilterOptions('posts');
			pty.getFilterOptions('cats');
			pty.getFilterOptions('types');
			pty.getFilterOptions('roles');
			pty.ready('popupFilterData-posts', function(){
				pty.ready('popupFilterData-cats', function(){
					pty.ready('popupFilterData-types', function(){
						pty.ready('popupFilterData-roles', function(){
							pty.loadFilters(pty.theme.filters);
							pty.checkPowerFilters();
						});
					});
				});
			});
		}
		if (pty.theme.events !== undefined) {
			$j.each(pty.theme.events, function(ev, act) {
				$j('#pty_event-'+ev).val(_pty.stripSlashes(act));
			});
		}
		pty.syncCustomForm();
		pty.syncFormInputs();
		pty.syncNotifies();
		pty.syncInputVisibility();
	},
	loadPopupData: function(){
		if (pty.theme.copy !== undefined) {
			$j.each(pty.theme.copy, function(i, v) {
				if (v.edited !== undefined) {
					$j('#inp-'+i).val(_pty.stripSlashes(v.text.replace(slashGex, "'")));
				}
			});
		}
		if (pty.theme.settings !== undefined) {
			$j.each(pty.theme.settings, function(i, v) {
				$j('#pty_'+i).val(_pty.stripSlashes(v));
				if (v === "1" || v === 'on') {
					$j('#pty_'+i).attr('checked', true);
				}
				else if (v === "0" || v === 'off') {
					$j('#pty_'+i).attr('checked', false);
				}
			});
			if (pty.theme.settings.trigger == '1') {
				$j('#pty_trigger').attr('checked', true);
			}
			slider.sync();
		}
		if (pty.theme.form !== undefined) {
			if (pty.theme.form.type !== undefined) {
				$j('#pty_newsletterRsp').empty().append('<span>Connected to '+pty.serviceName[pty.theme.form.type]+'</span>');
			}
		}
	},
	loadThemes: function() {
		$j.each(pty.themes, function(i, v) {
			$j("#pty_themeSide").append(pty.displayThemeSelect(v));	
		});
		$j('.variantShell').hide();
		$j.each(pty.variants, function(i, v) {
			var str = 'Show '+ ((v.length == 1) ? '1 variant' : v.length+' Variants');
			$j('<a href="#" id="pty_openVariants-'+i+'" class="pty_toggleVariants">'+str+'</a>').insertAfter('#pty_theme-'+i);
		});
	},
	displayThemeSelect: function(theme) {
		if (theme.parent != undefined) {
			if (pty.variants[theme.parent] == undefined) {
				pty.variants[theme.parent] = [];	
			}
			pty.variants[theme.parent].push(theme.file);
			html = ''
				+ ' <div class="themeShell variantShell variant-'+theme.parent+' loadSampleTheme selectSampleTheme"> '
				+ ' 	<div class="themeData">'
				+ ' 		<h4>'+theme.name+'</h4>'
				+ ' 	</div>'
				+ ' 	<div class="clear"></div> '
				+ ' </div>';
		}
		else {
			html = ''
				+ ' <div id="pty_theme-'+theme.file+'" class="themeShell loadSampleTheme selectSampleTheme"> '
				+ ' 	<img src="'+theme.imgUrl+'"/>				'
				+ ' 	<div class="themeData">'
				+ ' 		<h4>'+theme.name+'</h4>'
				+ ' 		<div class="themeBy">by '+theme.author+'</div>'
				+ ' 		<span class="themeDescr"> '+theme.descr+'</span>'
				+ ' 	</div>'
				+ ' 	<div class="clear"></div> '
				+ ' </div>';
		}
		html = $j(html).data('theme', theme); 
		return html;
	},
	toggleVariants: function() {
		var id = _pty.idData($j(this).attr('id'));
		var vs = $j('.variant-'+id);
		if (vs.first().is(':visible')) {
			vs.hide();
			$j(this).text($j(this).text().replace('Hide', 'Show'));
		}
		else{
			setTimeout(function(){$j.each(PTY_VARIMAGES[id], function(i, v) {
				$j('body', pty.frame).append('<img src="'+v+'" style="display:none;"/>');
			});}, 1);
			vs.show();
			$j(this).text($j(this).text().replace('Show', 'Hide'));
		}
		return false;
	},
	toggleSettingsSection: function(){
		var $t = $j(this);
		var id = _pty.idData($t);
		var sect = $j('#pty_'+id);
		if (sect.is(':visible')) {
			sect.hide();
			$j('.pty_settingsToggleText', $t).html('Show');
		}
		else {
			sect.show();
			$j('.pty_settingsToggleText', $t).html('Hide');
		}
		return false;
	},
	resize: function(){
		var winheight = $j(window).height();
		$j('.slideNav-shell').css('height', (winheight-205)+'px');
		$j('#pty_themeSide').css('height', (winheight-221)+'px');
		$j('#pty_example').css('height', ( ( $j('#pty_themeSide').height()  + 1 )+ 'px'));

	},

	/*
	 * Styling
	 */
	buildStylePage: function(){
		var sImgs = pty.themeMeta[pty.theme.file]['sImgs'];
		var styles = pty.themeMeta[pty.theme.file]['styles'];
		var html = '';
		var selected = '';
		pty.usedSImgLinks = [];
		if (_pty.count(styles)) {
			$j.each(styles, function(name, data){
				html += '<a href="#" id="pty_styleButton-'+name+'" class="pty_styleButton">'+_pty.unSlug(name)+'</a>';
			});
			$j('#pty_quickStyles').html(html);
			$j('#pty_quickStyles').show();
			$j('#pty_toggleSettings-quickStyles').show();
		}
		else {
			$j('#pty_quickStyles').hide();
			$j('#pty_toggleSettings-quickStyles').hide();
		}

		// Position
		var xSelectable = (pty.theme.selectX !== undefined && pty.theme.selectX.length);
		var ySelectable = (pty.theme.selectY !== undefined && pty.theme.selectY.length);
		var html = '';
		if (xSelectable || ySelectable) {
			var posSelect = function(label, letter, options){
				var out = '';
				out += '<label>'+label+'</label><select id="pty_setPosition-'+letter+'" class="pty_setPosition">';
				$j.each(options, function(i, v){
					out += '<option value="'+v+'">'+_pty.ucfirst(v)+'</option>';
				});
				out += '</select>';
				return out;
			}
			html += xSelectable ? posSelect('Horizontal', 'x', pty.theme.selectX) : '';
			html += ySelectable ? posSelect('Vertical', 'y', pty.theme.selectY) : '';
			$j('#pty_toggleSettings-positionShell').show();
			$j('#pty_positionShell').show().html(html);
			$j('#pty_setPosition-x').val(pty.theme.x);
			$j('#pty_setPosition-y').val(pty.theme.y);
		}
		else {
			$j('#pty_toggleSettings-positionShell').hide();
			$j('#pty_positionShell').hide();
		}

		// Style Images
		html = '';
		if (_pty.count(sImgs)) {
			$j.each(sImgs, function(i, v){
				var lbl = _pty.unSlug(i);
				var id = 'pty_sImg-'+i;
				var linkId = 'pty_sImg-';
				var linkLbl = '';
				var isLink = false;

				// For linking images
				if (pty.theme.sImgLinks !== undefined) {
					$j.each(pty.theme.sImgLinks, function(lindex, link){
						if (!isLink) {
							linkId = 'pty_sImg-';
							$j.each(link, function(eindex, elm){
								linkId += elm+'_';
								if (i == elm) {
									linkLbl = lindex;
									isLink = true;
								}
							});
						}
					});
				}
				if (isLink) {
					id = linkId.replace(/_$/, '');
					lbl = _pty.unSlug(linkLbl);
				}
				if (!isLink || pty.usedSImgLinks.indexOf(linkLbl) < 0) {
					html += '<label>'+lbl+' Image</label><select id="'+id+'" class="pty_sImgSelect">';
					$j.each(v, function(ii, vv) {
						var selected = '';
						if (pty.theme.styleImgs !== undefined && pty.theme.styleImgs[i] !== undefined) {
							if (vv == pty.theme.styleImgs[i]) {
								selected = 'selected';
							}
						}
						html += '<option value="'+vv+'" '+selected+'>'+_pty.unSlug(vv)+'</option>';
					});
					html += "</select>";
					if (isLink) {
						pty.usedSImgLinks.push(linkLbl);
					}
				}
			});
			$j('#pty_sImgsShell').empty().html(html);
			$j('#pty_toggleSettings-sImgsShell').show();
			pty.setSImgSels();
			pty.renderSImgs();
		}
		else {
			$j('#pty_toggleSettings-sImgsShell').hide();
			$j('#pty_sImgsShell').hide();
		}

		// Fonts
		html = '';
		var fsHtml = '';
		var fontButtons = '';
		$j.each(pty.fontList, function(i, v){
			$j.each(v.csskeys, function(i, f){
				fsHtml += '<option value="'+v.key+'%'+f+'">'+f+'</option>';
				fontButtons += '<a href="#" class="pty_fontButton" id="pty_fontButton-'+f+'"></a>';
			});
		});
		var szHtml = '';
		for (i = 8; i < 48; i++) {
			szHtml += '<option value="'+i+'px">'+i+'px</option>';
		}
		$j.each(pty.theme.copy, function(i, v){
			if (v.type != 'image') {
				var font = pty.getProp(i, 'font_family');
				var size = pty.getProp(i, 'font_size');
				if (font !== undefined) {
					var fontName = _pty.ari(font.split(','), 0);
					font = pty.fontCssToKey[fontName]+'%'+fontName;
					var fontSelect = $j('<div><select class="pty_fontSelect"></select></div>');
					var sizeSelect = $j('<div><select class="pty_fontsizeSelect"></select></div>');
					var fontView = $j(''
						+ '<div><div id="pty_fontViewShell-'+i+'" class="pty_fontViewShell">'
						+ '		<div class="pty_fontViewHead">'+fontName+'<span>▼</span></div>'
						+ '			<div class="pty_fontViewList">'
						+ '			<img src="http://pippity.com/images/font_list.png"/><div class="pty_fontButtons">'+fontButtons+'</div>'
						+ '		</div>'
						+ '</div>'
					);
					
					$j('select', fontSelect)
						.attr('id', 'pty_selectFont-'+i)
						.append(fsHtml.replace(font+'"', font+'" selected="selected"'));
					$j('select', sizeSelect)
						.attr('id', 'pty_selectFontSize-'+i)
						.append(szHtml.replace(size+'"', size+'" selected="selected"'));
					html += '<label>'+v.label+'</label>'+sizeSelect.html()+fontView.html()+' '+fontSelect.html();
				}
			}
		});
		$j('#pty_fontsShell').html(html);
		pty.changeFont();

		// Font Colors
		html = '';
		$j.each(pty.theme.copy, function(i, v){
			if (v.type != 'image') {
				var color = pty.getProp(i, 'color');
				html += '<label>'+v.label+'</label><input id="pty_color-'+i+'" type="text" value="'+color+'" class="pty_color"/>';
			}
		});
		$j('#pty_sCopyShell').html(html);

		// Overlay
		var o_color = pty.theme.overlay !== undefined && pty.theme.overlay.background !== undefined ? pty.theme.overlay.background : _pty.rgbToHex($j('#pty_overlay', pty.frame).css('background-color'));
		var o_opacity = _pty.to5($j('#pty_overlay', pty.frame).css('opacity') * 100); 
		if (pty.theme.overlay !== undefined && pty.theme.overlay.opacity !== undefined) {
			o_opacity = pty.theme.overlay.opacity * 100;
		}
		if (pty.theme.noOverlay !== undefined && pty.theme.noOverlay) {
			o_opacity = 0;
		}
		html = '';
		html += '<label>Overlay Opacity</label><select id="pty_overlayOpacity">';
		for(var o = 100; o > -1; o -= 5) {
			selected = '';
			if (o == o_opacity) {
				selected = ' selected="selected"';
			}
			html += '<option value="'+o+'"'+selected+'>'+o+'%</option>';
		}
		html += '</select>';
		html += '<label>Overlay Color</label><input id="pty_color-overlay" value="'+o_color+'" type="text" class="pty_color"/>';
		$j('#pty_toggleSettings-overlayShell').show();
		$j('#pty_overlayShell').html(html);

		// Custom CSS
		var css = '';
		if (pty.theme.customCss !== undefined) {
			css = pty.theme.customCss;
		}
		else if (pty.themeMeta[pty.theme.file].customCss !== undefined) {
			css = pty.themeMeta[pty.theme.file].customCss;
		}
		pty_color.bind();
		$j('#pty_customCss').val(css);
		pty.updateCustomCss();
	},
	changeSImg: function(){
		var $t = $j(this);
		var key = _pty.idData($t.attr('id')).split('_');
		$j('.pty_styleButton').removeClass('pty_styleSelected');
		if (pty.theme.styleImgs === undefined) {
			pty.theme.styleImgs = {};
		}
		$j.each(key, function(i, v){
			var img = $t.val();
			pty.theme.styleImgs[v] = img;
		});
		pty.renderSImgs();
	},
	renderSImgs: function(){
		$j('#pty_sImgStyles', pty.frame).remove();
		var styleTag = $j('<style  id="pty_sImgStyles" type="text/css"></style>');
		var styles = '';
		$j('.pty_sImgSelect').each(function(){
			var $t = $j(this);
			var keys = _pty.idData($t.attr('id')).split('_');
			$j.each(keys, function(i, key){
				var img = $t.val();
				var imgUrl= pty.theme.url + 'images/'+ key + '__' + img + '.png';
				$j('.pty_'+key, pty.frame).css('background-image', 'url('+imgUrl+')');
				styles += '.pty_'+key+'{ background-image: url('+imgUrl+') !important; } ';
			});
		});
		styleTag.html(styles);
		$j('body', pty.frame).append(styleTag);
	},
	setSImgSels: function(){
		if (_pty.count(pty.theme.styleImgs)) {
			$j.each(pty.theme.styleImgs, function(i, v){
				$j('#pty_sImg-'+i).val(v);
			});
		}
	},
	changePosition: function(){
		var $this = $j(this);
		var id = _pty.idData($this);
		var pos = $this.val();
		var start = 'start' + (id.toUpperCase());
		var starts = {
			top: 'above', 
			bottom: 'below',
			left: 'wideleft',
			right: 'wideright'
		};
		pty.theme[id] = pos;
		if (pty.theme[start] !== undefined) {
			pty.theme[start] = starts[pos];
		}
		pty.showTheme(pty.theme, 1);
	},
	changeAnimateSetting: function(){
		var val = $j('#pty_animateSetting').val();
		pty.theme.animate = val;
	},
	changeTextColor: function(elm){
		setTimeout(function(){
			$j('.pty_color').each(function(){
				var $t = $j(this);
				var id = _pty.idData($t.attr('id'));
				var color = $t.css('background-color');
				if (id != 'overlay') {
					$j('#pty_'+id, pty.frame).css('color', color);
					if (id == 'email') {
						$j('.pty_custom_field', pty.frame).css('color', color);
					}
					if (pty.theme.styleCopy === undefined) {
						pty.theme.styleCopy = {};
					}
					if (pty.theme.styleCopy[id] === undefined) {
						pty.theme.styleCopy[id] = {};
					}
					pty.theme.styleCopy[id].color = _pty.rgbToHex(color);
				}
				else {
					$j('#pty_'+id, pty.frame).css('background', color);
					if (pty.theme.overlay === undefined) {
						pty.theme.overlay = {};
					}
					pty.theme.overlay.background = _pty.rgbToHex(color);
					pty.changeOverlayOpacity();
				}
			});
		}, 10);
	},
	changeFont: function(){
		var fonts = [];
		$j('.pty_fontSelect').each(function(){
			var $this = $j(this);
			var id = _pty.idData($this);
			var font = $this.val().split('%');
			pty.loadFont(font[0]);
			$j('#pty_'+id, pty.frame).css('font-family', '"'+font[1]+'"');
			if (id == 'email') {
				$j('.pty_custom_field', pty.frame).css('font-family', '"'+font[1]+'"');
			}
			if (pty.theme.styleCopy === undefined) {
				pty.theme.styleCopy = {}; 
			}
			if (pty.theme.styleCopy[id] === undefined) {
				pty.theme.styleCopy[id] = {};
			}
			pty.theme.styleCopy[id].font_family = font[1];
			if (fonts.indexOf(font[0]) < 0) {
				fonts.push(font[0]);
			}
		});
		pty.theme.fonts = fonts;
	},
	changeFontSize: function(){
		$j('.pty_fontsizeSelect').each(function(){
			var $this = $j(this);
			var id = _pty.idData($this);
			var size = $this.val();
			$j('#pty_'+id, pty.frame).css('font-size', size);
			$j('#pty_'+id+' .pty_bullet', pty.frame).css('font-size', size);
			if (id == 'email') {
				$j('.pty_custom_field', pty.frame).css('font-size', size);
			}
			if (pty.theme.styleCopy === undefined) {
				pty.theme.styleCopy = {}; 
			}
			if (pty.theme.styleCopy[id] === undefined) {
				pty.theme.styleCopy[id] = {};
			}
			pty.theme.styleCopy[id].font_size = size;
		});
	},
	showFontList: function(){
		var id = _pty.idData($j(this));
		var fontlist = $j('.pty_fontViewList', $j(this));
		if (fontlist.is(':visible')) {
			pty.hideFontList(fontlist);
		}
		else {
			fontlist.show();
			$j('span', fontlist.parent()).text('x').addClass('pty_close_fontList');
		}
		pty.fontListOpening = true;
		setTimeout(function(){ pty.fontListOpening = false;}, 100);
	},
	hideFontList: function(fontlist){
		fontlist.hide();
		$j('span', fontlist.parent()).text('▼').removeClass('pty_close_fontList');
	},
	hideFontLists: function(){
		if (pty.fontListOpening !== undefined && !pty.fontListOpening) {
			$j('.pty_fontViewList').each(function(){
				pty.hideFontList($j(this));
			});
		}
	},
	selectFontFromList_Click: function(){
		var font = _pty.idData($j(this));
		var parent = $j(this).closest('.pty_fontViewShell');
		pty.selectFontFromList(parent, font);
		return false;
	},
	selectFontFromList: function(parent, font){
		var key = pty.fontCssToKey[font];
		var area = _pty.idData(parent);
		$j('#pty_selectFont-'+area).val(key+'%'+font).change();
		$j('.pty_fontViewHead', parent).html(font+'<span>▼</span>');
		$j('.pty_fontViewList', parent).hide();
	},
	changeOverlayOpacity: function(){
		var o = $j('#pty_overlayOpacity').val();
		$j('#pty_overlay', pty.frame).fadeTo(0, (o/100));
		if (pty.theme.overlay === undefined) {
			pty.theme.overlay = {};
		}
		pty.theme.overlay.opacity = o/100;
	},
	getProp: function(elm, prop){
		if (pty.theme.styleCopy !== undefined && pty.theme.styleCopy[elm] !== undefined && pty.theme.styleCopy[elm][prop] !== undefined) {
			return pty.theme.styleCopy[elm][prop];
		}
		else {
			if ($j('#pty_'+elm, pty.frame).length) {
				prop = prop.replace('_', '-');
				var val = '';
				if (prop == 'color') {
					val = _pty.rgbToHex($j('#pty_'+elm, pty.frame).css('color'));	
				}
				else {
					val = $j('#pty_'+elm, pty.frame).css(prop);		
				}
				if (val !== undefined && val) {
					return val.replace(/"/g, ''); 
				}
			}
		}
	},
	setCopyInps: function(){
		$j.each(pty.theme.styleCopy, function(i, v){
			document.getElementById('pty_color-'+i).color.fromString(v.color);
		});
	},
	selectStyle: function(){
		var $t = $j(this);
		var id = _pty.idData($t.attr('id'), {combine: true});
		var style = pty.themeMeta[pty.theme.file].styles[id];
		$j('.pty_styleButton').removeClass('pty_styleSelected');
		$t.addClass('pty_styleSelected');
		$j('#pty_customCss').val(_pty.stripSlashes(style.customCss));
		pty.theme.styleImgs = style.styleImgs;
		pty.theme.styleCopy = style.styleCopy;
		pty.setSImgSels();
		pty.renderSImgs();
		pty.setCopyInps();
		setTimeout(function(){pty.updateCustomCss();}, 30);
	},
	updateCustomCss: function(){
		$j('#pty_customCssStyle', pty.frame).remove();
		var css = $j('#pty_customCss').val(); 
		var styleTag = $j('<style  id="pty_customCssStyle" type="text/css"></style>');
		styleTag.html(css);
		$j('body', pty.frame).append(styleTag);
		pty.theme.customCss = css;
	},
	updateCustomForm: function() {
		var html = _pty.stripSlashes($j('#pty_customFormHtml').val());
		var form = $j('#pty_form', pty.frame);
		var customForm = $j('#pty_customFormShell', pty.frame);
		if (customForm.length) {
			customForm.remove();
		}
		if (html.trim().length > 0) {
			html = $j('<div/>').attr('id', 'pty_customFormShell').html(html);
			pty.theme.customForm = html.html();
			form.before(html);
			form.hide();
		}
		else {
			form.show();
			pty.theme.customForm = '';
		}
	},
	syncCustomForm: function() {
		var html = '';
		if (pty.theme.customForm !== undefined) {
			html = pty.theme.customForm;
		}
		$j('#pty_customFormHtml').val(_pty.stripSlashes(html)).change()
	},
	buildCustomFields: function() {
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
	},
	saveStyle: function() {
		var data = {settings : pty.theme};
		data = pty.step(data, 1);
		if (pty.popupid !== undefined) {
			data.popupid = pty.popupid;
		};
		_pty.a.send('updPopup', data, function(rsp) {
			pty.popupid = rsp.popupid;
			snav.next();	
		});  
	},
	getFonts: function(){
		if (pty.fontList === undefined) {
			$j.getJSON('http://pippity.com/fonts/json?callback=?', {}, function(rsp){
				pty.fontList = rsp;
				pty.fontCssToKey = {};
				$j.each(pty.fontList, function(i, v){
					$j.each(v.csskeys, function(i, c){
						pty.fontCssToKey[c] = v.key;
					});
				});
				pty.isReady('fontjson');
			});
		}
	},
	
	/*
	 * Copy Handling
	 */
	buildCopyForm: function() {
		if (pty.theme.copy.name == undefined) {
			pty.theme.copy.name = {};
			pty.theme.copy.name.text = 'Name';
			pty.theme.copy.name.label = 'Name Input Label';
			pty.theme.copy.name.type = 'field';
		}
		if (pty.theme.copy.email == undefined) {
			pty.theme.copy.email = {};
			pty.theme.copy.email.label = 'E-Mail Input Label';
			pty.theme.copy.email.text = 'E-Mail';
			pty.theme.copy.email.type = 'field';
		}
		if (pty.theme.copy.submit == undefined) {
			pty.theme.copy.submit = {};
			pty.theme.copy.submit.text = 'Sign-up!';
			pty.theme.copy.submit.type = 'submit';
			pty.theme.copy.submit.label = 'Submit Button Label';   
		}
		if (pty.theme.imageSpot != undefined) {
			pty.theme.copy.imageSpot.label = 'Image URL';	
			pty.theme.copy.imageSpot.text = '';
			pty.theme.copy.imageSpot.type = 'image';
		}  
		$j('#pty_copyForm').empty();
		$j.each(pty.theme.copy, function(i, v){
			var inp = '<input type="text" class="inp-text inp-text-change" name="inp-'+i+'" id="inp-'+i+'"/>';
			if(v.type == 'text' || v.type == 'html'){
				inp = ''+
					'<div class="pty_tooltip pty_ttip_fade"> <div class="pty_ttContent"> <p>Any line that starts with a * will be a bulleted list. </p><p>You can use HTML here. </p> </div>'+
					'<textarea type="text" class="inp-text inp-text-change" name="inp-'+i+'" id="inp-'+i+'"></textarea>'+
					'</div>';
				if (v.image != undefined) {
					var img = v.image != true ? v.image : '';
					inp += '<div class="pty-addImg'+(img ? '' : ' hide')+'" id="pty_addImgShell-'+i+'"><label>URL to Image</label><a href="' + PTY_ADM.replace('admin.php?page=', '')+'media-new.php'+'" target="_blank">Upload via WordPress</a><input id="pty_imageInp-'+i+'" type="text" class="inp-text inp-image-url" value="'+img+'"/></div>';
				  	inp += '<a href="#" id="pty_addImg-'+i+'" class="pty-addImg'+(img ? ' hide' : '')+'">Add Image</a>';
				}
			}
			if (v.type == 'image') {
				var recSize = v.size != undefined ? ' <span class="pty_recImgSize">Recommended Size: '+v.size+'</span>' : '';
				inp = ''+
					'<div class="pty_imageInputShell"><a class="pty_wpMedia" href="'+PTY_ADM.replace('admin.php?page=', 'media-new.php')+'" target="_blank">Upload via WordPress</a>' +
					' <div class="pty_tooltip" style="margin-bottom:-12px; height:70px"><div class="pty_ttContent">' +
					'	<p>Paste the URL to an image here. You can easily upload via WordPress\'s Media Manager. Click the X to have no image.</p></div>' +
					'	<a href="#" class="pty-inp-removeImg">X</a> ' +
				    '	<input type="text" class="inp-text inp-imageType-url pty_img_inp" id="pty_addImg-'+i+'" value="'+v.src+'"/>' +
						recSize +
					'</div></div>';
			}
			if (v.type == 'field' || v.type == 'submit') {
				inp = '<input type="text" class="inp-text pty_label_inp" name="inp-'+i+'" id="inp-'+i+'"/>';
			}
			$j('#pty_copyForm').append(''
				+ '<label for="inp-'+i+'">'+v.label+'</label>'
				+ inp
		);
		});
		$j('.pty_label_inp').keyup(function() {
			var id = _pty.idData($j(this).attr('id'));
			$j('#pty_'+id, pty.frame).val(_pty.stripSlashes($j(this).val()).replace(slashGex, "'"));
			pty.theme.copy[id].text = _pty.stripSlashes($j(this).val().replace(slashGex, "'"));
			if (snav.on.id == 2) {
				$j('#pty_formInputName-'+id).val(_pty.stripSlashes($j(this).val().replace(slashGex, "'")));
			}
		});
		$j('#inp-name').val(_pty.stripSlashes(pty.theme.copy.name.text).replace(slashGex, "'"));
		$j('#inp-email').val(_pty.stripSlashes(pty.theme.copy.email.text).replace(slashGex, "'"));
		$j('#inp-submit').val(_pty.stripSlashes(pty.theme.copy.submit.text).replace(slashGex, "'"));
	},
	copyChange: function() {
		$this = $j(this);
		var id = _pty.idData($this.attr('id'));
		var copy = $this.val();
		if (pty.theme.copy[id].type == 'html') {
			copy = pty.parseCopy(copy);
		}
		if (typeof pty.theme.copy[id].image == 'string') {
			copy = '<img src="'+pty.theme.copy[id].image+'" id="pty_image-'+id+'" class="pty_image"/>' + copy;	
		}
		$j('#pty_'+id, pty.frame).html(copy);
		pty.theme.copy[id].text = $this.val();
		pty.theme.copy[id].edited = true;
		pty.temp.copy = pty.theme.copy;
	},
	addCopy: function() {  
		$j.each(pty.theme.copy, function(i, v){
			if (v.type == 'image') {
				if (v.src.length) {
					$j('#pty_'+i+'Shell', pty.frame).append('<img src="'+v.src+'" id="pty_'+i+'"/>');	
				}
			}
			else if (v.type != 'submit' && v.type != 'field'){
				var copy = v.text != undefined ? v.text : '';
				if (v.type == 'html') {
					copy = pty.parseCopy(_pty.stripSlashes(copy).replace(slashGex, "'"));
				}
				if (typeof v.image == 'string') {
					copy = '<img src="'+v.image+'" id="pty_image-'+i+'" class="pty_image"/>' + copy;	
				} 
				$j('#pty_'+i, pty.frame).html(_pty.stripSlashes(copy).replace(slashGex, "'")); 
			}
			else if (v.type == 'field' || v.type == 'submit') {
				if (i == 'name' || i == 'email') {
					$j('#pty_formInputName-'+i).val(_pty.stripSlashes(v.text.replace(slashGex, "'")));
				}
				$j('#pty_'+i, pty.frame).val(_pty.stripSlashes(v.text).replace(slashGex, "'"));
			}
		});
		pty.do_hook('copy_added');
	},
	saveCopy: function() {
		var data = {settings : pty.theme};
		data = pty.step(data, 2);
		if (pty.popupid !== undefined) {
			data.popupid = pty.popupid;
		};
		_pty.a.send('updPopup', data, function(rsp) {
			pty.popupid = rsp.popupid;
			snav.next();	
		});  
	},
	parseCopy: function(copy) {
		if (copy != undefined && copy.length) {
		var lines = copy.split('\n');
		var output = '';
		var inList = false;
		$j.each(lines, function(i, v){
			var isBullet = false;
			if (v.indexOf('*') == 0) {
				isBullet = true;
			}
			if (!inList && isBullet) {
				output += "<ul>";
				inList = true;
			}
			if (isBullet) {
				output += '<li class="pty_bullet">'+_pty.trim(v.replace('*', ''))+'</li>';
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
		}
	},

	/*
	 * Image Handling
	 */
	changeImageInp: function() {
		var id = _pty.idData($j(this).attr('id'));	
		var imgUrl = $j(this).val();
		var testImg = $j('<img/>')
		.attr('src', imgUrl)
		.hide()
		.load(function() {
			pty.theme.copy[id].image = imgUrl;
			var copy = '<img src="'+imgUrl+'" id="pty_image-'+id+'" class="pty_image"/>' + pty.parseCopy(pty.theme.copy[id].text);
			$j('#pty_'+id, pty.frame).html(copy);
		})
		.bind('error', function() {
			pty.theme.copy[id].image = true;
			$j('#pty_'+id, pty.frame).html(pty.parseCopy(pty.theme.copy[id].text));
		});
		$j('body').append(testImg);
	}, 
	changeImageTypeInp: function(id) {
		if (typeof id != 'string') {
			id = _pty.idData($j(this).attr('id'));
		}
		var imgUrl = $j('#pty_addImg-'+id).val();
		if (imgUrl.length) {
			var testImg = $j('<img/>')
			.attr('src', imgUrl)
			.hide()
			.load(function() {
				if (pty.theme.copy[id].src != imgUrl) {
					pty.theme.copy[id].src = imgUrl;
					pty.showTheme(pty.theme, true, true);
				}
			})
			.bind('error', function() {
			});
			$j('body').append(testImg);
		}
		else {
			pty.theme.copy[id].src = '';
			pty.showTheme(pty.theme, true, true);
		}
	},
	blurImageInp: function() {
		var id = _pty.idData($j(this).attr('id'));
		if ($j(this).val() == '') {
			$j('#pty_addImgShell-'+id).hide();
			$j('#pty_addImg-'+id).removeClass('hide').show();
		}
	},
	openImageInp: function() {
		var id = _pty.idData($j(this).attr('id'));
		$j('#pty_addImg-'+id).hide();
		$j('#pty_addImgShell-'+id).removeClass('hide').show(); 
	},
	removeImage: function() {
		var img = $j('.inp-imageType-url', $j(this).parent())
		img.val('');
		var id =  _pty.idData(img.attr('id'));
		pty.changeImageTypeInp(id);
	},

	/*
	 * Popup Settings
	 */
	saveSettings: function() {
		var form =  _pty.formToJson('pty_settingsForm');
		pty.theme.settings = form;
		$j.each(pty.theme.settings, function(i, v){
			if (v == 'on') {
				pty.theme.settings[i] = 1;
			}
			else if (v == 'off') {
				pty.theme.settings[i] = 0;
			}
		});
		pty.theme.settings.customCookie = pty.theme.settings.customCookie.length ? pty.theme.settings.customCookie : 'visited';
		pty.theme.settings.priority = pty.theme.settings.priority !== undefined && pty.theme.settings.priority.length ? pty.theme.settings.priority : 0;
		var data = {settings: pty.theme};
		data = pty.step(data, 3);
		data.popupid = pty.popupid;
		_pty.a.send('updPopup', data, function() {
			snav.next();
		});
		pty.temp.settings = pty.theme.settings;
	},
	toggleFade: function(){
		pty.theme.settings.fade = $j('#pty_fade').is(':checked') ? true : false;
	},	
	updateEvent: function() {
		var inp = $j(this);
		var ev = inp.attr('name');
		if (pty.theme.events === undefined) {
			pty.theme.events = {};
		}
		pty.theme.events[ev] = inp.val();
	},

	/*
	 * Filters
	 */
	filterOptions: [],
	filterTypeStr: {loggedin: 'User Status', post: 'Post', cat: 'In Category', referred: 'Referred By', pagetype: 'Pagetype', url: 'URL', type:'Custom Post Type', roles: 'Role'},
	initFilters: function(){
		$j(document).on('change', '.pty_basicFilterToggle', pty.basicFilters);
		$j(document).on('click', '#pty_toggleSettings-powerFiltersShell', pty.showFilterBox);
		$j(document).on('click', '#pty_closeFilters', pty.closeFilterBox);
		$j(document).on('click', '#pty_filterDone', pty.closeFilterBox);
		$j(document).on('click', '#pty_addAndFilter-and', pty.showAddFilterView_CHandler);
		$j(document).on('click', '.pty_addAndFilter', pty.showAddFilterView_CHandler);
		$j(document).on('click', '.pty_addOrFilter', pty.showAddFilterView_CHandler);
		$j(document).on('click', '#pty_cancelAddFilter', pty.showFilterView);
		$j(document).on('click', '#pty_doAddFilter', pty.doAddFilter);
		$j(document).on('change', '#pty_filtersType', pty.changeFilterType);
		$j(document).on('click', '.pty_EditFilter', pty.editFilter);
		$j(document).on('click', '.pty_RemoveFilter', pty.removeFilter);
		$j(document).on('click', '.pty_filterOption', pty.filterOptionSelect_CHandler);
		$j(document)
			.on('keyup', '#pty_filterMatchVal[type="text"]', pty.filterOptionSelect_KHandler)
			.on('paste', '#pty_filterMatchVal[type="text"]', pty.filterOptionSelect_KHandler)
			.on('change', '#pty_filterMatchVal[type="text"]', pty.filterOptionSelect_KHandler);
	},
	initFilterSelect: function(options){
		var html = '<ul id="pty_filterOptions"></ul>';
		$j('#pty_filterOptionsShell').append(html).append('<input type="hidden" id="pty_filterMatchStr" name="filterMatchStr"/>');
		if (!$j('#pty_filterMatchVal').length) {
			$j('#pty_filterOptionsShell').append('<input type="hidden" id="pty_filterMatchVal" name="filterMatch"/>');
		}
		$j.each(options, function(i, o) {
			$j('#pty_filterOptions').append('<li><a href="#" class="pty_filterOption" id="pty_filterOption-'+o.value+'">'+o.label+'</a></li>');
		});
	},
	showFilterBox: function(){
		pty.getFilterOptions('posts');
		pty.getFilterOptions('cats');
		pty.getFilterOptions('types');
		pty.getFilterOptions('roles');
		var w = $j('body').width();
		var pkg = $j('#pty_filtersPkg');
		var shell = $j('#pty_filtersShell');
		pkg.show();
		shell.css('left', (( w / 2 ) - ( shell.width() / 2 )) + 'px');
		$j('.pty_settingsToggleText', '#pty_toggleSettings-powerFiltersShell').html('Show');
	},
	closeFilterBox: function(){
		$j('#pty_filtersPkg').hide();
	},
	getFilterOptions: function(type){
		if (pty.filterOptions[type] === undefined) {
			_pty.a.send('getFilterOptionData', {type: type}, function(rsp){
				pty.filterOptions[type] = rsp.options;
				pty.initFilterSelect(pty.filterOptions[type]);
				pty.isReady('popupFilterData-'+type);
			}, {silent:true});
			return [];
		}
		return pty.filterOptions[type];
	},
	filterOptionSelect_CHandler: function(){
		var value = _pty.idData($j(this));
		var str = $j(this).text();
		pty.filterOptionSelect(value, str);
	},
	filterOptionSelect_KHandler: function(){
		var value = $j(this).val();
		pty.filterOptionSelect(value, value);
	},
	filterOptionSelect: function(value, str){
		str = str !== undefined && str ? str : value;
		$j('#pty_filterError').hide();
		$j('.pty_filterOption').removeClass('pty_optionSelected');
		if($j('.pty_filterOption').length) {
			$j('#pty_filterOption-'+value).addClass('pty_optionSelected');
		}
		$j('#pty_filterMatchVal').val(value);
		$j('#pty_filterMatchStr').val(str);
	},
	showAddFilterView_CHandler: function(){
		pty.showAddFilterView($j(this));
	},
	showAddFilterView: function(elm){
		pty.filterParent = elm.closest('.pty_aFilter');
		pty.filterAndOr = _pty.idData(elm);
		pty.firstFilter = false;
		if (!pty.filterParent.length) {
			pty.firstFilter = true;
			pty.filterParent = $j('#pty_filters');
		}
		if (!pty.filterEditing) {
			$j('#pty_doAddFilter').html('Add Filter');
			$j('#pty_filtersIsSelect').val(1);
			$j('#pty_filtersType').val("");
			$j('#pty_addFilterSecondary').hide();
		}
		else {
			$j('#pty_doAddFilter').html('Save Edit');
		}
		$j('.pty_filterView').hide();
		$j('#pty_filtersView-add').show();
	},
	showFilterView: function(){
		pty.filterEditing = false;
		pty.filterParent = false;
		$j('.pty_filterView').hide();
		$j('#pty_filtersView-filters').show();
		if ($j('.pty_aFilter').length) {
			$j('#pty_addAndFilter-and').hide();
			$j('#pty_filterDone').show().css('display', 'block');
		}
		else {
			$j('#pty_addAndFilter-and').show();
			$j('#pty_filterDone').hide();
		}
	},
	changeFilterType: function(){
		var val = $j(this).val();
		var html = false;
		var options = [];
		$j('#pty_filterError').hide();
		switch(val) {
			case 'loggedin':
				html = 'Filter popup based on the current logged in status of the user: <div id="pty_filterOptionsShell"></div>';
				options.push({value: 0, label: 'Logged Out'});
				options.push({value: 1, label: 'Logged In'});
				options.push({value: 2, label: 'Logged Out or In'});
			break;
			case 'pagetype':
				html = 'Filter by page type: <div id="pty_filterOptionsShell"></div>';
				options.push({value: 0, label: 'Post'});
				options.push({value: 1, label: 'Page'});
				options.push({value: 2, label: 'Home Page'});
				options.push({value: 3, label: 'Search/Archive Page'});
			break;
			case 'post':
				html = 'Filter popup by specific post: <div id="pty_filterOptionsShell"></div>';
				options = pty.getFilterOptions('posts');
			break;
			case 'cat':
				html = 'Filter popup by category<div id="pty_filterOptionsShell"></div>';
				options = pty.getFilterOptions('cats');
			break;
			case 'referred': 
				html = 'Filter popup by how your reader got to the site: '
					+ '<input type="text" id="pty_filterMatchVal" name="filterMatch"/>'
					+' <h4>Quick Options</h4><div id="pty_filterOptionsShell"></div>';
				options.push({value: 'google.com', label: 'Google'});
				options.push({value: 'yahoo.com', label: 'Yahoo'});
				options.push({value: 'bing.com', label: 'Bing'});
				options.push({value: 'stumbleupon.com', label: 'StumbleUpon'});
				options.push({value: 'delicious.com', label: 'Delicious'});
				options.push({value: 'digg.com', label: 'Digg'});
				options.push({value: 'reddit.com', label: 'Reddit'});
			break;
			case 'url':
				html = 'Filter popup by URI (everything after the domain name). You can use * as a wildcard: '
					+ '<input type="text" id="pty_filterMatchVal" name="filterMatch">'
					+ '<input type="checkbox" name="matchExactly" id="pty_matchExactly"/> Match Exactly<div id="pty_filterOptionsShell"></div>';
			break;
			case 'type':
				html = 'Filter popup by custom post-type<div id="pty_filterOptionsShell"></div>';
				options = pty.getFilterOptions('types');
			break;
			case 'roles':
				html = 'Filter popup by user roles<div id="pty_filterOptionsShell"></div>';
				options = pty.getFilterOptions('roles');
			break;
		}
		if (html) {
			$j('#pty_addFilterSecondary').html(html).show();
			if (val == 'referred') {
				$j('#pty_filterMatchVal').labeledInput('Referring URL Contains');
			}
		}
		if (options.length || val == 'url') {
			pty.initFilterSelect(options);
		}
	},
	doAddFilter: function(save, parent, andor, power){
		parent = parent === undefined ? pty.filterParent : parent;
		andor = andor === undefined ? pty.filterAndOr : andor;
		save = save === undefined ? false : save;
		pty.theme.usingPowerFilters = false;
		if (power === undefined || (power !== undefined && power)) {
			pty.theme.usingPowerFilters = true;
		}
		var is = $j('#pty_filtersIsSelect').val();
		var type = $j('#pty_filtersType').val();
		var matchVal = $j('#pty_filterMatchVal').val();
		var matchStr = $j('#pty_filterMatchStr').val();
		var is_str = parseInt(is) ? 'Is' : 'Isn\'t';
		var and = andor == 'and' ? 1 : 0;
		var andOrOut = pty.firstFilter ? '' : ( and ? 'A<br/>n</br>d' : 'O<br/>r' );
		var typeStr = _pty.subText((pty.filterTypeStr[type]+': '+matchStr).trim(), 45);
		var filter = '';
		$j('#pty_filterError').hide();
		if (type === "") {
			return pty.filterError("type");
		}
		if (matchVal == "") {
			return pty.filterError("match");
		}
		if (type == 'url') {
			v = '/'+_pty.phptrim(matchVal, '/').replace('*', '(.+)');
			if ($j('#pty_matchExactly').is(':checked')) {
				v = '^'+v+'$';
			}
			matchVal = v;
		}
		if (!pty.filterEditing) {
			var r = Math.floor(Math.random()*44444);
		
			filter += ''
				+ '<div id="pty_aFilter-'+r+'" class="pty_aFilter">'
				+ '		<span class="pty_andOrSide">'+ andOrOut + '</span>'
				+ '		<span class="pty_aFilterIs">'+is_str+'</span><span class="pty_aFilterType">'+typeStr+'</span>'
				+ '		<div class="pty_filter-is pty_filterData">'+is+'</div>'
				+ '		<div class="pty_filter-type pty_filterData">'+type+'</div>'
				+ '		<div class="pty_filter-matchValue pty_filterData">'+matchVal+'</div>'
				+ '		<div class="pty_filter-matchStr pty_filterData">'+matchStr+'</div>'
				+ '		<div class="pty_filter-andor pty_filterData">'+andor+'</div>'
				+ 		pty.filterButtonsHtml()
				+ '</div>';
			if (andor == 'and') {
				$j(parent).append($j(filter));
			}
			else if (andor == 'or') {
				$j(parent).after($j(filter));
			}
		}
		else {
			$j('.pty_aFilterIs', pty.filterEditing).html(is_str);
			$j('.pty_aFilterType', pty.filterEditing).html(typeStr);
			$j('.pty_filter-is', pty.filterEditing).html(is);
			$j('.pty_filter-type', pty.filterEditing).html(type);
			$j('.pty_filter-matchValue', pty.filterEditing).html(matchVal);
			$j('.pty_filter-matchStr', pty.filterEditing).html(matchStr);
			$j('.pty_filter-andor', pty.filterEditing).html(andor);
			filter = pty.filterEditing;
		}
		pty.showFilterView();
		if (save) {
			pty.saveFilters();
		}
		return $j('#'+$j(filter).attr('id'));
	},
	filterButtonsHtml: function(){
		var r=Math.floor(Math.random()*44444);
		return '<div class="pty_filterInnerButtonShell"><a href="#" id="pty_andFilter_'+r+'-and" class="pty_addAndFilter pty_filterInnerButton">And</a><a href="#" id="pty_andFilter_'+r+'-or" class="pty_addOrFilter pty_filterInnerButton">Or</a><a href="#" id="pty_editFilter_'+r+'-rem" class="pty_EditFilter pty_filterInnerButton">Edit</a><a href="#" id="pty_remFilter_'+r+'-rem" class="pty_RemoveFilter pty_filterInnerButton">X</a></div>'
	},
	editFilter: function(){
		pty.filterEditing = $j(this).closest('.pty_aFilter');
		var e = pty.filterEditing;
		var type = $j('.pty_filter-type', e).text();
		$j('#pty_filtersIsSelect').val($j('.pty_filter-is', e).text());
		$j('#pty_filtersType').val(type).change();
		var oval = val = $j('.pty_filter-matchValue', e).text();
		var str = $j('.pty_filter-matchStr', e).text();
		if (type == 'url') {
			val = str;
		}
		pty.filterOptionSelect(val, str);
		pty.showAddFilterView($j(this));
		if (type == 'url' && oval.indexOf('^') === 0 && oval.indexOf('$') === (oval.length - 1)) {
			$j('#pty_matchExactly').attr('checked', 'checked');
		}
	},
	removeFilter: function(){
		$j(this).closest('.pty_aFilter').remove();
		pty.saveFilters(true);
		pty.showFilterView();
	},
	filtersToJSON: function(elm){
		if (elm === undefined) {
			elm = $j('#pty_filters');
		}
		var filters = [];
		$j('#'+elm.attr('id')+' > .pty_aFilter').each(function(){
			var $f = $j(this);
			var c = '.pty_filter-';
			
			var filter = {
				is: $j(c+'is', $f).first().text(),
				type: $j(c+'type', $f).first().text(),
				matchVal: $j(c+'matchValue', $f).first().text(),
				matchStr: $j(c+'matchStr', $f).first().text(),
				andor: $j(c+'andor', $f).first().text(),
				and: []
			};
			var and = pty.filtersToJSON($f);
			$j.each(and, function(i, r){
				filter.and.push(r);
			});
			filters.push(filter);
		});
		return filters;
	},
	saveFilters: function(silent){
		silent = silent === undefined ? false : silent;
		pty.theme.filters = pty.filtersToJSON();
		_pty.a.send('saveFilters', {popupid: pty.popupid, filters: pty.theme.filters}, function(){
		}, {silent: silent});
		pty.checkPowerFilters();
	},
	loadFilters: function(filters, parent, power){
		power = power === undefined ? true : power;
		parent = parent === undefined ? $j('#pty_filters') : parent;
		var andor = 'and';
		$j.each(filters, function(i, f){
			$j('#pty_filtersIsSelect').val(f.isVal);
			$j('#pty_filtersType').val(f.type).change();
			pty.filterOptionSelect(f.matchVal, f.matchStr);
			var fParent = pty.doAddFilter(false, parent, andor, power);
			if (f.and !== undefined && f.and.length) {
				pty.loadFilters(f.and, fParent, power);
			}
			parent = fParent;
			andor = 'or';
		});
	},
	checkPowerFilters: function(){
		if (!$j('.pty_aFilter').length) {	
			pty.theme.usingPowerFilters = false;
			$j('#pty_basicFiltersOff').hide();
			$j('#pty_basicFiltersOn').show();
		}
		if (pty.theme.usingPowerFilters) {
			$j('#pty_basicFiltersOn').hide();
			$j('#pty_basicFiltersOff').show();
		}	
	},
	basicFilters: function(){
		var filter = [];
		var f1 = false;
		var f2 = false;
		$j('#pty_filters').empty();
		if (parseInt($j('#pty_loggedOutOnly').val())) {
			f1 = {
				type: 'loggedin',
				matchVal: 0,
				matchStr: 'Logged Out',
				isVal: 1,
				and: []
			};
		}
		if (parseInt($j('#pty_postOnly').val())) {
			f2 = {
				type: 'pagetype',
				matchVal: 2,
				isVal: 0,
				matchStr: 'Page',
				and: [
					{
						type: 'pagetype', 
						matchVal: 3,
						matchStr: 'Home',
						isVal: 0,
						and: [
							{
								type: 'pagetype',
								matchVal: 4,
								matchStr: 'Search/Archive',
								isVal: 0,
								and: []
							}
						]
					}
				]
			};
			if (f1) {
				f1.and.push(f2);
			}
			else {
				f1 = f2;
			}
		}
		if (f1) {
			filter.push(f1);
			pty.loadFilters(filter, undefined, false);
		}
		pty.saveFilters();
	},
	filterError: function(type){
		var errors = {
			type: "You need to select a type.",
			match: "Select an option to filter by."
		};
		$j('#pty_filterError').html(errors[type]).show().css('display', 'block');
		return false;
	},

	/*    
	 * Process Newsletter HTML
	 */
	processNewsletterHtml: function(go) {
		if(typeof go == 'object'){
			setTimeout(function() {pty.processNewsletterHtml(1);}, 200);
			return true;
		}
		pty.theme.form = {};
		var val = $j('#pty_newsletterHtml').val();
		if (pty.isTribulantShortcode(val)) {
			pty.loadTribulantForm(val);
			pty.theme.form.type = 'tribulant';
			$j('#pty_newsletterRsp').empty().append('<span>Connecting to '+pty.serviceName[pty.theme.form.type]+'!</span>');
		}
		else {
			var html = $j('<div>'+val+'</div>').attr('id', 'pty_temp').hide();
			var form = $j('form', html);
			var act = form.attr('action');
			if (act === undefined) {
				return false;
			}
			pty.theme.form.action = act;
			$j('#pty_formAction').val(act);
			pty.theme.form.hidden = [];

			//
			// Aweber
			// 
			if(act.indexOf('aweber.com') > 0){
				pty.theme.form.type = 'aweber';
				pty.addFormInps('input', 'name', 'name');
				pty.addFormInps('input', 'email', 'email');
				$j('img', html).each(function(){
					if ($j(this).attr('src').indexOf('displays.htm') > 0) {
						pty.theme.form.stats = $j(this).attr('src');
					}
				});
			}
			//
			// Constant Contact
			// 
			else if (act.indexOf('constantcontact.com') > 0) {
				pty.theme.form.type = 'constcont';
				pty.theme.form.name = 'name';
				pty.theme.form.email = 'ea';
				pty.addFormInps('input', 'name', 'name');
				pty.addFormInps('input', 'email', 'ea');
			}
			//
			// iContact
			//
			else if (act.indexOf('.icontact.com') > -1) {
				pty.theme.form.type = 'icontact';
				pty.theme.form.name = 'fields_fname';
				pty.theme.form.email = 'fields_email';
				pty.addFormInps('input', 'name', 'fields_fname');
				pty.addFormInps('input', 'email', 'fields_email');
			}
			//
			// MailChimp
			//                 
			else if(act.indexOf('.list-manage') > -1){
				pty.theme.form.type = 'mailchimp';
				if ($j('input[name="NAME"]', html).length) {
					pty.theme.form.name = 'NAME';
					pty.addFormInps('input', 'name', 'NAME');
				}
				else if ($j('input[name="FNAME"]', html).length) {
					pty.theme.form.name = 'FNAME';
					pty.addFormInps('input', 'name', 'FNAME');
				}
				pty.theme.form.email = 'EMAIL';
				pty.addFormInps('input', 'email', 'EMAIL');
			}
			//
			// MadMimi
			//
			else if(act.indexOf('madmimi.com') > -1){
				pty.theme.form.type = 'madmimi';
				pty.theme.form.name = 'signup[name]';
				pty.theme.form.email = 'signup[email]';
				pty.addFormInps('input', 'name', 'signup[name]');
				pty.addFormInps('input', 'email', 'signup[email]');
			}

			//
			// 1ShoppingCart
			//
			else if (act.indexOf('mcssl.com') > -1) {
				pty.theme.form.type = '1shoppingcart';
				pty.theme.form.name = 'Name';
				pty.theme.form.email = 'Email1';
				pty.addFormInps('input', 'name', 'Name');
				pty.addFormInps('input', 'email', 'Email1');
			}

			//
			// Get Response
			//
			else if (act.indexOf('getresponse.com') > -1) {
				pty.theme.form.type = 'getresponse';
				pty.theme.form.name = 'name';
				pty.theme.form.email = 'email';
				pty.addFormInps('input', 'name', 'name');
				pty.addFormInps('input', 'email', 'email');
			}

			//
			// Campaign Monitor
			//
			else if (act.indexOf('createsend.com') > -1) {
				pty.theme.form.type = 'campaignmonitor';
				pty.theme.form.name = 'cm-name';
				pty.theme.form.email = $j('input[type="text"]', form).eq(1).attr('name');
				pty.addFormInps('input', 'name', 'cm-name');
				if ($j('input[type="email"]', form).length) {
					pty.addFormInps('input', 'email', $j('input[type="email"]', form).attr('name'));
				}
				else {
					pty.addFormInps('input', 'email', $j('input[type="text"]', form).eq(1).attr('name'));
				}
			}

			//
			// Graphic Mail
			// 
			else if (act.indexOf('graphicmail.com') > -1){
				pty.theme.form.type = 'graphicmail';
				pty.theme.form.name = 'Col2';
				pty.theme.form.email = 'Email';
				pty.addFormInps('input', 'name', 'Col2');
				pty.addFormInps('input', 'email', 'Email');
			}

			//
			// InfusionSoft
			// 
			else if (act.indexOf('infusionsoft.com') > -1){
				pty.theme.form.type = 'infusionsoft';
				if (html.html().indexOf('inf_field_Email') > -1) {
					pty.addFormInps('input', 'name', 'inf_field_FirstName');
					pty.addFormInps('input', 'email', 'inf_field_Email');
				}
				else {
					pty.addFormInps('input', 'name', 'Contact0FirstName');
					pty.addFormInps('input', 'email', 'Contact0Email');
				}
			}

			//
			// SilverPop
			// 
			else if (act.indexOf('pages05.net') > -1){
				pty.theme.form.type = 'silverpop';
				pty.theme.form.name = $j('input[type="text"]', form).eq(0).attr('name');
				pty.theme.form.email = 'Email';
				pty.addFormInps('input', 'name', $j('input[type="text"]', form).eq(0).attr('name'));
				pty.addFormInps('input', 'email', $j('input[type="text"]', form).eq(0).attr('name'));
			}

			//
			// Feedburner
			//
			else if (act.indexOf('feedburner.google.com') > -1) {
				pty.theme.form.type = 'feedburner';
				pty.theme.form.email = 'email';
				pty.addFormInps('input', 'email', 'email');
			}

			// DadaMail
			else if (act.indexOf('dada/mail.cgi') > -1) {
				pty.theme.form.type = 'dadamail';
				pty.addFormInps('input', 'name', 'name');
				pty.addFormInps('input', 'email', 'email');
				pty.theme.form.name = 'name';
				pty.theme.form.email = 'email';
			}

			// Office AutoPilot
			else if (act.indexOf('forms.moon-ray.com') > -1) {
				pty.theme.form.type = 'office_autopilot';
				pty.addFormInps('input', 'name', 'firstname');
				pty.addFormInps('input', 'email', 'email');
			}

			// Vertical Response
			else if (act.indexOf('vresp.com') > -1) {
				pty.theme.form.type = 'vertical_response';
				pty.addFormInps('input', 'name', 'first_name');
				pty.addFormInps('input', 'email', 'email_address');
			}

			// Interspire
			else if (act.indexOf('3dxbucks.com') > -1) {
				pty.theme.form.type = 'interspire';
				pty.addFormInps('input', 'name', 'name');
				pty.addFormInps('input', 'email', 'email');
			}

			// Active Campaign
			else if (act.indexOf('agenciaad.com') > -1) {
				pty.theme.form.type = 'active_campaign';
				pty.addFormInps('input', 'name', 'first_name');
				pty.addFormInps('input', 'email', 'email');
			}

			// E-Mail Broadcast
			else if (act.indexOf('mailings.emailbroadcast.com') > -1) {
				pty.theme.form.type = 'email_broadcast';
				pty.addFormInps('input', 'name', 'val_l');
				pty.addFormInps('input', 'email', 'email');
			}

			// HubSpot
			else if (act.indexOf('hubspot.php') > -1) {
				pty.theme.form.type = 'hubspot';
				pty.addFormInps('input', 'name', 'first_name');
				pty.addFormInps('input', 'email', 'email');
			}

			// ymlp
			else if (act.indexOf('ympl.com') > -1) {
				pty.theme.form.type = 'ymlp';
				pty.addFormInps('input', 'name', 'YMP0');
				pty.addFormInps('input', 'email', 'YMP3');
			}

			// Tribulant
			else if (act.indexOf('wpmlformid=') > -1) {
				pty.theme.form.type = 'tribulant';
				pty.addFormInps('input', 'name', 'name');
				pty.addFormInps('input', 'email', 'email');
			}
			else if (act.indexOf('e2ma.net') > -1) {
				pty.theme.form.type = 'myemma';
				pty.addFormInps('input', 'name', 'member_field_first_name');
				pty.addFormInps('input', 'email', 'email');
			}

			else {
				pty.theme.form.type = 'custom';
				$j('#pty_toggleSettings-manualConnectShell').click();
				$j('#pty_customFormMsg').show();
			}
			pty.processHiddenInps(form);
			pty.processCustomInps(form);
			pty.saveForm();
			pty.buildCustomFields();
			$j('#pty_formMsg').remove();
			$j('#pty_newsletterRsp').empty().append('<span>Connected to '+pty.serviceName[pty.theme.form.type]+'!</span>');
		}

		// Finish Processing
		pty.theme.rawForm = undefined;
		var data = {settings: pty.theme, popupid: pty.popupid}; 
		data = pty.step(data, 4);

	/*// Save
		_pty.a.send('updPopup', data, function(){
			if (PTY_PAGE == 'main') {
				location.href = location.href;
			}
			else{
				var doneMsg = ''
				+ '<p>You just made a Pippity Popup! That was easy.</p>'
				+ '<p>It\'s not activated yet, so head to the Pippity Panel'
				+ ' to make it live!</p>';
				if (pty.editing) {
					doneMsg = '<p>You just updated your Pippity Popup!</p>';
				}
				$j('#pty_newsletterFinish').html(''
				+ '<div class="pty_focusText">'+doneMsg+'<br/>'
				+ '<a href="'+PTY_ADM+'pty" class="button-primary">Go to the Pippity Panel</a></div>'
			)/
		} 
		}); */
	},
	serviceName : {
		'aweber' : 'Aweber', 
		'madmimi' : 'MadMimi', 
		'mailchimp' : 'MailChimp', 
		'icontact': 'iContact', 
		'getresponse': 'Get Response',
		'1shoppingcart': '1ShoppingCart',
		'campaignmonitor': 'Campaign Monitor',
		'constcont': 'Constant Contact',
		'graphicmail': 'Graphic Mail',
		'feedburner': 'Feedburner',
		'silverpop': 'Silverpop',
		'dadamail': 'DadaMail',
		'office_autopilot': 'Office Autopilot',
		'vertical_response': 'Vertical Response',
		'interspire': 'Interspire',
		'active_campaign': 'Active Campaign',
		'email_broadcast': 'Email Broadcast',
		'hubspot': 'HubSpot',
		'ymlp': 'ymlp',
		'gravity': 'Gravity Forms',
		'tribulant': 'Tribulant',
		'myemma': 'MyEmma',
		'infusionsoft': 'InfusionSoft',
		'wysija': 'Mail Poet',
		'gravity_pippity': 'Gravity Forms (Auto)',
		'gravity_custom': 'Gravity Forms (Custom)',
		'custom': 'a Custom Form'
	},
	processCustomInps: function(form) {
		pty.saveForm();
		$j('.pty_customInputShell').remove();
		$j('input[type="text"]', form).each(function(i, inp){
			inp = $j(inp);
			var name = inp.attr('name');
			if (name != pty.theme.form.name && name != pty.theme.form.email) {
				pty.addFormInps('custom', name, name, 'no-save');
				if (!$j('#pty_manualConnectShell').is(':visible')) {
					$j('#pty_toggleSettings-manualConnectShell').click();
				}
			}
		});
	},
	processHiddenInps: function(form) {
		$j('#pty_formHiddens').empty();
		$j('input', form).each(function(i, v) {
			v = $j(v);
			if((v.attr('type') == 'hidden' || v.attr('type') == 'submit')){
				name = v.attr('name')
				if (name !== 'undefined' && name) {
					value = v.attr('value')
					pty.theme.form.hidden.push({name: name, value: value});
					pty.addFormInps('hidden', name, value);
				}
			}
		});
	},
	isShortcode: function(str) {
		if (
			str.indexOf('[') > -1 &&
			str.indexOf(']') > str.indexOf('[')
		) {
				return true;
		}
		return false;
	},
	isGravityShortcode: function(str) {
		if (
			str.indexOf('[') > -1 &&
			str.indexOf('gravityform') > str.indexOf('[') &&
			str.indexOf(']') > str.indexOf('gravityform')
		) {
				return true;
		}
		return false;
	},
	isTribulantShortcode: function(str) {
		if (
			str.indexOf('[') > -1 &&
			str.indexOf('wpmlsubscribe') > str.indexOf('[') &&
			str.indexOf(']') > str.indexOf('wpmlsubscribe')
		) {
				return true;
		}
		return false;
	},
	shortCodeForm: function(html) {
		/*pty.loadShortcode(function(rsp.output) {
			$j('#pty_customFormHtml').val(rsp.output).change();
			$j('#pty_newsletterRsp').empty().append('<span>Connected to Shortcode!</span>');
			$j('#pty_toggleSettings-advancedConnectShell').click();
	});*/
	},
	loadTribulantForm: function(html) {
		_pty.a.send('getTribulantForm', {html: html}, function(rsp){
			$j('#pty_formMsg').remove();
			if (!pty.isTribulantShortcode(rsp.form)) {
				$j('#pty_newsletterHtml').val(rsp.form).change();
			}
		});
	},
	loadGravityForm: function(html) {
		_pty.a.send('getGravityForm', {html: html}, function(rsp){
			$j('#pty_formMsg').remove();
			if (!pty.isGravityShortcode(rsp.form)) {
				$j('#pty_customFormHtml').val(rsp.form).change();
				$j('#pty_newsletterRsp').empty().append('<span>Connected to '+pty.serviceName[pty.theme.form.type]+'!</span>');
				$j('#pty_toggleSettings-advancedConnectShell').click();
			}
			else {
				$j('#pty_newsletterRsp').empty().append('<span>Not a Valid Gravity Form</span>');
			}

		});
	},
	saveNewsletter: function() {
		pty.theme.new_page = $j("#pty_new_pageSetting").val()
		sNav.next();
	},
	updateCustomField: function() {
		pty.saveForm();
		pty.buildCustomFields();
	},
	syncInputOrder: function() {

	},
	moveInput: function() {
		var $t = $j(this);
		var $s = $t.closest('.pty_formInputShell');
		var nw = $s.clone();
		var dir = $t.data('dir');
		if (dir == 'up') {
			var around = $s.prev();
			if (around.length) {
				nw.insertBefore(around);
				$s.remove();
			}
		}
		else if(dir == 'dn') {
			var around = $s.next();
			if (around.length) {
				nw.insertAfter(around);
				$s.remove();
			}
		}
		pty.saveForm();
		pty.buildCustomFields();
		return false;
	},
	addFormInps: function(type, name, value, nosave) {
		if (name === undefined) {
			name = ''; 
		}
		if (value === undefined) {
			value = ''; 
		}
		if (typeof type !== 'string') {
			$t = $j(this);
			if ($t.hasClass('pty_formFieldAdd')) {
				type = 'custom';
			}
			else if ($t.hasClass('pty_formHiddenAdd')) {
				type = 'hidden';
			}
			else if ($t.hasClass('pty_formNotificationAddressAdd')) {
				type = 'notify';
			}
		}
		var el = '';
		if (type == 'input') {
			startName = 'Field';
			startValue = 'HTML Name';
			$j('#pty_formInputValue-'+name).labeledInput(startValue).val(value).focus().blur();	
		}
		else if (type == 'custom') {
			var cstm_id = $j('.pty_customInputShell').length;
			el = $j('#pty_formInputs');
			startLabel = 'Label';
			startName = 'HTML Name';
			html = '<div data-cstm_id="'+cstm_id+'" id="pty_fieldShell-'+name+'" class="pty_formInputShell pty_formPartShell pty_customInputShell">'
				+ '<div class="pty_formInputMoveShell">'
				+ '		<a href="#" class="pty_formInputMove" data-dir="up">▲</a>'
				+ '		<a href="#" class="pty_formInputMove" data-dir="dn">▼</a>'
				+ '</div>'
				+ '		<input type="text" class="pty_formPartInp pty_formPartLabel" name="formInputName[]"/>'
				+ '		<input type="text" id="pty_formInputValue-'+name+'" class="pty_formInputLabel pty_formPartInp pty_formPartName" name="formInputLabel[]"/>'
				+ '		<a href="#" class="pty_formInputRemove pty_formPartRemove">x</a>';
				+ '	</div>';
			obj = $j(html);
			el.append(obj);
			$j('.pty_formPartName', obj).labeledInput(startName).val(name).focus().blur();
			$j('.pty_formPartLabel', obj).labeledInput(startLabel).val(value).focus().blur();
			if (nosave === undefined) {
				pty.saveForm();
				pty.buildCustomFields();
			}
		}
		else if (type == 'hidden') {
			el = '#pty_formHiddens';
			startName = 'HTML Name';
			startValue = 'Value';
			html = '<div class="pty_formHiddenShell pty_formPartShell">'
				+ '<input type="text" class="pty_formHiddenName pty_formPartInp pty_formPartName" name="formHiddenName[]"/>'
				+ '<input type="text" class="pty_formHiddenValue pty_formPartInp pty_formPartValue" name="formHiddenValue[]"/>'
				+ '<a href="#" class="pty_formInputRemove pty_formPartRemove">x</a>';
			el = $j(el);
			obj = $j(html);
			el.append(obj);
			$j('.pty_formPartName', obj).labeledInput(startName).val(name).focus().blur();
			$j('.pty_formPartValue', obj).labeledInput(startValue).val(value).focus().blur();
		}
		else if (type == 'notify') {
			el = $j('#pty_notificationAddresses');
			obj = $j('.pty_notificationAddress').parent().clone();
			$j('.pty_notificationAddress', obj).val('');
			el.append(obj);
		}
		return false;
	},
	updateNotificationAddress: function(){
		pty.theme.notify = [];
		$j('.pty_notificationAddress').each(function(){
			pty.theme.notify.push($j(this).val());
		});
	},
	saveForm: function() {
		if (pty.theme.form === undefined) {
			pty.theme.form = {};
		}
		if (pty.theme.form !== undefined) {
			pty.theme.form.action = $j('#pty_formAction').val()
			pty.theme.form.custom_fields = [];
			$j('.pty_formInputShell', '#pty_formInputs').each(function(){
				$t = $j(this);
				var input = {};
				if ($t.hasClass('pty_customInputShell')) {
					input.label = $j('.pty_formPartLabel', $j(this)).val();
					input.name = $j('.pty_formPartName', $j(this)).val();
				}
				else {
					input.pre = $t.data('field');
				}
				pty.theme.form.custom_fields.push(input);
			})
			pty.theme.form.name = $j('#pty_formInputValue-name').val()
			pty.theme.form.email = $j('#pty_formInputValue-email').val()
			pty.theme.form.hidden = [];
			$j('.pty_formHiddenShell', '#pty_formHiddens').each(function(){
				var hidden = {};
				hidden.name = $j('.pty_formPartName', $j(this)).val();
				hidden.value = $j('.pty_formPartValue', $j(this)).val();
				if (hidden.value == 'Value') {
					hidden.value = '';
				}
				pty.theme.form.hidden.push(hidden);
			});
		}
		pty.theme.customForm = _pty.stripSlashes($j('#pty_customFormHtml').val());
		console.info(pty.theme.customForm);
		console.info($j('#pty_customFormHtml').val());
		if (typeof pty.theme.customForm != 'string') {
			pty.theme.customForm = '';
		}
	},
	saveNewsletterAndFinish: function(){
		pty.saveForm();
		var data = {settings: pty.theme, popupid: pty.popupid}; 
		var newP = '';
		if (parseInt(pty.theme.step) == 3) {
			newP = '&pty_n='+pty.popupid;
		}
		data = pty.step(data, 4);
		_pty.a.send('updPopup', data, function(){
				location.href = PTY_ADM + 'pty'+newP;
		});
		return false;
	},
	removeFormInp: function() {
		var $t = $j(this);
		var field = $t.data('field');
		if (field !== undefined) {
			$j('#pty_hideInput-'+field).attr('checked', 'checked');
			pty.toggleInputVisibility(field);
			$j(this).closest('.pty_formPartShell').hide();
		}
		else {
			$j(this).closest('.pty_formPartShell').remove();
		}
		pty.saveForm();
		pty.buildCustomFields();
		return false;
	},
	syncFormInputs: function() {
		if (pty.theme.form !== undefined) {
			var form = JSON.parse(JSON.stringify(pty.theme.form));
			if (form.action !== undefined) {
				$j('#pty_formAction').val(form.action);
			}
			if (form.name !== undefined) {
				$j('#pty_formInputValue-name').val(form.name);
				$j('#pty_formInputName-name').val(pty.theme.copy.name.text);
			}
			if (form.email !== undefined) {
				$j('#pty_formInputValue-email').val(form.email);
				$j('#pty_formInputName-email').val(pty.theme.copy.email.text);
			}
			if (form.custom_fields !== undefined && form.custom_fields.length) {
				$j.each(form.custom_fields, function(i, field){
					if (field.label !== undefined) {
						pty.addFormInps('custom', field.name, field.label, 'no-save');
					}
				});
				cstm = $j('<div/>').attr('id', 'pty_custom_field_divide');
				$j('#pty_formInputs').prepend(cstm);
				$j.each(form.custom_fields, function(i, field) {
					var name = '';
					if (field.name !== undefined) {
						name = field.name;
					}
					else {
						name = field.pre;
					}
					var mv_field = $j('#pty_fieldShell-'+name);
					var nw = mv_field.clone();
					nw.insertBefore(cstm);
					mv_field.remove();
				});
				cstm.remove();
			}
			if (form.hidden !== undefined) { 
				$j.each(pty.theme.form.hidden, function(i, v){
					pty.addFormInps('hidden', v.name, v.value, 'no-save');
				})
			}
			pty.saveForm();
			pty.buildCustomFields();
		}
	},
	syncNotifies: function() {
		if (pty.theme.notify !== undefined && pty.theme.notify.length) {
			var el = $j('#pty_notificationAddresses');
			var orig = $j('.pty_notificationAddress').parent();
			orig.remove();
			$j.each(pty.theme.notify, function(i, email) {
				var notify = orig.clone();
				$j('.pty_notificationAddress', notify).val(email);
				el.append(notify);
			});
		}
	},
	toggleInputVisibility_CLHandler: function() {
		input = _pty.idData($j(this));
		pty.toggleInputVisibility(input);
	},
	toggleInputVisibility: function(input) {
		custInp = $j('#pty_customCss');
		css = custInp.val();
		var show = true;
		if ($j('#pty_hideInput-'+input).is(':checked')) {
			show = false;
		}
		newCss = '#pty_'+input+' { display: none !important; } ';
		css = css.replace(newCss, '');
		if ( !show ) {
			$j('#pty_fieldShell-'+input).hide();
			css += newCss;
		}
		else {
			$j('#pty_fieldShell-'+input).show();
		}
		custInp.val(css).change()
	},
	syncInputVisibility: function() {
		if (pty.theme.customCss.indexOf('#pty_name { display: none !important; } ') > -1){
			$j('#pty_hideInput-name').attr('checked', 'checked');
		}
		else {
			$j('#pty_hideInput-name').removeAttr('checked');
		}
		if (pty.theme.customCss.indexOf('#pty_email { display: none !important; } ') > -1){
			$j('#pty_hideInput-email').attr('checked', 'checked');
		}
		else {
			$j('#pty_hideInput-email').removeAttr('checked');
		}
	},

	/*
	 * Theme handling
	 */
	toggleFullFrame: function() {
		var frame = $j('#pty_example');
		if (!pty.isFullFrame) {
			if (pty.selected) {
				frame
				.css('position', 'absolute')
				.css('top', '0px')
				.css('left', '0px');
				var dims = frame.offset();
				frame
				.css('left', '-'+dims.left+'px')
				.css('top', '-'+dims.top+'px')
				.css('width', $j(window).width()+'px')
				.css('height', $j(window).height()+'px');
				pty.position();
				$j('#pty_toggleFullFrame').addClass('pty_fullFramed').text('Hide Full-Size');
				$j('#pty_refreshTheme').fadeTo(500, .1).mouseover(function(){$j(this).fadeTo('500', 1);}).mouseout(function(){$j(this).fadeTo('500', .2);});
				pty.isFullFrame = true;  
			}
		}
		else{
			frame
			.css('position', 'relative')
			.css('top', '0px')
			.css('left', '0px')
			.css('width', '100%')
			.css('height', ($j(window).height()-215)+'px');
			pty.position();
			$j('#pty_toggleFullFrame').removeClass('pty_fullFramed').text('Show Full-Size');
			$j('#pty_refreshTheme').hide();
            pty.isFullFrame = false;
		}

	},
	refreshTheme: function() {
		var data = {theme: pty.theme.file, nocache:true};
		_pty.a.send('getTheme', data, function(rsp){
			pty.showTheme(rsp.theme, true);
		});
	},
	activate: function() {
		_pty.a.send('activate', {key : $j('#pty_key').val()}, function() {
			setTimeout(function() {
				location.href = PTY_ADM+'pty';	
			}, 750);
		});
		return false;
	},
	requestSupport: function() {
		var data = _pty.formToJson('pty_supportForm');
		data.details = { 
			resolution : {width: $j(window).width(), height: $j(window).height()},
			system : {}
		}
		data.details.system = {};
		$j.each(navigator, function(i, v){
			if (typeof v == 'string') {
				data.details.system[i] = v; 
			}
		});
		data.domain = PTY_DOM;
		_pty.a.send('supportRequest', data, function() {
			$j('.pty_messageText').html('<p><strong>Support Request Sent!</strong></p><p>We\'ll be in touch with you soon, thanks!</p>');
			$j('#pty_supportForm').remove();
			
		});
		return false;
	},
	initRange: function() {
		$j('#pty_rangeSelectShell').attr('style', 'position:absolute; top:-9999px; left:-9999px;');
		$j('#pty_rangepicker').datepicker({
			nextText: '▶',
			prevText: '◀',
			onSelect: pty.selectRangeData,
			disabled: true
		});
		setTimeout(function(){
			$j('#pty_rangepicker').datepicker('setDate', new Date());
			$j('#pty_rangeSelectShell').attr('style', 'position:absolute')
		}, 0);
		pty.selectRangeInput('#pty_rangeStart');    
		pty.rangeStart = 0;
		pty.rangeEnd = new Date();
		pty.rangeEnd = pty.rangeEnd.getTime() / 1000;
	},
	showRangeSelect: function() {
		$j('#pty_rangeSelectShell').show();
	},
	hideRangeSelect: function() {
		$j('#pty_rangeSelectShell').hide();
	},
	rangeSubmit: function() {
		$j('span', '#pty_range').html($j('#pty_rangeStart').val()+' - '+$j('#pty_rangeEnd').val());
		var start = ($j('#pty_rangeStart').val()) ? new Date($j('#pty_rangeStart').val()) : 0;
		var end = ($j('#pty_rangeEnd').val()) ? new Date($j('#pty_rangeEnd').val()) : 0;
		pty.rangeStart = start ? start.getTime()/1000 : 0;
		pty.rangeEnd = end ? end.getTime()/1000 : 0;
		if (pty.rangeSubmitEvent != undefined) {
			pty.rangeSubmitEvent();
		}
		return false;
	},
	selectRangeInput: function(obj) {     
		obj = this.__end == undefined ? $j(this) : $j(obj);
		if (obj.attr('class') == 'pty_dateInp') {
			var selDate = new Date(obj.val());
			$j('#pty_rangepicker').datepicker('setDate', selDate);
			pty.selectedRangeInput = obj;
			$j('.pty_dateInp').removeClass('pty_selectedRangeInput');
			obj.addClass('pty_selectedRangeInput');   	
		}
	},
	selectRangeData: function() {
		pty.selectedRangeInput.val($j.datepicker.formatDate('mm/dd/yy', $j(this).datepicker('getDate')));
		setTimeout(function(){
			pty.selectRangeInput('#pty_rangeEnd');
		},500);
	},
	selectQuickRange: function() {
		var type = _pty.idData($j(this));
		var start = $j('#pty_rangeStart');
		var now = new Date();
		var end = $j('#pty_rangeEnd').val($j.datepicker.formatDate('mm/dd/yy', now));
		var rangeText = '';
		pty.selectRangeInput(end);
		switch(type){
			case 'allTime':
			start.val('');
			rangeText = 'All-time';
			break;
			case 'pastMonth':
			var mAgo = new Date(now.getTime() - 2629743830);
			start.val($j.datepicker.formatDate('mm/dd/yy', mAgo));
			rangeText = 'Past Month';
			break;
			case 'pastWeek':
			var wAgo = new Date(now.getTime() - 604800000);
			start.val($j.datepicker.formatDate('mm/dd/yy', wAgo));
			rangeText = 'Past Week';
			break;
		}
		pty.selectRangeInput(start);
		pty.rangeSubmit();
		$j('span', '#pty_range').html(rangeText);
		$j('#pty_rangeSelectShell').hide();
	},
	toggleChangeInfo: function() {
		$j('.pty_notableChanges').toggle();
	},
	step: function(data, step) {
		if (pty.theme.step == undefined || pty.theme.step < step) {
			pty.theme.step = step;
			data.step = step;
		}
		return data;
	},

    /*
	 * Suggestion Messages
	 */
	initSMsg: function() {
		var smp = $j('#pty_example');
		var sdims = smp.offset();
		var msg = $j('<a id="pty_sugmsg" href="#" target="_blank"><span></span></a>')
		msg.insertBefore('#poststuff').hide();
		msg
			.css('top', (sdims.top-62) + 'px')
			.css('width', smp.width()-2+'px')
			.css('right', ($j(window).width() - 7 - (sdims.left + smp.width()) + 'px'));
		pty.showSMsg(0);
		setTimeout(function(){
		    msg.fadeIn('slow');
		},2000);
	},
	showSMsg: function(num) {
		$j('#pty_sugmsg > span').fadeOut('slow', function(){
			$j('#pty_sugmsg > span').html(pty.sugmsgs[num].text).fadeIn().parent().attr('href', pty.sugmsgs[num].url);

		});
	},
	sugmsgs: [
		{url: "http://pippity.com/custom", text: "Want a personally modified or totally custom popup? <span>Click here!</span>"},
		{url: "http://pippity.com/custom", text: "Want a personally modified or totally custom popup? <span>Click here!</span>"},
		{url: "http://pippity.com/covers", text: "Need a slick eBook Cover? <span>Try eCoverCreator3d!</span>"},
		{url: "http://tumbledesign.com", text: "You're almost done! Could this be any easier?!"},
		{url: "http://pippity.com/best-mail-services", text: "Our favorite Newsletter services are Aweber and MadMimi. <span>You should check them out.</span>"}
	],

	/**
	 * Pointers
	 */
	pnt_newPopup: function(popupid){
		if (jQuery.fn.pointer !== undefined) {
			$j('#pty_activate-'+popupid).pointer({ 
				content: '<h3>You Made a Popup!</h3><p>When you\'re ready, hit <b>Activate</b> to make it live!</p>', 
				buttons: function( event, t ) {
					button = $j('<a id="pointer-close" class="button-secondary">Ok, I got it.</a>');
					button.bind( 'click.pointer', function() {
						t.element.pointer('close');
					});
					return button;
				},
				position: {
					my: 'left top',
					at: 'left bottom', 
					offset: '-35 0'
				},
				arrow: {
					edge: 'right',
					align: 'right',
					offset: 10
				},
				close: function() { }
			}).pointer('open');
		}
	},

	/*
	 * Hooks 
	 */
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
	hooks: {},
	selected : false,
	variants : {},
	isFullFrame : false,
	themes : [],
	usedSImgLinks: [],
	temp : {settings:false, copy:false},
	base : {},
	showThemeTimo: 0,
	fontListTimos : {},
	__end : true
}

snav = {
	setup: function() {
		$j('#sNav-fwd').click(function() {
			if(!$j(this).hasClass('disabled')){
				snav.next();
			}
			return false;
		});
		$j('#sNav-back').click(function() {
			if (!$j(this).hasClass('disabled')) {
				snav.prev();
			};
			return false;
		});
	},
	next: function(now, callback) {
		if (callback === undefined) {
			callback = function(){};
		}
		snav.on.id++;
		pty.showSMsg(snav.on.id);
		snav.on.slug = snav.panes[snav.on.id];
		snav.on.elm = $j('#sNav-'+snav.on.slug);
		var delay = 300;
		if (now !== undefined) {
			delay = 0;
		}
		pty_tooltip.ready = false;
		var zoom = (window.outerWidth - 8) / window.innerWidth;
		$j('#slideNav').animate({
			left : '-=' + ($j('#sNav-'+snav.on.slug).offset().left - $j('.slideNav-shell').offset().left)
		}, delay, function() { 
			callback(); 
			pty_tooltip.ready = true
		});
		snav.changeHeading();
		if(snav.maxOn < snav.on.id){
			snav.maxOn = snav.on.id;
		}
		snav.nav();
		if (snav.on.slug == 'settings') {
			slider.sync();
		}
	},
	prev: function(now, callback) {
		if (callback === undefined) {
			callback = function(){};
		}
		snav.on.id--;
		snav.on.slug = snav.panes[snav.on.id];
		snav.on.elm = $j('#sNav-'+snav.on.slug);
		pty_tooltip.ready = false;
		var delay = 300;
		if (now !== undefined) {
			delay = 0;
		}
		$j('#slideNav').animate({
			left : '+=' + $j('#pty_themeSide').outerWidth()
		}, delay, function() { callback(); pty_tooltip.ready = true;});
		snav.changeHeading();
		snav.nav();
	},
	changeHeading: function(){
		$j('#sNav-heading').fadeOut(200, function(){
			$j('#sNav-heading')
			.html($j('.sNav-heading', snav.on.elm).html())
			.fadeIn(300);
		});
		pty.loadPopupData();
	},
	goTo: function(pane) {
		while(pane != snav.on.id) {
			if(pane > snav.on.id) {
				snav.next(function() { snav.goTo(1, pane); });
			}
			else {
				snav.prev(function() { snav.goTo(1, pane); });
			}
		}
	},
	nav: function() {
		$j('#sNav-nav').show();
		if(snav.maxOn > snav.on.id){
			$j('#sNav-fwd').removeClass('disabled');
		}
		else{
			$j('#sNav-fwd').addClass('disabled');
		}
		if (snav.on.id) {
			$j('#sNav-back').removeClass('disabled');
		}
		else{
			$j('#sNav-back').addClass('disabled');
		}
	},
	panes : ['select', 'copy', 'settings', 'newsletter', 'done'],
	on : {id:0, slug:'', elm:''},
	maxOn : 0,

	__end : true
}

pty_tooltip = {
	setup: function() {
		$j(document)
			.on('mouseover', '.pty_tooltip', pty_tooltip.show)
			.on('mouseout', '.pty_tooltip', pty_tooltip.hide);
		$j(document)
			.on('mouseover', '#pty_tip', pty_tooltip.show)
			.on('mouseout', '#pty_tip', pty_tooltip.hide);
		$j(document).on('focus', '.pty_tooltip input', pty_tooltip.show);
		pty_tooltip.ready = true;
	},
	show: function() {
		clearTimeout(pty_tooltip.hideTimo);
		$this = $j(this);
		if (pty_tooltip.ready && ( !$j('#pty_tip').length || ($this.attr('id') !== 'pty_tip'))) {
			$j('#pty_tip').remove();
			if (!$this.hasClass('.pty_tooltip')) {
				$this = $this.closest('.pty_tooltip');
			}
			var dims = $this.offset();
			var y = dims.top - ($this.height() / 2) + 35;
			var x = dims.left + ($this.width()) + 20;
			var tip = $j('<div>')
			.attr('id', 'pty_tip')
			.html('<img src="'+PTY_URL+'/images/tooltip_arrow.png" id="pty_tooltip_arrow"/>'+$j('.pty_ttContent', $this).html())

			.css('top', _pty.px(y))
			.css('left', _pty.px(x))
			.addClass('rc6')
			.show();
			$j('body').append(tip);
			$j('#pty_tooltip_arrow')
			.stop(true, true)
			.show();
			if ($this.hasClass('pty_ttip_fade')) {
				setTimeout(function(){
					$j('#pty_tip').fadeOut(3000);
					$j('#pty_tooltip_arrow').fadeOut(3000);
				}, 2000);
			}
		}
	},
	hide: function(now) {
		if (typeof now == 'string') {
			$j('#pty_tip').remove();
			$j('#pty_tooltip_arrow').hide();
		}
		else {
			pty_tooltip.hideTimo = setTimeout(function(){
				$j('#pty_tip').remove();
				$j('#pty_tooltip_arrow').hide();
			}, 100);
		}
	},
	ready : false,
	hideTimo: 0,
	__end : true
}

slider = {
	setup: function() {
		$j('.inp-slider').each(function() {
			var name = $j(this).attr('name');
			var dflt = $j(this).attr('value') == 'on' ? true : false;
			var sldr = $j('<div class="inp-sliderShell">');
			var clone = $j(this).clone().css('position', 'absolute').css('left', '-9999px');
			sldr.append(''
				+ '<a href="#" class="inp-slider-block"></a>'
				+ '<a href="#" class="inp-slider-on inp-slider-button">ON</a>'
				+ '<a href="#" class="inp-slider-off inp-slider-button">OFF</a>'
				+ '<div class="clear"></div>'
			).append(clone);
			$j('a', sldr)
			.click(slider.toggle)
			.focus(slider.setFocus);
			$j('input', sldr)
			.focus(slider.focus)
			.blur(slider.blur);
			if ($j('input', sldr).is(':checked')) {
				$j('.inp-slider-block', sldr).animate({left: '+=46px'});
				$j('.inp-slider-off', sldr).animate({textIndent: '+=46px'});
			}
			else{
				$j('.inp-slider-on', sldr).animate({textIndent: '-=46px'});
			}
			sldr.insertBefore($j(this));
			$j(this).remove();
		});
		$j(document).keyup(function(e) {
			if (slider.focused) {
				if (e.keyCode == 32) {
					slider.toggle(slider.focused);
					e.preventDefault();
					e.stopPropagation();
					return false;
				}
			}
		});
	},
	focus: function() {
		var shell = $j(this).closest('.inp-sliderShell');
		var shadow = '0 0 4px #0077F5';
		shell
			.css('-moz-box-shadow', shadow)
			.css('box-shadow', shadow)
			.css('-webkit-box-shadow', shadow);
		slider.focused = shell;
	},
	blur: function() {
		var shell = $j(this).closest('.inp-sliderShell');
		shell
			.css('-moz-box-shadow','')
			.css('box-shadow', '')
			.css('-webkit-box-shadow', '');
		slider.focused = false;
	},
	setFocus: function() {
		var shell = $j(this).closest('.inp-sliderShell');
		var inp = $j('input', shell);
		inp.focus();
	},
	sync: function() {
		$j('.inp-sliderShell').each(function() {
			var shell = $j(this);
			var slide = $j('.inp-slider-block', shell);
			var inp = $j('input', shell);
			if (inp.is(':checked')) {
				if (parseInt(slide.css('left')) < 25) {
					slider.animate(shell, 1);
				}
			}
			else{
				if (parseInt(slide.css('left')) > 25) {
					slider.animate(shell, -1);
				}
			}
		});
	},
	toggle: function(shell) {
		shell = $j(shell).hasClass('inp-sliderShell') ? shell : $j(this).closest('.inp-sliderShell');
		var inp = $j('input', shell);
		if (inp.is(':checked')) {
			inp.attr('checked', false).attr('value', '0');
			slider.animate(shell, -1);
		}
		else{
			inp.attr('checked', true).attr('value', '1');
			slider.animate(shell, 1);
		}
		if ($j('#pty_fade', shell).length) {
			pty.toggleFade();
		}
		inp.change();
		return false;
	},
	animate: function(shell, dir) {
		if (dir > 0) {
			$j('.inp-slider-block', shell).animate({left: '+=47px'});
			$j('.inp-slider-off', shell).animate({textIndent: '+=47px'});
			$j('.inp-slider-on', shell).animate({textIndent: '+=47px'});
		}
		else{
			$j('.inp-slider-block', shell).animate({left: '-=47px'});
			$j('.inp-slider-on', shell).animate({textIndent: '-=47px'});
			$j('.inp-slider-off', shell).animate({textIndent: '-=47px'});
		}
	},
	__end : true
}

pty.readys = {};
pty.ready = function(id, fnc) {
	if (pty.readys[id] === undefined) {
		pty.readys[id] = {ready: false, fnc: false};
	}
	if(typeof pty.readys[id] == 'function'){
		pty.readys[id].fnc = function(){
			pty.readys[id];
		}
	}
	else{
		pty.readys[id].fnc = function(){
			fnc();
		}
	}
	if (pty.readys[id].ready) {
		pty.doReady(id);
	}
}
pty.isReady = function(id) {
	if (pty.readys[id] !== undefined) {
		pty.readys[id].ready = true;
		pty.doReady(id);
	}
	else{
		pty.readys[id] = {ready: true, fnc: false};
	}
}
pty.doReady = function(id) {
	pty.readys[id].fnc();
	pty.readys[id].fnc = null;
}
