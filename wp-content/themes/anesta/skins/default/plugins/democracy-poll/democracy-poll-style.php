<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'anesta_democracy_poll_get_css' ) ) {
	add_filter( 'anesta_filter_get_css', 'anesta_democracy_poll_get_css', 10, 2 );
	function anesta_democracy_poll_get_css( $css, $args ) {
		if ( isset( $css['fonts'] ) && isset( $args['fonts'] ) ) {
			$fonts         = $args['fonts'];
			$css['fonts'] .= <<<CSS


div.dem-poll-shortcode input[type="submit"].dem-button, 
div.dem-poll-shortcode a.dem-button, 
div.dem-poll-shortcode .dem-button,
div.democracy input[type="submit"].dem-button, 
div.democracy a.dem-button, 
div.democracy .dem-button {
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