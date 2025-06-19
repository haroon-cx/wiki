<?php
/**
 * The template to display the logo or the site name and the slogan in the Header
 *
 * @package ANESTA
 * @since ANESTA 1.0
 */

$anesta_args = get_query_var( 'anesta_logo_args' );

// Site logo
$anesta_logo_type   = isset( $anesta_args['type'] ) ? $anesta_args['type'] : '';
$anesta_logo_image  = anesta_get_logo_image( $anesta_logo_type );
$anesta_logo_text   = anesta_is_on( anesta_get_theme_option( 'logo_text' ) ) ? get_bloginfo( 'name' ) : '';
$anesta_logo_slogan = get_bloginfo( 'description', 'display' );
if ( ! empty( $anesta_logo_image['logo'] ) || ! empty( $anesta_logo_text ) ) {
	?><a class="sc_layouts_logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php
		if ( ! empty( $anesta_logo_image['logo'] ) ) {
			if ( empty( $anesta_logo_type ) && function_exists( 'the_custom_logo' ) && is_numeric( $anesta_logo_image['logo'] ) && (int) $anesta_logo_image['logo'] > 0 ) {
				the_custom_logo();
			} else {
				$anesta_attr = anesta_getimagesize( $anesta_logo_image['logo'] );
				echo '<img src="' . esc_url( $anesta_logo_image['logo'] ) . '"'
						. ( ! empty( $anesta_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $anesta_logo_image['logo_retina'] ) . ' 2x"' : '' )
						. ' alt="' . esc_attr( $anesta_logo_text ) . '"'
						. ( ! empty( $anesta_attr[3] ) ? ' ' . wp_kses_data( $anesta_attr[3] ) : '' )
						. '>';
			}
		} else {
			anesta_show_layout( anesta_prepare_macros( $anesta_logo_text ), '<span class="logo_text">', '</span>' );
			anesta_show_layout( anesta_prepare_macros( $anesta_logo_slogan ), '<span class="logo_slogan">', '</span>' );
		}
		?>
	</a>
	<?php
}
