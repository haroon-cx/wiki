<?php
/* BuddyPress Docs support functions
------------------------------------------------------------------------------- */


// Check if plugin installed and activated
if ( ! function_exists( 'anesta_exists_buddypress_docs' ) ) {
	function anesta_exists_buddypress_docs() {
		return class_exists( 'BP_Docs' );
	}
}

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'anesta_buddypress_docs_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'anesta_buddypress_docs_theme_setup3', 3 );
	function anesta_buddypress_docs_theme_setup3() {
		if ( anesta_exists_buddypress_docs() ) {
			// Section 'BuddyPress Docs'
			anesta_storage_merge_array(
				'options', '', array_merge(
					array(
						'buddypress_docs'     => array(
							'title' => esc_html__( 'BuddyPress Docs', 'anesta' ),
							'desc'  => wp_kses_data( __( 'Select parameters to display the BuddyPress Docs pages', 'anesta' ) ),
							'icon'  => 'icon-docs',
							'type'  => 'section',
						)
					),
					anesta_options_get_list_cpt_options( 'buddypress_docs', esc_html__( 'BuddyPress Docs', 'anesta' ) )
				)
			);
		}
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'anesta_buddypress_docs_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'anesta_buddypress_docs_theme_setup9', 9 );
	function anesta_buddypress_docs_theme_setup9() {
		if ( anesta_exists_buddypress_docs() ) {
			add_action( 'wp_enqueue_scripts', 'anesta_buddypress_docs_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_buddypress_docs', 'anesta_buddypress_docs_frontend_scripts', 10, 1 );

			add_action( 'wp_enqueue_scripts', 'anesta_buddypress_docs_responsive_styles', 2000 );			
			add_action( 'trx_addons_action_load_scripts_front_buddypress_docs', 'anesta_buddypress_docs_responsive_styles', 10, 1 );
			
			add_filter( 'anesta_filter_merge_styles', 'anesta_buddypress_docs_merge_styles' );
			add_filter( 'anesta_filter_merge_styles_responsive', 'anesta_buddypress_docs_merge_styles_responsive' );
			
			add_action( 'anesta_filter_detect_blog_mode', 'anesta_buddypress_docs_detect_blog_mode' );
		}
		if ( is_admin() ) {
            add_filter( 'anesta_filter_tgmpa_required_plugins', 'anesta_buddypress_docs_tgmpa_required_plugins' );
        }
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'anesta_buddypress_docs_tgmpa_required_plugins' ) ) {    
    function anesta_buddypress_docs_tgmpa_required_plugins( $list = array() ) {
        if ( anesta_storage_isset( 'required_plugins', 'buddypress-docs' ) && anesta_storage_get_array( 'required_plugins', 'buddypress-docs', 'install' ) !== false ) {
            $list[] = array(
                'name'     => anesta_storage_get_array( 'required_plugins', 'buddypress-docs', 'title' ),
                'slug'     => 'buddypress-docs',
                'required' => false,
            );
        }
        return $list;
    }
}


// Styles & Scripts
//------------------------------------------------------------------------
// Enqueue styles for frontend
if ( ! function_exists( 'anesta_buddypress_docs_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_buddypress_docs_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_buddypress_docs', 'anesta_buddypress_docs_frontend_scripts', 10, 1 );
	function anesta_buddypress_docs_frontend_scripts( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && anesta_need_frontend_scripts( 'buddypress_docs' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
		$anesta_url = anesta_get_file_url( 'plugins/buddypress-docs/buddypress-docs.css' );
			if ( '' != $anesta_url ) {
				wp_enqueue_style( 'anesta-buddypress-docs', $anesta_url, array(), null );
			}
		}
	}
}

// Enqueue responsive styles for frontend
if ( ! function_exists( 'anesta_buddypress_docs_responsive_styles' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_buddypress_docs_responsive_styles', 2000 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_buddypress_docs', 'anesta_buddypress_docs_responsive_styles', 10, 1 );
	function anesta_buddypress_docs_responsive_styles( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && anesta_need_frontend_scripts( 'buddypress_docs' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$anesta_url = anesta_get_file_url( 'plugins/buddypress-docs/buddypress-docs-responsive.css' );
			if ( '' != $anesta_url ) {
				wp_enqueue_style( 'anesta-buddypress-docs-responsive', $anesta_url, array(), null, anesta_media_for_load_css_responsive( 'buddypress-docs' ) );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'anesta_buddypress_docs_merge_styles' ) ) {
	//Handler of the add_filter( 'anesta_filter_merge_styles', 'anesta_buddypress_docs_merge_styles');
	function anesta_buddypress_docs_merge_styles( $list ) {
		$list[ 'plugins/buddypress-docs/buddypress-docs.css' ] = true;
		return $list;
	}
}

// Merge responsive styles
if ( ! function_exists( 'anesta_buddypress_docs_merge_styles_responsive' ) ) {
	//Handler of the add_filter('anesta_filter_merge_styles_responsive', 'anesta_buddypress_docs_merge_styles_responsive');
	function anesta_buddypress_docs_merge_styles_responsive( $list ) {
		$list[ 'plugins/buddypress-docs/buddypress-docs-responsive.css' ] = true;
		return $list;
	}
}

// Add plugin-specific colors and fonts to the custom CSS
if ( anesta_exists_buddypress_docs() ) {
	require_once anesta_get_file_dir( 'plugins/buddypress-docs/buddypress-docs-style.php' );
}

// Load required styles and scripts for the frontend
if ( !function_exists( 'anesta_buddypress_docs_load_scripts_front' ) ) {
	add_action( "wp_enqueue_scripts", 'anesta_buddypress_docs_load_scripts_front', 20 );
	add_action( 'trx_addons_action_pagebuilder_preview_scripts', 'anesta_buddypress_docs_load_scripts_front', 10, 1 );
	function anesta_buddypress_docs_load_scripts_front( $force = false ) {
		static $loaded = false;
		if ( ! anesta_exists_buddypress_docs() || !anesta_exists_trx_addons() ) return;
		$debug    = trx_addons_is_on( trx_addons_get_option( 'debug_mode' ) );
		$optimize = ! trx_addons_is_off( trx_addons_get_option( 'optimize_css_and_js_loading' ) );
		$preview_elm = trx_addons_is_preview( 'elementor' );
		$preview_gb  = trx_addons_is_preview( 'gutenberg' );
		$theme_full  = current_theme_supports( 'styles-and-scripts-full-merged' );
		$need        = ! $loaded && ( ! $preview_elm || $debug ) && ! $preview_gb && $optimize && (
						$force === true
							|| ( $preview_elm && $debug )
							|| trx_addons_sc_check_in_content( array(
									'sc' => 'buddypress_docs',
									'entries' => array(
												array( 'type' => 'sc',  'sc' => 'bp_docs' ),
												//array( 'type' => 'gb',  'sc' => 'wp:trx-addons/charts' ),// This sc is not exists for GB
												array( 'type' => 'elm', 'sc' => '"widgetType":"bp_docs"' ),
												array( 'type' => 'elm', 'sc' => '"shortcode":"[bp_docs' ),
									)
								) ) );
		if ( ! $loaded && ! $preview_gb && ( ( ! $optimize && $debug ) || ( $optimize && $need ) ) ) {
			$loaded = true;
			do_action( 'trx_addons_action_load_scripts_front', $force, 'buddypress_docs' );
		}
		if ( ! $loaded && $preview_elm && $optimize && ! $debug && ! $theme_full ) {
			do_action( 'trx_addons_action_load_scripts_front', false, 'buddypress_docs', 2 );
		}
	}
}

// Load styles and scripts if present in the cache of the menu or layouts or finally in the whole page output
if ( !function_exists( 'anesta_buddypress_docs_check_in_html_output' ) ) {
	add_action( 'trx_addons_action_check_page_content', 'anesta_buddypress_docs_check_in_html_output', 10, 1 );
	function anesta_buddypress_docs_check_in_html_output( $content = '' ) {
		if ( anesta_exists_buddypress_docs()
			&& ! trx_addons_need_frontend_scripts( 'buddypress_docs' )
			&& ! trx_addons_is_off( trx_addons_get_option( 'optimize_css_and_js_loading' ) )
		) {
			$checklist = apply_filters( 'trx_addons_filter_check_in_html', array(
							'class=[\'"][^\'"]*bp-docs',
							'class=[\'"][^\'"]*bp_docs',
							),
							'buddypress_docs'
						);
			foreach ( $checklist as $item ) {
				if ( preg_match( "#{$item}#", $content, $matches ) ) {
					anesta_buddypress_docs_load_scripts_front( true );
					break;
				}
			}
		}
		return $content;
	}
}

// Remove plugin-specific styles if present in the page head output
if ( !function_exists( 'anesta_buddypress_docs_filter_head_output' ) ) {
	add_filter( 'trx_addons_filter_page_head', 'anesta_buddypress_docs_filter_head_output', 10, 1 );
	function anesta_buddypress_docs_filter_head_output( $content = '' ) {
		if ( anesta_exists_buddypress_docs()
			&& trx_addons_get_option( 'optimize_css_and_js_loading' ) == 'full'
			&& ! trx_addons_is_preview()
			&& ! trx_addons_need_frontend_scripts( 'buddypress_docs' )
			&& apply_filters( 'trx_addons_filter_remove_3rd_party_styles', true, 'buddypress_docs' )
		) {
			$content = preg_replace( '#<link[^>]*href=[\'"][^\'"]*/buddypress-docs/[^>]*>#', '', $content );
		}
		return $content;
	}
}

// Remove plugin-specific styles and scripts if present in the page body output
if ( !function_exists( 'anesta_buddypress_docs_filter_body_output' ) ) {
	add_filter( 'trx_addons_filter_page_content', 'anesta_buddypress_docs_filter_body_output', 10, 1 );
	function anesta_buddypress_docs_filter_body_output( $content = '' ) {
		if ( anesta_exists_buddypress_docs()
			&& trx_addons_get_option( 'optimize_css_and_js_loading' ) == 'full'
			&& ! trx_addons_is_preview()
			&& ! trx_addons_need_frontend_scripts( 'buddypress_docs' )
			&& apply_filters( 'trx_addons_filter_remove_3rd_party_styles', true, 'buddypress_docs' )
		) {
			$content = preg_replace( '#<link[^>]*href=[\'"][^\'"]*/buddypress-docs/[^>]*>#', '', $content );
			$content = preg_replace( '#<script[^>]*src=[\'"][^\'"]*/buddypress-docs/[^>]*>[\\s\\S]*</script>#U', '', $content );
			$content = preg_replace( '#<script[^>]*id=[\'"]buddypress-docs[^>]*>[\\s\\S]*</script>#U', '', $content );
		}
		return $content;
	}
}


// Other
//------------------------------------------------------------------------
// Detect current blog mode
if ( ! function_exists( 'anesta_buddypress_docs_detect_blog_mode' ) ) {
	//Handler of the add_filter( 'anesta_filter_detect_blog_mode', 'anesta_buddypress_docs_detect_blog_mode' );
	function anesta_buddypress_docs_detect_blog_mode( $mode = '' ) {
		if ( anesta_exists_buddypress_docs() ) {			
			if ( bp_docs_is_single_doc() || bp_docs_is_global_directory() || bp_docs_is_mygroups_directory() || bp_docs_is_doc_create() ) {
				$mode = 'buddypress_docs';
			}
		}
		return $mode;
	}
}

// Page title
if ( ! function_exists( 'anesta_buddypress_docs_page_title' ) ) {
	add_filter( 'anesta_skin_filter_page_title', 'anesta_buddypress_docs_page_title' );
	function anesta_buddypress_docs_page_title( $allow ) {	
		if ( anesta_exists_buddypress_docs() ) {
			return bp_docs_is_single_doc() ? true : $allow;
		}
		return $allow;
	}
}

// Widget title
if ( ! function_exists( 'anesta_buddypress_docs_widget_title' ) ) {
	add_filter( 'widget_title', 'anesta_buddypress_docs_widget_title', 10, 3 );
	function anesta_buddypress_docs_widget_title( $title, $instance='', $id_base='' ) {	 
		if ( anesta_exists_buddypress_docs() ) {
			/* View All groups */
			if ( 'widget_recent_buddypress_docs' == $id_base ) { 
				// Get the existing WP pages.
				$existing_pages = bp_docs_get_directory_url();
				if ( ! empty( $existing_pages ) ) { 
					$title .= 	'<div class="sc_button_wrap">
									<a href="' . esc_url( $existing_pages ) . '" class="sc_button sc_button_simple sc_button_size_normal sc_button_icon_left">
										<span class="sc_button_text">
											<span class="sc_button_title">' . esc_html__( 'View All', 'anesta' ) . '</span>
										</span>
									</a>
								</div>';
				}
			}
		}
		return $title;
	}
} 



// One-click import support
//------------------------------------------------------------------------
// Set plugin's specific importer options
if ( !function_exists( 'anesta_buddypress_docs_importer_set_options' ) ) {
	add_filter( 'trx_addons_filter_importer_options',	'anesta_buddypress_docs_importer_set_options' );
	function anesta_buddypress_docs_importer_set_options($options=array()) {
		 if ( anesta_exists_buddypress_docs() && in_array('buddypress-docs', $options['required_plugins']) ) {
			$options['additional_options'][]	= '%bp_doc%';
		}
		return $options;
	}
}