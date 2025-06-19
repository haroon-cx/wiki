<?php
/**
 * Skins support: Main skin file for the skin 'Default'
 *
 * Load scripts and styles,
 * and other operations that affect the appearance and behavior of the theme
 * when the skin is activated
 *
 * @package ANESTA
 * @since ANESTA 1.0.46
 */


// SKIN SETUP
//--------------------------------------------------------------------
// Setup fonts, colors, blog and single styles, etc.
$anesta_skin_path = anesta_get_file_dir( anesta_skins_get_current_skin_dir() . 'skin-setup.php' );
if ( ! empty( $anesta_skin_path ) ) {
	require_once $anesta_skin_path;
}

// Skin options
$anesta_skin_path = anesta_get_file_dir( anesta_skins_get_current_skin_dir() . 'skin-options.php' );
if ( ! empty( $anesta_skin_path ) ) {
	require_once $anesta_skin_path;
}

// Required plugins
$anesta_skin_path = anesta_get_file_dir( anesta_skins_get_current_skin_dir() . 'skin-plugins.php' );
if ( ! empty( $anesta_skin_path ) ) {
	require_once $anesta_skin_path;
}

// Demo import
$anesta_skin_path = anesta_get_file_dir( anesta_skins_get_current_skin_dir() . 'skin-demo-importer.php' );
if ( ! empty( $anesta_skin_path ) ) {
	require_once $anesta_skin_path;
}

// If separate single styles are supported with a current skin - return true to place its to the stand-alone files
// '__single.css' with general styles for single posts
// '__single-responsive.css' with responsive styles for single posts
if ( ! function_exists( 'anesta_skin_allow_separate_single_styles' ) ) {
	add_filter( 'anesta_filters_separate_single_styles', 'anesta_skin_allow_separate_single_styles' );
	function anesta_skin_allow_separate_single_styles( $allow ) {
		return true;
	}
}

// If separate ThemeREX Addons styles are supported with a current skin - return true to place its to the stand-alone files
// inside a skin's folder "skins/skin-slug/plugins/trx_addons/components".
// For example: "skins/default/plugins/trx_addons/components/sc-blogger.css" and "sc-blogger-responsive.css"
if ( ! function_exists( 'anesta_skin_allow_separate_trx_addons_styles' ) ) {
	add_filter( 'anesta_filters_separate_trx_addons_styles', 'anesta_skin_allow_separate_trx_addons_styles' );
	function anesta_skin_allow_separate_trx_addons_styles( $allow ) {
		return true;
	}
}

// If separate ThemeREX Addons styles are supported with a current skin - return a list of components,
// who have a separate css files inside a skin's folder "plugins/trx_addons/components".
// For example:
// 		'cpt_cars', 'cpt_courses', 'cpt_dishes', 'cpt_portfolio', 'cpt_properties', 'cpt_services', 'cpt_sport',
//		'cpt_team', 'cpt_testimonials',
//		'sc_accordionposts', 'sc_action', 'sc_anchor', 'sc_blogger', 'sc_content', 'sc_countdown', 'sc_cover',
//		'sc_googlemap', 'sc_hotspot', 'sc_icompare', 'sc_icons', 'sc_osmap', 'sc_price', 'sc_promo', 'sc_skills',
//		'sc_socials', 'sc_supertitle', 'sc_table', 'sc_users', 'sc_yandexmap',
//		'widget_aboutme', 'widget_audio', 'widget_banner', 'widget_categories_list', 'widget_contacts',
//		'widget_custom_links', 'widget_flickr', 'widget_instagram', 'widget_recent_news', 'widget_socials',
//		'widget_twitter', 'widget_video', 'widget_video_list'
if ( ! function_exists( 'anesta_skin_separate_trx_addons_styles_list' ) ) {
	add_filter( 'anesta_filters_separate_trx_addons_styles_list', 'anesta_skin_separate_trx_addons_styles_list' );
	function anesta_skin_separate_trx_addons_styles_list( $list ) {
		return array(
			'cpt_cars', 'cpt_courses', 'cpt_dishes', 'cpt_portfolio', 'cpt_properties', 'cpt_services', 'cpt_sport',
			'cpt_team', 'cpt_testimonials',
			'sc_action', 'sc_blogger', 'sc_countdown', 'sc_googlemap', 'sc_hotspot', 'sc_icons', 'sc_osmap', 'sc_price',
			'sc_promo', 'sc_skills', 'sc_switcher', 'sc_users', 'sc_yandexmap',
			'widget_categories_list', 'widget_contacts', 'widget_recent_news', 'widget_twitter',
		);
	}
}

// Filter to add in the required plugins list
// Priority 11 to add new plugins to the end of the list
if ( ! function_exists( 'anesta_skin_tgmpa_required_plugins' ) ) {
	add_filter( 'anesta_filter_tgmpa_required_plugins', 'anesta_skin_tgmpa_required_plugins', 11 );
	function anesta_skin_tgmpa_required_plugins( $list = array() ) {
		// ToDo: Check if plugin is in the 'required_plugins' and add his parameters to the TGMPA-list
		//       Replace 'skin-specific-plugin-slug' to the real slug of the plugin
		if ( anesta_storage_isset( 'required_plugins', 'bp-activity-shortcode' ) && anesta_storage_get_array( 'required_plugins', 'bp-activity-shortcode', 'install' ) !== false ) {
            $list[] = array(
                'name'     => anesta_storage_get_array( 'required_plugins', 'bp-activity-shortcode', 'title' ),
                'slug'     => 'bp-activity-shortcode',
                'required' => false,
            );
        }
        if ( anesta_storage_isset( 'required_plugins', 'buddypress-media' ) && anesta_storage_get_array( 'required_plugins', 'buddypress-media', 'install' ) !== false ) {
            $list[] = array(
                'name'     => anesta_storage_get_array( 'required_plugins', 'buddypress-media', 'title' ),
                'slug'     => 'buddypress-media',
                'required' => false,
            );
        }
		return $list;
	}
}


// TRX_ADDONS SETUP
//--------------------------------------------------------------------
// Filter to add/remove components of ThemeREX Addons when current skin is active
if ( ! function_exists( 'anesta_trx_addons_skin_default_components' ) ) {
	add_filter( 'trx_addons_filter_load_options', 'anesta_trx_addons_skin_default_components', 20 );
	function anesta_trx_addons_skin_default_components($components) {
		// ToDo: Set key value in the array $components to 0 (disable component) or 1 (enable component)
		//---> For example (enable reviews for posts):
		//---> $components['components_components_reviews'] = 1;
		return $components;
	}
}

// Filter to add/remove CPT
if ( ! function_exists( 'anesta_trx_addons_skin_cpt_list' ) ) {
	add_filter( 'trx_addons_cpt_list', 'anesta_trx_addons_skin_cpt_list' );
	function anesta_trx_addons_skin_cpt_list( $list = array() ) {
		// ToDo: Unset CPT slug from list to disable CPT when current skin is active
		//---> For example to disable CPT 'Portfolio':
		//---> unset( $list['portfolio'] );
		return $list;
	}
}

// Filter to add/remove shortcodes
if ( ! function_exists( 'anesta_trx_addons_skin_sc_list' ) ) {
	add_filter( 'trx_addons_sc_list', 'anesta_trx_addons_skin_sc_list' );
	function anesta_trx_addons_skin_sc_list( $list = array() ) {
		// ToDo: Unset shortcode's slug from list to disable shortcode when current skin is active
		//---> For example to disable shortcode 'Action':
		//---> unset( $list['action'] );

		// Also can be used to add/remove/modify shortcodes params
		//---> For example to add new template to the 'Blogger':
		//---> $list['blogger']['templates']['default']['new_template_slug'] = array(
		//--->		'title' => __('Title of the new template', 'anesta'),
		//--->		'layout' => array(
		//--->			'featured' => array(),
		//--->			'content' => array('meta_categories', 'title', 'excerpt', 'meta', 'readmore')
		//--->		)
		//---> );
		return $list;
	}
}

// Filter to add/remove widgets
if ( ! function_exists( 'anesta_trx_addons_skin_widgets_list' ) ) {
	add_filter( 'trx_addons_widgets_list', 'anesta_trx_addons_skin_widgets_list' );
	function anesta_trx_addons_skin_widgets_list( $list = array() ) {
		// ToDo: Unset widget's slug from list to disable widget when current skin is active
		//---> For example to disable widget 'About Me':
		//---> unset( $list['aboutme'] );
		return $list;
	}
}

// Scroll to top progress
if ( ! function_exists( 'anesta_trx_addons_skin_scroll_progress_type' ) ) {
	add_filter( 'trx_addons_filter_scroll_progress_type', 'anesta_trx_addons_skin_scroll_progress_type' );
	function anesta_trx_addons_skin_scroll_progress_type( $type = '' ) {
		return 'round';	// round | box | vertical | horizontal
	}
}


// WOOCOMMERCE SETUP
//--------------------------------------------------
// Allow extended layouts for WooCommerce
if ( ! function_exists( 'anesta_woocommerce_skin_allow_extensions' ) ) {
	add_filter( 'anesta_filter_load_woocommerce_extensions', 'anesta_woocommerce_skin_allow_extensions' );
	function anesta_woocommerce_skin_allow_extensions( $allow ) {
		return true;
	}
}


// SCRIPTS AND STYLES
//--------------------------------------------------
// Return a skin-specific media slug for each responsive css-file
if ( ! function_exists( 'anesta_skin_media_for_load_css_responsive' ) ) {
	add_filter( 'anesta_filter_media_for_load_css_responsive', 'anesta_skin_media_for_load_css_responsive', 10, 2 );
	function anesta_skin_media_for_load_css_responsive( $media, $slug ) {
		if ( in_array( $slug, array( 'main', 'single', 'single-styles', 'blog-styles', 'gutenberg-general', 'trx-addons', 'bbpress', 'bp-better-messages', 'buddypress-docs', 'elementor', 'sfwd-lms', 'wp-job-manager' ) ) ) {
			$media = 'xxl';
		} else if ( in_array( $slug, array( 'front-page', 'trx-addons-layouts', 'tribe-events', 'woocommerce', 'wp-job-manager-resumes' ) ) ) {
			$media = 'xl';
		} else if ( in_array( $slug, array( 'democracy-poll', 'echo-knowledge-base', 'woocommerce-extensions' ) ) ) {
			$media = 'lg';
		} else if ( in_array( $slug, array( 'theme-hovers', 'paid-memberships-pro' ) ) ) {
			$media = 'md';
		} else if ( in_array( $slug, array( 'instagram-feed' ) ) ) {
			$media = 'sm';
		} else if ( in_array( $slug, array( 'gutenberg' ) ) ) {
			$media = 'xs';
		}
		return $media;
	}
}

// Load required styles and scripts for admin mode
if ( ! function_exists( 'anesta_skin_admin_scripts' ) ) {
	add_action( 'admin_enqueue_scripts', 'anesta_skin_admin_scripts', 11 );
	function anesta_skin_admin_scripts( $all = false ) {
		$anesta_url = anesta_get_file_url( anesta_skins_get_current_skin_dir() . 'css/_skin-admin.css' );
		if ( '' != $anesta_url ) {
			wp_enqueue_style( 'anesta-skin-admin-' . esc_attr( anesta_skins_get_current_skin_name() ), $anesta_url, array(), null );
		}
	}
}

// Enqueue skin-specific styles
// Priority 1050 -  before main theme plugins-specific (1100)
if ( ! function_exists( 'anesta_skin_frontend_styles' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_skin_frontend_styles', 1050 );
	function anesta_skin_frontend_styles() {
		if ( anesta_is_on( anesta_get_theme_option( 'debug_mode' ) ) ) {
			$anesta_url = anesta_get_file_url( anesta_skins_get_current_skin_dir() . 'css/style.css' );
			if ( '' != $anesta_url ) {
				wp_enqueue_style( 'anesta-skin-' . esc_attr( anesta_skins_get_current_skin_name() ), $anesta_url, array(), null );
			}
			if ( 'blocks' == anesta_skin_page_content_type() || anesta_is_on( anesta_get_theme_option( 'content_switcher' ) ) ) {
				$anesta_url = anesta_get_file_url( anesta_skins_get_current_skin_dir() . 'css/blocks.css' );
				if ( '' != $anesta_url ) {
					wp_enqueue_style( 'anesta-skin-blocks-' . esc_attr( anesta_skins_get_current_skin_name() ), $anesta_url, array(), null );
				}
			} 
			if ( 'classic' == anesta_skin_page_content_type() || anesta_is_on( anesta_get_theme_option( 'content_switcher' ) ) ) {
				$anesta_url = anesta_get_file_url( anesta_skins_get_current_skin_dir() . 'css/classic.css' );
				if ( '' != $anesta_url ) {
					wp_enqueue_style( 'anesta-skin-classic-' . esc_attr( anesta_skins_get_current_skin_name() ), $anesta_url, array(), null );
				}
			}
		}
	}
}

// Enqueue scripts
if ( ! function_exists( 'anesta_skin_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_skin_frontend_scripts', 1100 );
	function anesta_skin_frontend_scripts() {
		if ( anesta_is_on( anesta_get_theme_option( 'debug_mode' ) ) ) {
			$anesta_url = anesta_get_file_url( anesta_skins_get_current_skin_dir() . 'skin.js' );
			if ( '' != $anesta_url ) {
				wp_enqueue_script( 'anesta-skin-' . esc_attr( anesta_skins_get_current_skin_name() ), $anesta_url, array( 'jquery' ), null, true );
			}
			$anesta_url = anesta_get_file_url( anesta_skins_get_current_skin_dir() . 'plugins/plugins.js' );
			if ( '' != $anesta_url ) {
				wp_enqueue_script( 'anesta-skin-plugins-' . esc_attr( anesta_skins_get_current_skin_name() ), $anesta_url, array( 'jquery' ), null, true );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'anesta_skin_merge_styles' ) ) {
	//Handler of the add_filter( 'anesta_filter_merge_styles', 'anesta_skin_merge_styles' );
	function anesta_skin_merge_styles( $list ) {
		$list[ 'css/style.css' ] = true;
		if ( 'blocks' == anesta_skin_page_content_type() || anesta_is_on( anesta_get_theme_option( 'content_switcher' ) ) ) {
			$list[ 'css/blocks.css' ] = true;
		}
		if ( 'classic' == anesta_skin_page_content_type() || anesta_is_on( anesta_get_theme_option( 'content_switcher' ) ) ) {
			$list[ 'css/classic.css' ] = true;		
		}
		return $list;
	}
}

// Merge custom scripts
if ( ! function_exists( 'anesta_skin_merge_scripts' ) ) {
	//Handler of the add_filter('anesta_filter_merge_scripts', 'anesta_skin_merge_scripts');
	function anesta_skin_merge_scripts( $list ) {
		$list[ 'skin.js' ] = true;
		$list[ 'plugins/plugins.js' ] = true;
		return $list;
	}
}


// Enqueue skin-specific scripts
if ( ! function_exists( 'anesta_skin_wp_styles_custom' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'anesta_skin_wp_styles_custom', 1200 );
	function anesta_skin_wp_styles_custom() {		
		$page_width = apply_filters( 'anesta_filter_content_width', anesta_get_theme_option( 'page_width' ) );
		$css = "
		:root {
			--theme-var-page_width: {$page_width}px;
		}";
   		wp_register_style( 'anesta-skin-custom', false );
	    wp_enqueue_style( 'anesta-skin-custom' );
		wp_add_inline_style( 'anesta-skin-custom', $css );	
	}
}

// Add skin-specific variables to the scripts
if ( ! function_exists( 'anesta_skin_localize_script' ) ) {
	add_filter( 'anesta_filter_localize_script', 'anesta_skin_localize_script');
	function anesta_skin_localize_script( $arr ) {
		// ToDo: Add skin-specific vars to the $arr to use its in the 'skin.js'
		// ---> For example: $arr['myvar'] = 'Value';
		// ---> In js code you can use variable 'myvar' as ANESTA_STORAGE['myvar']
		return $arr;
	}
}

// Custom styles
$anesta_style_path = anesta_get_file_dir( anesta_skins_get_current_skin_dir() . 'css/style.php' );
if ( ! empty( $anesta_style_path ) ) {
	require_once $anesta_style_path;
}

remove_action( 'anesta_filter_gutenberg_get_styles', 'anesta_skins_gutenberg_get_styles' );


// New
//--------------------------------------------------
// Theme init priorities:
//10 - standard Theme init procedures (not ordered)
if ( ! function_exists( 'anesta_skin_theme_setup' ) ) {
	add_action( 'after_setup_theme', 'anesta_skin_theme_setup' );
	function anesta_skin_theme_setup() {
		add_theme_support( 'html5', array(  'script', 'style' ) );
		
		// Add body classes
		add_filter( 'body_class', 'anesta_skin_add_body_classes' );

		// Remove filter
		remove_action( 'anesta_filter_qsetup_options', 'anesta_options_qsetup_add_accent_colors' );

		// Plugins widgets area
		if ( ! anesta_exists_tribe_events() ) {
			remove_action( 'anesta_filter_list_sidebars', 'anesta_tribe_events_list_sidebars' );
		}
		if ( ! anesta_exists_bbpress() ) {
			remove_action( 'anesta_filter_list_sidebars', 'anesta_bbpress_list_sidebars' );
		}
		if ( ! anesta_exists_woocommerce() ) {
			remove_action( 'anesta_filter_list_sidebars', 'anesta_woocommerce_list_sidebars' );
		}
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'anesta_skin_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'anesta_skin_theme_setup9', 9 );
	function anesta_skin_theme_setup9() {		  
		add_action( 'wp_enqueue_scripts', 'anesta_skin_frontend_styles', 1050 );
		add_action( 'wp_enqueue_scripts', 'anesta_skin_frontend_scripts', 1100 );    
		add_action( 'wp_enqueue_scripts', 'anesta_skin_wp_styles_custom', 1200 );

		add_filter( 'anesta_filter_merge_styles', 'anesta_skin_merge_styles' );
		add_filter( 'anesta_filter_merge_scripts', 'anesta_skin_merge_scripts' );

		if ( ! is_admin() ) {
			add_action( 'pre_get_posts', 'anesta_skin_pre_get_posts' );
		}
	}
}

// Theme specified classes
if ( ! function_exists( 'anesta_skin_add_body_classes' ) ) {
	add_filter( 'body_class', 'anesta_skin_add_body_classes' );
	function anesta_skin_add_body_classes( $classes ) {		
		$classes[] = 'page_content_' . esc_attr( anesta_skin_page_content_type() );
		$classes[] = in_array( anesta_get_theme_option( 'menu_side' ), array( 'left', 'right' ) ) && anesta_get_theme_option( 'menu_side_open' ) > 0 ? 'menu_mobile_is_opened' : ''; 
		$classes[] = anesta_is_on( anesta_get_theme_option( 'page_title' ) ) ? 'show_page_title' : 'hide_page_title'; 
		$classes[] = anesta_is_on( anesta_get_theme_option( 'debug_mode' ) ) ? 'debug_on' : 'debug_off'; 
		return $classes;
	}
}

// Page content type
if ( ! function_exists( 'anesta_skin_page_content_type' ) ) {
	function anesta_skin_page_content_type() {		
		$page_content = anesta_get_theme_option( 'page_content' );
		return apply_filters( 'anesta_skin_filter_page_content_type', $page_content );
	}
}

// Check if menu exists
if ( ! function_exists( 'anesta_skin_menu_exists' ) ) {
	function anesta_skin_menu_exists() {		
		// Mobile menu
		$anesta_menu = anesta_get_nav_menu( 'menu_mobile' );
		if ( empty( $anesta_menu ) ) {
			$anesta_menu = apply_filters( 'anesta_filter_get_mobile_menu', '' );
			if ( empty( $anesta_menu ) ) {
				$anesta_menu = anesta_get_nav_menu( 'menu_main' );
				if ( empty( $anesta_menu ) ) {
					$anesta_menu = anesta_get_nav_menu();
				}
			}
		}
		return !empty( $anesta_menu ) ? true : false;
	}
}

// Sorting form before post stream
if ( ! function_exists( 'anesta_skin_before_page_posts' ) ) {
	add_action( 'anesta_action_before_page_posts', 'anesta_skin_before_page_posts' );
	function anesta_skin_before_page_posts() {		
		global $wp_query;
		if ( $wp_query->get( 'post_type' ) == 'post' || ($wp_query->is_home() && $wp_query->is_main_query()) ) {
			?><div class="posts_header"><?php

				do_action( 'anesta_action_post_title' );
				
				$current_url = explode("?", anesta_get_current_url());
				?><div class="posts_sorting">
					<form action="<?php echo esc_url( $current_url[0] ); ?>" method="get">
						<div class="posts_search">
							<input type="text" class="search_field" placeholder="<?php esc_attr_e('Search', 'anesta'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="text">
							<button type="submit" class="search_submit trx_addons_icon-search" aria-label="Start search"></button>
						</div>
						<select name="posts_order">
							<option value="date_asc"<?php echo ( ($value = anesta_get_value_gp('posts_order')) != '' && $value == 'date_asc' ? ' selected="selected"' : '' ); ?>><?php esc_attr_e('Date Ascending', 'anesta'); ?></option>
							<option value="date_desc"<?php echo ( ($value = anesta_get_value_gp('posts_order')) != '' && $value == 'date_desc' ? ' selected="selected"' : '' ); ?>><?php esc_attr_e('Date Descending', 'anesta'); ?></option>
							<option value="title_asc"<?php echo ( ($value = anesta_get_value_gp('posts_order')) != '' && $value == 'title_asc' ? ' selected="selected"' : '' ); ?>><?php esc_attr_e('Title Ascending', 'anesta'); ?></option>
							<option value="title_desc"<?php echo ( ($value = anesta_get_value_gp('posts_order')) != '' && $value == 'title_desc' ? ' selected="selected"' : '' ); ?>><?php esc_attr_e('Title Descending', 'anesta'); ?></option>
						</select>
						<button type="submit" class="search_submit trx_addons_icon-search hide"></button>
					</form>
				</div>

				<div class="posts_navigation"><?php
					$posts_per_page = get_query_var('posts_per_page') ? get_query_var('posts_per_page') : 0;
					$paged = get_query_var('paged') ? get_query_var('paged') : 1;
					$posts_count = $wp_query->post_count ? $wp_query->post_count : 0;

					$x = ($paged - 1) * $posts_per_page + 1;
					$y = ($paged * $posts_per_page) - ($posts_per_page - $posts_count);

					?><div class="posts_viewing"><?php
						echo sprintf( esc_html__('Viewing %d-%d posts', 'anesta'), $x, $y );
					?></div><?php
					$args = array( 'pagination' => 'pages' );
					anesta_show_pagination( $args );
				?></div>
			</div><?php
		}
	}
}

// Post archive Query manipulations
if ( ! function_exists( 'anesta_skin_pre_get_posts' ) ) {
	//Handler of the add_action( 'pre_get_posts', 'anesta_edd_pre_get_posts' );
	function anesta_skin_pre_get_posts( $wp_query ) {
		if ( $wp_query->get( 'post_type' ) == 'post' || ($wp_query->is_home() && $wp_query->is_main_query()) ) {
			if ( ($value = anesta_get_value_gp('text')) != '' )	{
				$wp_query->set('s', sanitize_text_field($value) );
			}
			if ( ($value = anesta_get_value_gp('posts_order')) != '' )	{
				$x = explode( "_", sanitize_text_field($value) );
				$wp_query->set('orderby', $x[0]);	
				$wp_query->set('order', $x[1]);
			}
		}
	}
}

// Page title
if ( ! function_exists( 'anesta_skin_post_title' ) ) {
	add_action( 'anesta_action_post_title', 'anesta_skin_post_title' );
	function anesta_skin_post_title() { 
		if ( anesta_is_on( anesta_get_theme_option( 'page_title' ) ) ) {
			if ( apply_filters( 'anesta_skin_filter_page_title', !is_single() && anesta_need_page_title() ) ) {
				anesta_sc_layouts_showed( 'title', true );
				?>
				<div class="post_content_title sc_layouts_title_title">
					<?php
					$anesta_blog_title           = anesta_get_blog_title();
					$anesta_blog_title_text      = '';
					$anesta_blog_title_subtext   = anesta_get_theme_option( 'page_subtitle' );
					$anesta_blog_title_class     = '';
					if ( is_array( $anesta_blog_title ) ) {
						$anesta_blog_title_text      = $anesta_blog_title['text'];
						$anesta_blog_title_class     = ! empty( $anesta_blog_title['class'] ) ? ' ' . $anesta_blog_title['class'] : '';
					} else {
						$anesta_blog_title_text = $anesta_blog_title;
					}
					?>
					<h1 itemprop="headline" class="sc_layouts_title_caption<?php echo esc_attr( $anesta_blog_title_class ); ?>">
						<?php
						$anesta_top_icon = anesta_get_term_image_small();
						if ( ! empty( $anesta_top_icon ) ) {
							$anesta_attr = anesta_getimagesize( $anesta_top_icon );
							?>
							<img src="<?php echo esc_url( $anesta_top_icon ); ?>" alt="<?php esc_attr_e( 'Site icon', 'anesta' ); ?>"
								<?php
								if ( ! empty( $anesta_attr[3] ) ) {
									anesta_show_layout( $anesta_attr[3] );
								}
								?>
							>
							<?php
						}
						echo wp_kses_data( $anesta_blog_title_text );
						?>
					</h1>
					<?php
					if ( !empty( $anesta_blog_title_subtext ) ) {
						echo '<div class="sc_layouts_subtitle_caption">' . esc_html($anesta_blog_title_subtext) . '</div>';
					}
					?>
				</div>
				<?php
			}
		}
	}
}

// Load styles for single posts
if ( ! function_exists( 'anesta_skin_load_single_styles' ) ) {
	add_filter( 'anesta_filters_load_single_styles', 'anesta_skin_load_single_styles' );
	function anesta_skin_load_single_styles( $allow ) {
		$allow = !$allow && is_page() && ( have_comments() || comments_open() ) ? true : $allow;
		return $allow;
	}
}

// Single post meta parts
if ( ! function_exists( 'anesta_skin_post_meta_args' ) ) {
	add_filter( 'anesta_filter_post_meta_args', 'anesta_skin_post_meta_args', 10, 3 );
	function anesta_skin_post_meta_args( $array, $type, $x ) {		
		if ( 'single' == $type && 'post_meta_other' == $array['class'] ) {
			$array['components'] .= ',likes';
		}
		
		if ( 'page' == get_post_type() || 'product' == get_post_type() ) { 
			$array['components'] = explode( ',', $array['components'] );
			$array['components'] = anesta_array_delete_by_value( $array['components'], 'comments' );
			$array['components'] = anesta_array_delete_by_value( $array['components'], 'likes' );
			$array['components'] = join( ',', $array['components'] );
		}
		return $array;
	}
}

// Comments button class
if ( ! function_exists( 'anesta_skin_comments_button_class' ) ) {
	add_filter( 'anesta_filter_comments_button_class', 'anesta_skin_comments_button_class' );
	function anesta_skin_comments_button_class( $class ) {		
		$class .= ' sc_button sc_button_default sc_button_size_large';
		return $class;
	}
}

// Open wrap before related wrap title 
if ( ! function_exists( 'anesta_skin_before_related_wrap_title' ) ) {
	add_action( 'anesta_action_before_related_wrap_title', 'anesta_skin_before_related_wrap_title' );
	function anesta_skin_before_related_wrap_title() {	
		?><div class="related_wrap_header"><?php
	}
}

// Close wrap tag after related wrap title  
if ( ! function_exists( 'anesta_skin_after_related_wrap_title' ) ) {
	add_action( 'anesta_action_after_related_wrap_title', 'anesta_skin_after_related_wrap_title' );
	function anesta_skin_after_related_wrap_title() {	
		$post_id = get_the_ID();
		$post_type = get_post_type( $post_id );
		$archive_link = apply_filters( 'anesta_filter_post_type_archive_link', get_post_type_archive_link( $post_type ), $post_type ); 
			?><div class="related_wrap_button sc_button_wrap">
				<a href="<?php echo esc_url($archive_link); ?>" class="sc_button sc_button_simple">
					<span class="sc_button_text">
						<span class="sc_button_title"><?php echo esc_html__('View All', 'anesta'); ?></span>
					</span>
				</a>
			</div>		
		</div><?php
	}
}

// Button color styles
if ( ! function_exists( 'anesta_skin_get_list_sc_color_styles' ) ) {
	add_filter( 'anesta_filter_get_list_sc_color_styles', 'anesta_skin_get_list_sc_color_styles' );
	function anesta_skin_get_list_sc_color_styles( $array ) {		
		$array = array(
			'default' => esc_html__( 'Default', 'anesta' ),
			'link2'   => esc_html__( 'Accent 2', 'anesta' ),
			'link3'   => esc_html__( 'Accent 3', 'anesta' ),
			'link4'   => esc_html__( 'Accent 4', 'anesta' ),
			'link5'   => esc_html__( 'Accent 5', 'anesta' ),
			'dark'    => esc_html__( 'Dark', 'anesta' ),
			'light'   => esc_html__( 'Light', 'anesta' ),
		);

		return $array;
	}
}

// Load More button class
if ( ! function_exists( 'anesta_skin_load_more_class' ) ) {
	add_filter( 'anesta_filter_load_more_class', 'anesta_skin_load_more_class' );
	function anesta_skin_load_more_class( $class ) {		
		$class .= " sc_button sc_button_default sc_button_size_large";
		return $class;
	}
}

// Image's hovers
if ( ! function_exists( 'anesta_skin_list_hovers' ) ) {
	add_filter( 'anesta_filter_list_hovers', 'anesta_skin_list_hovers' );
	function anesta_skin_list_hovers( $array ) {		
		unset($array['icon']);
		unset($array['icons']);
		unset($array['zoom']);
		$array['none'] = esc_html__( 'None', 'anesta' );
		return $array;
	}
}

// Settings for Front Page Builder
if ( ! function_exists( 'anesta_skin_front_page_options' ) ) {
	add_filter( 'anesta_filter_front_page_options', 'anesta_skin_front_page_options', 11 );
	function anesta_skin_front_page_options( $array ) {		
		return array();
	}
}

// Theme specific widgetized areas
if ( ! function_exists( 'anesta_skin_list_sidebars' ) ) {
	add_filter( 'anesta_filter_list_sidebars', 'anesta_skin_list_sidebars', 11, 1 );
	function anesta_skin_list_sidebars( $list ) {		
		unset($list['header_widgets']);
		unset($list['above_page_widgets']);
		unset($list['above_content_widgets']);
		unset($list['below_content_widgets']);
		unset($list['below_page_widgets']);
		unset($list['front_page_features_widgets']);
		unset($list['front_page_team_widgets']);
		unset($list['front_page_testimonials_widgets']);
		unset($list['front_page_blog_widgets']);
		unset($list['front_page_googlemap_widgets']);
		return $list;
	}
}

// Add page content switcher to the body
if ( ! function_exists('anesta_skin_add_page_content_switcher') ) {
	add_action('wp_footer', 'anesta_skin_add_page_content_switcher', 9);
	function anesta_skin_add_page_content_switcher() {
		if ( is_customize_preview() || anesta_elementor_is_preview() ) {
			return;
		}
		if ( anesta_is_on( anesta_get_theme_option( 'content_switcher' ) ) ) {
			anesta_show_layout('<div id="page_content_switcher" class="icon-settings-1"></div>');
		}
	}
}

// Add color scheme switcher to the body
if ( ! function_exists('anesta_skin_add_color_scheme_switcher') ) {
	add_action('wp_footer', 'anesta_skin_add_color_scheme_switcher', 9);
	function anesta_skin_add_color_scheme_switcher() {
		if ( is_customize_preview() || anesta_elementor_is_preview() ) {
			return;
		}
		if ( anesta_is_on( anesta_get_theme_option( 'scheme_switcher' ) ) ) {
			$list = anesta_get_list_schemes();
			if ( is_array($list) ) {
				$invert = anesta_is_on( anesta_get_theme_option( 'invert_logo' ) ) ? 'invert' : '';
				$output = '<ul id="color_scheme_switcher" class="icon-pipette ' . esc_attr($invert) . '">';
				foreach ($list as $key => $value) {					
					$output .= '<li class="scheme_' . esc_attr($key) . '" data-value="' . esc_attr($key) . '"><span>' . esc_html($value) . '</span></li>';
				}
				$output .= '</ul>';
			}
			anesta_show_layout($output);
		}
	}
}

// Login page
if ( ! function_exists( 'anesta_skin_login_page' ) ) {
	add_action( 'get_header', 'anesta_skin_login_page' );
	function anesta_skin_login_page() {	
		if (  anesta_is_on( anesta_get_theme_option( 'enable_login_privacy' ) ) && !is_user_logged_in() ) {
			$is_login = apply_filters( 'anesta_filter_is_login_page', in_array( $GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php') ) );
			if ( !$is_login ) {
				wp_redirect( wp_login_url() );	
			} 			
		}
	}
}

// Styling Login
if ( ! function_exists( 'anesta_skin_login_page_styles' ) ) {
	add_action( 'login_enqueue_scripts', 'anesta_skin_login_page_styles' );
	add_action( 'login_enqueue_scripts', 'anesta_load_theme_fonts', 0 );
	add_action( 'login_enqueue_scripts', 'anesta_load_theme_icons', 0 );
	function anesta_skin_login_page_styles() {
		if ( anesta_is_on( anesta_get_theme_option( 'login_privacy_styles' ) ) ) {
			$load_fonts = anesta_storage_get( 'load_fonts' );
		    $font = anesta_get_load_fonts_family_string( $load_fonts[0] );

			$css = 
			'body.login {
				font-family: ' . $font . ';
			}';

			/* Bg image */
			$bg_image = anesta_get_theme_option( 'login_privacy_bg' );
			if ( !empty($bg_image) ) {
				$css .= 
				'body.login {
					background-image: url(' . $bg_image . ');
				}';
			}

			?><style type="text/css"><?php anesta_show_layout($css); ?></style><?php

		    wp_enqueue_style( 'anesta-login', anesta_get_file_url( anesta_skins_get_current_skin_dir() . 'theme-specific/theme-login/theme-login.css' ) );		
			wp_enqueue_script( 'anesta-login', anesta_get_file_url( anesta_skins_get_current_skin_dir() . 'theme-specific/theme-login/theme-login.js' ), array( 'jquery' ), null, true );	    
		}
	}
}

// Login form message
if ( ! function_exists( 'anesta_skin_login_page_message' ) ) {
	add_action( 'login_form', 'anesta_skin_login_page_message' );
	function anesta_skin_login_page_message() {
		$message = anesta_get_theme_option( 'login_privacy_message' );
		if ( anesta_is_on( anesta_get_theme_option( 'login_privacy_styles' ) ) && !empty($message) ) {
			echo '<div class="loginmmessage">' . trim($message) . '</div>';
		}
	}
}

// Login page logo
if ( ! function_exists( 'anesta_skin_login_page_logo' ) ) {
	add_action( 'login_header', 'anesta_skin_login_page_logo' );
	function anesta_skin_login_page_logo() {
		if ( anesta_is_on( anesta_get_theme_option( 'login_privacy_styles' ) ) ) {
			$logo = anesta_get_theme_option( 'login_privacy_logo' );

			?><div class="loginlogo"><?php

			if ( !empty($logo) ) {
				echo '<img src="' . esc_url($logo) . '" alt="">';
			} else {
				get_template_part( apply_filters( 'anesta_filter_get_template_part', 'templates/header-logo' ) );
			} 

			?></div><?php
		}
	}
}

// Add accent colors to the 'Quick Setup' section in the Theme Panel
if ( ! function_exists( 'anesta_skin_options_qsetup_add_accent_colors' ) ) {
	add_filter( 'anesta_filter_qsetup_options', 'anesta_skin_options_qsetup_add_accent_colors' );
	function anesta_skin_options_qsetup_add_accent_colors( $options ) {
		return anesta_array_merge(
			array(
				'colors_info'        => array(
					'title'    => esc_html__( 'Theme Colors', 'anesta' ),
					'desc'     => '',
					'qsetup'   => esc_html__( 'General', 'anesta' ),
					'type'     => 'info',
				),
				'colors_accent_link'   => array(
					'title'    => esc_html__( 'Accent color 1', 'anesta' ),
					'desc'     => wp_kses_data( __( "Color of the links", 'anesta' ) ),
					'std'      => '',
					'val'      => anesta_get_scheme_color( 'accent_link' ),
					'qsetup'   => esc_html__( 'General', 'anesta' ),
					'type'     => 'color',
				),
				'colors_accent_link2'  => array(
					'title'    => esc_html__( 'Accent color 2', 'anesta' ),
					'desc'     => wp_kses_data( __( "Color of the hovered state of the links", 'anesta' ) ),
					'std'      => '',
					'val'      => anesta_get_scheme_color( 'accent_link2' ),
					'qsetup'   => esc_html__( 'General', 'anesta' ),
					'type'     => 'color',
				),
				'colors_accent_link3'  => array(
					'title'    => esc_html__( 'Accent color 3', 'anesta' ),
					'desc'     => wp_kses_data( __( "Color of the accented areas", 'anesta' ) ),
					'std'      => '',
					'val'      => anesta_get_scheme_color( 'accent_link3' ),
					'qsetup'   => esc_html__( 'General', 'anesta' ),
					'type'     => 'color',
				),
				'colors_accent_link4' => array(
					'title'    => esc_html__( 'Accent color 4', 'anesta' ),
					'desc'     => wp_kses_data( __( "Color of the hovered state of the accented areas", 'anesta' ) ),
					'std'      => '',
					'val'      => anesta_get_scheme_color( 'accent_link4' ),
					'qsetup'   => esc_html__( 'General', 'anesta' ),
					'type'     => 'color',
				),
				'colors_accent_link5'  => array(
					'title'    => esc_html__( 'Accent color 5', 'anesta' ),
					'desc'     => wp_kses_data( __( "Color of the another accented areas", 'anesta' ) ),
					'std'      => '',
					'val'      => anesta_get_scheme_color( 'accent_link5' ),
					'qsetup'   => esc_html__( 'General', 'anesta' ),
					'type'     => 'color',
				),
			),
			$options
		);
	}
}

// Theme-specific variables
if ( ! function_exists( 'anesta_skin_add_theme_vars' ) ) {
	add_filter( 'anesta_filter_add_theme_vars', 'anesta_skin_add_theme_vars', 10, 2 );
	function anesta_skin_add_theme_vars( $rez, $vars ) {
		// Add border radius
		if ( isset( $rez['rad'] ) ) {
			$rez['rad1'] = $rez['rad'];
		}
		return $rez;
	}
}