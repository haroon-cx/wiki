<?php
/**
 * Shortcode: AGenerator (Elementor support)
 *
 * @package ThemeREX Addons
 * @since v2.31.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

use TrxAddons\AiHelper\Lists;
use TrxAddons\AiHelper\Utils;
use TrxAddons\AiHelper\WidgetGenerator;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Modules\DynamicTags\Module as TagsModule;


// Elementor Widget
//------------------------------------------------------
if ( ! function_exists('trx_addons_sc_agenerator_add_in_elementor')) {
	add_action( trx_addons_elementor_get_action_for_widgets_registration(), 'trx_addons_sc_agenerator_add_in_elementor' );
	function trx_addons_sc_agenerator_add_in_elementor() {

		class TRX_Addons_Elementor_Widget_AGenerator extends WidgetGenerator {

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
			public function __construct( $data = array(), $args = null ) {
				parent::__construct( $data, $args );
				$this->add_plain_params( array(
					'prompt_width' => 'size',
					'button_image' => 'url',
					'settings_button_image' => 'url',
					'button_download_image' => 'url',
				) );
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
				return 'trx_sc_agenerator';
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
				return __( 'AI Helper Audio Generator', 'trx_addons' );
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
				return [ 'ai', 'helper', 'generator', 'agenerator', 'audio', 'voice', 'speech', 'ai audio', 'ai generator' ];
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
				return 'eicon-play trx_addons_elementor_widget_icon';
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
				$this->register_controls_content_generator_settings();
				$this->register_controls_content_demo_audio();

				$this->register_controls_style_sc_content();
				$this->register_controls_style_sc_form();
				$this->register_controls_style_tabs();
				$this->register_controls_style_sc_form_field();
				$this->register_controls_style_button_generate();
				$this->register_controls_style_settings_button();
				$this->register_controls_style_settings_popup();
				$this->register_controls_style_settings_field();
				$this->register_controls_style_limits();
				$this->register_controls_style_message();
				$this->register_controls_style_audio_preview();
				$this->register_controls_style_single_audio();
				$this->register_controls_style_media_player( 'audio' );
				$this->register_controls_style_button_download( 'audio' );

				$this->after_register_controls();
			}

			/**
			 * Register widget controls: tab 'Content' section 'AI Helper Audio Generator'
			 */
			protected function register_controls_content_general() {

				// Register controls
				$this->start_controls_section(
					'section_sc_agenerator',
					[
						'label' => __( 'AI Helper Audio Generator', 'trx_addons' ),
					]
				);

				$this->add_control(
					'type',
					[
						'label' => __( 'Layout', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::SELECT,
						'options' => apply_filters('trx_addons_sc_type', Lists::get_list_sc_audio_generator_layouts(), 'trx_sc_agenerator'),
						'default' => 'default'
					]
				);

				$this->add_control(
					'placeholder_text',
					[
						'label' => __( 'Placeholder', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::TEXT,
						'default' => ''
					]
				);

				$this->add_control(
					'button_text',
					[
						'label' => __( 'Button text', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::TEXT,
						'default' => ''
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Content' section 'Generator Settings'
			 */
			protected function register_controls_content_generator_settings() {

				// Section: Generator settings
				$this->start_controls_section(
					'section_sc_agenerator_settings',
					[
						'label' => __( 'Generator Settings', 'trx_addons' ),
					]
				);

				$this->add_control(
					'premium',
					[
						'label' => __( 'Premium Mode', 'trx_addons' ),
						'label_block' => false,
						'description' => __( 'Enables you to set a broader range of limits for image generation, which can be used for a paid image generation service. The limits are configured in the global settings.', 'trx_addons' ),
						'type' => Controls_Manager::SWITCHER,
						'return_value' => '1',
					]
				);

				$this->add_control(
					'show_limits',
					[
						'label' => __( 'Show limits', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::SWITCHER,
						'return_value' => '1',
					]
				);

				$this->add_control(
					'show_settings',
					[
						'label' => __( 'Show button "Settings"', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::SWITCHER,
						'return_value' => '1'
					]
				);

				$this->add_control(
					'show_download',
					[
						'label' => __( 'Show button "Download"', 'trx_addons' ),
						'label_block' => false,
						'type' => Controls_Manager::SWITCHER,
						'return_value' => '1',
					]
				);

				$this->add_control(
					'base64',
					[
						'label' => __( 'Use Base64', 'trx_addons' ),
						'label_block' => false,
						'description' => __( "Pass the original audio/voice to the generation server inside a query (using Base64 encoding) or via a temporary URL (the file will be cached on your server for some time, not suitable for local installations inaccessible from the Internet)", 'trx_addons' ),
						'type' => Controls_Manager::SWITCHER,
						'default' => '',
						'return_value' => '1',
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Content' section 'Demo Audio'
			 */
			protected function register_controls_content_demo_audio() {

				// Section: Demo audio
				$this->start_controls_section(
					'section_sc_agenerator_demo',
					[
						'label' => __( 'Demo Audio', 'trx_addons' ),
					]
				);

				$repeater = new Repeater();
		
				$repeater->add_control(
					'audio',
					[
						'label' => __( 'Audio', 'trx_addons' ),
						'description' => wp_kses_data( __("Selected files will be used instead of the audio generator as a demo mode when limits are reached", 'trx_addons') ),
						'type' => Controls_Manager::MEDIA,
						'dynamic' => [
							'active' => true,
							'categories' => [
								TagsModule::MEDIA_CATEGORY,
							],
						],
						'media_types' => [
							'audio',
						],
						'default' => [],
					]
				);

				$this->add_control(
					'demo_audio',
					[
						'type'        => Controls_Manager::REPEATER,
						'fields'      => $repeater->get_controls(),
						'title_field' => '{{{trx_addons_get_file_name(audio.url,false)}}}',
					]
				);
		
				$this->end_controls_section();
			}

			/*-----------------------------------------------------------------------------------*/
			/*	TAB "STYLE"
			/*-----------------------------------------------------------------------------------*/

			/**
			 * Register widget controls: tab 'Style' section 'Tabs'
			 */
			protected function register_controls_style_tabs() {

				$this->start_controls_section(
					'section_sc_agenerator_tabs_style',
					[
						'label' => __( 'Tabs', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE,
					]
				);

				$this->add_responsive_control(
					'tab_slider_heading',
					[
						'label' => __( 'Slider', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::HEADING,
					]
				);

				$this->add_responsive_control(
					'tab_slider_height',
					[
						'label' => __( 'Height', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::NUMBER,
						'default' => 2,
						'min' => 0,
						'max' => 10,
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_actions_list:after,
							 {{WRAPPER}} .sc_agenerator_form_actions_slider' => 'height: {{VALUE}}px;',
						],
					]
				);

				$this->add_responsive_control(
					'tab_slider_offset',
					[
						'label' => __( 'Offset', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::NUMBER,
						'default' => '',
						'min' => -20,
						'max' => 20,
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_actions_list:after,
							 {{WRAPPER}} .sc_agenerator_form_actions_slider' => 'bottom: {{VALUE}}px;',
						],
					]
				);

				$this->add_control(
					"tab_slider_color",
					[
						'label' => __( 'Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_actions_slider' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"tab_slider_line_color",
					[
						'label' => __( 'Line Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_actions_list:after' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'tab_items_heading',
					[
						'label' => __( 'Tabs', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'tab_typography',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_actions_item > a'
					]
				);

				$this->add_responsive_control(
					'tab_gap',
					[
						'label' => __( 'Gap', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'em'
						],
						'size_units' => [ 'px', 'em', 'rem', 'vw', '%', 'custom' ],
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
							'{{WRAPPER}} .sc_agenerator_form_actions_list' => 'gap: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->start_controls_tabs( 'tabs_sc_agenerator_tabs_style' );

				$this->start_controls_tab(
					'tab_sc_agenerator_tab_normal',
					[
						'label' => __( 'Normal', 'trx_addons' ),
					]
				);

				$this->add_control(
					"tab_text_color",
					[
						'label' => __( 'Text Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_actions_item > a' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'tab_background',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_actions_item > a'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'tab_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_form_actions_item > a',
					)
				);
		
				$this->add_responsive_control(
					'tab_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_form_actions_item > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'tab_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_form_actions_item > a',
					]
				);

				$this->add_responsive_control(
					'tab_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_form_actions_item > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
						'separator'             => 'before',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_agenerator_tab_hover',
					[
						'label' => __( 'Hover', 'trx_addons' ),
					]
				);

				$this->add_control(
					"tab_text_color_hover",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_actions_item:not(.sc_agenerator_form_actions_item_active) > a:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'tab_background_hover',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_actions_item:not(.sc_agenerator_form_actions_item_active) > a:hover'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'tab_border_hover',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_form_actions_item:not(.sc_agenerator_form_actions_item_active) > a:hover',
					)
				);
		
				$this->add_responsive_control(
					'tab_border_radius_hover',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_form_actions_item:not(.sc_agenerator_form_actions_item_active) > a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'tab_shadow_hover',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_form_actions_item:not(.sc_agenerator_form_actions_item_active) > a:hover',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_agenerator_tab_active',
					[
						'label' => __( 'Active', 'trx_addons' ),
					]
				);

				$this->add_control(
					"tab_text_color_active",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_actions_item_active > a' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'tab_background_active',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_actions_item_active > a'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'tab_border_active',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_form_actions_item_active > a',
					)
				);
		
				$this->add_responsive_control(
					'tab_border_radius_active',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_form_actions_item_active > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'tab_shadow_active',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_form_actions_item_active > a',
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Form field'
			 */
			protected function register_controls_style_sc_form_field() {

				$this->start_controls_section(
					'section_sc_agenerator_form_field_style',
					[
						'label' => __( 'Form Field', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE
					]
				);

				$this->add_responsive_control(
					'sc_form_fields_spacing',
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
							'{{WRAPPER}} .sc_agenerator_form_field:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'sc_form_field_typography',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_field input,
										{{WRAPPER}} .sc_agenerator_form_field select,
										{{WRAPPER}} .sc_agenerator_form_field .select_container,
										{{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator,
										{{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator'
					]
				);

				$this->start_controls_tabs( 'tabs_sc_agenerator_form_field_style' );

				$this->start_controls_tab(
					'tab_sc_agenerator_form_field_normal',
					[
						'label' => __( 'Normal', 'trx_addons' ),
					]
				);

				$this->add_control(
					"sc_form_field_text_color",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field input,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator,
							 {{WRAPPER}} .sc_agenerator_form_field .select_container:after,
							 {{WRAPPER}} .sc_agenerator_form_field select,
							 {{WRAPPER}} .sc_agenerator_form_field .sc_agenerator_form_field_numeric_wrap_button:before' => 'color: {{VALUE}};',
							// Additional rule to override the select field background with !important
							// '{{WRAPPER}} .sc_agenerator_form_field .select_container select' => 'background-color: {{sc_form_field_background_color.VALUE}} !important;',
						],
					]
				);

				$this->add_control(
					"sc_form_field_placeholder_color",
					[
						'label' => __( 'Placeholder color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field input[placeholder]::placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_field input[placeholder]::-moz-placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_field input[placeholder]::-webkit-input-placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator .sc_agenerator_form_field_upload_audio_text.theme_form_field_placeholder,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator .sc_agenerator_form_field_upload_audio_text.theme_form_field_placeholder' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"sc_form_field_browse_color",
					[
						'label' => __( 'Button "Browse" color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field_upload_audio_button,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_button' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'sc_form_field_background',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_field input,
										{{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator,
										{{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator,
										{{WRAPPER}} .sc_agenerator_form_field select,
										{{WRAPPER}} .sc_agenerator_form_field .select_container:before',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'sc_form_field_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_form_field input,
										{{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator,
										{{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator,
										{{WRAPPER}} .sc_agenerator_form_field select',
					)
				);
		
				$this->add_responsive_control(
					'sc_form_field_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_form_field input,
										 {{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator,
										 {{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator,
										 {{WRAPPER}} .sc_agenerator_form_field select,
										 {{WRAPPER}} .sc_agenerator_form_field .select_container,
										 {{WRAPPER}} .sc_agenerator_form_field .select_container:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
					)
				);

				$this->add_responsive_control(
					'sc_form_field_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_form_field input,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator,
							 {{WRAPPER}} .sc_agenerator_form_field select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'sc_form_field_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_form_field input,
										{{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator,
										{{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator,
										{{WRAPPER}} .sc_agenerator_form_field :not(.select_container) > select,
										{{WRAPPER}} .sc_agenerator_form_field .select_container',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_agenerator_form_field_focus',
					[
						'label' => __( 'Focus', 'trx_addons' ),
					]
				);

				$this->add_control(
					"sc_form_field_text_color_focus",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field input:focus,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator:focus,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator:hover,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator:focus,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator:hover,
							 {{WRAPPER}} .sc_agenerator_form_field select:focus,
							 {{WRAPPER}} .sc_agenerator_form_field input:focus + .sc_agenerator_form_field_numeric_wrap_buttons .sc_agenerator_form_field_numeric_wrap_button:before' => 'color: {{VALUE}};',
							// Additional rule to override the select field background with !important
							// '{{WRAPPER}} .sc_agenerator_form_field .select_container select:focus' => 'background-color: {{sc_form_field_background_color.VALUE}} !important;',
						],
					]
				);

				$this->add_control(
					"sc_form_field_placeholder_color_focus",
					[
						'label' => __( 'Placeholder color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						// 'global' => array(
						// 	'active' => false,
						// ),
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field input[placeholder]:focus::placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_field input[placeholder]:focus::-moz-placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_field input[placeholder]:focus::-webkit-input-placeholder' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator:focus .sc_agenerator_form_field_upload_audio_text.theme_form_field_placeholder,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator:focus .sc_agenerator_form_field_upload_audio_text.theme_form_field_placeholder' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"sc_form_field_browse_color_hover",
					[
						'label' => __( 'Button "Browse" color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator:focus .sc_agenerator_form_field_upload_audio_button,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator:hover .sc_agenerator_form_field_upload_audio_button,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator:focus .sc_agenerator_form_field_upload_voice_modelslab_button,
							 {{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator:hover .sc_agenerator_form_field_upload_voice_modelslab_button' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'sc_form_field_background_focus',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_field input:focus,
										{{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator:focus,
							 			{{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator:focus,
										{{WRAPPER}} .sc_agenerator_form_field select:focus',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'sc_form_field_border_focus',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_form_field input:focus,
										{{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator:focus,
							 			{{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator:focus,
										{{WRAPPER}} .sc_agenerator_form_field select:focus',
					)
				);
		
				$this->add_responsive_control(
					'sc_form_field_border_radius_focus',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_form_field input:focus,
										 {{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator:focus,
							 			 {{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator:focus,
										 {{WRAPPER}} .sc_agenerator_form_field select:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'sc_form_field_shadow_focus',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_form_field input:focus,
										{{WRAPPER}} .sc_agenerator_form_field_upload_audio_decorator:focus,
							 			{{WRAPPER}} .sc_agenerator_form_field_upload_voice_modelslab_decorator:focus,
										{{WRAPPER}} .sc_agenerator_form_field select:focus',
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->add_control(
					"sc_form_field_label_heading",
					[
						'label' => __( 'Fields Label', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'sc_form_field_label_typography',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_field label,
										{{WRAPPER}} .sc_agenerator_form_field_tags_label'
					]
				);

				$this->add_control(
					"sc_form_field_label_color",
					[
						'label' => __( 'Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field label,
							 {{WRAPPER}} .sc_agenerator_form_field_tags_label' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'sc_form_field_label_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_form_field label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					"sc_form_field_description_heading",
					[
						'label' => __( 'Fields Description', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'sc_form_field_description_typography',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_field_description'
					]
				);

				$this->add_control(
					"sc_form_field_description_color",
					[
						'label' => __( 'Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_field_description' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'sc_form_field_description_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_form_field_description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Button Settings'
			 */
			protected function register_controls_style_settings_button() {

				$this->start_controls_section(
					'section_sc_agenerator_settings_button_style',
					[
						'label' => __( 'Button "Settings"', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE,
						'condition' => [
							'show_settings' => '1'
						]
					]
				);

				$this->add_responsive_control(
					'settings_button_width',
					[
						'label' => __( 'Width', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'px'
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
							'{{WRAPPER}} .sc_agenerator_form_settings_button' => 'width: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'settings_button_gap',
					[
						'label' => __( 'Gap', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => '',
							'unit' => 'px'
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
							'{{WRAPPER}} .sc_agenerator_form_field_model_wrap_with_settings' => 'gap: {{SIZE}}{{UNIT}};',
						],
						'separator' => 'before',
					]
				);

				$this->add_control( 'settings_button_image',
					[
						'label' => esc_html__( 'Image', 'trx_addons' ),
						'type' => \Elementor\Controls_Manager::MEDIA,
						'media_types' => [ 'image', 'svg' ],
					]
				);

				$params = trx_addons_get_icon_param( 'settings_button_icon' );
				$params = trx_addons_array_get_first_value( $params );
				unset( $params['name'] );
				$params['condition'] = [
					'settings_button_image[url]' => '',
				];
				$this->add_control( 'settings_button_icon', $params );

				$this->add_responsive_control(
					'settings_button_icon_size',
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
							'{{WRAPPER}} .sc_agenerator_form_settings_button:before,
							 {{WRAPPER}} .sc_agenerator_form_settings_button_image,
							 {{WRAPPER}} .sc_agenerator_form_settings_button_svg' => 'font-size: {{SIZE}}{{UNIT}};',
						],
						'conditions' => array(
							'relation' => 'or',
							'terms'    => array(
								array(
									'name'     => 'settings_button_icon',
									'operator' => '!==',
									'value'    => array( '', 'none' ),
								),
								array(
									'name'     => 'settings_button_image[url]',
									'operator' => '!==',
									'value'    => '',
								),
							),
						),
					]
				);

				$this->start_controls_tabs( 'tabs_sc_agenerator_settings_button_style' );

				$this->start_controls_tab(
					'tab_sc_agenerator_settings_button_normal',
					[
						'label' => __( 'Normal', 'trx_addons' ),
					]
				);

				$this->add_control(
					"settings_button_icon_color",
					[
						'label' => __( 'Icon color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_settings_button' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_settings_button svg' => 'fill: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'settings_button_background',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_settings_button'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'settings_button_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_form_settings_button',
					)
				);
		
				$this->add_responsive_control(
					'settings_button_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_form_settings_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'settings_button_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_form_settings_button',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_sc_agenerator_settings_button_hover',
					[
						'label' => __( 'Hover', 'trx_addons' ),
					]
				);

				$this->add_control(
					"settings_button_icon_color_hover",
					[
						'label' => __( 'Icon color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_form_settings_button:hover' => 'color: {{VALUE}};',
							'{{WRAPPER}} .sc_agenerator_form_settings_button:hover svg' => 'fill: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'settings_button_background_hover',
						'selector' => '{{WRAPPER}} .sc_agenerator_form_settings_button:hover'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'settings_button_border_hover',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_form_settings_button:hover',
					)
				);
		
				$this->add_responsive_control(
					'settings_button_border_radius_hover',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_form_settings_button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'settings_button_shadow_hover',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_form_settings_button:hover',
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Audio Preview'
			 */
			protected function register_controls_style_audio_preview() {

				$this->start_controls_section(
					'section_sc_agenerator_audio_preview_style',
					[
						'label' => __( 'Preview Area', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE,
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'audio_preview_background',
						'selector' => '{{WRAPPER}} .sc_agenerator_audio'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'audio_preview_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_audio',
					)
				);
		
				$this->add_responsive_control(
					'audio_preview_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_audio' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'audio_preview_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_audio',
					]
				);

				$this->add_responsive_control(
					'audio_preview_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_audio' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'audio_preview_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_audio' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Audio Container'
			 */
			protected function register_controls_style_single_audio() {

				$this->start_controls_section(
					'section_sc_agenerator_single_audio_style',
					[
						'label' => __( 'Audio Container', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE,
					]
				);

				$this->add_responsive_control(
					'single_audio_width',
					[
						'label' => __( 'Min. width', 'trx_addons' ),
						'type' => Controls_Manager::SLIDER,
						'size_units' => [ 'px', 'em', 'rem', '%', 'vw', 'custom' ],
						'range' => [
							'px' => [
								'min' => 200,
								'max' => 1000
							],
							'%' => [
								'min' => 20,
								'max' => 100
							],
							'em' => [
								'min' => 10,
								'max' => 100,
								'step' => 0.1
							],
							'rem' => [
								'min' => 10,
								'max' => 100,
								'step' => 0.1
							],
							'vw' => [
								'min' => 10,
								'max' => 100
							],
						],
						'selectors' => [
							'{{WRAPPER}} .sc_agenerator_audio_wrap' => 'min-width: {{SIZE}}{{UNIT}};',
						]
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'single_audio_background',
						'selector' => '{{WRAPPER}} .sc_agenerator_audio_wrap'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'single_audio_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .sc_agenerator_audio_wrap',
					)
				);
		
				$this->add_responsive_control(
					'single_audio_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .sc_agenerator_audio_wrap,
										 {{WRAPPER}} .sc_agenerator_audio_wrap img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'single_audio_shadow',
				 		'label' => esc_html__( 'Shadow', 'trx_addons' ),
						'selector' => '{{WRAPPER}} .sc_agenerator_audio_wrap',
					]
				);

				$this->add_responsive_control(
					'single_audio_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .sc_agenerator_audio_wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				if ( ! Utils::is_audio_api_available() ) {
					trx_addons_get_template_part( 'templates/tpe.sc_placeholder.php',
						'trx_addons_args_sc_placeholder',
						apply_filters( 'trx_addons_filter_sc_placeholder_args', array(
							'sc' => 'trx_sc_agenerator',
							'title' => __('AI Audio Generator is not available - token for access to the API for audio generation is not specified', 'trx_addons'),
							'class' => 'sc_placeholder_with_title'
						) )
					);
				} else {
					trx_addons_get_template_part(TRX_ADDONS_PLUGIN_ADDONS . 'ai-helper/shortcodes/agenerator/tpe.agenerator.php',
						'trx_addons_args_sc_agenerator',
						array('element' => $this)
					);
				}
			}
		}
		
		// Register widget
		trx_addons_elm_register_widget( 'TRX_Addons_Elementor_Widget_AGenerator' );
	}
}
