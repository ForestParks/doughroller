;(function($){

	$(document).ready(function(){
		$('body').on('click','.copy_button', function(){
			var name = $(this).attr('title');
			if(confirm('Are you sure you wish to copy "'+name+'"?')){
				var id = $(this).attr('data-id');
				copy_record(id, popup_domination_delete_table);
			}
			return false;
		});

	});
	
	function copy_record(id, table){
		var data = {
			action: 'popup_domination_copy',
			table: table,
			id: id
		};
		jQuery.post(popup_domination_admin_ajax, data, function(response) {
			$('#popup_domination_campaign_list').append(response);
			var value = $('#row_count').text();
			value = parseInt(value);
			$('#row_count').text(value+1);
		}).error(function(){
			alert("There was a problem copying this campaign.");
		});
	}	
})(jQuery);