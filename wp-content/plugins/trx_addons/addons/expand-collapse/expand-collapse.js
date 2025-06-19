/* global jQuery */

(function() {

	"use strict";

	jQuery( document ).on('action.init_hidden_elements', function( e, $cont ) {
		if ( ! $cont || ! $cont.length ) {
			$cont = jQuery( 'body' );
		}
		$cont.find( '.trx_expcol_on:not(.trx_expcol_inited)' ).each( function() {
			add_expcol_button( jQuery( this ) );
		} );
	} );
	
	jQuery( document ).ready( function() {
		if (typeof window.elementorFrontend != 'undefined' && window.elementorFrontend.isEditMode() ) {
			var add_expcol_button_throttled = trx_addons_throttle( function( $elm ) {
				add_expcol_button( $elm.removeClass( 'trx_expcol_inited' ) );
			}, 2000, true );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function( $elm ) {
				if ( $elm.hasClass( 'trx_expcol_inited' ) && ! $elm.find( '.trx_expcol_button' ).length || $elm.hasClass( 'trx_expcol_on' ) && ! $elm.hasClass( 'trx_expcol_inited' ) ) {
					add_expcol_button_throttled( $elm );
				}
			} );
		}
	} );

	function add_expcol_button( $parent ) {

		$parent.addClass( 'trx_expcol_inited' );
		
		var bt_layout = $parent.data( 'trx-expcol-button' );

		if ( ! bt_layout && typeof window.elementorFrontend != 'undefined' && window.elementorFrontend.isEditMode() ) {
			bt_layout = get_button_layout( $parent );
		}

		if ( bt_layout ) {
			
			$parent.append( bt_layout );
			
			var $bt = $parent.find( '.trx_expcol_button' );
			
			if ( $bt.length ) {

				$bt.on( 'click', function(e) {
					var collapsed = $parent.hasClass( 'trx_expcol_state_collapsed' );
					if ( collapsed ) {
						var $container = $parent.find( '>.e-con-inner,>.e-con,>.elementor-container,>.elementor-column-wrap,>.elementor-widget,>.elementor-widget-container' );
						if ( $container.length ) {
							var pt = $parent.css( 'padding-top' ) || 0,
								pb = $parent.css( 'padding-bottom' ) || 0;
							// Old way: Get the height of the first child element (if any)
							//var h = $container.eq(0).outerHeight() + parseInt( pt, 10 ) + parseInt( pb, 10 );
							// New way: Get the total height of the container ( elements may be in several columns)
							var start = -1, end = -1;
							$container.each( function() {
								var $this = jQuery( this ),
									pos = $this.position().top,
									h = $this.outerHeight(); 
								if ( start == -1 || pos < start ) {
									start = pos;
								}
								if ( end == -1 || pos + h > end ) {
									end = pos + h;
								}
							} );
							var h = end - start + parseInt( pt, 10 ) + parseInt( pb, 10 );
							// Animate the height of the parent element to the height of the container
//							$bt.fadeOut();
							$parent.css( {
								'height': $parent.outerHeight()
							} );
							$parent.addClass('trx_expcol_state_animated').animate( { height: h }, 500, function() {
								$parent.removeClass( 'trx_expcol_state_animated' );
								$parent.height( 'auto' );
//									$bt.fadeIn();
								if ( ! $parent.hasClass( 'trx_expcol_hidden_inited' ) ) {
									$parent.addClass( 'trx_expcol_hidden_inited' );
									jQuery(document).trigger( 'action.init_hidden_elements', [$parent] );
								}
							} );
						}
						$parent.removeClass( 'trx_expcol_state_collapsed' ).addClass( 'trx_expcol_state_expanded' );
						$bt.find( '.trx_expcol_button_title' ).text( $bt.data( 'trx-expcol-expanded-title' ) );
					} else {
						var h = $parent.data('trx-expcol-collapsed-height');
						if ( h ) {
//								$bt.fadeOut();
							$parent.css( {
								'height': $parent.outerHeight()
							} );
							$parent.addClass('trx_expcol_state_animated');
							$parent.addClass('trx_expcol_state_animated').animate( { height: trx_addons_units2px( h, $parent ) }, 500, function() {
								$parent.removeClass( 'trx_expcol_state_animated trx_expcol_state_expanded' ).addClass( 'trx_expcol_state_collapsed' );
								$parent.height( 'auto' );
//									$bt.fadeIn();
							} );
						} else {
							$parent.removeClass( 'trx_expcol_state_expanded' ).addClass( 'trx_expcol_state_collapsed' );
						}
						$bt.find( '.trx_expcol_button_title' ).text( $bt.data( 'trx-expcol-collapsed-title' ) );
					}
					e.preventDefault();
					return false;
				} );
			
			}
		
		}

	}		

	function get_editor_settings( $target ) {

		if ( ! window.elementor || ! window.elementor.hasOwnProperty( 'elements' ) ) {
			return false;
		}

		var elements = window.elementor.elements;

		if ( ! elements.models ) {
			return false;
		}

		var section_id = $target.data('id'),
			section_cid = $target.data('model-cid'),
			section_data = {};

		function get_section_data( idx, obj ) {

			if ( 0 < Object.keys( section_data ).length ) {
				return;
			} else if ( section_id == obj.id ) {
				section_data = obj.attributes.settings.attributes;
			} else if ( obj.attributes && obj.attributes.elements && obj.attributes.elements.models ) {
				jQuery.each( obj.attributes.elements.models, get_section_data );
			}

		}

		jQuery.each( elements.models, get_section_data );

		return 0 === Object.keys( section_data ).length ? false : section_data;

	}


	function get_button_layout( $parent ) {

		var bt_layout = '',
			settings = get_editor_settings( $parent ),
			styles_allowed = jQuery( 'body' ).hasClass( 'trx_addons_customizable_theme' );

		if ( settings && typeof settings.trx_expcol_allow != 'undefined' && settings.trx_expcol_allow == 'on' ) {

			$parent.attr( 'data-trx-expcol-collapsed-height', settings.trx_expcol_collapsed_height.size + settings.trx_expcol_collapsed_height.unit );

			bt_layout = '<a href="#" class="trx_expcol_button'
							+ ( settings.trx_expcol_collapsed_bg_color !== '' || settings.trx_expcol_expanded_bg_color !== ''
								? ' trx_expcol_button_with_bg'
								: ''
								)
							+ ( styles_allowed
								? ' sc_button_default trx_addons_customizable'
								: ''
								)
							+ '"'
							+ ' data-trx-expcol-collapsed-title="'  + settings.trx_expcol_collapsed_title + '"'
							+ ' data-trx-expcol-expanded-title="'  + settings.trx_expcol_expanded_title + '"'
							+ '>';
			for ( var state in { 'collapsed': 1, 'expanded': 1 } ) {
				var icon = settings['trx_expcol_' + state + '_icon'];
				if ( icon ) {
					bt_layout += '<span class="trx_expcol_button_icon trx_expcol_button_icon_' + state + '">'
									+ ( trx_addons_is_url( icon )
										? '<img class="sc_icon_as_image" src="' + icon + '">'
										: '<span class="' + icon + '"></span>'
										)
									+ '</span>';
				}
			}
			if ( settings.trx_expcol_expanded_title !== '' || settings.trx_expcol_collapsed_title !== '' ) {
				bt_layout += '<span class="trx_expcol_button_title">'
								+ settings['trx_expcol_' + settings.trx_expcol_state + '_title']
								+ '</span>';
			}
			bt_layout += '</a>';
		}

		return trx_addons_apply_filters( 'trx_addons_filter_expcol_button_layout', bt_layout );

	}

})();