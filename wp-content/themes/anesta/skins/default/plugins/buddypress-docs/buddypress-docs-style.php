<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'anesta_bp_docs_get_css' ) ) {
	add_filter( 'anesta_filter_get_css', 'anesta_bp_docs_get_css', 10, 2 );
	function anesta_bp_docs_get_css( $css, $args ) {
		if ( isset( $css['fonts'] ) && isset( $args['fonts'] ) ) {
			$fonts         = $args['fonts'];
			$css['fonts'] .= <<<CSS

div#buddypress .bp-docs #bp-create-doc-button,
div#buddypress .bp-docs .wp-switch-editor,
div#buddypress .bp-docs .tablenav input[type="submit"],
.bp-docs #doc-form #doc-submit-options .action,
.bp-docs #doc-form #doc-submit-options .delete-doc-button,
.bp-docs #doc-form #doc-attachments-ul .button,
div#buddypress .bp-docs .folder-action-links,
div#buddypress .bp-docs .create-new-folder form input[type="submit"],
div#buddypress .bp-docs .docs-folder-manage form input[type="submit"],
div#buddypress .bp-docs .docs-folder-manage form .folder-delete {
	{$fonts['button_font-family']}
	font-size: var(--theme-font-button_font-size); /* replace with {$fonts['button_font-size']} */ 
	{$fonts['button_font-weight']}
	{$fonts['button_font-style']}
	{$fonts['button_line-height']}
	{$fonts['button_text-decoration']}
	{$fonts['button_text-transform']}
	{$fonts['button_letter-spacing']}
}
#docs-filter-section-search form #docs-search,
div#buddypress .bp-docs [name="new-folder"] {
	{$fonts['input_font-family']}
	{$fonts['input_font-size']}
	{$fonts['input_font-weight']}
	{$fonts['input_font-style']}
	{$fonts['input_line-height']}
	{$fonts['input_text-decoration']}
	{$fonts['input_text-transform']}
	{$fonts['input_letter-spacing']}
}

CSS;
		}

		return $css;
	}
}