;(function($){

	$(document).ready(function(){
		$('body').on('click','.toggle_button', function(){
			  $("*").css("cursor","wait");

				var id = $(this).attr('data-id');
				toggle(id, popup_domination_delete_table);
				return false;
		});
	});
	
	function toggle(id, table){
		var data = {
			action: 'popup_domination_toggle',
			table: table,
			id: id
		};
		jQuery.post(popup_domination_admin_ajax, data, function(response) {
			if (response == 1){
				$('#camprow_'+id+' .toggle_button').html("<span style='color:silver'>ON</span> | OFF").addClass('on').removeClass('off');
			} else {
				$('#camprow_'+id+' .toggle_button').html("ON | <span style='color:silver'>OFF</span>").addClass('off').removeClass('on');
			}
			$("*").css("cursor","auto");
		}).error(function(){
			alert("There was a problem with turning this campaign off within the database.");
		});
	}
})(jQuery);