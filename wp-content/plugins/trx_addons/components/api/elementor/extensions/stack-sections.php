<?php
/**
 * Elementor extension: Stack sections support
 *
 * @package ThemeREX Addons
 * @since v2.18.4
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	exit;
}

if ( ! function_exists( 'trx_addons_elm_add_params_stack_section' ) ) {
	add_action( 'elementor/element/before_section_start', 'trx_addons_elm_add_params_stack_section', 10, 3 );
	/**
	 * Add parameters 'Stack section' to the Elementor sections and containers
	 * to add the ability to stack sections over each other on the page scroll.
	 * Available effects: 'slide' and 'fade'
	 * 
	 * @hooked elementor/element/before_section_end
	 *
	 * @param object $element     Elementor element
	 * @param string $section_id  Section ID
	 * @param array $args         Section arguments
	 */
	function trx_addons_elm_add_params_stack_section( $element, $section_id, $args ) {

		if ( ! is_object( $element ) ) {
			return;
		}
		
		$el_name = $element->get_name();

		// Add 'Stack section' to the sections
		if ( in_array( $el_name, array( 'section', 'container' ) ) && $section_id == '_section_responsive' ) {

			$element->start_controls_section( 'section_trx_stack_sections', array(
				'tab' => ! empty( $args['tab'] ) ? $args['tab'] : \Elementor\Controls_Manager::TAB_ADVANCED,
				'label' => __( 'Stack section', 'trx_addons' )
			) );

			$element->add_control( 'stack_section', array(
									'type' => \Elementor\Controls_Manager::SWITCHER,
									'label' => __("Stack section", 'trx_addons'),
									'label_on' => __( 'On', 'trx_addons' ),
									'label_off' => __( 'Off', 'trx_addons' ),
									'return_value' => 'on',
									'render_type' => 'template',
									'prefix_class' => 'sc_stack_section_',
								) );

			$element->add_control( 'stack_section_effect', array(
									'type' => \Elementor\Controls_Manager::SELECT,
									'label' => __("Stack effect", 'trx_addons'),
									'options' => apply_filters( 'trx_addons_filter_stack_section_effects', array(
													'slide' => __( 'Slide', 'trx_addons' ),
													'fade' => __( 'Fade', 'trx_addons' ),
													) ),
									'default' => 'slide',
									'condition' => array(
										'stack_section' => array( 'on' )
									),
									'prefix_class' => 'sc_stack_section_effect_',
								) );

			$element->add_responsive_control( 'stack_section_top_offset', array(
									'label' => __( 'Top Offset', 'trx_addons' ),
									'type' => \Elementor\Controls_Manager::SLIDER,
									'default' => array(
										'size' => '',
										'unit' => 'px'
									),
									'size_units' => array( 'px', 'em', 'rem', '%', 'vh', 'custom' ),
									'range' => array(
										'em' => array(
											'min' => 0,
											'max' => 20,
											'step' => 0.1
										),
										'rem' => array(
											'min' => 0,
											'max' => 20,
											'step' => 0.1
										),
									),
									'condition' => array(
										'stack_section' => array( 'on' ),
										'stack_section_effect' => array( 'slide' )
									),
									'selectors' => array(
										'body.fixed_blocks_sticky {{WRAPPER}}.sc_stack_section_on.sc_stack_section_effect_slide:not(.elementor-element-edit-mode)' => 'top: calc( {{SIZE}}{{UNIT}} + var(--fixed-rows-height) );',
									),
								) );

			$element->add_control( 'stack_section_zoom', array(
									'type' => \Elementor\Controls_Manager::SWITCHER,
									'label' => __("Stacked Zoom Out", 'trx_addons'),
									'label_on' => __( 'On', 'trx_addons' ),
									'label_off' => __( 'Off', 'trx_addons' ),
									'return_value' => 'on',
									'render_type' => 'template',
									'prefix_class' => 'sc_stack_section_zoom_',
									'condition' => array(
										'stack_section' => array( 'on' ),
										'stack_section_effect' => array( 'slide' )
									),
									'selectors' => array(
										'{{WRAPPER}}.sc_stack_section_zoom_on' => '--e-transform-transition-duration: 0s;',
									),
								) );

			$element->add_responsive_control( 'stack_section_zoom_value', array(
									'label' => __( 'Zoom Out to', 'trx_addons' ),
									'type' => \Elementor\Controls_Manager::SLIDER,
									'default' => array(
										'size' => '0.8',
										'unit' => 'px'
									),
									'range' => array(
										'px' => array(
											'min' => 0,
											'max' => 1,
											'step' => 0.01
										),
									),
									'size_units' => array( 'px' ),
									'condition' => array(
										'stack_section' => array( 'on' ),
										'stack_section_effect' => array( 'slide' ),
										'stack_section_zoom' => array( 'on' )
									),
								) );

			$element->add_control( 'stack_section_transparency', array(
									'type' => \Elementor\Controls_Manager::SWITCHER,
									'label' => __("Stacked Transparent", 'trx_addons'),
									'label_on' => __( 'On', 'trx_addons' ),
									'label_off' => __( 'Off', 'trx_addons' ),
									'return_value' => 'on',
									'render_type' => 'template',
									'prefix_class' => 'sc_stack_section_transparency_',
									'condition' => array(
										'stack_section' => array( 'on' ),
										'stack_section_effect' => array( 'slide' )
									),
								) );

			$element->add_responsive_control( 'stack_section_transparency_value', array(
									'label' => __( 'Transparent to', 'trx_addons' ),
									'type' => \Elementor\Controls_Manager::SLIDER,
									'default' => array(
										'size' => '0',
										'unit' => 'px'
									),
									'size_units' => array( 'px' ),
									'range' => array(
										'px' => array(
											'min' => 0,
											'max' => 1,
											'step' => 0.01
										),
									),
									'condition' => array(
										'stack_section' => array( 'on' ),
										'stack_section_effect' => array( 'slide' ),
										'stack_section_transparency' => array( 'on' )
									),
								) );

			$element->add_control( 'stack_section_blur', array(
									'type' => \Elementor\Controls_Manager::SWITCHER,
									'label' => __("Stacked Blur", 'trx_addons'),
									'label_on' => __( 'On', 'trx_addons' ),
									'label_off' => __( 'Off', 'trx_addons' ),
									'return_value' => 'on',
									'render_type' => 'template',
									'prefix_class' => 'sc_stack_section_blur_',
									'condition' => array(
										'stack_section' => array( 'on' ),
										'stack_section_effect' => array( 'slide' )
									),
								) );

			$element->add_responsive_control( 'stack_section_blur_value', array(
									'label' => __( 'Blur to', 'trx_addons' ),
									'type' => \Elementor\Controls_Manager::SLIDER,
									'default' => array(
										'size' => '10',
										'unit' => 'px'
									),
									'size_units' => array( 'px' ),
									'condition' => array(
										'stack_section' => array( 'on' ),
										'stack_section_effect' => array( 'slide' ),
										'stack_section_blur' => array( 'on' )
									),
								) );

			$element->add_control( 'stack_section_effect_duration', array(
									'label' => __( 'Effect Duration (s)', 'trx_addons' ),
									'type' => \Elementor\Controls_Manager::SLIDER,
									'default' => array(
										'size' => '1',
										'unit' => 'px'
									),
									'size_units' => array( 'px' ),
									'range' => array(
										'px' => array(
											'min' => 0,
											'max' => 3,
											'step' => 0.1
										),
									),
									'separator' => 'before',
									'condition' => array(
										'relation' => 'and',
										'terms' => array(
											array(
												'name'     => 'stack_section',
												'operator' => '==',
												'value'    => 'on',
											),
											array(
												'name'     => 'stack_section_effect',
												'operator' => '==',
												'value'    => 'slide',
											),
											array(
												'relation' => 'or',
												'terms' => array(
													array(
														'name'     => 'stack_section_zoom',
														'operator' => '==',
														'value'    => 'on',
													),
													array(
														'name'     => 'stack_section_transparency',
														'operator' => '==',
														'value'    => 'on',
													),
													array(
														'name'     => 'stack_section_blur',
														'operator' => '==',
														'value'    => 'on',
													),
												),
											),
										),
									),
								) );

			$element->end_controls_section();
		}
	}
}

if ( ! function_exists( 'trx_addons_elm_add_params_stack_section_before_render' ) ) {
	// Before Elementor 2.1.0
	add_action( 'elementor/frontend/element/before_render',  'trx_addons_elm_add_params_stack_section_before_render', 10, 1 );
	// After Elementor 2.1.0
	add_action( 'elementor/frontend/section/before_render', 'trx_addons_elm_add_params_stack_section_before_render', 10, 1 );
	// After Elementor 3.16.0
	add_action( 'elementor/frontend/container/before_render', 'trx_addons_elm_add_params_stack_section_before_render', 10, 1 );
	/**
	 * Enqueue scripts and styles before render the Elementor section
	 * 
	 * @hooked elementor/frontend/element/before_render
	 * @hooked elementor/frontend/section/before_render
	 * @hooked elementor/frontend/container/before_render
	 * 
	 * @param object $element Current element
	 */
	function trx_addons_elm_add_params_stack_section_before_render( $element ) {
		if ( is_object( $element ) ) {
			$el_name = $element->get_name();
			if ( 'section' == $el_name || 'container' == $el_name ) {
				$stack = $element->get_settings( 'stack_section' );
				if ( $stack == 'on' ) {
					trx_addons_enqueue_tweenmax( array(
						'ScrollTrigger' => true
					) );
					// Add data-attributes to the wrapper
					$settings = $element->get_settings();
					if ( isset( $settings['stack_section_effect_duration']['size'] ) && $settings['stack_section_effect_duration']['size'] !== '' ) {
						$element->add_render_attribute( '_wrapper', 'data-stack-section-effect-duration', max( 0.001, $settings['stack_section_effect_duration']['size'] ) );
					}
					if ( isset( $settings['stack_section_zoom_value']['size'] ) && $settings['stack_section_zoom_value']['size'] !== '' ) {
						$element->add_render_attribute( '_wrapper', 'data-stack-section-zoom-value', max( 0, min( 1, $settings['stack_section_zoom_value']['size'] ) ) );
					}
					if ( isset( $settings['stack_section_transparency_value']['size'] ) && $settings['stack_section_transparency_value']['size'] !== '' ) {
						$element->add_render_attribute( '_wrapper', 'data-stack-section-transparency-value', max( 0, min( 1, $settings['stack_section_transparency_value']['size'] ) ) );
					}
					if ( isset( $settings['stack_section_blur_value']['size'] ) && $settings['stack_section_blur_value']['size'] !== '' ) {
						$element->add_render_attribute( '_wrapper', 'data-stack-section-blur-value', max( 0, $settings['stack_section_blur_value']['size'] ) );
					}
				}
			}
		}
	}
}

if ( !function_exists( 'trx_addons_elm_stack_section_check_in_html_output' ) ) {
	add_action( 'trx_addons_action_show_layout_from_cache', 'trx_addons_elm_stack_section_check_in_html_output', 10, 1 );
	add_action( 'trx_addons_action_check_page_content', 'trx_addons_elm_stack_section_check_in_html_output', 10, 1 );
	/**
	 * Load TweenMax library if the stack section is used in the page
	 * 
	 * @hooked trx_addons_action_show_layout_from_cache
	 * @hooked trx_addons_action_check_page_content
	 * 
	 * @param string $content Page content
	 * 
	 * @return string  Page content
	 */
	function trx_addons_elm_stack_section_check_in_html_output( $content = '' ) {
		$args = array(
			'check' => array(
				'class=[\'"][^\'"]*sc_stack_section_on'
			)
		);
		if ( trx_addons_check_in_html_output( 'sc_stack_section', $content, $args ) ) {
			trx_addons_enqueue_tweenmax( array(
				'ScrollTrigger' => true
			) );
		}
		return $content;
	}
}
