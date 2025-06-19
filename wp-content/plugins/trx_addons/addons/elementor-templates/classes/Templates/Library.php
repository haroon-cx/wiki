<?php
namespace TrxAddons\ElementorTemplates\Templates;

defined( 'ABSPATH' ) || exit;

use TrxAddons\ElementorTemplates\Utils;
// use TrxAddons\ElementorTemplates\Options;


/**
 * Class Library - show a Templates Library and import any template to the current page
 */
class Library {

	private $templates_library_cache_name = 'trx_addons_elementor_list_templates';
	private $templates_library_cache_time = 2 * 24 * 60 * 60;
	private $templates_library_option_favorites = 'trx_addons_elementor_favorite_templates';

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Update list of templates
		add_action( 'admin_init', array( $this, 'update_list_templates' ) );

		// Refresh templates list
		add_action( 'wp_ajax_trx_addons_elementor_templates_library_refresh', array( $this, 'refresh_list_templates' ) );

		// Import template
		add_action( 'wp_ajax_trx_addons_elementor_templates_library_item_import', array( $this, 'import_template' ) );

		// Mark/unmark template as favorite
		add_action( 'wp_ajax_trx_addons_elementor_templates_library_item_favorite', array( $this, 'favorite_template' ) );

		// Enqueue scripts and styles
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_editor_scripts' ) );
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue_preview_scripts' ) );

		// Add messages to js-vars
		add_filter( 'trx_addons_filter_localize_script_admin', array( $this, 'localize_script_admin' ) );
	}

	/**
	 * Get list of templates
	 * 
	 * @return array  List of templates or false
	 */
	public function get_list_templates() {
		return get_transient( $this->templates_library_cache_name );
	}

	/**
	 * Save list of templates to the cache
	 * 
	 * @param array $templates  List of templates
	 */
	public function set_list_templates( $templates ) {
		set_transient( $this->templates_library_cache_name, $templates, $this->templates_library_cache_time );
	}

	/**
	 * Update list of templates
	 * 
	 * @param bool $force  If true - force update
	 * 
	 * @hooked admin_init
	 */
	public function update_list_templates( $force = false ) {
		$templates = $force ? false : $this->get_list_templates();
		if ( ! is_array( $templates ) ) {
			$templates_available = trx_addons_get_upgrade_data( array( 'action' => 'info_elementor_templates' ) );
			if ( empty( $templates_available['error'] ) && ! empty( $templates_available['data'] ) && $templates_available['data'][0] == '{' ) {
				$templates = json_decode( $templates_available['data'], true );
			}
			$this->set_list_templates( is_array( $templates ) ? $templates : array() );
		}
	}

	/**
	 * Get list of favorite templates
	 * 
	 * @return array  List of favorite templates
	 */
	public function get_list_favorites() {
		return get_option( $this->templates_library_option_favorites, array() );
	}

	/**
	 * Save list of favorite templates
	 * 
	 * @param array $templates  List of templates
	 */
	public function set_list_favorites( $templates ) {
		update_option( $this->templates_library_option_favorites, $templates );
	}

	/**
	 * Get template data by type and name
	 * 
	 * @param string $name  Template name
	 * @param string $type  Template type (not used now)
	 * 
	 * @return array  Template data
	 */
	public function get_template_data( $name, $type = '' ) {
		$templates = $this->get_list_templates();
		return ! empty( $templates[ $name ] ) ? $templates[ $name ] : false;
	}

	/**
	 * Get templates tabs and categories
	 * 
	 * @return array  Templates tabs and categories
	 */
	public function get_tabs_and_categories() {
		$tabs = array();
		$templates = $this->get_list_templates();
		if ( is_array( $templates ) ) {
			foreach ( $templates as $name => $data ) {
				if ( ! empty( $data['type'] ) ) {
					if ( empty( $tabs[ $data['type'] ] ) ) {
						$tabs[ $data['type'] ] = array(
							'title' => $this->get_tab_title( $data['type'] ),
							'category' => array()
						);
					}
					$cats = array_map( 'trim', explode( ',', ! empty( $data['category'] ) ? $data['category'] : '' ) );
					foreach ( $cats as $cat ) {
						if ( empty( $cat ) ) {
							continue;
						}
						if ( ! isset( $tabs[ $data['type'] ]['category'][ $cat ] ) ) {
							$tabs[ $data['type'] ]['category'][ $cat ] = array(
								'title' => ucfirst( str_replace( array( '-', '_' ), ' ', $cat ) ),
								'total' => 0
							);
						}
						$tabs[ $data['type'] ]['category'][ $cat ]['total']++;
					}
					ksort( $tabs[ $data['type'] ]['category'] );
				}
			}
		}
		return $tabs;
	}

	
	/**
	 * Get the tab's title by type
	 * 
	 * @return string  Tab's title
	 */
	private function get_tab_title( $type ) {
		return $type == 'page' ? esc_html__( 'Pages', 'trx_addons' ) : esc_html__( 'Blocks', 'trx_addons' );
	}


	/**
	 * Refresh list of templates
	 * 
	 * @hooked wp_ajax_trx_addons_elementor_templates_library_refresh
	 */
	public function refresh_list_templates() {

		trx_addons_verify_nonce();

		$this->update_list_templates( true );

		$response = array(
			'error' => '',
			'data' => array(
				'templates' => $this->get_list_templates(),
				'tabs' => $this->get_tabs_and_categories(),
			)
		);

		trx_addons_ajax_response( $response );
	}

	/**
	 * Mark/unmark template as favorite
	 * 
	 * @hooked wp_ajax_trx_addons_elementor_templates_library_item_favorite
	 */
	public function favorite_template() {

		trx_addons_verify_nonce();

		$response = array(
			'error' => '',
			'data' => array()
		);

		$template_name = trx_addons_get_value_gp( 'template_name' );
		$favorite = (int)trx_addons_get_value_gp( 'favorite' );

		$templates = $this->get_list_favorites();
		if ( $favorite ) {
			$templates[ $template_name ] = true;
		} else {
			unset( $templates[ $template_name ] );
		}
		$this->set_list_favorites( $templates );

		trx_addons_ajax_response( $response );
	}


	/**
	 * Import template
	 * 
	 * @hooked wp_ajax_trx_addons_elementor_templates_library_item_import
	 */
	public function import_template() {

		trx_addons_verify_nonce();

		$response = array(
			'error' => '',
			'data' => array()
		);

		$template_name = trx_addons_get_value_gp( 'template_name' );
		$template_type = trx_addons_get_value_gp( 'template_type' );

		$templates = $this->get_list_templates();
		$template_data = ! empty( $templates[ $template_name ] ) ? $templates[ $template_name ] : false;

		if ( ! is_array( $template_data ) ) {
			$response['error'] = esc_html__( 'The contents of the selected template are inaccessible!', 'trx_addons' );
		} else if ( ! empty( $template_data['content'] ) ) {
			$response['data'] = $template_data['content'];
		} else {
			$key = trx_addons_get_theme_activation_code();
			if ( empty( $key ) ) {
				$response['error'] = esc_html__( 'Theme is not activated!', 'trx_addons' );
			} else {
				$template_content = trx_addons_get_upgrade_data( array(
					'action' => 'download_elementor_template',
					'key' => $key,
					'template' => $template_name,
					'type' => $template_type
				) );
				if ( ! empty( $template_content['error'] ) ) {
					$response['error'] = $template_content['error'];
				} else if ( empty( $template_content['data'] ) || $template_content['data'][0] != '{' ) {
					$response['error'] = esc_html__( 'The contents of the selected template are unavailable!', 'trx_addons' );
				} else {
					$response['data'] = json_decode( $template_content['data'], true );
					if ( ! is_array( $response['data']['content'] ) ) {
						$response['error'] = esc_html__( 'The contents of the selected template are corrupted!', 'trx_addons' );
					} else {
						// Download images from the template content and save them to the uploads folder. Replace URLs in the content
						$response['data']['content'] = $this->download_images( $response['data']['content'], $template_name );
						// Change the thumbnail size names - replace the prefix 'elementra-thumb-' with the 'current-theme-slug-thumb-'
						$response['data']['content'] = $this->replace_thumb_sizes( $response['data']['content'], $template_name );
						// Save template content to the cache
						$templates[ $template_name ]['content'] = $response['data'];
						$this->set_list_templates( $templates );
						// Prepare template content for import
						$response['data']['content'] = $this->prepare_template( $response['data']['content'], $template_name );
					}
				}
			}
		}

		trx_addons_ajax_response( $response );
	}

	/**
	 * Download images from the content of Elementor's template and save them to the uploads folder.
	 * Replace URLs in the content and return modified content.
	 * 
	 * @param array $content  Template content
	 * @param string $template_name  Template name
	 * @param string $param_name  Parameter name (not used now)
	 * 
	 * @return array  Modified template content
	 */
	public function download_images( $content, $template_name = '', $param_name = '' ) {
		static $loaded = array();
		if ( ! function_exists( 'media_handle_sideload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';
		}
		$templates_url = untrailingslashit( trx_addons_get_upgrade_domain_url() ) . '/templates/elementor/' . $template_name . '/images/';
		$no_image_attachment = get_option( 'trx_addons_no_image_attachment', array() );
		if ( is_array( $content) || is_object( $content ) ) {
			foreach ( $content as $k => $v ) {
				if ( is_array( $v ) || is_object( $v ) ) {
					$content[ $k ] = $this->download_images( $v, $template_name, $k );
				} else if ( is_string( $v ) && stripos( $v, 'http' ) === 0 ) {					// Check if the string is a URL
					$is_image = preg_match( '/http[s]?:.*\.(jpg|jpeg|png|gif|svg|webp)/i', $v );
					$is_video = preg_match( '/http[s]?:.*\.(mp4|webm|ogg)/i', $v );
					$is_audio = preg_match( '/http[s]?:.*\.(mp3|ogg|wav)/i', $v );
					$is_media = $is_image || $is_video || $is_audio;
					$is_local = empty( $content['source'] ) || $content['source'] == 'library';
					if ( $is_media && $is_local ) {	// Check if the URL is a local media file
						$media_name = basename( $v );
						$file_array = array(
							'name' => $media_name,
						);
						if ( ! isset( $loaded[ $v ] ) ) {
							$loaded[ $v ] = array(
								'id' => 0,
								'url' => '',
							);
							// Get a media file content
							$is_no_media = false;
							$ext = strtolower( pathinfo( $media_name, PATHINFO_EXTENSION ) );
							$no_media_key = $is_video ? 'mp4' : ( $is_audio ? 'mp3' : ( $ext == 'svg' ? 'svg' : 'img' ) );
							$media_content = trx_addons_fgc( $templates_url . $media_name );
							if ( empty( $media_content ) && apply_filters( 'trx_addons_filter_elementor_templates_download_images_from_site', false ) ) {
								$media_content = trx_addons_fgc( $v );
							}
							if ( empty( $media_content ) ) {
								if ( ! empty( $no_image_attachment[ $no_media_key ]['id'] ) && ! empty( $no_image_attachment[ $no_media_key ]['url'] ) ) {
									$loaded[ $v ] = array(
										'id'  => $no_image_attachment[ $no_media_key ]['id'],
										'url' => $no_image_attachment[ $no_media_key ]['url'],
									);
								} else {
									$media_name = $file_array['name'] = ( $is_video ? 'no-video' : ( $is_audio ? 'no-audio' : 'no-image' ) )
																		. '.' . ( $no_media_key == 'img' ? 'jpg' : $no_media_key );
									$media_content = trx_addons_fgc( trx_addons_get_file_dir( 'css/images/' . $media_name ) );
									$is_no_media = true;
								}
							}
							if ( ! empty( $media_content ) ) {
								// Save a content to the file to temp location
								$temp_file_name = wp_tempnam( $media_name );
								if ( $temp_file_name && trx_addons_fpc( $temp_file_name, $media_content ) ) {
									$file_array['tmp_name'] = $temp_file_name;
								}
								if ( ! empty( $file_array['tmp_name'] ) ) {
									$attachment_post_data = array(
										'post_title' => $media_name,
										'post_content' => '',
										'post_excerpt' => '',
										'post_status' => 'inherit',
									);
									// Allow SVG uploading
									if ( $ext == 'svg' ) {
										$old_setting = trx_addons_get_setting( 'allow_upload_svg', false );
										trx_addons_set_setting( 'allow_upload_svg', true );
									}
									$old_setting = trx_addons_get_setting( 'allow_upload_svg', false );
									trx_addons_set_setting( 'allow_upload_svg', true );
									// Save an image to the media library
									$attachment_id = media_handle_sideload( $file_array, 0, null, $attachment_post_data );
									// Restore the old SVG setting
									if ( $ext == 'svg' ) {
										trx_addons_set_setting( 'allow_upload_svg', $old_setting );
									}
									// Save the result to the cache
									if ( ! is_wp_error( $attachment_id ) ) {
										$loaded[ $v ] = array(
											'id' => $attachment_id,
											'url' => wp_get_attachment_url( $attachment_id ),	//trx_addons_get_attachment_url( $attachment_id )
										);
										// Save the no-media data
										if ( $is_no_media ) {
											$no_image_attachment[ $no_media_key ] = array(
												'id' => $loaded[ $v ]['id'],
												'url' => $loaded[ $v ]['url'],
											);
											update_option( 'trx_addons_no_image_attachment', $no_image_attachment );
										}
									}
								}
							}
						}
						// Replace the URL in the content
						if ( ! empty( $loaded[ $v ]['id'] ) && ! empty( $loaded[ $v ]['url'] ) ) {
							$content[ $k ] = $loaded[ $v ]['url'];
							if ( isset( $content['id'] ) ) {
								$content['id'] = $loaded[ $v ]['id'];
							}
						}
					} else if ( isset( $content['is_external'] ) ) {	// Replace all links in the content with '#' to prevent loading of external resources
						$content[ $k ] = '#';
						// $content['is_external'] = '';
					}
				}
			}
		}
		return $content;
	}

	/**
	 * Change the thumbnail size names - replace the prefix 'elementra-thumb-' with the 'current-theme-slug-thumb-'
	 * 
	 * @param array $content  Template content
	 * @param string $template_name  Template name
	 * @param string $old_theme_slug  Theme slug to replace
	 * @param string $new_theme_slug  New theme slug to replace with
	 * 
	 * @return array  Modified template content
	 */
	public function replace_thumb_sizes( $content, $template_name = '', $old_theme_slug = '', $new_theme_slug = '' ) {
		if ( is_array( $content ) || is_object( $content ) ) {
			if ( empty( $old_theme_slug ) ) {
				$old_theme_slug = apply_filters( 'trx_addons_filter_templates_library_replace_thumb_sizes_theme_slug', 'elementra' );
				$new_theme_slug = get_template();
			}
			foreach ( $content as $k => $v ) {
				if ( is_array( $v ) ) {
					$content[ $k ] = $this->replace_thumb_sizes( $v, $template_name, $old_theme_slug, $new_theme_slug );
				} else if ( is_string( $v ) && strpos( $v, $old_theme_slug . '-thumb-' ) !== false ) {
					$content[ $k ] = str_replace( $old_theme_slug . '-thumb-', $new_theme_slug . '-thumb-', $v );
				}
			}
		}
		return $content;
	}

	/**
	 * Check an Elementor's template for each widget exists and replace them with the default ones if not exists
	 * 
	 * @param array $content  Template content
	 * @param string $template_name  Template name
	 * 
	 * @return array  Modified template content
	 */
	public function prepare_template( $content, $template_name = '' ) {
		if ( is_array( $content ) || is_object( $content ) ) {
			foreach ( $content as $k => $v ) {
				if ( ! empty( $v['elements'] ) ) {
					$content[ $k ]['elements'] = $this->prepare_template( $v['elements'], $template_name );
				} else if ( ! empty( $v['elType'] ) && $v['elType'] == 'widget' && ! empty( $v['widgetType'] ) ) {
					$widget = $v['widgetType'];
					$widget_class = \Elementor\Plugin::$instance->widgets_manager->get_widget_types( $widget );
					if ( empty( $widget_class ) ) {
						// Replace the widget with the default one
						$content[ $k ]['widgetType'] = 'alert';
						$content[ $k ]['settings'] = array(
							'alert_type' => 'warning',
							'show_dismiss' => '',
							'alert_title' => sprintf( __( 'Unavailable Widget!', 'trx_addons' ), $widget, $template_name ),
							'alert_description' => sprintf( __( 'Widget "%s" from the template "%s" is not available now!', 'trx_addons' ), $widget, $template_name ),
						);
					}
				}
			}
		}
		return $content;
	}

	/**
	 * Load styles and scripts for the templates library editor area
	 *
	 * @return void
	 */
	public function enqueue_editor_scripts() {
		wp_enqueue_script( 'trx_addons_elementor_extension_templates_library', trx_addons_get_file_url( TRX_ADDONS_PLUGIN_ADDONS . 'elementor-templates/js/templates-library.js' ), array( 'jquery' ), null, false );
		wp_enqueue_style( 'trx_addons_elementor_extension_templates_library', trx_addons_get_file_url( TRX_ADDONS_PLUGIN_ADDONS . 'elementor-templates/css/templates-library.css'), array( 'dashicons' ), null );
	}

	/**
	 * Load styles and scripts for the templates library preview area
	 *
	 * @return void
	 */
	public function enqueue_preview_scripts() {
		wp_enqueue_style( 'trx_addons_elementor_extension_templates_library', trx_addons_get_file_url( TRX_ADDONS_PLUGIN_ADDONS . 'elementor-templates/css/templates-library-preview.css'), array( 'dashicons' ), null );
	}

	/**
	 * Localize script to show messages in the admin mode
	 * 
	 * @hooked 'trx_addons_filter_localize_script_admin'
	 * 
	 * @param array $vars  Array of variables to be passed to the script
	 * 
	 * @return array  Modified array of variables
	 */
	function localize_script_admin( $vars ) {
		$vars['elementor_templates_library'] = $this->get_list_templates();
		if ( ! is_array( $vars['elementor_templates_library'] ) ) $vars['elementor_templates_library'] = array();
		$vars['elementor_templates_library_favorites'] = $this->get_list_favorites();
		$vars['elementor_templates_library_tabs'] = $this->get_tabs_and_categories();
		$vars['elementor_templates_library_url'] = '//upgrade.themerex.net/templates/elementor';
		$vars['elementor_templates_library_pagination_items'] = array( 'block' => 50, 'page' => 20 );
		$vars['elementor_templates_library_navigation_style'] = apply_filters( 'trx_addons_filter_templates_library_navigation_style', 'toolbar' );	// 'toolbar' or 'sidebar'
		$vars['msg_elementor_templates_library_title'] = esc_html__( "ThemeREX Templates", 'trx_addons' );
		$vars['msg_elementor_templates_library_close'] = esc_html__( "Close Library", 'trx_addons' );
		$vars['msg_elementor_templates_library_preview_close'] = esc_html__( "Close preview", 'trx_addons' );
		$vars['msg_elementor_templates_library_refresh'] = esc_html__( "Refresh Library", 'trx_addons' );
		$vars['msg_elementor_templates_library_refresh_title'] = esc_html__( "Check for updates on the templates library server", 'trx_addons' );
		$vars['msg_elementor_templates_library_search'] = esc_html__( "SEARCH", 'trx_addons' );
		$vars['msg_elementor_templates_library_category_all'] = esc_html__( "All Templates", 'trx_addons' );
		$vars['msg_elementor_templates_library_category_favorites'] = esc_html__( "Favorites", 'trx_addons' );
		$vars['msg_elementor_templates_library_filter_by_category'] = esc_html__( "Filter by category", 'trx_addons' );
		$vars['msg_elementor_templates_library_empty'] = esc_html__( "No templates available!", 'trx_addons' );
		$vars['msg_elementor_templates_library_type_page'] = esc_html__( "Page", 'trx_addons' );
		$vars['msg_elementor_templates_library_type_block'] = esc_html__( "Block", 'trx_addons' );
		$vars['msg_elementor_templates_library_add_template'] = esc_html__( "Add Template from Library", 'trx_addons' );
		$vars['msg_elementor_templates_library_import_template'] = esc_html__( "Insert", 'trx_addons' );
		$vars['msg_elementor_templates_library_import_confirm'] = esc_html__( "Insert a template into the page?", 'trx_addons' );
		return $vars;
	}

}
