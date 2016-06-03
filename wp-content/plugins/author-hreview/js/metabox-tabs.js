jQuery(document).ready(function() {
	// tab between them
	jQuery('.wpar-metabox-tabs li.wpar-tab a').each(function(i) {
		var thisTab = jQuery(this).parent().attr('class').replace(/wpar-tab /, '');
		if ( 'active' != jQuery(this).attr('class') )
			jQuery('div.' + thisTab).hide();
 
		jQuery(this).click(function(){
 
			// hide all child content
			jQuery(this).parent().parent().parent().children('div').hide();
 
			// remove all active tabs
			jQuery(this).parent().parent('ul').find('li a.active').removeClass('active');
 
			// show selected content
			jQuery(this).parent().parent().parent().find('div.'+thisTab).show();
			jQuery(this).parent().parent().parent().find('li.'+thisTab+' a').addClass('active');
		});

		jQuery('.heading').hide();
		jQuery('.wpar-metabox-tabs').show();
	});
});