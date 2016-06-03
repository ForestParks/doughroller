;(function($){
	$(document).ready(function(){
		
		
		/* Create initial hidden form */
		var htmlform = $('#popup_domination_tab_htmlform #popup_domination_formhtml').val();
		if (htmlform.length > 0){
			$('#popup-domination-html-hidden-form').html($('#popup_domination_tab_htmlform textarea').val());
			createForm();
		}
		
		/* Add warning if user does not enter details */
		$('#popup_domination_tab_htmlform #popup_domination_formhtml').blur(function(){
			if ($(this).val().length == 0){
				$(this).addClass('input-error');
			} else {
				$(this).removeClass('input-error');
			}
		});
		
		$('#popup_domination_tab_htmlform #popup_domination_formhtml').change(function(){
			/* Fill in new hidden HTML form code */
			$('#popup-domination-html-hidden-form').html($(this).val());
			createForm();
			/* Show reminder if user makes a change to the HTML form code */
			$('#popup_domination_tab_htmlform #name_box_reminder').show();
			$('#popup_domination_tab_htmlform #email_box_reminder').show();
		});
		
		// Hide reminder once the user has selected the option
		$('#popup_domination_tab_htmlform #popup_domination_name_box').focus(function(){
			$('#popup_domination_tab_htmlform #name_box_reminder').hide();
		});
		$('#popup_domination_tab_htmlform #popup_domination_email_box').focus(function(){
			$('#popup_domination_tab_htmlform #email_box_reminder').hide();
		});
		
		
		
		/* Fill in the new options from the HTML form code */
		function fill_options(){
			var options = '';
			$('#popup-domination-html-hidden-form input[type="text"], #popup-domination-html-hidden-form input[type="email"]').each(function(){
				var name = $(this).attr('name');
				options += '<option value="'+name+'">'+name+'</option>';
			});
			var custom_fields = '';
			var i = 1;
			var numfields = 0;
			$('#custom_select option').each(function(){
				numfields = $(this).val();
			});
			for (; i <= numfields; i++){
				custom_fields += ', #form_custom_fields #custom'+i;
			}
			$('#popup_domination_tab_htmlform #popup_domination_name_box, #popup_domination_tab_htmlform #popup_domination_email_box'+custom_fields).each(function(){
				$(this).html(options);
			});
			$('#popup_domination_tab_htmlform #popup_domination_name_box').val($('#popup_domination_tab_htmlform #popup_domination_name_box_selected').val());
			$('#popup_domination_tab_htmlform #popup_domination_email_box').val($('#popup_domination_tab_htmlform #popup_domination_email_box_selected').val());
			var i = 1;
			for (; i <= numfields; i++){
				$('#form_custom_fields #custom'+i).val($('#form_custom_fields #custom'+i+'_selected').val());
			}
		}
		
		
		
		/* Create the new hidden HTML form code - Possibly add errors depending if some data is empty -> action="" */
		function createForm() {
			var fields = "";
			$('#popup-domination-html-hidden-form input[type="text"], #popup-domination-html-hidden-form input[type="email"], #popup-domination-html-hidden-form input[type="hidden"]').each(function(){
				var type = $(this).attr('type');
				var name = $(this).attr('name');
				var value = $(this).attr('value');
				fields += '<input type="'+type+'" value="'+value+'" name="'+name+'" />';
			});
			
			//set action
			var action = $('#popup-domination-html-hidden-form form').attr('action');
			if(!action)
			{
    			action = 'Unable to get form action, please paste';
			}
			$('#popup_domination_tab_htmlform #popup_domination_action').val(action);

			fill_options();
			hidden_fields();
			
			//print form
			var form = '<form method="post" action="'+action+'" target="_blank" >';
			form += fields;
			form += '<input type="submit" value="Submit" /></form>';
			return form;
		}
		
		function hidden_fields(){
			var hidden_fields = '';
			$('#popup-domination-html-hidden-form input[type="hidden"]').each(function(){
				var name = $(this).attr('name');
				var value = $(this).val();
				hidden_fields += '<input name="'+name+'" type="hidden" value="'+value+'" />';
			});
			$('#hidden-fields').val(hidden_fields);
		}
	});
})(jQuery);