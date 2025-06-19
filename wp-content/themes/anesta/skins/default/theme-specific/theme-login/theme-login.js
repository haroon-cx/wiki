/* global jQuery:false */
/* global ANESTA_STORAGE:false */


( function() {

	"use strict";


	jQuery('.loginmmessage .copy').each(function(){
		jQuery('<span class="icon"></span>').appendTo(jQuery(this));
	});

	if( navigator.clipboard ) {
		jQuery('.loginmmessage .copy').on('click', function() {
			var item = jQuery(this);
			var text = item.text();
			navigator.clipboard.writeText(text);

			item.addClass('copied');
			setTimeout(function() {
				item.removeClass('copied');
			}, 3000);
		});
	}
})();
