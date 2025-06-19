<?php
/**
 * The default template to displaying related posts
 *
 * @package ANESTA
 * @since ANESTA 1.0.54
 */

$anesta_link        = get_permalink();
$anesta_post_format = get_post_format();
$anesta_post_format = empty( $anesta_post_format ) ? 'standard' : str_replace( 'post-format-', '', $anesta_post_format );
?><div id="post-<?php the_ID(); ?>" <?php post_class( 'related_item post_format_' . esc_attr( $anesta_post_format ) ); ?> data-post-id="<?php the_ID(); ?>">
	<?php
	anesta_show_post_featured(
		array(
			'thumb_size' => apply_filters( 'anesta_filter_related_thumb_size', anesta_get_thumb_size( (int) anesta_get_theme_option( 'related_posts' ) == 1 ? 'huge' : 'big' ) ),
		)
	);
	?>
	<div class="post_header entry-header">
		<h6 class="post_title entry-title"><a href="<?php echo esc_url( $anesta_link ); ?>"><?php
			if ( '' == get_the_title() ) {
				esc_html_e( 'No title', 'anesta' );
			} else {
				the_title();
			}
		?></a></h6>
		<?php
		if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {
			?>
			<span class="post_date"><a href="<?php echo esc_url( $anesta_link ); ?>"><?php echo wp_kses_data( anesta_get_date() ); ?></a></span>
			<?php
		}
		?>
	</div>
</div>
