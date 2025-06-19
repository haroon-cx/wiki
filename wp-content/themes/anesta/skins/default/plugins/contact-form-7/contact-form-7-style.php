<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'anesta_cf7_get_css' ) ) {
	add_filter( 'anesta_filter_get_css', 'anesta_cf7_get_css', 10, 2 );
	function anesta_cf7_get_css( $css, $args ) {
		if ( isset( $css['fonts'] ) && isset( $args['fonts'] ) ) {
			$fonts         = $args['fonts'];
			$css['fonts'] .= <<<CSS
CSS;
		}

		return $css;
	}
}

