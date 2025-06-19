<?php
/**
 * Shortcode: AI Chat Topics (Elementor support)
 *
 * @package ThemeREX Addons
 * @since v2.22.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

use TrxAddons\AiHelper\WidgetGenerator;

// Elementor Widget
//------------------------------------------------------
if ( ! function_exists('trx_addons_sc_chat_topics_add_in_elementor')) {
	add_action( trx_addons_elementor_get_action_for_widgets_registration(), 'trx_addons_sc_chat_topics_add_in_elementor' );
	function trx_addons_sc_chat_topics_add_in_elementor() {

		class TRX_Addons_Elementor_Widget_Chat_Topics extends WidgetGenerator {

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
				$this->add_plain_params([
					'number' => 'size'
				]);
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
				return 'trx_sc_chat_topics';
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
				return __( 'AI Helper Chat Topics', 'trx_addons' );
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
				return [ 'ai', 'helper', 'chat', 'conversation', 'messages', 'history', 'topics', 'ai chat' ];
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
				return 'eicon-text trx_addons_elementor_widget_icon';
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
				$this->before_register_controls();

				$this->register_controls_content_general();

				$this->register_controls_style_topic_list();
				$this->register_controls_style_topic_item();

				$this->after_register_controls();
			}

			/**
			 * Register widget controls: tab 'Content' section 'AI Helper Chat Topics'
			 */
			protected function register_controls_content_general() {

				// Register controls
				$this->start_controls_section(
					'section_sc_chat_topics',
					[
						'label' => __( 'AI Helper Chat Topics', 'trx_addons' ),
					]
				);

				$this->add_control(
					'type',
					[
						'label' => __( 'Layout', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::SELECT,
						'options' => apply_filters('trx_addons_sc_type', array( 'default' => __( 'Default', 'trx_addons' ) ), 'trx_sc_chat_topics'),
						'default' => 'default'
					]
				);

				$this->add_control(
					'number',
					[
						'label' => __( 'Number of topics', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => 5,
							'unit' => 'px'
						],
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 1,
								'max' => apply_filters( 'trx_addons_filter_sc_chat_topics_max', 20 )
							]
						]
					]
				);

				$this->add_control(
					'chat_id',
					[
						'label' => __( 'Chat ID', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::TEXT,
						'default' => ''
					]
				);

				$this->add_control(
					'topics',
					[
						'label' => __( 'Topics', 'trx_addons' ),
						'label_block' => true,
						'type' => \Elementor\Controls_Manager::REPEATER,
						'fields' => apply_filters('trx_addons_sc_param_group_params', [
							[
								'name' => 'topic',
								'label' => __( 'Topic', 'trx_addons' ),
								'label_block' => false,
								'type' => \Elementor\Controls_Manager::TEXT,
								'default' => ''
							],
						], 'trx_sc_chat_topics' ),
						'title_field' => '{{{ topic }}}'
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Topic List'
			 */
			protected function register_controls_style_topic_list() {

				$this->start_controls_section(
					'section_sc_chat_topic_list_style',
					[
						'label' => __( 'Topic List', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'topic_list_typography',
						'selector' => '{{WRAPPER}} .sc_chat_topics_list'
					]
				);

				$this->add_control(
					"topic_list_style",
					[
						'label' => __( 'List Style', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::SELECT,
						'options' => trx_addons_get_list_style_types(),
						'default' => 'none',
						'selectors' => [
							'{{WRAPPER}} .sc_chat_topics_list' => 'list-style-type: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"topic_hide_theme_marker",
					[
						'label' => __( 'Theme markers', 'trx_addons' ),
						'label_on' => __( 'Hide', 'trx_addons' ),
						'label_off' => __( 'Show', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'selectors' => [
							'{{WRAPPER}} .sc_chat_topics_list .sc_chat_topics_item > a:before' => 'display: none;',
						],
						'condition' => [
							'topic_list_style!' => 'none',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'topic_list_background',
						'selector' => '{{WRAPPER}} .sc_chat_topics_list'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'topic_list_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_chat_topics_list',
					)
				);
		
				$this->add_responsive_control(
					'topic_list_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_chat_topics_list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);
		
				$this->add_responsive_control(
					'topic_list_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_chat_topics_list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'topic_list_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_chat_topics_list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
		
				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name'     => 'topic_list_box_shadow',
						'selector' => '{{WRAPPER}} .sc_chat_topics_list',
					]
				);
		
				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Topic Item'
			 */
			protected function register_controls_style_topic_item() {

				$this->start_controls_section(
					'section_sc_chat_topic_item_style',
					[
						'label' => __( 'Topic Item', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE
					]
				);

				$this->start_controls_tabs( 'topic_item_style_tabs' );

				$this->start_controls_tab(
					'topic_item_style_tab_normal',
					[
						'label' => __( 'Normal', 'trx_addons' ),
					]
				);

				$this->add_control(
					'topic_item_color',
					[
						'label' => esc_html__( 'Color', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .sc_chat_topics_item > a' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'topic_item_marker_color',
					[
						'label' => esc_html__( 'Marker Color', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .sc_chat_topics_item::marker' => 'color: {{VALUE}};',
						],
						'condition' => [
							'topic_list_style!' => 'none',
						],
					]
				);
		
				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'topic_item_background',
						'selector' => '{{WRAPPER}} .sc_chat_topics_item'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'topic_item_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_chat_topics_item',
					)
				);
		
				$this->add_responsive_control(
					'topic_item_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_chat_topics_item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Text_Shadow::get_type(),
					array(
						'name'      => 'topic_item_text_shadow',
						'label'     => __( 'Text Shadow', 'trx_addons' ),
						'selector'  => '{{WRAPPER}} .sc_chat_topics_item',
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name'     => 'topic_item_box_shadow',
						'selector' => '{{WRAPPER}} .sc_chat_topics_item',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'topic_item_style_tab_hover',
					[
						'label' => __( 'Hover', 'trx_addons' ),
					]
				);

				$this->add_control(
					'topic_item_color_hover',
					[
						'label' => esc_html__( 'Color', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .sc_chat_topics_item > a:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'topic_item_marker_color_hover',
					[
						'label' => esc_html__( 'Marker Color', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .sc_chat_topics_item:hover::marker' => 'color: {{VALUE}};',
						],
						'condition' => [
							'topic_list_style!' => 'none',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'topic_item_background_hover',
						'selector' => '{{WRAPPER}} .sc_chat_topics_item:hover'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'topic_item_border_hover',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_chat_topics_item:hover',
					)
				);
		
				$this->add_responsive_control(
					'topic_item_border_radius_hover',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_chat_topics_item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Text_Shadow::get_type(),
					array(
						'name'      => 'topic_item_text_shadow_hover',
						'label'     => __( 'Text Shadow', 'trx_addons' ),
						'selector'  => '{{WRAPPER}} .sc_chat_topics_item:hover',
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name'     => 'topic_item_box_shadow_hover',
						'selector' => '{{WRAPPER}} .sc_chat_topics_item:hover',
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->add_responsive_control(
					'topic_item_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'separator'             => 'before',
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_chat_topics_item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'topic_item_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_chat_topics_item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
		
				$this->end_controls_section();
			}

			/**
			 * Render widget's template for the editor.
			 *
			 * Written as a Backbone JavaScript template and used to generate the live preview.
			 *
			 * @since 1.6.41
			 * @access protected
			 */
			protected function content_template() {
				trx_addons_get_template_part(TRX_ADDONS_PLUGIN_ADDONS . 'ai-helper/shortcodes/chat_topics/tpe.chat_topics.php',
										'trx_addons_args_sc_chat_topics',
										array('element' => $this)
									);
			}
		}
		
		// Register widget
		trx_addons_elm_register_widget( 'TRX_Addons_Elementor_Widget_Chat_Topics' );
	}
}
