<?php
/* Knowledge Base support functions
------------------------------------------------------------------------------- */


// Check if plugin installed and activated
if ( ! function_exists( 'anesta_exists_echo_knowledge_base' ) ) {
	function anesta_exists_echo_knowledge_base() {
		return class_exists( 'Echo_Knowledge_Base' );
	}
}

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'anesta_echo_knowledge_base_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'anesta_echo_knowledge_base_theme_setup3', 3 );
	function anesta_echo_knowledge_base_theme_setup3() {
		if ( anesta_exists_echo_knowledge_base() ) {
			// Section 'Knowledge Base'
			anesta_storage_merge_array(
				'options', '', array_merge(
					array(
						'echo_knowledge_base'     => array(
							'title' => esc_html__( 'Knowledge Base', 'anesta' ),
							'desc'  => wp_kses_data( __( 'Select parameters to display the Knowledge Base pages', 'anesta' ) ),
							'icon'  => 'icon-book-open',
							'type'  => 'section',
						)
					),
					anesta_options_get_list_cpt_options( 'echo_knowledge_base', esc_html__( 'Knowledge Base', 'anesta' ) )
				)
			);
		}
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'anesta_echo_knowledge_base_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'anesta_echo_knowledge_base_theme_setup9', 9 );
	function anesta_echo_knowledge_base_theme_setup9() {
		if ( anesta_exists_echo_knowledge_base() ) {
			
			add_action( 'wp_enqueue_scripts', 'anesta_echo_knowledge_base_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_echo_knowledge_base', 'anesta_echo_knowledge_base_frontend_scripts', 10, 1 );

			add_action( 'wp_enqueue_scripts', 'anesta_echo_knowledge_base_frontend_scripts_responsive', 2000 );
			add_action( 'trx_addons_action_load_scripts_front_echo_knowledge_base', 'anesta_echo_knowledge_base_frontend_scripts_responsive', 10, 1 );

			add_filter( 'anesta_filter_merge_styles', 'anesta_echo_knowledge_base_merge_styles' );
			add_filter( 'anesta_filter_merge_styles_responsive', 'anesta_echo_knowledge_base_merge_styles_responsive' );

			add_action( 'anesta_filter_detect_blog_mode', 'anesta_echo_knowledge_base_detect_blog_mode' );
		}
		if ( is_admin() ) {
            add_filter( 'anesta_filter_tgmpa_required_plugins', 'anesta_echo_knowledge_base_tgmpa_required_plugins' );
        }
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'anesta_echo_knowledge_base_tgmpa_required_plugins' ) ) {    
    function anesta_echo_knowledge_base_tgmpa_required_plugins( $list = array() ) {
        if ( anesta_storage_isset( 'required_plugins', 'echo-knowledge-base' ) && anesta_storage_get_array( 'required_plugins', 'echo-knowledge-base', 'install' ) !== false ) {
            $list[] = array(
                'name'     => anesta_storage_get_array( 'required_plugins', 'echo-knowledge-base', 'title' ),
                'slug'     => 'echo-knowledge-base',
                'required' => false,
            );
        }
        return $list;
    }
}


// Styles & Scripts
//------------------------------------------------------------------------
// Enqueue styles for frontend
if ( ! function_exists( 'anesta_echo_knowledge_base_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_echo_knowledge_base_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_echo_knowledge_base', 'anesta_echo_knowledge_base_frontend_scripts', 10, 1 );
	function anesta_echo_knowledge_base_frontend_scripts( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && anesta_need_frontend_scripts( 'echo_knowledge_base' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$anesta_url = anesta_get_file_url( 'plugins/echo-knowledge-base/echo-knowledge-base.css' );
			if ( '' != $anesta_url ) {
				wp_enqueue_style( 'anesta-echo-knowledge-base', $anesta_url, array(), null );
			}
		}
	}
}

// Enqueue responsive styles for frontend
if ( ! function_exists( 'anesta_echo_knowledge_base_responsive_styles' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_echo_knowledge_base_frontend_scripts_responsive', 2000 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_echo_knowledge_base', 'anesta_echo_knowledge_base_frontend_scripts_responsive', 10, 1 );
	function anesta_echo_knowledge_base_frontend_scripts_responsive( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && anesta_need_frontend_scripts( 'echo_knowledge_base' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$anesta_url = anesta_get_file_url( 'plugins/echo-knowledge-base/echo-knowledge-base-responsive.css' );
			if ( '' != $anesta_url ) {
				wp_enqueue_style( 'anesta-echo-knowledge-base-responsive', $anesta_url, array(), null, anesta_media_for_load_css_responsive( 'echo-knowledge-base' ) );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'anesta_echo_knowledge_base_merge_styles' ) ) {
	//Handler of the add_filter( 'anesta_filter_merge_styles', 'anesta_echo_knowledge_base_merge_styles');
	function anesta_echo_knowledge_base_merge_styles( $list ) {
		$list[ 'plugins/echo-knowledge-base/echo-knowledge-base.css' ] = true;
		return $list;
	}
}

// Merge responsive styles
if ( ! function_exists( 'anesta_echo_knowledge_base_merge_styles_responsive' ) ) {
	//Handler of the add_filter('anesta_filter_merge_styles_responsive', 'anesta_echo_knowledge_base_merge_styles_responsive');
	function anesta_echo_knowledge_base_merge_styles_responsive( $list ) {
		$list[ 'plugins/echo-knowledge-base/echo-knowledge-base-responsive.css' ] = true;
		return $list;
	}
}

// Add plugin-specific colors and fonts to the custom CSS
if ( anesta_exists_echo_knowledge_base() ) {
	require_once anesta_get_file_dir( 'plugins/echo-knowledge-base/echo-knowledge-base-style.php' );
}

// Load required styles and scripts for the frontend
if ( !function_exists( 'anesta_echo_knowledge_base_load_scripts_front' ) ) {
	add_action( "wp_enqueue_scripts", 'anesta_echo_knowledge_base_load_scripts_front', 20 );
	add_action( 'trx_addons_action_pagebuilder_preview_scripts', 'anesta_echo_knowledge_base_load_scripts_front', 10, 1 );
	function anesta_echo_knowledge_base_load_scripts_front( $force = false ) {
		static $loaded = false;
		if ( ! anesta_exists_echo_knowledge_base() || !anesta_exists_trx_addons() ) return;
		$debug    = trx_addons_is_on( trx_addons_get_option( 'debug_mode' ) );
		$optimize = ! trx_addons_is_off( trx_addons_get_option( 'optimize_css_and_js_loading' ) );
		$preview_elm = trx_addons_is_preview( 'elementor' );
		$preview_gb  = trx_addons_is_preview( 'gutenberg' );
		$theme_full  = current_theme_supports( 'styles-and-scripts-full-merged' );
		$need        = ! $loaded && ( ! $preview_elm || $debug ) && ! $preview_gb && $optimize && (
						$force === true
							|| ( $preview_elm && $debug )
							|| trx_addons_sc_check_in_content( array(
									'sc' => 'echo_knowledge_base',
									'entries' => array(
												array( 'type' => 'sc',  'sc' => 'epkb' ),
												array( 'type' => 'elm', 'sc' => '"widgetType":"epkb"' ),
												array( 'type' => 'elm', 'sc' => '"shortcode":"[epkb' ),
									)
								) ) );
		if ( ! $loaded && ! $preview_gb && ( ( ! $optimize && $debug ) || ( $optimize && $need ) ) ) {
			$loaded = true;
			do_action( 'trx_addons_action_load_scripts_front', $force, 'echo_knowledge_base' );
		}
		if ( ! $loaded && $preview_elm && $optimize && ! $debug && ! $theme_full ) {
			do_action( 'trx_addons_action_load_scripts_front', false, 'echo_knowledge_base', 2 );
		}
	}
}

// Load styles and scripts if present in the cache of the menu or layouts or finally in the whole page output
if ( !function_exists( 'anesta_echo_knowledge_base_check_in_html_output' ) ) {
	add_action( 'trx_addons_action_check_page_content', 'anesta_echo_knowledge_base_check_in_html_output', 10, 1 );
	function anesta_echo_knowledge_base_check_in_html_output( $content = '' ) {
		if ( anesta_exists_echo_knowledge_base()
			&& ! trx_addons_need_frontend_scripts( 'echo_knowledge_base' )
			&& ! trx_addons_is_off( trx_addons_get_option( 'optimize_css_and_js_loading' ) )
		) {
			$checklist = apply_filters( 'trx_addons_filter_check_in_html', array(
							'class=[\'"][^\'"]*epkb',
							),
							'echo_knowledge_base'
						);
			foreach ( $checklist as $item ) {
				if ( preg_match( "#{$item}#", $content, $matches ) ) {
					anesta_echo_knowledge_base_load_scripts_front( true );
					break;
				}
			}
		}
		return $content;
	}
}

// Remove plugin-specific styles if present in the page head output
if ( !function_exists( 'anesta_echo_knowledge_base_filter_head_output' ) ) {
	add_filter( 'trx_addons_filter_page_head', 'anesta_echo_knowledge_base_filter_head_output', 10, 1 );
	function anesta_echo_knowledge_base_filter_head_output( $content = '' ) {
		if ( anesta_exists_echo_knowledge_base()
			&& trx_addons_get_option( 'optimize_css_and_js_loading' ) == 'full'
			&& ! trx_addons_is_preview()
			&& ! trx_addons_need_frontend_scripts( 'echo_knowledge_base' )
			&& apply_filters( 'trx_addons_filter_remove_3rd_party_styles', true, 'echo_knowledge_base' )
		) {
			$content = preg_replace( '#<link[^>]*href=[\'"][^\'"]*/echo-knowledge-base/[^>]*>#', '', $content );
		}
		return $content;
	}
}

// Remove plugin-specific styles and scripts if present in the page body output
if ( !function_exists( 'anesta_echo_knowledge_base_filter_body_output' ) ) {
	add_filter( 'trx_addons_filter_page_content', 'anesta_echo_knowledge_base_filter_body_output', 10, 1 );
	function anesta_echo_knowledge_base_filter_body_output( $content = '' ) {
		if ( anesta_exists_echo_knowledge_base()
			&& trx_addons_get_option( 'optimize_css_and_js_loading' ) == 'full'
			&& ! trx_addons_is_preview()
			&& ! trx_addons_need_frontend_scripts( 'echo_knowledge_base' )
			&& apply_filters( 'trx_addons_filter_remove_3rd_party_styles', true, 'echo_knowledge_base' )
		) {
			$content = preg_replace( '#<link[^>]*href=[\'"][^\'"]*/echo-knowledge-base/[^>]*>#', '', $content );
			$content = preg_replace( '#<script[^>]*src=[\'"][^\'"]*/echo-knowledge-base/[^>]*>[\\s\\S]*</script>#U', '', $content );
			$content = preg_replace( '#<script[^>]*id=[\'"]echo-knowledge-base[^>]*>[\\s\\S]*</script>#U', '', $content );
		}
		return $content;
	}
}


// Other
//------------------------------------------------------------------------
// Detect current blog mode
if ( ! function_exists( 'anesta_echo_knowledge_base_detect_blog_mode' ) ) {
	//Handler of the add_filter( 'anesta_filter_detect_blog_mode', 'anesta_echo_knowledge_base_detect_blog_mode' );
	function anesta_echo_knowledge_base_detect_blog_mode( $mode = '' ) {
		if ( anesta_exists_echo_knowledge_base() ) {			
			if ( EPKB_KB_Handler::is_kb_post_type( get_post_type() ) ) {
				$mode = 'echo_knowledge_base';
			}
		}
		return $mode;
	}
}


// One-click import support
//------------------------------------------------------------------------
// Set plugin's specific importer options
if ( !function_exists( 'anesta_echo_knowledge_base_importer_set_options' ) ) {
	add_filter( 'trx_addons_filter_importer_options',	'anesta_echo_knowledge_base_importer_set_options' );
	function anesta_echo_knowledge_base_importer_set_options($options=array()) {
		if ( anesta_exists_echo_knowledge_base() && in_array('echo-knowledge-base', $options['required_plugins']) ) {
			$options['additional_options'][]	= '%epkb%';
		}
		return $options;
	}
}