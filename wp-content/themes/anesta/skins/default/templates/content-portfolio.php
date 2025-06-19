<?php
/**
 * The Portfolio template to display the content
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
?>"><article id="post-<?php the_ID(); ?>" 
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $anesta_post_format )
		. ' post_layout_portfolio'
		. ' post_layout_portfolio_' . esc_attr( $anesta_columns )
		. ( 'portfolio' != $anesta_blog_style[0] ? ' ' . esc_attr( $anesta_blog_style[0] )  . '_' . esc_attr( $anesta_columns ) : '' )
	);
	anesta_add_blog_animation( $anesta_template_args );
	?>
>
<?php

	// Sticky label
	if ( is_sticky() && ! is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	$anesta_hover   = ! empty( $anesta_template_args['hover'] ) && ! anesta_is_inherit( $anesta_template_args['hover'] )
								? $anesta_template_args['hover']
								: anesta_get_theme_option( 'image_hover' );

	if ( 'dots' == $anesta_hover ) {
		$anesta_post_link = empty( $anesta_template_args['no_links'] )
								? ( ! empty( $anesta_template_args['link'] )
									? $anesta_template_args['link']
									: get_permalink()
									)
								: '';
		$anesta_target    = ! empty( $anesta_post_link ) && false === strpos( $anesta_post_link, home_url() )
								? ' target="_blank" rel="nofollow"'
								: '';
	}
	
	// Meta parts
	$anesta_components = ! empty( $anesta_template_args['meta_parts'] )
								? ( is_array( $anesta_template_args['meta_parts'] )
									? $anesta_template_args['meta_parts']
									: explode( ',', $anesta_template_args['meta_parts'] )
									)
								: anesta_array_get_keys_by_value( anesta_get_theme_option( 'meta_parts' ) );

	// Featured image
	anesta_show_post_featured( apply_filters( 'anesta_filter_args_featured', 
		array(
			'hover'         => $anesta_hover,
			'no_links'      => ! empty( $anesta_template_args['no_links'] ),
			'thumb_size'    => ! empty( $anesta_template_args['thumb_size'] )
								? $anesta_template_args['thumb_size']
								: anesta_get_thumb_size(
									anesta_is_blog_style_use_masonry( $anesta_blog_style[0] )
										? (	strpos( anesta_get_theme_option( 'body_style' ), 'full' ) !== false || $anesta_columns < 3
											? 'masonry-big'
											: 'masonry'
											)
										: (	strpos( anesta_get_theme_option( 'body_style' ), 'full' ) !== false || $anesta_columns < 3
											? 'big'
											: 'med'
											)
								),
			'show_no_image' => true,
			'meta_parts'    => $anesta_components,
			'class'         => in_array( $anesta_hover, apply_filters( 'anesta_filter_add_info_to_hovers', array( 'dots' ) ) )
										? 'hover_with_info'
										: '',
			'post_info'     => in_array( $anesta_hover, apply_filters( 'anesta_filter_add_info_to_hovers', array( 'dots' ) ) )
										? '<div class="post_info"><h5 class="post_title">'
											. ( ! empty( $anesta_post_link )
												? '<a href="' . esc_url( $anesta_post_link ) . '"' . ( ! empty( $target ) ? $target : '' ) . '>'
												: ''
												)
												. esc_html( get_the_title() ) 
											. ( ! empty( $anesta_post_link )
												? '</a>'
												: ''
												)
											. '</h5></div>'
										: '',
		),
		'content-portfolio',
		$anesta_template_args
	) );
	?>
</article></div><?php
// Need opening PHP-tag above, because <article> is a inline-block element (used as column)!