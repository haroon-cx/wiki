<?php
/* LearnPress support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 1 - register filters, that add/remove lists items for the Theme Options
if ( ! function_exists( 'anesta_learpress_theme_setup1' ) ) {
	add_action( 'after_setup_theme', 'anesta_learpress_theme_setup1', 1 );
	function anesta_learpress_theme_setup1() {
		if ( ! defined( 'LP_COURSE_CPT' ) ) define( 'LP_COURSE_CPT', 'lp_course' );
		if ( ! defined( 'LP_LESSON_CPT' ) ) define( 'LP_LESSON_CPT', 'lp_lesson' );
		if ( ! defined( 'LP_QUESTION_CPT' ) ) define( 'LP_QUESTION_CPT', 'lp_question' );
		if ( ! defined( 'LP_QUIZ_CPT' ) ) define( 'LP_QUIZ_CPT', 'lp_quiz' );
		if ( ! defined( 'LP_ORDER_CPT' ) ) define( 'LP_ORDER_CPT', 'lp_order' );
		if ( ! defined( 'LP_COURSE_TAXONOMY' ) ) define( 'LP_COURSE_TAXONOMY', 'course_category' );
		add_filter( 'anesta_filter_list_sidebars', 'anesta_learnpress_list_sidebars' );
	}
}


// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'anesta_learnpress_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'anesta_learnpress_theme_setup3', 3 );
	function anesta_learnpress_theme_setup3() {
		if ( anesta_exists_learnpress() ) {
			// Section 'LearnPress'
			anesta_storage_merge_array(
				'options', '', array_merge(
					array(
						'learnpress' => array(
							"title" => esc_html__( 'LearnPress', 'anesta' ),
							"desc" => wp_kses_data( __( 'Select parameters to display the LearnPress pages', 'anesta' ) ),
							'icon'  => 'icon-courses',
							"type" => "section"
						),
					),
					anesta_options_get_list_cpt_options( 'learnpress' )
				)
			);
		}
	}
}


// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'anesta_learnpress_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'anesta_learnpress_theme_setup9', 9 );
	function anesta_learnpress_theme_setup9() {
		if ( anesta_exists_learnpress() ) {
			add_action( 'wp_enqueue_scripts', 'anesta_learnpress_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_learnpress', 'anesta_learnpress_frontend_scripts', 10, 1 );
			add_action( 'wp_enqueue_scripts', 'anesta_learnpress_frontend_scripts_responsive', 2000 );
			add_action( 'trx_addons_action_load_scripts_front_learnpress', 'anesta_learnpress_frontend_scripts_responsive', 10, 1 );
			add_filter( 'anesta_filter_merge_styles', 'anesta_learnpress_merge_styles' );
			add_filter( 'anesta_filter_merge_styles_responsive', 'anesta_learnpress_merge_styles_responsive' );
			add_filter( 'anesta_filter_post_type_taxonomy', 'anesta_learnpress_post_type_taxonomy', 10, 2 );
			add_action( 'anesta_action_override_theme_options', 'anesta_learnpress_override_theme_options' );
			add_filter( 'anesta_filter_list_posts_types', 'anesta_learnpress_list_post_types');
			add_filter( 'anesta_filter_detect_blog_mode', 'anesta_learnpress_detect_blog_mode' );
			add_filter( 'trx_addons_filter_get_blog_title', 'anesta_learnpress_get_blog_title' );
			add_filter( 'anesta_filter_get_post_categories', 'anesta_learnpress_get_post_categories', 10, 2 );
			// Redirect templates to the skin
			// Attention! If theme use new templates (which is absent in the plugin) - load it only with 'learn_press_get_template'
			//            not with 'learn_press_get_template_part'
			add_filter( 'learn_press_locate_template', 'anesta_learnpress_locate_template', 10000, 3 );
			add_filter( 'learn_press_get_template_part', 'anesta_learnpress_get_template_part', 10000, 3 );
			add_filter( 'learn_press_get_template', 'anesta_learnpress_get_template', 10000, 5 );
			
			// Allow override templates in the theme
			add_filter( 'learn-press/override-templates', '__return_true' );
			// Remove breadcrumbs (if our breadcrumb shown)
			add_action( 'learn-press/before-main-content', 'anesta_learnpress_remove_breadcrumb', 0 );
			
			LP()->template( 'course' )->remove( 'learn-press/course-content-summary', array( '<div class="course-detail-info"> <div class="lp-content-area"> <div class="course-info-left">', 'course-info-left-open' ), 10 );
			
			LP()->template( 'course' )->remove_callback( 'learn-press/course-content-summary', 'single-course/title', 10 );
			LP()->template( 'course' )->remove_callback( 'learn-press/course-content-summary', 'single-course/meta-primary', 10 );
			LP()->template( 'course' )->remove_callback( 'learn-press/course-content-summary', 'single-course/meta-secondary', 10 );
			
			LP()->template( 'course' )->remove( 'learn-press/course-content-summary', array( ' </div> </div> </div>', 'course-info-left-close' ), 15 );
			
			add_action(
					'learn-press/course-content-summary',
					LP()->template( 'course' )->text(
							'<div class="course-detail-info"> <div class="lp-content-area"> <div class="course-info-left">',
							'course-info-left-open'
					),
					35
			);
			add_action(
					'learn-press/course-content-summary',
					LP()->template( 'course' )->callback( 'single-course/meta-primary' ),
					35
			);
			add_action( 'learn-press/course-content-summary', LP()->template( 'course' )->callback( 'single-course/title' ), 35 );
			add_action(
					'learn-press/course-content-summary',
					LP()->template( 'course' )->callback( 'single-course/meta-secondary' ),
					35
			);
			add_action(
				'learn-press/course-content-summary',
				LP()->template( 'course' )->text( ' </div> </div> </div>', 'course-info-left-close' ),
				35
			);
		}
		if ( is_admin() ) {
			add_filter( 'anesta_filter_tgmpa_required_plugins', 'anesta_learnpress_tgmpa_required_plugins' );
		}
	}
}


// Filter to add in the required plugins list
if ( !function_exists( 'anesta_learnpress_tgmpa_required_plugins' ) ) {
	function anesta_learnpress_tgmpa_required_plugins($list=array() ) {
		if ( anesta_storage_isset( 'required_plugins', 'learnpress' ) && anesta_storage_get_array( 'required_plugins', 'learnpress', 'install' ) !== false ) {
			$list[] = array(
				'name' 		=> anesta_storage_get_array('required_plugins', 'learnpress', 'title'),
				'slug' 		=> 'learnpress',
				'required' 	=> false
			);
		}
		return $list;
	}
}


// Check if LearnPress installed and activated
if ( ! function_exists( 'anesta_exists_learnpress' ) ) {
	function anesta_exists_learnpress() {
		return class_exists( 'LearnPress' );
	}
}

// Return true, if current page is any learnpress page
if ( !function_exists( 'anesta_is_learnpress_page' ) ) {
	function anesta_is_learnpress_page() {
		$rez = false;
		if ( anesta_exists_learnpress() && ! is_search() ) {
			$rez = is_learnpress();
		}
		return $rez;
	}
}

// Detect current blog mode
if ( ! function_exists( 'anesta_learnpress_detect_blog_mode' ) ) {
	//Handler of the add_filter( 'anesta_filter_detect_blog_mode', 'anesta_learnpress_detect_blog_mode' );
	function anesta_learnpress_detect_blog_mode( $mode = '' ) {
		if ( anesta_is_learnpress_page() ) {
			$mode = 'learnpress';
		}
		return $mode;
	}
}


// Return taxonomy for current post type
if ( ! function_exists( 'anesta_learnpress_post_type_taxonomy' ) ) {
	//Handler of the add_filter( 'anesta_filter_post_type_taxonomy', 'anesta_learnpress_post_type_taxonomy', 10, 2 );
	function anesta_learnpress_post_type_taxonomy( $tax = '', $post_type = '' ) {
		if ( anesta_exists_learnpress() ) {
			if ( LP_COURSE_CPT == $post_type ) {
				$tax = LP_COURSE_TAXONOMY;
			}
		}
		return $tax;
	}
}

// Show categories of the current course
if ( ! function_exists( 'anesta_learnpress_get_post_categories' ) ) {
	//Handler of the add_filter( 'anesta_filter_get_post_categories', 'anesta_learnpress_get_post_categories', 10, 2 );
	function anesta_learnpress_get_post_categories( $cats = '', $args = array() ) {
		if ( get_post_type() == LP_COURSE_CPT ) {
			$cat_sep = apply_filters(
				'anesta_filter_post_meta_cat_separator',
				'<span class="post_meta_item_cat_separator">' . ( ! isset( $args['cat_sep'] ) || ! empty( $args['cat_sep'] ) ? ', ' : ' ' ) . '</span>',
				$args
			);
			$cats = anesta_get_post_terms( $cat_sep, get_the_ID(), LP_COURSE_TAXONOMY );
		}
		return $cats;
	}
}

// Enqueue styles for frontend
if ( ! function_exists( 'anesta_learnpress_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_learnpress_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_learnpress', 'anesta_learnpress_frontend_scripts', 10, 1 );
	function anesta_learnpress_frontend_scripts( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
				current_action() == 'wp_enqueue_scripts' && anesta_need_frontend_scripts( 'learnpress' )
				||
				current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$anesta_url = anesta_get_file_url( 'plugins/learnpress/learnpress.css' );
			if ( '' != $anesta_url ) {
				wp_enqueue_style( 'anesta-learnpress', $anesta_url, array(), null );
			}
		}
	}
}

// Enqueue responsive styles for frontend
if ( ! function_exists( 'anesta_learnpress_frontend_scripts_responsive' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_learnpress_frontend_scripts_responsive', 2000 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_learnpress', 'anesta_learnpress_frontend_scripts_responsive', 10, 1 );
	function anesta_learnpress_frontend_scripts_responsive( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
				current_action() == 'wp_enqueue_scripts' && anesta_need_frontend_scripts( 'learnpress' )
				||
				current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$anesta_url = anesta_get_file_url( 'plugins/learnpress/learnpress-responsive.css' );
			if ( '' != $anesta_url ) {
				wp_enqueue_style( 'anesta-learnpress-responsive', $anesta_url, array(), null, anesta_media_for_load_css_responsive( 'learnpress' ) );
			}
		}
	}
}


// Merge custom styles
if ( ! function_exists( 'anesta_learnpress_merge_styles' ) ) {
	//Handler of the add_filter('anesta_filter_merge_styles', 'anesta_learnpress_merge_styles');
	function anesta_learnpress_merge_styles( $list ) {
		$list[ 'plugins/learnpress/learnpress.css' ] = false;
		return $list;
	}
}

// Merge responsive styles
if ( ! function_exists( 'anesta_learnpress_merge_styles_responsive' ) ) {
	//Handler of the add_filter('anesta_filter_merge_styles_responsive', 'anesta_learnpress_merge_styles_responsive');
	function anesta_learnpress_merge_styles_responsive( $list ) {
		$list[ 'plugins/learnpress/learnpress-responsive.css' ] = false;
		return $list;
	}
}

// Override options with stored page meta on 'learnpress' pages
if ( ! function_exists( 'anesta_learnpress_override_theme_options' ) ) {
	//Handler of the add_action( 'anesta_action_override_theme_options', 'anesta_learnpress_override_theme_options' );
	function anesta_learnpress_override_theme_options() {
		if ( is_learnpress() ) {
			$id = learn_press_get_page_id( 'courses' );
			if ( 0 < $id ) {
				// Get Theme Options from the courses page
				$courses_meta = get_post_meta( $id, 'anesta_options', true );
				// Add (override) with current post (course) options
				if ( anesta_storage_isset( 'options_meta' ) && is_array( $courses_meta ) && count( $courses_meta ) > 0 ) {
					$options_meta = anesta_storage_get( 'options_meta' );
					if ( is_array( $options_meta ) ) {
						$courses_meta = array_merge( $courses_meta, $options_meta );
					}
				}
				anesta_storage_set( 'options_meta', $courses_meta );
			}
		}
	}
}


// Check if meta box is allowed
if ( ! function_exists( 'anesta_learnpress_allow_override_options' ) ) {
	if ( ! ANESTA_THEME_FREE ) {
		add_filter( 'anesta_filter_allow_override_options', 'anesta_learnpress_allow_override_options', 10, 2);
	}
	function anesta_learnpress_allow_override_options( $allow, $post_type ) {
		return $allow || ( anesta_exists_learnpress() && LP_COURSE_CPT == $post_type );
	}
}


// Return current page title
if ( !function_exists( 'anesta_learnpress_get_blog_title' ) ) {
	//Handler of the add_filter( 'trx_addons_filter_get_blog_title', 'anesta_learnpress_get_blog_title' );
	function anesta_learnpress_get_blog_title( $title = '' ) {
		if ( ! anesta_exists_trx_addons() && anesta_exists_learnpress() && learn_press_is_courses() ) {
			$id = learn_press_get_page_id( 'courses' );
			$title = $id ? get_the_title( $id ) : esc_html__( 'LearnPress', 'anesta' );
		}
		return $title;
	}
}


// Add 'course' to the list of the supported post-types
if ( ! function_exists( 'anesta_learnpress_list_post_types' ) ) {
	//Handler of the add_filter( 'anesta_filter_list_posts_types', 'anesta_learnpress_list_post_types');
	function anesta_learnpress_list_post_types( $list = array() ) {
		if ( anesta_exists_learnpress() ) {
			$list[ LP_COURSE_CPT ] = esc_html__( 'Courses', 'anesta' );
		}
		return $list;
	}
}


// Convert duration to string
if ( ! function_exists( 'anesta_learnpress_get_duration' ) ) {
	function anesta_learnpress_get_duration( $item ) {
		$duration = $item->get_duration();
		if ( is_a( $duration, 'LP_Duration' ) && $duration->get() ) {
			$format = array(
				'day'    => _x( '%s days', 'duration', 'anesta' ),
				'hour'   => _x( '%s hours', 'duration', 'anesta' ),
				'minute' => _x( '%s min', 'duration', 'anesta' ),
				'second' => _x( '%s sec', 'duration', 'anesta' ),
			);
			$duration = $duration->to_timer( $format, true );
		} else {
			$duration = human_time_diff( current_time( 'timestamp' ) + $duration, current_time( 'timestamp' ) );
		}
		return $duration;
	}
}



// Redirect LearnPress templates to the skin
//------------------------------------------------------------------------

// Search theme-specific template's part in the skin dir (if exists)
if ( ! function_exists( 'anesta_learnpress_locate_template' ) ) {
	//Handler of the add_filter( 'learn_press_locate_template', 'anesta_learnpress_locate_template', 10000, 3 );
	function anesta_learnpress_locate_template( $template, $template_name, $template_path='' ) {
		$theme_dir = apply_filters( 'anesta_filter_get_theme_file_dir', '', "learnpress/{$template_name}" );
		if ( '' != $theme_dir ) {
			$template = $theme_dir;
		}
		return $template;
	}
}

// Search theme-specific template's part in the skin dir (if exists)
if ( ! function_exists( 'anesta_learnpress_get_template_part' ) ) {
	//Handler of the add_filter( 'learn_press_get_template_part', 'anesta_learnpress_get_template_part', 10000, 3 );
	function anesta_learnpress_get_template_part( $template, $slug, $name ) {
		$theme_dir = '';
		if ( '' != $name ) {
			$theme_dir = apply_filters( 'anesta_filter_get_theme_file_dir', '', "learnpress/{$slug}-{$name}.php" );
		}
		if ( '' == $theme_dir ) {
			$theme_dir = apply_filters( 'anesta_filter_get_theme_file_dir', '', "learnpress/{$slug}.php" );
		}
		if ( '' != $theme_dir ) {
			$template = $theme_dir;
		}
		return $template;
	}
}

// Search theme-specific template in the skin dir (if exists)
if ( ! function_exists( 'anesta_learnpress_get_template' ) ) {
	//Handler of the add_filter( 'learn_press_get_template', 'anesta_learnpress_get_template', 10000, 5 );
	function anesta_learnpress_get_template( $located, $template_name, $args=array(), $template_path='', $default_path='' ) {
		$theme_dir = apply_filters( 'anesta_filter_get_theme_file_dir', '', "learnpress/{$template_name}" );
		if ( '' != $theme_dir ) {
			$located = $theme_dir;
		}
		return $located;
	}
}



// Add LearnPress specific items into lists
//------------------------------------------------------------------------

// Add sidebar
if ( ! function_exists( 'anesta_learnpress_list_sidebars' ) ) {
	//Handler of the add_filter( 'anesta_filter_list_sidebars', 'anesta_learnpress_list_sidebars' );
	function anesta_learnpress_list_sidebars( $list = array() ) {
		$list['learnpress_widgets'] = array(
			'name'        => esc_html__( 'LearnPress Widgets', 'anesta' ),
			'description' => esc_html__( 'Widgets to be shown on the LearnPress pages', 'anesta' ),
		);
		return $list;
	}
}



// Decorate courses
//------------------------------------------------------------------------

// Remove breadcrumbs
if ( ! function_exists( 'anesta_learnpress_remove_breadcrumb' ) ) {
	//Handler of the add_action( 'learn-press/before-main-content', 'anesta_learnpress_remove_breadcrumb', 0 );
	function anesta_learnpress_remove_breadcrumb() {
		// Old way: before v.4.0
		remove_action( 'learn-press/before-main-content', 'learn_press_breadcrumb' );
		// New way: after v.4.0
		if ( function_exists( 'LP' ) ) {
			remove_action( 'learn-press/before-main-content', array( LP()->template( 'general' ), 'breadcrumb' ) );
		}
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if ( anesta_exists_learnpress() ) {
	require_once anesta_get_file_dir( 'plugins/learnpress/learnpress-style.php' );
}


// Remove filters
if ( ! function_exists( 'anesta_gdpr_remove_action_register_form' ) ) {
	add_action( 'init', 'anesta_gdpr_remove_action_register_form', 10 );
	function anesta_gdpr_remove_action_register_form() {
		if ( anesta_exists_trx_addons() && anesta_exists_learnpress() && anesta_check_url( 'lp-profile' ) ) {
			trx_addons_remove_filter( 'register_form', 'addField', 'WPGDPRC\Integrations\WPRegistration' );
		}
	}
}