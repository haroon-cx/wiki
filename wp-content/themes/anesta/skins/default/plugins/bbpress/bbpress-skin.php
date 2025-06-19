<?php
/* BBPress and BuddyPress support functions
------------------------------------------------------------------------------- */


// Check if BuddyPress
if ( ! function_exists( 'anesta_skin_exists_buddypress' ) ) {
	function anesta_skin_exists_buddypress() {
		return class_exists( 'BuddyPress' );
	}
}

// Check if bbPress
if ( ! function_exists( 'anesta_skin_exists_bbpress' ) ) {
	function anesta_skin_exists_bbpress() {
		return class_exists( 'bbPress' );
	}
}

// Check if rtMedia
if ( ! function_exists( 'anesta_exists_buddypress_media' ) ) {
	function anesta_exists_buddypress_media() {
		return defined( 'RTMEDIA_VERSION' );
	}
}

// Plugin init
if ( ! function_exists( 'anesta_bbpress_skin_init' ) ) {
	add_action( 'init', 'anesta_bbpress_skin_init', 9 );
	function anesta_bbpress_skin_init() {
		if ( anesta_skin_exists_buddypress() ) {
			if ( anesta_is_on( anesta_get_theme_option( 'enable_login_privacy' ) ) ) {
				remove_action( 'register_url', 'bp_get_signup_page' );
				remove_action( 'bp_init', 'bp_core_wpsignup_redirect' );
			}
		}
	}
}

// Theme init priorities:
// 2 - create Theme Options
if ( ! function_exists( 'anesta_bbpress_skin_theme_setup2' ) ) {
	add_action( 'after_setup_theme', 'anesta_bbpress_skin_theme_setup2', 2 );
	function anesta_bbpress_skin_theme_setup2() {
		if ( anesta_exists_bbpress() ) {
			remove_action( 'after_setup_theme', 'anesta_bbpress_theme_setup3', 3 );
			remove_action( 'anesta_filter_detect_blog_mode', 'anesta_bbpress_detect_blog_mode' );
		}
	}
}

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'anesta_bbpress_skin_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'anesta_bbpress_skin_theme_setup3', 3 );
	function anesta_bbpress_skin_theme_setup3() {
		if ( anesta_skin_exists_buddypress() ) {
			// Section 'BuddyPress'
			anesta_storage_merge_array(
				'options', '', array_merge(
					array(
						'buddypress'     => array(
							'title' => esc_html__( 'BuddyPress', 'anesta' ),
							'desc'  => wp_kses_data( __( 'Select parameters to display the BuddyPress pages', 'anesta' ) ),
							'icon'  => 'icon-bb-buddy-press',
							'type'  => 'section',
						)
					),
					anesta_options_get_list_cpt_options( 'buddypress', esc_html__( 'BuddyPress', 'anesta' ) )
				)
			);
		}

		if ( anesta_skin_exists_bbpress() ) {
			// Section 'bbPress'
			anesta_storage_merge_array(
				'options', '', array_merge(
					array(
						'bbpress'     => array(
							'title' => esc_html__( 'bbPress', 'anesta' ),
							'desc'  => wp_kses_data( __( 'Select parameters to display the BBPress pages', 'anesta' ) ),
							'icon'  => 'icon-speech-bubble',
							'type'  => 'section',
						),
						'forum_style' => array(
							'title'   => esc_html__( 'Forum style', 'anesta' ),
							'desc'    => wp_kses_data( __( 'Select style to display forums list on the community pages', 'anesta' ) ),
							'std'     => 'default',
							'options' => array(
								'default'  => esc_html__( 'Default', 'anesta' ),
								'light'    => esc_html__( 'Light', 'anesta' ),
								'callouts' => esc_html__( 'Callouts', 'anesta' ),
							),
							'type'    => 'hidden',
						),
					),
					anesta_options_get_list_cpt_options( 'bbpress', esc_html__( 'bbPress', 'anesta' ), 'list' )
				)
			);
		}
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'anesta_bbpress_skin_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'anesta_bbpress_skin_theme_setup9', 9 );
	function anesta_bbpress_skin_theme_setup9() {
		if ( anesta_exists_bbpress() ) {		
			add_action( 'anesta_filter_detect_blog_mode', 'anesta_bbpress_skin_detect_blog_mode' );
			add_action( 'anesta_filter_detect_blog_mode', 'anesta_buddypress_skin_detect_blog_mode' );
		}
	}
}

// Return true, if current page is any buddypress page
if ( ! function_exists( 'anesta_skin_is_buddypress_page' ) ) {
	function anesta_skin_is_buddypress_page() {
		$rez = false;
		if ( anesta_skin_exists_buddypress() ) {
			if ( ! is_search() ) {
				$rez = ( function_exists( 'is_buddypress' ) && is_buddypress() );
			}
		}
		return $rez;
	}
}

// Return true, if current page is any bbpress page
if ( ! function_exists( 'anesta_skin_is_bbpress_page' ) ) {
	function anesta_skin_is_bbpress_page() {
		$rez = false;
		if ( anesta_skin_exists_bbpress() ) {
			if ( ! is_search() ) {
				$rez = ( function_exists( 'is_bbpress' ) && is_bbpress() )
					|| ( ! is_user_logged_in() && in_array( get_query_var( 'post_type' ), array( 'forum', 'topic', 'reply' ) ) );
			}
		}
		return $rez;
	}
}

// Detect current buddypress blog mode 
if ( ! function_exists( 'anesta_buddypress_skin_detect_blog_mode' ) ) {
	//Handler of the add_filter( 'anesta_filter_detect_blog_mode', 'anesta_buddypress_skin_detect_blog_mode' );
	function anesta_buddypress_skin_detect_blog_mode( $mode = '' ) {
		if ( anesta_skin_is_buddypress_page() ) {
			$mode = 'buddypress';
		}
		return $mode;
	}
}


// Detect current bbpress blog mode
if ( ! function_exists( 'anesta_bbpress_skin_detect_blog_mode' ) ) {
	//Handler of the add_filter( 'anesta_filter_detect_blog_mode', 'anesta_bbpress_skin_detect_blog_mode' );
	function anesta_bbpress_skin_detect_blog_mode( $mode = '' ) {
		if ( anesta_skin_is_bbpress_page() ) {
			$mode = 'bbpress';
		}
		return $mode;
	}
}

// Page title
if ( ! function_exists( 'anesta_bbpress_page_title' ) ) {
	add_filter( 'anesta_skin_filter_page_title', 'anesta_bbpress_page_title' );
	function anesta_bbpress_page_title( $allow ) {	
		if ( anesta_skin_exists_buddypress() ) {
			return bp_is_user() || bp_is_group() ? false : $allow;
		}
		return $allow;
	}
}

// Change the width and the height of the Cover Image
if ( ! function_exists( 'anesta_bbpress_cover_image_size' ) ) {
	add_filter( 'bp_before_members_cover_image_settings_parse_args', 'anesta_bbpress_cover_image_size', 10, 1 );
	function anesta_bbpress_cover_image_size( $settings = array() ) {	
		$settings['width']  = 1690;
	    $settings['height'] = 464;	 
	    return $settings;
	}
}

// Set author image size
if ( ! function_exists( 'anesta_bbpress_get_topic_author_avatar' ) ) {
	add_filter( 'bbp_get_topic_author_avatar', 'anesta_bbpress_get_topic_author_avatar', 10, 3 );
	function anesta_bbpress_get_topic_author_avatar( $author_avatar, $topic_id, $size ) {	
		if ( ! empty( $topic_id ) ) {
			$size = 75;	
			if ( ! bbp_is_topic_anonymous( $topic_id ) ) {
				$author_avatar = get_avatar( bbp_get_topic_author_id( $topic_id ), $size );
			} else {
				$author_avatar = get_avatar( get_post_meta( $topic_id, '_bbp_anonymous_email', true ), $size );
			}
		}
		return $author_avatar;
	}
}

// Widget title
if ( ! function_exists( 'anesta_bbpress_widget_title' ) ) {
	add_filter( 'widget_title', 'anesta_bbpress_widget_title', 10, 3 );
	function anesta_bbpress_widget_title( $title, $instance='', $id_base='' ) {	 
		if ( anesta_skin_exists_buddypress() ) {
			// View All members
			if ( 'bp_core_recently_active_widget' == $id_base || 'bp_core_members_widget' == $id_base ) {
				// Get the existing WP pages.
				$existing_pages = bp_core_get_directory_page_ids();
				if ( ! empty( $existing_pages['members'] ) && get_post( $existing_pages['members'] ) ) { 
					$title .= 	'<div class="sc_button_wrap">
									<a href="' . esc_url( get_permalink( $existing_pages['members'] ) ) . '" class="sc_button sc_button_simple sc_button_size_normal sc_button_icon_left">
										<span class="sc_button_text">
											<span class="sc_button_title">' . esc_html__( 'View All', 'anesta' ) . '</span>
										</span>
									</a>
								</div>';
				}
			}

			// View All groups
			if ( 'bp_groups_widget' == $id_base ) {
				// Get the existing WP pages.
				$existing_pages = bp_core_get_directory_page_ids();
				if ( ! empty( $existing_pages['groups'] ) && get_post( $existing_pages['groups'] ) ) { 
					$title .= 	'<div class="sc_button_wrap">
									<a href="' . esc_url( get_permalink( $existing_pages['groups'] ) ) . '" class="sc_button sc_button_simple sc_button_size_normal sc_button_icon_left">
										<span class="sc_button_text">
											<span class="sc_button_title">' . esc_html__( 'View All', 'anesta' ) . '</span>
										</span>
									</a>
								</div>';
				}
			}

			// Members count
			if ( 'bp_core_members_widget' == $id_base  ) {
				$title .= '<span class="members_count">' . esc_html(bp_get_total_site_member_count()) . '</span>';
			}
		}
		return $title;
	}
} 

// Display user name
if ( ! function_exists( 'anesta_bbpress_before_member_header_meta' ) ) {
	add_action( 'bp_before_member_header_meta', 'anesta_bbpress_before_member_header_meta' );
	function anesta_bbpress_before_member_header_meta() {	
		echo '<h2 class="user-publicname">' . esc_html(bp_core_get_user_displayname(bp_displayed_user_id())) . '</h2>';
	}
}

/*// Get the row class of the current group in the loop
if ( ! function_exists( 'anesta_bbpress_get_group_class' ) ) {
	add_filter( 'bp_get_group_class', 'anesta_bbpress_get_group_class' );
	function anesta_bbpress_get_group_class( $classes ) {	
		$url = bp_get_group_cover_url('');
		if ( !empty($url) ) {
			$classes[] = anesta_add_inline_css_class('background-image: url(' . esc_url($url) . ')');
		}
		return $classes;
	}
}*/

// Get the cover image of the current group in the loop
if ( ! function_exists( 'anesta_bp_directory_groups_item' ) ) {
	add_action( 'bp_directory_groups_item', 'anesta_bp_directory_groups_item' );
	function anesta_bp_directory_groups_item() {	
		$url = bp_get_group_cover_url('');
		if ( !empty($url) ) {
			echo '<div class="item-cover lazyload_inited" style="background-image: url(' . esc_url($url) . ')"></div>';
		}
	}
}

// Get the row class of the current group in the loop
if ( ! function_exists( 'anesta_bbpress_group_invites_items' ) ) {
	add_action( 'bp_group_invites_item', 'anesta_bbpress_group_invites_items' );
	function anesta_bbpress_group_invites_items() {	
		$url = bp_get_group_cover_url('');
		if ( !empty($url) ) {
			echo '<div class="item_bg ' . anesta_add_inline_css_class('background-image: url(' . esc_url($url) . ')') . '"></div>';
		}
	}
}

// Get a group's avatar
if ( ! function_exists( 'anesta_bbpress_get_group_avatar' ) ) {
	add_filter( 'bp_get_group_avatar', 'anesta_bbpress_get_group_avatar', 10, 2 );
	function anesta_bbpress_get_group_avatar( $avatar, $r ) {	
		global $groups_template;

		$avatar = bp_core_fetch_avatar( array(
			'item_id'    => is_null($groups_template) ? '' : $groups_template->group->id,
			'avatar_dir' => 'group-avatars',
			'object'     => 'group',
			'type'       => 'full',
			'alt'        => $r['alt'],
			'css_id'     => $r['id'],
			'class'      => $r['class'],
		) );

		return $avatar;
	}
}

// Get a member's avatar
if ( ! function_exists( 'anesta_bbpress_get_member_avatar' ) ) {
	add_filter( 'bp_get_member_avatar', 'anesta_bbpress_get_member_avatar', 10, 2 );
	add_filter( 'bp_get_group_member_avatar_thumb', 'anesta_bbpress_get_member_avatar', 10, 2 );
	function anesta_bbpress_get_member_avatar( $avatar, $r ) {			
		global $members_template;
		if ( $members_template ) {
			if ( bp_is_members_component() || bp_is_groups_component() ) {
				$avatar = bp_core_fetch_avatar( array(
					'item_id' => $members_template->member->id,
					'type'       => 'full'
				) );
			} else {
				$avatar = bp_core_fetch_avatar( array(
					'item_id' => $members_template->member->id,
					'width' => 75, 
					'height' => 75
				) );
			}
		}
		return $avatar;
	}
}

// Add "New message" button to the member card
if ( ! function_exists( 'anesta_bbpress_get_message_button' ) ) {
	add_action( 'bp_directory_members_actions', 'anesta_bbpress_get_message_button' );
	function anesta_bbpress_get_message_button() {	 
		$potential_friend_id = bp_get_member_user_id();
		if ( empty( $potential_friend_id ) ) {
			$potential_friend_id = bp_get_potential_friend_id( $potential_friend_id );
		}
		if ( function_exists('bp_is_friend') ) { 
			$is_friend = bp_is_friend( $potential_friend_id );
			if ( empty( $is_friend ) ) {
				return false;
			}
		}

		if ( bp_is_active('messages') ) {
			bp_send_message_button();
		}
	}
}

// Display group name
if ( ! function_exists( 'anesta_bbpress_get_group_name' ) ) {
	add_action( 'bp_after_group_menu_admins', 'anesta_bbpress_get_group_name' );
	function anesta_bbpress_get_group_name() {	 
		echo '<h2 class="user-publicname">' . esc_html( bp_get_current_group_name() ) . '</h2>';
	}
}

// Output the Add Friend button
if ( ! function_exists( 'anesta_bbpress_get_add_friend_button' ) ) {
	add_filter( 'bp_get_add_friend_button', 'anesta_bbpress_get_add_friend_button' );
	function anesta_bbpress_get_add_friend_button( $button ) {	
		if ( $button['id'] == 'pending' ) {
			$button['link_text'] = __( 'Cancel Request', 'anesta' );
		}
		return $button;
	}
}

// Disable lazyload
if ( ! function_exists( 'anesta_bbpress_allow_media_lazy_load' ) ) {
	add_filter( 'trx_addons_filter_allow_media_lazy_load', 'anesta_bbpress_allow_media_lazy_load', 10, 2 );
	function anesta_bbpress_allow_media_lazy_load( $allow, $content ) {	
		if ( preg_match( '/(rtmedia-list-item media-type-music)/', $content ) || preg_match( '/(rtmedia-list-item media-type-video)/', $content ) ) {
			return false;
		}
		return $allow;
	}
}

// Disable a new Widgets block editor
if (!function_exists('anesta_buddypress_widgets_disable_block_editor')) {
	add_action( 'after_setup_theme', 'anesta_buddypress_widgets_disable_block_editor' );
	function anesta_buddypress_widgets_disable_block_editor() {
		if ( (int) anesta_get_theme_option( 'disable_widgets_block_editor' ) == 0 ) {
			global $pagenow;

		    if ( $pagenow !== 'widgets.php' ) {
		        remove_theme_support( 'widgets-block-editor' );
		    }
		}
	}
}


// One-click import support
//------------------------------------------------------------------------

// Clear tables
if ( !function_exists( 'anesta_bbpress_importer_clear_tables' ) ) {
	add_action( 'trx_addons_action_importer_clear_tables',	'anesta_bbpress_importer_clear_tables', 10, 2 );
	function anesta_bbpress_importer_clear_tables($importer, $clear_tables) {
		if ( anesta_exists_bbpress() && in_array('bbpress', $importer->options['required_plugins']) ) {
			if (strpos($clear_tables, 'bbpress')!==false) {
				if ($importer->options['debug']) dfl(__('Clear BBPress and BuddyPress tables', 'anesta'));
				// Check if BuddyPress and BBPress tables are exists and recreate it (if need)
				 anesta_bbpress_recreate_tables();
			}
		}
	}
}

// Check if BuddyPress and BBPress tables are exists and recreate it (if need)
if ( !function_exists( 'anesta_bbpress_recreate_tables' ) ) {
	function anesta_bbpress_recreate_tables() {
		global $wpdb;
		$messages = count($wpdb->get_results( $wpdb->prepare("SHOW TABLES LIKE %s", $wpdb->prefix."bp_messages_notices"), ARRAY_A )) == 1;
		if ($messages==0) {
			if (function_exists('buddypress')) {
				$bp = buddypress();
			}
			if (!function_exists('dbDelta')) {
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			}
			if (file_exists($bp->plugin_dir . '/bp-core/admin/bp-core-admin-schema.php')) {
				require_once $bp->plugin_dir . '/bp-core/admin/bp-core-admin-schema.php';
				if ($messages==0 && function_exists('bp_core_install_private_messaging'))	bp_core_install_private_messaging();
				if (function_exists('bp_core_maybe_install_signups'))				bp_core_maybe_install_signups();
			}
		}
	}
}

// Check plugin in the required plugins
if ( !function_exists( 'anesta_buddypress_media_required_plugins' ) ) {
    if (is_admin()) add_filter( 'trx_addons_filter_importer_required_plugins',	'anesta_buddypress_media_required_plugins', 10, 2 );
    function anesta_buddypress_media_required_plugins($not_installed='', $list='') {
        if (strpos($list, 'buddypress-media')!==false && !anesta_exists_buddypress_media() )
            $not_installed .= '<br>' . esc_html__('rtMedia for BuddyPress and bbPress', 'anesta');
        return $not_installed;
    }
}

// Set plugin's specific importer options
if ( !function_exists( 'anesta_buddypress_media_importer_set_options' ) ) {
	add_filter( 'trx_addons_filter_importer_options',	'anesta_buddypress_media_importer_set_options' );
	function anesta_buddypress_media_importer_set_options($options=array()) {
		if ( anesta_exists_buddypress_media() && in_array('buddypress-media', $options['required_plugins']) ) {
			$options['additional_options'][]	= 'rtmedia_%';

			if (is_array($options['files']) && count($options['files']) > 0) {
				foreach ($options['files'] as $k => $v) {
					$options['files'][$k]['file_with_buddypress-media'] = str_replace('name.ext', 'buddypress-media.txt', $v['file_with_']);
				}
			}
		}
		return $options;
	}
}

// Prevent import plugin's specific options if plugin is not installed
if ( !function_exists( 'anesta_buddypress_media_check_options' ) ) {
	add_filter( 'trx_addons_filter_import_theme_options', 'anesta_buddypress_media_check_options', 10, 4 );
	function anesta_buddypress_media_check_options($allow, $k, $v, $options) {
		if ($allow && (strpos($k, 'rtmedia_')===0) ) {
			$allow = anesta_exists_buddypress_media() && in_array('buddypress-media', $options['required_plugins']);
		}
		return $allow;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'anesta_buddypress_media_show_params' ) ) {
	add_action( 'trx_addons_action_importer_params',	'anesta_buddypress_media_show_params', 10, 1 );
	function anesta_buddypress_media_show_params($importer) {
		if ( anesta_exists_buddypress_media() && in_array('buddypress-media', $importer->options['required_plugins']) ) {
			$importer->show_importer_params(array(
				'slug' => 'buddypress-media',
				'title' => esc_html__('Import rtMedia for BuddyPress and bbPress', 'anesta'),
				'part' => 0
			));
		}
	}
}

// Import posts
if ( !function_exists( 'anesta_buddypress_media_importer_import' ) ) {
	if (is_admin()) add_action( 'trx_addons_action_importer_import',	'anesta_buddypress_media_importer_import', 10, 2 );
	function anesta_buddypress_media_importer_import($importer, $action) {
		if ( anesta_exists_buddypress_media() && in_array('buddypress-media', $importer->options['required_plugins']) ) {
			if ( $action == 'import_buddypress-media' ) {
				$importer->response['start_from_id'] = 0;
				$importer->import_dump('buddypress-media', esc_html__('rtMedia for BuddyPress and bbPress meta', 'anesta'));
			}
		}
	}
}


// Display import progress
if ( !function_exists( 'anesta_buddypress_media_import_fields' ) ) {
	// Handler of the add_action( 'trx_addons_action_importer_import_fields',	'anesta_buddypress_media_import_fields', 10, 1 );
	function anesta_buddypress_media_import_fields($importer) {
		if ( anesta_exists_buddypress_media() && in_array('buddypress-media', $importer->options['required_plugins']) ) {
			$importer->show_importer_fields(array(
					'slug'=>'buddypress-media',
					'title' => esc_html__('rtMedia for BuddyPress and bbPress meta', 'anesta')
				)
			);
		}
	}
}

// Export posts
if ( !function_exists( 'anesta_buddypress_media_export' ) ) {
	add_action( 'trx_addons_action_importer_export',	'anesta_buddypress_media_export', 10, 1 );
	function anesta_buddypress_media_export($importer) {
		if ( anesta_exists_buddypress_media() && in_array('buddypress-media', $importer->options['required_plugins']) ) {
			trx_addons_fpc($importer->export_file_dir('buddypress-media.txt'), serialize( array(
					"rt_rtm_activity"				=> $importer->export_dump("rt_rtm_activity"),
					"rt_rtm_media"					=> $importer->export_dump("rt_rtm_media"),
					"rt_rtm_media_interaction"		=> $importer->export_dump("rt_rtm_media_interaction"),
					"rt_rtm_media_meta"				=> $importer->export_dump("rt_rtm_media_meta"),
				) )
			);
		}
	}
}

// Display exported data in the fields
if ( !function_exists( 'anesta_buddypress_media_export_fields' ) ) {
	add_action( 'trx_addons_action_importer_export_fields',	'anesta_buddypress_media_export_fields', 10, 1 );
	function anesta_buddypress_media_export_fields($importer) {
		if ( anesta_exists_buddypress_media() && in_array('buddypress-media', $importer->options['required_plugins']) ) {
			$importer->show_exporter_fields(array(
					'slug'	=> 'buddypress-media',
					'title' => esc_html__('rtMedia for BuddyPress and bbPress', 'anesta')
				)
			);
		}
	}
}