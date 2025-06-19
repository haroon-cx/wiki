<?php
/**
 * Plugin support: WPML for Elementor
 *
 * @package ThemeREX Addons
 * @since v1.88.6
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	exit;
}


if ( ! function_exists( 'trx_addons_wpml_elementor_widgets_to_translate' ) ) {
	add_filter( 'wpml_elementor_widgets_to_translate', 'trx_addons_wpml_elementor_widgets_to_translate' );
	/**
	 * Add Elementor's widgets to WPML's string translation
	 * 
	 * @hooked wpml_elementor_widgets_to_translate
	 * 
	 * @param array $nodes  List of widgets (shortcodes) and their params
	 * 
	 * @return array        Modified list of widgets (shortcodes) and their params
	 */
	function trx_addons_wpml_elementor_widgets_to_translate( $nodes ) {

		// Elementor Core Elements (Sections, Columns, Containers) and Widgets
		//--------------------------------------------------------------------

		// Background text for Sections and Containers
		$elements = array(
			'container' => __( 'Container', 'trx_addons' ),
			'section'   => __( 'Section', 'trx_addons' ),
		);
		$fields = array(
			array(
				'field'       => 'bg_text',
				'type'        => __( 'Background Text', 'trx_addons' ),
				'editor_type' => 'AREA'
			),
		);
		foreach( $elements as $el => $el_name ) {
			if ( isset( $nodes[ $el ] ) ) {
				$nodes[ $el ]['fields'] = array_merge( $nodes[ $el ]['fields'], $fields );
			} else {
				$nodes[ $el ] = array(
					'conditions' => array( 'elType' => $el ),
					'fields'     => $fields
				);
			}
		}

		// Nested Tabs: add Subtitle to the existing Tabs
		$elements = array(
			'nested-tabs' => __( 'Tabs', 'trx_addons' ),
		);
		foreach( $elements as $el => $el_name ) {
			$fields = array(
				array(
					'field'       => 'tab_subtitle',
					'type'        => sprintf( __( '%s: Subtitle', 'trx_addons' ), $el_name ),
					'editor_type' => 'AREA'
				),
			);
			if ( isset( $nodes[ $el ] ) ) {
				$nodes[ $el ]['fields_in_item'] = array_merge( $nodes[ $el ]['fields_in_item'], $fields );
			} else {
				$nodes[ $el ] = array(
					'conditions' => array( 'widgetType' => $el ),
					'fields_in_item' => array_merge( array(
						array(
							'field' => 'tab_title',
							'type'  => sprintf( __( '%s: Title', 'trx_addons' ), $el_name ),
							'editor_type' => 'LINE'
						),
					), $fields )
				);
			}
		}

		// Elementor Widgets
		//----------------------------------

		// Accordion
		$sc = __( 'Accordion', 'trx_addons' );
		$nodes['trx_elm_accordion'] = array(
			'conditions' => array( 'widgetType' => 'trx_elm_accordion' ),
			'fields'     => array(
								array(
									'field'       => 'item_number_prefix',
									'type'        => sprintf( __( '%s: item number prefix', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'item_number_sufix',
									'type'        => sprintf( __( '%s: item number suffix', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
			),
			// Use only one of following two options to translate the content of the items:
			// 'fields_in_item' or 'integration-class'
			//-----------------------------------------------------------------------------
			// 'fields_in_item' => array(
			// 	'tabs' => array(
			// 		array(
			// 			'field' => 'tab_title',
			// 			'type'  => sprintf( __( '%s: Title', 'trx_addons' ), $sc ),
			// 			'editor_type' => 'LINE'
			// 		),
			// 		array(
			// 			'field' => 'accordion_content',
			// 			'type' => sprintf( __( '%s: Content', 'trx_addons' ), $sc ),
			// 			'editor_type' => 'VISUAL'
			// 		),
			// 	),
			// ),
   			'integration-class' => 'WPML_Elementor_Trx_Elm_Accordion',
		);

		// Advanced Title
		$sc = __( 'Advanced Title', 'trx_addons' );
		$nodes['trx_elm_advanced_title'] = array(
			'conditions' => array( 'widgetType' => 'trx_elm_advanced_title' ),
			'fields'     => array(
				'link' => array(
					'field'       => 'url',
					'type'        => sprintf( __( '%s: link URL', 'trx_addons' ), $sc ),
					'editor_type' => 'LINK'
				),
			),
			'integration-class' => 'WPML_Elementor_Trx_Elm_Advanced_Title',
		);

		// Counter
		$sc = __( 'Counter', 'trx_addons' );
		$nodes['trx_elm_counter'] = array(
			'conditions' => array( 'widgetType' => 'trx_elm_counter' ),
			'fields'     => array(
				array(
					'field'       => 'counter_title',
					'type'        => sprintf( __( '%s: Title', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'counter_subtitle',
					'type'        => sprintf( __( '%s: Subtitle', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'number_prefix',
					'type'        => sprintf( __( '%s: Number Prefix', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'number_suffix',
					'type'        => sprintf( __( '%s: Number Suffix', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
			),
		);

		// Flip Box
		$sc = __( 'Flip Box', 'trx_addons' );
		$nodes['trx_elm_flip_box'] = array(
			'conditions' => array( 'widgetType' => 'trx_elm_flip_box' ),
			'fields'     => array(
				array(
					'field'       => 'icon_text',
					'type'        => sprintf( __( '%s: Icon Text Front', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'title_front',
					'type'        => sprintf( __( '%s: Title Front', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'description_front',
					'type'        => sprintf( __( '%s: Description Front', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'icon_text_back',
					'type'        => sprintf( __( '%s: Icon Text Back', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'title_back',
					'type'        => sprintf( __( '%s: Title Back', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'description_back',
					'type'        => sprintf( __( '%s: Description Back', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				'link' => array(
					'field'       => 'url',
					'type'        => sprintf( __( '%s: Link', 'trx_addons' ), $sc ),
					'editor_type' => 'LINK'
				),
				array(
					'field'       => 'flipbox_button_text',
					'type'        => sprintf( __( '%s: Button Text', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
			),
		);

		// Icon List
		$sc = __( 'Icon List', 'trx_addons' );
		$nodes['trx_elm_icon_list'] = array(
			'conditions' => array( 'widgetType' => 'trx_elm_icon_list' ),
			'fields'     => array(
			),
			'integration-class' => 'WPML_Elementor_Trx_Elm_Icon_List',
		);

		// Image Accordion
		$sc = __( 'Image Accordion', 'trx_addons' );
		$nodes['trx_elm_image_accordion'] = array(
			'conditions' => array( 'widgetType' => 'trx_elm_image_accordion' ),
			'fields'     => array(
			),
			'integration-class' => 'WPML_Elementor_Trx_Elm_Image_Accordion',
		);

		// Info Box
		$sc = __( 'Info Box', 'trx_addons' );
		$nodes['trx_elm_info_box'] = array(
			'conditions' => array( 'widgetType' => 'trx_elm_info_box' ),
			'fields'     => array(
				array(
					'field'       => 'icon_text',
					'type'        => sprintf( __( '%s: Icon Text', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'heading',
					'type'        => sprintf( __( '%s: Title', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'sub_heading',
					'type'        => sprintf( __( '%s: Subtitle', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'description',
					'type'        => sprintf( __( '%s: Description', 'trx_addons' ), $sc ),
					'editor_type' => 'VISUAL'
				),
				'link' => array(
					'field'       => 'url',
					'type'        => sprintf( __( '%s: Link', 'trx_addons' ), $sc ),
					'editor_type' => 'LINK'
				),
				array(
					'field'       => 'button_text',
					'type'        => sprintf( __( '%s: Button Text', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
			),
		);

		// Info List
		$sc = __( 'Info List', 'trx_addons' );
		$nodes['trx_elm_info_list'] = array(
			'conditions' => array( 'widgetType' => 'trx_elm_info_list' ),
			'fields'     => array(
			),
			'integration-class' => 'WPML_Elementor_Trx_Elm_Info_List',
		);

		// Nav Menu
		$sc = __( 'Nav Menu', 'trx_addons' );
		$nodes['trx_elm_nav_menu'] = array(
			'conditions' => array( 'widgetType' => 'trx_elm_nav_menu' ),
			'fields'     => array(
			),
			'integration-class' => 'WPML_Elementor_Trx_Elm_Nav_Menu',
		);

		// Post Meta
		$sc = __( 'Post Meta', 'trx_addons' );
		$nodes['trx_elm_post_meta'] = array(
			'conditions' => array( 'widgetType' => 'trx_elm_post_meta' ),
			'fields'     => array(
				array(
					'field'       => 'type_custom',
					'type'        => sprintf( __( '%s: Custom Field', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'html_custom',
					'type'        => sprintf( __( '%s: Custom HTML', 'trx_addons' ), $sc ),
					'editor_type' => 'AREA'
				),
				array(
					'field'       => 'date_format',
					'type'        => sprintf( __( '%s: Date Format', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'custom_format',
					'type'        => sprintf( __( '%s: Custom Format', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'before',
					'type'        => sprintf( __( '%s: Text Before', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'after',
					'type'        => sprintf( __( '%s: Text After', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
			),
		);

		// Posts
		$sc = __( 'Posts', 'trx_addons' );
		$nodes['trx_elm_posts'] = array(
			'conditions' => array( 'widgetType' => 'trx_elm_posts' ),
			'fields'     => array(
				array(
					'field'       => 'nothing_found_message',
					'type'        => sprintf( __( '%s: Nothing Found Message', 'trx_addons' ), $sc ),
					'editor_type' => 'AREA'
				),
			),
		);

		// Pricing Menu
		$sc = __( 'Pricing Menu', 'trx_addons' );
		$nodes['trx_elm_pricing_menu'] = array(
			'conditions' => array( 'widgetType' => 'trx_elm_pricing_menu' ),
			'fields'     => array(
				array(
					'field'       => 'menu_title',
					'type'        => sprintf( __( '%s: Title', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'menu_description',
					'type'        => sprintf( __( '%s: Description', 'trx_addons' ), $sc ),
					'editor_type' => 'AREA'
				),
				array(
					'field'       => 'menu_price',
					'type'        => sprintf( __( '%s: Price', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'original_price',
					'type'        => sprintf( __( '%s: Original Price', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				'link' => array(
					'field'       => 'url',
					'type'        => sprintf( __( '%s: Link', 'trx_addons' ), $sc ),
					'editor_type' => 'LINK'
				),
			),
		);

		// Pricing Table
		$sc = __( 'Pricing Table', 'trx_addons' );
		$nodes['trx_elm_pricing_table'] = array(
			'conditions' => array( 'widgetType' => 'trx_elm_pricing_table' ),
			'fields'     => array(
				array(
					'field'       => 'table_title',
					'type'        => sprintf( __( '%s: Title', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'table_subtitle',
					'type'        => sprintf( __( '%s: Subtitle', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'ribbon_title',
					'type'        => sprintf( __( '%s: Ribbon Title', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'currency_symbol',
					'type'        => sprintf( __( '%s: Currency Symbol', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'currency_symbol_custom',
					'type'        => sprintf( __( '%s: Custom Symbol', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'table_price',
					'type'        => sprintf( __( '%s: Price', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'table_original_price',
					'type'        => sprintf( __( '%s: Original Price', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'table_duration',
					'type'        => sprintf( __( '%s: Duration', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				'link' => array(
					'field'       => 'url',
					'type'        => sprintf( __( '%s: Link', 'trx_addons' ), $sc ),
					'editor_type' => 'LINK'
				),
				array(
					'field'       => 'table_button_text',
					'type'        => sprintf( __( '%s: Button Text', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'table_additional_info',
					'type'        => sprintf( __( '%s: Additional Info', 'trx_addons' ), $sc ),
					'editor_type' => 'VISUAL'
				),
			),
			'integration-class' => 'WPML_Elementor_Trx_Elm_Pricing_Table',
		);

		// Tabs
		$sc = __( 'Tabs', 'trx_addons' );
		$nodes['trx_elm_tabs'] = array(
			'conditions' => array( 'widgetType' => 'trx_elm_tabs' ),
			'fields'     => array(
			),
			'integration-class' => 'WPML_Elementor_Trx_Elm_Tabs',
		);

		// Team Member
		$sc = __( 'Team Member', 'trx_addons' );
		$nodes['trx_elm_team_member'] = array(
			'conditions' => array( 'widgetType' => 'trx_elm_team_member' ),
			'fields'     => array(
				array(
					'field'       => 'team_member_name',
					'type'        => sprintf( __( '%s: Name', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'team_member_position',
					'type'        => sprintf( __( '%s: Position', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'team_member_description',
					'type'        => sprintf( __( '%s: Description', 'trx_addons' ), $sc ),
					'editor_type' => 'VISUAL'
				),
				'link' => array(
					'field'       => 'url',
					'type'        => sprintf( __( '%s: Link', 'trx_addons' ), $sc ),
					'editor_type' => 'LINK'
				),
			),
			'integration-class' => 'WPML_Elementor_Trx_Elm_Team_Member',
		);

		// Testimonials
		$sc = __( 'Testimonials', 'trx_addons' );
		$nodes['trx_elm_testimonials'] = array(
			'conditions' => array( 'widgetType' => 'trx_elm_testimonials' ),
			'fields'     => array(
			),
			'integration-class' => 'WPML_Elementor_Trx_Elm_Testimonials',
		);

		// Woo Products
		$sc = __( 'Woo Products', 'trx_addons' );
		$nodes['trx_elm_woo_products'] = array(
			'conditions' => array( 'widgetType' => 'trx_elm_woo_products' ),
			'fields'     => array(
				array(
					'field'       => 'load_more_text',
					'type'        => sprintf( __( '%s: Button Text', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'ids',
					'type'        => sprintf( __( '%s: Product IDs', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'empty_products_msg',
					'type'        => sprintf( __( '%s: Empty Query Message', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'prev_string',
					'type'        => sprintf( __( '%s: Previous Page String', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'next_string',
					'type'        => sprintf( __( '%s: Next Page String', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'qv_text',
					'type'        => sprintf( __( '%s: Quick View Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'sale_string',
					'type'        => sprintf( __( '%s: Sale Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'featured_string',
					'type'        => sprintf( __( '%s: Featured Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'sold_out_string',
					'type'        => sprintf( __( '%s: Sold Out Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'grid_1_cta_buy_now_label',
					'type'        => sprintf( __( '%s (Skin 1): Buy Now Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'grid_1_cta_read_more_label',
					'type'        => sprintf( __( '%s (Skin 1): Read More Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'grid_2_cta_buy_now_label',
					'type'        => sprintf( __( '%s (Skin 2): Buy Now Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'grid_2_cta_read_more_label',
					'type'        => sprintf( __( '%s (Skin 2): Read More Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'grid_3_cta_buy_now_label',
					'type'        => sprintf( __( '%s (Skin 3): Buy Now Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'grid_3_cta_read_more_label',
					'type'        => sprintf( __( '%s (Skin 3): Read More Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'grid_4_cta_buy_now_label',
					'type'        => sprintf( __( '%s (Skin 4): Buy Now Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'grid_4_cta_read_more_label',
					'type'        => sprintf( __( '%s (Skin 4): Read More Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'grid_6_cta_buy_now_label',
					'type'        => sprintf( __( '%s (Skin 6): Buy Now Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'grid_6_cta_read_more_label',
					'type'        => sprintf( __( '%s (Skin 6): Read More Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'grid_7_cta_buy_now_label',
					'type'        => sprintf( __( '%s (Skin 7): Buy Now Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'grid_7_cta_read_more_label',
					'type'        => sprintf( __( '%s (Skin 7): Read More Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'grid_9_cta_buy_now_label',
					'type'        => sprintf( __( '%s (Skin 9): Buy Now Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'grid_9_cta_read_more_label',
					'type'        => sprintf( __( '%s (Skin 9): Read More Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'grid_10_cta_buy_now_label',
					'type'        => sprintf( __( '%s (Skin 10): Buy Now Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'grid_10_cta_read_more_label',
					'type'        => sprintf( __( '%s (Skin 10): Read More Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'grid_10_cta_view_products_label',
					'type'        => sprintf( __( '%s (Skin 10): View Products Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'grid_10_cta_purchase_label',
					'type'        => sprintf( __( '%s (Skin 10): Purchase Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'grid_10_cta_select_options_label',
					'type'        => sprintf( __( '%s (Skin 10): Select Options Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'grid_11_cta_buy_now_label',
					'type'        => sprintf( __( '%s (Skin 11): Buy Now Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
				array(
					'field'       => 'grid_11_cta_read_more_label',
					'type'        => sprintf( __( '%s (Skin 11): Read More Label', 'trx_addons' ), $sc ),
					'editor_type' => 'LINE'
				),
			),
		);

		// Shortcodes
		//----------------------------------

		// Shortcode 'Action'
		$sc = __( 'Action', 'trx_addons' );
		$nodes['trx_sc_action'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_action' ),
			'fields'     => array_merge(
								// Shortcode-specific params
								array(
								),
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc )
							),
			'integration-class' => 'WPML_Elementor_Trx_Sc_Action',
		);

		// Shortcode 'Anchor'
		$sc = __( 'Anchor', 'trx_addons' );
		$nodes['trx_sc_anchor'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_anchor' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								'url' => array(
									'field'       => 'url',
									'type'        => sprintf( __( '%s: link URL', 'trx_addons' ), $sc ),
									'editor_type' => 'LINK'
								),
							),
		);

		// Shortcode 'Blogger'
		$sc = __( 'Blogger', 'trx_addons' );
		$nodes['trx_sc_blogger'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_blogger' ),
			'fields'     => array_merge(
								// Shortcode-specific params
								array(
									array(
										'field'       => 'filters_title',
										'type'        => sprintf( __( '%s: filters title', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
									array(
										'field'       => 'filters_subtitle',
										'type'        => sprintf( __( '%s: filters subtitle', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
									array(
										'field'       => 'filters_all_text',
										'type'        => sprintf( __( '%s: filters "All" tab', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
									array(
										'field'       => 'filters_more_text',
										'type'        => sprintf( __( '%s: filters "More posts"', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
									array(
										'field'       => 'more_text',
										'type'        => sprintf( __( '%s: button "Read More"', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
									array(
										'field'       => 'date_format',
										'type'        => sprintf( __( '%s: date format', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
								),
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc )
							),
		);

		// Shortcode 'Button'
		$nodes['trx_sc_button'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_button' ),
			'fields'     => array(
							),
			'integration-class' => 'WPML_Elementor_Trx_Sc_Button',
		);

		// Shortcode 'Cover'
		$sc = __( 'Cover', 'trx_addons' );
		$nodes['trx_sc_cover'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_cover' ),
			'fields'     => array(
								'url' => array(
									'field'       => 'url',
									'type'        => sprintf( __( '%s: link URL', 'trx_addons' ), $sc ),
									'editor_type' => 'LINK'
								),
							),
		);

		// Shortcode 'Form'
		$sc = __( 'Form', 'trx_addons' );
		$nodes['trx_sc_form'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_form' ),
			'fields'     => array_merge(
								// Shortcode-specific params
								array(
									array(
										'field'       => 'email',
										'type'        => sprintf( __( '%s: e-mail', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
									array(
										'field'       => 'phone',
										'type'        => sprintf( __( '%s: phone', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
									array(
										'field'       => 'address',
										'type'        => sprintf( __( '%s: address', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
									array(
										'field'       => 'button_caption',
										'type'        => sprintf( __( '%s: button caption', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
								),
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc )
							),
		);

		// Shortcode 'Google map'
		$sc = __( 'Google map', 'trx_addons' );
		$nodes['trx_sc_googlemap'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_googlemap' ),
			'fields'     => array_merge(
								// Shortcode-specific params
								array(
									array(
										'field'       => 'address',
										'type'        => sprintf( __( '%s: address or Lat,Lng', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
									array(
										'field'       => 'center',
										'type'        => sprintf( __( '%s: center of the map', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
									array(
										'field'       => 'content',
										'type'        => sprintf( __( '%s: content', 'trx_addons' ), $sc ),
										'editor_type' => 'VISUAL'
									),
								),
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc )
							),
			'integration-class' => 'WPML_Elementor_Trx_Sc_Googlemap',
		);

		// Shortcode 'Icons'
		$sc = __( 'Icons', 'trx_addons' );
		$nodes['trx_sc_icons'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_icons' ),
			'fields'     => array_merge(
								// Shortcode-specific params
								array(
								),
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc )
							),
			'integration-class' => 'WPML_Elementor_Trx_Sc_Icons',
		);

		// Shortcode 'OpenStreet map'
		$sc = __( 'OpenStreet map', 'trx_addons' );
		$nodes['trx_sc_osmap'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_osmap' ),
			'fields'     => array_merge(
								// Shortcode-specific params
								array(
									array(
										'field'       => 'address',
										'type'        => sprintf( __( '%s: address or Lat,Lng', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
									array(
										'field'       => 'center',
										'type'        => sprintf( __( '%s: center of the map', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
									array(
										'field'       => 'content',
										'type'        => sprintf( __( '%s: content', 'trx_addons' ), $sc ),
										'editor_type' => 'VISUAL'
									),
								),
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc )
							),
			'integration-class' => 'WPML_Elementor_Trx_Sc_Osmap',
		);

		// Shortcode 'Price'
		$sc = __( 'Price', 'trx_addons' );
		$nodes['trx_sc_price'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_price' ),
			'fields'     => array_merge(
								// Shortcode-specific params
								array(
								),
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc )
							),
			'integration-class' => 'WPML_Elementor_Trx_Sc_Price',
		);

		// Shortcode 'Promo'
		$sc = __( 'Promo', 'trx_addons' );
		$nodes['trx_sc_promo'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_promo' ),
			'fields'     => array_merge(
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc ),
								// Shortcode-specific params
								array(
									'link2' => array(
										'field'       => 'url',
										'field_id'    => 'link2_url',
										'type'        => sprintf( __( '%s: button 2 URL', 'trx_addons' ), $sc ),
										'editor_type' => 'LINK'
									),
									array(
										'field'       => 'link2_text',
										'type'        => sprintf( __( '%s: button 2 text', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
									array(
										'field'       => 'images',
										'type'        => sprintf( __( '%s: image URL', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
									array(
										'field'       => 'video_url',
										'type'        => sprintf( __( '%s: video URL', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
									array(
										'field'       => 'video_embed',
										'type'        => sprintf( __( '%s: video embed', 'trx_addons' ), $sc ),
										'editor_type' => 'AREA'
									),
									array(
										'field'       => 'content',
										'type'        => sprintf( __( '%s: content', 'trx_addons' ), $sc ),
										'editor_type' => 'VISUAL'
									),
								)
							),
		);

		// Shortcode 'Skills'
		$sc = __( 'Skills', 'trx_addons' );
		$nodes['trx_sc_skills'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_skills' ),
			'fields'     => array_merge(
								// Shortcode-specific params
								array(
								),
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc )
							),
			'integration-class' => 'WPML_Elementor_Trx_Sc_Skills',
		);

		// Shortcode 'Socials'
		$sc = __( 'Socials', 'trx_addons' );
		$nodes['trx_sc_socials'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_socials' ),
			'fields'     => array_merge(
								// Shortcode-specific params
								array(
								),
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc )
							),
			'integration-class' => 'WPML_Elementor_Trx_Sc_Socials',
		);

		// Shortcode 'Super title'
		$sc = __( 'Super title', 'trx_addons' );
		$nodes['trx_sc_supertitle'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_supertitle' ),
			'fields'     => array(
							),
			'integration-class' => 'WPML_Elementor_Trx_Sc_Supertitle',
		);

		// Shortcode 'Switcher'
		$sc = __( 'Switcher', 'trx_addons' );
		$nodes['trx_sc_switcher'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_switcher' ),
			'fields'     => array_merge(
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc ),
								// Shortcode-specific params
								array(
									array(
										'field'       => 'slide1_title',
										'type'        => sprintf( __( '%s: Slide 1 title', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
									array(
										'field'       => 'slide2_title',
										'type'        => sprintf( __( '%s: Slide 2 title', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
								)
							),
		);

		// Shortcode 'Table'
		$sc = __( 'Table', 'trx_addons' );
		$nodes['trx_sc_table'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_table' ),
			'fields'     => array_merge(
								// Shortcode-specific params
								array(
									array(
										'field'       => 'content',
										'type'        => sprintf( __( '%s: content', 'trx_addons' ), $sc ),
										'editor_type' => 'AREA'
									),
								),
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc )
							),
		);

		// Shortcode 'Title'
		$sc = __( 'Title', 'trx_addons' );
		$nodes['trx_sc_title'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_title' ),
			'fields'     => array_merge(
								// Shortcode-specific params
								array(
								),
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc )
							)
		);

		// Shortcode 'Users'
		$sc = __( 'Users', 'trx_addons' );
		$nodes['trx_sc_users'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_users' ),
			'fields'     => array_merge(
								// Shortcode-specific params
								array(
								),
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc )
							)
		);

		// Shortcode 'WooCommerce Extended Products'
		$sc = __( 'WooCommerce Extended Products', 'trx_addons' );
		$nodes['trx_sc_extended_products'] = array(
				'conditions' => array( 'widgetType' => 'trx_sc_extended_products' ),
				'fields'     => array_merge(
						// Shortcode-specific params
						array(
						),
						// Common params
						trx_addons_wpml_elementor_get_title_params( $sc )
				)
		);


		// Widgets
		//-----------------------------------------

		// Widget 'About me'
		$sc = __( 'Widget About me', 'trx_addons' );
		$nodes['trx_widget_aboutme'] = array(
			'conditions' => array( 'widgetType' => 'trx_widget_aboutme' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								'avatar' => array(
									'field'       => 'url',
									'type'        => sprintf( __( '%s: avatar URL', 'trx_addons' ), $sc ),
									'editor_type' => 'LINK'
								),
								array(
									'field'       => 'username',
									'type'        => sprintf( __( '%s: user name', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'description',
									'type'        => sprintf( __( '%s: description', 'trx_addons' ), $sc ),
									'editor_type' => 'VISUAL'
								),
							),
		);

		// Widget 'Audio'
		$sc = __( 'Widget Audio', 'trx_addons' );
		$nodes['trx_widget_audio'] = array(
			'conditions' => array( 'widgetType' => 'trx_widget_audio' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'subtitle',
									'type'        => sprintf( __( '%s: subtitle', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'next_text',
									'type'        => sprintf( __( '%s: "Next" button', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'prev_text',
									'type'        => sprintf( __( '%s: "Prev" button', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'now_text',
									'type'        => sprintf( __( '%s: "Now playing" text', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							),
			'integration-class' => 'WPML_Elementor_Trx_Widget_Audio',
		);

		// Widget 'Banner'
		$sc = __( 'Widget Banner', 'trx_addons' );
		$nodes['trx_widget_banner'] = array(
			'conditions' => array( 'widgetType' => 'trx_widget_banner' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								'link' => array(
									'field'       => 'url',
									'type'        => sprintf( __( '%s: link URL', 'trx_addons' ), $sc ),
									'editor_type' => 'LINK'
								),
								array(
									'field'       => 'code',
									'type'        => sprintf( __( '%s: or HTML code', 'trx_addons' ), $sc ),
									'editor_type' => 'AREA'
								),
							),
		);

		// Widget 'Calendar'
		$sc = __( 'Widget Calendar', 'trx_addons' );
		$nodes['trx_widget_calendar'] = array(
			'conditions' => array( 'widgetType' => 'trx_widget_calendar' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							),
		);

		// Widget 'Categories list'
		$sc = __( 'Widget Categories list', 'trx_addons' );
		$nodes['trx_widget_categories_list'] = array(
			'conditions' => array( 'widgetType' => 'trx_widget_categories_list' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							),
		);

		// Widget 'Contacts'
		$sc = __( 'Widget Contacts', 'trx_addons' );
		$nodes['trx_widget_contacts'] = array(
			'conditions' => array( 'widgetType' => 'trx_widget_contacts' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'description',
									'type'        => sprintf( __( '%s: description', 'trx_addons' ), $sc ),
									'editor_type' => 'VISUAL'
								),
								array(
									'field'       => 'address',
									'type'        => sprintf( __( '%s: address', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'phone',
									'type'        => sprintf( __( '%s: phone', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'email',
									'type'        => sprintf( __( '%s: e-mail', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							),
		);

		// Widget 'Custom links'
		$sc = __( 'Widget Custom links', 'trx_addons' );
		$nodes['trx_widget_custom_links'] = array(
			'conditions' => array( 'widgetType' => 'trx_widget_custom_links' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							),
			'integration-class' => 'WPML_Elementor_Trx_Widget_Custom_Links',
		);

		// Widget 'Flickr'
		$sc = __( 'Widget Flickr', 'trx_addons' );
		$nodes['trx_widget_flickr'] = array(
			'conditions' => array( 'widgetType' => 'trx_widget_flickr' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'flickr_api_key',
									'type'        => sprintf( __( '%s: API key', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'flickr_username',
									'type'        => sprintf( __( '%s: user name', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'flickr_count',
									'type'        => sprintf( __( '%s: number of photos', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'flickr_columns',
									'type'        => sprintf( __( '%s: columns', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							),
		);

		// Widget 'Instagram'
		$sc = __( 'Widget Instagram', 'trx_addons' );
		$nodes['trx_widget_instagram'] = array(
			'conditions' => array( 'widgetType' => 'trx_widget_instagram' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'hashtag',
									'type'        => sprintf( __( '%s: Hashtag or Username', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'count',
									'type'        => sprintf( __( '%s: number of photos', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'columns',
									'type'        => sprintf( __( '%s: columns', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							),
		);

		// Widget 'Popular posts'
		$sc = __( 'Widget Popular posts', 'trx_addons' );
		$nodes['trx_widget_popular_posts'] = array(
			'conditions' => array( 'widgetType' => 'trx_widget_popular_posts' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'title_1',
									'type'        => sprintf( __( '%s: tab 1 title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'title_2',
									'type'        => sprintf( __( '%s: tab 2 title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'title_3',
									'type'        => sprintf( __( '%s: tab 3 title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							),
		);

		// Widget 'Recent news'
		$sc = __( 'Widget Recent news', 'trx_addons' );
		$nodes['trx_sc_recent_news'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_recent_news' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'subtitle',
									'type'        => sprintf( __( '%s: subtitle', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'ids',
									'type'        => sprintf( __( '%s: list IDs', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							),
		);

		// Widget 'Recent posts'
		$sc = __( 'Widget Recent posts', 'trx_addons' );
		$nodes['trx_widget_recent_posts'] = array(
			'conditions' => array( 'widgetType' => 'trx_widget_recent_posts' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							),
		);

		// Widget 'Slider'
		$sc = __( 'Widget Slider', 'trx_addons' );
		$nodes['trx_widget_slider'] = array(
			'conditions' => array( 'widgetType' => 'trx_widget_slider' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'alias',
									'type'        => sprintf( __( '%s: RevSlider alias', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'label_prev',
									'type'        => sprintf( __( '%s: Label Prev', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'label_next',
									'type'        => sprintf( __( '%s: Label Next', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							),
			'integration-class' => 'WPML_Elementor_Trx_Widget_Slider',
		);

		// Widget 'Socials'
		$sc = __( 'Widget Socials', 'trx_addons' );
		$nodes['trx_widget_socials'] = array(
			'conditions' => array( 'widgetType' => 'trx_widget_socials' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'description',
									'type'        => sprintf( __( '%s: description', 'trx_addons' ), $sc ),
									'editor_type' => 'VISUAL'
								),
							),
		);

		// Widget 'Twitter'
		$sc = __( 'Widget Twitter', 'trx_addons' );
		$nodes['trx_widget_twitter'] = array(
			'conditions' => array( 'widgetType' => 'trx_widget_twitter' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'username',
									'type'        => sprintf( __( '%s: user name', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'consumer_key',
									'type'        => sprintf( __( '%s: consumer key', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'consumer_secret',
									'type'        => sprintf( __( '%s: consumer secret', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'token_key',
									'type'        => sprintf( __( '%s: token key', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'token_secret',
									'type'        => sprintf( __( '%s: token secret', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							),
		);

		// Widget 'Video'
		$sc = __( 'Widget Video', 'trx_addons' );
		$nodes['trx_widget_video'] = array(
			'conditions' => array( 'widgetType' => 'trx_widget_video' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'link',
									'type'        => sprintf( __( '%s: link to the video', 'trx_addons' ), $sc ),
									'editor_type' => 'LINK'
								),
								array(
									'field'       => 'embed',
									'type'        => sprintf( __( '%s: video embed code', 'trx_addons' ), $sc ),
									'editor_type' => 'AREA'
								),
							),
		);

		// Widget 'Video list'
		$sc = __( 'Widget Video list', 'trx_addons' );
		$nodes['trx_widget_video_list'] = array(
			'conditions' => array( 'widgetType' => 'trx_widget_video_list' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							),
			'integration-class' => 'WPML_Elementor_Trx_Widget_Video_List',
		);


		// CPT: Custom post types
		//------------------------------------------

		// CPT 'Cars'
		$sc = __( 'Cars', 'trx_addons' );
		$nodes['trx_sc_cars'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_cars' ),
			'fields'     => array_merge(
								// Shortcode-specific params
								array(
									array(
										'field'       => 'more_text',
										'type'        => sprintf( __( '%s: "More" text', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
								),
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc )
							)
		);

		// CPT 'Courses'
		$sc = __( 'Courses', 'trx_addons' );
		$nodes['trx_sc_courses'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_courses' ),
			'fields'     => array_merge(
								// Shortcode-specific params
								array(
									array(
										'field'       => 'more_text',
										'type'        => sprintf( __( '%s: "More" text', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
								),
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc )
							)
		);

		// CPT 'Dishes'
		$sc = __( 'Dishes', 'trx_addons' );
		$nodes['trx_sc_dishes'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_dishes' ),
			'fields'     => array_merge(
								// Shortcode-specific params
								array(
									array(
										'field'       => 'more_text',
										'type'        => sprintf( __( '%s: "More" text', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
								),
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc )
							)
		);

		// CPT 'Layouts': Blog itam
		$sc = __( 'Layouts - Blog item', 'trx_addons' );
		$nodes['trx_sc_layouts_blog_item'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_layouts_blog_item' ),
			'fields'     => array(
								array(
									'field'       => 'button_text',
									'type'        => sprintf( __( '%s: Button text', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							)
		);

		// CPT 'Layouts': Cart
		$sc = __( 'Layouts - Cart', 'trx_addons' );
		$nodes['trx_sc_layouts_cart'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_layouts_cart' ),
			'fields'     => array(
								array(
									'field'       => 'text',
									'type'        => sprintf( __( '%s: Cart text', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							)
		);

		// CPT 'Layouts': Iconed text
		$sc = __( 'Layouts - Iconed text', 'trx_addons' );
		$nodes['trx_sc_layouts_iconed_text'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_layouts_iconed_text' ),
			'fields'     => array(
								array(
									'field'       => 'text1',
									'type'        => sprintf( __( '%s: Text line 1', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'text2',
									'type'        => sprintf( __( '%s: Text line 2', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							)
		);

		// CPT 'Layouts': Layout
		$sc = __( 'Layouts', 'trx_addons' );
		$nodes['trx_sc_layouts'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_layouts' ),
			'fields'     => array(
								array(
									'field'       => 'popup_id',
									'type'        => sprintf( __( '%s: Popup (panel) ID', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'content',
									'type'        => sprintf( __( '%s: Popup (panel) content', 'trx_addons' ), $sc ),
									'editor_type' => 'VISUAL'
								),
							)
		);

		// CPT 'Layouts': Login
		$sc = __( 'Layouts - Login', 'trx_addons' );
		$nodes['trx_sc_layouts_login'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_layouts_login' ),
			'fields'     => array(
								array(
									'field'       => 'text_login',
									'type'        => sprintf( __( '%s: Login text', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'text_logout',
									'type'        => sprintf( __( '%s: Logout text', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							)
		);

		// CPT 'Layouts': Logo
		$sc = __( 'Layouts - Logo', 'trx_addons' );
		$nodes['trx_sc_layouts_logo'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_layouts_logo' ),
			'fields'     => array(
								array(
									'field'       => 'logo_text',
									'type'        => sprintf( __( '%s: Logo text', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'logo_slogan',
									'type'        => sprintf( __( '%s: Logo slogan', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								'logo' => array(
									'field'       => 'url',
									'field_id'    => 'logo',
									'type'        => sprintf( __( '%s: Logo image', 'trx_addons' ), $sc ),
									'editor_type' => 'LINK'
								),
								'logo_retina' => array(
									'field'       => 'url',
									'field_id'    => 'logo_retina',
									'type'        => sprintf( __( '%s: Logo Retina', 'trx_addons' ), $sc ),
									'editor_type' => 'LINK'
								),
							)
		);

		// CPT 'Layouts': Title
		$sc = __( 'Layouts - Title', 'trx_addons' );
		$nodes['trx_sc_layouts_title'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_layouts_title' ),
			'fields'     => array(
								'image' => array(
									'field'       => 'url',
									'field_id'    => 'image',
									'type'        => sprintf( __( '%s: image URL', 'trx_addons' ), $sc ),
									'editor_type' => 'LINK'
								),
							)
		);

		// CPT 'Portfolio'
		$sc = __( 'Portfolio', 'trx_addons' );
		$nodes['trx_sc_portfolio'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_portfolio' ),
			'fields'     => array_merge(
								// Shortcode-specific params
								array(
									array(
										'field'       => 'more_text',
										'type'        => sprintf( __( '%s: "More" text', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
								),
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc )
							)
		);

		// CPT 'Properties'
		$sc = __( 'Properties', 'trx_addons' );
		$nodes['trx_sc_properties'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_properties' ),
			'fields'     => array_merge(
								// Shortcode-specific params
								array(
									array(
										'field'       => 'more_text',
										'type'        => sprintf( __( '%s: "More" text', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
								),
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc )
							)
		);

		// CPT 'Services'
		$sc = __( 'Services', 'trx_addons' );
		$nodes['trx_sc_services'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_services' ),
			'fields'     => array_merge(
								// Shortcode-specific params
								array(
									array(
										'field'       => 'more_text',
										'type'        => sprintf( __( '%s: "More" text', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
								),
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc )
							)
		);

		// CPT 'Sport'
		$sc = __( 'Sport - Matches', 'trx_addons' );
		$nodes['trx_sc_matches'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_matches' ),
			'fields'     => array_merge(
								// Shortcode-specific params
								array(
									array(
										'field'       => 'more_text',
										'type'        => sprintf( __( '%s: "More" text', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
								),
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc )
							)
		);

		// CPT 'Team'
		$sc = __( 'Team', 'trx_addons' );
		$nodes['trx_sc_team'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_team' ),
			'fields'     => array_merge(
								// Shortcode-specific params
								array(
									array(
										'field'       => 'more_text',
										'type'        => sprintf( __( '%s: "More" text', 'trx_addons' ), $sc ),
										'editor_type' => 'LINE'
									),
								),
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc )
							)
		);

		// CPT 'Testimonials'
		$sc = __( 'Testimonials', 'trx_addons' );
		$nodes['trx_sc_testimonials'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_testimonials' ),
			'fields'     => array_merge(
								// Shortcode-specific params
								array(
								),
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc )
							)
		);


		// Third-party plugins
		//-------------------------------

		// Widget 'EDD Search'
		$sc = __( 'Widget EDD Search', 'trx_addons' );
		$nodes['trx_widget_edd_search'] = array(
			'conditions' => array( 'widgetType' => 'trx_widget_edd_search' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							),
		);

		// Widget 'LearnPress Course info'
		$sc = __( 'Widget LearnPress Course info', 'trx_addons' );
		$nodes['trx_sc_widget_lp_course_info'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_widget_lp_course_info' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							),
		);

		// Shortcode 'MP Time table'
		$sc = __( 'Widget MP Time table', 'trx_addons' );
		$nodes['trx_sc_mptt'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_widget_lp_course_info' ),
			'fields'     => array(
								array(
									'field'       => 'label',
									'type'        => sprintf( __( '%s: filter label', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'mptt_id',
									'type'        => sprintf( __( '%s: timetable ID', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							),
		);

		// Shortcode 'Events'
		$sc = __( 'Events', 'trx_addons' );
		$nodes['trx_sc_events'] = array(
			'conditions' => array( 'widgetType' => 'trx_sc_events' ),
			'fields'     => array_merge(
								// Shortcode-specific params
								array(
								),
								// Common params
								trx_addons_wpml_elementor_get_title_params( $sc )
							)
		);

		// Shortcode 'ThemeREX Donations form'
		$sc = __( 'Donations form', 'trx_addons' );
		$nodes['trx_donations_form'] = array(
			'conditions' => array( 'widgetType' => 'trx_donations_form' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'subtitle',
									'type'        => sprintf( __( '%s: subtitle', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'description',
									'type'        => sprintf( __( '%s: description', 'trx_addons' ), $sc ),
									'editor_type' => 'VISUAL'
								),
								array(
									'field'       => 'client_id',
									'type'        => sprintf( __( '%s: PayPal client ID', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'amount',
									'type'        => sprintf( __( '%s: default amount', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							)
		);

		// Shortcode 'ThemeREX Donations list'
		$sc = __( 'Donations list', 'trx_addons' );
		$nodes['trx_donations_list'] = array(
			'conditions' => array( 'widgetType' => 'trx_donations_list' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'subtitle',
									'type'        => sprintf( __( '%s: subtitle', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'description',
									'type'        => sprintf( __( '%s: description', 'trx_addons' ), $sc ),
									'editor_type' => 'VISUAL'
								),
								'link' => array(
									'field'       => 'url',
									'type'        => sprintf( __( '%s: link URL', 'trx_addons' ), $sc ),
									'editor_type' => 'LINK'
								),
								array(
									'field'       => 'link_caption',
									'type'        => sprintf( __( '%s: link text', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							)
		);

		// Shortcode 'ThemeREX Donations info'
		$sc = __( 'Donations info', 'trx_addons' );
		$nodes['trx_donations_info'] = array(
			'conditions' => array( 'widgetType' => 'trx_donations_info' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'subtitle',
									'type'        => sprintf( __( '%s: subtitle', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'description',
									'type'        => sprintf( __( '%s: description', 'trx_addons' ), $sc ),
									'editor_type' => 'VISUAL'
								),
								'link' => array(
									'field'       => 'url',
									'type'        => sprintf( __( '%s: link URL', 'trx_addons' ), $sc ),
									'editor_type' => 'LINK'
								),
								array(
									'field'       => 'link_caption',
									'type'        => sprintf( __( '%s: link text', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							)
		);

		// Widget 'WooCommerce Search'
		$sc = __( 'Widget WooCommerce search', 'trx_addons' );
		$nodes['trx_widget_woocommerce_search'] = array(
			'conditions' => array( 'widgetType' => 'trx_widget_woocommerce_search' ),
			'fields'     => array(
								array(
									'field'       => 'title',
									'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'last_text',
									'type'        => sprintf( __( '%s: text after last field', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
								array(
									'field'       => 'button_text',
									'type'        => sprintf( __( '%s: button text', 'trx_addons' ), $sc ),
									'editor_type' => 'LINE'
								),
							),
			'integration-class' => 'WPML_Elementor_Trx_Widget_Woocommerce_Search',
		);
		
		return $nodes;

	}
}

if ( ! function_exists( 'trx_addons_wpml_elementor_get_title_params' ) ) {
	/**
	 * Return array with title parameters for WPML translation
	 *
	 * @param string $sc  Shortcode name
	 * 
	 * @return array      Array with title parameters
	 */
	function trx_addons_wpml_elementor_get_title_params( $sc ) {
		return array(
					array(
						'field'       => 'title',
						'type'        => sprintf( __( '%s: title', 'trx_addons' ), $sc ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'subtitle',
						'type'        => sprintf( __( '%s: subtitle', 'trx_addons' ), $sc ),
						'editor_type' => 'LINE'
					),
					array(
						'field'       => 'description',
						'type'        => sprintf( __( '%s: description', 'trx_addons' ), $sc ),
						'editor_type' => 'AREA'
					),
					'link' => array(
						'field'       => 'url',
						'type'        => sprintf( __( '%s: link URL', 'trx_addons' ), $sc ),
						'editor_type' => 'LINK'
					),
					array(
						'field'       => 'link_text',
						'type'        => sprintf( __( '%s: link text', 'trx_addons' ), $sc ),
						'editor_type' => 'LINE'
					),
				);
	}
}

if ( ! function_exists( 'trx_addons_wpml_elementor_autoload_classes' ) ) {
	/**
	 * Autoload required classes for WPML translation
	 *
	 * @param string $class  Class name
	 */
	function trx_addons_wpml_elementor_autoload_classes( $class ) {
		if (   0 !== strpos( $class, 'WPML_Elementor_Trx_Module_' )
			&& 0 !== strpos( $class, 'WPML_Elementor_Trx_Widget_' )
			&& 0 !== strpos( $class, 'WPML_Elementor_Trx_Sc_' )
			&& 0 !== strpos( $class, 'WPML_Elementor_Trx_Elm_' )
		) {
			return;
		}
		$file = TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'sitepress-multilingual-cms/ate/class-' . trx_addons_esc( str_replace( '_', '-', strtolower( $class ) ) ) . '.php';
		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}
	// Register autoloader
	spl_autoload_register( 'trx_addons_wpml_elementor_autoload_classes' );
}

if ( ! function_exists( 'trx_addons_wpml_elementor_translate_media_widget' ) ) {
	add_filter( 'elementor/frontend/before_render', 'trx_addons_wpml_elementor_translate_media_widget');
	/**
	 * Translate media files in Elementor widgets
	 * 
	 * @param object $element  Elementor element object
	 */
	function trx_addons_wpml_elementor_translate_media_widget( $element ) {
		if ( ! trx_addons_exists_wpml() || ! is_object( $element ) ) {
			return;
		}

		$widgets = array(

			// Addon "AI Helper"
			'trx_sc_agenerator' => array(
				'demo_audio' => array( 'audio' )	// 'audio' is a field of the repeater (array) 'settings[demo_audio]' with the image id and url
			),
			'trx_sc_igenerator' => array(
				'demo_images' => true				// 'demo_images' is a field of array 'settings' with the image id and url
			),
			'trx_sc_mgenerator' => array(
				'demo_music' => array( 'music' )
			),
			'trx_sc_vgenerator' => array(
				'demo_video' => array( 'video' )
			),

			// Addon "Elementor Widgets"
			'trx_elm_advanced_title' => array(
				'content' => array( 'image', 'gallery', 'video' )
			),
			'trx_elm_image_accordion' => array(
				'accordion_items' => array( 'image' )
			),
			'trx_elm_tabs' => array(
				'tabs' => array( 'tabs_image' )
			),
			'trx_elm_team_member' => array(
				'image' => true
			),

			// Layouts shortcodes
			'trx_sc_layouts_logo' => array(
				'logo' => true,
				'logo_retina' => true
			),

			// Shortcodes
			'trx_sc_actions' => array(
				'actions' => array( 'image', 'bg_image' )
			),
			'trx_sc_hotspot' => array(
				'image' => true,
				'spots' => array( 'spot_image', 'image' )
			),
			'trx_sc_icompare' => array(
				'image1' => true,
				'image2' => true
			),
			'trx_sc_price' => array(
				'prices' => array( 'image' )
			),
			'trx_sc_squeeze' => array(
				'slides' => array( 'image' )
			),
			'trx_sc_supertitle' => array(
				'image' => true,
				'items' => array( 'media' )
			),

			// Widgets
			'trx_widget_aboutme' => array(
				'avatar' => true
			),
			'trx_widget_banner' => array(
				'image' => true
			),
			'trx_widget_contacts' => array(
				'logo' => true,
				'logo_retina' => true
			),
			'trx_widget_slider' => array(
				'slides' => array( 'image' )
			),

			// Core Elementor's elements and widgets
			// 'container' => array(
			// 	'bg_slides' => array( 'slide' )
			// ),
			// 'section' => array(
			// 	'bg_slides' => array( 'slide' )
			// ),
		);

		$el_name = $element->get_name();

		if ( ! isset( $widgets[ $el_name ] ) ) {
			return;
		}

		$params = $widgets[ $el_name ];
	
		$settings = $element->get_settings();

		foreach( $params as $prm => $fields ) {
			if ( ! isset( $settings[ $prm ] ) || ! is_array( $settings[ $prm ] ) ) {
				continue;
			}
			$changed = false;
			if ( ! is_array( $fields ) ) {	// Single field
				if ( ! empty( $settings[ $prm ]['id'] ) ) {		// Single media file
					$changed = trx_addons_wpml_elementor_translate_media( $settings[ $prm ] );
				} else if ( is_array( $settings[ $prm ] ) ) {	// May be an array of media files (type ::GALLERY)
					foreach ( $settings[ $prm ] as $k => $v ) {
						if ( ! empty( $v['id'] ) ) {
							$changed = trx_addons_wpml_elementor_translate_media( $settings[ $prm ][ $k ] ) || $changed;
						}
					}
				}
			} else {						// Array of fields ( type ::REPEATER)
				foreach ( $settings[ $prm ] as $key => $value ) {
					foreach ( $fields as $field ) {
						if ( ! empty( $value[ $field ]['id'] ) ) {		// Single media file
							$changed = trx_addons_wpml_elementor_translate_media( $settings[ $prm ][ $key ][ $field ] ) || $changed;
						} else if ( is_array( $value[ $field ] ) ) {	// May be an array of media files (type ::GALLERY)
							foreach ( $value[ $field ] as $k => $v ) {
								if ( ! empty( $v['id'] ) ) {
									$changed = trx_addons_wpml_elementor_translate_media( $settings[ $prm ][ $key ][ $field ][ $k ] ) || $changed;
								}
							}
						}
					}
				}
			}
			if ( $changed ) {
				$element->set_settings( $prm, $settings[ $prm ] );
			}
		}
	}
}

if ( ! function_exists( 'trx_addons_wpml_elementor_translate_media' ) ) {
	/**
	 * Translate a single parameter in Elementor widgets with the media file
	 * 
	 * @param array $media  Media file data. It should contain 'id' and 'url' keys. Passed by reference.
	 * 
	 * @return bool  	  True if the media file was changed, false otherwise.
	 */
	function trx_addons_wpml_elementor_translate_media( &$media ) {
		$changed = false;
		if ( is_array( $media ) && ! empty( $media['id'] ) ) {
			$post_type = get_post_type( $media['id'] );      
			if ( $post_type ) {
				$translated_id = apply_filters( 'wpml_object_id', $media['id'], $post_type, true );
				if ( $translated_id > 0 && $translated_id != $media['id'] ) {
					$media['id'] = $translated_id;
					$media['url'] = wp_get_attachment_url( $translated_id );
					$changed = true;
				}
			}
		}
		return $changed;
	}
}
