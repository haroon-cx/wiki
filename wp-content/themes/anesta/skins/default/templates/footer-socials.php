<?php
/**
 * The template to display the socials in the footer
 *
 * @package ANESTA
 * @since ANESTA 1.0.10
 */


// Socials
if ( anesta_is_on( anesta_get_theme_option( 'socials_in_footer' ) ) ) {
	$anesta_output = anesta_get_socials_links();
	if ( '' != $anesta_output ) {
		?>
		<div class="footer_socials_wrap socials_wrap">
			<div class="footer_socials_inner">
				<?php anesta_show_layout( $anesta_output ); ?>
			</div>
		</div>
		<?php
	}
}
