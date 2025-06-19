<?php
/**
 * The custom template to display the content
 *
 * Used for index/archive/search.
 *
 * @package ANESTA
 * @since ANESTA 1.0.50
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
$anesta_blog_id       = anesta_get_custom_blog_id( join( '_', $anesta_blog_style ) );
$anesta_blog_style[0] = str_replace( 'blog-custom-', '', $anesta_blog_style[0] );
$anesta_expanded      = ! anesta_sidebar_present() && anesta_get_theme_option( 'expand_content' ) == 'expand';
$anesta_components    = ! empty( $anesta_template_args['meta_parts'] )
							? ( is_array( $anesta_template_args['meta_parts'] )
								? join( ',', $anesta_template_args['meta_parts'] )
								: $anesta_template_args['meta_parts']
								)
							: anesta_array_get_keys_by_value( anesta_get_theme_option( 'meta_parts' ) );
$anesta_post_format   = get_post_format();
$anesta_post_format   = empty( $anesta_post_format ) ? 'standard' : str_replace( 'post-format-', '', $anesta_post_format );

$anesta_blog_meta     = anesta_get_custom_layout_meta( $anesta_blog_id );
$anesta_custom_style  = ! empty( $anesta_blog_meta['scripts_required'] ) ? $anesta_blog_meta['scripts_required'] : 'none';

if ( ! empty( $anesta_template_args['slider'] ) || $anesta_columns > 1 || ! anesta_is_off( $anesta_custom_style ) ) {
	?><div class="<?php
		if ( ! empty( $anesta_template_args['slider'] ) ) {
			echo 'slider-slide swiper-slide';
		} else {
			echo esc_attr( anesta_is_off( $anesta_custom_style )
							? $anesta_columns_class
							: sprintf( '%1$s_item %1$s_item-1_%2$d', $anesta_custom_style, $anesta_columns )
							);
		}
	?>">
	<?php
}
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
			'post_item post_item_container post_format_' . esc_attr( $anesta_post_format )
					. ' post_layout_custom post_layout_custom_' . esc_attr( $anesta_columns )
					. ' post_layout_' . esc_attr( $anesta_blog_style[0] )
					. ' post_layout_' . esc_attr( $anesta_blog_style[0] ) . '_' . esc_attr( $anesta_columns )
					. ( ! anesta_is_off( $anesta_custom_style )
						? ' post_layout_' . esc_attr( $anesta_custom_style )
							. ' post_layout_' . esc_attr( $anesta_custom_style ) . '_' . esc_attr( $anesta_columns )
						: ''
						)
		);
	anesta_add_blog_animation( $anesta_template_args );
	?>
>
	<?php
	// Sticky label
	if ( is_sticky() && ! is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}
	// Custom layout
	do_action( 'anesta_action_show_layout', $anesta_blog_id, get_the_ID() );
	?>
</article><?php
if ( ! empty( $anesta_template_args['slider'] ) || $anesta_columns > 1 || ! anesta_is_off( $anesta_custom_style ) ) {
	?></div><?php
	// Need opening PHP-tag above just after </div>, because <div> is a inline-block element (used as column)!
}
