<?php
/* WP Resume Manager support functions
------------------------------------------------------------------------------- */


// Check if plugin installed and activated
if ( ! function_exists( 'anesta_exists_wp_job_manager_resumes' ) ) {
	function anesta_exists_wp_job_manager_resumes() {
		return class_exists( 'WP_Resume_Manager' );
	}
}

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'anesta_wp_job_manager_resumes_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'anesta_wp_job_manager_resumes_theme_setup3', 3 );
	function anesta_wp_job_manager_resumes_theme_setup3() {
		if ( anesta_exists_wp_job_manager_resumes() ) {
			// Section 'WP Resume Manager'
			anesta_storage_merge_array(
				'options', '', array_merge(
					array(
						'wp-job-manager-resumes'     => array(
							'title' => esc_html__( 'WP Resume Manager', 'anesta' ),
							'desc'  => wp_kses_data( __( 'Select parameters to display the WP Resume Manager pages', 'anesta' ) ),
							'icon'  => 'icon-briefcase',
							'type'  => 'section',
						)
					),
					anesta_options_get_list_cpt_options( 'wp-job-manager-resumes', esc_html__( 'WP Resume Manager', 'anesta' ) ),
					array(
						'blog_single_info_wp-job-manager-resumes'      => array(
							'title' => esc_html__( 'WP Resume Manager posts', 'anesta' ),
							'desc'  => '',
							'type'  => 'info',
						),
						'show_author_info_wp-job-manager-resumes'		=> array(
							'title' => esc_html__( 'Show author info', 'anesta' ),
							'desc'  => wp_kses_data( __( "Display block with information about post's author", 'anesta' ) ),
							'std'   => 1,
							'type'  => 'switch',
						),
						'show_related_posts_wp-job-manager-resumes'		=> array(
							'title'    => esc_html__( 'Show related posts', 'anesta' ),
							'desc'     => wp_kses_data( __( "Show 'Related posts' section on single post pages", 'anesta' ) ),
							'std'      => 1,
							'type'     => 'switch',
						),
						'posts_navigation_wp-job-manager-resumes'		=> array(
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
if ( ! function_exists( 'anesta_wp_job_manager_resumes_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'anesta_wp_job_manager_resumes_theme_setup9', 9 );
	function anesta_wp_job_manager_resumes_theme_setup9() {
		if ( anesta_exists_wp_job_manager_resumes() ) {
			add_action( 'wp_enqueue_scripts', 'anesta_wp_job_manager_resumes_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_wp_job_manager_resumes', 'anesta_wp_job_manager_resumes_frontend_scripts', 10, 1 );

			add_action( 'wp_enqueue_scripts', 'anesta_wp_job_manager_resumes_frontend_scripts_responsive', 2000 );
			add_action( 'trx_addons_action_load_scripts_front_wp_job_manager_resumes', 'anesta_wp_job_manager_resumes_frontend_scripts_responsive', 10, 1 );
			
			add_filter( 'anesta_filter_merge_styles', 'anesta_wp_job_manager_resumes_merge_styles' );
			add_filter( 'anesta_filter_merge_styles_responsive', 'anesta_wp_job_manager_resumes_merge_styles_responsive' );

			add_action( 'anesta_filter_detect_blog_mode', 'anesta_wp_job_manager_resumes_detect_blog_mode' );
		}
	}
}


// Styles & Scripts
//------------------------------------------------------------------------
// Enqueue styles for frontend
if ( ! function_exists( 'anesta_wp_job_manager_resumes_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_wp_job_manager_resumes_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_wp_job_manager_resumes', 'anesta_wp_job_manager_resumes_frontend_scripts', 10, 1 );
	function anesta_wp_job_manager_resumes_frontend_scripts( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && anesta_need_frontend_scripts( 'wp_job_manager_resumes' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$anesta_url = anesta_get_file_url( 'plugins/wp-job-manager-resumes/wp-job-manager-resumes.css' );
			if ( '' != $anesta_url ) {
				wp_enqueue_style( 'anesta-wp-job-manager-resumes', $anesta_url, array(), null );
			}
		}
	}
}

// Enqueue responsive styles for frontend
if ( ! function_exists( 'anesta_wp_job_manager_resumes_frontend_scripts_responsive' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_wp_job_manager_resumes_frontend_scripts_responsive', 2000 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_wp_job_manager_resumes', 'anesta_wp_job_manager_resumes_frontend_scripts_responsive', 10, 1 );
	function anesta_wp_job_manager_resumes_frontend_scripts_responsive( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && anesta_need_frontend_scripts( 'wp_job_manager_resumes' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$anesta_url = anesta_get_file_url( 'plugins/wp-job-manager-resumes/wp-job-manager-resumes-responsive.css' );
			if ( '' != $anesta_url ) {
				wp_enqueue_style( 'anesta-wp-job-manager-resumes-responsive', $anesta_url, array(), null, anesta_media_for_load_css_responsive( 'wp-job-manager-resumes' ) );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'anesta_wp_job_manager_resumes_merge_styles' ) ) {
	//Handler of the add_filter( 'anesta_filter_merge_styles', 'anesta_wp_job_manager_resumes_merge_styles');
	function anesta_wp_job_manager_resumes_merge_styles( $list ) {
		$list[ 'plugins/wp-job-manager-resumes/wp-job-manager-resumes.css' ] = true;
		return $list;
	}
}

// Merge responsive styles
if ( ! function_exists( 'anesta_wp_job_manager_resumes_merge_styles_responsive' ) ) {
	//Handler of the add_filter('anesta_filter_merge_styles_responsive', 'anesta_wp_job_manager_resumes_merge_styles_responsive');
	function anesta_wp_job_manager_resumes_merge_styles_responsive( $list ) {
		$list[ 'plugins/wp-job-manager-resumes/wp-job-manager-resumes-responsive.css' ] = true;
		return $list;
	}
}

// Add plugin-specific colors and fonts to the custom CSS
if ( anesta_exists_wp_job_manager_resumes() ) {
	require_once anesta_get_file_dir( 'plugins/wp-job-manager-resumes/wp-job-manager-resumes-style.php' );
}

// Load required styles and scripts for the frontend
if ( !function_exists( 'anesta_wp_job_manager_resumes_load_scripts_front' ) ) {
	add_action( "wp_enqueue_scripts", 'anesta_wp_job_manager_resumes_load_scripts_front', 20 );
	add_action( 'trx_addons_action_pagebuilder_preview_scripts', 'anesta_wp_job_manager_resumes_load_scripts_front', 10, 1 );
	function anesta_wp_job_manager_resumes_load_scripts_front( $force = false ) {
		static $loaded = false;
		if ( ! anesta_exists_wp_job_manager_resumes() || !anesta_exists_trx_addons() ) return;
		$debug    = trx_addons_is_on( trx_addons_get_option( 'debug_mode' ) );
		$optimize = ! trx_addons_is_off( trx_addons_get_option( 'optimize_css_and_js_loading' ) );
		$preview_elm = trx_addons_is_preview( 'elementor' );
		$preview_gb  = trx_addons_is_preview( 'gutenberg' );
		$theme_full  = current_theme_supports( 'styles-and-scripts-full-merged' );
		$need        = ! $loaded && ( ! $preview_elm || $debug ) && ! $preview_gb && $optimize && (
						$force === true
							|| ( $preview_elm && $debug )
							|| trx_addons_sc_check_in_content( array(
									'sc' => 'wp_job_manager_resumes',
									'entries' => array(
												array( 'type' => 'sc',  'sc' => 'resumes' ),
												array( 'type' => 'sc',  'sc' => 'submit_resume_form' ),
												array( 'type' => 'sc',  'sc' => 'candidate_dashboard' ),
												array( 'type' => 'sc',  'sc' => 'past_applications' ),
												//array( 'type' => 'gb',  'sc' => 'wp:trx-addons/charts' ),// This sc is not exists for GB
												array( 'type' => 'elm', 'sc' => '"widgetType":"resumes"' ),
												array( 'type' => 'elm', 'sc' => '"widgetType":"submit_resume_form"' ),
												array( 'type' => 'elm', 'sc' => '"widgetType":"candidate_dashboard"' ),
												array( 'type' => 'elm', 'sc' => '"widgetType":"past_applications"' ),
												array( 'type' => 'elm', 'sc' => '"shortcode":"[resumes' ),
												array( 'type' => 'elm', 'sc' => '"shortcode":"[submit_resume_form' ),
												array( 'type' => 'elm', 'sc' => '"shortcode":"[candidate_dashboard' ),
												array( 'type' => 'elm', 'sc' => '"shortcode":"[past_applications' ),
									)
								) ) );
		if ( ! $loaded && ! $preview_gb && ( ( ! $optimize && $debug ) || ( $optimize && $need ) ) ) {
			$loaded = true;
			do_action( 'trx_addons_action_load_scripts_front', $force, 'wp_job_manager_resumes' );
		}
		if ( ! $loaded && $preview_elm && $optimize && ! $debug && ! $theme_full ) {
			do_action( 'trx_addons_action_load_scripts_front', false, 'wp_job_manager_resumes', 2 );
		}
	}
}

// Load styles and scripts if present in the cache of the menu or layouts or finally in the whole page output
if ( !function_exists( 'anesta_wp_job_manager_resumes_check_in_html_output' ) ) {
	add_action( 'trx_addons_action_check_page_content', 'anesta_wp_job_manager_resumes_check_in_html_output', 10, 1 );
	function anesta_wp_job_manager_resumes_check_in_html_output( $content = '' ) {
		if ( anesta_exists_wp_job_manager_resumes()
			&& ! trx_addons_need_frontend_scripts( 'wp_job_manager_resumes' )
			&& ! trx_addons_is_off( trx_addons_get_option( 'optimize_css_and_js_loading' ) )
		) {
			$checklist = apply_filters( 'trx_addons_filter_check_in_html', array(
							'class=[\'"][^\'"]*resume',
							'id=[\'"][^\'"]*resume',
							),
							'wp_job_manager_resumes'
						);
			foreach ( $checklist as $item ) {
				if ( preg_match( "#{$item}#", $content, $matches ) ) {
					anesta_wp_job_manager_resumes_load_scripts_front( true );
					break;
				}
			}
		}
		return $content;
	}
}

// Remove plugin-specific styles if present in the page head output
if ( !function_exists( 'anesta_wp_job_manager_resumes_filter_head_output' ) ) {
	add_filter( 'trx_addons_filter_page_head', 'anesta_wp_job_manager_resumes_filter_head_output', 10, 1 );
	function anesta_wp_job_manager_resumes_filter_head_output( $content = '' ) {
		if ( anesta_exists_wp_job_manager_resumes()
			&& trx_addons_get_option( 'optimize_css_and_js_loading' ) == 'full'
			&& ! trx_addons_is_preview()
			&& ! trx_addons_need_frontend_scripts( 'wp_job_manager_resumes' )
			&& apply_filters( 'trx_addons_filter_remove_3rd_party_styles', true, 'wp_job_manager_resumes' )
		) {
			$content = preg_replace( '#<link[^>]*href=[\'"][^\'"]*/wp-job-manager-resumes/[^>]*>#', '', $content );
		}
		return $content;
	}
}

// Remove plugin-specific styles and scripts if present in the page body output
if ( !function_exists( 'anesta_wp_job_manager_resumes_filter_body_output' ) ) {
	add_filter( 'trx_addons_filter_page_content', 'anesta_wp_job_manager_resumes_filter_body_output', 10, 1 );
	function anesta_wp_job_manager_resumes_filter_body_output( $content = '' ) {
		if ( anesta_exists_wp_job_manager_resumes()
			&& trx_addons_get_option( 'optimize_css_and_js_loading' ) == 'full'
			&& ! trx_addons_is_preview()
			&& ! trx_addons_need_frontend_scripts( 'wp_job_manager_resumes' )
			&& apply_filters( 'trx_addons_filter_remove_3rd_party_styles', true, 'wp_job_manager_resumes' )
		) {
			$content = preg_replace( '#<link[^>]*href=[\'"][^\'"]*/wp-job-manager-resumes/[^>]*>#', '', $content );
			$content = preg_replace( '#<script[^>]*src=[\'"][^\'"]*/wp-job-manager-resumes/[^>]*>[\\s\\S]*</script>#U', '', $content );
			$content = preg_replace( '#<script[^>]*id=[\'"]wp-job-manager-resumes[^>]*>[\\s\\S]*</script>#U', '', $content );
		}
		return $content;
	}
}


// Other
//------------------------------------------------------------------------
// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'anesta_wp_job_manager_resumes_init' ) ) {
	add_action( 'wp', 'anesta_wp_job_manager_resumes_init' );
	function anesta_wp_job_manager_resumes_init() {
		if ( anesta_exists_wp_job_manager_resumes() ) {
			if ( is_wp_resume_manager() && is_single() ) {
				add_action( 'anesta_action_page_content_start', 'anesta_skin_post_title' );
			}
		}
	}
}

// Page title
if ( ! function_exists( 'anesta_wp_job_manager_resumes_page_title' ) ) {
	add_filter( 'anesta_skin_filter_page_title', 'anesta_wp_job_manager_resumes_page_title' );
	function anesta_wp_job_manager_resumes_page_title( $allow ) {	
		if ( anesta_exists_wp_job_manager_resumes() ) {
			return is_single() ? true : $allow;
		}
		return $allow;
	}
}

// Detect current blog mode
if ( ! function_exists( 'anesta_wp_job_manager_resumes_detect_blog_mode' ) ) {
	//Handler of the add_filter( 'anesta_filter_detect_blog_mode', 'anesta_wp_job_manager_resumes_detect_blog_mode' );
	function anesta_wp_job_manager_resumes_detect_blog_mode( $mode = '' ) {
		if ( anesta_exists_wp_job_manager_resumes() ) {
			if ( is_wp_resume_manager() ) {
				$mode = 'wp-job-manager-resumes';
			}
		}
		return $mode;
	}
}

// Add meta to the single post
if ( ! function_exists( 'anesta_wp_job_manager_resumes_single_resume_start' ) ) {
	add_action( 'single_resume_start', 'anesta_wp_job_manager_resumes_single_resume_start' );
	function anesta_wp_job_manager_resumes_single_resume_start() {
		global $post;
		?><ul class="resume-meta">
			<li class="job-title"><?php the_candidate_title(); ?></li>
			<li class="location"><?php the_candidate_location(); ?></li>
			<li class="date-posted"><?php printf( __( 'Updated %s ago', 'anesta' ), human_time_diff( get_the_modified_time( 'U' ), current_time( 'timestamp' ) ) ); ?></li>
		</ul>

		<div class="resume-card"><?php
			the_candidate_photo();

			get_job_manager_template( 'contact-details.php', [ 'post' => $post ], 'wp-job-manager-resumes', RESUME_MANAGER_PLUGIN_DIR . '/templates/' );

			?><div class="resume_contacts">
				<h5><?php echo esc_html__('Profile & Portfolio', 'anesta'); ?></h5>
				<?php the_resume_links(); ?>
			</div>
		</div><?php
	}
}

// Related posts 
if ( ! function_exists( 'anesta_wp_job_manager_resumes_related_post_output' ) ) {
	add_filter( 'anesta_filter_related_post_output', 'anesta_wp_job_manager_resumes_related_post_output', 10, 2 );
	function anesta_wp_job_manager_resumes_related_post_output( $output, $id ) {
		if ( get_post_type( $id ) == 'resume' ) {
			ob_start();
			?><div class="resume-card"><?php
				the_candidate_photo(); 				
				?><h4 class="job-title"><?php the_candidate_title(); ?></h4>
				<div class="location"><?php the_candidate_location(); ?></div><?php
			?></div><?php

			$output = ob_get_contents();
			ob_end_clean();
		}
		return $output;
	}
}


// One-click import support
//------------------------------------------------------------------------
// Set plugin's specific importer options
if ( !function_exists( 'anesta_wp_job_manager_resumes_importer_set_options' ) ) {
	add_filter( 'trx_addons_filter_importer_options',	'anesta_wp_job_manager_resumes_importer_set_options' );
	function anesta_wp_job_manager_resumes_importer_set_options($options=array()) {
		if ( anesta_exists_wp_job_manager_resumes() && in_array('wp-job-manager-resumes', $options['required_plugins']) ) {
			$options['additional_options'][]	= '%resume_manager%';
		}
		return $options;
	}
}