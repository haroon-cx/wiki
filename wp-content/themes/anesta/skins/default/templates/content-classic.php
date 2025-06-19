<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package ANESTA
 * @since ANESTA 1.0
 */

$anesta_template_args = get_query_var( 'anesta_template_args' );

if ( is_array( $anesta_template_args ) ) {
	$anesta_columns       = empty( $anesta_template_args['columns'] ) ? 2 : max( 1, $anesta_template_args['columns'] );
	$anesta_blog_style    = array( $anesta_template_args['type'], $anesta_columns );
	$anesta_columns_class = anesta_get_column_class( 1, $anesta_columns, ! empty( $anesta_template_args['columns_tablet']) ? $anesta_template_args['columns_tablet'] : '', ! empty($anesta_template_args['columns_mobile']) ? $anesta_template_args['columns_mobile'] : '' );
} else {
	$anesta_template_args = array();
	$anesta_blog_style    = explode( '_', anesta_get_theme_option( 'blog_style' ) );
	$anesta_columns       = empty( $anesta_blog_style[1] ) ? 2 : max( 1, $anesta_blog_style[1] );
	$anesta_columns_class = anesta_get_column_class( 1, $anesta_columns );
}

$anesta_expanded   = ! anesta_sidebar_present() && anesta_get_theme_option( 'expand_content' ) == 'expand';

$anesta_post_format = get_post_format();
$anesta_post_format = empty( $anesta_post_format ) ? 'standard' : str_replace( 'post-format-', '', $anesta_post_format );

?><div class="<?php
	if ( ! empty( $anesta_template_args['slider'] ) ) {
		echo ' slider-slide swiper-slide';
	} else {
		echo ( anesta_is_blog_style_use_masonry( $anesta_blog_style[0] )
			? 'masonry_item masonry_item-1_' . esc_attr( $anesta_columns )
			: esc_attr( $anesta_columns_class )
			);
	}
?>"><article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $anesta_post_format )
				. ' post_layout_classic post_layout_classic_' . esc_attr( $anesta_columns )
				. ' post_layout_' . esc_attr( $anesta_blog_style[0] )
				. ' post_layout_' . esc_attr( $anesta_blog_style[0] ) . '_' . esc_attr( $anesta_columns )
	);
	anesta_add_blog_animation( $anesta_template_args );
	?>
>
	<?php

	// Sticky label
	if ( is_sticky() && ! is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	$anesta_hover      = ! empty( $anesta_template_args['hover'] ) && ! anesta_is_inherit( $anesta_template_args['hover'] )
							? $anesta_template_args['hover']
							: anesta_get_theme_option( 'image_hover' );

	$anesta_components = ! empty( $anesta_template_args['meta_parts'] )
							? ( is_array( $anesta_template_args['meta_parts'] )
								? $anesta_template_args['meta_parts']
								: explode( ',', $anesta_template_args['meta_parts'] )
								)
							: anesta_array_get_keys_by_value( anesta_get_theme_option( 'meta_parts' ) );

	anesta_show_post_featured( apply_filters( 'anesta_filter_args_featured',
		array(
			'thumb_size' => ! empty( $anesta_template_args['thumb_size'] )
								? $anesta_template_args['thumb_size']
								: anesta_get_thumb_size(
									'classic' == $anesta_blog_style[0]
											? ( strpos( anesta_get_theme_option( 'body_style' ), 'full' ) !== false
													? ( $anesta_columns > 2 ? 'big' : 'huge' )
													: ( $anesta_columns > 2
														? ( $anesta_expanded ? 'big' : 'small' )
														: ( $anesta_expanded ? 'big' : 'huge' )
														)
												)
											: ( strpos( anesta_get_theme_option( 'body_style' ), 'full' ) !== false
													? ( $anesta_columns > 2 ? 'masonry-big' : 'full' )
													: ( $anesta_columns <= 2 && $anesta_expanded ? 'masonry-big' : 'masonry' )
												)
								),
			'hover'      => $anesta_hover,
			'meta_parts' => $anesta_components,
			'no_links'   => ! empty( $anesta_template_args['no_links'] ),
		),
		'content-classic',
		$anesta_template_args
	) );

	?><div class="post_content_wrap"><?php

	// Title and post meta
	$anesta_show_title = get_the_title() != '';
	$anesta_show_meta  = count( $anesta_components ) > 0 && ! in_array( $anesta_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );

	if ( $anesta_show_title ) {
		?><div class="post_header entry-header"><?php
			// Categories
			if ( apply_filters( 'anesta_filter_show_blog_categories', $anesta_show_meta && in_array( 'categories', $anesta_components ), array( 'categories' ), 'classic' ) ) {
				do_action( 'anesta_action_before_post_category' );
				
				anesta_show_post_meta( apply_filters(
													'anesta_filter_post_meta_args',
													array(
														'components' => 'categories,date',
														'seo'        => false,
														'echo'       => true,
														),
													'hover_' . $anesta_hover, 1
													)
									);
			
				$anesta_components = anesta_array_delete_by_value( $anesta_components, 'categories' );
				$anesta_components = anesta_array_delete_by_value( $anesta_components, 'date' );
				do_action( 'anesta_action_after_post_category' );
			}
			// Post title
			if ( apply_filters( 'anesta_filter_show_blog_title', true, 'classic' ) ) {
				do_action( 'anesta_action_before_post_title' );
				if ( empty( $anesta_template_args['no_links'] ) ) {
					the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
				} else {
					the_title( '<h3 class="post_title entry-title">', '</h3>' );
				}
				do_action( 'anesta_action_after_post_title' );
			}
		?></div><?php
	}

	// Post content
	ob_start();
	$anesta_show_excerpt = in_array( $anesta_post_format, array('quote', 'aside', 'status', 'link') ) ? true : (isset( $anesta_template_args['hide_excerpt'] ) ? (int) $anesta_template_args['hide_excerpt'] == 0 : (int) anesta_get_theme_option( 'excerpt_length' ) > 0);
	if ( apply_filters( 'anesta_filter_show_blog_excerpt', $anesta_show_excerpt, 'classic' ) ) {
		anesta_show_post_content( $anesta_template_args, '<div class="post_content_inner">', '</div>' );
	}
	$anesta_content = ob_get_contents();
	ob_end_clean();

	anesta_show_layout( $anesta_content, '<div class="post_content entry-content">', '</div>' );

	// Post meta
	if ( apply_filters( 'anesta_filter_show_blog_meta', $anesta_show_meta, $anesta_components, 'classic' ) ) {
		if ( count( $anesta_components ) > 0 ) {
			do_action( 'anesta_action_before_post_meta' );
			anesta_show_post_meta(
				apply_filters(
					'anesta_filter_post_meta_args', array(
						'components' => join( ',', $anesta_components ),
						'seo'        => false,
						'echo'       => true,
					), $anesta_blog_style[0], $anesta_columns
				)
			);
			do_action( 'anesta_action_after_post_meta' );
		}
	}
		
	// More button
	if ( apply_filters( 'anesta_filter_show_blog_readmore', ( ! $anesta_show_title || ! empty( $anesta_template_args['more_button'] ) ) && ! empty( $args['more_text'] ), 'classic' ) ) {
		if ( empty( $anesta_template_args['no_links'] ) ) {
			do_action( 'anesta_action_before_post_readmore' );
			anesta_show_post_more_link( $anesta_template_args, '<p>', '</p>' );
			do_action( 'anesta_action_after_post_readmore' );
		}
	}
	?>
	</div>

</article></div><?php
// Need opening PHP-tag above, because <div> is a inline-block element (used as column)!
