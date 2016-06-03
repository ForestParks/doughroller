$j(function(){
	$j('.pty_content_image').hide();
	ptyb.init();
});

ptyb = {
	init: function() {
		$j('.pty_add_contentArea').live('click', ptyb.addContentArea);
		$j('.theme_content_type').live('change', ptyb.contentTypeChange);
	},
	addContentArea: function(){
		$j('#pty_contentAreas')
			.append($j('.pty_contentArea').first().clone())
			.css('width', ($j('.pty_contentArea').length * 300) + 'px' );
		return false;
	},
	contentTypeChange: function(){
		var val = $j(this).val();
		if (val == 'image') {
			$j('.pty_content_text', $j(this).parent()).hide();
			$j('.pty_content_image', $j(this).parent()).show();
		}
		else {
			$j('.pty_content_text', $j(this).parent()).show();
			$j('.pty_content_image', $j(this).parent()).hide();
		}
	},
	__end: true
}