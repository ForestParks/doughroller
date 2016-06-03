;(function($){
	$(document).ready(function(){
		var provider = $('#provider').val();
		select_tab(provider);
		if($('#popup_domination_container .notices .message').text().length > 2){
			$('#popup_domination_container .notices').fadeIn('slow').delay(8000).fadeOut('slow');
		}
		$('#landingpage').change(function(){
			$('#landingurl').attr('disabled',($(this).is(':not(:checked)')));
		});
		
		
		
		
		
		
		$('#custom_select').change(function(){
			customnum = parseInt($(this).val());
			var i = 1;
			
			for (; i <= 2; i++){
				$('#custom_fields #custom'+i).val('').hide();
				$('#cc_custom_fields #custom'+i).val('').hide();
				$('#form_custom_fields #custom'+i).val('').hide();
			}
			i = 1;
			for (; i <= customnum; i++){
				$('#custom_fields #custom'+i).val('').show();
				$('#cc_custom_fields #custom'+i).val('').show();
				$('#form_custom_fields #custom'+i).val('').show();
			}
		});
		
		
		$('.fancybox').fancybox({
			'type': 'iframe',
			'width': '75%',
			'height': '75%'
		});
		
		$('#landingpage').change(function(){
			if ($('#landingpage').attr('checked') == 'checked') {
				var redirect = $('#landingurl').val();
		       	$('.redirecturl').val(redirect);
		    }else{
		    	$('.redirecturl').val('');
		    }
	    });
	
	    $('.landingpage #landingurl').blur(function(){
	    	var url = $(this).val();
	    	$('#form .redirecturl').val(url);
	    });		
		
		
		
		
		$('#popup_domination_tabs a').click(function(){
			var provider = $(this).attr('data-provider');
			$('#newprovider').val(provider);
			
			$('#popup_domination #mailing-redirect-check').show();
			if($('#popup_domination #mailing-redirect-check').val() == 'yes'){
				$('#popup_domination #mailing-redirect-url').show();
			}
			$('#mailingfeedback').show();
			$('#popup_domination #redirect_user').removeAttr('disabled');
			$('#popup_domination #redirect_url').removeAttr('disabled');
			$('#general_custom_fields').show();
			$('#cc_custom_fields').hide().children('select').each(function(){
				$(this).attr('disabled', 'disabled');
				$(this).hide();
			});
			$('#form_custom_fields').hide().children('select').each(function(){
				$(this).attr('disabled', 'disabled');
				$(this).hide();
			});
			
			if(provider == 'form'){
				$('#popup_domination #redirect_user').attr('disabled', 'disable');
				$('#popup_domination #redirect_url').attr('disabled', 'disable');
				$('#popup_domination #mailing-redirect-check').hide();
				$('#popup_domination #mailing-redirect-url').hide();
				$('#mailingfeedback').hide();
				$('#general_custom_fields').hide();
				$('#form_custom_fields').show().children('select').each(function(){
					$(this).removeAttr('disabled');
					$(this).show();
				});
			} else if (provider == 'nm') {
				$('#mailingfeedback').hide();
			} else if (provider == 'cc'){
				$('#general_custom_fields').hide();
				$('#cc_custom_fields').show().children('select').each(function(){
					$(this).removeAttr('disabled');
					$(this).show();
				});
			}
		});
		
		$('#redirect_check').change(function(){
			if ($(this).attr('checked') == 'checked'){
				$('#redirect_url').removeAttr('disabled');
			} else {
				$('#redirect_url').attr('disabled', 'disabled');
			}
		});
		
		$('#disable_name_field').change(function(){
			if ($('#disable_name_yes').attr('checked') == 'checked'){
				$('#aw_disable_name').val('email');
			} else {
				$('#aw_disable_name').val('name,email');
			}
		});
		
		$('#redirect_user').change(function(){
			if($('#redirect_user_yes').is(':checked')){
				$('#mailing-redirect-url').show();
				$('#redirect_url').removeAttr('disabled');
			} else {
				$('#mailing-redirect-url').hide();
				$('#redirect_url').attr('disabled', 'disabled');
			}
		});
		$('body').on('change','.mailing_lists', function(){
			var value = $(this).val();
			$('#newlistid').val(value);
			var text = $('.mailing_lists option:selected').text();
			$('#newlistname').val(text);
		});
		$('body').on('focus','.mailing_lists', function(){
			var value = $(this).val();
			$('#newlistid').val(value);
			var text = $('.mailing_lists option:selected').text();
			$('#newlistname').val(text);
		});
		$('body').on('click','.apisubmit', function(e){
			var name = $('#config_name').val();
			name = $.trim(name);
			if (name.length != 0){
				var listval = $('.mailing_lists').val();
				if( $('#newlistid').val().length === 0 ) {
					var value = $('.mailing_lists').val();
					$('#newlistid').val(value);
					var text = $('.mailing_lists option:selected').text();
					$('#newlistname').val(text);
				}
			} else {
				alert("Please enter a name for the Mailing List configuration");
				e.preventDefault();	
			}
		});
	});
	
	
	
	function hide(){
		$('.popdom_contentbox_inside .waiting').hide();
	}
	
	function set_cookie(name,value,date){
		var str = popup_domination_url;
		var str2 = str.split(website_url+'/');
		var f = str2[1];
		window.document.cookie = [name+'='+escape(value),'expires='+date.toUTCString(),'path=/'+f+'inc/'].join('; ');
	};
	
	function multiple_fields(){
		var num_extra_inputs = custominputs.numfields;
		var i = 1;
		if(num_extra_inputs == 0){
			$('#popup_domination_field_custom' + i +'_default').remove();
			$('.popup_domination_custom_inputs').empty();
		}else{
			var checkforinputs = $('.popup_domination_custom_inputs > p').size();
			if(checkforinputs <= num_extra_inputs){
				while(i<=num_extra_inputs){
					$('.popup_domination_custom_inputs').append('<p><label for="popup_domination_custom'+(i)+'_box"><strong>Custom Field '+(i)+':</strong></label><select id="popup_domination_custom'+(i)+'_box" name="popup_domination[custom'+(i)+'_box]"></select><input type="hidden" id="popup_domination_custom'+(i)+'_box_selected" value=""/></p>');
					i++;
				}
			}
		}
	}
	
	function select_tab(provider){
		$('#popup_domination_tabs a').each(function(){
			$(this).removeClass('selected');
			var hashtab = $(this).attr('href');
			var tab = hashtab.split('#').pop();
			$('#popup_domination_tab_'+tab).hide();
		});
		$('#popup_domination_tabs a').each(function(){
			var alt = $(this).attr('data-provider');
			if (alt == provider){
				$(this).addClass('selected');
				var hashtab = $(this).attr('href');
				var tab = hashtab.split('#').pop();
				$('#popup_domination_tab_'+tab).show();
			}
		});
	};
	function get_hash(str){
		if(str.indexOf('#') !== -1)
			return str.split('#').pop();
		return str;
	};
	function init_tabs(){
		var linestart = true;
		var thislink = '';
		cur_hash = get_hash(document.location.hash);
		var elem = $('#popup_domination_tabs a'), cur_hash = get_hash(document.location.hash);
		elem.each(function(){
			var hash = get_hash($(this).attr('href'));
			if($('#popup_domination_tab_'+hash).length > 0){
				$(this).click(function(){
					$('.mailingfeedback').empty();
					var id = get_hash($(this).attr('href'));
					if(id == 'htmlform'){
						$('#landingurl').attr('disabled','disabled');
						$('#landingpage').attr('disabled','disabled');
					}else{
						$('#landingurl').removeAttr('disabled');
						$('#landingpage').removeAttr('disabled');
					}
					id = '#popup_domination_tab_'+id;
					$(id).show();
					$('#popup_domination_container div[id^="popup_domination_tab_"]:not('+id+'):visible').toggle();
					
					
					
					
					$(id+':not(:visible)').toggle();
					$('.selected').removeClass('selected');
					$(this).addClass('selected');
					return false;
				});
			}
		});
		if(cur_hash != ''){
			var elem2 = elem.filter('[href$="#'+cur_hash+'"]');
			if(elem2.length > 0){
				elem2.click();
				return;
			}
		}
		elem.filter(':eq(0)').click();
	};

})(jQuery);