<?php
/**
 * The template 'Style 1' to displaying related posts
 *
 * @package ANESTA
 * @since ANESTA 1.0
 */

$anesta_link        = get_permalink();
$anesta_post_format = get_post_format();
$anesta_post_format = empty( $anesta_post_format ) ? 'standard' : str_replace( 'post-format-', '', $anesta_post_format );
?><div id="post-<?php the_ID(); ?>" <?php post_class( 'related_item post_format_' . esc_attr( $anesta_post_format ) ); ?> data-post-id="<?php the_ID(); ?>">
	<?php
	anesta_show_post_featured(
		array(
			'thumb_size'    => apply_filters( 'anesta_filter_related_thumb_size', anesta_get_thumb_size( (int) anesta_get_theme_option( 'related_posts' ) == 1 ? 'huge' : 'big' ) ),
			'post_info'     => '<div class="post_header entry-header">'
									. '<div class="post_categories">' . wp_kses( anesta_get_post_categories( '' ), 'anesta_kses_content' ) . '</div>'
									. '<h6 class="post_title entry-title"><a href="' . esc_url( $anesta_link ) . '">'
										. wp_kses_data( '' == get_the_title() ? esc_html__( 'No title', 'anesta' ) : get_the_title() )
									. '</a></h6>'
									. ( in_array( get_post_type(), array( 'post', 'attachment' ) )
											? '<div class="post_meta"><a href="' . esc_url( $anesta_link ) . '" class="post_meta_item post_date">' . wp_kses_data( anesta_get_date() ) . '</a></div>'
											: '' )
								. '</div>',
		)
	);
	?>
</div>
