<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'anesta_memberships_get_css' ) ) {
	add_filter( 'anesta_filter_get_css', 'anesta_memberships_get_css', 10, 2 );
	function anesta_memberships_get_css( $css, $args ) {
		if ( isset( $css['fonts'] ) && isset( $args['fonts'] ) ) {
			$fonts         = $args['fonts'];
			$css['fonts'] .= <<<CSS
			
.pmpro_btn, 
.pmpro_btn:link, 
.pmpro_content_message a, 
.pmpro_content_message a:link {
	{$fonts['button_font-family']}
	font-size: var(--theme-font-button_font-size); /* replace with {$fonts['button_font-size']} */
	{$fonts['button_font-weight']}
	{$fonts['button_font-style']}
	{$fonts['button_line-height']}
	{$fonts['button_text-decoration']}
	{$fonts['button_text-transform']}
	{$fonts['button_letter-spacing']}
}

CSS;
		}

		return $css;
	}
}
