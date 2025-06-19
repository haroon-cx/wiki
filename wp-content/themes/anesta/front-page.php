<?php
/**
 * The Front Page template file.
 *
 * @package ANESTA
 * @since ANESTA 1.0.31
 */

get_header();

// If front-page is a static page
if ( get_option( 'show_on_front' ) == 'page' ) {

	// If Front Page Builder is enabled - display sections
	if ( anesta_is_on( anesta_get_theme_option( 'front_page_enabled', false ) ) ) {

		if ( have_posts() ) {
			the_post();
		}

		$anesta_sections = anesta_array_get_keys_by_value( anesta_get_theme_option( 'front_page_sections' ) );
		if ( is_array( $anesta_sections ) ) {
			foreach ( $anesta_sections as $anesta_section ) {
				get_template_part( apply_filters( 'anesta_filter_get_template_part', 'front-page/section', $anesta_section ), $anesta_section );
			}
		}

		// Else if this page is a blog archive
	} elseif ( is_page_template( 'blog.php' ) ) {
		get_template_part( apply_filters( 'anesta_filter_get_template_part', 'blog' ) );

		// Else - display a native page content
	} else {
		get_template_part( apply_filters( 'anesta_filter_get_template_part', 'page' ) );
	}

	// Else get the template 'index.php' to show posts
} else {
	get_template_part( apply_filters( 'anesta_filter_get_template_part', 'index' ) );
}

get_footer();
