<?php
/**
 * The template 'Style 2' to displaying related posts
 *
 * @package ANESTA
 * @since ANESTA 1.0
 */

$anesta_link        = get_permalink();
$anesta_post_format = get_post_format();
$anesta_post_format = empty( $anesta_post_format ) ? 'standard' : str_replace( 'post-format-', '', $anesta_post_format );
ob_start();
?><div id="post-<?php the_ID(); ?>" <?php post_class( 'related_item post_format_' . esc_attr( $anesta_post_format ) ); ?> data-post-id="<?php the_ID(); ?>">
	<?php
	anesta_show_post_featured(
		array(
			'thumb_size'    => apply_filters( 'anesta_filter_related_thumb_size', anesta_get_thumb_size( (int) anesta_get_theme_option( 'related_posts' ) == 1 ? 'huge' : 'big' ) ),
		)
	);
	?>
	<div class="post_header entry-header">
		<?php
		if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {
			?>
			<div class="post_meta">
				<a href="<?php echo esc_url( $anesta_link ); ?>" class="post_meta_item post_date"><?php echo wp_kses_data( anesta_get_date() ); ?></a>
			</div>
			<?php
		}
		?>
		<h6 class="post_title entry-title"><a href="<?php echo esc_url( $anesta_link ); ?>"><?php
			if ( '' == get_the_title() ) {
				esc_html_e( 'No title', 'anesta' );
			} else {
				the_title();
			}
		?></a></h6>
	</div>
</div><?php
$anesta_post = apply_filters( 'anesta_filter_related_post_output', ob_get_contents(), the_ID() );
ob_end_clean();
anesta_show_layout( $anesta_post );