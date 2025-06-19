<?php
/* Theme-specific action to configure ThemeREX Addons components
------------------------------------------------------------------------------- */


/* ThemeREX Addons components
------------------------------------------------------------------------------- */
if ( ! function_exists( 'anesta_trx_addons_theme_specific_components' ) ) {
	add_filter( 'trx_addons_filter_components_editor', 'anesta_trx_addons_theme_specific_components' );
	function anesta_trx_addons_theme_specific_components( $enable = false ) {
		return ANESTA_THEME_FREE
					? false     // Free version
					: false;    // Pro version or Developer mode
	}
}

if ( ! function_exists( 'anesta_trx_addons_theme_specific_setup1' ) ) {
	add_action( 'after_setup_theme', 'anesta_trx_addons_theme_specific_setup1', 1 );
	function anesta_trx_addons_theme_specific_setup1() {
		if ( anesta_exists_trx_addons() ) {
			add_filter( 'trx_addons_addons_list', 'anesta_trx_addons_addons_list', 100 );
			add_filter( 'trx_addons_api_list', 'anesta_trx_addons_api_list' );
			add_filter( 'trx_addons_cpt_list', 'anesta_trx_addons_cpt_list' );
			add_filter( 'trx_addons_sc_list', 'anesta_trx_addons_sc_list' );
			add_filter( 'trx_addons_widgets_list', 'anesta_trx_addons_widgets_list' );
		}
	}
}

// Addons
if ( ! function_exists( 'anesta_trx_addons_addons_list' ) ) {
	//Handler of the add_filter( 'trx_addons_addons_list', 'anesta_trx_addons_addons_list', 100 );
	function anesta_trx_addons_addons_list( $list = array() ) {
		// To do: Enable/Disable theme-specific addons via add/remove it in the list
		if ( is_array( $list ) ) {
			// List of the theme/skin required addons:
			// array(
			// 		'addon1-slug' => array( 'title' => "Title of the addon 1" ),
			// 		'addon2-slug' => array( 'title' => "Title of the addon 2" ),
			// 		...
			//      )
			$required_addons = array(				
				'bp-widgets' 	  => array( 'title' => esc_html__( 'BuddyPress widgets', 'anesta' ) )
			);
			foreach( $required_addons as $k => $v ) {
				if ( ! isset( $list[ $k ] ) || ! is_array( $list[ $k ] ) ) {
					$list[ $k ] = $v;
				}
				$list[ $k ]['required'] = true;
			}
		}
		return $list;
	}
}

// API
if ( ! function_exists( 'anesta_trx_addons_api_list' ) ) {
	//Handler of the add_filter('trx_addons_api_list',	'anesta_trx_addons_api_list');
	function anesta_trx_addons_api_list( $list = array() ) {
		// To do: Enable/Disable Third-party plugins API via add/remove it in the list

		// If it's a free version - leave only basic set
		if ( ANESTA_THEME_FREE ) {
			$free_api = array( 'gutenberg', 'elementor', 'contact-form-7', 'instagram_feed', 'woocommerce' );
			foreach ( $list as $k => $v ) {
				if ( ! in_array( $k, $free_api ) ) {
					unset( $list[ $k ] );
				}
			}
		}
		return $list;
	}
}

// CPT
if ( ! function_exists( 'anesta_trx_addons_cpt_list' ) ) {
	//Handler of the add_filter('trx_addons_cpt_list',	'anesta_trx_addons_cpt_list');
	function anesta_trx_addons_cpt_list( $list = array() ) {
		// To do: Enable/Disable CPT via add/remove it in the list

		// If it's a free version - leave only basic set
		if ( ANESTA_THEME_FREE ) {
			$free_cpt = array( 'layouts', 'portfolio', 'post', 'services', 'team', 'testimonials' );
			foreach ( $list as $k => $v ) {
				if ( ! in_array( $k, $free_cpt ) ) {
					unset( $list[ $k ] );
				}
			}
		}
		return $list;
	}
}

// Shortcodes
if ( ! function_exists( 'anesta_trx_addons_sc_list' ) ) {
	//Handler of the add_filter('trx_addons_sc_list',	'anesta_trx_addons_sc_list');
	function anesta_trx_addons_sc_list( $list = array() ) {
		// To do: Add/Remove shortcodes into list
		// If you add new shortcode - in the theme's folder must exists /trx_addons/shortcodes/new_sc_name/new_sc_name.php

		// If it's a free version - leave only basic set
		if ( ANESTA_THEME_FREE ) {
			$free_shortcodes = array( 'action', 'anchor', 'blogger', 'button', 'form', 'icons', 'price', 'promo', 'socials' );
			foreach ( $list as $k => $v ) {
				if ( ! in_array( $k, $free_shortcodes ) ) {
					unset( $list[ $k ] );
				}
			}
		}

		// Blogger Default
		$list['blogger']['templates']['default']['classic']['layout'] = 
		array(
			'header' => array(
				'meta_author', 'meta_date'
			),
			'featured' => array(
			),
			'content' => array(
				'meta_categories', 'meta_date', 'title', 'excerpt', 'meta', 'readmore'
			)
		);
		$list['blogger']['templates']['default']['modern'] = 
		array(
			'title'  => __('Modern Grid', 'anesta'),
			'layout' => array(
				'content' => array(
					'meta_categories', 'meta_date', 'title', 'excerpt', 'meta', 'readmore'
				)
			)
		);
		$list['blogger']['templates']['default']['over'] = 
		array(
			'title'  => __('Info over image', 'anesta'),
			'layout' => array(
				'featured' => array(
					'bl' => array(
						'meta_categories', 'title', 'excerpt', 'meta', 'readmore'
					),
					'tl' => array(
						'price'
					)
				)
			)
		);

		unset($list['blogger']['templates']['default']['classic_2']);
		unset($list['blogger']['templates']['default']['classic_3']);
		unset($list['blogger']['templates']['default']['over_centered']);
		unset($list['blogger']['templates']['default']['over_bottom']);

		// Blogger List
		$list['blogger']['templates']['list']['simple']['layout'] = 
		array(
			'content' => array(
				'meta_categories', 'meta_date', 'title', 'meta'
			)
		);
		unset($list['blogger']['templates']['list']['with_image']);

		// Icons
		$list['icons']['layouts_sc']['classic'] = esc_html__('Classic', 'anesta');

		return $list;
	}
}

// Widgets
if ( ! function_exists( 'anesta_trx_addons_widgets_list' ) ) {
	//Handler of the add_filter('trx_addons_widgets_list',	'anesta_trx_addons_widgets_list');
	function anesta_trx_addons_widgets_list( $list = array() ) {
		// To do: Add/Remove widgets into list
		// If you add widget - in the theme's folder must exists /trx_addons/widgets/new_widget_name/new_widget_name.php

		// If it's a free version - leave only basic set
		if ( ANESTA_THEME_FREE ) {
			$free_widgets = array( 'aboutme', 'banner', 'contacts', 'flickr', 'popular_posts', 'recent_posts', 'slider', 'socials' );
			foreach ( $list as $k => $v ) {
				if ( ! in_array( $k, $free_widgets ) ) {
					unset( $list[ $k ] );
				}
			}
		}
		return $list;
	}
}

// Add mobile menu to the plugin's cached menu list
if ( ! function_exists( 'anesta_trx_addons_menu_cache' ) ) {
	add_filter( 'trx_addons_filter_menu_cache', 'anesta_trx_addons_menu_cache' );
	function anesta_trx_addons_menu_cache( $list = array() ) {
		if ( in_array( '#menu_main', $list ) ) {
			$list[] = '#menu_mobile';
		}
		$list[] = '.menu_mobile_inner > nav > ul';
		return $list;
	}
}

// Add theme-specific vars into localize array
if ( ! function_exists( 'anesta_trx_addons_localize_script' ) ) {
	add_filter( 'anesta_filter_localize_script', 'anesta_trx_addons_localize_script' );
	function anesta_trx_addons_localize_script( $arr ) {
		return $arr;
	}
}

// Add theme-specific width where used min 2 columns
if ( ! function_exists( 'anesta_trx_addons_max_one_column_width' ) ) {
	add_filter( 'trx_addons_filter_max_one_column_width', 'anesta_trx_addons_max_one_column_width' );
	function anesta_trx_addons_max_one_column_width( $w ) {
		$media = anesta_storage_get_array( 'responsive', 'sm_wp' );
		if ( empty( $media['max'] ) ) {
			$media = array( 'max' => 600 );
		}
		return $media['max'];
	}
}


// Shortcodes support
//------------------------------------------------------------------------

// Add new output types (layouts) in the shortcodes
if ( ! function_exists( 'anesta_trx_addons_sc_type' ) ) {
	add_filter( 'trx_addons_sc_type', 'anesta_trx_addons_sc_type', 10, 3 );
	function anesta_trx_addons_sc_type( $list, $sc, $need_custom = true ) {
		// To do: check shortcode slug and if correct - add new 'key' => 'title' to the list
		if ( 'trx_sc_blogger' == $sc ) {
			$list = anesta_array_merge( $list, anesta_get_list_blog_styles( false, 'sc', $need_custom ) );
		}
		return $list;
	}
}

// Add params values to the shortcode's atts
if ( ! function_exists( 'anesta_trx_addons_sc_prepare_atts' ) ) {
	add_filter( 'trx_addons_filter_sc_prepare_atts', 'anesta_trx_addons_sc_prepare_atts', 10, 2 );
	function anesta_trx_addons_sc_prepare_atts( $atts, $sc ) {
		if ( 'trx_sc_blogger' == $sc ) {
			$is_custom = strpos( $atts['type'], 'blog-custom-' ) === 0;
			$list = anesta_get_list_blog_styles( false, 'sc', $is_custom );
			if ( isset( $list[ $atts['type'] ] ) ) {
			    $blog_id = 0;
			    $blog_meta = array( 'scripts_required' => '' );
				$custom_type = '';
				$use_masonry = false;
				if ( $is_custom ) {
					$blog_id = anesta_get_custom_blog_id( $atts['type'] );
					$blog_meta = anesta_get_custom_layout_meta( $blog_id );
					$custom_type = ! empty( $blog_meta['scripts_required'] ) ? $blog_meta['scripts_required'] : 'custom';
					$use_masonry = strpos( $blog_meta['scripts_required'], 'masonry' ) !== false;
				} else {
					$use_masonry = anesta_is_blog_style_use_masonry( $atts['type'] );
				}
				// Classes for the container with posts
				$columns = $atts['columns'] > 0
								? $atts['columns']
								: ( 1 < $atts['count']
									? $atts['count']
									: ( -1 == $atts['count']
										? 3
										: 1
										)
									);
				$atts['posts_container'] = 'posts_container'
					. ' ' . esc_attr( $atts['type'] ) . '_wrap'
					. ( $columns > 1
							? ' ' . esc_attr( $atts['type'] ) . '_' . $columns 
							: '' )
					. ( $use_masonry
						?  sprintf( ' masonry_wrap masonry_%d', $columns )
						: ( $columns > 1
							? ' columns_wrap columns_padding_bottom'
							: ''
							)
						);
				// Scripts for masonry and portfolio
				if ( $use_masonry ) {
					anesta_lazy_load_off();
					anesta_load_masonry_scripts();
				}
			}
		}
		return $atts;
	}
}

// Add new params to the default shortcode's atts
if ( ! function_exists( 'anesta_trx_addons_sc_atts' ) ) {
	add_filter( 'trx_addons_sc_atts', 'anesta_trx_addons_sc_atts', 10, 2 );
	function anesta_trx_addons_sc_atts( $atts, $sc ) {

		// Param 'scheme'
		if ( in_array(
			$sc, array(
				'trx_sc_action',
				'trx_sc_blogger',
				'trx_sc_cars',
				'trx_sc_courses',
				'trx_sc_content',
				'trx_sc_countdown',
				'trx_sc_dishes',
				'trx_sc_events',
				'trx_sc_form',
				'trx_sc_icons',
				'trx_sc_googlemap',
				'trx_sc_yandexmap',
				'trx_sc_osmap',
				'trx_sc_portfolio',
				'trx_sc_price',
				'trx_sc_promo',
				'trx_sc_properties',
				'trx_sc_services',
				'trx_sc_skills',
				'trx_sc_socials',
				'trx_sc_table',
				'trx_sc_team',
				'trx_sc_testimonials',
				'trx_sc_title',
				'trx_widget_audio',
				'trx_widget_twitter',
				'trx_sc_layouts',
				'trx_sc_layouts_container',
			)
		) ) {
			$atts['scheme'] = 'inherit';
		}
		// Param 'color_style'
		if ( in_array(
			$sc, array(
				'trx_sc_action',
				'trx_sc_blogger',
				'trx_sc_cars',
				'trx_sc_courses',
				'trx_sc_content',
				'trx_sc_countdown',
				'trx_sc_dishes',
				'trx_sc_events',
				'trx_sc_form',
				'trx_sc_icons',
				'trx_sc_googlemap',
				'trx_sc_yandexmap',
				'trx_sc_osmap',
				'trx_sc_portfolio',
				'trx_sc_price',
				'trx_sc_promo',
				'trx_sc_properties',
				'trx_sc_services',
				'trx_sc_skills',
				'trx_sc_socials',
				'trx_sc_table',
				'trx_sc_team',
				'trx_sc_testimonials',
				'trx_sc_title',
				'trx_widget_audio',
				'trx_widget_twitter'
			)
		) ) {
			$atts['color_style'] = 'default';
		}
		if ( in_array(
			$sc, array(
				'trx_sc_button',
			)
		) ) {
			if ( is_array( $atts['buttons'] ) ) {
				foreach( $atts['buttons'] as $k => $v ) {
					$atts['buttons'][ $k ]['color_style'] = 'default';
				}
			}
		}

		// Calendar
		if ( $sc == 'trx_widget_calendar') {
			$atts['image'] = '';
		}
		return $atts;
	}
}

// Add classes to the shortcode's output from new params
if ( ! function_exists( 'anesta_trx_addons_sc_output' ) ) {
	add_filter( 'trx_addons_sc_output', 'anesta_trx_addons_sc_output', 10, 4 );
	function anesta_trx_addons_sc_output( $output, $sc, $atts, $content ) {
		$sc = str_replace( array( 'trx_widget', 'trx_' ), array( 'sc_widget', '' ), $sc );
		if ( substr( $sc, -3 ) == 'map' ) {
			$sc = str_replace( 'map', 'map_content', $sc );
		}
		if ( ! empty( $atts['scheme'] ) && ! anesta_is_inherit( $atts['scheme'] ) ) {
			$output = str_replace( 'class="' . esc_attr( $sc ) . ' ', 'class="' . esc_attr( $sc ) . ' scheme_' . esc_attr( $atts['scheme'] ) . ' ', $output );
		}
		if ( ! empty( $atts['color_style'] ) && ! anesta_is_inherit( $atts['color_style'] ) && 'default' != $atts['color_style'] ) {
			$output = str_replace( 'class="' . esc_attr( $sc ) . ' ', 'class="' . esc_attr( $sc ) . ' color_style_' . esc_attr( $atts['color_style'] ) . ' ', $output );
		}
		return $output;
	}
}

// Add color_style to the button items
if ( ! function_exists( 'anesta_trx_addons_sc_item_link_classes' ) ) {
	add_filter( 'trx_addons_filter_sc_item_link_classes', 'anesta_trx_addons_sc_item_link_classes', 10, 3 );
	function anesta_trx_addons_sc_item_link_classes( $class, $sc, $atts=array() ) {
		if ( 'sc_button' == $sc ) {
			if ( ! empty( $atts['color_style'] ) && ! anesta_is_inherit( $atts['color_style'] ) && 'default' != $atts['color_style'] ) {
				$class .= ' color_style_' . esc_attr( $atts['color_style'] );
			}
		}
		return $class;
	}
}

// Return tag for the item's title
if ( ! function_exists( 'anesta_trx_addons_sc_item_title_tag' ) ) {
	add_filter( 'trx_addons_filter_sc_item_title_tag', 'anesta_trx_addons_sc_item_title_tag' );
	function anesta_trx_addons_sc_item_title_tag( $tag = '' ) {
		return 'h1' == $tag ? 'h2' : $tag;
	}
}

// Return args for the item's button
if ( ! function_exists( 'anesta_trx_addons_sc_item_button_args' ) ) {
	add_filter( 'trx_addons_filter_sc_item_button_args', 'anesta_trx_addons_sc_item_button_args', 10, 3 );
	function anesta_trx_addons_sc_item_button_args( $args, $sc, $sc_args ) {
		if ( ! empty( $sc_args['color_style'] ) ) {
			$args['color_style'] = $sc_args['color_style'];
		}
		return $args;
	}
}

// Add new styles to the Google map
if ( ! function_exists( 'anesta_trx_addons_sc_googlemap_styles' ) ) {
	add_filter( 'trx_addons_filter_sc_googlemap_styles', 'anesta_trx_addons_sc_googlemap_styles' );
	function anesta_trx_addons_sc_googlemap_styles( $list ) {
		$list['dark'] = esc_html__( 'Dark', 'anesta' );
		return $list;
	}
}

// Show post info from CPT Portfolio instead post meta
if ( ! function_exists( 'anesta_trx_addons_portfolio_info' ) ) {
	add_filter( 'anesta_filter_show_blog_meta', 'anesta_trx_addons_portfolio_info', 10, 2 );
	function anesta_trx_addons_portfolio_info( $show, $meta_parts ) {
		if ( anesta_exists_trx_addons() && defined( 'TRX_ADDONS_CPT_PORTFOLIO_PT' ) && get_post_type() == TRX_ADDONS_CPT_PORTFOLIO_PT && function_exists( 'trx_addons_cpt_portfolio_show_details' ) ) {
			trx_addons_cpt_portfolio_show_details( array( 'class' => 'post_meta', 'count' => 3 ) );
			$show = false;
		}
		return $show;
	}
}


// WP Editor addons
//------------------------------------------------------------------------
// Theme-specific configure of the WP Editor
if ( ! function_exists( 'anesta_trx_addons_tiny_mce_style_formats' ) ) {
	add_filter( 'trx_addons_filter_tiny_mce_style_formats', 'anesta_trx_addons_tiny_mce_style_formats' );
	function anesta_trx_addons_tiny_mce_style_formats( $style_formats ) {
		// Add style 'Arrow' to the 'List styles'
		// Remove 'false &&' from the condition below to add new style to the list
		if ( false && is_array( $style_formats ) && count( $style_formats ) > 0 ) {
			foreach ( $style_formats as $k => $v ) {
				if ( esc_html__( 'List styles', 'anesta' ) == $v['title'] ) {
					$style_formats[ $k ]['items'][] = array(
						'title'    => esc_html__( 'Arrow', 'anesta' ),
						'selector' => 'ul',
						'classes'  => 'trx_addons_list trx_addons_list_arrow',
					);
				}
			}
		}
		return $style_formats;
	}
}


// Setup team and portflio pages
//------------------------------------------------------------------------
// Disable override header image on team and portfolio pages
if ( ! function_exists( 'anesta_trx_addons_allow_override_header_image' ) ) {
	add_filter( 'anesta_filter_allow_override_header_image', 'anesta_trx_addons_allow_override_header_image' );
	function anesta_trx_addons_allow_override_header_image( $allow ) {
		return anesta_is_single()
				&& (
					anesta_is_team_page()
					|| anesta_is_cars_page()
					|| anesta_is_cars_agents_page()
					|| anesta_is_properties_agents_page()
					)
				? false
				: $allow;
	}
}

// Add fields to the meta box for the team members
// All other CPT meta boxes may be modified in the same method
if ( ! function_exists( 'anesta_trx_addons_meta_box_fields' ) ) {
	add_filter( 'trx_addons_filter_meta_box_fields', 'anesta_trx_addons_meta_box_fields', 10, 2 );
	function anesta_trx_addons_meta_box_fields( $mb, $post_type ) {
		if ( defined( 'TRX_ADDONS_CPT_TEAM_PT' ) && TRX_ADDONS_CPT_TEAM_PT == $post_type ) {
			if ( ! isset( $mb['email'] ) ) {
				$mb['email'] = array(
					'title'   => esc_html__( 'E-mail', 'anesta' ),
					'desc'    => wp_kses_data( __( "Team member's email", 'anesta' ) ),
					'std'     => '',
					'details' => true,
					'type'    => 'text',
				);
			}
		}
		return $mb;
	}
}

// Change thumb size for the team items
if ( ! function_exists( 'anesta_trx_addons_thumb_size' ) ) {
	add_filter( 'trx_addons_filter_thumb_size', 'anesta_trx_addons_thumb_size', 10, 2 );
	function anesta_trx_addons_thumb_size( $thumb_size = '', $type = '' ) {
		// ToDo: Change team members image's size (default is 'avatar'):
		//---> if ($type == 'team-default') $thumb_size = anesta_get_thumb_size('big');
		return $thumb_size;
	}
}


// Modify layouts of some components
//------------------------------------------------------------------------
// Return theme specific title layout for the slider
if ( ! function_exists( 'anesta_trx_addons_slider_title' ) ) {
	add_filter( 'trx_addons_filter_slider_title', 'anesta_trx_addons_slider_title', 10, 3 );
	function anesta_trx_addons_slider_title( $title, $data, $args ) {
		$title = '';
		if ( ! empty( $data['title'] ) ) {
			$title .= '<h3 class="slide_title">'
						. ( ! empty( $data['link'] )
								? '<a href="' . esc_url( $data['link'] ) . '"'
									. ( ! empty( $data['link_atts'] )
											? $data['link_atts']
											: ''
											)
									. '>'
								: ''
								)
							. esc_html( $data['title'] )
						. ( ! empty( $data['link'] ) ? '</a>' : '' )
					. '</h3>';
		}
		if ( ! empty( $data['cats'] ) ) {
			$title .= sprintf( '<div class="slide_cats">%s</div>', $data['cats'] );
		}
		return $title;
	}
}


// New
//--------------------------------------------------
// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'anesta_trx_addons_skin_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'anesta_trx_addons_skin_theme_setup9', 9 );
	function anesta_trx_addons_skin_theme_setup9() {
		if ( anesta_exists_trx_addons() ) {
			add_action( 'wp_enqueue_scripts', 'anesta_trx_addons_skin_frontend_scripts', 1100 );
			add_filter( 'anesta_filter_merge_scripts', 'anesta_trx_addons_skin_merge_scripts' );
		}
	}
}

// Theme init priorities:
// 10 - standard Theme init procedures (not ordered)
if ( ! function_exists( 'anesta_trx_addons_skin_theme_setup10' ) ) {
	add_action( 'after_setup_theme', 'anesta_trx_addons_skin_theme_setup10', 10 );
	function anesta_trx_addons_skin_theme_setup10() {
		if ( anesta_exists_trx_addons() ) {
			remove_action( 'trx_addons_filter_get_theme_accent_color', 'anesta_trx_addons_get_theme_accent_color' );
		}
	}
}

// Plugin init
if ( ! function_exists( 'anesta_trx_addons_skin_init' ) ) {
	add_action( 'init', 'anesta_trx_addons_skin_init', 10 );
	function anesta_trx_addons_skin_init() {
		if ( anesta_exists_trx_addons() ) {	
			remove_action( 'init', 'trx_addons_banners_init', 11 );
		}
	}
}

// Enqueue scripts for WP Editor
if ( ! function_exists( 'anesta_trx_addons_gutenberg_editor_assets' ) ) {
	add_action( 'enqueue_block_editor_assets', 'anesta_trx_addons_gutenberg_editor_assets' );
	function anesta_trx_addons_gutenberg_editor_assets() {
		if ( anesta_exists_trx_addons() && anesta_exists_gutenberg() && anesta_get_theme_setting( 'allow_gutenberg_blocks' ) ) {
			// Scripts
			wp_enqueue_script(
				'anesta-trx-addons-skin-gutenberg-editor',
				anesta_get_file_url( 'plugins/trx_addons/trx_addons.gutenberg-editor.js' ),
				trx_addons_block_editor_dependencis(),
				filemtime( anesta_get_file_dir( 'plugins/trx_addons/trx_addons.gutenberg-editor.js' ) ),
				true
			);
		}
	}
}

// Enqueue styles for frontend
if ( ! function_exists( 'anesta_trx_addons_skin_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_trx_addons_skin_frontend_scripts', 1100 );
	function anesta_trx_addons_skin_frontend_scripts() {
		if ( anesta_is_on( anesta_get_theme_option( 'debug_mode' ) ) ) {
			$anesta_url = anesta_get_file_url( 'plugins/trx_addons/trx_addons.js' );
			if ( '' != $anesta_url ) {
				wp_enqueue_script( 'anesta-trx-addons-skin', $anesta_url, array( 'jquery', 'anesta-utils', 'anesta-init' ), null, true );
			}
		}
	}
}

// Merge custom scripts
if ( ! function_exists( 'anesta_trx_addons_skin_merge_scripts' ) ) {
	//Handler of the add_filter('anesta_filter_merge_scripts', 'anesta_trx_addons_skin_merge_scripts');
	function anesta_trx_addons_skin_merge_scripts( $list ) {
		$list[ 'plugins/trx_addons/trx_addons.js' ] = true;
		return $list;
	}
}

// Admin inline styles
if ( ! function_exists( 'anesta_trx_addons_inline_styles' ) ) {
	add_action('admin_head', 'anesta_trx_addons_inline_styles');
	function anesta_trx_addons_inline_styles() {
		if ( anesta_exists_trx_addons() && trx_addons_is_options_page() ) {
		 	echo '<style>
					.trx_addons_options_item_hidden + .trx_addons_options_item_info:not(:first-child) {
					  padding-top: 0;
					}
				</style>';
		}
	}
}

// Current system info
if ( ! function_exists( 'anesta_trx_addons_sys_info' ) ) {
	add_filter( 'trx_addons_filter_get_sys_info', 'anesta_trx_addons_sys_info' );
	function anesta_trx_addons_sys_info( $array ) {
		$array['wp_version']['recommended'] = "5.6.0+";
		return $array;
	}
}

// Plugin's options
if ( ! function_exists( 'anesta_trx_addons_options' ) ) {
	add_filter( 'trx_addons_filter_options', 'anesta_trx_addons_options' );
	function anesta_trx_addons_options( $array ) {
		/* Banners */
		$array['banners_section']['type'] = "hidden";
		$array['posts_banners']['type'] = "hidden";		
		$array['posts_banners']['std'] = "";		
		unset($array['posts_banners']['fields']);
		unset($array['posts_banners']['clone']);

		/* Anchor */
		$array['sc_anchor_info']['type'] = "hidden";
		$array['scroll_to_anchor']['type'] = "hidden";
		$array['update_location_from_anchor']['type'] = "hidden";

		/* Posts selector */
		$array['sc_ids_info']['type'] = "hidden";
		$array['sc_ids_type']['type'] = "hidden";

		/* Tabs */
		$array['sc_tabs_info']['type'] = "hidden";
		$array['sc_tabs_layouts']['type'] = "hidden";

		/* Emotions */
		$array['emotions_info']['type'] = "hidden";
		$array['emotions_allowed']['type'] = "hidden";

		return $array;
	}
}

// Add param 'hover_style' to the shortcode 'Button' in the Elementor
if ( ! function_exists( 'anesta_trx_addons_skin_sc_param_group_params' ) ) {
	add_filter( 'trx_addons_sc_param_group_params', 'anesta_trx_addons_skin_sc_param_group_params', 10, 2 );
	function anesta_trx_addons_skin_sc_param_group_params( $params, $sc ) {
		if ( in_array( $sc, array( 'trx_sc_button' ) ) ) {
			foreach ( $params as $key => $value ) {
				if ( in_array( $value['name'], array('subtitle', 'icon', 'image', 'icon_position') ) ) {
					if ( 'image' == $value['name'] ) {
						$params[$key]['type'] = \Elementor\Controls_Manager::HIDDEN;
					} else {
						unset($params[$key]);
					}					
				}
			}
		}
		return $params;
	}
}

// Slider widget engines
if ( ! function_exists( 'anesta_trx_addons_sc_slider_engines' ) ) {
	add_filter( 'trx_addons_filter_get_list_sc_slider_engines', 'anesta_trx_addons_sc_slider_engines' );
	function anesta_trx_addons_sc_slider_engines( $list ) {	
		unset($list['elastistack']);
		return $list;
	}
}

// Blogger widget image positions
if ( ! function_exists( 'anesta_trx_addons_sc_blogger_image_positions' ) ) {
	add_filter( 'trx_addons_filter_get_list_sc_blogger_image_positions', 'anesta_trx_addons_sc_blogger_image_positions' );
	function anesta_trx_addons_sc_blogger_image_positions( $list ) {
		unset($list['left']);
		unset($list['right']);
		unset($list['alter']);
		return $list;
	}
}

// Blogger widget parts of template
if ( ! function_exists( 'anesta_trx_addons_sc_blogger_item_element' ) ) {
	add_filter( 'trx_addons_action_sc_blogger_item_element', 'anesta_trx_addons_sc_blogger_item_element', 10, 4 );
	function anesta_trx_addons_sc_blogger_item_element( $output, $element, $type, $args ) {
		if ( $args['template_'.$args['type']] == 'classic' && $type == 'header' && $element == 'meta_date') {			
			if ( !in_array('author', $args['meta_parts']) ) {
				return '';
			}			 
		} 
		if ( $args['template_'.$args['type']] == 'classic' && $type == 'content' && $element == 'meta_date') {			
			if ( in_array('author', $args['meta_parts']) ) {
				return '';
			}			 
		} 
		return $output;
	}
}

// Cart widget types
if ( ! function_exists( 'anesta_trx_addons_sc_layouts_cart_types' ) ) {
	add_filter( 'trx_addons_filter_get_list_sc_layouts_cart_types', 'anesta_trx_addons_sc_layouts_cart_types' );
	function anesta_trx_addons_sc_layouts_cart_types( $list ) {
		unset($list['panel']);
		return $list;
	}
}

// Widget attributes
if ( ! function_exists( 'anesta_trx_addons_sc_show_attribute' ) ) {
	add_action( 'trx_addons_action_sc_show_attributes', 'anesta_trx_addons_sc_show_attribute', 10, 3 );
	function anesta_trx_addons_sc_show_attribute( $sc, $args, $area ) {
		if ( 'sc_skills' == $sc &&  $args['columns'] > 0 ) {
			echo ' data-col="' . esc_attr($args['columns']) . '"';
		}
	}
}

// Image ratio
if ( ! function_exists( 'anesta_trx_addons_list_sc_image_ratio' ) ) {
	add_filter( 'trx_addons_filter_get_list_sc_image_ratio', 'anesta_trx_addons_list_sc_image_ratio' );
	function anesta_trx_addons_list_sc_image_ratio( $list ) {
		$list = array_slice($list, 0, 5, true) +
			    array('3:2' => esc_html__('3:2', 'anesta')) +
			    array('10:7' => esc_html__('10:7', 'anesta')) +
			    array_slice($list, 5, count($list)-5, true);
		return $list;
	}
}

// Video cover size
if ( ! function_exists( 'anesta_trx_addons_video_cover_thumb_size' ) ) {
	add_filter( 'trx_addons_filter_video_cover_thumb_size', 'anesta_trx_addons_video_cover_thumb_size' );
	function anesta_trx_addons_video_cover_thumb_size( $size ) {
		$size = 'anesta-thumb-huge';
		return $size;
	}
}

// Processing audio in the content
if ( ! function_exists( 'anesta_trx_addons_lazy_load_content_process_audio' ) ) {
	add_filter( 'the_content', 'anesta_trx_addons_lazy_load_content_process_audio', 200, 1 );
	add_filter( 'trx_addons_filter_page_content', 'anesta_trx_addons_lazy_load_content_process_audio' );
	function anesta_trx_addons_lazy_load_content_process_audio( $content, $media = true ) {
		if ( function_exists('trx_addons_lazy_load_enable') && trx_addons_lazy_load_enable() && ! is_admin() ) {

			// Return if tags not exists in the content
			if ( ! preg_match( '/<[a-z]+ /', $content ) ) {
				return $content;
			}

			// Audio
			if ( $media && apply_filters( 'trx_addons_filter_allow_media_lazy_load', true, $content ) ) {
				// Get all items
				preg_match_all( '/<(audio)[^>]*( data-trx-lazyload-src="[^\s]+")[^>]*?>/', $content, $matches );
				$items = array_shift( $matches );
				// Check exists videos
				if ( $items ) {                
					foreach ( $items as $item ) {
						if ( ! preg_match( '/ controls=/', $item ) ) {                    
							// Create original item backup
							$new_item = str_replace( '<audio', '<audio controls="controls"', $item );       
							// Update item
							$content = str_replace( $item, $new_item, $content );
						}
					}
				}
			}
		}

		return $content;
	}
}


// Widgets
//--------------------------------------------------
// Widgets settings
if ( ! function_exists( 'anesta_trx_addons_widget_args' ) ) {
	add_filter( 'trx_addons_filter_widget_args', 'anesta_trx_addons_widget_args', 10, 3 );
	function anesta_trx_addons_widget_args($array, $instance, $sc) {
		// Calendar
		if ( 'trx_addons_widget_calendar'== $sc) {
			if ( isset($instance['image']) ) {
				$image_url = anesta_get_attachment_url( $instance['image'], anesta_get_thumb_size('med') );
				$image = '<img src="' . esc_url($image_url) . '">';
				$array['image'] = $image;
			}
		}
		return $array;
	}
}

// Default widget settings
if ( ! function_exists( 'anesta_trx_addons_widget_args_default' ) ) {
	add_filter( 'trx_addons_filter_widget_args_default', 'anesta_trx_addons_widget_args_default', 10, 2 );
	function anesta_trx_addons_widget_args_default($array, $sc) {
		// Calendar
		if ( 'trx_addons_widget_calendar' == $sc ) {
			$array['image'] = '';
		}
		return $array;
	}
}

// Update default widget settings
if ( ! function_exists( 'anesta_trx_addons_widget_args_update' ) ) {
	add_filter( 'trx_addons_filter_widget_args_update', 'anesta_trx_addons_widget_args_update', 10, 3 );
	function anesta_trx_addons_widget_args_update($instance, $new_instance, $sc) {
		// Calendar
		if ( 'trx_addons_widget_calendar' == $sc ) {
			$instance['image'] = $new_instance['image'];
		}
		return $instance;
	}
}

// Add/remove/update widget controls in the widget editor
if ( ! function_exists( 'anesta_trx_addons_widget_fields' ) ) {
	add_action( 'trx_addons_action_after_widget_fields', 'anesta_trx_addons_widget_fields', 10, 3 );
	function anesta_trx_addons_widget_fields($instance, $sc, $widget) {
		// Calendar
		if ( 'trx_addons_widget_calendar' == $sc ) {
			$widget->show_field(array('name' => 'image',
						'title' => __('Image source URL:', 'anesta'),
						'value' => isset($instance['image']) ? $instance['image'] : '',
						'type' => 'image'));
		}
	}
}

// Add/remove/update Title widget controls in the Elementor editor
if ( ! function_exists( 'anesta_trx_addons_elm_title_controls' ) ) {
	add_filter( 'trx_addons_filter_elementor_add_title_param', 'anesta_trx_addons_elm_title_controls' );
	function anesta_trx_addons_elm_title_controls( $params ) {	
		for(  $i = 0; $i < count($params); $i++ ) {
			if ( $params[$i]['name'] == 'link_image' ) {
				unset($params[$i]);
			}
		}
		return $params;
	}
}

// Add/remove/update widget controls in the Elementor editor
if ( ! function_exists( 'anesta_trx_addons_elm_controls' ) ) {
	add_action( 'elementor/element/before_section_end', 'anesta_trx_addons_elm_controls', 10, 3 );
	function anesta_trx_addons_elm_controls( $element, $section_id, $args ) {		
		if ( is_object( $element ) ) {
			$el_name = $element->get_name();

			// Calendar
			if ( 'trx_widget_calendar' == $el_name  && 'section_sc_calendar' === $section_id ) {
				$element->add_control(
					'image', array(
						'label'       => esc_html__( 'Image', 'anesta' ),						
						'label_block' => false,						
						'type' => \Elementor\Controls_Manager::MEDIA,
						'default' => [
							'url' => '',
						],
					)
				);
			}

			// Slider
			if ( 'trx_widget_slider' == $el_name  && 'section_sc_slider_layout' === $section_id ) {		
				$element->remove_control('large');	

				/* Type of the slides content */
				$control = $element->get_controls('slides_type');
				$control['options'] = [
					'bg' => esc_html__('Background', 'anesta'),
				];
				$element->update_control('slides_type', $control);	
			}
			if ( 'trx_widget_slider' == $el_name  && 'section_sc_slider_controller' === $section_id ) {	
				$element->remove_control('section_sc_slider_controller');				
			}

			// Blogger
			if ( 'trx_sc_blogger' == $el_name  && 'section_sc_blogger' === $section_id ) {
				$element->remove_control('heading_filters');	
				$element->remove_control('filters_title');	
				$element->remove_control('filters_subtitle');	
				$element->remove_control('filters_title_align');	
				$element->remove_control('show_filters');	
				$element->remove_control('filters_tabs_position');	
				$element->remove_control('filters_tabs_on_hover');	
				$element->remove_control('filters_taxonomy');	
				$element->remove_control('filters_ids');	
				$element->remove_control('filters_all');	
				$element->remove_control('filters_all_text');	
				$element->remove_control('filters_more_text');	
			}
			if ( 'trx_sc_blogger' == $el_name  && 'section_sc_blogger_details' === $section_id ) {
				$element->remove_control('image_position');	
				$element->remove_control('full_post');	
				$element->remove_control('on_plate');	
				$element->remove_control('numbers');	

				/* Image ratio */
				$control = $element->get_controls('image_ratio');
				$control['condition'] = [
					'type' => [ 'default' ],
					'template_default' => [ 'classic', 'over' ],
				];
				$element->update_control('image_ratio', $control);	

				/* Image size */
				$control = $element->get_controls('thumb_size');
				$control['condition'] = [
					'type!' => [ 'news', 'list' ],
					'template_default!' => [ 'modern' ],
				];
				$element->update_control('thumb_size', $control);	

				/* Image hover */
				$control = $element->get_controls('hover');
				$control['condition'] = [
					'type!' => [ 'list' ],
					'template_default!' => [ 'modern' ],
				];
				$element->update_control('hover', $control);	

				/* Hide excerpt */
				$control = $element->get_controls('hide_excerpt');
				$control['condition'] = [
					'type!' => [ 'list' ],
				];
				$element->update_control('hide_excerpt', $control);		

				/* Text length (in words) */
				$control = $element->get_controls('excerpt_length');
				$control['condition'] = [
					'type!' => [ 'list' ],
					'hide_excerpt' => '',
				];
				$element->update_control('excerpt_length', $control);	

				/* 'More' text */
				$control = $element->get_controls('more_text');
				$control['condition'] = [
					'type!' => [ 'list' ],
					'more_button' => '1',
					'no_links' => ''
				];
				$element->update_control('more_text', $control);	
			}
		}
	}
}

// Add/remove/update widget controls in the Gutenberg editor
if ( ! function_exists( 'anesta_trx_addons_gb_controls' ) ) {
	add_action( 'trx_addons_gb_map', 'anesta_trx_addons_gb_controls', 10, 2 );
	function anesta_trx_addons_gb_controls( $array, $element ) {
		if ( 'trx-addons/calendar' == $element) {
			$array['attributes']['image'] = array(
												'type'    => 'number',
												'default' => 0,
											);
			$array['attributes']['image_url'] = array(
												'type'    => 'string',
												'default' => '',
											);
		}
		return $array;
	}
}

// Widget output
if ( ! function_exists( 'anesta_trx_addons_widget_output' ) ) {
	add_filter( 'trx_addons_filter_widget_output', 'anesta_trx_addons_widget_output', 10, 3 );
	function anesta_trx_addons_widget_output( $output, $sc, $instance ) {	
		// Calendar	
		if ( 'trx_addons_widget_calendar' == $sc ) {
			$image = isset($instance['image']) ? $instance['image'] : '';
			if ( !empty($image) ) {
				if ( (int) $image > 0 ) {
					$image_url = wp_get_attachment_image_src($image, 'full');
					$image = $image_url[0];
				}
				$output = '<div class="wp-calendar-image ' . anesta_add_inline_css_class('background-image: url(' . esc_url($image) . ')') . '"></div>' . $output;
			}
		}
		return $output;
	}
}


// Shortcode's specific lists to the JS storage
if ( ! function_exists( 'trx_addons_sc_slider_gutenberg_sc_params' ) ) {
	add_filter( 'trx_addons_filter_gutenberg_sc_params', 'trx_addons_sc_slider_gutenberg_sc_params', 11 );
	function trx_addons_sc_slider_gutenberg_sc_params( $vars = array() ) {
		// Type of the slides content
		$vars['slides_type'] = array(
			'bg'     => esc_html__( 'Background', 'anesta' )
		);
		return $vars;
	}
}