<?php
/* ThemeREX Popup support functions
------------------------------------------------------------------------------- */


// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'anesta_trx_popup_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'anesta_trx_popup_theme_setup9', 9 );
	function anesta_trx_popup_theme_setup9() {
		if ( anesta_exists_trx_popup() ) {
			add_action( 'wp_enqueue_scripts', 'anesta_trx_popup_frontend_scripts', 1100 );
			add_filter( 'anesta_filter_merge_styles', 'anesta_trx_popup_merge_styles' );
		}
		if ( is_admin() ) {
			add_filter( 'anesta_filter_tgmpa_required_plugins', 'anesta_trx_popup_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'anesta_trx_popup_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter( 'anesta_filter_tgmpa_required_plugins',	'anesta_trx_popup_tgmpa_required_plugins' );
	function anesta_trx_popup_tgmpa_required_plugins( $list = array() ) {
		if ( anesta_storage_isset( 'required_plugins', 'trx_popup' ) && anesta_storage_get_array( 'required_plugins', 'trx_popup', 'install' ) !== false && anesta_is_theme_activated() ) {
			$path = anesta_get_plugin_source_path( 'plugins/trx_popup/trx_popup.zip' );
			if ( ! empty( $path ) || anesta_get_theme_setting( 'tgmpa_upload' ) ) {
				$list[] = array(
					'name'     => anesta_storage_get_array( 'required_plugins', 'trx_popup', 'title' ),
					'slug'     => 'trx_popup',
					'source'   => ! empty( $path ) ? $path : 'upload://trx_popup.zip',
					'version'  => '1.0',
					'required' => false,
				);
			}
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( ! function_exists( 'anesta_exists_trx_popup' ) ) {
	function anesta_exists_trx_popup() {
		return defined( 'TRX_POPUP_URL' );
	}
}

// Enqueue custom scripts
if ( ! function_exists( 'anesta_trx_popup_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_trx_popup_frontend_scripts', 1100 );
	function anesta_trx_popup_frontend_scripts() {
		if ( anesta_is_on( anesta_get_theme_option( 'debug_mode' ) ) ) {
			$anesta_url = anesta_get_file_url( 'plugins/trx_popup/trx_popup.css' );
			if ( '' != $anesta_url ) {
				wp_enqueue_style( 'anesta-trx-popup', $anesta_url, array(), null );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'anesta_trx_popup_merge_styles' ) ) {
	//Handler of the add_filter('anesta_filter_merge_styles', 'anesta_trx_popup_merge_styles');
	function anesta_trx_popup_merge_styles( $list ) {
		$list[ 'plugins/trx_popup/trx_popup.css' ] = true;
		return $list;
	}
}

// Add plugin-specific colors and fonts to the custom CSS
if ( anesta_exists_trx_popup() ) {
	$anesta_fdir = anesta_get_file_dir( 'plugins/trx_popup/trx_popup-style.php' );
	if ( ! empty( $anesta_fdir ) ) {
		require_once $anesta_fdir;
	}
}
