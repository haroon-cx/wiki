<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: //codex.wordpress.org/Template_Hierarchy
 *
 * @package ANESTA
 * @since ANESTA 1.0
 */

$anesta_template = apply_filters( 'anesta_filter_get_template_part', anesta_blog_archive_get_template() );

if ( ! empty( $anesta_template ) && 'index' != $anesta_template ) {

	get_template_part( $anesta_template );

} else {

	anesta_storage_set( 'blog_archive', true );

	get_header();

	if ( have_posts() ) {

		// Query params
		$anesta_stickies   = is_home()
								|| ( in_array( anesta_get_theme_option( 'post_type' ), array( '', 'post' ) )
									&& (int) anesta_get_theme_option( 'parent_cat' ) == 0
									)
										? get_option( 'sticky_posts' )
										: false;
		$anesta_post_type  = anesta_get_theme_option( 'post_type' );
		$anesta_args       = array(
								'blog_style'     => anesta_get_theme_option( 'blog_style' ),
								'post_type'      => $anesta_post_type,
								'taxonomy'       => anesta_get_post_type_taxonomy( $anesta_post_type ),
								'parent_cat'     => anesta_get_theme_option( 'parent_cat' ),
								'posts_per_page' => anesta_get_theme_option( 'posts_per_page' ),
								'sticky'         => anesta_get_theme_option( 'sticky_style' ) == 'columns'
															&& is_array( $anesta_stickies )
															&& count( $anesta_stickies ) > 0
															&& get_query_var( 'paged' ) < 1
								);

		anesta_blog_archive_start();

		do_action( 'anesta_action_blog_archive_start' );

		if ( is_author() ) {
			do_action( 'anesta_action_before_page_author' );
			get_template_part( apply_filters( 'anesta_filter_get_template_part', 'templates/author-page' ) );
			do_action( 'anesta_action_after_page_author' );
		}

		if ( anesta_get_theme_option( 'show_filters' ) ) {
			do_action( 'anesta_action_before_page_filters' );
			anesta_show_filters( $anesta_args );
			do_action( 'anesta_action_after_page_filters' );
		} else {
			do_action( 'anesta_action_before_page_posts' );
			anesta_show_posts( array_merge( $anesta_args, array( 'cat' => $anesta_args['parent_cat'] ) ) );
			do_action( 'anesta_action_after_page_posts' );
		}

		do_action( 'anesta_action_blog_archive_end' );

		anesta_blog_archive_end();

	} else {

		if ( is_search() ) {
			get_template_part( apply_filters( 'anesta_filter_get_template_part', 'templates/content', 'none-search' ), 'none-search' );
		} else {
			get_template_part( apply_filters( 'anesta_filter_get_template_part', 'templates/content', 'none-archive' ), 'none-archive' );
		}
	}

	get_footer();
}
