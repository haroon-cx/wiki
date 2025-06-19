<?php
/**
 * The template to display default site footer
 *
 * @package ANESTA
 * @since ANESTA 1.0.10
 */

?>
<footer class="footer_wrap footer_default
<?php
$anesta_footer_scheme = anesta_get_theme_option( 'footer_scheme' );
if ( ! empty( $anesta_footer_scheme ) && ! anesta_is_inherit( $anesta_footer_scheme  ) ) {
	echo ' scheme_' . esc_attr( $anesta_footer_scheme );
}
?>
				">
	<?php

	// Footer widgets area
	get_template_part( apply_filters( 'anesta_filter_get_template_part', 'templates/footer-widgets' ) );

	// Logo
	get_template_part( apply_filters( 'anesta_filter_get_template_part', 'templates/footer-logo' ) );

	// Socials
	get_template_part( apply_filters( 'anesta_filter_get_template_part', 'templates/footer-socials' ) );

	// Menu
	get_template_part( apply_filters( 'anesta_filter_get_template_part', 'templates/footer-menu' ) );

	// Copyright area
	get_template_part( apply_filters( 'anesta_filter_get_template_part', 'templates/footer-copyright' ) );

	?>
</footer>
