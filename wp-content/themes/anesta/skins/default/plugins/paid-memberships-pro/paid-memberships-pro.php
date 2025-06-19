<?php
/* Paid Memberships Pro support functions
------------------------------------------------------------------------------- */


// Check if plugin installed and activated
if ( ! function_exists( 'anesta_exists_paid_memberships_pro' ) ) {
	function anesta_exists_paid_memberships_pro() {
		return class_exists( 'PMPro_Membership_Level' );
	}
}

// Plugin init
if ( ! function_exists( 'anesta_paid_memberships_pro_init' ) ) {
	add_action( 'init', 'anesta_paid_memberships_pro_init', 9 );
	function anesta_paid_memberships_pro_init() {
		if ( anesta_exists_paid_memberships_pro() ) {	
			if ( anesta_is_on( anesta_get_theme_option( 'enable_login_privacy' ) ) ) {
				remove_action("login_init", "pmpro_redirect_to_logged_in", 5);
				add_filter('pmpro_register_redirect', '__return_false');
				remove_action( 'lostpassword_url', 'wc_lostpassword_url', 10 );
			}
		}
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'anesta_paid_memberships_pro_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'anesta_paid_memberships_pro_theme_setup9', 9 );
	function anesta_paid_memberships_pro_theme_setup9() {
		if ( anesta_exists_paid_memberships_pro() ) {
			add_action( 'wp_enqueue_scripts', 'anesta_paid_memberships_pro_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_paid_memberships_pro', 'anesta_paid_memberships_pro_frontend_scripts', 10, 1 );

			add_action( 'wp_enqueue_scripts', 'anesta_paid_memberships_pro_frontend_scripts_responsive', 2000 );
			add_action( 'trx_addons_action_load_scripts_front_paid_memberships_pro', 'anesta_paid_memberships_pro_frontend_scripts_responsive', 10, 1 );
			
			add_filter( 'anesta_filter_merge_styles', 'anesta_paid_memberships_pro_merge_styles' );
			add_filter( 'anesta_filter_merge_styles_responsive', 'anesta_paid_memberships_pro_merge_styles_responsive' );
		}
		if ( is_admin() ) {
            add_filter( 'anesta_filter_tgmpa_required_plugins', 'anesta_paid_memberships_pro_tgmpa_required_plugins' );
        }
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'anesta_paid_memberships_pro_tgmpa_required_plugins' ) ) {    
    function anesta_paid_memberships_pro_tgmpa_required_plugins( $list = array() ) {
        if ( anesta_storage_isset( 'required_plugins', 'paid-memberships-pro' ) && anesta_storage_get_array( 'required_plugins', 'paid-memberships-pro', 'install' ) !== false ) {
            $list[] = array(
                'name'     => anesta_storage_get_array( 'required_plugins', 'paid-memberships-pro', 'title' ),
                'slug'     => 'paid-memberships-pro',
                'required' => false,
            );
        }
        return $list;
    }
}


// Styles & Scripts
//------------------------------------------------------------------------
// Enqueue styles for frontend
if ( ! function_exists( 'anesta_paid_memberships_pro_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_paid_memberships_pro_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_paid_memberships_pro', 'anesta_paid_memberships_pro_frontend_scripts', 10, 1 );
	function anesta_paid_memberships_pro_frontend_scripts( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && anesta_need_frontend_scripts( 'paid_memberships_pro' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$anesta_url = anesta_get_file_url( 'plugins/paid-memberships-pro/paid-memberships-pro.css' );
			if ( '' != $anesta_url ) {
				wp_enqueue_style( 'anesta-paid-memberships-pro', $anesta_url, array(), null );
			}
		}
	}
}

// Enqueue responsive styles for frontend
if ( ! function_exists( 'anesta_paid_memberships_pro_frontend_scripts_responsive' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_paid_memberships_pro_frontend_scripts_responsive', 2000 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_paid_memberships_pro', 'anesta_paid_memberships_pro_frontend_scripts_responsive', 10, 1 );
	function anesta_paid_memberships_pro_frontend_scripts_responsive( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && anesta_need_frontend_scripts( 'paid_memberships_pro' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$anesta_url = anesta_get_file_url( 'plugins/paid-memberships-pro/paid-memberships-pro-responsive.css' );
			if ( '' != $anesta_url ) {
				wp_enqueue_style( 'anesta-paid-memberships-pro-responsive', $anesta_url, array(), null, anesta_media_for_load_css_responsive( 'paid-memberships-pro' ) );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'anesta_paid_memberships_pro_merge_styles' ) ) {
	//Handler of the add_filter('anesta_filter_merge_styles', 'anesta_paid_memberships_pro_merge_styles');
	function anesta_paid_memberships_pro_merge_styles( $list ) {
		$list[ 'plugins/paid-memberships-pro/paid-memberships-pro.css' ] = true;
		return $list;
	}
}

// Merge responsive styles
if ( ! function_exists( 'anesta_paid_memberships_pro_merge_styles_responsive' ) ) {
	//Handler of the add_filter('anesta_filter_merge_styles_responsive', 'anesta_paid_memberships_pro_merge_styles_responsive');
	function anesta_paid_memberships_pro_merge_styles_responsive( $list ) {
		$list[ 'plugins/paid-memberships-pro/paid-memberships-pro-responsive.css' ] = true;
		return $list;
	}
}

// Add plugin-specific colors and fonts to the custom CSS
if ( anesta_exists_paid_memberships_pro() ) {
	require_once anesta_get_file_dir( 'plugins/paid-memberships-pro/paid-memberships-pro-style.php' );
}

// Load required styles and scripts for the frontend
if ( !function_exists( 'anesta_paid_memberships_pro_load_scripts_front' ) ) {
	add_action( "wp_enqueue_scripts", 'anesta_paid_memberships_pro_load_scripts_front', 20 );
	add_action( 'trx_addons_action_pagebuilder_preview_scripts', 'anesta_paid_memberships_pro_load_scripts_front', 10, 1 );
	function anesta_paid_memberships_pro_load_scripts_front( $force = false ) {
		static $loaded = false;
		if ( ! anesta_exists_paid_memberships_pro() || !anesta_exists_trx_addons() ) return;
		$debug    = trx_addons_is_on( trx_addons_get_option( 'debug_mode' ) );
		$optimize = ! trx_addons_is_off( trx_addons_get_option( 'optimize_css_and_js_loading' ) );
		$preview_elm = trx_addons_is_preview( 'elementor' );
		$preview_gb  = trx_addons_is_preview( 'gutenberg' );
		$theme_full  = current_theme_supports( 'styles-and-scripts-full-merged' );
		$need        = ! $loaded && ( ! $preview_elm || $debug ) && ! $preview_gb && $optimize && (
						$force === true
							|| ( $preview_elm && $debug )
							|| trx_addons_sc_check_in_content( array(
									'sc' => 'paid_memberships_pro',
									'entries' => array(
												array( 'type' => 'sc',  'sc' => 'membership' ),
												//array( 'type' => 'gb',  'sc' => 'wp:trx-addons/charts' ),// This sc is not exists for GB
												array( 'type' => 'elm', 'sc' => '"widgetType":"membership"' ),
												array( 'type' => 'elm', 'sc' => '"shortcode":"[membership' ),
									)
								) ) );
		if ( ! $loaded && ! $preview_gb && ( ( ! $optimize && $debug ) || ( $optimize && $need ) ) ) {
			$loaded = true;
			do_action( 'trx_addons_action_load_scripts_front', $force, 'paid_memberships_pro' );
		}
		if ( ! $loaded && $preview_elm && $optimize && ! $debug && ! $theme_full ) {
			do_action( 'trx_addons_action_load_scripts_front', false, 'paid_memberships_pro', 2 );
		}
	}
}

// Load styles and scripts if present in the cache of the menu or layouts or finally in the whole page output
if ( !function_exists( 'anesta_paid_memberships_pro_check_in_html_output' ) ) {
	add_action( 'trx_addons_action_check_page_content', 'anesta_paid_memberships_pro_check_in_html_output', 10, 1 );
	function anesta_paid_memberships_pro_check_in_html_output( $content = '' ) {
		if ( anesta_exists_paid_memberships_pro()
			&& ! trx_addons_need_frontend_scripts( 'paid_memberships_pro' )
			&& ! trx_addons_is_off( trx_addons_get_option( 'optimize_css_and_js_loading' ) )
		) {
			$checklist = apply_filters( 'trx_addons_filter_check_in_html', array(
							'class=[\'"][^\'"]*pmpro_',
							),
							'paid_memberships_pro'
						);
			foreach ( $checklist as $item ) {
				if ( preg_match( "#{$item}#", $content, $matches ) ) {
					anesta_paid_memberships_pro_load_scripts_front( true );
					break;
				}
			}
		}
		return $content;
	}
} 

// Remove plugin-specific styles if present in the page head output
if ( !function_exists( 'anesta_paid_memberships_pro_filter_head_output' ) ) {
	add_filter( 'trx_addons_filter_page_head', 'anesta_paid_memberships_pro_filter_head_output', 10, 1 );
	function anesta_paid_memberships_pro_filter_head_output( $content = '' ) {
		if ( anesta_exists_paid_memberships_pro()
			&& trx_addons_get_option( 'optimize_css_and_js_loading' ) == 'full'
			&& ! trx_addons_is_preview()
			&& ! trx_addons_need_frontend_scripts( 'paid_memberships_pro' )
			&& apply_filters( 'trx_addons_filter_remove_3rd_party_styles', true, 'paid_memberships_pro' )
		) {
			$content = preg_replace( '#<link[^>]*href=[\'"][^\'"]*/paid-memberships-pro/[^>]*>#', '', $content );
		}
		return $content;
	}
}

// Remove plugin-specific styles and scripts if present in the page body output
if ( !function_exists( 'anesta_paid_memberships_pro_filter_body_output' ) ) {
	add_filter( 'trx_addons_filter_page_content', 'anesta_paid_memberships_pro_filter_body_output', 10, 1 );
	function anesta_paid_memberships_pro_filter_body_output( $content = '' ) {
		if ( anesta_exists_paid_memberships_pro()
			&& trx_addons_get_option( 'optimize_css_and_js_loading' ) == 'full'
			&& ! trx_addons_is_preview()
			&& ! trx_addons_need_frontend_scripts( 'paid_memberships_pro' )
			&& apply_filters( 'trx_addons_filter_remove_3rd_party_styles', true, 'paid_memberships_pro' )
		) {
			$content = preg_replace( '#<link[^>]*href=[\'"][^\'"]*/paid-memberships-pro/[^>]*>#', '', $content );
			$content = preg_replace( '#<script[^>]*src=[\'"][^\'"]*/paid-memberships-pro/[^>]*>[\\s\\S]*</script>#U', '', $content );
			$content = preg_replace( '#<script[^>]*id=[\'"]paid-memberships-pro[^>]*>[\\s\\S]*</script>#U', '', $content );
		}
		return $content;
	}
}


// One-click import support
//------------------------------------------------------------------------
// Check plugin in the required plugins
if ( !function_exists( 'anesta_paid_memberships_pro_required_plugins' ) ) {
    if (is_admin()) add_filter( 'trx_addons_filter_importer_required_plugins',	'anesta_paid_memberships_pro_required_plugins', 10, 2 );
    function anesta_paid_memberships_pro_required_plugins($not_installed='', $list='') {
        if (strpos($list, 'paid-memberships-pro')!==false && !anesta_exists_paid_memberships_pro() )
            $not_installed .= '<br>' . esc_html__('Paid Memberships Pro', 'anesta');
        return $not_installed;
    }
}

// Set plugin's specific importer options
if ( !function_exists( 'anesta_paid_memberships_pro_importer_set_options' ) ) {
	add_filter( 'trx_addons_filter_importer_options',	'anesta_paid_memberships_pro_importer_set_options' );
	function anesta_paid_memberships_pro_importer_set_options($options=array()) {
		if ( anesta_exists_paid_memberships_pro()  ) {
			$options['additional_options'][]	= '%pmpro%';

			if (is_array($options['files']) && count($options['files']) > 0) {
				foreach ($options['files'] as $k => $v) {
					$options['files'][$k]['file_with_paid-memberships-pro'] = str_replace('name.ext', 'paid-memberships-pro.txt', $v['file_with_']);
				}
			}
		}
		return $options;
	}
}

// Prevent import plugin's specific options if plugin is not installed
if ( !function_exists( 'anesta_paid_memberships_pro_check_options' ) ) {
	add_filter( 'trx_addons_filter_import_theme_options', 'anesta_paid_memberships_pro_check_options', 10, 4 );
	function anesta_paid_memberships_pro_check_options($allow, $k, $v, $options) {
		if ($allow && (strpos($k, 'pmpro')===0) ) {
			$allow = anesta_exists_paid_memberships_pro() && in_array('paid-memberships-pro', $options['required_plugins']);
		}
		return $allow;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'anesta_paid_memberships_pro_show_params' ) ) {
	add_action( 'trx_addons_action_importer_params',	'anesta_paid_memberships_pro_show_params', 10, 1 );
	function anesta_paid_memberships_pro_show_params($importer) {
		if ( anesta_exists_paid_memberships_pro() && in_array('paid-memberships-pro', $importer->options['required_plugins']) ) {
			$importer->show_importer_params(array(
				'slug' => 'paid-memberships-pro',
				'title' => esc_html__('Import Paid Memberships Pro', 'anesta'),
				'part' => 0
			));
		}
	}
}

// Import posts
if ( !function_exists( 'anesta_paid_memberships_pro_importer_import' ) ) {
	if (is_admin()) add_action( 'trx_addons_action_importer_import',	'anesta_paid_memberships_pro_importer_import', 10, 2 );
	function anesta_paid_memberships_pro_importer_import($importer, $action) {
		if ( anesta_exists_paid_memberships_pro() && in_array('paid-memberships-pro', $importer->options['required_plugins']) ) {
			if ( $action == 'import_paid-memberships-pro' ) {
				$importer->response['start_from_id'] = 0;
				$importer->import_dump('paid-memberships-pro', esc_html__('Paid Memberships Pro meta', 'anesta'));
			}
		}
	}
}


// Display import progress
if ( !function_exists( 'anesta_paid_memberships_pro_import_fields' ) ) {
	add_action( 'trx_addons_action_importer_import_fields',	'anesta_paid_memberships_pro_import_fields', 10, 1 );
	function anesta_paid_memberships_pro_import_fields($importer) {
		if ( anesta_exists_paid_memberships_pro() && in_array('paid-memberships-pro', $importer->options['required_plugins']) ) {
			$importer->show_importer_fields(array(
					'slug'=>'paid-memberships-pro',
					'title' => esc_html__('Paid Memberships Pro meta', 'anesta')
				)
			);
		}
	}
}	

// Export posts
if ( !function_exists( 'anesta_paid_memberships_pro_export' ) ) {
	add_action( 'trx_addons_action_importer_export',	'anesta_paid_memberships_pro_export', 10, 1 );
	function anesta_paid_memberships_pro_export($importer) {
		if ( anesta_exists_paid_memberships_pro() && in_array('paid-memberships-pro', $importer->options['required_plugins']) ) {
			trx_addons_fpc($importer->export_file_dir('paid-memberships-pro.txt'), serialize( array(
					"pmpro_memberships_pages"				=> $importer->export_dump("pmpro_memberships_pages"),
					"pmpro_memberships_users"				=> $importer->export_dump("pmpro_memberships_users"),
					"pmpro_membership_levelmeta"			=> $importer->export_dump("pmpro_membership_levelmeta"),
					"pmpro_membership_levels"				=> $importer->export_dump("pmpro_membership_levels"),
					"pmpro_membership_orders"				=> $importer->export_dump("pmpro_membership_orders"),
				) )
			);
		}
	}
}

// Display exported data in the fields
if ( !function_exists( 'anesta_paid_memberships_pro_export_fields' ) ) {
	add_action( 'trx_addons_action_importer_export_fields',	'anesta_paid_memberships_pro_export_fields', 10, 1 );
	function anesta_paid_memberships_pro_export_fields($importer) {
		if ( anesta_exists_paid_memberships_pro() && in_array('paid-memberships-pro', $importer->options['required_plugins']) ) {
			$importer->show_exporter_fields(array(
					'slug'	=> 'paid-memberships-pro',
					'title' => esc_html__('Paid Memberships Pro', 'anesta')
				)
			);
		}
	}
}