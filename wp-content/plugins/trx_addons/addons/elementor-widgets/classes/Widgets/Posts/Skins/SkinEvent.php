<?php
namespace TrxAddons\ElementorWidgets\Widgets\Posts\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;

/**
 * Event Skin for Posts widget
 */
class SkinEvent extends BaseSkin {

	/**
	 * Retrieve Skin ID.
	 *
	 * @return string Skin ID.
	 */
	public function get_id() {
		return 'event';
	}

	/**
	 * Retrieve Skin title.
	 *
	 * @return string Skin title.
	 */
	public function get_title() {
		return __( 'Event', 'trx_addons' );
	}

	/**
	 * Add skin controls to the parent widget.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		parent::_register_controls_actions();
		// Add controls to edit a parts width
		add_action( 'elementor/element/trx_elm_posts/event_section_image_style/after_section_start', array( $this, 'add_style_image_controls' ) );
		add_action( 'elementor/element/trx_elm_posts/event_section_meta_style/after_section_start', array( $this, 'add_style_meta_controls' ) );
		add_action( 'elementor/element/trx_elm_posts/event_section_title_style/after_section_start', array( $this, 'add_style_title_controls' ) );
	}

	/**
	 * Style Tab: Image
	 */
	public function add_style_image_controls() {

		$this->add_responsive_control(
			'image_width',
			array(
				'label'     => __( 'Width (in %)', 'trx_addons' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-item-thumbnail' => 'width: {{SIZE}}%',
				),
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);
	}

	/**
	 * Style Tab: Meta
	 */
	public function add_style_meta_controls() {

		$this->add_responsive_control(
			'meta_width',
			array(
				'label'     => __( 'Width (in %)', 'trx_addons' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-item-meta-wrap' => 'width: {{SIZE}}%',
				),
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);
	}

	/**
	 * Style Tab: Title
	 */
	public function add_style_title_controls() {

		$this->add_responsive_control(
			'title_width',
			array(
				'label'     => __( 'Width (in %)', 'trx_addons' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'selectors' => array(
					'{{WRAPPER}} .trx-addons-posts-item-title-wrap' => 'width: {{SIZE}}%',
				),
				'condition' => array(
					$this->get_control_id( 'post_title' ) => 'yes',
				),
			)
		);
	}

	/**
	 * Render post body output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_post_body() {

		$settings = $this->parent->get_settings_for_display();

		$post_terms         = $this->get_instance_value( 'post_terms' );
		$post_meta          = $this->get_instance_value( 'post_meta' );

		do_action( 'trx_addons_action_elementor_widgets_posts_before_single_post_wrap', get_the_ID(), $settings );
		?>
		<div <?php post_class( $this->get_item_wrap_classes() ); ?>>
			<?php do_action( 'trx_addons_action_elementor_widgets_posts_before_single_post', get_the_ID(), $settings ); ?>
			<div class="<?php echo esc_attr( $this->get_item_classes() ); ?>">

				<?php do_action( 'trx_addons_action_elementor_widgets_posts_before_single_post_content', get_the_ID(), $settings ); ?>

				<div class="trx-addons-posts-item-content-wrap">
					<div class="trx-addons-posts-item-content"><?php
						$this->render_post_thumbnail();

						if ( 'yes' == $post_terms || 'yes' == $post_meta ) {
							?><div class="trx-addons-posts-item-meta-wrap"><?php
								$this->render_post_meta();
								$this->render_terms();
							?></div><?php	
						}
						?><div class="trx-addons-posts-item-title-wrap"><?php
							$this->render_post_title();
							$this->render_excerpt();
						?></div><?php
						$this->render_button();
					?></div><?php
				?></div><?php
				
				do_action( 'trx_addons_action_elementor_widgets_posts_after_single_post_content', get_the_ID(), $settings );
			?></div><?php
			
			do_action( 'trx_addons_action_elementor_widgets_posts_after_single_post', get_the_ID(), $settings );
		?></div><?php

		do_action( 'trx_addons_action_elementor_widgets_posts_after_single_post_wrap', get_the_ID(), $settings );
	}
}
