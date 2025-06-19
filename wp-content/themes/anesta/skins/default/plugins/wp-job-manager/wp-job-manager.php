<?php
/* WP Job Manager support functions
------------------------------------------------------------------------------- */


// Check if plugin installed and activated
if ( ! function_exists( 'anesta_exists_wp_job_manager' ) ) {
	function anesta_exists_wp_job_manager() {
		return class_exists( 'WP_Job_Manager' );
	}
}

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'anesta_wp_job_manager_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'anesta_wp_job_manager_theme_setup3', 3 );
	function anesta_wp_job_manager_theme_setup3() {
		if ( anesta_exists_wp_job_manager() ) {
			// Section 'WP Job Manager'
			anesta_storage_merge_array(
				'options', '', array_merge(
					array(
						'wp-job-manager'     => array(
							'title' => esc_html__( 'WP Job Manager', 'anesta' ),
							'desc'  => wp_kses_data( __( 'Select parameters to display the WP Job Manager pages', 'anesta' ) ),
							'icon'  => 'icon-briefcase',
							'type'  => 'section',
						)
					),
					anesta_options_get_list_cpt_options( 'wp-job-manager', esc_html__( 'WP Job Manager', 'anesta' ) ),
					array(
						'blog_single_info_wp-job-manager'      => array(
							'title' => esc_html__( 'WP Job Manager posts', 'anesta' ),
							'desc'  => '',
							'type'  => 'info',
						),
						'show_author_info_wp-job-manager'		=> array(
							'title' => esc_html__( 'Show author info', 'anesta' ),
							'desc'  => wp_kses_data( __( "Display block with information about post's author", 'anesta' ) ),
							'std'   => 1,
							'type'  => 'switch',
						),
						'show_related_posts_wp-job-manager'		=> array(
							'title'    => esc_html__( 'Show related posts', 'anesta' ),
							'desc'     => wp_kses_data( __( "Show 'Related posts' section on single post pages", 'anesta' ) ),
							'std'      => 1,
							'type'     => 'switch',
						),
						'posts_navigation_wp-job-manager'		=> array(
							'title'   => esc_html__( 'Show post navigation', 'anesta' ),
							'desc'    => wp_kses_data( __( "Display post navigation on single post pages or load the next post automatically after the content of the current article.", 'anesta' ) ),
							'std'     => 'links',
							'options' => array(
								'none'   => esc_html__('None', 'anesta'),
								'links'  => esc_html__('Prev/Next links', 'anesta'),
							),
							'pro_only'=> ANESTA_THEME_FREE,
							'type'    => 'radio',
						)
					)
				)
			);
		}
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'anesta_wp_job_manager_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'anesta_wp_job_manager_theme_setup9', 9 );
	function anesta_wp_job_manager_theme_setup9() {
		if ( anesta_exists_wp_job_manager() ) {
			add_action( 'wp_enqueue_scripts', 'anesta_wp_job_manager_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_wp_job_manager', 'anesta_wp_job_manager_frontend_scripts', 10, 1 );

			add_action( 'wp_enqueue_scripts', 'anesta_wp_job_manager_frontend_scripts_responsive', 2000 );
			add_action( 'trx_addons_action_load_scripts_front_wp_job_manager', 'anesta_wp_job_manager_frontend_scripts_responsive', 10, 1 );
			
			add_filter( 'anesta_filter_merge_styles', 'anesta_wp_job_manager_merge_styles' );
			add_filter( 'anesta_filter_merge_styles_responsive', 'anesta_wp_job_manager_merge_styles_responsive' );

			add_action( 'anesta_filter_detect_blog_mode', 'anesta_wp_job_manager_detect_blog_mode' );

			// Search theme-specific templates in the skin dir (if exists)
			add_filter( 'job_manager_locate_template', 'anesta_wp_job_manager_locate_template', 100, 3 );
		}
		if ( is_admin() ) {
            add_filter( 'anesta_filter_tgmpa_required_plugins', 'anesta_wp_job_manager_tgmpa_required_plugins' );
        }
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'anesta_wp_job_manager_tgmpa_required_plugins' ) ) {    
    function anesta_wp_job_manager_tgmpa_required_plugins( $list = array() ) {
        if ( anesta_storage_isset( 'required_plugins', 'wp-job-manager' ) && anesta_storage_get_array( 'required_plugins', 'wp-job-manager', 'install' ) !== false ) {
            $list[] = array(
                'name'     => anesta_storage_get_array( 'required_plugins', 'wp-job-manager', 'title' ),
                'slug'     => 'wp-job-manager',
                'required' => false,
            );
        }
        return $list;
    }
}


// Styles & Scripts
//------------------------------------------------------------------------
// Enqueue styles for frontend
if ( ! function_exists( 'anesta_wp_job_manager_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_wp_job_manager_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_wp_job_manager', 'anesta_wp_job_manager_frontend_scripts', 10, 1 );
	function anesta_wp_job_manager_frontend_scripts( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && anesta_need_frontend_scripts( 'wp_job_manager' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$anesta_url = anesta_get_file_url( 'plugins/wp-job-manager/wp-job-manager.css' );
			if ( '' != $anesta_url ) {
				wp_enqueue_style( 'anesta-wp-job-manager', $anesta_url, array(), null );
			}
		}
	}
}

// Enqueue responsive styles for frontend
if ( ! function_exists( 'anesta_wp_job_manager_frontend_scripts_responsive' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_wp_job_manager_frontend_scripts_responsive', 2000 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_wp_job_manager', 'anesta_wp_job_manager_frontend_scripts_responsive', 10, 1 );
	function anesta_wp_job_manager_frontend_scripts_responsive( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && anesta_need_frontend_scripts( 'wp_job_manager' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$anesta_url = anesta_get_file_url( 'plugins/wp-job-manager/wp-job-manager-responsive.css' );
			if ( '' != $anesta_url ) {
				wp_enqueue_style( 'anesta-wp-job-manager-responsive', $anesta_url, array(), null, anesta_media_for_load_css_responsive( 'wp-job-manager' ) );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'anesta_wp_job_manager_merge_styles' ) ) {
	//Handler of the add_filter( 'anesta_filter_merge_styles', 'anesta_wp_job_manager_merge_styles');
	function anesta_wp_job_manager_merge_styles( $list ) {
		$list[ 'plugins/wp-job-manager/wp-job-manager.css' ] = true;
		return $list;
	}
}

// Merge responsive styles
if ( ! function_exists( 'anesta_wp_job_manager_merge_styles_responsive' ) ) {
	//Handler of the add_filter('anesta_filter_merge_styles_responsive', 'anesta_wp_job_manager_merge_styles_responsive');
	function anesta_wp_job_manager_merge_styles_responsive( $list ) {
		$list[ 'plugins/wp-job-manager/wp-job-manager-responsive.css' ] = true;
		return $list;
	}
}

// Add plugin-specific colors and fonts to the custom CSS
if ( anesta_exists_wp_job_manager() ) {
	require_once anesta_get_file_dir( 'plugins/wp-job-manager/wp-job-manager-style.php' );
}

// Load required styles and scripts for the frontend
if ( !function_exists( 'anesta_wp_job_manager_load_scripts_front' ) ) {
	add_action( "wp_enqueue_scripts", 'anesta_wp_job_manager_load_scripts_front', 20 );
	add_action( 'trx_addons_action_pagebuilder_preview_scripts', 'anesta_wp_job_manager_load_scripts_front', 10, 1 );
	function anesta_wp_job_manager_load_scripts_front( $force = false ) {
		static $loaded = false;
		if ( ! anesta_exists_wp_job_manager() || !anesta_exists_trx_addons() ) return;
		$debug    = trx_addons_is_on( trx_addons_get_option( 'debug_mode' ) );
		$optimize = ! trx_addons_is_off( trx_addons_get_option( 'optimize_css_and_js_loading' ) );
		$preview_elm = trx_addons_is_preview( 'elementor' );
		$preview_gb  = trx_addons_is_preview( 'gutenberg' );
		$theme_full  = current_theme_supports( 'styles-and-scripts-full-merged' );
		$need        = ! $loaded && ( ! $preview_elm || $debug ) && ! $preview_gb && $optimize && (
						$force === true
							|| ( $preview_elm && $debug )
							|| trx_addons_sc_check_in_content( array(
									'sc' => 'wp_job_manager',
									'entries' => array(
												array( 'type' => 'sc',  'sc' => 'job' ),
												//array( 'type' => 'gb',  'sc' => 'wp:trx-addons/charts' ),// This sc is not exists for GB
												array( 'type' => 'elm', 'sc' => '"widgetType":"job"' ),
												array( 'type' => 'elm', 'sc' => '"shortcode":"[job' ),
												array( 'type' => 'elm', 'sc' => '"shortcode":"[submit_job_form' ),
									)
								) ) );
		if ( ! $loaded && ! $preview_gb && ( ( ! $optimize && $debug ) || ( $optimize && $need ) ) ) {
			$loaded = true;
			do_action( 'trx_addons_action_load_scripts_front', $force, 'wp_job_manager' );
		}
		if ( ! $loaded && $preview_elm && $optimize && ! $debug && ! $theme_full ) {
			do_action( 'trx_addons_action_load_scripts_front', false, 'wp_job_manager', 2 );
		}
	}
}

// Load styles and scripts if present in the cache of the menu or layouts or finally in the whole page output
if ( !function_exists( 'anesta_wp_job_manager_check_in_html_output' ) ) {
	add_action( 'trx_addons_action_check_page_content', 'anesta_wp_job_manager_check_in_html_output', 10, 1 );
	function anesta_wp_job_manager_check_in_html_output( $content = '' ) {
		if ( anesta_exists_wp_job_manager()
			&& ! trx_addons_need_frontend_scripts( 'wp_job_manager' )
			&& ! trx_addons_is_off( trx_addons_get_option( 'optimize_css_and_js_loading' ) )
		) {
			$checklist = apply_filters( 'trx_addons_filter_check_in_html', array(
							'class=[\'"][^\'"]*job-',
							'class=[\'"][^\'"]*job_',
							),
							'wp_job_manager'
						);
			foreach ( $checklist as $item ) {
				if ( preg_match( "#{$item}#", $content, $matches ) ) {
					anesta_wp_job_manager_load_scripts_front( true );
					break;
				}
			}
		}
		return $content;
	}
}

// Remove plugin-specific styles if present in the page head output
if ( !function_exists( 'anesta_wp_job_manager_filter_head_output' ) ) {
	add_filter( 'trx_addons_filter_page_head', 'anesta_wp_job_manager_filter_head_output', 10, 1 );
	function anesta_wp_job_manager_filter_head_output( $content = '' ) {
		if ( anesta_exists_wp_job_manager()
			&& trx_addons_get_option( 'optimize_css_and_js_loading' ) == 'full'
			&& ! trx_addons_is_preview()
			&& ! trx_addons_need_frontend_scripts( 'wp_job_manager' )
			&& apply_filters( 'trx_addons_filter_remove_3rd_party_styles', true, 'wp_job_manager' )
		) {
			$content = preg_replace( '#<link[^>]*href=[\'"][^\'"]*/wp-job-manager/[^>]*>#', '', $content );
		}
		return $content;
	}
}

// Remove plugin-specific styles and scripts if present in the page body output
if ( !function_exists( 'anesta_wp_job_manager_filter_body_output' ) ) {
	add_filter( 'trx_addons_filter_page_content', 'anesta_wp_job_manager_filter_body_output', 10, 1 );
	function anesta_wp_job_manager_filter_body_output( $content = '' ) {
		if ( anesta_exists_wp_job_manager()
			&& trx_addons_get_option( 'optimize_css_and_js_loading' ) == 'full'
			&& ! trx_addons_is_preview()
			&& ! trx_addons_need_frontend_scripts( 'wp_job_manager' )
			&& apply_filters( 'trx_addons_filter_remove_3rd_party_styles', true, 'wp_job_manager' )
		) {
			$content = preg_replace( '#<link[^>]*href=[\'"][^\'"]*/wp-job-manager/[^>]*>#', '', $content );
			$content = preg_replace( '#<script[^>]*src=[\'"][^\'"]*/wp-job-manager/[^>]*>[\\s\\S]*</script>#U', '', $content );
			$content = preg_replace( '#<script[^>]*id=[\'"]wp-job-manager[^>]*>[\\s\\S]*</script>#U', '', $content );
		}
		return $content;
	}
}


// Other
//------------------------------------------------------------------------
// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'anesta_wp_job_manager_init' ) ) {
	add_action( 'wp', 'anesta_wp_job_manager_init' );
	function anesta_wp_job_manager_init() {
		if ( anesta_exists_wp_job_manager() ) {
			if ( is_wpjm() && is_single() ) {
				add_action( 'anesta_action_page_content_start', 'anesta_skin_post_title' );
			}
		}
	}
}

// Page title
if ( ! function_exists( 'anesta_wp_job_manager_page_title' ) ) {
	add_filter( 'anesta_skin_filter_page_title', 'anesta_wp_job_manager_page_title' );
	function anesta_wp_job_manager_page_title( $allow ) {	
		if ( anesta_exists_wp_job_manager() ) {
			return is_single() ? true : $allow;
		}
		return $allow;
	}
}

// Detect current blog mode
if ( ! function_exists( 'anesta_wp_job_manager_detect_blog_mode' ) ) {
	//Handler of the add_filter( 'anesta_filter_detect_blog_mode', 'anesta_wp_job_manager_detect_blog_mode' );
	function anesta_wp_job_manager_detect_blog_mode( $mode = '' ) {
		if ( anesta_exists_wp_job_manager() ) {
			if ( is_wpjm() ) {
				$mode = 'wp-job-manager';
			}
		}
		return $mode;
	}
}

// Shortcode [jobs] - add title
if ( ! function_exists( 'anesta_wp_job_manager_output_jobs_defaults' ) ) {
	add_filter( 'job_manager_output_jobs_defaults', 'anesta_wp_job_manager_output_jobs_defaults' );
	function anesta_wp_job_manager_output_jobs_defaults( $atts ) {	
		$atts['title'] = '';
		return $atts;
	}
}

// Shortcode [jobs] - add title
if ( ! function_exists( 'anesta_wp_job_manager_jobs_shortcode_data_attributes' ) ) {
	add_filter( 'job_manager_jobs_shortcode_data_attributes', 'anesta_wp_job_manager_jobs_shortcode_data_attributes', 10, 2 );
	function anesta_wp_job_manager_jobs_shortcode_data_attributes( $data_attributes, $atts ) {	
		if ( !empty($atts['title']) ) {
			$data_attributes['title'] = $atts['title'];
		}
		return $data_attributes;
	}
}

// Search skin-specific templates in the skin dir (if exists)
if ( ! function_exists( 'anesta_wp_job_manager_locate_template' ) ) {
	//Handler of the add_filter( 'job_manager_locate_template', 'anesta_wp_job_manager_locate_template', 100, 3 );
	function anesta_wp_job_manager_locate_template( $template, $template_name, $template_path ) {
		$folders = apply_filters( 'anesta_filter_wp_job_manager_locate_template_folders', array(
			$template_path,
			'plugins/wp-job-manager/templates'
		) );
		foreach ( $folders as $f ) {
			$theme_dir = apply_filters( 'anesta_filter_get_theme_file_dir', '', trailingslashit( anesta_esc( $f ) ) . $template_name );
			if ( '' != $theme_dir ) {
				$template = $theme_dir;
				break;
			}
		}
		return $template;
	}
}

// Related posts 
if ( ! function_exists( 'anesta_wp_job_manager_related_post_output' ) ) {
	add_filter( 'anesta_filter_related_post_output', 'anesta_wp_job_manager_related_post_output', 10, 2 );
	function anesta_wp_job_manager_related_post_output( $output, $id ) {
		if ( get_post_type( $id ) == 'job_listing' ) {
			$output = do_shortcode( '[job_summary id="'. $id .'" align="none" width="100%"]' );
		}
		return $output;
	}
}

// Archive page link
if ( ! function_exists( 'anesta_wp_job_manager_post_type_archive_link' ) ) {
	add_filter( 'anesta_filter_post_type_archive_link', 'anesta_wp_job_manager_post_type_archive_link', 10, 2 );
	function anesta_wp_job_manager_post_type_archive_link( $link, $post_type ) {
		if ( $post_type == 'job_listing' ) {
			$jobs_page_id = get_option( 'job_manager_jobs_page_id' );
			if ( $jobs_page_id ) {
				$link = get_permalink( $jobs_page_id );
			}	
		}
		return $link;
	}
}


// One-click import support
//------------------------------------------------------------------------
// Set plugin's specific importer options
if ( !function_exists( 'anesta_wp_job_manager_importer_set_options' ) ) {
	add_filter( 'trx_addons_filter_importer_options',	'anesta_wp_job_manager_importer_set_options' );
	function anesta_wp_job_manager_importer_set_options($options=array()) {
		if ( anesta_exists_wp_job_manager()  && in_array('wp-job-manager', $options['required_plugins']) ) {
			$options['additional_options'][]	= '%job_manager%';
		}
		return $options;
	}
}
