<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'anesta_sfwd_lms_get_css' ) ) {
	add_filter( 'anesta_filter_get_css', 'anesta_sfwd_lms_get_css', 10, 2 );
	function anesta_sfwd_lms_get_css( $css, $args ) {
		if ( isset( $css['fonts'] ) && isset( $args['fonts'] ) ) {
			$fonts         = $args['fonts'];
			$css['fonts'] .= <<<CSS


.ld-course-list-items .ld_course_grid .course .caption .ld_course_grid_button .btn,
.learndash-wrapper .ld-alert-certificate .ld-button,
.bp-group-discussion a,
.ld-info .ld-status,
.learndash-wrapper .ld-content-actions .ld-content-action a,
.learndash-wrapper .ld-content-actions .ld-content-action input[type="submit"],
.learndash-wrapper .ld-content-actions > .ld-primary-color,
.learndash-wrapper .wpProQuiz_content input[type="button"],
.learndash-wrapper .wpProQuiz_content a#quiz_continue_link {
	{$fonts['button_font-family']}
	font-size: var(--theme-font-button_font-size); /* replace with {$fonts['button_font-size']} */
	{$fonts['button_font-weight']}
	{$fonts['button_font-style']}
	{$fonts['button_line-height']}
	{$fonts['button_text-decoration']}
	{$fonts['button_text-transform']}
	{$fonts['button_letter-spacing']}
}

#learndash_profile #course_list h4 .flip {	
	{$fonts['p_font-family']}
	{$fonts['p_font-size']}
	{$fonts['p_font-weight']}
	{$fonts['p_font-style']}
	{$fonts['p_line-height']}
	{$fonts['p_text-decoration']}
	{$fonts['p_text-transform']}
	{$fonts['p_letter-spacing']}
}

.ld-course-list-items .ld_course_grid h3 {
	{$fonts['h4_font-family']}
	font-size: var(--theme-font-h4_font-size); /* replace with {$fonts['h4_font-size']} */
	{$fonts['h4_font-weight']}
	{$fonts['h4_font-style']}
	{$fonts['h4_line-height']}
	{$fonts['h4_text-decoration']}
	{$fonts['h4_text-transform']}
	{$fonts['h4_letter-spacing']}
}

.learndash-wrapper .ld-item-list.ld-course-list .ld-section-heading h2, 
.learndash-wrapper .ld-item-list.ld-lesson-list .ld-section-heading h2,
#learndash_profile #course_list h4 {
	{$fonts['h5_font-family']}
	font-size: var(--theme-font-h5_font-size); /* replace with {$fonts['h5_font-size']} */
	{$fonts['h5_font-weight']}
	{$fonts['h5_font-style']}
	{$fonts['h5_line-height']}
	{$fonts['h5_text-decoration']}
	{$fonts['h5_text-transform']}
	{$fonts['h5_letter-spacing']}
}

.learndash-wrapper .ld-item-list.ld-course-list .ld-lesson-section-heading, 
.learndash-wrapper .ld-item-list.ld-lesson-list .ld-lesson-section-heading {
	{$fonts['h6_font-family']}
	{$fonts['h6_font-size']}
	{$fonts['h6_font-style']}
	{$fonts['h6_line-height']}
	{$fonts['h6_text-decoration']}
	{$fonts['h6_text-transform']}
	{$fonts['h6_letter-spacing']}
}

CSS;
		}

		return $css;
	}
}