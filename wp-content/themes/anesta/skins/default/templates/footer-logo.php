<?php
/**
 * The template to display the site logo in the footer
 *
 * @package ANESTA
 * @since ANESTA 1.0.10
 */

// Logo
if ( anesta_is_on( anesta_get_theme_option( 'logo_in_footer' ) ) ) {
	$anesta_logo_image = anesta_get_logo_image( 'footer' );
	$anesta_logo_text  = get_bloginfo( 'name' );
	if ( ! empty( $anesta_logo_image['logo'] ) || ! empty( $anesta_logo_text ) ) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if ( ! empty( $anesta_logo_image['logo'] ) ) {
					$anesta_attr = anesta_getimagesize( $anesta_logo_image['logo'] );
					echo '<a href="' . esc_url( home_url( '/' ) ) . '">'
							. '<img src="' . esc_url( $anesta_logo_image['logo'] ) . '"'
								. ( ! empty( $anesta_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $anesta_logo_image['logo_retina'] ) . ' 2x"' : '' )
								. ' class="logo_footer_image"'
								. ' alt="' . esc_attr__( 'Site logo', 'anesta' ) . '"'
								. ( ! empty( $anesta_attr[3] ) ? ' ' . wp_kses_data( $anesta_attr[3] ) : '' )
							. '>'
						. '</a>';
				} elseif ( ! empty( $anesta_logo_text ) ) {
					echo '<h1 class="logo_footer_text">'
							. '<a href="' . esc_url( home_url( '/' ) ) . '">'
								. esc_html( $anesta_logo_text )
							. '</a>'
						. '</h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
