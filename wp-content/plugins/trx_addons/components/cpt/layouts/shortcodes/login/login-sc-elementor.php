<?php
/**
 * Shortcode: Display Login link (Elementor support)
 *
 * @package ThemeREX Addons
 * @since v1.6.08
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;


// Elementor Widget
//------------------------------------------------------
if (!function_exists('trx_addons_sc_layouts_login_add_in_elementor')) {
	add_action( trx_addons_elementor_get_action_for_widgets_registration(), 'trx_addons_sc_layouts_login_add_in_elementor' );
	function trx_addons_sc_layouts_login_add_in_elementor() {
		
		if (!class_exists('TRX_Addons_Elementor_Layouts_Widget')) return;	

		class TRX_Addons_Elementor_Widget_Layouts_Login extends TRX_Addons_Elementor_Layouts_Widget {

			var $styles_allowed = false;

			/**
			 * Widget base constructor.
			 *
			 * Initializing the widget base class.
			 *
			 * @since 1.6.41
			 * @access public
			 *
			 * @param array      $data Widget data. Default is an empty array.
			 * @param array|null $args Optional. Widget default arguments. Default is null.
			 */
			public function __construct( $data = [], $args = null ) {
				parent::__construct( $data, $args );
				$this->add_plain_params( [
					'login_image' => 'url',
					'logout_image' => 'url',
					'user_menu_image' => 'url',
					'tab_login_image' => 'url',
					'tab_register_image' => 'url',
				] );
				$this->styles_allowed = apply_filters( 'trx_addons_filter_allow_sc_styles_in_elementor', false, 'sc_layouts_login' );
			}

			/**
			 * Retrieve widget name.
			 *
			 * @since 1.6.41
			 * @access public
			 *
			 * @return string Widget name.
			 */
			public function get_name() {
				return 'trx_sc_layouts_login';
			}

			/**
			 * Retrieve widget title.
			 *
			 * @since 1.6.41
			 * @access public
			 *
			 * @return string Widget title.
			 */
			public function get_title() {
				return __( 'Layouts: Login', 'trx_addons' );
			}

			/**
			 * Get widget keywords.
			 *
			 * Retrieve the list of keywords the widget belongs to.
			 *
			 * @since 2.27.2
			 * @access public
			 *
			 * @return array Widget keywords.
			 */
			public function get_keywords() {
				return [ 'login', 'register', 'layouts', 'sign' ];
			}

			/**
			 * Retrieve widget icon.
			 *
			 * @since 1.6.41
			 * @access public
			 *
			 * @return string Widget icon.
			 */
			public function get_icon() {
				return 'eicon-lock-user trx_addons_elementor_widget_icon';
			}

			/**
			 * Retrieve the list of categories the widget belongs to.
			 *
			 * Used to determine where to display the widget in the editor.
			 *
			 * @since 1.6.41
			 * @access public
			 *
			 * @return array Widget categories.
			 */
			public function get_categories() {
				return ['trx_addons-layouts'];
			}

			/**
			 * Register widget controls.
			 *
			 * Adds different input fields to allow the user to change and customize the widget settings.
			 *
			 * @since 1.6.41
			 * @access protected
			 */
			protected function register_controls() {

				$this->register_content_controls();

				if ( $this->styles_allowed ) {
					$this->register_style_controls_link();
					$this->register_style_controls_dropdown();
					$this->register_style_controls_dropdown_items();
					$this->register_style_controls_popup();
					$this->register_style_controls_popup_tabs();
					$this->register_style_controls_popup_close();
					$this->register_style_controls_popup_form();
					$this->register_style_controls_popup_form_fields();
					$this->register_style_controls_popup_form_button();
					$this->register_style_controls_popup_form_message();
				}
			}

			/**
			 * Register content controls.
			 */
			protected function register_content_controls() {

				$this->start_controls_section(
					'section_sc_layouts_login',
					[
						'label' => __( 'Layouts: Login', 'trx_addons' ),
					]
				);

				$this->add_control(
					'type',
					[
						'label' => __( 'Layout', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::SELECT,
						'options' => apply_filters('trx_addons_sc_type', array(
								'default' => esc_html__('Default', 'trx_addons'),
							), 'trx_sc_layouts_login'),
						'default' => 'default'
					]
				);

				$this->add_control(
					'user_menu',
					[
						'label' => __( 'User menu', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::SWITCHER,
						'label_off' => __( 'Hide', 'trx_addons' ),
						'label_on' => __( 'Show', 'trx_addons' ),
						'return_value' => '1'
					]
				);

				$this->add_control(
					'text_login',
					[
						'label' => __( 'Login text', 'trx_addons' ),
						'label_block' => false,
						'description' => __( 'Use "|" to separate lines.', 'trx_addons' ),
						'type' => Controls_Manager::TEXT,
						'placeholder' => __("Login link text", 'trx_addons')
					]
				);

				$this->add_control(
					'text_logout',
					[
						'label' => __( 'Logout text', 'trx_addons' ),
						'label_block' => false,
						'description' => __( 'Use "|" to separate lines, "%s" to insert a current user name.', 'trx_addons' ),
						'type' => Controls_Manager::TEXT,
						'placeholder' => __("Logout link text", 'trx_addons')
					]
				);
				
				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Login Link'
			 */
			protected function register_style_controls_link() {

				$this->start_controls_section(
					'section_sc_login_link_style',
					[
						'label' => __( 'Login Link', 'trx_addons' ),
						'tab' => Controls_Manager::TAB_STYLE
					]
				);

				$this->add_control( 'user_icon_heading',
					[
						'label' => esc_html__( 'User Icon', 'trx_addons' ),
						'type' => Controls_Manager::HEADING,
					]
				);

				$this->add_responsive_control(
					'user_icon_size',
					[
						'label' => __( 'Icon Size', 'trx_addons' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'px'
						],
						'size_units' => [ 'px', 'em', 'rem', 'vh', '%', 'custom' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							],
							'em' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'rem' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'%' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							]
						],
						'selectors' => [
							'{{WRAPPER}} .trx_addons_login_link .sc_layouts_item_icon' => 'font-size: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'user_icon_offset',
					[
						'label' => __( 'Icon Offset', 'trx_addons' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'px'
						],
						'size_units' => [ 'px', 'em', 'rem', 'vh', '%', 'custom' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							],
							'em' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'rem' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'%' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							]
						],
						'selectors' => [
							'{{WRAPPER}} .trx_addons_login_link .sc_layouts_item_icon + .sc_layouts_item_details' => 'margin-left: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_control( 'login_image',
					[
						'label' => esc_html__( 'Login Image', 'trx_addons' ),
						'type' => Controls_Manager::MEDIA,
						'media_types' => [ 'image', 'svg' ],
					]
				);

				$params = trx_addons_get_icon_param( 'login_icon' );
				$params = trx_addons_array_get_first_value( $params );
				unset( $params['name'] );
				$params['label'] = esc_html__( 'Login Icon', 'trx_addons' );
				$params['condition'] = [
					'login_image[url]' => '',
				];
				$this->add_control( 'login_icon', $params );

				$this->add_control( 'logout_image',
					[
						'label' => esc_html__( 'Logout Image', 'trx_addons' ),
						'type' => Controls_Manager::MEDIA,
						'media_types' => [ 'image', 'svg' ],
					]
				);

				$params = trx_addons_get_icon_param( 'logout_icon' );
				$params = trx_addons_array_get_first_value( $params );
				unset( $params['name'] );
				$params['label'] = esc_html__( 'Logout Icon', 'trx_addons' );
				$params['condition'] = [
					'logout_image[url]' => '',
				];
				$this->add_control( 'logout_icon', $params );

				// User Menu Icon
				$this->add_control( 'user_menu_heading',
					[
						'label' => esc_html__( 'User Menu Icon', 'trx_addons' ),
						'type' => Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_responsive_control(
					'user_menu_icon_size',
					[
						'label' => __( 'Icon Size', 'trx_addons' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'px'
						],
						'size_units' => [ 'px', 'em', 'rem', 'vh', '%', 'custom' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							],
							'em' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'rem' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'%' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							]
						],
						'selectors' => [
							'{{WRAPPER}} .trx_addons_login_link:after' => 'font-size: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .trx_addons_login_link .sc_layouts_dropdown_icon' => 'font-size: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'user_menu_icon_offset',
					[
						'label' => __( 'Icon Offset', 'trx_addons' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'px'
						],
						'size_units' => [ 'px', 'em', 'rem', 'vh', '%', 'custom' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							],
							'em' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'rem' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'%' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							]
						],
						'selectors' => [
							'{{WRAPPER}} .sc_layouts_dropdown li.menu-item.menu-item-has-children > a:after,
							 {{WRAPPER}} .sc_layouts_dropdown li.menu-item.menu-item-has-children > a > .sc_layouts_dropdown_icon' => 'margin-left: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_control( 'user_menu_image',
					[
						'label' => esc_html__( 'User Menu Image', 'trx_addons' ),
						'type' => Controls_Manager::MEDIA,
						'media_types' => [ 'svg' ],
					]
				);

				$params = trx_addons_get_icon_param( 'user_menu_icon' );
				$params = trx_addons_array_get_first_value( $params );
				unset( $params['name'] );
				$params['label'] = esc_html__( 'User Menu Icon', 'trx_addons' );
				$params['condition'] = [
					'user_menu' => '1',
					'user_menu_image[url]' => '',
				];
				$this->add_control( 'user_menu_icon', $params );

				$this->start_controls_tabs( 'tabs_sc_login_link_style' );

				$this->start_controls_tab(
					'tabs_sc_login_link_normal',
					[
						'label' => __( 'Normal', 'trx_addons' ),
					]
				);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => 'link_typography',
						'selector' => '{{WRAPPER}} .sc_layouts_item_details_line1, {{WRAPPER}} .sc_layouts_item_details_line2',
						'separator' => 'before',
					]
				);

				$this->add_control(
					"link_icon_color",
					[
						'label' => __( 'Icon Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_login_link .sc_layouts_item_icon' => 'color: {{VALUE}};',
							'{{WRAPPER}} .trx_addons_login_link .sc_layouts_item_icon svg' => 'fill: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"link_line1_color",
					[
						'label' => __( 'Line 1 Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .sc_layouts_item_details_line1' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"link_line2_color",
					[
						'label' => __( 'Line 2 Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .sc_layouts_item_details_line2' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"user_menu_icon_color",
					[
						'label' => __( 'User Menu Icon Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_login_link:after' => 'color: {{VALUE}};',
							'{{WRAPPER}} .trx_addons_login_link .sc_layouts_dropdown_icon' => 'color: {{VALUE}};',
							'{{WRAPPER}} .trx_addons_login_link .sc_layouts_dropdown_icon svg' => 'fill: {{VALUE}};',
						],
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tabs_sc_login_link_hover',
					[
						'label' => __( 'Hover', 'trx_addons' ),
					]
				);

				$this->add_control(
					"link_icon_hover",
					[
						'label' => __( 'Icon Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_login_link:hover .sc_layouts_item_icon' => 'color: {{VALUE}};',
							'{{WRAPPER}} .trx_addons_login_link:hover .sc_layouts_item_icon svg' => 'fill: {{VALUE}};',
							'{{WRAPPER}} .trx_addons_login_link:focus .sc_layouts_item_icon' => 'color: {{VALUE}};',
							'{{WRAPPER}} .trx_addons_login_link:focus .sc_layouts_item_icon svg' => 'fill: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"link_line1_hover",
					[
						'label' => __( 'Line 1 Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_login_link:hover .sc_layouts_item_details_line1' => 'color: {{VALUE}};',
							'{{WRAPPER}} .trx_addons_login_link:focus .sc_layouts_item_details_line1' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"link_line2_hover",
					[
						'label' => __( 'Line 2 Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_login_link:hover .sc_layouts_item_details_line2' => 'color: {{VALUE}};',
							'{{WRAPPER}} .trx_addons_login_link:focus .sc_layouts_item_details_line2' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"user_menu_icon_hover",
					[
						'label' => __( 'User Menu Icon Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_login_link:hover:after' => 'color: {{VALUE}};',
							'{{WRAPPER}} .trx_addons_login_link:hover .sc_layouts_dropdown_icon' => 'color: {{VALUE}};',
							'{{WRAPPER}} .trx_addons_login_link:hover .sc_layouts_dropdown_icon svg' => 'fill: {{VALUE}};',
							'{{WRAPPER}} .trx_addons_login_link:focus:after' => 'color: {{VALUE}};',
							'{{WRAPPER}} .trx_addons_login_link:focus .sc_layouts_dropdown_icon' => 'color: {{VALUE}};',
							'{{WRAPPER}} .trx_addons_login_link:focus .sc_layouts_dropdown_icon svg' => 'fill: {{VALUE}};',
						],
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'User Menu'
			 */
			protected function register_style_controls_dropdown() {

				$this->start_controls_section(
					'section_sc_login_dropdown_style',
					[
						'label' => __( 'User Menu', 'trx_addons' ),
						'tab' => Controls_Manager::TAB_STYLE
					]
				);
		
				$this->add_control(
					'dropdown_show_in_editor',
					array(
						'label'      => __( 'Always Show in Editor', 'trx_addons' ),
						'type'       => Controls_Manager::SWITCHER,
						'selectors'  => array(
										// 'body.elementor-editor-active.single-cpt_layouts {{WRAPPER}} .menu-item > ul' => 'display: block !important;',
										'body.elementor-editor-active {{WRAPPER}} .menu-item > ul' => 'display: block !important;',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => 'dropdown_background',
						'selector' => '{{WRAPPER}} .menu-item > ul'
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					array(
						'name'        => 'dropdown_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .menu-item > ul',
					)
				);
		
				$this->add_responsive_control(
					'dropdown_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .menu-item > ul' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'dropdown_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .menu-item > ul',
					]
				);

				$this->add_responsive_control(
					'dropdown_padding',
					[
						'label'      => esc_html__( 'Padding', 'trx_addons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => [
							'{{WRAPPER}} .menu-item > ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'tags_dropdown_margin',
					[
						'label'      => esc_html__( 'Margin', 'trx_addons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => [
							'{{WRAPPER}} .menu-item > ul' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'User Menu Items'
			 */
			protected function register_style_controls_dropdown_items() {

				$this->start_controls_section(
					'section_sc_login_dropdown_items_style',
					[
						'label' => __( 'User Menu Items', 'trx_addons' ),
						'tab' => Controls_Manager::TAB_STYLE
					]
				);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => 'dropdown_typography',
						'selector' => '{{WRAPPER}} .menu-item > ul > li > a'
					]
				);

				$this->add_responsive_control(
					'dropdown_items_icon_size',
					[
						'label' => __( 'Icon Size', 'trx_addons' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'px'
						],
						'size_units' => [ 'px', 'em', 'rem', 'vh', '%', 'custom' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							],
							'em' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'rem' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'%' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							]
						],
						'selectors' => [
							'{{WRAPPER}} .menu-item > ul > .menu-item:before' => 'font-size: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'dropdown_items_icon_offset',
					[
						'label' => __( 'Icon Offset', 'trx_addons' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'px'
						],
						'size_units' => [ 'px', 'em', 'rem', 'vh', '%', 'custom' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							],
							'em' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'rem' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'%' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							]
						],
						'selectors' => [
							'{{WRAPPER}} .menu-item > ul > .menu-item:before' => 'left: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->start_controls_tabs( 'tabs_sc_layouts_link_dropdown_items_style' );

				$this->start_controls_tab(
					'tab_sc_layouts_link_dropdown_items_normal',
					[
						'label' => __( 'Normal', 'trx_addons' ),
					]
				);

				$this->add_control(
					"dropdown_items_icon_color",
					[
						'label' => __( 'Icon Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .menu-item > ul > .menu-item:before' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"dropdown_items_color",
					[
						'label' => __( 'Text Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .menu-item > ul > .menu-item > a' => 'color: {{VALUE}} !important;',
						],
					]
				);

				$this->add_control(
					"dropdown_items_delimiter_color",
					[
						'label' => __( 'Delimiter Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .menu-item > ul > .menu-item.menu-delimiter' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => 'dropdown_items_background',
						'selector' => '{{WRAPPER}} .menu-item > ul > .menu-item > a'
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					array(
						'name'        => 'dropdown_items_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .menu-item > ul > .menu-item > a',
					)
				);
		
				$this->add_responsive_control(
					'dropdown_items_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .menu-item > ul > .menu-item > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'dropdown_items_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .menu-item > ul > .menu-item > a',
					]
				);

				$this->add_responsive_control(
					'dropdown_items_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .menu-item > ul > .menu-item > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
						'separator'             => 'before',
					]
				);

				$this->add_responsive_control(
					'dropdown_items_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .menu-item > ul > .menu-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_layouts_link_dropdown_items_hover',
					[
						'label' => __( 'Hover', 'trx_addons' ),
					]
				);

				$this->add_control(
					"dropdown_items_icon_hover",
					[
						'label' => __( 'Icon Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .menu-item > ul > .menu-item:hover:before,
							 {{WRAPPER}} .menu-item > ul > .menu-item:focus:before' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"dropdown_items_hover",
					[
						'label' => __( 'Text Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .menu-item > ul > .menu-item > a:hover,
							 {{WRAPPER}} .menu-item > ul > .menu-item > a:focus' => 'color: {{VALUE}} !important;',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => 'dropdown_items_background_hover',
						'selector' => '{{WRAPPER}} .menu-item > ul > .menu-item > a:hover,
										{{WRAPPER}} .menu-item > ul > .menu-item > a:focus'
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					array(
						'name'        => 'dropdown_items_border_hover',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .menu-item > ul > .menu-item > a:hover,
											{{WRAPPER}} .menu-item > ul > .menu-item > a:focus',
					)
				);
		
				$this->add_responsive_control(
					'dropdown_items_border_radius_hover',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .menu-item > ul > .menu-item > a:hover,
										 {{WRAPPER}} .menu-item > ul > .menu-item > a:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'dropdown_items_shadow_hover',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .menu-item > ul > .menu-item > a:hover,
										{{WRAPPER}} .menu-item > ul > .menu-item > a:focus',
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Popup Login/Register'
			 */
			protected function register_style_controls_popup() {

				$this->start_controls_section(
					'section_sc_login_popup_style',
					[
						'label' => __( 'Popup Login/Register', 'trx_addons' ),
						'tab' => Controls_Manager::TAB_STYLE
					]
				);
		
				$this->add_control(
					'popup_show_in_editor',
					array(
						'label'      => __( 'Always Show in Editor', 'trx_addons' ),
						'type'       => Controls_Manager::SWITCHER,
						'selectors'  => array(
										// 'body.elementor-editor-active.single-cpt_layouts #trx_addons_login_popup' => 'display: block !important; position: fixed; z-index: 9999; top: 50%; left: 50%; transform: translate(-50%, -50%);',
										'body.elementor-editor-active #trx_addons_login_popup' => 'display: block !important; position: fixed; z-index: 9999; top: 50%; left: 50%; transform: translate(-50%, -50%);',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => 'popup_background',
						'selector' => '#trx_addons_login_popup'
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					array(
						'name'        => 'popup_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '#trx_addons_login_popup',
					)
				);
		
				$this->add_responsive_control(
					'popup_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'#trx_addons_login_popup' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
						),
					)
				);

				$this->add_responsive_control(
					'popup_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'#trx_addons_login_popup' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
						'separator'             => 'before',
					]
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'popup_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '#trx_addons_login_popup',
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Popup Tabs'
			 */
			protected function register_style_controls_popup_tabs() {

				$this->start_controls_section(
					'section_sc_login_popup_tabs_style',
					[
						'label' => __( 'Popup Tabs', 'trx_addons' ),
						'tab' => Controls_Manager::TAB_STYLE
					]
				);

				$this->add_control(
					'tabs_heading',
					array(
						'label'      => __( 'Tabs', 'trx_addons' ),
						'type'       => Controls_Manager::HEADING,
					)
				);

				$this->add_responsive_control(
					'tabs_height',
					[
						'label' => __( 'Height', 'trx_addons' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'px'
						],
						'size_units' => [ 'px', 'em', 'rem', 'vh', '%', 'custom' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							],
							'em' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'rem' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'%' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							]
						],
						'selectors' => [
							'#trx_addons_login_popup .trx_addons_tabs_title > a' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
							'#trx_addons_login_popup .trx_addons_tabs_titles' => 'margin-right: {{SIZE}}{{UNIT}};',
							'#trx_addons_login_popup button.mfp-close' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
						],
						'separator' => 'before',
					]
				);

				$this->add_responsive_control(
					'tabs_gap',
					[
						'label' => __( 'Gap', 'trx_addons' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'px'
						],
						'size_units' => [ 'px', 'em', 'rem', 'vh', '%', 'custom' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							],
							'em' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'rem' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'%' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							]
						],
						'selectors' => [
							'#trx_addons_login_popup .trx_addons_tabs_title' => 'width: calc( 50% - {{SIZE}}{{UNIT}} ); margin-right: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'tabs_margin',
					array(
						'label'      => __( 'Margin', 'trx_addons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
											'#trx_addons_login_popup .trx_addons_tabs_titles' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						),
					)
				);

				$this->add_control(
					'tab_icons_notice',
					[
						'heading' => __( 'Tab Icons', 'trx_addons' ),
						'content' => __( 'The new icons can only be seen on the frontend after reloading the page.', 'trx_addons' ),
						'type' => Controls_Manager::NOTICE,
						'notice_type' => 'info',
						'icon' => true,
						'dissmissible' => false,
					]
				);

				$this->add_responsive_control(
					'tab_icon_size',
					[
						'label' => __( 'Icon Size', 'trx_addons' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'px'
						],
						'size_units' => [ 'px', 'em', 'rem', 'vh', '%', 'custom' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							],
							'em' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'rem' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'%' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							]
						],
						'selectors' => [
							'#trx_addons_login_popup .trx_addons_tabs_titles li.trx_addons_tabs_title > a > i' => 'font-size: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_control( 'tab_login_image',
					[
						'label' => esc_html__( 'Login Image', 'trx_addons' ),
						'type' => Controls_Manager::MEDIA,
						'media_types' => [ 'image', 'svg' ],
					]
				);

				$params = trx_addons_get_icon_param( 'tab_login_icon' );
				$params = trx_addons_array_get_first_value( $params );
				unset( $params['name'] );
				$params['label'] = esc_html__( 'Login Icon', 'trx_addons' );
				$params['condition'] = [
					'tab_login_image[url]' => '',
				];
				$this->add_control( 'tab_login_icon', $params );


				$this->add_control( 'tab_register_image',
					[
						'label' => esc_html__( 'Register Image', 'trx_addons' ),
						'type' => Controls_Manager::MEDIA,
						'media_types' => [ 'image', 'svg' ],
					]
				);

				$params = trx_addons_get_icon_param( 'tab_register_icon' );
				$params = trx_addons_array_get_first_value( $params );
				unset( $params['name'] );
				$params['label'] = __( 'Register Icon', 'trx_addons' );
				$params['condition'] = [
					'tab_register_image[url]' => '',
				];
				$this->add_control( 'tab_register_icon', $params );

				$this->add_responsive_control(
					'tab_icons_margin',
					array(
						'label'      => __( 'Icon Margin', 'trx_addons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'#trx_addons_login_popup .trx_addons_tabs_titles li.trx_addons_tabs_title > a > i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_control(
					'tab_heading',
					array(
						'label'      => __( 'Single Tab', 'trx_addons' ),
						'type'       => Controls_Manager::HEADING,
						'separator'  => 'before',
					)
				);

				$this->start_controls_tabs( 'tabs_sc_login_popup_tabs_style' );

				$this->start_controls_tab(
					'tab_sc_login_popup_tabs_normal',
					[
						'label' => __( 'Normal', 'trx_addons' ),
					]
				);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => 'tab_typography',
						'selector' => '#trx_addons_login_popup .trx_addons_tabs_title > a'
					]
				);

				$this->add_control(
					"tab_icon_color",
					[
						'label' => __( 'Icon Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'#trx_addons_login_popup .trx_addons_tabs_title > a > i' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"tab_text_color",
					[
						'label' => __( 'Text Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'#trx_addons_login_popup .trx_addons_tabs_title > a' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => 'tab_background',
						'selector' => '#trx_addons_login_popup .trx_addons_tabs_title'
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					array(
						'name'        => 'tab_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '#trx_addons_login_popup .trx_addons_tabs_title',
					)
				);
		
				$this->add_responsive_control(
					'tab_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'#trx_addons_login_popup .trx_addons_tabs_title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'tab_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '#trx_addons_login_popup .trx_addons_tabs_title',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_login_popup_tabs_hover',
					[
						'label' => __( 'Hover', 'trx_addons' ),
					]
				);

				$this->add_control(
					"tab_icon_hover",
					[
						'label' => __( 'Icon Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'#trx_addons_login_popup .trx_addons_tabs_title:not(.ui-tabs-active) > a:hover > i' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"tab_text_hover",
					[
						'label' => __( 'Text Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'#trx_addons_login_popup .trx_addons_tabs_title:not(.ui-tabs-active) > a:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => 'tab_background_hover',
						'selector' => '#trx_addons_login_popup .trx_addons_tabs_title:not(.ui-tabs-active):hover'
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					array(
						'name'        => 'tab_border_hover',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '#trx_addons_login_popup .trx_addons_tabs_title:not(.ui-tabs-active):hover',
					)
				);
		
				$this->add_responsive_control(
					'tab_border_radius_hover',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'#trx_addons_login_popup .trx_addons_tabs_title:not(.ui-tabs-active):hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'tab_shadow_hover',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '#trx_addons_login_popup .trx_addons_tabs_title:not(.ui-tabs-active):hover',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_login_popup_tabs_active',
					[
						'label' => __( 'Active', 'trx_addons' ),
					]
				);

				$this->add_control(
					"tab_icon_active",
					[
						'label' => __( 'Icon Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'#trx_addons_login_popup .trx_addons_tabs_title.ui-tabs-active > a > i' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"tab_text_active",
					[
						'label' => __( 'Text Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'#trx_addons_login_popup .trx_addons_tabs_title.ui-tabs-active > a' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => 'tab_background_active',
						'selector' => '#trx_addons_login_popup .trx_addons_tabs_title.ui-tabs-active'
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					array(
						'name'        => 'tab_border_active',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '#trx_addons_login_popup .trx_addons_tabs_title.ui-tabs-active',
					)
				);

				$this->add_responsive_control(
					'tab_border_radius_active',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'#trx_addons_login_popup .trx_addons_tabs_title.ui-tabs-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'tab_shadow_active',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '#trx_addons_login_popup .trx_addons_tabs_title.ui-tabs-active',
					]
				);

				$this->add_control(
					"tab_line_heading",
					[
						'label' => __( 'Line Over the Active Tab', 'trx_addons' ),
						'type' => Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_control(
					"tab_line_active",
					[
						'label' => __( 'Line Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'#trx_addons_login_popup .trx_addons_tabs_title.ui-tabs-active:after' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'tab_line_height_active',
					[
						'label' => __( 'Line Height', 'trx_addons' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'px'
						],
						'size_units' => [ 'px', 'em', 'rem', 'vh', '%', 'custom' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							],
							'em' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'rem' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'%' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							]
						],
						'selectors' => [
							'#trx_addons_login_popup .trx_addons_tabs_title.ui-tabs-active:after' => 'height: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'tab_line_offset_active',
					[
						'label' => __( 'Line Offset', 'trx_addons' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'px'
						],
						'size_units' => [ 'px', 'em', 'rem', 'vh', '%', 'custom' ],
						'range' => [
							'px' => [
								'min' => -100,
								'max' => 100,
								'step' => 1
							],
							'em' => [
								'min' => -10,
								'max' => 10,
								'step' => 0.1
							],
							'rem' => [
								'min' => -10,
								'max' => 10,
								'step' => 0.1
							],
							'%' => [
								'min' => -100,
								'max' => 100,
								'step' => 1
							]
						],
						'selectors' => [
							'#trx_addons_login_popup .trx_addons_tabs_title.ui-tabs-active:after' => 'top: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Popup Close'
			 */
			protected function register_style_controls_popup_close() {

				$this->start_controls_section(
					'section_sc_login_popup_close_style',
					[
						'label' => __( 'Popup Close', 'trx_addons' ),
						'tab' => Controls_Manager::TAB_STYLE
					]
				);

				$this->start_controls_tabs( 'tabs_sc_login_popup_close_style' );

				$this->start_controls_tab(
					'tab_sc_login_popup_close_normal',
					[
						'label' => __( 'Normal', 'trx_addons' ),
					]
				);

				$this->add_control(
					"close_color",
					[
						'label' => __( 'Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'#trx_addons_login_popup button.mfp-close .mfp-close-icon:before,
							 #trx_addons_login_popup button.mfp-close .mfp-close-icon:after' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => 'close_background',
						'selector' => '#trx_addons_login_popup button.mfp-close'
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					array(
						'name'        => 'close_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '#trx_addons_login_popup button.mfp-close',
					)
				);
		
				$this->add_responsive_control(
					'close_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'#trx_addons_login_popup button.mfp-close' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'close_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '#trx_addons_login_popup button.mfp-close',
					]
				);

				$this->add_responsive_control(
					'close_opacity',
					[
						'label' => __( 'Opacity', 'trx_addons' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'px'
						],
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 1,
								'step' => 0.01
							],
						],
						'selectors' => [
							'#trx_addons_login_popup button.mfp-close' => 'opacity: {{SIZE}};',
						],
					]
				);

				$this->add_responsive_control(
					'close_height',
					[
						'label' => __( 'Height', 'trx_addons' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'px'
						],
						'size_units' => [ 'px', 'em', 'rem', 'vh', '%', 'custom' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							],
							'em' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'rem' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'%' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							]
						],
						'selectors' => [
							'#trx_addons_login_popup button.mfp-close' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
						],
						'separator' => 'before',
					]
				);

				$this->add_responsive_control(
					'close_margin',
					array(
						'label'      => __( 'Margin', 'trx_addons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
											'#trx_addons_login_popup button.mfp-close' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						),
					)
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_login_popup_close_hover',
					[
						'label' => __( 'Hover', 'trx_addons' ),
					]
				);

				$this->add_control(
					"close_hover",
					[
						'label' => __( 'Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'#trx_addons_login_popup button.mfp-close:hover .mfp-close-icon:before,
							 #trx_addons_login_popup button.mfp-close:hover .mfp-close-icon:after' => 'border-color: {{VALUE}};',
							'#trx_addons_login_popup button.mfp-close:focus .mfp-close-icon:before,
							 #trx_addons_login_popup button.mfp-close:focus .mfp-close-icon:after' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => 'close_background_hover',
						'selector' => '#trx_addons_login_popup button.mfp-close:hover,
										#trx_addons_login_popup button.mfp-close:focus'
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					array(
						'name'        => 'close_border_hover',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '#trx_addons_login_popup button.mfp-close:hover,
											#trx_addons_login_popup button.mfp-close:focus',
					)
				);

				$this->add_responsive_control(
					'close_border_radius_hover',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
											'#trx_addons_login_popup button.mfp-close:hover,
											 #trx_addons_login_popup button.mfp-close:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'close_shadow_hover',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '#trx_addons_login_popup button.mfp-close:hover,
										#trx_addons_login_popup button.mfp-close:focus',
					]
				);

				$this->add_responsive_control(
					'close_opacity_hover',
					[
						'label' => __( 'Opacity', 'trx_addons' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'px'
						],
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 1,
								'step' => 0.01
							],
						],
						'selectors' => [
							'#trx_addons_login_popup button.mfp-close:hover,
							 #trx_addons_login_popup button.mfp-close:focus' => 'opacity: {{SIZE}};',
						],
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Popup Form'
			 */
			protected function register_style_controls_popup_form() {

				$this->start_controls_section(
					'section_sc_login_popup_form_style',
					[
						'label' => __( 'Popup Form', 'trx_addons' ),
						'tab' => Controls_Manager::TAB_STYLE
					]
				);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => 'popup_form_background',
						'selector' => '#trx_addons_login_popup .trx_addons_tabs_content'
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					array(
						'name'        => 'popup_form_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '#trx_addons_login_popup .trx_addons_tabs_content',
					)
				);
		
				$this->add_responsive_control(
					'popup_form_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'#trx_addons_login_popup .trx_addons_tabs_content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
						),
					)
				);

				$this->add_responsive_control(
					'popup_form_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'#trx_addons_login_popup .trx_addons_tabs_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
						'separator'             => 'before',
					]
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'popup_form_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '#trx_addons_login_popup .trx_addons_tabs_content',
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Popup Fields'
			 */
			protected function register_style_controls_popup_form_fields() {

				$this->start_controls_section(
					'section_sc_login_popup_fields_style',
					[
						'label' => __( 'Popup Fields', 'trx_addons' ),
						'tab' => Controls_Manager::TAB_STYLE
					]
				);

				$this->add_control(
					'popup_icons_notice',
					[
						'heading' => __( 'Field Icons', 'trx_addons' ),
						'content' => __( 'The new icons can only be seen on the frontend after reloading the page', 'trx_addons' ),
						'type' => Controls_Manager::NOTICE,
						'notice_type' => 'info',
						'icon' => true,
						'dissmissible' => false,
					]
				);

				if ( ! $this->styles_allowed ) {
					$params = trx_addons_get_icon_param( 'field_login_icon' );
					$params = trx_addons_array_get_first_value( $params );
					unset( $params['name'] );
					$params['label'] = __( 'Login Icon', 'trx_addons' );
					$this->add_control( 'field_login_icon', $params );

					$params = trx_addons_get_icon_param( 'field_password_icon' );
					$params = trx_addons_array_get_first_value( $params );
					unset( $params['name'] );
					$params['label'] = __( 'Password Icon', 'trx_addons' );
					$this->add_control( 'field_password_icon', $params );

					$params = trx_addons_get_icon_param( 'field_email_icon' );
					$params = trx_addons_array_get_first_value( $params );
					unset( $params['name'] );
					$params['label'] = __( 'Email Icon', 'trx_addons' );
					$this->add_control( 'field_email_icon', $params );
				}

				$this->add_responsive_control(
					'popup_fields_spacing',
					[
						'label' => __( 'Fields Spacing', 'trx_addons' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'em'
						],
						'size_units' => [ 'px', 'em', 'rem', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							],
							'em' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'rem' => [
								'min' => 0,
								'max' => 10,
								'step' => 0.1
							],
							'%' => [
								'min' => 0,
								'max' => 100,
								'step' => 1
							]
						],
						'selectors' => [
							'#trx_addons_login_popup .trx_addons_popup_form_field .sc_form_field' => 'margin-bottom: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => 'popup_fields_typography',
						'selector' => '#trx_addons_login_popup input[type="text"],
										#trx_addons_login_popup input[type="password"],
										#trx_addons_login_popup .sc_form_field_hover'
					]
				);

				$this->start_controls_tabs( 'tabs_sc_login_popup_fields_style' );

				$this->start_controls_tab(
					'tabs_sc_login_popup_fields_normal',
					[
						'label' => __( 'Normal', 'trx_addons' ),
					]
				);

				$this->add_control(
					"popup_fields_text_color",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'#trx_addons_login_popup input[type="text"],
							 #trx_addons_login_popup input[type="password"],
							 #trx_addons_login_popup .sc_form_field_icon' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"popup_fields_placeholder_color",
					[
						'label' => __( 'Placeholder color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'#trx_addons_login_popup input[type="text"]::placeholder' => 'color: {{VALUE}};',
							'#trx_addons_login_popup input[type="text"]::-moz-placeholder' => 'color: {{VALUE}};',
							'#trx_addons_login_popup input[type="text"]::-webkit-input-placeholder' => 'color: {{VALUE}};',
							'#trx_addons_login_popup input[type="password"]::placeholder' => 'color: {{VALUE}};',
							'#trx_addons_login_popup input[type="password"]::-moz-placeholder' => 'color: {{VALUE}};',
							'#trx_addons_login_popup input[type="password"]::-webkit-input-placeholder' => 'color: {{VALUE}};',
							'#trx_addons_login_popup .sc_form_field_content' => 'color: {{VALUE}};',
						],
					]
				);

				if ( ! $this->styles_allowed ) {
					$this->add_control(
						"popup_fields_icon_color",
						[
							'label' => __( 'Icon color', 'trx_addons' ),
							'label_block' => false,
							'type' => Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'#trx_addons_login_popup .sc_form_field_icon' => 'color: {{VALUE}};',
							],
						]
					);
				}
		
				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => 'popup_fields_background',
						'selector' => '#trx_addons_login_popup input[type="text"],
										#trx_addons_login_popup input[type="password"]',
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					array(
						'name'        => 'popup_fields_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '#trx_addons_login_popup input[type="text"],
										  #trx_addons_login_popup input[type="password"]',
					)
				);
		
				$this->add_responsive_control(
					'popup_fields_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'#trx_addons_login_popup input[type="text"],
										 #trx_addons_login_popup input[type="password"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
					)
				);

				$this->add_responsive_control(
					'popup_fields_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
										'#trx_addons_login_popup input[type="text"],
										 #trx_addons_login_popup input[type="password"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'popup_fields_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '#trx_addons_login_popup input[type="text"],
									   #trx_addons_login_popup input[type="password"]',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tabs_sc_login_popup_fields_focus',
					[
						'label' => __( 'Focused', 'trx_addons' ),
					]
				);

				$this->add_control(
					"popup_fields_text_color_focus",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'#trx_addons_login_popup input[type="text"]:focus,
							 #trx_addons_login_popup input[type="password"]:focus,
							 #trx_addons_login_popup input:focus ~ .sc_form_field_hover .sc_form_field_icon' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"popup_fields_placeholder_color_focus",
					[
						'label' => __( 'Placeholder color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'#trx_addons_login_popup input[type="text"]:focus::placeholder' => 'color: {{VALUE}};',
							'#trx_addons_login_popup input[type="text"]:focus::-moz-placeholder' => 'color: {{VALUE}};',
							'#trx_addons_login_popup input[type="text"]:focus::-webkit-input-placeholder' => 'color: {{VALUE}};',
							'#trx_addons_login_popup input[type="password"]:focus::placeholder' => 'color: {{VALUE}};',
							'#trx_addons_login_popup input[type="password"]:focus::-moz-placeholder' => 'color: {{VALUE}};',
							'#trx_addons_login_popup input[type="password"]:focus::-webkit-input-placeholder' => 'color: {{VALUE}};',
							'#trx_addons_login_popup input:focus ~ .sc_form_field_hover .sc_form_field_content' => 'color: {{VALUE}};',
						],
					]
				);

				if ( ! $this->styles_allowed ) {
					$this->add_control(
						"popup_fields_icon_color_focus",
						[
							'label' => __( 'Icon color', 'trx_addons' ),
							'label_block' => false,
							'type' => Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'#trx_addons_login_popup input:focus ~ .sc_form_field_hover .sc_form_field_icon' => 'color: {{VALUE}};',
							],
						]
					);
				}
		
				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => 'popup_fields_background_focus',
						'selector' => '#trx_addons_login_popup input[type="text"]:focus,
									   #trx_addons_login_popup input[type="password"]:focus',
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					array(
						'name'        => 'popup_fields_border_focus',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '#trx_addons_login_popup input[type="text"]:focus,
									      #trx_addons_login_popup input[type="password"]:focus',
					)
				);
		
				$this->add_responsive_control(
					'popup_fields_border_radius_focus',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'#trx_addons_login_popup input[type="text"]:focus,
									     #trx_addons_login_popup input[type="password"]:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
					)
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'popup_fields_shadow_focus',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '#trx_addons_login_popup input[type="text"]:focus,
									   #trx_addons_login_popup input[type="password"]:focus',
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->add_control(
					"popup_checkboxes_heading",
					[
						'label' => __( 'Checkboxes', 'trx_addons' ),
						'type' => Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => 'popup_checkboxes_typography',
						'selector' => '#trx_addons_login_popup input[type="checkbox"] + label,
									   #trx_addons_login_popup .trx_addons_popup_form_field a'
					]
				);

				if ( ! $this->styles_allowed ) {
					$this->add_control(
						"popup_checkboxes_check_color",
						[
							'label' => __( 'Check Color', 'trx_addons' ),
							'label_block' => false,
							'type' => Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'#trx_addons_login_popup input[type="checkbox"] + label:before' => 'color: {{VALUE}};',
							],
						]
					);
				}

				$this->add_control(
					"popup_checkboxes_check_bg_color",
					[
						'label' => __( 'Unchecked Background', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'#trx_addons_login_popup input[type="checkbox"] + label:before' => 'background: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"popup_checkboxes_check_bg_color_checked",
					[
						'label' => __( 'Checked Background', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'#trx_addons_login_popup input[type="checkbox"]:checked + label:before' => 'background: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"popup_checkboxes_border_color",
					[
						'label' => __( 'Border Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'#trx_addons_login_popup input[type="checkbox"] + label:before' => 'border-color: {{VALUE}} !important;',
						],
					]
				);

				$this->add_responsive_control(
					'popup_checkboxes_border_radius',
					[
						'label'                 => esc_html__( 'Border Radius', 'trx_addons' ),
						'type'                  => Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'#trx_addons_login_popup input[type="checkbox"] + label:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					"popup_checkboxes_text_color",
					[
						'label' => __( 'Text Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'#trx_addons_login_popup input[type="checkbox"] + label' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"popup_checkboxes_link_color",
					[
						'label' => __( 'Link Color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'#trx_addons_login_popup .trx_addons_popup_form_field a' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"popup_checkboxes_link_hover",
					[
						'label' => __( 'Link Hover', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'#trx_addons_login_popup .trx_addons_popup_form_field a:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'popup_checkboxes_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'#trx_addons_login_popup input[type="checkbox"] + label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Button "Sign In/Up"'
			 */
			protected function register_style_controls_popup_form_button() {

				$this->start_controls_section(
					'section_sc_login_popup_button_style',
					[
						'label' => __( 'Popup Button', 'trx_addons' ),
						'tab' => Controls_Manager::TAB_STYLE
					]
				);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => 'button_typography',
						'selector' => '#trx_addons_login_popup .submit_button'
					]
				);

				$this->start_controls_tabs( 'tabs_sc_login_popup_button_style' );

				$this->start_controls_tab(
					'tabs_sc_login_popup_button_normal',
					[
						'label' => __( 'Normal', 'trx_addons' ),
					]
				);

				$this->add_control(
					"button_text_color",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'#trx_addons_login_popup .submit_button' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => 'button_background',
						'selector' => '#trx_addons_login_popup .submit_button'
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					array(
						'name'        => 'button_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '#trx_addons_login_popup .submit_button',
					)
				);
		
				$this->add_responsive_control(
					'button_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'#trx_addons_login_popup .submit_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'button_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '#trx_addons_login_popup .submit_button',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tabs_sc_login_popup_button_hover',
					[
						'label' => __( 'Hover', 'trx_addons' ),
					]
				);

				$this->add_control(
					"button_text_color_hover",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'#trx_addons_login_popup .submit_button:hover,
							 #trx_addons_login_popup .submit_button:focus' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => 'button_background_hover',
						'selector' => '#trx_addons_login_popup .submit_button:hover,
										#trx_addons_login_popup .submit_button:focus'
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					array(
						'name'        => 'button_border_hover',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '#trx_addons_login_popup .submit_button:hover,
										#trx_addons_login_popup .submit_button:focus',
					)
				);
		
				$this->add_responsive_control(
					'button_border_radius_hover',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'#trx_addons_login_popup .submit_button:hover,
										#trx_addons_login_popup .submit_button:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'button_shadow_hover',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '#trx_addons_login_popup .submit_button:hover,
										#trx_addons_login_popup .submit_button:focus',
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Popup Message'
			 */
			protected function register_style_controls_popup_form_message() {

				$this->start_controls_section(
					'section_sc_login_popup_message_style',
					[
						'label' => __( 'Popup Message', 'trx_addons' ),
						'tab' => Controls_Manager::TAB_STYLE
					]
				);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => 'message_typography',
						'selector' => '#trx_addons_login_popup .trx_addons_message_box'
					]
				);

				$this->start_controls_tabs( 'tabs_sc_login_popup_message_style' );

				$this->start_controls_tab(
					'tabs_sc_login_popup_message_success',
					[
						'label' => __( 'Success', 'trx_addons' ),
					]
				);

				$this->add_control(
					"message_success_text_color",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'#trx_addons_login_popup .trx_addons_message_box_success' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => 'message_success_background',
						'selector' => '#trx_addons_login_popup .trx_addons_message_box_success'
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					array(
						'name'        => 'message_success_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '#trx_addons_login_popup .trx_addons_message_box_success',
					)
				);
		
				$this->add_responsive_control(
					'message_success_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'#trx_addons_login_popup .trx_addons_message_box_success' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'message_success_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '#trx_addons_login_popup .trx_addons_message_box_success',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tabs_sc_login_popup_message_error',
					[
						'label' => __( 'Error', 'trx_addons' ),
					]
				);
				$this->add_control(
					"message_error_text_color",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'#trx_addons_login_popup .trx_addons_message_box_error' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => 'message_error_background',
						'selector' => '#trx_addons_login_popup .trx_addons_message_box_error'
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					array(
						'name'        => 'message_error_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '#trx_addons_login_popup .trx_addons_message_box_error',
					)
				);
		
				$this->add_responsive_control(
					'message_error_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'#trx_addons_login_popup .trx_addons_message_box_error' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'message_error_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '#trx_addons_login_popup .trx_addons_message_box_error',
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->end_controls_section();
			}

		}
		
		// Register widget
		trx_addons_elm_register_widget( 'TRX_Addons_Elementor_Widget_Layouts_Login' );
	}
}
