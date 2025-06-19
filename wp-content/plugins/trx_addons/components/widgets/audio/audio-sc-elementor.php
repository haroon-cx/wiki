<?php
/**
 * Widget: Audio player for Local hosted audio and Soundcloud and other embeded audio (Elementor support)
 *
 * @package ThemeREX Addons
 * @since v1.2
 */


// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	exit;
}



// Elementor Widget
//------------------------------------------------------
if ( ! function_exists( 'trx_addons_sc_widget_audio_add_in_elementor' ) ) {
	add_action( trx_addons_elementor_get_action_for_widgets_registration(), 'trx_addons_sc_widget_audio_add_in_elementor' );
	function trx_addons_sc_widget_audio_add_in_elementor() {

		if ( ! class_exists( 'TRX_Addons_Elementor_Widget' ) ) {
			return;
		}

		class TRX_Addons_Elementor_Widget_Audio extends TRX_Addons_Elementor_Widget {

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
				$this->add_plain_params(
					[
						'cover' => 'url'
					]
				);
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
				return 'trx_widget_audio';
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
				return __( 'Audio', 'trx_addons' );
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
				return [ 'audio', 'player', 'radio', 'sound' ];
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
				return 'eicon-posts-ticker trx_addons_elementor_widget_icon';
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
				return [ 'trx_addons-elements' ];
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
				$this->register_controls_content_general();
				$this->register_controls_style_sc_content();
				$this->register_controls_style_audio_navigation();
				$this->register_controls_style_audio_info();
				$this->register_controls_style_audio_player();
			}

			/**
			 * Register widget controls: tab 'Content' section 'Audio'
			 */
			protected function register_controls_content_general() {
				$this->start_controls_section(
					'section_sc_audio',
					[
						'label' => __( 'Audio', 'trx_addons' ),
					]
				);

				$this->add_control(
					'title',
					[
						'label'       => __( 'Title', 'trx_addons' ),
						'label_block' => false,
						'type'        => \Elementor\Controls_Manager::TEXT,
						'placeholder' => __( 'Widget title', 'trx_addons' ),
						'default'     => '',
					]
				);

				$this->add_control(
					'subtitle',
					[
						'label'       => __( 'Subtitle', 'trx_addons' ),
						'label_block' => false,
						'type'        => \Elementor\Controls_Manager::TEXT,
						'placeholder' => __( 'Widget subtitle', 'trx_addons' ),
						'default'     => '',
					]
				);

				$this->add_control(
					'media_from_post',
					[
						'label'        => __( 'Get audio from post', 'trx_addons' ),
						'label_block'  => false,
						'type'         => \Elementor\Controls_Manager::SWITCHER,
						'label_off'    => __( 'No', 'trx_addons' ),
						'label_on'     => __( 'Yes', 'trx_addons' ),
						'return_value' => '1',
					]
				);

				$this->add_control(
					'next_btn',
					[
						'label'        => __( 'Show "NEXT" button', 'trx_addons' ),
						'label_block'  => false,
						'type'         => \Elementor\Controls_Manager::SWITCHER,
						'label_off'    => __( 'Hide', 'trx_addons' ),
						'label_on'     => __( 'Show', 'trx_addons' ),
						'return_value' => '1',
						'condition' => [
							'media_from_post' => ''
						]
					]
				);

				$this->add_control(
					'prev_btn',
					[
						'label'        => __( 'Show "PREV" button', 'trx_addons' ),
						'label_block'  => false,
						'type'         => \Elementor\Controls_Manager::SWITCHER,
						'label_off'    => __( 'Hide', 'trx_addons' ),
						'label_on'     => __( 'Show', 'trx_addons' ),
						'return_value' => '1',
						'condition' => [
							'media_from_post' => ''
						]
					]
				);

				$this->add_control(
					'next_text',
					[
						'label'       => __( '"NEXT" button caption', 'trx_addons' ),
						'label_block' => false,
						'type'        => \Elementor\Controls_Manager::TEXT,
						'placeholder' => __( 'Next', 'trx_addons' ),
						'default'     => '',
						'condition' => [
							'media_from_post' => ''
						]
					]
				);

				$this->add_control(
					'prev_text',
					[
						'label'       => __( '"PREV" button caption', 'trx_addons' ),
						'label_block' => false,
						'type'        => \Elementor\Controls_Manager::TEXT,
						'placeholder' => __( 'Prev', 'trx_addons' ),
						'default'     => '',
						'condition' => [
							'media_from_post' => ''
						]
					]
				);

				$this->add_control(
					'now_text',
					[
						'label'       => __( '"Now Playing" text', 'trx_addons' ),
						'label_block' => false,
						'type'        => \Elementor\Controls_Manager::TEXT,
						'placeholder' => __( 'Now Playing', 'trx_addons' ),
						'default'     => '',
					]
				);

				$this->add_control(
					'track_time',
					[
						'label'        => __( 'Track time', 'trx_addons' ),
						'label_block'  => false,
						'type'         => \Elementor\Controls_Manager::SWITCHER,
						'label_off'    => __( 'Hide', 'trx_addons' ),
						'label_on'     => __( 'Show', 'trx_addons' ),
						'default'      => '1',
						'return_value' => '1',
					]
				);

				$this->add_control(
					'track_scroll',
					[
						'label'        => __( 'Track scroll bar', 'trx_addons' ),
						'label_block'  => false,
						'type'         => \Elementor\Controls_Manager::SWITCHER,
						'label_off'    => __( 'Hide', 'trx_addons' ),
						'label_on'     => __( 'Show', 'trx_addons' ),
						'default'      => '1',
						'return_value' => '1',
					]
				);

				$this->add_control(
					'track_volume',
					[
						'label'        => __( 'Track volume bar', 'trx_addons' ),
						'label_block'  => false,
						'type'         => \Elementor\Controls_Manager::SWITCHER,
						'label_off'    => __( 'Hide', 'trx_addons' ),
						'label_on'     => __( 'Show', 'trx_addons' ),
						'default'      => '1',
						'return_value' => '1',
					]
				);

				$this->add_control(
					'media',
					[
						'label'   => '',
						'type'    => \Elementor\Controls_Manager::REPEATER,
						'title_field' => '{{caption}}',
						'default' => apply_filters(
							'trx_addons_sc_param_group_value', [
								[
									'url'         => '',
									'embed'       => '',
									'caption'     => __( 'Song', 'trx_addons' ),
									'author'      => __( 'Author', 'trx_addons' ),
									'description' => $this->get_default_description(),
									'cover'       => [ 'url' => '' ],
								],
							], 'trx_widget_audio'
						),
						'fields'  => apply_filters( 'trx_addons_sc_param_group_params',
								[
									[
										'name' => 'audio',
										'label' => __( 'Select Audio', 'trx_addons' ),
										'type' => \Elementor\Controls_Manager::MEDIA,
										'dynamic' => [
											'active' => true,
											'categories' => [
												\Elementor\Modules\DynamicTags\Module::MEDIA_CATEGORY,
											],
										],
										'media_types' => [
											'audio',
										],
										'default' => [],
									],
									[
										'name'        => 'url',
										'label'       => __( 'or Specify Audio URL', 'trx_addons' ),
										'label_block' => true,
										'type'        => \Elementor\Controls_Manager::TEXT,
										'default'     => '',
										'placeholder' => __( '//audio.url', 'trx_addons' ),
										'condition' => [
											'audio[url]' => ''
										]
									],
									[
										'name'        => 'embed',
										'label'       => __( 'or Embed code', 'trx_addons' ),
										'label_block' => true,
										'description' => wp_kses_data( __( 'Paste HTML code to embed audio (to use it instead URL from the field above)', 'trx_addons' ) ),
										'type'        => \Elementor\Controls_Manager::TEXTAREA,
										'default'     => '',
										'rows'        => 10,
										'condition' => [
											'url' => '',
											'audio[url]' => ''
										]
									],
									[
										'name'        => 'caption',
										'label'       => __( 'Audio caption', 'trx_addons' ),
										'label_block' => false,
										'type'        => \Elementor\Controls_Manager::TEXT,
										'placeholder' => __( 'Caption', 'trx_addons' ),
										'default'     => '',
									],
									[
										'name'        => 'author',
										'label'       => __( 'Author', 'trx_addons' ),
										'label_block' => false,
										'type'        => \Elementor\Controls_Manager::TEXT,
										'placeholder' => __( 'Author name', 'trx_addons' ),
										'default'     => '',
									],
									[
										'name'        => 'description',
										'label'       => __( 'Description', 'trx_addons' ),
										'label_block' => true,
										'description' => wp_kses_data( __( 'Short description', 'trx_addons' ) ),
										'type'        => \Elementor\Controls_Manager::TEXTAREA,
										'default'     => '',
										'rows'        => 10,
									],
									[
										'name'        => 'cover',
										'label'       => __( 'Cover image', 'trx_addons' ),
										'label_block' => true,
										'type'        => \Elementor\Controls_Manager::MEDIA,
										'default'     => [
											'url' => '',
										],
									],
								],
								'trx_widget_audio'
						),
						'condition' => [
							'media_from_post' => ''
						]
					]
				);

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Content Area'
			 */
			protected function register_controls_style_sc_content() {

				$this->start_controls_section(
					'section_sc_audio_content_style',
					[
						'label' => __( 'Content Area', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'sc_content_background',
						'selector' => '{{WRAPPER}} .trx_addons_audio_player'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'sc_content_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .trx_addons_audio_player',
					)
				);
		
				$this->add_responsive_control(
					'sc_content_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .trx_addons_audio_player' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
							'{{WRAPPER}} .trx_addons_audio_player' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
		
				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name'     => 'sc_content_box_shadow',
						'selector' => '{{WRAPPER}} .trx_addons_audio_player',
					]
				);
		
				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Audio Navigation'
			 */
			protected function register_controls_style_audio_navigation() {

				$this->start_controls_section(
					'section_sc_audio_navigation_style',
					[
						'label' => __( 'Audio Navigation', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE
					]
				);

				$this->add_control(
					'audio_navigation_box_heading',
					array(
						'label'      => __( 'Navigation Box', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::HEADING,
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'audio_navigation_background',
						'selector' => '{{WRAPPER}} .trx_addons_audio_navigation'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'audio_navigation_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .trx_addons_audio_navigation',
					)
				);
		
				$this->add_responsive_control(
					'audio_navigation_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .trx_addons_audio_navigation' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_responsive_control(
					'audio_navigation_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .trx_addons_audio_navigation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'audio_navigation_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .trx_addons_audio_navigation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
		
				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name'     => 'audio_navigation_box_shadow',
						'selector' => '{{WRAPPER}} .trx_addons_audio_navigation',
					]
				);

				$this->add_control(
					'audio_navigation_buttons_heading',
					array(
						'label'      => __( 'Navigation Buttons', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::HEADING,
					)
				);

				$params = trx_addons_get_icon_param( 'prev_icon' );
				$params = trx_addons_array_get_first_value( $params );
				unset( $params['name'] );
				$params['label'] = __( 'Prev Button Icon', 'trx_addons' );
				$this->add_control( 'prev_icon', $params );

				$params = trx_addons_get_icon_param( 'next_icon' );
				$params = trx_addons_array_get_first_value( $params );
				unset( $params['name'] );
				$params['label'] = __( 'Next Button Icon', 'trx_addons' );
				$this->add_control( 'next_icon', $params );

				$this->start_controls_tabs( 'tabs_sc_audio_navigation_buttons_style' );

				$this->start_controls_tab(
					'tab_audio_navigation_buttons_normal',
					[
						'label' => __( 'Normal', 'trx_addons' ),
					]
				);

				$this->add_control(
					"audio_navigation_buttons_color",
					[
						'label' => __( 'Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_navigation .nav_btn' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'audio_navigation_buttons_background',
						'selector' => '{{WRAPPER}} .trx_addons_audio_navigation .nav_btn'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'audio_navigation_buttons_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .trx_addons_audio_navigation .nav_btn',
					)
				);
		
				$this->add_responsive_control(
					'audio_navigation_buttons_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .trx_addons_audio_navigation .nav_btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_responsive_control(
					'audio_navigation_buttons_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .trx_addons_audio_navigation .nav_btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'audio_navigation_buttons_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .trx_addons_audio_navigation .nav_btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					'audio_navigation_buttons_opacity',
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
							'{{WRAPPER}} .trx_addons_audio_navigation .nav_btn' => 'opacity: {{SIZE}};',
						],
					]
				);
		
				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name'     => 'audio_navigation_buttons_box_shadow',
						'selector' => '{{WRAPPER}} .trx_addons_audio_navigation .nav_btn',
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_audio_navigation_buttons_hover',
					[
						'label' => __( 'Hover', 'trx_addons' ),
					]
				);

				$this->add_control(
					"audio_navigation_buttons_color_hover",
					[
						'label' => __( 'Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_navigation .nav_btn:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'audio_navigation_buttons_background_hover',
						'selector' => '{{WRAPPER}} .trx_addons_audio_navigation .nav_btn:hover'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'audio_navigation_buttons_border_hover',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .trx_addons_audio_navigation .nav_btn:hover',
					)
				);
		
				$this->add_responsive_control(
					'audio_navigation_buttons_border_radius_hover',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .trx_addons_audio_navigation .nav_btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);
		
				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name'     => 'audio_navigation_buttons_box_shadow_hover',
						'selector' => '{{WRAPPER}} .trx_addons_audio_navigation .nav_btn:hover',
					]
				);

				$this->add_control(
					'audio_navigation_buttons_opacity:hover',
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
							'{{WRAPPER}} .trx_addons_audio_navigation .nav_btn:hover' => 'opacity: {{SIZE}};',
						],
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Audio Info'
			 */
			protected function register_controls_style_audio_info() {

				$this->start_controls_section(
					'section_sc_audio_info_style',
					[
						'label' => __( 'Audio Info', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE
					]
				);

				$this->start_controls_tabs( 'tabs_sc_audio_info_style' );

				$this->start_controls_tab(
					'tab_audio_info_normal',
					[
						'label' => __( 'Normal', 'trx_addons' ),
					]
				);

				$this->add_control(
					'audio_info_box_heading',
					array(
						'label'      => __( 'Info Box', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::HEADING,
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'audio_info_background',
						'selector' => '{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .audio_info'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'audio_info_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .audio_info',
					)
				);
		
				$this->add_responsive_control(
					'audio_info_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .audio_info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_responsive_control(
					'audio_info_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .audio_info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'audio_info_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .audio_info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
		
				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name'     => 'audio_info_box_shadow',
						'selector' => '{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .audio_info',
					]
				);

				$this->add_control(
					'audio_info_now_playing_heading',
					array(
						'label'      => __( 'Info Titles: Now Playing', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::HEADING,
						'separator'  => 'before',
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'audio_info_now_playing_typography',
						'selector' => '{{WRAPPER}} .trx_addons_audio_player .audio_now_playing'
					]
				);

				$this->add_control(
					"audio_info_now_playing_color",
					[
						'label' => __( 'Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .audio_now_playing' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'audio_info_now_playing_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .trx_addons_audio_player .audio_now_playing' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					'audio_info_author_heading',
					array(
						'label'      => __( 'Info Titles: Author', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::HEADING,
						'separator'  => 'before',
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'audio_info_author_typography',
						'selector' => '{{WRAPPER}} .trx_addons_audio_player .audio_author'
					]
				);

				$this->add_control(
					"audio_info_author_color",
					[
						'label' => __( 'Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .audio_author' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'audio_info_author_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .trx_addons_audio_player .audio_author' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					'audio_info_caption_heading',
					array(
						'label'      => __( 'Info Titles: Caption', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::HEADING,
						'separator'  => 'before',
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'audio_info_caption_typography',
						'selector' => '{{WRAPPER}} .trx_addons_audio_player .audio_caption'
					]
				);

				$this->add_control(
					"audio_info_caption_color",
					[
						'label' => __( 'Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .audio_caption' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'audio_info_caption_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .trx_addons_audio_player .audio_caption' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					'audio_info_description_heading',
					array(
						'label'      => __( 'Info Titles: Description', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::HEADING,
						'separator'  => 'before',
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'audio_info_description_typography',
						'selector' => '{{WRAPPER}} .trx_addons_audio_player .audio_description'
					]
				);

				$this->add_control(
					"audio_info_description_color",
					[
						'label' => __( 'Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .audio_description' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'audio_info_description_margin',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .trx_addons_audio_player .audio_description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_audio_info_covered',
					[
						'label' => __( 'With Cover', 'trx_addons' ),
					]
				);

				$this->add_control(
					'audio_info_box_heading_covered',
					array(
						'label'      => __( 'Info Box', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::HEADING,
					)
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'audio_info_background_covered',
						'selector' => '{{WRAPPER}} .trx_addons_audio_player.with_cover .audio_info'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'audio_info_border_covered',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .trx_addons_audio_player.with_cover .audio_info',
					)
				);
		
				$this->add_responsive_control(
					'audio_info_border_radius_covered',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .trx_addons_audio_player.with_cover .audio_info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_responsive_control(
					'audio_info_padding_covered',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .trx_addons_audio_player.with_cover .audio_info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'audio_info_margin_covered',
					[
						'label'                 => esc_html__( 'Margin', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .trx_addons_audio_player.with_cover .audio_info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
		
				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name'     => 'audio_info_box_shadow_covered',
						'selector' => '{{WRAPPER}} .trx_addons_audio_player.with_cover .audio_info',
					]
				);

				$this->add_control(
					'audio_info_box_titles_heading_covered',
					array(
						'label'      => __( 'Info Titles', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::HEADING,
						'separator'  => 'before',
					)
				);

				$this->add_control(
					"audio_info_now_playing_color_covered",
					[
						'label' => __( '"Now Playing" Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player.with_cover .audio_now_playing' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_info_author_color_covered",
					[
						'label' => __( 'Author Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player.with_cover .audio_author' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_info_caption_color_covered",
					[
						'label' => __( 'Caption Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player.with_cover .audio_caption' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_info_description_color_covered",
					[
						'label' => __( 'Description Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player.with_cover .audio_description' => 'color: {{VALUE}};',
						],
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

				$this->end_controls_section();
			}

			/**
			 * Register widget controls: tab 'Style' section 'Audio Player'
			 */
			protected function register_controls_style_audio_player() {

				$this->start_controls_section(
					'section_sc_audio_player_style',
					[
						'label' => __( 'Audio Player', 'trx_addons' ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE,
					]
				);

				$this->start_controls_tabs( 'tabs_sc_audio_player_style' );

				$this->start_controls_tab(
					'tab_audio_player_normal',
					[
						'label' => __( 'Normal', 'trx_addons' ),
					]
				);

				$this->add_responsive_control(
					'audio_player_height',
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
							'{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-container,
							 {{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-controls' => 'height: {{SIZE}}{{UNIT}} !important;',
						],
					]
				);

				$this->add_responsive_control(
					'audio_player_padding',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-controls' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'audio_player_background',
						'selector' => '{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-controls'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'audio_player_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-controls',
					)
				);
		
				$this->add_responsive_control(
					'audio_player_border_radius',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-container,
										 {{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-controls' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_control(
					"audio_player_text_color",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-controls .mejs-time' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_buttons_color",
					[
						'label' => __( 'Buttons color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-controls .mejs-button > button' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_buttons_hover",
					[
						'label' => __( 'Buttons hover', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-controls .mejs-button > button:hover,
							 {{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-controls .mejs-button > button:focus' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_slider_bg",
					[
						'label' => __( 'Sliders Background', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-controls .mejs-time-rail .mejs-time-total,
							 {{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-controls .mejs-time-rail .mejs-time-loaded,
							 {{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-controls .mejs-time-rail .mejs-time-hovered,
							 {{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-controls .mejs-volume-slider .mejs-volume-total,
							 {{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-total' => 'background: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_slider_filled",
					[
						'label' => __( 'Sliders Filled', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-controls .mejs-time-rail .mejs-time-current,
							 {{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-controls .mejs-volume-slider .mejs-volume-current,
							 {{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current' => 'background: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_slider_handle",
					[
						'label' => __( 'Sliders Handle', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-controls .mejs-time-rail .mejs-time-handle-content' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_time_float_color",
					[
						'label' => __( 'Time Float Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-controls .mejs-time-rail .mejs-time-float' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_time_float_bg",
					[
						'label' => __( 'Time Float Background', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-controls .mejs-time-rail .mejs-time-float' => 'background: {{VALUE}};',
							'{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-controls .mejs-time-rail .mejs-time-float-corner' => 'border-top-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_time_float_bd",
					[
						'label' => __( 'Time Float Border', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player:not(.with_cover) .mejs-controls .mejs-time-rail .mejs-time-float' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_audio_player_covered',
					[
						'label' => __( 'With Cover', 'trx_addons' ),
					]
				);

				$this->add_responsive_control(
					'audio_player_height_covered',
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
							'{{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-container,
							 {{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-controls' => 'height: {{SIZE}}{{UNIT}} !important;',
						],
					]
				);

				$this->add_responsive_control(
					'audio_player_padding_covered',
					[
						'label'                 => esc_html__( 'Padding', 'trx_addons' ),
						'type'                  => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'             => [
							'{{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-controls' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					[
						'name' => 'audio_player_background_covered',
						'selector' => '{{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-controls'
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'audio_player_border_covered',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => '{{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-controls',
					)
				);
		
				$this->add_responsive_control(
					'audio_player_border_radius_covered',
					array(
						'label'      => __( 'Border Radius', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
										'{{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-container,
										 {{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-controls' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_control(
					"audio_player_text_color_covered",
					[
						'label' => __( 'Text color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-controls .mejs-time' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_buttons_color_covered",
					[
						'label' => __( 'Buttons color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-controls .mejs-button > button' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_buttons_hover_covered",
					[
						'label' => __( 'Buttons hover', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-controls .mejs-button > button:hover,
							 {{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-controls .mejs-button > button:focus' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_slider_bg_covered",
					[
						'label' => __( 'Sliders Background', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-controls .mejs-time-rail .mejs-time-total,
							 {{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-controls .mejs-time-rail .mejs-time-loaded,
							 {{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-controls .mejs-time-rail .mejs-time-hovered,
							 {{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-controls .mejs-volume-slider .mejs-volume-total,
							 {{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-total' => 'background: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_slider_filled_covered",
					[
						'label' => __( 'Sliders Filled', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-controls .mejs-time-rail .mejs-time-current,
							 {{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-controls .mejs-volume-slider .mejs-volume-current,
							 {{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current' => 'background: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_slider_handle_covered",
					[
						'label' => __( 'Sliders Handle', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-controls .mejs-time-rail .mejs-time-handle-content' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_time_float_color_covered",
					[
						'label' => __( 'Time Float Color', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-controls .mejs-time-rail .mejs-time-float' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_time_float_bg_covered",
					[
						'label' => __( 'Time Float Background', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-controls .mejs-time-rail .mejs-time-float' => 'background: {{VALUE}};',
							'{{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-controls .mejs-time-rail .mejs-time-float-corner' => 'border-top-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					"audio_player_time_float_bd_covered",
					[
						'label' => __( 'Time Float Border', 'trx_addons' ),
						'label_block' => false,
						'type' => \Elementor\Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .trx_addons_audio_player.with_cover .mejs-controls .mejs-time-rail .mejs-time-float' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->end_controls_tab();

				$this->end_controls_tabs();

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
				trx_addons_get_template_part(
					TRX_ADDONS_PLUGIN_WIDGETS . 'audio/tpe.audio.php',
					'trx_addons_args_widget_audio',
					array( 'element' => $this )
				);
			}
		}

		/* Register widget */
		trx_addons_elm_register_widget( 'TRX_Addons_Elementor_Widget_Audio' );
	}
}


// Disable our widgets (shortcodes) to use in Elementor
// because we create special Elementor's widgets instead
if ( ! function_exists( 'trx_addons_widget_audio_black_list' ) ) {
	add_action( 'elementor/widgets/black_list', 'trx_addons_widget_audio_black_list' );
	function trx_addons_widget_audio_black_list( $list ) {
		$list[] = 'trx_addons_widget_audio';
		return $list;
	}
}
