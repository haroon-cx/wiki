<?php
/* Better Messages support functions
------------------------------------------------------------------------------- */


// Check if plugin installed and activated
if ( ! function_exists( 'anesta_exists_bp_better_messages' ) ) {
	function anesta_exists_bp_better_messages() {
		return class_exists( 'BP_Better_Messages' );
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'anesta_bp_better_messages_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'anesta_bp_better_messages_theme_setup9', 9 );
	function anesta_bp_better_messages_theme_setup9() {
		if ( anesta_exists_bp_better_messages() ) {
			add_action( 'wp_enqueue_scripts', 'anesta_bp_better_messages_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_bp_better_messages', 'anesta_bp_better_messages_frontend_scripts', 10, 1 );

			add_action( 'wp_enqueue_scripts', 'anesta_bp_better_messages_frontend_scripts_responsive', 2000 );
			add_action( 'trx_addons_action_load_scripts_front_bp_better_messages', 'anesta_bp_better_messages_frontend_scripts_responsive', 10, 1 );
			
			add_filter( 'anesta_filter_merge_styles', 'anesta_bp_better_messages_merge_styles' );
			add_filter( 'anesta_filter_merge_styles_responsive', 'anesta_bp_better_messages_merge_styles_responsive' );
		}
		if ( is_admin() ) {
            add_filter( 'anesta_filter_tgmpa_required_plugins', 'anesta_bp_better_messages_tgmpa_required_plugins' );
        }
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'anesta_bp_better_messages_tgmpa_required_plugins' ) ) {    
    function anesta_bp_better_messages_tgmpa_required_plugins( $list = array() ) {
        if ( anesta_storage_isset( 'required_plugins', 'bp-better-messages' ) && anesta_storage_get_array( 'required_plugins', 'bp-better-messages', 'install' ) !== false ) {
            $list[] = array(
                'name'     => anesta_storage_get_array( 'required_plugins', 'bp-better-messages', 'title' ),
                'slug'     => 'bp-better-messages',
                'required' => false,
            );
        }
        return $list;
    }
}


// Styles & Scripts
//------------------------------------------------------------------------
// Enqueue styles for frontend
if ( ! function_exists( 'anesta_bp_better_messages_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_bp_better_messages_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_bp_better_messages', 'anesta_bp_better_messages_frontend_scripts', 10, 1 );
	function anesta_bp_better_messages_frontend_scripts( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && anesta_need_frontend_scripts( 'bp_better_messages' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$anesta_url = anesta_get_file_url( 'plugins/bp-better-messages/bp-better-messages.css' );
			if ( '' != $anesta_url ) {
				wp_enqueue_style( 'anesta-bp-better-messages', $anesta_url, array(), null );
			}
		}
	}
}

// Enqueue responsive styles for frontend
if ( ! function_exists( 'anesta_bp_better_messages_frontend_scripts_responsive' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_bp_better_messages_frontend_scripts_responsive', 2000 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_bp_better_messages', 'anesta_bp_better_messages_frontend_scripts_responsive', 10, 1 );
	function anesta_bp_better_messages_frontend_scripts_responsive( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && anesta_need_frontend_scripts( 'bp_better_messages' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$anesta_url = anesta_get_file_url( 'plugins/bp-better-messages/bp-better-messages-responsive.css' );
			if ( '' != $anesta_url ) {
				wp_enqueue_style( 'anesta-bp-better-messages-responsive', $anesta_url, array(), null, anesta_media_for_load_css_responsive( 'bp-better-messages' ) );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'anesta_bp_better_messages_merge_styles' ) ) {
	//Handler of the add_filter( 'anesta_filter_merge_styles', 'anesta_bp_better_messages_merge_styles');
	function anesta_bp_better_messages_merge_styles( $list ) {
		$list[ 'plugins/bp-better-messages/bp-better-messages.css' ] = false;
		return $list;
	}
}

// Merge responsive styles
if ( ! function_exists( 'anesta_bp_better_messages_merge_styles_responsive' ) ) {
	//Handler of the add_filter('anesta_filter_merge_styles_responsive', 'anesta_bp_better_messages_merge_styles_responsive');
	function anesta_bp_better_messages_merge_styles_responsive( $list ) {
		$list[ 'plugins/bp-better-messages/bp-better-messages-responsive.css' ] = false;
		return $list;
	}
}

// Add plugin-specific colors and fonts to the custom CSS
if ( anesta_exists_bp_better_messages() ) {
	require_once anesta_get_file_dir( 'plugins/bp-better-messages/bp-better-messages-style.php' );
}

// Load required styles and scripts for the frontend
if ( !function_exists( 'anesta_bp_better_messages_load_scripts_front' ) ) {
	add_action( "wp_enqueue_scripts", 'anesta_bp_better_messages_load_scripts_front', 20 );
	add_action( 'trx_addons_action_pagebuilder_preview_scripts', 'anesta_bp_better_messages_load_scripts_front', 10, 1 );
	function anesta_bp_better_messages_load_scripts_front( $force = false ) {
		static $loaded = false;
		if ( ! anesta_exists_bp_better_messages() || !anesta_exists_trx_addons() ) return;
		$debug    = trx_addons_is_on( trx_addons_get_option( 'debug_mode' ) );
		$optimize = ! trx_addons_is_off( trx_addons_get_option( 'optimize_css_and_js_loading' ) );
		$preview_elm = trx_addons_is_preview( 'elementor' );
		$preview_gb  = trx_addons_is_preview( 'gutenberg' );
		$theme_full  = current_theme_supports( 'styles-and-scripts-full-merged' );
		$need        = ! $loaded && ( ! $preview_elm || $debug ) && ! $preview_gb && $optimize && (
						$force === true
							|| ( $preview_elm && $debug )
							|| trx_addons_sc_check_in_content( array(
									'sc' => 'bp_better_messages',
									'entries' => array(
												array( 'type' => 'sc',  'sc' => 'bp_better_messages' ),
												//array( 'type' => 'gb',  'sc' => 'wp:trx-addons/charts' ),// This sc is not exists for GB
												array( 'type' => 'elm', 'sc' => '"widgetType":"bp_better_messages"' ),
												array( 'type' => 'elm', 'sc' => '"shortcode":"[bp_better_messages' ),
									)
								) ) );
		if ( ! $loaded && ! $preview_gb && ( ( ! $optimize && $debug ) || ( $optimize && $need ) ) ) {
			$loaded = true;
			do_action( 'trx_addons_action_load_scripts_front', $force, 'bp_better_messages' );
		}
		if ( ! $loaded && $preview_elm && $optimize && ! $debug && ! $theme_full ) {
			do_action( 'trx_addons_action_load_scripts_front', false, 'bp_better_messages', 2 );
		}
	}
}

// Load styles and scripts if present in the cache of the menu or layouts or finally in the whole page output
if ( !function_exists( 'anesta_bp_better_messages_check_in_html_output' ) ) {
	add_action( 'trx_addons_action_check_page_content', 'anesta_bp_better_messages_check_in_html_output', 10, 1 );
	function anesta_bp_better_messages_check_in_html_output( $content = '' ) {
		if ( anesta_exists_bp_better_messages()
			&& ! trx_addons_need_frontend_scripts( 'bp_better_messages' )
			&& ! trx_addons_is_off( trx_addons_get_option( 'optimize_css_and_js_loading' ) )
		) {
			$checklist = apply_filters( 'trx_addons_filter_check_in_html', array(
							'class=[\'"][^\'"]*bp-messages',
							),
							'bp_better_messages'
						);
			foreach ( $checklist as $item ) {
				if ( preg_match( "#{$item}#", $content, $matches ) ) {
					anesta_bp_better_messages_load_scripts_front( true );
					break;
				}
			}
		}
		return $content;
	}
}

// Remove plugin-specific styles if present in the page head output
if ( !function_exists( 'anesta_bp_better_messages_filter_head_output' ) ) {
	add_filter( 'trx_addons_filter_page_head', 'anesta_bp_better_messages_filter_head_output', 10, 1 );
	function anesta_bp_better_messages_filter_head_output( $content = '' ) {
		if ( anesta_exists_bp_better_messages()
			&& trx_addons_get_option( 'optimize_css_and_js_loading' ) == 'full'
			&& ! trx_addons_is_preview()
			&& ! trx_addons_need_frontend_scripts( 'bp_better_messages' )
			&& apply_filters( 'trx_addons_filter_remove_3rd_party_styles', true, 'bp_better_messages' )
		) {
			$content = preg_replace( '#<link[^>]*href=[\'"][^\'"]*/bp-better-messages/[^>]*>#', '', $content );
		}
		return $content;
	}
}

// Remove plugin-specific styles and scripts if present in the page body output
if ( !function_exists( 'anesta_bp_better_messages_filter_body_output' ) ) {
	add_filter( 'trx_addons_filter_page_content', 'anesta_bp_better_messages_filter_body_output', 10, 1 );
	function anesta_bp_better_messages_filter_body_output( $content = '' ) {
		if ( anesta_exists_bp_better_messages()
			&& trx_addons_get_option( 'optimize_css_and_js_loading' ) == 'full'
			&& ! trx_addons_is_preview()
			&& ! trx_addons_need_frontend_scripts( 'bp_better_messages' )
			&& apply_filters( 'trx_addons_filter_remove_3rd_party_styles', true, 'bp_better_messages' )
		) {
			$content = preg_replace( '#<link[^>]*href=[\'"][^\'"]*/bp-better-messages/[^>]*>#', '', $content );
			$content = preg_replace( '#<script[^>]*src=[\'"][^\'"]*/bp-better-messages/[^>]*>[\\s\\S]*</script>#U', '', $content );
			$content = preg_replace( '#<script[^>]*id=[\'"]bp-better-messages[^>]*>[\\s\\S]*</script>#U', '', $content );
		}
		return $content;
	}
}


// One-click import support
//------------------------------------------------------------------------
// Set plugin's specific importer options
if ( !function_exists( 'anesta_bp_better_messages_importer_set_options' ) ) {
	add_filter( 'trx_addons_filter_importer_options',	'anesta_bp_better_messages_importer_set_options' );
	function anesta_bp_better_messages_importer_set_options($options=array()) {
		if ( anesta_exists_bp_better_messages() && in_array('bp-better-messages', $options['required_plugins']) ) {
			$options['additional_options'][]	= '%bp_messages%';
			$options['additional_options'][]	= 'bp-better-chat-settings';
		}
		return $options;
	}
}