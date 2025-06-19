<?php
/**
 * The template to display default site footer
 *
 * @package ANESTA
 * @since ANESTA 1.0.10
 */

$anesta_footer_id = anesta_get_custom_footer_id();
$anesta_footer_meta = anesta_get_custom_layout_meta( $anesta_footer_id );
if ( ! empty( $anesta_footer_meta['margin'] ) ) {
	anesta_add_inline_css( sprintf( '.page_content_wrap{padding-bottom:%s}', esc_attr( anesta_prepare_css_value( $anesta_footer_meta['margin'] ) ) ) );
}
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr( $anesta_footer_id ); ?> footer_custom_<?php echo esc_attr( sanitize_title( get_the_title( $anesta_footer_id ) ) ); ?>
						<?php
						$anesta_footer_scheme = anesta_get_theme_option( 'footer_scheme' );
						if ( ! empty( $anesta_footer_scheme ) && ! anesta_is_inherit( $anesta_footer_scheme  ) ) {
							echo ' scheme_' . esc_attr( $anesta_footer_scheme );
						}
						?>
						">
	<?php
	// Custom footer's layout
	do_action( 'anesta_action_show_layout', $anesta_footer_id );
	?>
</footer>
