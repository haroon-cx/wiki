<?php
/**
 * Abstract class for Elementor Widgets with AI Helper Generators
 *
 * @package ThemeREX Addons
 * @since v2.34.8
 */

 namespace TrxAddons\AiHelper;

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! class_exists( 'WidgetGenerator' ) && class_exists( 'TRX_Addons_Elementor_Widget' ) ) {

	abstract class WidgetGenerator extends \TRX_Addons_Elementor_Widget {

		var $is_edit_mode = false;
		var $slug = '';

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
			$this->slug = $this->get_slug();
		}

		/**
		 * Return a widget slug from the widget name. For example, if the widget name is 'trx_sc_vgenerator',
		 * the slug will be 'vgenerator'.
		 *
		 * Used to make a widget-specific parameter name.
		 *
		 * @since 1.6.41
		 * @access public
		 *
		 * @return string  Widget slug.
		 */
		public function get_slug() {
			return str_replace( 'trx_sc_', '', $this->get_name() );
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
			return ['trx_addons-elements'];
		}

		/**
		 * Before register widget controls.
		 *
		 * @since 2.34.8
		 * @access protected
		 */
		protected function before_register_controls() {
			$this->is_edit_mode = trx_addons_elm_is_edit_mode();
		}

		/**
		 * After register widget controls.
		 *
		 * @since 2.34.8
		 * @access protected
		 */
		protected function after_register_controls() {
			if ( apply_filters( 'trx_addons_filter_add_title_param', true, $this->get_name() ) ) {
				$this->add_title_param();
			}
		}

		/*-----------------------------------------------------------------------------------*/
		/*	TAB "STYLE"
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Register widget controls: tab 'Style' section 'Content Area'
		 */
		protected function register_controls_style_sc_content() {

			$this->start_controls_section(
				'sc_content_style_section',
				[
					'label' => __( 'Content Area', 'trx_addons' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'sc_content_background',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_content'
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				array(
					'name'        => 'sc_content_border',
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .sc_' . $this->slug . '_content',
				)
			);
	
			$this->add_responsive_control(
				'sc_content_border_radius',
				array(
					'label'      => __( 'Border Radius', 'trx_addons' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'  => array(
									'{{WRAPPER}} .sc_' . $this->slug . '_content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'sc_content_padding',
				[
					'label'                 => esc_html__( 'Padding', 'trx_addons' ),
					'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'             => [
						'{{WRAPPER}} .sc_' . $this->slug . '_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'sc_content_margin',
				[
					'label'                 => esc_html__( 'Margin', 'trx_addons' ),
					'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'             => [
						'{{WRAPPER}} .sc_' . $this->slug . '_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
	
			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'sc_content_box_shadow',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_content',
				]
			);
	
			$this->end_controls_section();
		}

		/**
		 * Register widget controls: tab 'Style' section 'Form'
		 */
		protected function register_controls_style_sc_form() {

			$this->start_controls_section(
				'sс_form_style_section',
				[
					'label' => __( 'Form', 'trx_addons' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'sc_form_background',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form'
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				array(
					'name'        => 'sc_form_border',
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .sc_' . $this->slug . '_form',
				)
			);
	
			$this->add_responsive_control(
				'sc_form_border_radius',
				array(
					'label'      => __( 'Border Radius', 'trx_addons' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'  => array(
									'{{WRAPPER}} .sc_' . $this->slug . '_form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
	
			$this->add_responsive_control(
				'sc_form_padding',
				[
					'label'                 => esc_html__( 'Padding', 'trx_addons' ),
					'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'             => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'sc_form_margin',
				[
					'label'                 => esc_html__( 'Margin', 'trx_addons' ),
					'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'             => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
	
			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'sc_form_box_shadow',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form',
				]
			);

			$this->end_controls_section();
		}

		/**
		 * Register widget controls: tab 'Style' section 'Button Generate'
		 */
		protected function register_controls_style_button_generate() {

			$this->start_controls_section(
				'sc_button_generate_style_section',
				[
					'label' => __( 'Button "Generate"', 'trx_addons' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'button_typography',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button,
									{{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button'
				]
			);

			$this->add_control( 'button_image',
				[
					'label' => esc_html__( 'Image', 'trx_addons' ),
					'type' => \Elementor\Controls_Manager::MEDIA,
					'media_types' => [ 'image', 'svg' ],
				]
			);

			$params = trx_addons_get_icon_param( 'button_icon' );
			$params = trx_addons_array_get_first_value( $params );
			unset( $params['name'] );
			$params['condition'] = [
				'button_image[url]' => '',
			];
			$this->add_control( 'button_icon', $params );

			$this->add_responsive_control(
				'button_icon_size',
				[
					'label' => __( 'Icon Size', 'trx_addons' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
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
						'{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button .sc_' . $this->slug . '_form_field_prompt_button_icon,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button .sc_' . $this->slug . '_form_field_prompt_button_svg,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button .sc_' . $this->slug . '_form_field_prompt_button_image,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button .sc_button_icon' => 'font-size: {{SIZE}}{{UNIT}};',
					],
					'conditions' => array(
						'relation' => 'or',
						'terms'    => array(
							array(
								'name'     => 'button_icon',
								'operator' => '!==',
								'value'    => array( '', 'none' ),
							),
							array(
								'name'     => 'button_image[url]',
								'operator' => '!==',
								'value'    => '',
							),
						),
					),
				]
			);

			$this->add_responsive_control(
				'button_icon_margin',
				[
					'label'                 => esc_html__( 'Icon Margin', 'trx_addons' ),
					'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'             => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button .sc_' . $this->slug . '_form_field_prompt_button_icon,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button .sc_' . $this->slug . '_form_field_prompt_button_svg,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button .sc_' . $this->slug . '_form_field_prompt_button_image,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button .sc_button_icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->start_controls_tabs( 'sc_button_generate_style_tabs' );

			$this->start_controls_tab(
				'sc_button_generate_tab_normal',
				[
					'label' => __( 'Normal', 'trx_addons' ),
				]
			);

			$this->add_control(
				"button_text_color",
				[
					'label' => __( 'Text color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				"button_icon_color",
				[
					'label' => __( 'Icon color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button .sc_' . $this->slug . '_form_field_prompt_button_icon,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button .sc_button_icon' => 'color: {{VALUE}};',
						'{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button .sc_' . $this->slug . '_form_field_prompt_button_svg svg,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button .sc_button_icon svg' => 'fill: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'button_background',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button'
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				array(
					'name'        => 'button_border',
					'label'       => __( 'Border', 'trx_addons' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button,
									  {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button',
				)
			);
	
			$this->add_responsive_control(
				'button_border_radius',
				array(
					'label'      => __( 'Border Radius', 'trx_addons' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'  => array(
									'{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button,
									 {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'button_padding',
				[
					'label'                 => esc_html__( 'Padding', 'trx_addons' ),
					'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'             => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'button_shadow',
					'label' => esc_html__( 'Shadow', 'trx_addons' ),
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button',
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'sc_button_generate_tab_hover',
				[
					'label' => __( 'Hover', 'trx_addons' ),
				]
			);

			$this->add_control(
				"button_text_color_hover",
				[
					'label' => __( 'Text color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):hover,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):focus,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):hover,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):focus' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				"button_icon_color_hover",
				[
					'label' => __( 'Icon color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):hover .sc_' . $this->slug . '_form_field_prompt_button_icon,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):hover .sc_button_icon' => 'color: {{VALUE}};',
						'{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):focus .sc_' . $this->slug . '_form_field_prompt_button_icon,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):focus .sc_button_icon' => 'color: {{VALUE}};',
						'{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):hover .sc_' . $this->slug . '_form_field_prompt_button_svg svg,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):hover .sc_button_icon svg' => 'fill: {{VALUE}};',
						'{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):focus .sc_' . $this->slug . '_form_field_prompt_button_svg svg,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):focus .sc_button_icon svg' => 'fill: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'button_background_hover',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):hover,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):focus,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):hover,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):focus'
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				array(
					'name'        => 'button_border_hover',
					'label'       => __( 'Border', 'trx_addons' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):hover,
									  {{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):focus,
									  {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):hover,
									  {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):focus',
				)
			);
	
			$this->add_responsive_control(
				'button_border_radius_hover',
				array(
					'label'      => __( 'Border Radius', 'trx_addons' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'  => array(
									'{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):hover,
									 {{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):focus,
									 {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):hover,
									 {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'button_shadow_hover',
					'label' => esc_html__( 'Shadow', 'trx_addons' ),
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):hover,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):focus,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):hover,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button:not(.sc_' . $this->slug . '_form_field_prompt_button_disabled):focus',
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'sc_button_generate_tab_disabled',
				[
					'label' => __( 'Disabled', 'trx_addons' ),
				]
			);

			$this->add_control(
				"button_text_color_disabled",
				[
					'label' => __( 'Text color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button.sc_' . $this->slug . '_form_field_prompt_button_disabled,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button.sc_' . $this->slug . '_form_field_prompt_button_disabled' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				"button_icon_color_disabled",
				[
					'label' => __( 'Icon color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button.sc_' . $this->slug . '_form_field_prompt_button_disabled .sc_' . $this->slug . '_form_field_prompt_button_icon,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button.sc_' . $this->slug . '_form_field_prompt_button_disabled .sc_button_icon' => 'color: {{VALUE}};',
						'{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button.sc_' . $this->slug . '_form_field_prompt_button_disabled .sc_' . $this->slug . '_form_field_prompt_button_svg svg,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button.sc_' . $this->slug . '_form_field_prompt_button_disabled .sc_button_icon svg' => 'fill: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'button_background_disabled',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button.sc_' . $this->slug . '_form_field_prompt_button_disabled,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button.sc_' . $this->slug . '_form_field_prompt_button_disabled'
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				array(
					'name'        => 'button_border_disabled',
					'label'       => __( 'Border', 'trx_addons' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button.sc_' . $this->slug . '_form_field_prompt_button_disabled,
									  {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button.sc_' . $this->slug . '_form_field_prompt_button_disabled',
				)
			);
	
			$this->add_responsive_control(
				'button_border_radius_disabled',
				array(
					'label'      => __( 'Border Radius', 'trx_addons' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'  => array(
									'{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button.sc_' . $this->slug . '_form_field_prompt_button_disabled,
									 {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button.sc_' . $this->slug . '_form_field_prompt_button_disabled' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'button_shadow_disabled',
					'label' => esc_html__( 'Shadow', 'trx_addons' ),
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button.sc_' . $this->slug . '_form_field_prompt_button_disabled,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button.sc_' . $this->slug . '_form_field_prompt_button_disabled',
				]
			);

			$this->add_control(
				'button_opacity_disabled',
				[
					'label' => __( 'Opacity', 'trx_addons' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
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
						]
					],
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_field_prompt_button.sc_' . $this->slug . '_form_field_prompt_button_disabled,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_field_generate_button.sc_' . $this->slug . '_form_field_prompt_button_disabled' => 'opacity: {{SIZE}};',
					],
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->end_controls_section();
		}

		/**
		 * Register widget controls: tab 'Style' section 'Popup Settings'
		 */
		protected function register_controls_style_settings_popup() {

			$this->start_controls_section(
				'sc_settings_popup_style_section',
				[
					'label' => __( 'Popup "Settings"', 'trx_addons' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
					'condition' => [
						'show_settings' => '1'
					]
				]
			);

			$this->add_responsive_control(
				'settings_popup_width',
				[
					'label' => __( 'Width', 'trx_addons' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'default' => [
						'size' => '',
						'unit' => 'em'
					],
					'size_units' => [ 'px', 'em', 'rem', '%', 'vw', 'vh', 'custom' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1
						],
						'em' => [
							'min' => 0,
							'max' => 100,
							'step' => 0.1
						],
						'rem' => [
							'min' => 0,
							'max' => 100,
							'step' => 0.1
						],
						'%' => [
							'min' => 0,
							'max' => 100,
							'step' => 1
						]
					],
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_settings' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%;',
					],
				]
			);

			$this->add_responsive_control(
				'settings_popup_height',
				[
					'label' => __( 'Height', 'trx_addons' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'default' => [
						'size' => '',
						'unit' => 'em'
					],
					'size_units' => [ 'px', 'em', 'rem', '%', 'vw', 'vh', 'custom' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1
						],
						'em' => [
							'min' => 0,
							'max' => 100,
							'step' => 0.1
						],
						'rem' => [
							'min' => 0,
							'max' => 100,
							'step' => 0.1
						],
						'%' => [
							'min' => 0,
							'max' => 100,
							'step' => 1
						]
					],
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_settings' => 'max-height: {{SIZE}}{{UNIT}}; overflow-y: auto;',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'settings_popup_background',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_settings'
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				array(
					'name'        => 'settings_popup_border',
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .sc_' . $this->slug . '_form_settings',
				)
			);
	
			$this->add_responsive_control(
				'settings_popup_border_radius',
				array(
					'label'      => __( 'Border Radius', 'trx_addons' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'  => array(
									'{{WRAPPER}} .sc_' . $this->slug . '_form_settings' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
	
			$this->add_responsive_control(
				'settings_popup_padding',
				[
					'label'                 => esc_html__( 'Padding', 'trx_addons' ),
					'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'             => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_settings' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'settings_popup_margin',
				[
					'label'                 => esc_html__( 'Margin', 'trx_addons' ),
					'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'             => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_settings' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
	
			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'settings_popup_box_shadow',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_settings',
				]
			);

			$this->add_control(
				"settings_popup_scrollbar_color",
				[
					'label' => __( 'Scrollbar Color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_settings' => 'scrollbar-color: {{VALUE}} {{settings_popup_scrollbar_slider.VALUE}};',
					],
				]
			);

			$this->add_control(
				"settings_popup_scrollbar_slider",
				[
					'label' => __( 'Scrollbar Slider', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_settings' => 'scrollbar-color: {{settings_popup_scrollbar_color.VALUE}} {{VALUE}};',
					],
				]
			);

			$this->end_controls_section();
		}

		/**
		 * Register widget controls: tab 'Style' section 'Field Settings'
		 */
		protected function register_controls_style_settings_field( $group_settings = false) {

			$this->start_controls_section(
				'sc_settings_field_style_section',
				[
					'label' => __( 'Fields in "Settings"', 'trx_addons' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE
				]
			);

			$this->add_responsive_control(
				'settings_fields_spacing',
				[
					'label' => __( 'Fields Spacing', 'trx_addons' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
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
						'{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field + .sc_' . $this->slug . '_form_settings_field:not(.sc_' . $this->slug . '_form_settings_field_group_title)' => 'margin-top: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'settings_field_typography',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[type="radio"] + label,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[type="checkbox"] + label,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field select,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field .select_container'
				]
			);

			$this->start_controls_tabs( 'sc_form_settings_field_style_tabs' );

			$this->start_controls_tab(
				'sc_form_settings_field_style_tab_normal',
				[
					'label' => __( 'Normal', 'trx_addons' ),
				]
			);

			$this->add_control(
				"settings_field_text_color",
				[
					'label' => __( 'Text Color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[type="radio"] + label:before,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[type="checkbox"] + label:before,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field .select_container:after,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field select,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field select option,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field select optgroup,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field .sc_' . $this->slug . '_form_settings_field_numeric_wrap_button:before' => 'color: {{VALUE}};',
						// Additional rule to override the select field background with !important
						// '{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field .select_container select' => 'background-color: {{settings_field_background_color.VALUE}} !important;',
					],
				]
			);

			$this->add_control(
				"settings_field_placeholder_color",
				[
					'label' => __( 'Placeholder color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[placeholder]::placeholder' => 'color: {{VALUE}};',
						'{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[placeholder]::-moz-placeholder' => 'color: {{VALUE}};',
						'{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[placeholder]::-webkit-input-placeholder' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'settings_field_background',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[type="radio"] + label:before,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[type="checkbox"] + label:before,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field select,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field select option,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field select optgroup,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field .select_container:before',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				array(
					'name'        => 'settings_field_border',
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input,
									  {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[type="radio"] + label:before,
									  {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[type="checkbox"] + label:before,
									  {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field select',
				)
			);
	
			$this->add_responsive_control(
				'settings_field_border_radius',
				array(
					'label'      => __( 'Border Radius', 'trx_addons' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'  => array(
									'{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input,
									 {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[type="checkbox"] + label:before,
									 {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field select,
									 {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field .select_container,
									 {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field .select_container:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								),
				)
			);

			$this->add_responsive_control(
				'settings_field_padding',
				[
					'label'                 => esc_html__( 'Padding', 'trx_addons' ),
					'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'             => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'settings_field_shadow',
					'label' => esc_html__( 'Shadow', 'trx_addons' ),
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field :not(.select_container) > select,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field .select_container',
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'sc_form_settings_field_style_tab_focus',
				[
					'label' => __( 'Focus', 'trx_addons' ),
				]
			);

			$this->add_control(
				"settings_field_text_color_focus",
				[
					'label' => __( 'Text color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input:focus,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[type="radio"]:focus + label:before,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[type="checkbox"]:focus + label:before,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field select:focus,
						 {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input:focus + .sc_' . $this->slug . '_form_settings_field_numeric_wrap_buttons .sc_' . $this->slug . '_form_settings_field_numeric_wrap_button:before' => 'color: {{VALUE}};',
						// Additional rule to override the select field background with !important
						// '{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field .select_container select:focus' => 'background-color: {{settings_field_background_color.VALUE}} !important;',
					],
				]
			);

			$this->add_control(
				"settings_field_placeholder_color_focus",
				[
					'label' => __( 'Placeholder color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[placeholder]:focus::placeholder' => 'color: {{VALUE}};',
						'{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[placeholder]:focus::-moz-placeholder' => 'color: {{VALUE}};',
						'{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[placeholder]:focus::-webkit-input-placeholder' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'settings_field_background_focus',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input:focus,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[type="radio"]:focus + label:before,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[type="checkbox"]:focus + label:before,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field select:focus',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				array(
					'name'        => 'settings_field_border_focus',
					'label'       => __( 'Border', 'trx_addons' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input:focus,
									  {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[type="radio"]:focus + label:before,
									  {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[type="checkbox"]:focus + label:before,
									  {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field select:focus',
				)
			);
	
			$this->add_responsive_control(
				'settings_field_border_radius_focus',
				array(
					'label'      => __( 'Border Radius', 'trx_addons' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'  => array(
									'{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input:focus,
									 {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[type="radio"]:focus + label:before,
									 {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input[type="checkbox"]:focus + label:before,
									 {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field select:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								),
				)
			);

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'settings_field_shadow_focus',
					'label' => esc_html__( 'Shadow', 'trx_addons' ),
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field input:focus,
								   {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field select:focus',
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			// Group Label
			if ( $group_settings ) {
				$this->add_control(
					"settings_group_label_heading",
					[
						'label' => __( 'Group Label', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'settings_group_label_typography',
						'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field_group_title,
									   {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field select optgroup'
					]
				);

				$this->add_control(
					"settings_group_label_color",
					[
						'label' => __( 'Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field_group_title,
							 {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field select optgroup' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'settings_group_label_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field_group_title,
							 {{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field select optgroup' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
			}

			// Field Label
			$this->add_control(
				"settings_field_label_heading",
				[
					'label' => __( 'Fields Label', 'trx_addons' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'settings_field_label_typography',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field label'
				]
			);

			$this->add_control(
				"settings_field_label_color",
				[
					'label' => __( 'Color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field label' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'settings_field_label_margin',
				[
					'label'                 => esc_html__( 'Margin', 'trx_addons' ),
					'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'             => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			// Field Description
			$this->add_control(
				"settings_field_description_heading",
				[
					'label' => __( 'Fields Description', 'trx_addons' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'settings_field_description_typography',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field_description'
				]
			);

			$this->add_control(
				"settings_field_description_color",
				[
					'label' => __( 'Color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field_description' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'settings_field_description_margin',
				[
					'label'                 => esc_html__( 'Margin', 'trx_addons' ),
					'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'             => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_settings .sc_' . $this->slug . '_form_settings_field_description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->end_controls_section();
		}

		/**
		 * Register widget controls: tab 'Style' section 'Tags'
		 */
		protected function register_controls_style_tags() {

			$this->start_controls_section(
				'sc_tags_style_section',
				[
					'label' => __( 'Tags', 'trx_addons' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE
				]
			);

			$this->add_responsive_control(
				'tags_field_spacing',
				[
					'label' => __( 'Space Before', 'trx_addons' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'default' => [
						'size' => '',
						'unit' => 'em'
					],
					'size_units' => [ 'px', 'em', 'rem', '%', 'vw', 'vh', 'custom' ],
					'range' => [
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
					],
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . ' .sc_' . $this->slug . '_form_field_tags' => 'margin-top: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'tag_label_heading',
				[
					'label' => __( 'Label', 'trx_addons' ),
					'type' => \Elementor\Controls_Manager::HEADING,
				]
			);

			$this->add_responsive_control(
				'tag_label_offset',
				[
					'label' => __( 'Vertical Offset', 'trx_addons' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'default' => [
						'size' => '',
						'unit' => 'px'
					],
					'size_units' => [ 'px', 'em', 'rem', '%', 'vh' ],
					'range' => [
						'px' => [
							'min' => -50,
							'max' => 50
						],
						'%' => [
							'min' => -50,
							'max' => 50
						],
						'em' => [
							'min' => -2,
							'max' => 2,
							'step' => 0.1
						],
						'rem' => [
							'min' => -2,
							'max' => 2,
							'step' => 0.1
						],
					],
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_field_tags_label' => 'top: {{SIZE}}{{UNIT}}; position: relative;',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'tags_label_typography',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_field_tags_label'
				]
			);

			$this->add_control(
				"tags_label_color",
				[
					'label' => __( 'Color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_field_tags_label' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'tags_label_padding',
				[
					'label'                 => esc_html__( 'Padding', 'trx_addons' ),
					'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'             => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_field_tags_label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'tag_items_heading',
				[
					'label' => __( 'Items', 'trx_addons' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'tag_typography',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_field_tags_item'
				]
			);

			$this->start_controls_tabs( 'sc_tag_style_tabs' );

			$this->start_controls_tab(
				'sc_tag_style_normal',
				[
					'label' => __( 'Normal', 'trx_addons' ),
				]
			);

			$this->add_control(
				"tag_text_color",
				[
					'label' => __( 'Text Color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_field_tags_item' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'tag_background',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_field_tags_item'
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				array(
					'name'        => 'tag_border',
					'label'       => __( 'Border', 'trx_addons' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .sc_' . $this->slug . '_form_field_tags_item',
				)
			);
	
			$this->add_responsive_control(
				'tag_border_radius',
				array(
					'label'      => __( 'Border Radius', 'trx_addons' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'  => array(
									'{{WRAPPER}} .sc_' . $this->slug . '_form_field_tags_item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'tag_shadow',
					'label' => esc_html__( 'Shadow', 'trx_addons' ),
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_field_tags_item',
				]
			);

			$this->add_responsive_control(
				'tag_padding',
				[
					'label'                 => esc_html__( 'Padding', 'trx_addons' ),
					'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'             => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_field_tags_item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator'             => 'before',
				]
			);

			$this->add_responsive_control(
				'tag_margin',
				[
					'label'                 => esc_html__( 'Margin', 'trx_addons' ),
					'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'             => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_field_tags_item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'sc_tag_style_hover',
				[
					'label' => __( 'Hover', 'trx_addons' ),
				]
			);

			$this->add_control(
				"tag_text_color_hover",
				[
					'label' => __( 'Text color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_form_field_tags_item:hover' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'tag_background_hover',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_field_tags_item:hover'
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				array(
					'name'        => 'tag_border_hover',
					'label'       => __( 'Border', 'trx_addons' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .sc_' . $this->slug . '_form_field_tags_item:hover',
				)
			);
	
			$this->add_responsive_control(
				'tag_border_radius_hover',
				array(
					'label'      => __( 'Border Radius', 'trx_addons' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'  => array(
									'{{WRAPPER}} .sc_' . $this->slug . '_form_field_tags_item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'tag_shadow_hover',
					'label' => esc_html__( 'Shadow', 'trx_addons' ),
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_form_field_tags_item:hover',
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->end_controls_section();
		}

		/**
		 * Register widget controls: tab 'Style' section 'Limits'
		 */
		protected function register_controls_style_limits( $with_spacing = false ) {

			$this->start_controls_section(
				'sc_limits_style_section',
				[
					'label' => __( 'Limits', 'trx_addons' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
					'condition' => [
						'show_limits' => '1'
					]
				]
			);

			if ( $with_spacing ) {
				$this->add_responsive_control(
					'limits_field_spacing',
					[
						'label' => __( 'Space Before', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'em'
						],
						'size_units' => [ 'px', 'em', 'rem', '%', 'vw', 'vh', 'custom' ],
						'range' => [
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
						],
						'selectors' => [
							'{{WRAPPER}} .sc_' . $this->slug . ' .sc_' . $this->slug . '_limits' => 'margin-top: {{SIZE}}{{UNIT}};',
						],
					]
				);
			}

			$this->add_responsive_control(
				'limits_width',
				[
					'label' => __( 'Limits width (in %)', 'trx_addons' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'default' => [
						'size' => '',
						'unit' => 'px'
					],
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 50,
							'max' => 100
						]
					],
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_limits' => 'width: {{SIZE}}%;',
					]
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'limits_typography',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_limits'
				]
			);

			$this->add_control(
				"limits_text_color",
				[
					'label' => __( 'Text Color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_limits' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'limits_background',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_limits'
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				array(
					'name'        => 'limits_border',
					'label'       => __( 'Border', 'trx_addons' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .sc_' . $this->slug . '_limits',
				)
			);
	
			$this->add_responsive_control(
				'limits_border_radius',
				array(
					'label'      => __( 'Border Radius', 'trx_addons' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'  => array(
									'{{WRAPPER}} .sc_' . $this->slug . '_limits' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'limits_shadow',
					'label' => esc_html__( 'Shadow', 'trx_addons' ),
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_limits',
				]
			);

			$this->add_responsive_control(
				'limits_padding',
				[
					'label'                 => esc_html__( 'Padding', 'trx_addons' ),
					'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'             => [
						'{{WRAPPER}} .sc_' . $this->slug . '_limits' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			if ( ! $with_spacing ) {
				$this->add_responsive_control(
					'limits_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_' . $this->slug . '_limits' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
			}

			$this->add_control(
				"limits_values_heading",
				[
					'label' => __( 'Limit Values', 'trx_addons' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'limits_values_typography',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_limits_total_value,
									{{WRAPPER}} .sc_' . $this->slug . '_limits_total_requests,
									{{WRAPPER}} .sc_' . $this->slug . '_limits_used_value,
									{{WRAPPER}} .sc_' . $this->slug . '_limits_used_requests'
				]
			);

			$this->add_control(
				"limits_values_color",
				[
					'label' => __( 'Text Color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					// 'global' => array(
					// 	'active' => false,
					// ),
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_limits_total_value,
							{{WRAPPER}} .sc_' . $this->slug . '_limits_total_requests,
							{{WRAPPER}} .sc_' . $this->slug . '_limits_used_value,
							{{WRAPPER}} .sc_' . $this->slug . '_limits_used_requests' => 'color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_section();
		}

		/**
		 * Register widget controls: tab 'Style' section 'Message' (for warning messages).
		 */
		protected function register_controls_style_message() {

			$this->start_controls_section(
				'message_section',
				[
					'label' => __( 'Message', 'trx_addons' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);

			$this->start_controls_tabs( 'message_tabs' );

			$this->start_controls_tab(
				'message_tab_warning',
				[
					'label' => __( 'Warning', 'trx_addons' ),
				]
			);

			$this->add_control(
				"message_heading",
				[
					'label' => __( 'Message Area', 'trx_addons' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'message_background',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_message'
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'message_border',
					'label'       => __( 'Border', 'trx_addons' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .sc_' . $this->slug . '_message',
				)
			);
	
			$this->add_responsive_control(
				'message_border_radius',
				array(
					'label'      => __( 'Border Radius', 'trx_addons' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'  => array(
									'{{WRAPPER}} .sc_' . $this->slug . '_message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'message_padding',
				[
					'label'                 => esc_html__( 'Padding', 'trx_addons' ),
					'type'                  => Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'             => [
						'{{WRAPPER}} .sc_' . $this->slug . '_message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'message_margin',
				[
					'label'                 => esc_html__( 'Margin', 'trx_addons' ),
					'type'                  => Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'             => [
						'{{WRAPPER}} .sc_' . $this->slug . '_message' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'message_shadow',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_message',
				]
			);

			$this->add_control(
				"message_close_heading",
				[
					'label' => __( 'Button "Close"', 'trx_addons' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);

			$this->add_control(
				"message_close_color",
				[
					'label' => __( 'Close Color', 'trx_addons' ),
					'label_block' => false,
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .trx_addons_button_close .trx_addons_button_close_icon:before,
							{{WRAPPER}} .trx_addons_button_close .trx_addons_button_close_icon:after' => 'border-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				"message_close_hover",
				[
					'label' => __( 'Close Hover', 'trx_addons' ),
					'label_block' => false,
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .trx_addons_button_close:hover .trx_addons_button_close_icon:before,
							{{WRAPPER}} .trx_addons_button_close:hover .trx_addons_button_close_icon:after' => 'border-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				"message_header_heading",
				[
					'label' => __( 'Header', 'trx_addons' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);
			
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'message_header_typography',
					'label' => __( 'Header Typography', 'trx_addons' ),
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_message h5'
				]
			);

			$this->add_control(
				"message_header_color",
				[
					'label' => __( 'Header Color', 'trx_addons' ),
					'label_block' => false,
					'type' => Controls_Manager::COLOR,
					'default' => '',
					// 'global' => array(
					// 	'active' => false,
					// ),
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_message h5' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				"message_text_heading",
				[
					'label' => __( 'Text', 'trx_addons' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'message_typography',
					'label' => __( 'Text Typography', 'trx_addons' ),
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_message p'
				]
			);

			$this->add_control(
				"message_text_color",
				[
					'label' => __( 'Text Color', 'trx_addons' ),
					'label_block' => false,
					'type' => Controls_Manager::COLOR,
					'default' => '',
					// 'global' => array(
					// 	'active' => false,
					// ),
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_message p' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				"message_link_heading",
				[
					'label' => __( 'Link (normal state)', 'trx_addons' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'message_link_typography',
					'label' => __( 'Typography', 'trx_addons' ),
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_message_inner a'
				]
			);

			$this->add_control(
				"message_link_color",
				[
					'label' => __( 'Color', 'trx_addons' ),
					'label_block' => false,
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_message_inner a' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'message_link_background',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_message_inner a'
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'message_link_border',
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .sc_' . $this->slug . '_message_inner a',
				)
			);

			$this->add_responsive_control(
				'message_link_border_radius',
				array(
					'label'      => __( 'Border Radius', 'trx_addons' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'  => array(
									'{{WRAPPER}} .sc_' . $this->slug . '_message_inner a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'message_link_padding',
				[
					'label'                 => esc_html__( 'Padding', 'trx_addons' ),
					'type'                  => Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'             => [
						'{{WRAPPER}} .sc_' . $this->slug . '_message_inner a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'message_link_margin',
				[
					'label'                 => esc_html__( 'Margin', 'trx_addons' ),
					'type'                  => Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'             => [
						'{{WRAPPER}} .sc_' . $this->slug . '_message_inner a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'message_link_shadow',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_message_inner a',
				]
			);

			$this->add_control(
				"message_link_hover_heading",
				[
					'label' => __( 'Link (hovered state)', 'trx_addons' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);

			$this->add_control(
				"message_link_hover",
				[
					'label' => __( 'Color', 'trx_addons' ),
					'label_block' => false,
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_message_inner a:hover,
							{{WRAPPER}} .sc_' . $this->slug . '_message_inner a:focus' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'message_link_background_hover',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_message_inner a:hover,
									{{WRAPPER}} .sc_' . $this->slug . '_message_inner a:focus'
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'message_link_border_hover',
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .sc_' . $this->slug . '_message_inner a:hover,
									{{WRAPPER}} .sc_' . $this->slug . '_message_inner a:focus',
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'message_link_shadow_hover',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_message_inner a:hover,
									{{WRAPPER}} .sc_' . $this->slug . '_message_inner a:focus',
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'message_tab_info',
				[
					'label' => __( 'Info', 'trx_addons' ),
				]
			);

			$this->add_control(
				"message_heading_info",
				[
					'label' => __( 'Message Area', 'trx_addons' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'message_background_info',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_message_type_info'
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'message_border_info',
					'label'       => __( 'Border', 'trx_addons' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .sc_' . $this->slug . '_message_type_info',
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'message_shadow_info',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_message_type_info',
				]
			);

			$this->add_control(
				"message_close_heading_info",
				[
					'label' => __( 'Button "Close"', 'trx_addons' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);

			$this->add_control(
				"message_close_color_info",
				[
					'label' => __( 'Close Color', 'trx_addons' ),
					'label_block' => false,
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_message_type_info .trx_addons_button_close .trx_addons_button_close_icon:before,
							{{WRAPPER}} .sc_' . $this->slug . '_message_type_info .trx_addons_button_close .trx_addons_button_close_icon:after' => 'border-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				"message_close_hover_info",
				[
					'label' => __( 'Close Hover', 'trx_addons' ),
					'label_block' => false,
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_message_type_info .trx_addons_button_close:hover .trx_addons_button_close_icon:before,
							{{WRAPPER}} .sc_' . $this->slug . '_message_type_info .trx_addons_button_close:hover .trx_addons_button_close_icon:after' => 'border-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				"message_header_heading_info",
				[
					'label' => __( 'Header', 'trx_addons' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);

			$this->add_control(
				"message_header_color_info",
				[
					'label' => __( 'Header Color', 'trx_addons' ),
					'label_block' => false,
					'type' => Controls_Manager::COLOR,
					'default' => '',
					// 'global' => array(
					// 	'active' => false,
					// ),
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_message_type_info h5' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				"message_text_heading_info",
				[
					'label' => __( 'Text', 'trx_addons' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);

			$this->add_control(
				"message_text_color_info",
				[
					'label' => __( 'Text Color', 'trx_addons' ),
					'label_block' => false,
					'type' => Controls_Manager::COLOR,
					'default' => '',
					// 'global' => array(
					// 	'active' => false,
					// ),
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_message_type_info p' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				"message_link_heading_info",
				[
					'label' => __( 'Link (normal state)', 'trx_addons' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);

			$this->add_control(
				"message_link_color_info",
				[
					'label' => __( 'Color', 'trx_addons' ),
					'label_block' => false,
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_message_type_info .sc_' . $this->slug . '_message_inner a' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'message_link_background_info',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_message_type_info .sc_' . $this->slug . '_message_inner a'
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'message_link_border_info',
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .sc_' . $this->slug . '_message_type_info .sc_' . $this->slug . '_message_inner a',
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'message_link_shadow_info',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_message_type_info .sc_' . $this->slug . '_message_inner a',
				]
			);

			$this->add_control(
				"message_link_heading_hover_info",
				[
					'label' => __( 'Link (hovered state)', 'trx_addons' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);

			$this->add_control(
				"message_link_hover_info",
				[
					'label' => __( 'Color', 'trx_addons' ),
					'label_block' => false,
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_message_type_info .sc_' . $this->slug . '_message_inner a:hover,
							{{WRAPPER}} .sc_' . $this->slug . '_message_type_info .sc_' . $this->slug . '_message_inner a:focus' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'message_link_background_hover_info',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_message_type_info .sc_' . $this->slug . '_message_inner a:hover,
									{{WRAPPER}} .sc_' . $this->slug . '_message_type_info .sc_' . $this->slug . '_message_inner a:focus'
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'message_link_border_hover_info',
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .sc_' . $this->slug . '_message_type_info .sc_' . $this->slug . '_message_inner a:hover,
									{{WRAPPER}} .sc_' . $this->slug . '_message_type_info .sc_' . $this->slug . '_message_inner a:focus',
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'message_link_shadow_hover_info',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_message_type_info .sc_' . $this->slug . '_message_inner a:hover,
									{{WRAPPER}} .sc_' . $this->slug . '_message_type_info .sc_' . $this->slug . '_message_inner a:focus',
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->end_controls_section();
		}

		/**
		 * Register widget controls: tab 'Style' section 'Media Player'
		 */
		protected function register_controls_style_media_player( $prefix ) {

			$this->start_controls_section(
				'sc_media_player_style_section',
				[
					'label' => __( 'Media Player', 'trx_addons' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);

			if ( $prefix != 'video' ) {
				$this->add_responsive_control(
					$prefix . '_player_height',
					[
						'label' => __( 'Height', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'px'
						],
						'size_units' => [ 'px', 'em', 'rem', 'vh', '%', 'custom' ],
						'range' => [
							'px' => [
								'min' => 30,
								'max' => 100,
								'step' => 1
							],
							'em' => [
								'min' => 2,
								'max' => 10,
								'step' => 0.1
							],
							'rem' => [
								'min' => 2,
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
							'{{WRAPPER}} .mejs-container,
							 {{WRAPPER}} .mejs-container .mejs-controls' => 'height: {{SIZE}}{{UNIT}} !important;',
						],
					]
				);

				$this->add_responsive_control(
					$prefix . '_player_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .mejs-container .mejs-controls' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
			}

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => $prefix . '_player_background',
					'selector' => '{{WRAPPER}} .mejs-container .mejs-controls'
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				array(
					'name'        => $prefix . '_player_border',
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .mejs-container .mejs-controls',
				)
			);
	
			$this->add_responsive_control(
				$prefix . '_player_border_radius',
				array(
					'label'      => __( 'Border Radius', 'trx_addons' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'  => array(
									'{{WRAPPER}} .mejs-container,
									 {{WRAPPER}} .mejs-container .mejs-controls' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				$prefix . '_player_text_color',
				[
					'label' => __( 'Text color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .mejs-container .mejs-controls .mejs-time' => 'color: {{VALUE}};',
					],
				]
			);

			if ( apply_filters( 'trx_addons_filter_ai_helper_media_player_button_colors', ! apply_filters( 'trx_addons_filter_allow_sc_styles_in_elementor', false, 'mediaelementplayer' ) ) ) {
				$this->add_control(
					$prefix . '_player_buttons_color',
					[
						'label' => __( 'Buttons color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .mejs-container .mejs-controls .mejs-button > button' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					$prefix . '_player_buttons_hover',
					[
						'label' => __( 'Buttons hover', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .mejs-container .mejs-controls .mejs-button > button:hover,
							 {{WRAPPER}} .mejs-container .mejs-controls .mejs-button > button:focus' => 'color: {{VALUE}};',
						],
					]
				);
			}

			$this->add_control(
				$prefix . '_player_slider_bg',
				[
					'label' => __( 'Sliders Background', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .mejs-container .mejs-controls .mejs-time-rail .mejs-time-total,
						 {{WRAPPER}} .mejs-container .mejs-controls .mejs-time-rail .mejs-time-loaded,
						 {{WRAPPER}} .mejs-container .mejs-controls .mejs-time-rail .mejs-time-hovered,
						 {{WRAPPER}} .mejs-container .mejs-controls .mejs-volume-slider .mejs-volume-total,
						 {{WRAPPER}} .mejs-container .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-total' => 'background: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				$prefix . '_player_slider_filled',
				[
					'label' => __( 'Sliders Filled', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .mejs-container .mejs-controls .mejs-time-rail .mejs-time-current,
						 {{WRAPPER}} .mejs-container .mejs-controls .mejs-volume-slider .mejs-volume-current,
						 {{WRAPPER}} .mejs-container .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current' => 'background: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				$prefix . '_player_slider_handle',
				[
					'label' => __( 'Sliders Handle', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .mejs-container .mejs-controls .mejs-time-rail .mejs-time-handle-content' => 'border-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				$prefix . '_player_time_float_color',
				[
					'label' => __( 'Time Float Color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .mejs-container .mejs-controls .mejs-time-rail .mejs-time-float' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				$prefix . '_player_time_float_bg',
				[
					'label' => __( 'Time Float Background', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .mejs-container .mejs-controls .mejs-time-rail .mejs-time-float' => 'background: {{VALUE}};',
						'{{WRAPPER}} .mejs-container .mejs-controls .mejs-time-rail .mejs-time-float-corner' => 'border-top-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				$prefix . '_player_time_float_bd',
				[
					'label' => __( 'Time Float Border', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .mejs-container .mejs-controls .mejs-time-rail .mejs-time-float' => 'border-color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_section();
		}

		/**
		 * Register widget controls: tab 'Style' section 'Button Download'
		 * 
		 * @param string $type - type of link (audio | image | music | video )
		 */
		protected function register_controls_style_button_download( $type ) {

			$this->start_controls_section(
				'sc_button_download_style_section',
				[
					'label' => __( 'Button "Download"', 'trx_addons' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
					'condition' => [
						'show_download' => '1'
					]
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'button_download_typography',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_' . $type . '_link.sc_button.sc_button_default'
				]
			);

			$params = trx_addons_get_icon_param( 'button_download_icon' );
			$params = trx_addons_array_get_first_value( $params );
			unset( $params['name'] );
			$this->add_control( 'button_download_icon', $params );

			$this->add_control( 'button_download_image',
				[
					'label' => esc_html__( 'Image', 'trx_addons' ),
					'type' => \Elementor\Controls_Manager::MEDIA,
					'media_types' => [ 'image', 'svg' ],
					'condition' => [
						'button_download_icon' => ['', 'none'],
					],
				]
			);

			$this->start_controls_tabs( 'sc_button_download_style_tabs' );

			$this->start_controls_tab(
				'sc_button_download_style_tab_normal',
				[
					'label' => __( 'Normal', 'trx_addons' ),
				]
			);

			$this->add_control(
				"button_download_text_color",
				[
					'label' => __( 'Text color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_' . $type . '_link.sc_button.sc_button_default' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				"button_download_icon_color",
				[
					'label' => __( 'Icon color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_' . $type . '_link.sc_button.sc_button_default .sc_button_icon' => 'color: {{VALUE}};',
						'{{WRAPPER}} .sc_' . $this->slug . '_' . $type . '_link.sc_button.sc_button_default .sc_button_icon svg' => 'fill: {{VALUE}};',
					],
					'condition' => [
						'button_download_icon!' => ['', 'none'],
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'button_download_background',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_' . $type . '_link.sc_button.sc_button_default'
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				array(
					'name'        => 'button_download_border',
					'label'       => __( 'Border', 'trx_addons' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .sc_' . $this->slug . '_' . $type . '_link.sc_button.sc_button_default',
				)
			);
	
			$this->add_responsive_control(
				'button_download_border_radius',
				array(
					'label'      => __( 'Border Radius', 'trx_addons' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'  => array(
									'{{WRAPPER}} .sc_' . $this->slug . '_' . $type . '_link.sc_button.sc_button_default' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'button_download_shadow',
					'label' => esc_html__( 'Shadow', 'trx_addons' ),
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_' . $type . '_link.sc_button.sc_button_default',
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'sc_button_download_style_tab_hover',
				[
					'label' => __( 'Hover', 'trx_addons' ),
				]
			);

			$this->add_control(
				"button_download_text_color_hover",
				[
					'label' => __( 'Text color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_' . $type . '_link.sc_button.sc_button_default:hover,
						 {{WRAPPER}} .sc_' . $this->slug . '_' . $type . '_link.sc_button.sc_button_default:focus' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				"button_download_icon_color_hover",
				[
					'label' => __( 'Icon color', 'trx_addons' ),
					'label_block' => false,
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .sc_' . $this->slug . '_' . $type . '_link.sc_button.sc_button_default:hover .sc_button_icon,
							{{WRAPPER}} .sc_' . $this->slug . '_' . $type . '_link.sc_button.sc_button_default:focus .sc_button_icon' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'button_download_background_hover',
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_' . $type . '_link.sc_button.sc_button_default:hover,
								   {{WRAPPER}} .sc_' . $this->slug . '_' . $type . '_link.sc_button.sc_button_default:focus'
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				array(
					'name'        => 'button_download_border_hover',
					'label'       => __( 'Border', 'trx_addons' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .sc_' . $this->slug . '_' . $type . '_link.sc_button.sc_button_default:hover,
									  {{WRAPPER}} .sc_' . $this->slug . '_' . $type . '_link.sc_button.sc_button_default:focus',
				)
			);
	
			$this->add_responsive_control(
				'button_download_border_radius_hover',
				array(
					'label'      => __( 'Border Radius', 'trx_addons' ),
					'type'       => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
					'selectors'  => array(
									'{{WRAPPER}} .sc_' . $this->slug . '_' . $type . '_link.sc_button.sc_button_default:hover,
									 {{WRAPPER}} .sc_' . $this->slug . '_' . $type . '_link.sc_button.sc_button_default:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'button_download_shadow_hover',
					'label' => esc_html__( 'Shadow', 'trx_addons' ),
					'selector' => '{{WRAPPER}} .sc_' . $this->slug . '_' . $type . '_link.sc_button.sc_button_default:hover,
								   {{WRAPPER}} .sc_' . $this->slug . '_' . $type . '_link.sc_button.sc_button_default:focus',
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->end_controls_section();
		}
	}
}
