<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'anesta_bp_messages_get_css' ) ) {
	add_filter( 'anesta_filter_get_css', 'anesta_bp_messages_get_css', 10, 2 );
	function anesta_bp_messages_get_css( $css, $args ) {
		if ( isset( $css['fonts'] ) && isset( $args['fonts'] ) ) {
			$fonts         = $args['fonts'];
			$css['fonts'] .= <<<CSS

			
.bp-messages .bp-messages-wrap .chat-header .bpbm-search form input[type="text"],
.bp-messages .bp-messages-wrap .new-message form > div input, 
.bp-messages .bp-messages-wrap .new-message form > div textarea,
.bp-emojionearea, 
.bp-emojionearea.form-control,
.bp-messages-wrap .taggle_list,
#better-messages-modals-container .bm-modal-window .bm-modal-window-content .bm_user_selector .bm_user_selector__control {
	{$fonts['input_font-family']}
	{$fonts['input_font-size']}
	{$fonts['input_font-weight']}
	{$fonts['input_font-style']}
	{$fonts['input_line-height']}
	{$fonts['input_text-decoration']}
	{$fonts['input_text-transform']}
	{$fonts['input_letter-spacing']}
}
.bp-messages .bp-messages-wrap .threads-list .info .name {
	{$fonts['h6_font-family']}
	{$fonts['h6_font-size']}
	{$fonts['h6_font-weight']}
	{$fonts['h6_font-style']}
	{$fonts['h6_line-height']}
	{$fonts['h6_text-decoration']}
	{$fonts['h6_text-transform']}
	{$fonts['h6_letter-spacing']}
}
.bp-messages .bp-messages-wrap .bpbm-user-option-title {
	font-size: var(--theme-font-h4_font-size); /* replace with {$fonts['h4_font-size']} */
}

CSS;
		}

		return $css;
	}
}