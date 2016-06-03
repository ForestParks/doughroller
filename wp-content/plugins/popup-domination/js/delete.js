;(function($){

	$(document).ready(function(){
		$('body').on('click','.thedeletebutton', function(){
			if(confirm('You are about to DELETE a campaign, are you sure?')){
				var id = $(this).attr('data-id');
				ajax_delete(id, popup_domination_delete_table, popup_domination_delete_stats);
			}
			return false;
		});

	});
	
	function ajax_delete(id, table, stats){
		var data = {
			action: 'popup_domination_delete',
			table: table,
			stats: stats,
			id: id
		};
		jQuery.post(popup_domination_admin_ajax, data, function(response) {
			$('#camprow_'+id).fadeOut();
			var value = $('#row_count').text();
			value = parseInt(value);
			$('#row_count').text(value-1);
		}).error(function(){
			alert("There was a problem with deleting this from the database.");
		});
	}
	
})(jQuery);