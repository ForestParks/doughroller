function addNewCompetitorField(){
	if ( document.getElementById('competitor-list') ) {
		var $field_holder = document.getElementById('competitor-list');
		var $template_field = $field_holder.getElementsByTagName('tr')[0].cloneNode(true);

		//reset the values
		for(var $i = 0; $i < $template_field.getElementsByTagName('input').length; $i++){
			$template_field.getElementsByTagName('input')[$i].value = '';
		}

		$remove_link = document.createElement('a');
		$remove_link.setAttribute('href','Javascript:void(0);');
		$remove_link.appendChild(document.createTextNode('Remove This Field'));		
				
		$remove_span = document.createElement('span');
		$remove_span.appendChild($remove_link);
		$template_field.appendChild($remove_span);

		var $new_field_link = document.getElementById('new_field_link');
		var $controls = document.getElementById('wizzard_controls');
		
		$field_holder.appendChild( $template_field );
		$field_holder.appendChild( $new_field_link );
		
		//add it after adding to the document to make sure the event works
		$remove_link.onclick = function(){ this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode); };
	}
}
function addNewCompetitorField2() {
	$("table#competitor-list tr:first")
		.clone()
		.appendTo("table#competitor-list tbody");
	
	$("table#competitor-list tr:last td input").val('');
	
	$('<span><a href="javascript:void(0);" class="remove-link"><img src="http://test.quero.lcl/KimPress/wp-content/plugins/positioner/images/remove.png" alt="Remove" align="absbottom" /></a></span>').insertAfter("table#competitor-list tr:last td input").click();
	//$("a").append("table#competitor-list tr:last td input");
	
	$("table#competitor-list tr:last td span a").click(function() {
		$("table#competitor-list tr:last").remove();
	});
	/*$("table#competitor-list tr:last").click(function() {
		$(this).remove();
	});*/	
}