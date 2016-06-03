jQuery(document).ready(function() {
	
	// image upload
	jQuery('#upload_image_button').click(function() {
	
		window.restore_send_to_editor = window.send_to_editor; // store old send to editor function
		formfield = jQuery('#ta_post_review_image').attr('name');
		var pid = jQuery('#post_ID').val();
		tb_show('Upload', 'media-upload.php?&post_id='+pid+'&type=image&TB_iframe=true');

		// redefine send to editor function
		window.send_to_editor = function(html) {
			imgurl = jQuery('img',html).attr('src');
			jQuery('#ta_post_review_image').val(imgurl);
			tb_remove();

			// restore original send to editor function
			window.send_to_editor = window.restore_send_to_editor;
		}
		return false;
	});
});