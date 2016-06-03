;(function($){
	$(document).ready(function(){
		$('.fancybox').fancybox({
			'type': 'iframe',
			'width': '75%',
			'height': '75%'
		});
		$('.cc_getlist').click(function(){
			cc_mail_list();	
		});
		$('#cc_custom1').change(function(){
			val = $(this).val();
			$('.custom1').val(val);
		});
		$('#cc_custom2').change(function(){
			val = $(this).val();
			$('.custom2').val(val);
		});
	});
	function cc_mail_list(){
		$('.mailing-ajax-waiting').show();
		var apikey = $('#cc_apikey').val();
		var username = $('#cc_username').val();
		var secret = $('#cc_usersecret').val();
		var data = {
			action: 'popup_domination_mailing_client',
			provider: 'cc',
			token_key: apikey,
			username : username,
			user_secret : secret
		};
		jQuery.post(popup_domination_admin_ajax, data, function(response) {
			$('.mailing-ajax-waiting').hide();
			$('#mailingfeedback').empty();
			$('#mailingfeedback').append(response);
			$('#form #provider').val('cc');
			$('#newlistid').val($('.mailing_lists').val());
			$('#newlistname').val($('.mailing_lists option:selected').text());
		});
	}
})(jQuery);