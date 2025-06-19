<?php
/* Democracy Poll support functions
------------------------------------------------------------------------------- */


// Check if plugin installed and activated
if ( ! function_exists( 'anesta_exists_democracy_poll' ) ) {
	function anesta_exists_democracy_poll() {
		return class_exists( 'DemPoll' );
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'anesta_democracy_poll_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'anesta_democracy_poll_theme_setup9', 9 );
	function anesta_democracy_poll_theme_setup9() {
		if ( anesta_exists_democracy_poll() ) {
			add_action( 'wp_enqueue_scripts', 'anesta_democracy_poll_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_democracy_poll', 'anesta_democracy_poll_frontend_scripts', 10, 1 );

			add_action( 'wp_enqueue_scripts', 'anesta_democracy_poll_frontend_scripts_responsive', 2000 );
			add_action( 'trx_addons_action_load_scripts_front_democracy_poll', 'anesta_democracy_poll_frontend_scripts_responsive', 10, 1 );
	
			add_filter( 'anesta_filter_merge_styles', 'anesta_democracy_poll_merge_styles' );
			add_filter( 'anesta_filter_merge_styles_responsive', 'anesta_democracy_poll_merge_styles_responsive' );
		}
		if ( is_admin() ) {
            add_filter( 'anesta_filter_tgmpa_required_plugins', 'anesta_democracy_poll_tgmpa_required_plugins' );
        }
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'anesta_democracy_poll_tgmpa_required_plugins' ) ) {    
    function anesta_democracy_poll_tgmpa_required_plugins( $list = array() ) {
        if ( anesta_storage_isset( 'required_plugins', 'democracy-poll' ) && anesta_storage_get_array( 'required_plugins', 'democracy-poll', 'install' ) !== false ) {
            $list[] = array(
                'name'     => anesta_storage_get_array( 'required_plugins', 'democracy-poll', 'title' ),
                'slug'     => 'democracy-poll',
                'required' => false,
            );
        }
        return $list;
    }
}


// Styles & Scripts
//------------------------------------------------------------------------
// Enqueue custom scripts
if ( ! function_exists( 'anesta_democracy_poll_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_democracy_poll_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_democracy_poll', 'anesta_democracy_poll_frontend_scripts', 10, 1 );
	function anesta_democracy_poll_frontend_scripts( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && anesta_need_frontend_scripts( 'democracy_poll' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$anesta_url = anesta_get_file_url( 'plugins/democracy-poll/democracy-poll.css' );
			if ( '' != $anesta_url ) {
				wp_enqueue_style( 'anesta-democracy-poll', $anesta_url, array(), null );
			}
		}
	}	
}

// Enqueue responsive styles for frontend
if ( ! function_exists( 'anesta_democracy_poll_frontend_scripts_responsive' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_democracy_poll_frontend_scripts_responsive', 2000 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_democracy_poll', 'anesta_democracy_poll_frontend_scripts_responsive', 10, 1 );
	function anesta_democracy_poll_frontend_scripts_responsive( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && anesta_need_frontend_scripts( 'democracy_poll' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$anesta_url = anesta_get_file_url( 'plugins/democracy-poll/democracy-poll-responsive.css' );
			if ( '' != $anesta_url ) {
				wp_enqueue_style( 'anesta-democracy-poll-responsive', $anesta_url, array(), null, anesta_media_for_load_css_responsive( 'democracy-poll' ) );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'anesta_democracy_poll_merge_styles' ) ) {
	//Handler of the add_filter('anesta_filter_merge_styles', 'anesta_democracy_poll_merge_styles');
	function anesta_democracy_poll_merge_styles( $list ) {
		$list[ 'plugins/democracy-poll/democracy-poll.css' ] = true;
		return $list;
	}
}

// Merge responsive styles
if ( ! function_exists( 'anesta_democracy_poll_merge_styles_responsive' ) ) {
	//Handler of the add_filter('anesta_filter_merge_styles_responsive', 'anesta_democracy_poll_merge_styles_responsive');
	function anesta_democracy_poll_merge_styles_responsive( $list ) {
		$list[ 'plugins/democracy-poll/democracy-poll-responsive.css' ] = true;
		return $list;
	}
}

// Add plugin-specific colors and fonts to the custom CSS
if ( anesta_exists_democracy_poll() ) {
	require_once anesta_get_file_dir( 'plugins/democracy-poll/democracy-poll-style.php' );
}

// Load required styles and scripts for the frontend
if ( !function_exists( 'anesta_democracy_poll_load_scripts_front' ) ) {
	add_action( "wp_enqueue_scripts", 'anesta_democracy_poll_load_scripts_front', 20 );
	add_action( 'trx_addons_action_pagebuilder_preview_scripts', 'anesta_democracy_poll_load_scripts_front', 10, 1 );
	function anesta_democracy_poll_load_scripts_front( $force = false ) {
		static $loaded = false;
		if ( ! anesta_exists_democracy_poll() || !anesta_exists_trx_addons() ) return;
		$debug    = trx_addons_is_on( trx_addons_get_option( 'debug_mode' ) );
		$optimize = ! trx_addons_is_off( trx_addons_get_option( 'optimize_css_and_js_loading' ) );
		$preview_elm = trx_addons_is_preview( 'elementor' );
		$preview_gb  = trx_addons_is_preview( 'gutenberg' );
		$theme_full  = current_theme_supports( 'styles-and-scripts-full-merged' );
		$need        = ! $loaded && ( ! $preview_elm || $debug ) && ! $preview_gb && $optimize && (
						$force === true
							|| ( $preview_elm && $debug )
							|| trx_addons_sc_check_in_content( array(
									'sc' => 'democracy_poll',
									'entries' => array(
												array( 'type' => 'sc',  'sc' => 'democracy' ),
												//array( 'type' => 'gb',  'sc' => 'wp:trx-addons/charts' ),// This sc is not exists for GB
												array( 'type' => 'elm', 'sc' => '"widgetType":"democracy"' ),
												array( 'type' => 'elm', 'sc' => '"shortcode":"[democracy' ),
									)
								) ) );
		if ( ! $loaded && ! $preview_gb && ( ( ! $optimize && $debug ) || ( $optimize && $need ) ) ) {
			$loaded = true;
			do_action( 'trx_addons_action_load_scripts_front', $force, 'democracy_poll' );
		}
		if ( ! $loaded && $preview_elm && $optimize && ! $debug && ! $theme_full ) {
			do_action( 'trx_addons_action_load_scripts_front', false, 'democracy_poll', 2 );
		}
	}
}

// Load styles and scripts if present in the cache of the menu or layouts or finally in the whole page output
if ( !function_exists( 'anesta_democracy_poll_check_in_html_output' ) ) {
	add_action( 'trx_addons_action_check_page_content', 'anesta_democracy_poll_check_in_html_output', 10, 1 );
	function anesta_democracy_poll_check_in_html_output( $content = '' ) {
		if ( anesta_exists_democracy_poll()
			&& ! trx_addons_need_frontend_scripts( 'democracy_poll' )
			&& ! trx_addons_is_off( trx_addons_get_option( 'optimize_css_and_js_loading' ) )
		) {
			$checklist = apply_filters( 'trx_addons_filter_check_in_html', array(
							'class=[\'"][^\'"]*democracy',
							),
							'democracy_poll'
						);
			foreach ( $checklist as $item ) {
				if ( preg_match( "#{$item}#", $content, $matches ) ) {
					anesta_democracy_poll_load_scripts_front( true );
					break;
				}
			}
		}
		return $content;
	}
}

// Remove plugin-specific styles if present in the page head output
if ( !function_exists( 'anesta_democracy_poll_filter_head_output' ) ) {
	add_filter( 'trx_addons_filter_page_head', 'anesta_democracy_poll_filter_head_output', 10, 1 );
	function anesta_democracy_poll_filter_head_output( $content = '' ) {
		if ( anesta_exists_democracy_poll()
			&& trx_addons_get_option( 'optimize_css_and_js_loading' ) == 'full'
			&& ! trx_addons_is_preview()
			&& ! trx_addons_need_frontend_scripts( 'democracy_poll' )
			&& apply_filters( 'trx_addons_filter_remove_3rd_party_styles', true, 'democracy_poll' )
		) {
			$content = preg_replace( '#<link[^>]*href=[\'"][^\'"]*/democracy-poll/[^>]*>#', '', $content );
		}
		return $content;
	}
}

// Remove plugin-specific styles and scripts if present in the page body output
if ( !function_exists( 'anesta_democracy_poll_filter_body_output' ) ) {
	add_filter( 'trx_addons_filter_page_content', 'anesta_democracy_poll_filter_body_output', 10, 1 );
	function anesta_democracy_poll_filter_body_output( $content = '' ) {
		if ( anesta_exists_democracy_poll()
			&& trx_addons_get_option( 'optimize_css_and_js_loading' ) == 'full'
			&& ! trx_addons_is_preview()
			&& ! trx_addons_need_frontend_scripts( 'democracy_poll' )
			&& apply_filters( 'trx_addons_filter_remove_3rd_party_styles', true, 'democracy_poll' )
		) {
			$content = preg_replace( '#<link[^>]*href=[\'"][^\'"]*/democracy-poll/[^>]*>#', '', $content );
			$content = preg_replace( '#<script[^>]*src=[\'"][^\'"]*/democracy-poll/[^>]*>[\\s\\S]*</script>#U', '', $content );
			$content = preg_replace( '#<script[^>]*id=[\'"]democracy-poll[^>]*>[\\s\\S]*</script>#U', '', $content );
		}
		return $content;
	}
}


// Other
//------------------------------------------------------------------------
// Widget output
if ( ! function_exists( 'anesta_democracy_poll_dem_vote_screen' ) ) {
	add_filter( 'dem_vote_screen', 'anesta_democracy_poll_dem_vote_screen', 10, 2 );
	add_filter( 'dem_result_screen', 'anesta_democracy_poll_dem_vote_screen', 10, 2 );
	function anesta_democracy_poll_dem_vote_screen( $html, $poll ) {
		if ( strpos($html, 'dem-revote-button-wrap')!==false ) {
			$html = str_replace( '<span class="dem-revote-button-wrap">', '<div class="dem-revote-button-wrap">', $html);
			$html = str_replace( '</form>', '</form></div><span>', $html);
		}
		return $html;
	}
}


// One-click import support
//------------------------------------------------------------------------
// Check plugin in the required plugins
if ( !function_exists( 'anesta_democracy_poll_required_plugins' ) ) {
    if (is_admin()) add_filter( 'trx_addons_filter_importer_required_plugins',	'anesta_democracy_poll_required_plugins', 10, 2 );
    function anesta_democracy_poll_required_plugins($not_installed='', $list='') {
        if (strpos($list, 'democracy-poll')!==false && !anesta_exists_democracy_poll() )
            $not_installed .= '<br>' . esc_html__('Democracy Poll', 'anesta');
        return $not_installed;
    }
}

// Set plugin's specific importer options
if ( !function_exists( 'anesta_democracy_poll_importer_set_options' ) ) {
	add_filter( 'trx_addons_filter_importer_options',	'anesta_democracy_poll_importer_set_options' );
	function anesta_democracy_poll_importer_set_options($options=array()) {
		if ( anesta_exists_democracy_poll()  ) {
			$options['additional_options'][]	= '%democracy%';

			if (is_array($options['files']) && count($options['files']) > 0) {
				foreach ($options['files'] as $k => $v) {
					$options['files'][$k]['file_with_democracy-poll'] = str_replace('name.ext', 'democracy-poll.txt', $v['file_with_']);
				}
			}
		}
		return $options;
	}
}

// Prevent import plugin's specific options if plugin is not installed
if ( !function_exists( 'anesta_democracy_poll_check_options' ) ) {
	add_filter( 'trx_addons_filter_import_theme_options', 'anesta_democracy_poll_check_options', 10, 4 );
	function anesta_democracy_poll_check_options($allow, $k, $v, $options) {
		if ($allow && (strpos($k, 'democracy')===0) ) {
			$allow = anesta_exists_democracy_poll() && in_array('democracy-poll', $options['required_plugins']);
		}
		return $allow;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'anesta_democracy_poll_show_params' ) ) {
	add_action( 'trx_addons_action_importer_params',	'anesta_democracy_poll_show_params', 10, 1 );
	function anesta_democracy_poll_show_params($importer) {
		if ( anesta_exists_democracy_poll() && in_array('democracy-poll', $importer->options['required_plugins']) ) {
			$importer->show_importer_params(array(
				'slug' => 'democracy-poll',
				'title' => esc_html__('Import Democracy Poll', 'anesta'),
				'part' => 0
			));
		}
	}
}

// Import posts
if ( !function_exists( 'anesta_democracy_poll_importer_import' ) ) {
	if (is_admin()) add_action( 'trx_addons_action_importer_import',	'anesta_democracy_poll_importer_import', 10, 2 );
	function anesta_democracy_poll_importer_import($importer, $action) {
		if ( anesta_exists_democracy_poll() && in_array('democracy-poll', $importer->options['required_plugins']) ) {
			if ( $action == 'import_democracy-poll' ) {
				$importer->response['start_from_id'] = 0;
				$importer->import_dump('democracy-poll', esc_html__('Democracy Poll meta', 'anesta'));
			}
		}
	}
}


// Display import progress
if ( !function_exists( 'anesta_democracy_poll_import_fields' ) ) {
	add_action( 'trx_addons_action_importer_import_fields',	'anesta_democracy_poll_import_fields', 10, 1 );
	function anesta_democracy_poll_import_fields($importer) {
		if ( anesta_exists_democracy_poll() && in_array('democracy-poll', $importer->options['required_plugins']) ) {
			$importer->show_importer_fields(array(
					'slug'=>'democracy-poll',
					'title' => esc_html__('Democracy Poll meta', 'anesta')
				)
			);
		}
	}
}

// Export posts
if ( !function_exists( 'anesta_democracy_poll_export' ) ) {
	add_action( 'trx_addons_action_importer_export',	'anesta_democracy_poll_export', 10, 1 );
	function anesta_democracy_poll_export($importer) {
		if ( anesta_exists_democracy_poll() && in_array('democracy-poll', $importer->options['required_plugins']) ) {
			trx_addons_fpc($importer->export_file_dir('democracy-poll.txt'), serialize( array(
					"democracy_a"				=> $importer->export_dump("democracy_a"),
					"democracy_log"				=> $importer->export_dump("democracy_log"),
					"democracy_q"				=> $importer->export_dump("democracy_q"),
				) )
			);
		}
	}
}

// Display exported data in the fields
if ( !function_exists( 'anesta_democracy_poll_export_fields' ) ) {
	add_action( 'trx_addons_action_importer_export_fields',	'anesta_democracy_poll_export_fields', 10, 1 );
	function anesta_democracy_poll_export_fields($importer) {
		if ( anesta_exists_democracy_poll() && in_array('democracy-poll', $importer->options['required_plugins']) ) {
			$importer->show_exporter_fields(array(
					'slug'	=> 'democracy-poll',
					'title' => esc_html__('Democracy Poll', 'anesta')
				)
			);
		}
	}
}
