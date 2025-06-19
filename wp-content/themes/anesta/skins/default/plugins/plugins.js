/* global jQuery:false */
/* global ANESTA_STORAGE:false */

( function() {
	"use strict";

	var $window   = jQuery( window ),
		$document = jQuery( document ),
		$html = jQuery('html'),
		$body = jQuery('body');



	/* Init
	-----------------------------------------------------------------*/
	// BBPress and BuddyPress
	anesta_bbpress_init();

	// BuddyPress Docs
	anesta_bp_docs_init();

	// Knowledge Base
	anesta_epkb_init();

	// LearnDash LMS
	anesta_sfwd_lms_init();

	// LearnPress
	anesta_learnpress_init();

	// WP Job Manager
	anesta_job_manager_init();

	// Paid Memberships Pro
	anesta_memberships_init();

	// Elementor
	anesta_elementor_init();

	// WooCommerce
	anesta_woocommerce_init();



	/* Ready
	-----------------------------------------------------------------*/
	$document.on( 'action.ready_anesta', function() {
		// Process Tribe Events view after it was reloaded by AJAX
		jQuery('.tribe-events-view').on( 'beforeAjaxComplete.tribeEvents beforeAjaxSuccess.tribeEvents beforeAjaxError.tribeEvents', anesta_tribe_events_init );
	});



	/* Resize
	-----------------------------------------------------------------*/
	$window.on( 'resize', function() {
		// BBPress and BuddyPress
		anesta_bbpress_resize();
	});



	/* Elementor editor
	-----------------------------------------------------------------*/
	$window.on( 'elementor/frontend/init', function() {
		if ( typeof window.elementorFrontend !== 'undefined' && typeof window.elementorFrontend.hooks !== 'undefined' ) {
			// If Elementor is in the Editor's Preview mode
			if ( elementorFrontend.isEditMode() ) {
				// Init elements after creation
				elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function( $cont ) {		
					// BBPress and BuddyPress
					anesta_bbpress_init();

					// BuddyPress Docs
					anesta_bp_docs_init();

					// Knowledge Base
					anesta_epkb_init();

					// LearnDash LMS
					anesta_sfwd_lms_init();

					// LearnPress
					anesta_learnpress_init();

					// WP Job Manager
					anesta_job_manager_init();

					// Paid Memberships Pro
					anesta_memberships_init();

					// Elementor
					anesta_elementor_init();
				});
			}
		}
	});



	/* Google Map
	-----------------------------------------------------------------*/
	// Add new style 'Extra' to the Google maps
	$document.on('action.add_googlemap_styles', function(e) {
		if (typeof TRX_ADDONS_STORAGE == 'undefined') return;
		TRX_ADDONS_STORAGE['googlemap_styles']['extra'] = [{"featureType": "water", "elementType": "geometry", "stylers": [{"color": "#e9e9e9"}, {"lightness": 17}]}, {"featureType": "landscape", "elementType": "geometry", "stylers": [{"color": "#f5f5f5"}, {"lightness": 20}]}, {"featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{"color": "#ffffff"}, {"lightness": 17}]}, {"featureType": "road.highway", "elementType": "geometry.stroke", "stylers": [{"color": "#ffffff"}, {"lightness": 29}, {"weight": 0.2}]}, {"featureType": "road.arterial", "elementType": "geometry", "stylers": [{"color": "#ffffff"}, {"lightness": 18}]}, {"featureType": "road.local", "elementType": "geometry", "stylers": [{"color": "#ffffff"}, {"lightness": 16}]}, {"featureType": "poi", "elementType": "geometry", "stylers": [{"color": "#f5f5f5"}, {"lightness": 21}]}, {"featureType": "poi.park", "elementType": "geometry", "stylers": [{"color": "#dedede"}, {"lightness": 21}]}, {"elementType": "labels.text.stroke", "stylers": [{"visibility": "on"}, {"color": "#ffffff"}, {"lightness": 16}]}, {"elementType": "labels.text.fill", "stylers": [{"saturation": 36}, {"color": "#333333"}, {"lightness": 40}]}, {"elementType": "labels.icon", "stylers": [{"visibility": "off"}]}, {"featureType": "transit", "elementType": "geometry", "stylers": [{"color": "#f2f2f2"}, {"lightness": 19}]}, {"featureType": "administrative", "elementType": "geometry.fill", "stylers": [{"color": "#fefefe"}, {"lightness": 20}]}, {"featureType": "administrative", "elementType": "geometry.stroke", "stylers": [{"color": "#fefefe"}, {"lightness": 17}, {"weight": 1.2}]}];
	} );
	


	// BBPress and BuddyPress 
	function anesta_bbpress_init() {
		// Widget Topics
		jQuery('.widget_display_topics,\
			.wp-widget-bbp_topics_widget').find('.bbp-author-avatar').parents('.widget').addClass('with_author');

		// Widget Recent Activity
		jQuery('.widget_bp_core_recently_active_widget,\
			.wp-widget-bp_core_recently_active_widget').find('.widget_title .sc_button_wrap').each(function(){
			var btn = jQuery(this);
			if ( btn.parents('.widget').find('.avatar-block').length > 0 ) {
				btn.insertAfter(btn.parents('.widget').find('.avatar-block'));
			} else {
				btn.insertAfter(btn.parents('.widget').find('.widget-error'));
			}
		});

		// Widget Members
		jQuery('.widget.widget_bp_core_members_widget,\
			.wp-widget-bp_core_members_widget').find('.widget_title .sc_button_wrap').each(function(){
			var btn = jQuery(this);
			btn.insertAfter(btn.parents('.widget').find('.item-list'));
		});

		// Widget Groups
		jQuery('.widget.widget_bp_groups_widget,\
			.wp-widget-bp_groups_widget').find('.widget_title .sc_button_wrap').each(function(){
			var btn = jQuery(this);
			btn.insertAfter(btn.parents('.widget').find('.item-list'));
		});

		// Widget Docs
		jQuery('.widget.widget_recent_bp_docs,\
			.wp-widget-widget_recent_bp_docs').find('.widget_title .sc_button_wrap').each(function(){
			var btn = jQuery(this);
			btn.insertAfter(btn.parents('.widget').find('ul'));
		});

		// Profile header
		jQuery('#item-header-content').each(function(){
			var header = jQuery(this).append('<div class="user-meta"></div>');
			header.find('.user-nicename').appendTo(header.find('.user-meta'));
			header.find('.highlight').appendTo(header.find('.user-meta'));
			header.find('.activity').appendTo(header.find('.user-meta'));

			// Group
			header.next('#item-actions').prependTo(header);
		});

		// Activity comments
		jQuery('.activity-item .activity-comments ul li').each(function(){
			jQuery(this).parents('.activity-item').addClass('has-comments')
		});

		// New Post form
		jQuery('#subnav + #whats-new-form').each(function(){
			var form = jQuery(this);
			form.insertBefore(form.prev());
		});

		// Activity load more
		jQuery('div.bpas-shortcode-activities').on('click', 'li.load-more', function() {
			setTimeout(function(){
				$document.trigger( 'action.init_hidden_elements', [$body.eq(0)] );
				$window.trigger( 'resize' );
			}, 3000);
	    }); 

		// Members buttons
		jQuery('#members-list > li .action .generic-button a').each(function(){
			var btn = jQuery(this);
			btn.attr('data-title', btn.text());
		});

		// Forums buttons
		jQuery('#subscription-toggle').each(function(){
			var btn = jQuery(this);
			var txt = btn.html().replace('&nbsp;|&nbsp;', '');
			btn.html(txt);
		});

		// Register & Login
		jQuery('#signup_form .error').each(function(){
			var error = jQuery(this);
			error.insertAfter(error.next().addClass('not-valid'));
		});
	}

	// BuddyPress Docs
	function anesta_bp_docs_init() {
		// Table wrap
		jQuery('.doctable').each(function(){
			jQuery(this).wrap('<div class="doctable_wrap"><div class="doctable_wrap_inner"></div></div>');
		});

		// Messages
		jQuery('.bp-template-notice + .doc-content').each(function(){
			jQuery(this).prev().prependTo(jQuery(this));
		});

		// Messages
		jQuery('.bp-docs .doc-meta').each(function(){
			var meta = jQuery(this);
			if ( meta.text().length == 4 ) {
				meta.hide();
			}
		});
	}

	// Knowledge Base
	function anesta_epkb_init() {
		// Remove all inline styles
		jQuery('.eckb-kb-template, .eckb-kb-template *').each(function(){
			jQuery(this).removeAttr('style');

			jQuery('.eckb-kb-template').css('opacity', 1);
		});
	}

	// LearnDash LMS
	function anesta_sfwd_lms_init() {
		jQuery('.post_content_title + .learndash-wrap #ld_categorydropdown,\
			.post_content_title + .learndash-wrap #ld_course_categorydropdown').each(function(){
			var drop = jQuery(this);
			drop.addClass('inside_title');
			drop.appendTo(drop.parent().prev().addClass('with_category'));
		});

		jQuery('.ld-content .ld-course-status').attr('id', 'ld-course-status');
	}

	// LearnPress
	function anesta_learnpress_init() {
		// Disable page scroll at lesson/quiz post type(LearnPress)
		if( jQuery('body').hasClass('course-item-lp_lesson') || jQuery('body').hasClass('course-item-lp_quiz') ) {
			jQuery('html').addClass('overflow-y-hidden');
		}

		// Wrap LearPress tables with .lp-responsive-table
		if (jQuery('#learn-press-profile .lp-list-table').length > 0) {
			jQuery('#learn-press-profile .lp-list-table').wrap( '<div class="lp-responsive-table"></div>' );
		}

		// LearPress course progress
		if (jQuery('.curriculum-sections .learn-press-progress').length > 0) {
			jQuery('.curriculum-sections .learn-press-progress').each(function () {
				const $progress = jQuery(this);
				const $active = $progress.find('.learn-press-progress__active');
				const value = $active.data('value');

				if (value === undefined) {
					return;
				}

				$active.css('left', -(100 - parseInt(value)) + '%');
			});
		}
	}

	// WP Job Manager
	function anesta_job_manager_init() {
		jQuery('.search_jobs input[type="submit"]').each(function(){
			var btn = jQuery(this);
			var txt = btn.attr('value').replace( 'Jobs', '' );
			btn.attr('value', txt);
		});

		jQuery('#submit-resume-form input[type="submit"], #resume_preview input[type="submit"]').each(function(){
			var btn = jQuery(this);
			var txt = btn.attr('value').replace( ' →', '' ).replace( '← ', '' );
			btn.attr('value', txt);
		});
	}

	// Paid Memberships Pro
	function anesta_memberships_init() {
		jQuery('.pmpro_actionlinks').each(function(){
			var links = jQuery(this);
			var html = links.html().replaceAll( '|', '' );
			links.html(html);
		});
		jQuery('.pmpro_table').each(function(){
			jQuery(this).wrap('<div class="pmpro_table_wrap"></div>');
		});
	}

	// Elementor
	function anesta_elementor_init() {
		jQuery('.elementor-inner-section .elementor-widget').addClass('elementor-inner');
	}	

	// WooCommerce
	function anesta_woocommerce_init() {
		jQuery('.rating_details_table_cell_total').each(function(){
			var width = jQuery(this).html();
			jQuery(this).prev().find('[class*="rating_details_table_cell_bar_fill_"]').width(width);

		});
	}	

	// Tribe events
	function anesta_tribe_events_init( jqXHR, textStatus ) {
		setTimeout( function() {
			// Set up event handler again because .tribe-events-view was recreated after AJAX
			jQuery('.tribe-events-view').on( 'beforeAjaxComplete.tribeEvents beforeAjaxSuccess.tribeEvents beforeAjaxError.tribeEvents', anesta_tribe_events_init );
			// ToDo: Any actions after the Tribe Events View is reloaded
		}, 10 );
	}	

	// BBPress and BuddyPress
	function anesta_bbpress_resize() {
		if ( $window.width() <= 1679 ) {
			jQuery('#cover-image-container').addClass('scheme_dark');
		} else {
			jQuery('#cover-image-container').removeClass('scheme_dark');
		}
	}
})();