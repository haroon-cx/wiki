<?php
/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'anesta_mailchimp_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'anesta_mailchimp_theme_setup9', 9 );
	function anesta_mailchimp_theme_setup9() {
		if ( anesta_exists_mailchimp() ) {
			add_action( 'wp_enqueue_scripts', 'anesta_mailchimp_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_mailchimp', 'anesta_mailchimp_frontend_scripts', 10, 1 );
			add_filter( 'anesta_filter_merge_styles', 'anesta_mailchimp_merge_styles' );
		}
		if ( is_admin() ) {
			add_filter( 'anesta_filter_tgmpa_required_plugins', 'anesta_mailchimp_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'anesta_mailchimp_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('anesta_filter_tgmpa_required_plugins',	'anesta_mailchimp_tgmpa_required_plugins');
	function anesta_mailchimp_tgmpa_required_plugins( $list = array() ) {
		if ( anesta_storage_isset( 'required_plugins', 'mailchimp-for-wp' ) && anesta_storage_get_array( 'required_plugins', 'mailchimp-for-wp', 'install' ) !== false ) {
			$list[] = array(
				'name'     => anesta_storage_get_array( 'required_plugins', 'mailchimp-for-wp', 'title' ),
				'slug'     => 'mailchimp-for-wp',
				'required' => false,
			);
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( ! function_exists( 'anesta_exists_mailchimp' ) ) {
	function anesta_exists_mailchimp() {
		return function_exists( '__mc4wp_load_plugin' ) || defined( 'MC4WP_VERSION' );
	}
}



// Custom styles and scripts
//------------------------------------------------------------------------

// Enqueue styles for frontend
if ( ! function_exists( 'anesta_mailchimp_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_mailchimp_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_mailchimp', 'anesta_mailchimp_frontend_scripts', 10, 1 );
	function anesta_mailchimp_frontend_scripts( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && anesta_need_frontend_scripts( 'mailchimp' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$anesta_url = anesta_get_file_url( 'plugins/mailchimp-for-wp/mailchimp-for-wp.css' );
			if ( '' != $anesta_url ) {
				wp_enqueue_style( 'anesta-mailchimp-for-wp', $anesta_url, array(), null );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'anesta_mailchimp_merge_styles' ) ) {
	//Handler of the add_filter( 'anesta_filter_merge_styles', 'anesta_mailchimp_merge_styles');
	function anesta_mailchimp_merge_styles( $list ) {
		$list[ 'plugins/mailchimp-for-wp/mailchimp-for-wp.css' ] = false;
		return $list;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if ( anesta_exists_mailchimp() ) {
	$anesta_fdir = anesta_get_file_dir( 'plugins/mailchimp-for-wp/mailchimp-for-wp-style.php' );
	if ( ! empty( $anesta_fdir ) ) {
		require_once $anesta_fdir;
	}
}

