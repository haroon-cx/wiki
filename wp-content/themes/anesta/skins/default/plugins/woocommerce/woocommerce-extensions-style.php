<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'anesta_woocommerce_extensions_get_css' ) ) {
	add_filter( 'anesta_filter_get_css', 'anesta_woocommerce_extensions_get_css', 10, 2 );
	function anesta_woocommerce_extensions_get_css( $css, $args ) {

		if ( isset( $css['fonts'] ) && isset( $args['fonts'] ) ) {
			$fonts         = $args['fonts'];
			$css['fonts'] .= <<<CSS

.woocommerce-accordion.anesta_accordion .woocommerce-accordion-title,
.woocommerce-accordion.anesta_accordion .woocommerce-accordion-content h2,
.woocommerce-accordion.anesta_accordion .woocommerce-accordion-content h3,
.woocommerce #reviews .rating_details .rating_details_title {
	{$fonts['h5_font-family']}
	font-size: var(--theme-font-h5_font-size); /* replace with {$fonts['h5_font-size']} */
	{$fonts['h5_font-weight']}
	{$fonts['h5_font-style']}
	{$fonts['h5_line-height']}
	{$fonts['h5_text-decoration']}
	{$fonts['h5_text-transform']}
	{$fonts['h5_letter-spacing']}
}
.woocommerce #reviews .rating_details .rating_details_title,
.woocommerce #reviews #comments .woocommerce-Reviews-title {
	{$fonts['h3_font-family']}
	font-size: var(--theme-font-h3_font-size); /* replace with {$fonts['h3_font-size']} */
	{$fonts['h3_font-weight']}
	{$fonts['h3_font-style']}
	{$fonts['h3_line-height']}
	{$fonts['h3_text-decoration']}
	{$fonts['h3_text-transform']}
	{$fonts['h3_letter-spacing']}
}

CSS;
		}

		return $css;
	}
}
