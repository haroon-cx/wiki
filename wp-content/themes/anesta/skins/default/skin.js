/* global jQuery:false */
/* global ANESTA_STORAGE:false */

( function() {
	"use strict";

	var $window   = jQuery( window ),
		$document = jQuery( document ),
		$html = jQuery('html'),
		$body = jQuery('body'),
		$header = jQuery('.top_panel'),
		$footer = jQuery('.footer_wrap'),
		$content_wrap = jQuery('.content_wrap'),
		$menu_mobile = jQuery('.menu_mobile'),
		$color_switcher = jQuery('#color_scheme_switcher'),
		header_height = 0,
		footer_height = 0,
		top_panel_height = 0,
		fixed_height = 0,
		header_resize = 0;



	/* Init
	-----------------------------------------------------------------*/
	// Page blocks
	anesta_skin_page_content_blocks_init();

	// Side (mobile) menu
	anesta_skin_menu_side_init();

	// Posts order
	anesta_skin_posts_order_init();

	// Radio & Checkbox
	anesta_skin_radio_checkbox_init();

	// Page content switcher
	anesta_skin_content_switcher_init();

	// Color switcher
	anesta_skin_color_switcher_init();

	// Search init
	anesta_skin_search_init();

	// Single post
	anesta_skin_single_post_init();



	/* Load
	-----------------------------------------------------------------*/
	$window.on( 'load', function() {
		// Gutenberg
		anesta_skin_gutenberg_init();

		// Page blocks
		anesta_skin_page_content_blocks_init();
	});



	/* Ready
	-----------------------------------------------------------------*/
	$document.on( 'ready', function() {
		$window.trigger( 'resize' );
	});



	/* Resize
	-----------------------------------------------------------------*/
	$window.on( 'resize', function() {
		// Page width
		anesta_skin_body_width_init();

		// Close menu side
		anesta_skin_menu_side_resize();

		// Header height
		anesta_skin_header_height_init();

		// Footer height
		anesta_skin_footer_height_init();
		
		// Fixed row position on mobile
		anesta_skin_fixed_row_mobile_init();

		// Search init
		anesta_skin_search_init();

		// Single post
		anesta_skin_single_post_init();

		if ( jQuery('.post_layout_excerpt').length > 0 ) {			
			setTimeout(function() {
				// Blog item height
				anesta_skin_blog_item_height_init();

				$window.trigger( 'scroll' );
			}, 100);
		}
	});



	/* Scroll
	-----------------------------------------------------------------*/
	$window.on( 'scroll', function() {
		// Fixed row position on mobile
		anesta_skin_fixed_row_mobile_init();
	});



	/* AJAX and hidden elements
	-----------------------------------------------------------------*/
	$document.on( 'action.init_hidden_elements', function() {
		// Page blocks
		anesta_skin_page_content_blocks_init();

		// Blog item height
		anesta_skin_blog_item_height_init();

		// Audio
		if ( $body.hasClass('elementor-editor-active') ) {
			anesta_skin_audio_init();
		}
	});



	/* Elementor editor
	-----------------------------------------------------------------*/
	$window.on( 'elementor/frontend/init', function() {
		if ( typeof window.elementorFrontend !== 'undefined' && typeof window.elementorFrontend.hooks !== 'undefined' ) {
			// If Elementor is in the Editor's Preview mode
			if ( elementorFrontend.isEditMode() ) {
				// Init elements after creation
				elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function( $cont ) {		
					// Page width
					anesta_skin_body_width_init();

					// Page blocks
					anesta_skin_page_content_blocks_init();

					// Radio & Checkbox
					anesta_skin_radio_checkbox_init();	

					// Blog item height
					anesta_skin_blog_item_height_init();

					// Audio
					anesta_skin_audio_init();
				});
			}
		}
	});



	// Return Gutenberg editor object
	function anesta_skin_gutenberg_editor_object() {
		// Get Post Editor
		var $editor = jQuery( '.edit-post-visual-editor:not(.anesta_inited2)' ).eq( 0 );
		if ( ! $editor.length ) {
			// Check if Full Site Editor exists
			var $editor_frame = jQuery( 'iframe[name="editor-canvas"]' );
			if ( $editor_frame.length ) {
				$editor_frame = jQuery( $editor_frame.get(0).contentDocument.body );
				if ( $editor_frame.hasClass('editor-styles-wrapper') && ! $editor_frame.hasClass('anesta_inited2') ) {
					$editor = $editor_frame;
				}
			} else {
				// Check if Widgets Editor exists
				$editor = jQuery( '.edit-widgets-block-editor:not(.anesta_inited2)' ).eq( 0 );
			}
		}
		return $editor;
	}

	// Gutenberg
	function anesta_skin_gutenberg_init() {
		// Get Gutenberg editor object
		var $editor = anesta_skin_gutenberg_editor_object();
		if ( ! $editor.length ) {
			return;
		}

		var old_GB = $editor.hasClass( 'editor-styles-wrapper' ) && $editor.hasClass( 'edit-post-visual-editor' ),
			styles_wrapper  = old_GB || $editor.hasClass( 'editor-styles-wrapper' )
								? $editor
								: $editor.find( '.editor-styles-wrapper' );

		styles_wrapper.addClass( 'page_content_' + ANESTA_STORAGE['page_content'] );
		styles_wrapper.find('.is-root-container').addClass('content');

		$editor.addClass('anesta_inited2');
	}

	// Page blocks
	function anesta_skin_page_content_blocks_init() {
		var content = jQuery('body.sidebar_show [class*="content_wrap"] > .content,\
							   body:not(.sidebar_show) .page_content_wrap > [class*="content_wrap"],\
							   .is-root-container');

		content.find('.widget_title, .widget_subtitle').each(function(){
			jQuery(this).parent('.widget').addClass('is_block');	
		});

		content.find('.slider_outer_pagination, .slider_outer_controls').each(function(){
			jQuery(this).parents('.sc_testimonials').addClass('is_block');	
		});

		content.find('.sc_item_title, .sc_item_button').each(function(){
			jQuery(this).parent().addClass('is_block');	
		});
	}

	// Side (mobile) menu
	function anesta_skin_menu_side_init() {
		if ( $body.hasClass('menu_side_present') ) {
			// Close menu side
			anesta_skin_menu_side_resize();

			// Menu side actions on the mouse enter
			$menu_mobile.find('.menu_mobile_nav_area').on( 'mouseenter', function() {	
				if ( !$menu_mobile.hasClass('is_opened') && !$menu_mobile.hasClass('is_hovered') ) {
					$menu_mobile.addClass('is_hovered');
				}	
			});				

			// Menu side actions on the mouse leave
			$menu_mobile.on( 'mouseleave', function() {	
				if ( !$menu_mobile.hasClass('is_opened') && $menu_mobile.hasClass('is_hovered') ) {
					$menu_mobile.removeClass('is_hovered');
				}								
			});

			// Menu side actions on the 'menu' button click
			$menu_mobile.find('.menu_mobile_close').on( 'click', function(e) {
				$menu_mobile.toggleClass('is_opened').removeClass('is_hovered');
				$body.toggleClass('menu_mobile_is_opened');
				
				$document.trigger( 'action.init_hidden_elements', [$body.eq(0)] );
				
				if ( $window.width() >= 1679 ) {
					setTimeout(function() {
						$window.trigger( 'resize' );
					}, 300);
				}
			});
		} else {			
			// Menu mobile actions on the 'menu' button click
			jQuery('.sc_layouts_menu_mobile_button a').on( 'click', function(e) {
				if ( $menu_mobile.hasClass('opened') ) {
					$menu_mobile.find('.menu_mobile_close').click();
				}
			});
		}
	}

	// Side (mobile) menu
	function anesta_skin_menu_side_resize() {
		if ( $body.hasClass('menu_side_present') ) {
			// Close menu side
			if ( $window.width() < 1679 ) {
				$menu_mobile.removeClass('is_opened').removeClass('is_hovered').addClass('inited');
				$body.removeClass('menu_mobile_is_opened');
			}
		}
	}

	// Posts order
	function anesta_skin_posts_order_init() {
		if ( jQuery('.posts_sorting').length > 0 ) {
			jQuery('.posts_sorting select[name="posts_order"]').on('change', function(){
				jQuery(this).parents().submit();
			})
		}
	}

	// Radio & Checkbox
	function anesta_skin_radio_checkbox_init() { 
		/* Radio */
		jQuery('label > input[type="radio"]').each(function() {
			var item = this;
			jQuery(item).parent().addClass('radio_label');
			anesta_skin_radio_checkbox_change(item);

			jQuery(item).change(function() {
				var name = jQuery(item).attr('name');
				jQuery('input[type="radio"][name="' + name + '"]').parent().removeClass('checked');
			    anesta_skin_radio_checkbox_change(item);
			});
		});

		/* Checkbox */
		jQuery('label > input[type="checkbox"]').each(function() {
			var item = this;
			jQuery(item).parent().addClass('checkbox_label');
			anesta_skin_radio_checkbox_change(item);

			jQuery(item).change(function() {
			    anesta_skin_radio_checkbox_change(item);
			});
		});
	}

	function anesta_skin_radio_checkbox_change(item) { 
		if ( item.checked ) {
	        jQuery(item).parent().addClass('checked');  
	    } else {
	        jQuery(item).parent().removeClass('checked');  
	    }
	}

	// Page content
	function anesta_skin_content_switcher_init() {
		jQuery('#page_content_switcher').on('click', function(){
			$body.toggleClass('page_content_blocks').toggleClass('page_content_classic');

			$document.trigger( 'action.init_hidden_elements', [$body.eq(0)] );
			$window.trigger( 'resize' );
		});	
	}

	// Color switcher
	function anesta_skin_color_switcher_init() {
		if ( $color_switcher.length > 0 ) {
			// Change color scheme after page load
			var site_color_scheme = anesta_get_cookie('anesta_color_scheme');
			if ( site_color_scheme != null ) {		
				anesta_skin_remove_color_scheme_class();
				$body.addClass('scheme_' + site_color_scheme);
				$color_switcher.find('li[data-value="' + site_color_scheme + '"]').addClass('selected');
				anesta_skin_invert_logo_colors(site_color_scheme);
			}

			// Open color schemes
			$color_switcher.on('click', function(){
				jQuery(this).toggleClass('opened');
			});

			// Change color scheme on the button click
			$color_switcher.find('li').on('click', function() {
				var val = jQuery(this).attr('data-value');
				anesta_skin_remove_color_scheme_class();
				$body.addClass('scheme_' + val);
				$color_switcher.removeClass('opened');
				anesta_set_cookie( 'anesta_color_scheme', val, { expires: 365, path: '/' });
				anesta_skin_invert_logo_colors(val);
			});
		}
	}

	// Remove body color scheme class
	function anesta_skin_remove_color_scheme_class() {
		$color_switcher.find('li').each(function(){
			var val = jQuery(this).attr('data-value');
			$body.removeClass('scheme_' + val);
		});
	}

	// Invert logo colors
	function anesta_skin_invert_logo_colors(site_color_scheme) {
		if ( $color_switcher.hasClass('invert') ) {
			if ( site_color_scheme == 'dark') {
				jQuery('body.scheme_dark').find('header:not([class*="scheme_"]), .menu_mobile:not([class*="scheme_"]), footer:not([class*="scheme_"]), #trx_addons_login_popup').find('.logo_image, .sc_layouts_logo').addClass('invert');
			} else {
				jQuery('body.scheme_dark').find('header:not([class*="scheme_"]), .menu_mobile:not([class*="scheme_"]), footer:not([class*="scheme_"]), #trx_addons_login_popup').find('.logo_image, .sc_layouts_logo').removeClass('invert');
			}
		}
	}

	// Page width
	function anesta_skin_body_width_init() { 
		var cont = jQuery( 'body.menu_side_present,\
							body.menu_side_present.menu_side_left .sc_layouts_row_fixed,\
							body.menu_side_present .menu_mobile,\
							body.menu_side_present .menu_mobile .menu_mobile_top_panel,\
							body.menu_side_present .menu_mobile .menu_mobile_close,\
							body.menu_side_present .top_panel .elementor-section > .elementor-container,\
							body.menu_side_present .content_wrap,\
							body.menu_side_present .content_wrap > .content,\
							body.menu_side_present .content_wrap > .sidebar,\
							body.menu_side_present .content_container,\
							body.menu_side_present .content_container > .content,\
							body.menu_side_present .content_container > .sidebar');
		cont.addClass('no_anim');

		document.querySelector('html').style.setProperty( '--theme-var-body', $window.width() + 'px' );

		setTimeout(function() {
			cont.removeClass('no_anim');
		}, 100);
	}

	// Header height
	function anesta_skin_header_height_init() { 
		for( var i = 0; i < 3; i++ ) {
			setTimeout(function() {
				anesta_skin_header_height_resize();
			}, 50);
		}
	}

	function anesta_skin_header_height_resize() { 
		header_height = $header.length === 0 ? 0 : $header.outerHeight();
		if (header_height < 1) {
			header_height = $header.find('.sc_layouts_row_fixed').outerHeight();
		}
		document.querySelector('html').style.setProperty( '--theme-var-header', header_height + 'px' );

		// Calculate a fixed row height
		var fixed_placeholder = jQuery('body.menu_side_present .sc_layouts_row_fixed:not(.sc_layouts_row_hide_unfixed) + .sc_layouts_row_fixed_placeholder');
		var fixed_height = fixed_placeholder.length === 0 ? 0 : fixed_placeholder.prev().outerHeight();
		fixed_placeholder.height(fixed_height);  
	}

	// Footer height
	function anesta_skin_footer_height_init() { 
		footer_height = $footer.length === 0 ? 0 : $footer.outerHeight();
		document.querySelector('html').style.setProperty( '--theme-var-footer', footer_height + 'px' );
	}

	// Fixed row position on mobile
	function anesta_skin_fixed_row_mobile_init() {
		if ( $body.hasClass('menu_side_present') && jQuery('.top_panel').length > 0 && jQuery('.sc_layouts_row_fixed').length > 0 ) {
			if ( $body.hasClass('admin-bar') && $body.hasClass('mobile_layout') ) {
				if ( $body.width() > 600 ) { 
					jQuery('body.admin-bar .menu_mobile,\
							body.admin-bar .sc_layouts_row_fixed').css('transform', 'none');
				} else {
					var yOffset = $window.scrollTop(); 
					if ( yOffset > 46) {
						jQuery('body.admin-bar .menu_mobile,\
								body.admin-bar .sc_layouts_row_fixed').css('transform', 'translateY(0px)');
					} else {
						var off = 46 - yOffset;
						jQuery('body.admin-bar .menu_mobile,\
								body.admin-bar .sc_layouts_row_fixed').css('transform', 'translateY(' + off + 'px)');
					}
				}
			}
		}
	}

	// Blog item minimal height
	function anesta_skin_blog_item_height_init() {
		var masonry_array = [];

		jQuery('.masonry_wrap').each(function() {
			var wrap = jQuery(this);
			if ( wrap.find('.post_layout_excerpt.format-video, .post_layout_excerpt.format-audio').length > 0 ) {
				masonry_array.push(wrap);
			}
		});

		jQuery('.post_layout_excerpt.format-video, .post_layout_excerpt.format-audio').each(function() {
			var item = jQuery(this);

			anesta_when_images_loaded( item, function() {		
				item.find('.post_featured').css('min-height', 'unset');
				item.find('.post_featured > img').removeClass('cover_image');

				var item_height = Math.ceil(item.outerHeight());
				var info = item.find('.post_featured .post_info');
				var info_height = Math.ceil(info.outerHeight());

				if ( info_height > item_height ) {
					item.find('.post_featured').css('min-height', info_height + 'px');
					item.find('.post_featured > img').addClass('cover_image');
				}
			});		
		});

		setTimeout(function() {
			for( var i = 0; i < masonry_array.length; i++ ) {
				masonry_array[i].masonry(); 
			}
		}, 100);
	}

	// Audio
	function anesta_skin_audio_init() {
		jQuery('.audio_frame, .audio_wrap, .wp-widget-media_audio').each(function() {
			var audio = jQuery(this);
			var html = audio.html().replaceAll('mejs__', 'mejs-');
			audio.html(html);
		});
	}

	// Search init
	function anesta_skin_search_init() {
		if ( $window.width() < 1024 ) {
			jQuery('.search_field[data-mobile-placeholder]').each(function() {
				var search = jQuery(this);

				if ( !search.hasClass('mobile_placeholder') ) {
					var placeholder = search.attr('placeholder');
					search.attr('desc-placeholder', placeholder);

					var mobile_placeholder = search.attr('data-mobile-placeholder');
					search.attr('placeholder', mobile_placeholder);

					search.addClass('mobile_placeholder');
				}
			});
		} else {
			jQuery('.search_field[data-mobile-placeholder]').each(function() {
				var search = jQuery(this);

				if ( search.hasClass('mobile_placeholder') ) {
					var desc_placeholder = search.attr('desc-placeholder');
					search.attr('placeholder', desc_placeholder);

					search.removeClass('mobile_placeholder');
				}
			});
		}

		jQuery('.search_style_fullscreen.search_opened .search_close').click();
	}

	// Single post
	function anesta_skin_single_post_init() {
		jQuery('.sidebar_show.single_style_style-1 .post_header_wrap .post_featured.post_featured_bg + .post_header').each(function() {
			var header = jQuery(this);
			var header_height = header.outerHeight();
			var featured = header.prev();
			featured.css('min-height', 'calc(' + header_height + 'px + 2 * ( var(--theme-var-grid_gap) + ( var(--theme-var-grid_gap_koef) * 15px )))');
		});
	}
})();