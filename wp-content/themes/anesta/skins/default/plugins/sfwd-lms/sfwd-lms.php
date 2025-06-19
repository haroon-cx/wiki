<?php
/* LearnDash LMS support functions
------------------------------------------------------------------------------- */


// Check if plugin installed and activated
if ( ! function_exists( 'anesta_exists_sfwd_lms' ) ) {
	function anesta_exists_sfwd_lms() {
		return class_exists( 'LearnDash_Addon_Updater' );
	}
}

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'anesta_sfwd_lms_resumes_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'anesta_sfwd_lms_resumes_theme_setup3', 3 );
	function anesta_sfwd_lms_resumes_theme_setup3() {
		if ( anesta_exists_sfwd_lms() ) {
			// Section 'LearnDash LMS'
			anesta_storage_merge_array(
				'options', '', array_merge(
					array(
						'sfwd-lms'     => array(
							'title' => esc_html__( 'LearnDash LMS', 'anesta' ),
							'desc'  => wp_kses_data( __( 'Select parameters to display the LearnDash LMS pages', 'anesta' ) ),
							'icon'  => 'icon-wireframe',
							'type'  => 'section',
						)
					),
					anesta_options_get_list_cpt_options_body( 'sfwd-lms',  esc_html__( 'LearnDash LMS', 'anesta' ) ),              
					anesta_options_get_list_cpt_options_header( 'sfwd-lms',  esc_html__( 'LearnDash LMS', 'anesta' ), 'list' ), 
					anesta_options_get_list_cpt_options_sidebar( 'sfwd-lms',  esc_html__( 'Courses', 'anesta' ), 'list' ),
					anesta_options_get_list_cpt_options_widgets( 'sfwd-lms',  esc_html__( 'Courses', 'anesta' ) ),
					array(
						'blog_single_info_sfwd-lms'      => array(
							'title' => esc_html__( 'LearnDash LMS posts', 'anesta' ),
							'desc'  => '',
							'type'  => 'info',
						),
						'show_author_info_sfwd-lms'		=> array(
							'title' => esc_html__( 'Show author info', 'anesta' ),
							'desc'  => wp_kses_data( __( "Display block with information about post's author", 'anesta' ) ),
							'std'   => 1,
							'type'  => 'switch',
						),
						'show_related_posts_sfwd-lms'		=> array(
							'title'    => esc_html__( 'Show related posts', 'anesta' ),
							'desc'     => wp_kses_data( __( "Show 'Related posts' section on single post pages", 'anesta' ) ),
							'std'      => 1,
							'type'     => 'switch',
						),
						'posts_navigation_sfwd-lms'		=> array(
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
if ( ! function_exists( 'anesta_sfwd_lms_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'anesta_sfwd_lms_theme_setup9', 9 );
	function anesta_sfwd_lms_theme_setup9() {
		if ( anesta_exists_sfwd_lms() ) {			
			add_action( 'wp_enqueue_scripts', 'anesta_sfwd_lms_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_sfwd_lms', 'anesta_sfwd_lms_frontend_scripts', 10, 1 );

			add_action( 'wp_enqueue_scripts', 'anesta_sfwd_lms_frontend_scripts_responsive', 2000 );
			add_action( 'trx_addons_action_load_scripts_front_sfwd_lms', 'anesta_sfwd_lms_frontend_scripts_responsive', 10, 1 );
			
			add_filter( 'anesta_filter_merge_styles', 'anesta_sfwd_lms_merge_styles' );
			add_filter( 'anesta_filter_merge_styles_responsive', 'anesta_sfwd_lms_merge_styles_responsive' );

			add_action( 'anesta_filter_detect_blog_mode', 'anesta_sfwd_lms_detect_blog_mode' );
			add_filter( 'anesta_filter_sidebar_present', 'anesta_sfwd_lms_sidebar_present' );

			// Search theme-specific templates in the skin dir (if exists)
			add_filter( 'learndash_template', 'anesta_sfwd_lms_locate_template', 100, 5 );
		}
		if ( is_admin() ) {
            add_filter( 'anesta_filter_tgmpa_required_plugins', 'anesta_sfwd_tgmpa_required_plugins' );
        }
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'anesta_sfwd_tgmpa_required_plugins' ) ) {    
    function anesta_sfwd_tgmpa_required_plugins( $list = array() ) {
		if ( anesta_exists_sfwd_lms() ) {
	        if ( anesta_storage_isset( 'required_plugins', 'buddypress-learndash' ) && anesta_storage_get_array( 'required_plugins', 'buddypress-learndash', 'install' ) !== false ) {
	            $list[] = array(
	                'name'     => anesta_storage_get_array( 'required_plugins', 'buddypress-learndash', 'title' ),
	                'slug'     => 'buddypress-learndash',
	                'required' => false,
	            );
	        }
        }
        return $list;
    }
}


// Styles & Scripts
//------------------------------------------------------------------------
// Enqueue styles for frontend
if ( ! function_exists( 'anesta_sfwd_lms_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_sfwd_lms_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_sfwd_lms', 'anesta_sfwd_lms_frontend_scripts', 10, 1 );
	function anesta_sfwd_lms_frontend_scripts( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && anesta_need_frontend_scripts( 'sfwd_lms' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$anesta_url = anesta_get_file_url( 'plugins/sfwd-lms/sfwd-lms.css' );
			if ( '' != $anesta_url ) {
				wp_enqueue_style( 'anesta-sfwd-lms', $anesta_url, array(), null );
			}
		}
	}
}

// Enqueue responsive styles for frontend
if ( ! function_exists( 'anesta_sfwd_lms_frontend_scripts_responsive' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_sfwd_lms_frontend_scripts_responsive', 2000 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_sfwd_lms', 'anesta_sfwd_lms_frontend_scripts_responsive', 10, 1 );
	function anesta_sfwd_lms_frontend_scripts_responsive( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && anesta_need_frontend_scripts( 'sfwd_lms' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$anesta_url = anesta_get_file_url( 'plugins/sfwd-lms/sfwd-lms-responsive.css' );
			if ( '' != $anesta_url ) {
				wp_enqueue_style( 'anesta-sfwd-lms-responsive', $anesta_url, array(), null, anesta_media_for_load_css_responsive( 'sfwd-lms' ) );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'anesta_sfwd_lms_merge_styles' ) ) {
	//Handler of the add_filter( 'anesta_filter_merge_styles', 'anesta_sfwd_lms_merge_styles');
	function anesta_sfwd_lms_merge_styles( $list ) {
		$list[ 'plugins/sfwd-lms/sfwd-lms.css' ] = true;
		return $list;
	}
}

// Merge responsive styles
if ( ! function_exists( 'anesta_sfwd_lms_merge_styles_responsive' ) ) {
	//Handler of the add_filter('anesta_filter_merge_styles_responsive', 'anesta_sfwd_lms_merge_styles_responsive');
	function anesta_sfwd_lms_merge_styles_responsive( $list ) {
		$list[ 'plugins/sfwd-lms/sfwd-lms-responsive.css' ] = true;
		return $list;
	}
}

// Add plugin-specific colors and fonts to the custom CSS
if ( anesta_exists_sfwd_lms() ) {
	require_once anesta_get_file_dir( 'plugins/sfwd-lms/sfwd-lms-style.php' );
}

// Load required styles and scripts for the frontend
if ( !function_exists( 'anesta_sfwd_lms_load_scripts_front' ) ) {
	add_action( "wp_enqueue_scripts", 'anesta_sfwd_lms_load_scripts_front', 20 );
	add_action( 'trx_addons_action_pagebuilder_preview_scripts', 'anesta_sfwd_lms_load_scripts_front', 10, 1 );
	function anesta_sfwd_lms_load_scripts_front( $force = false ) {
		static $loaded = false;
		if ( ! anesta_exists_sfwd_lms() || !anesta_exists_trx_addons() ) return;
		$debug    = trx_addons_is_on( trx_addons_get_option( 'debug_mode' ) );
		$optimize = ! trx_addons_is_off( trx_addons_get_option( 'optimize_css_and_js_loading' ) );
		$preview_elm = trx_addons_is_preview( 'elementor' );
		$preview_gb  = trx_addons_is_preview( 'gutenberg' );
		$theme_full  = current_theme_supports( 'styles-and-scripts-full-merged' );
		$need        = ! $loaded && ( ! $preview_elm || $debug ) && ! $preview_gb && $optimize && (
						$force === true
							|| ( $preview_elm && $debug )
							|| trx_addons_sc_check_in_content( array(
									'sc' => 'sfwd_lms',
									'entries' => array(
												array( 'type' => 'sc',  'sc' => 'ld-' ),
												array( 'type' => 'sc',  'sc' => 'ld_' ),
												//array( 'type' => 'gb',  'sc' => 'wp:trx-addons/charts' ),// This sc is not exists for GB
												array( 'type' => 'elm', 'sc' => '"widgetType":"ld-"' ),
												array( 'type' => 'elm', 'sc' => '"widgetType":"ld_"' ),
												array( 'type' => 'elm', 'sc' => '"shortcode":"[ld-' ),
												array( 'type' => 'elm', 'sc' => '"shortcode":"[ld_' ),
									)
								) ) );
		if ( ! $loaded && ! $preview_gb && ( ( ! $optimize && $debug ) || ( $optimize && $need ) ) ) {
			$loaded = true;
			do_action( 'trx_addons_action_load_scripts_front', $force, 'sfwd_lms' );
		}
		if ( ! $loaded && $preview_elm && $optimize && ! $debug && ! $theme_full ) {
			do_action( 'trx_addons_action_load_scripts_front', false, 'sfwd_lms', 2 );
		}
	}
}

// Load styles and scripts if present in the cache of the menu or layouts or finally in the whole page output
if ( !function_exists( 'anesta_sfwd_lms_check_in_html_output' ) ) {
	add_action( 'trx_addons_action_check_page_content', 'anesta_sfwd_lms_check_in_html_output', 10, 1 );
	function anesta_sfwd_lms_check_in_html_output( $content = '' ) {
		if ( anesta_exists_sfwd_lms()
			&& ! trx_addons_need_frontend_scripts( 'sfwd_lms' )
			&& ! trx_addons_is_off( trx_addons_get_option( 'optimize_css_and_js_loading' ) )
		) {
			$checklist = apply_filters( 'trx_addons_filter_check_in_html', array(
							'class=[\'"][^\'"]*learndash',
							'id=[\'"][^\'"]*learndash',
							'class=[\'"][^\'"]*ld-',
							),
							'sfwd_lms'
						);
			foreach ( $checklist as $item ) {
				if ( preg_match( "#{$item}#", $content, $matches ) ) {
					anesta_sfwd_lms_load_scripts_front( true );
					break;
				}
			}
		}
		return $content;
	}
}

// Remove plugin-specific styles if present in the page head output
if ( !function_exists( 'anesta_sfwd_lms_filter_head_output' ) ) {
	add_filter( 'trx_addons_filter_page_head', 'anesta_sfwd_lms_filter_head_output', 10, 1 );
	function anesta_sfwd_lms_filter_head_output( $content = '' ) {
		if ( anesta_exists_sfwd_lms()
			&& trx_addons_get_option( 'optimize_css_and_js_loading' ) == 'full'
			&& ! trx_addons_is_preview()
			&& ! trx_addons_need_frontend_scripts( 'sfwd_lms' )
			&& apply_filters( 'trx_addons_filter_remove_3rd_party_styles', true, 'sfwd_lms' )
		) {
			$content = preg_replace( '#<link[^>]*href=[\'"][^\'"]*/sfwd-lms/[^>]*>#', '', $content );
		}
		return $content;
	}
}

// Remove plugin-specific styles and scripts if present in the page body output
if ( !function_exists( 'anesta_sfwd_lms_filter_body_output' ) ) {
	add_filter( 'trx_addons_filter_page_content', 'anesta_sfwd_lms_filter_body_output', 10, 1 );
	function anesta_sfwd_lms_filter_body_output( $content = '' ) {
		if ( anesta_exists_sfwd_lms()
			&& trx_addons_get_option( 'optimize_css_and_js_loading' ) == 'full'
			&& ! trx_addons_is_preview()
			&& ! trx_addons_need_frontend_scripts( 'sfwd_lms' )
			&& apply_filters( 'trx_addons_filter_remove_3rd_party_styles', true, 'sfwd_lms' )
		) {
			$content = preg_replace( '#<link[^>]*href=[\'"][^\'"]*/sfwd-lms/[^>]*>#', '', $content );
			$content = preg_replace( '#<script[^>]*src=[\'"][^\'"]*/sfwd-lms/[^>]*>[\\s\\S]*</script>#U', '', $content );
			$content = preg_replace( '#<script[^>]*id=[\'"]sfwd-lms[^>]*>[\\s\\S]*</script>#U', '', $content );
		}
		return $content;
	}
}


// Other
//------------------------------------------------------------------------
// Detect current blog mode
if ( ! function_exists( 'anesta_sfwd_lms_detect_blog_mode' ) ) {
	//Handler of the add_filter( 'anesta_filter_detect_blog_mode', 'anesta_sfwd_lms_detect_blog_mode' );
	function anesta_sfwd_lms_detect_blog_mode( $mode = '' ) {
		if ( anesta_exists_sfwd_lms() ) {
			if ( !is_search() && learndash_is_valid_post_type( get_post_type() ) ) {
				$mode = 'sfwd-lms';
			}
		}
		return $mode;
	}
}

// Detect current blog mode
if ( ! function_exists( 'anesta_sfwd_lms_sidebar_present' ) ) {
	//Handler of the add_filter( 'anesta_filter_sidebar_present', 'anesta_sfwd_lms_sidebar_present' );
	function anesta_sfwd_lms_sidebar_present( $is ) {
		if ( anesta_exists_sfwd_lms() ) {
			$post_type = get_post_type();
			if ( !is_search() && in_array( $post_type, array('sfwd-lessons', 'sfwd-topic', 'sfwd-quiz') ) ) {
				return false;
			}
		}
		return $is;
	}
}

// Search skin-specific templates in the skin dir (if exists)
if ( ! function_exists( 'anesta_sfwd_lms_locate_template' ) ) {
	//Handler of the add_filter( 'learndash_template', 'anesta_sfwd_lms_locate_template', 100, 53 );
	function anesta_sfwd_lms_locate_template( $filepath, $name, $args, $echo, $return_file_path ) {
		$folders = apply_filters( 'anesta_filter_sfwd_lms_locate_template_folders', array(
			'plugins/sfwd-lms/templates'
		) );
		foreach ( $folders as $f ) {
			$theme_dir = apply_filters( 'anesta_filter_get_theme_file_dir', '', trailingslashit( anesta_esc( $f ) ) . $name . '.php' );
			if ( '' != $theme_dir ) {
				$filepath = $theme_dir;
				break;
			}
		}
		return $filepath;
	}
}

// Course grid: add course status
if ( ! function_exists( 'anesta_sfwd_lms_course_grid_ribbon_text' ) ) {
	add_filter( 'learndash_course_grid_ribbon_text', 'anesta_sfwd_lms_course_grid_ribbon_text', 10, 3 );
	function anesta_sfwd_lms_course_grid_ribbon_text( $ribbon_text, $course_id, $price_type ) {
		if ( is_user_logged_in() ) {
			$ribbon_text = learndash_course_status( $course_id );
		} 
		return $ribbon_text;
	}
}

// Course grid: add course status class
if ( ! function_exists( 'anesta_sfwd_lms_course_grid_ribbon_class' ) ) {
	add_filter( 'learndash_course_grid_ribbon_class', 'anesta_sfwd_lms_course_grid_ribbon_class', 10, 3 );
	function anesta_sfwd_lms_course_grid_ribbon_class( $class, $course_id, $price_type ) {
		if ( is_user_logged_in() ) {
			$class .= ' ' . str_replace( " ", "-", strtolower( learndash_course_status( $course_id ) ) );
		} 
		return $class;
	}
}

// Course grid: replace video with image
if ( ! function_exists( 'anesta_sfwd_lms_course_grid_html_output' ) ) {
	add_filter( 'learndash_course_grid_html_output', 'anesta_sfwd_lms_course_grid_html_output', 10, 4 );
	function anesta_sfwd_lms_course_grid_html_output( $output, $post, $shortcode_atts, $user_id ) {
		$post_id =  $post->ID;
		$enable_video = get_post_meta( $post_id, '_learndash_course_grid_enable_video_preview', true );
		$embed_code   = get_post_meta( $post_id, '_learndash_course_grid_video_embed_code', true );

		if ( 1 == $enable_video && ! empty( $embed_code ) && has_post_thumbnail() ) {
			// Link 
			if ( isset( $shortcode_atts['course_id'] ) ) {
				$button_link = learndash_get_step_permalink( get_the_ID(), $shortcode_atts['course_id'] );
			} else {
				$button_link = get_permalink();
			}
			$button_link = apply_filters( 'learndash_course_grid_custom_button_link', $button_link, $post_id );

			// Thumb size
			$thumb_size = isset( $shortcode_atts['thumb_size'] ) && ! empty( $shortcode_atts['thumb_size'] ) ? $shortcode_atts['thumb_size'] : 'course-thumb';

			// Image
			$image = 	'<a href="' . esc_url( $button_link ) . '" class="ld_course_grid_img" rel="bookmark">
							' . get_the_post_thumbnail( $post_id, $thumb_size ) . '
						</a>';

			// Add image
			$replace = '<div class="ld_course_grid_video_embed">'; 
			$output = str_replace( $replace, $image . $replace, $output );
		}
		return $output;
	}
}

// Course grid: add attribute pager to shortcode
if ( ! function_exists( 'anesta_sfwd_lms_course_list_shortcode_attr_defaults' ) ) {
	add_filter( 'ld_course_list_shortcode_attr_defaults', 'anesta_sfwd_lms_course_list_shortcode_attr_defaults', 10, 3 );
	function anesta_sfwd_lms_course_list_shortcode_attr_defaults( $attr_defaults, $attr ) {
		$attr_defaults['pager'] = false;
		return $attr_defaults;
	}
}

// Course grid: hide pager
if ( ! function_exists( 'anesta_sfwd_lms_course_list_output' ) ) {
	add_filter( 'ld_course_list', 'anesta_sfwd_lms_course_list_output', 10, 3 );
	function anesta_sfwd_lms_course_list_output( $output, $atts, $filter ) {
		if ( $atts['pager'] == false ) {
			$replace = 'learndash-pager '; 
			$output = str_replace( $replace, $replace . 'hide ', $output );
			$output = str_replace( 'disabled="disabled"', '', $output );
		}		
		return $output;
	}
}

// Course grid: output
if ( ! function_exists( 'anesta_sfwd_lms_course_html_outputt' ) ) {
	add_filter( 'learndash_course_grid_html_output', 'anesta_sfwd_lms_course_html_outputt', 10, 4 );
	function anesta_sfwd_lms_course_html_outputt( $output, $post, $shortcode_atts, $user_id ) {
		$output = str_replace( ' frameborder="0"', '', $output );	
		return $output;
	}
}

// Course page: add header with thumbnails, block with short info and content wrap
if ( ! function_exists( 'anesta_sfwd_lms_course_before' ) ) {
	add_action( 'learndash-course-before', 'anesta_sfwd_lms_course_before', 10, 3 );
	function anesta_sfwd_lms_course_before( $id, $course_id, $user_id ) {
		// Header
		anesta_sfwd_lms_course_header( $course_id );

		// Short info
		anesta_sfwd_lms_course_short_info( $id );

		?><div class="ld-content"><?php
	}
}

// Course page: close tag of content wrap 
if ( ! function_exists( 'anesta_sfwd_lms_course_after' ) ) {
	add_action( 'learndash-course-after', 'anesta_sfwd_lms_course_after', 10, 3 );
	function anesta_sfwd_lms_course_after( $id, $course_id, $user_id ) {
		?>		
		<div class="ld-author">
			<h5><?php echo esc_html__( "About Instructor", 'anesta' ); ?></h5>
			<div class="author">
				<?php anesta_sfwd_lms_user_info( get_the_author_meta( 'ID' ), true ); ?>
			</div>
		</div>	
		</div><?php
	}
}

// Lessons and topic page: add context sidebar
if ( ! function_exists( 'anesta_sfwd_lms_context_sidebar' ) ) {
	add_action( 'anesta_action_page_content_start', 'anesta_sfwd_lms_context_sidebar' );
	function anesta_sfwd_lms_context_sidebar() {
		$post_type = get_post_type();
		if ( is_singular() && in_array( $post_type, array('sfwd-lessons', 'sfwd-topic', 'sfwd-quiz') ) ) {
			$post_id = get_the_ID();
			$user_id = get_current_user_id();
			$course_id = learndash_get_course_id( $post_id );

			?><div class="ld-course-context">
				<a href="<?php echo esc_url(get_permalink($course_id)); ?>" class="ld-course-link"><?php echo esc_html__('Back to course', 'anesta'); ?></a>
				<h4 class="ld-course-title">					
					<?php echo esc_html(get_the_title( $course_id )); ?>
				</h4><?php

				// Course progress
				if ( is_user_logged_in() ) { 					
					echo do_shortcode( '[learndash_course_progress course_id="' . $course_id . '" user_id="' . $user_id . '"]' );
				}

				// Course lessons and topics
				echo do_shortcode('[course_content course_id="' . $course_id . '"]'); 

				// Get list of users for course
				anesta_sfwd_lms_users_for_course( $course_id ); ?>
			</div><?php
		}
	}
}

// Lessons and topic page: Add title before quiz list
if ( ! function_exists( 'anesta_sfwd_lms_add_quiz_heading' ) ) {
	add_action( 'learndash-quiz-row-before', 'anesta_sfwd_lms_add_quiz_heading', 10, 3 );
	function anesta_sfwd_lms_add_quiz_heading($id, $course_id, $user_id) {
		$post_type = get_post_type();
		if ( in_array( $post_type, array('sfwd-lessons', 'sfwd-topic', 'sfwd-quiz') ) ) {
			?><div class="ld-item-list-section-heading quiz"> 		
				<div class="ld-lesson-section-heading" aria-role="heading"><?php echo esc_html__('Quizzes', 'anesta'); ?></div> 	
			</div><?php
		}
	}
}

// Lessons and topic page: Add post title
if ( ! function_exists( 'anesta_sfwd_lms_add_post_title' ) ) {
	add_action( 'learndash-content-tabs-before', 'anesta_sfwd_lms_add_post_title', 10, 3 );
	function anesta_sfwd_lms_add_post_title($id, $course_id, $user_id) {
		$post_type = get_post_type();
		if ( in_array( $post_type, array('sfwd-lessons', 'sfwd-topic', 'sfwd-quiz') ) ) {
			$post_title = get_the_title();
			?><div class="ld-post-header"> 		
				<h1 class="ld-post-title"><?php echo esc_html( $post_title ); ?></h1> 	
				<div class="ld-post-meta">
					<div class="author">
						<?php anesta_sfwd_lms_user_info( get_the_author_meta( 'ID' ), true ); ?>
					</div>
				</div>
			</div><?php
		}
	}
}

// User info
if ( ! function_exists( 'anesta_sfwd_lms_user_info' ) ) {
	function anesta_sfwd_lms_user_info( $id, $courses = false ) {
		$user_info = get_userdata( $id );
		$user_url = function_exists('bp_core_get_user_domain') ? bp_core_get_user_domain( $id ) : $user_info->user_url;
		$user_name = !empty($user_info) ? $user_info->display_name : '';
		$user_gravatar = get_avatar_url( $id, array('size' => 41) );

		if ( $user_gravatar ) {
			?><img src="<?php echo esc_url( $user_gravatar ); ?>" alt=""><?php
		}

		if ( $courses ) {
			?><div><?php
		}

		if ( !empty( $user_url ) ) {
			?><a href="<?php echo esc_url( $user_url ); ?>"><?php echo esc_html( $user_name ); ?></a><?php
		} else {
			?><div><?php echo esc_html( $user_name ); ?></span><?php
		}

		if ( $courses ) {
				?><span class="courses_count">
					<?php echo esc_html( count_user_posts( $id, 'sfwd-courses' ) ) . ' ' . esc_html__( 'Courses', 'anesta' ); ?>
				</span>
			</div><?php
		}
	}
}

// Course page: Header
if ( ! function_exists( 'anesta_sfwd_lms_course_header' ) ) {
	function anesta_sfwd_lms_course_header( $id ) {	
		$class = '';
		$description = get_post_meta( $id, '_learndash_course_grid_short_description', true );
		$enable_video = get_post_meta( $id, '_learndash_course_grid_enable_video_preview', true );
		$embed_code   = get_post_meta( $id, '_learndash_course_grid_video_embed_code', true );

		if( has_post_thumbnail() ) {
			$class = ' has_thumbnail ' . anesta_add_inline_css_class( 'background-image: url(' . esc_url( get_the_post_thumbnail_url($id, 'anesta-thumb-huge') ) . ') ' );
		}
		if ( 1 == $enable_video && ! empty( $embed_code ) ) {
			$class .= ' with_info';
		}	

		?><div class="ld-header<?php echo esc_attr( $class ); ?>">
			<div class="ld-header-wrap">
				<h1 class="ld-title"><?php echo esc_html( get_the_title() ); ?></h1>
				<?php if ( ! empty( $description ) ) { ?>
					<div class="ld-description"><?php echo do_shortcode( wp_specialchars_decode( $description ) ); ?></div><?php
				} ?>

				<div class="sc_button_wrap">
					<a href="#ld-course-status" class="sc_button sc_button_simple sc_button_size_normal">
						<span class="sc_button_text">
							<span class="sc_button_title"><?php echo esc_html__( 'Course Details', 'anesta' ); ?></span>
						</span>
					</a>
				</div>

				<div class="ld-meta">
					<div class="author">
						<?php anesta_sfwd_lms_user_info( get_the_author_meta( 'ID' ) ); ?>
					</div>
					<div class="date"><?php echo esc_html( get_the_date() ); ?></div>
				</div>
			</div>
		</div><?php 
	}
}

// Course page: Short info
if ( ! function_exists( 'anesta_sfwd_lms_course_short_info' ) ) {
	function anesta_sfwd_lms_course_short_info( $id ) {
		$course = get_post( $id );	
		$course_settings = learndash_get_setting( $course );	
		$enable_video = get_post_meta( $id, '_learndash_course_grid_enable_video_preview', true );
		$embed_url  = get_post_meta( $id, '_learndash_course_grid_video_embed_code', true );

		if ( 1 == $enable_video && ! empty( $embed_url ) ) { 
			// Retrive oembed HTML if URL provided
			if ( preg_match( '/^http/', $embed_url ) ) {
				$embed_code = wp_oembed_get( $embed_url, array( 'height' => 200, 'width' => 300 ) );
			}

			?><div class="ld-info">
				<div class="ld-info-video">					
					<?php 
					if ( function_exists( 'trx_addons_sc_widget_video' ) ) {
						$thumb = has_post_thumbnail() ? get_the_post_thumbnail_url($id, 'medium') : '';			
						echo do_shortcode( '[trx_widget_video link="' . $embed_url . '" cover="' . $thumb  . '" popup="1"]' );
					} else {
						anesta_show_layout($embed_code);
					} ?>
				</div>

				<div class="ld-info-content">					
					<?php $status = '';
					if ( is_user_logged_in() ) {
						$status = ( learndash_is_item_complete() ? 'complete' : 'incomplete' );
						learndash_status_bubble( $status );
					} 

					?><div class="ld-access-mode"><?php
					$price_type = learndash_get_setting( $id, 'course_price_type' );
					switch ( $price_type ) {
					    case 'open':
					        echo esc_html__( 'Open Course', 'anesta' );
					        break;
					    case 'free':
					        echo esc_html__( 'Free Course', 'anesta' );
					        break;
					    case 'paynow':
					        echo esc_html__( 'Paid Course', 'anesta' );
					        break;
					    case 'subscribe':
					        echo esc_html__( 'Recurring Course', 'anesta' );
					        break;
					    case 'closed':
					        echo esc_html__( 'Closed Course', 'anesta' );
					        break;
					}
					?></div><?php

					if ( ( isset( $course_settings['course_materials'] ) ) && ( ! empty( $course_settings['course_materials'] ) ) ) {
						?><div class="ld-materials">
						<h6><?php echo esc_html__( 'Course Include', 'anesta' ); ?></h6><?php
						anesta_show_layout( $course_settings['course_materials'] );
						?></div><?php
					}
					?>
				</div>
			</div><?php
		}	
	}
}

// Get list of users for course
if ( ! function_exists( 'anesta_sfwd_lms_users_for_course' ) ) {
	function anesta_sfwd_lms_users_for_course( $id ) {
		$total_users = 0;
		$users_query = learndash_get_users_for_course( $id );

		if ( is_a( $users_query, 'WP_User_Query' ) ) {
			$total_users = absint( $users_query->total_users );

			if ( $total_users > 0 ) {
				?><div class="ld-course-users">
					<h6 class="ld-course-users-title">
						<?php echo esc_html__('Participants', 'anesta'); ?>
						<span class="total"><?php echo esc_html($total_users); ?></span>
					</h6>
					<ul class="ld-course-users-list"><?php

						$users_list = $users_query->results;
						foreach ($users_list as $user_id) {
							?><li class="ld-course-user">
								<?php anesta_sfwd_lms_user_info( $user_id ); ?>
							</li><?php
						} ?>
					</ul>
				</div><?php
			}
		}
	}
}

// Get list of users for course
if ( ! function_exists( 'anesta_sfwd_lms_video_cover_thumb_size' ) ) {
	add_filter( 'trx_addons_filter_video_cover_thumb_size', 'anesta_sfwd_lms_video_cover_thumb_size', 11 );
	function anesta_sfwd_lms_video_cover_thumb_size( $size ) {
		if ( anesta_exists_sfwd_lms() ) {
			if ( 'sfwd-courses' === get_post_type( get_the_ID() ) ) {
				$size = 'medium';
			}
		}
		return $size;
	}
}


// One-click import support
//------------------------------------------------------------------------

// Check plugin in the required plugins
if ( !function_exists( 'anesta_sfwd_lms_required_plugins' ) ) {
    if (is_admin()) add_filter( 'trx_addons_filter_importer_required_plugins',	'anesta_sfwd_lms_required_plugins', 10, 2 );
    function anesta_sfwd_lms_required_plugins($not_installed='', $list='') {
        if (strpos($list, 'sfwd-lms')!==false && !anesta_exists_sfwd_lms() )
            $not_installed .= '<br>' . esc_html__('LearnDash LMS', 'anesta');
        return $not_installed;
    }
}

// Set plugin's specific importer options
if ( !function_exists( 'anesta_sfwd_lms_importer_set_options' ) ) {
	add_filter( 'trx_addons_filter_importer_options',	'anesta_sfwd_lms_importer_set_options' );
	function anesta_sfwd_lms_importer_set_options($options=array()) {
		if ( anesta_exists_sfwd_lms()  ) {
			$options['additional_options'][]	= '%sfwd%';
			$options['additional_options'][]	= 'learndash%';
			$options['additional_options'][]	= 'learndash_%';

			if (is_array($options['files']) && count($options['files']) > 0) {
				foreach ($options['files'] as $k => $v) {
					$options['files'][$k]['file_with_sfwd-lms'] = str_replace('name.ext', 'sfwd-lms.txt', $v['file_with_']);
				}
			}
		}
		return $options;
	}
}

// Prevent import plugin's specific options if plugin is not installed
if ( !function_exists( 'anesta_sfwd_lms_check_options' ) ) {
	add_filter( 'trx_addons_filter_import_theme_options', 'anesta_sfwd_lms_check_options', 10, 4 );
	function anesta_sfwd_lms_check_options($allow, $k, $v, $options) {
		if ( $allow && ( strpos( $k, 'sfwd' ) === 0 || strpos( $k, 'learndash' ) === 0 ) || strpos( $k, 'learndash_' ) === 0 ) {
			$allow = anesta_exists_sfwd_lms() && in_array('sfwd-lms', $options['required_plugins']);
		}
		return $allow;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'anesta_sfwd_lms_show_params' ) ) {
	add_action( 'trx_addons_action_importer_params',	'anesta_sfwd_lms_show_params', 10, 1 );
	function anesta_sfwd_lms_show_params($importer) {
		if ( anesta_exists_sfwd_lms() && in_array('sfwd-lms', $importer->options['required_plugins']) ) {
			$importer->show_importer_params(array(
				'slug' => 'sfwd-lms',
				'title' => esc_html__('Import LearnDash LMS', 'anesta'),
				'part' => 0
			));
		}
	}
}

// Import posts
if ( !function_exists( 'anesta_sfwd_lms_importer_import' ) ) {
	if (is_admin()) add_action( 'trx_addons_action_importer_import',	'anesta_sfwd_lms_importer_import', 10, 2 );
	function anesta_sfwd_lms_importer_import($importer, $action) {
		if ( anesta_exists_sfwd_lms() && in_array('sfwd-lms', $importer->options['required_plugins']) ) {
			if ( $action == 'import_sfwd-lms' ) {
				$importer->response['start_from_id'] = 0;
				$importer->import_dump('sfwd-lms', esc_html__('LearnDash LMS meta', 'anesta'));
			}
		}
	}
}


// Display import progress
if ( !function_exists( 'anesta_sfwd_lms_import_fields' ) ) {
	add_action( 'trx_addons_action_importer_import_fields',	'anesta_sfwd_lms_import_fields', 10, 1 );
	function anesta_sfwd_lms_import_fields($importer) {
		if ( anesta_exists_sfwd_lms() && in_array('sfwd-lms', $importer->options['required_plugins']) ) {
			$importer->show_importer_fields(array(
					'slug'=>'sfwd-lms',
					'title' => esc_html__('LearnDash LMS meta', 'anesta')
				)
			);
		}
	}
}

// Export posts
if ( !function_exists( 'anesta_sfwd_lms_export' ) ) {
	add_action( 'trx_addons_action_importer_export',	'anesta_sfwd_lms_export', 10, 1 );
	function anesta_sfwd_lms_export($importer) {
		if ( anesta_exists_sfwd_lms() && in_array('sfwd-lms', $importer->options['required_plugins']) ) {
			trx_addons_fpc($importer->export_file_dir('sfwd-lms.txt'), serialize( array(
					"learndash_pro_quiz_master"				=> $importer->export_dump("learndash_pro_quiz_master"),
					"learndash_pro_quiz_prerequisite"		=> $importer->export_dump("learndash_pro_quiz_prerequisite"),
					"learndash_pro_quiz_question"			=> $importer->export_dump("learndash_pro_quiz_question"),
					"learndash_pro_quiz_statistic"			=> $importer->export_dump("learndash_pro_quiz_statistic"),
					"learndash_pro_quiz_statistic_ref"		=> $importer->export_dump("learndash_pro_quiz_statistic_ref"),
					"learndash_user_activity"				=> $importer->export_dump("learndash_user_activity"),
					"learndash_user_activity_meta"			=> $importer->export_dump("learndash_user_activity_meta")
				) )
			);
		}
	}
}

// Display exported data in the fields
if ( !function_exists( 'anesta_sfwd_lms_export_fields' ) ) {
	add_action( 'trx_addons_action_importer_export_fields',	'anesta_sfwd_lms_export_fields', 10, 1 );
	function anesta_sfwd_lms_export_fields($importer) {
		if ( anesta_exists_sfwd_lms() && in_array('sfwd-lms', $importer->options['required_plugins']) ) {
			$importer->show_exporter_fields(array(
					'slug'	=> 'sfwd-lms',
					'title' => esc_html__('LearnDash LMS', 'anesta')
				)
			);
		}
	}
}