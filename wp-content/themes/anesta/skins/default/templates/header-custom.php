<?php
/**
 * The template to display custom header from the ThemeREX Addons Layouts
 *
 * @package ANESTA
 * @since ANESTA 1.0.06
 */

$anesta_header_css   = '';
$anesta_header_image = get_header_image();
$anesta_header_video = anesta_get_header_video();
if ( ! empty( $anesta_header_image ) && anesta_trx_addons_featured_image_override( is_singular() || anesta_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$anesta_header_image = anesta_get_current_mode_image( $anesta_header_image );
}

$anesta_header_id = anesta_get_custom_header_id();
$anesta_header_meta = anesta_get_custom_layout_meta( $anesta_header_id );
if ( ! empty( $anesta_header_meta['margin'] ) ) {
	anesta_add_inline_css( sprintf( '.page_content_wrap{padding-top:%s}', esc_attr( anesta_prepare_css_value( $anesta_header_meta['margin'] ) ) ) );
	anesta_storage_set( 'custom_header_margin', anesta_prepare_css_value( $anesta_header_meta['margin'] ) );
}

?><header class="top_panel top_panel_custom top_panel_custom_<?php echo esc_attr( $anesta_header_id ); ?> top_panel_custom_<?php echo esc_attr( sanitize_title( get_the_title( $anesta_header_id ) ) ); ?>
				<?php
				echo ! empty( $anesta_header_image ) || ! empty( $anesta_header_video )
					? ' with_bg_image'
					: ' without_bg_image';
				if ( '' != $anesta_header_video ) {
					echo ' with_bg_video';
				}
				if ( '' != $anesta_header_image ) {
					echo ' ' . esc_attr( anesta_add_inline_css_class( 'background-image: url(' . esc_url( $anesta_header_image ) . ');' ) );
				}
				if ( is_single() && has_post_thumbnail() ) {
					echo ' with_featured_image';
				}
				if ( anesta_is_on( anesta_get_theme_option( 'header_fullheight' ) ) ) {
					echo ' header_fullheight anesta-full-height';
				}
				$anesta_header_scheme = anesta_get_theme_option( 'header_scheme' );
				if ( ! empty( $anesta_header_scheme ) && ! anesta_is_inherit( $anesta_header_scheme  ) ) {
					echo ' scheme_' . esc_attr( $anesta_header_scheme );
				}
				?>
">
	<?php

	// Background video
	if ( ! empty( $anesta_header_video ) ) {
		get_template_part( apply_filters( 'anesta_filter_get_template_part', 'templates/header-video' ) );
	}

	// Custom header's layout
	do_action( 'anesta_action_show_layout', $anesta_header_id );

	// Header widgets area
	get_template_part( apply_filters( 'anesta_filter_get_template_part', 'templates/header-widgets' ) );

	?>
</header>
