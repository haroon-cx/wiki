<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package ANESTA
 * @since ANESTA 1.0.10
 */

// Copyright area
?> 
<div class="footer_copyright_wrap
<?php
$anesta_copyright_scheme = anesta_get_theme_option( 'copyright_scheme' );
if ( ! empty( $anesta_copyright_scheme ) && ! anesta_is_inherit( $anesta_copyright_scheme  ) ) {
	echo ' scheme_' . esc_attr( $anesta_copyright_scheme );
}
?>
				">
	<div class="footer_copyright_inner">
		<div class="content_wrap">
			<div class="copyright_text">
			<?php
				$anesta_copyright = anesta_get_theme_option( 'copyright' );
			if ( ! empty( $anesta_copyright ) ) {
				// Replace {{Y}} or {Y} with the current year
				$anesta_copyright = str_replace( array( '{{Y}}', '{Y}' ), date( 'Y' ), $anesta_copyright );
				// Replace {{...}} and ((...)) on the <i>...</i> and <b>...</b>
				$anesta_copyright = anesta_prepare_macros( $anesta_copyright );
				// Display copyright
				echo wp_kses( nl2br( $anesta_copyright ), 'anesta_kses_content' );
			}
			?>
			</div>
		</div>
	</div>
</div>
