/* global jQuery:false */
/* global ANESTA_STORAGE:false */

( function() {
	"use strict";

	var $window   = jQuery( window ),
		$document = jQuery( document ),
		$body = jQuery('body');



	/* Init
	-----------------------------------------------------------------*/
	anesta_add_filter('anesta_filter_mediaelements_audio_selector', function(elem) {
		elem = elem + ':not([data-trx-lazyload-src])';
		return elem;
	});
	anesta_add_filter('anesta_filter_mediaelements_video_selector', function(elem) {
		elem = elem + ':not([data-trx-lazyload-src])';
		return elem;
	});



	/* Resize
	-----------------------------------------------------------------*/
	$window.on( 'resize', function() {
		setTimeout(function() {
			// Blogger Modern item minimal height
			anesta_trx_addons_blog_modern_item_height_init();

			// Blogger Over item minimal height
			anesta_trx_addons_blog_over_item_height_init();
		}, 100);
	});

	$document.on( 'action.init_resize_elements', function(e) {
		setTimeout(function() {
			// Skills resize
			$document.trigger( 'action.resize_trx_addons' );
		}, 300);
	});



	/* AJAX and hidden elements
	-----------------------------------------------------------------*/
	$document.on( 'action.init_hidden_elements', function() {
		// Blogger Modern item minimal height
		anesta_trx_addons_blog_modern_item_height_init();

		// Blogger Over item minimal height
		anesta_trx_addons_blog_over_item_height_init();
	});



	/* Elementor editor
	-----------------------------------------------------------------*/
	$window.on( 'elementor/frontend/init', function() {
		if ( typeof window.elementorFrontend !== 'undefined' && typeof window.elementorFrontend.hooks !== 'undefined' ) {
			// If Elementor is in the Editor's Preview mode
			if ( elementorFrontend.isEditMode() ) {
				// Init elements after creation
				elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function( $cont ) {		
					// Blogger Modern item minimal height
					anesta_trx_addons_blog_modern_item_height_init();

					// Blogger Over item minimal height
					anesta_trx_addons_blog_over_item_height_init();
				});
			}
		}
	});



	/* LazyLoad
	-----------------------------------------------------------------*/
	$document.on( 'action.after_lazy_load_media', function(e) {
		if ( jQuery('audio.lazyload_inited').length > 0 || jQuery('video.lazyload_inited').length > 0 ) {
			$document.trigger( 'action.init_hidden_elements', [$body.eq(0)] );
		}		
	});
	$document.on( 'action.init_lazy_load_elements', function(e) {
		$document.trigger( 'action.got_ajax_response', [$body.eq(0)] );		
	});

	

	// Blogger Modern item minimal height
	function anesta_trx_addons_blog_modern_item_height_init() {
		var masonry_array = [];

		jQuery('.masonry_wrap').each(function() {
			var wrap = jQuery(this);
			if ( wrap.find('.sc_blogger_item_default_modern.format-gallery, .sc_blogger_item_default_modern.format-audio, .sc_blogger_item_default_modern.format-video').length > 0 ) {
				masonry_array.push(wrap);
			}
		});

		jQuery('.sc_blogger_item_default_modern.format-gallery, .sc_blogger_item_default_modern.format-audio, .sc_blogger_item_default_modern.format-video').find('.sc_blogger_item_body').each(function(){
			var item = jQuery(this);
			item.css('min-height', 'unset');

			if ( $window.width() >= 1280 ) {
				var width = item.outerWidth();
				item.css('min-height', width);		
			}	
		});

		setTimeout(function() {
			for( var i = 0; i < masonry_array.length; i++ ) {
				masonry_array[i].masonry(); 
			}
		}, 100);
	}

	// Blogger Over item minimal height
	function anesta_trx_addons_blog_over_item_height_init() {
		var masonry_array = [];

		jQuery('.masonry_wrap').each(function() {
			var wrap = jQuery(this);
			if ( wrap.find('.sc_blogger_item_default_over .sc_blogger_item_body').length > 0 ) {
				masonry_array.push(wrap);
			}
		});

		jQuery('.sc_blogger_item_default_over .sc_blogger_item_body').each(function() {
			var item = jQuery(this);

			anesta_when_images_loaded( item, function() {
				item.find('.sc_item_featured').css('min-height', 'unset');
				item.find('.sc_item_featured > img').removeClass('cover_image');

				var item_height = item.outerHeight();
				var info = item.find('.sc_item_featured [class*="post_info"]');
				var info_height = Math.ceil(info.outerHeight());

				if ( info_height > item_height  ) {
					item.find('.sc_item_featured').css('min-height', info_height + 'px');
					item.find('.sc_item_featured > img').addClass('cover_image');

					if ( item.parents('.format-gallery').length > 0 ) {
						item.find('.slider_swiper').css('min-height', info_height);
					}
				}
			});			
		});	

		setTimeout(function() {
			for( var i = 0; i < masonry_array.length; i++ ) {
				masonry_array[i].masonry(); 
			}
		}, 100);
	}
})();
