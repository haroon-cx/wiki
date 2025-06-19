<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'anesta_epkb_get_css' ) ) {
	add_filter( 'anesta_filter_get_css', 'anesta_epkb_get_css', 10, 2 );
	function anesta_epkb_get_css( $css, $args ) {
		if ( isset( $css['fonts'] ) && isset( $args['fonts'] ) ) {
			$fonts         = $args['fonts'];
			$css['fonts'] .= <<<CSS

.eckb-kb-template #epkb-main-page-container.epkb-tabs-template .epkb-search .epkb-search-box .epkb-search-box_button-wrap button {
	{$fonts['button_font-family']}
	font-size: var(--theme-font-button_font-size); /* replace with {$fonts['button_font-size']} */
	{$fonts['button_font-weight']}
	{$fonts['button_font-style']}
	{$fonts['button_line-height']}
	{$fonts['button_text-decoration']}
	{$fonts['button_text-transform']}
	{$fonts['button_letter-spacing']}
}
.eckb-kb-template #epkb-main-page-container.epkb-tabs-template .epkb-search .epkb-search-box input[type="text"] {	
	{$fonts['input_font-family']}
	{$fonts['input_font-size']}
	{$fonts['input_font-weight']}
	{$fonts['input_font-style']}
	{$fonts['input_line-height']}
	{$fonts['input_text-decoration']}
	{$fonts['input_text-transform']}
	{$fonts['input_letter-spacing']}
}
.eckb-kb-template #epkb-main-page-container.epkb-tabs-template .epkb-doc-search-container__title,
.eckb-kb-template #eckb-article-body #eckb-article-content .eckb-article-title {
	{$fonts['h2_font-family']}
	font-size: var(--theme-font-h2_font-size); /* replace with {$fonts['h2_font-size']} */
	{$fonts['h2_font-weight']}
	{$fonts['h2_font-style']}
	{$fonts['h2_line-height']}
	{$fonts['h2_text-decoration']}
	{$fonts['h2_text-transform']}
	{$fonts['h2_letter-spacing']}
}

.eckb-kb-template #epkb-main-page-container.epkb-tabs-template .epkb-top-category-box .section-head > div,
.eckb-kb-template #epkb-main-page-container.epkb-tabs-template .epkb-panel-container .section-head h3,
.eckb-kb-template #epkb-main-page-container.epkb-tabs-template .epkb-doc-search-container .epkb-search-results-message {
	{$fonts['h4_font-family']}
	font-size: var(--theme-font-h4_font-size); /* replace with {$fonts['h4_font-size']} */
	{$fonts['h4_font-weight']}
	{$fonts['h4_font-style']}
	{$fonts['h4_line-height']}
	{$fonts['h4_text-decoration']}
	{$fonts['h4_text-transform']}
	{$fonts['h4_letter-spacing']}
}

#eckb-article-page-container-v2 .eckb-article-toc .eckb-article-toc__inner .eckb-article-toc__title {
	{$fonts['h5_font-family']}
	font-size: var(--theme-font-h5_font-size) !important; /* replace with {$fonts['h5_font-size']} */
	{$fonts['h5_font-weight']}
	{$fonts['h5_font-style']}
	{$fonts['h5_line-height']}
	{$fonts['h5_text-decoration']}
	{$fonts['h5_text-transform']}
	{$fonts['h5_letter-spacing']}
}

CSS;
		}

		return $css;
	}
}