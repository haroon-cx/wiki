<?php
/**
 * The template to display default site header
 *
 * @package ANESTA
 * @since ANESTA 1.0
 */

$anesta_header_css   = '';
$anesta_header_image = get_header_image();
$anesta_header_video = anesta_get_header_video();
if ( ! empty( $anesta_header_image ) && anesta_trx_addons_featured_image_override( is_singular() || anesta_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$anesta_header_image = anesta_get_current_mode_image( $anesta_header_image );
}
?><header class="top_panel top_panel_default
	<?php
	echo ! empty( $anesta_header_image ) || ! empty( $anesta_header_video ) ? ' with_bg_image' : ' without_bg_image';
	if ( '' != $anesta_header_video ) {
		echo ' with_bg_video';
	}
	if ( '' != $anesta_header_image ) {
		echo ' ' . esc_attr( anesta_add_inline_css_class( 'background-image: url(' . esc_url( $anesta_header_image ) . ');' ) );
	}
	if ( is_singular() && has_post_thumbnail() ) {
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

	// Main menu
	get_template_part( apply_filters( 'anesta_filter_get_template_part', 'templates/header-navi' ) );

	// Mobile header
	if ( anesta_is_on( anesta_get_theme_option( 'header_mobile_enabled' ) ) ) {
		get_template_part( apply_filters( 'anesta_filter_get_template_part', 'templates/header-mobile' ) );
	}

	// Page title and breadcrumbs area
	if ( false && ! is_single() ) {
		get_template_part( apply_filters( 'anesta_filter_get_template_part', 'templates/header-title' ) );
	}

	// Header widgets area
	get_template_part( apply_filters( 'anesta_filter_get_template_part', 'templates/header-widgets' ) );
	?>
</header>
