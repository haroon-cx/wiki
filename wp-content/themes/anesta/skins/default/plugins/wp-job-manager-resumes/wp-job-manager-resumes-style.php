<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'anesta_job_manager_resumes_get_css' ) ) {
	add_filter( 'anesta_filter_get_css', 'anesta_job_manager_resumes_get_css', 10, 2 );
	function anesta_job_manager_resumes_get_css( $css, $args ) {
		if ( isset( $css['fonts'] ) && isset( $args['fonts'] ) ) {
			$fonts         = $args['fonts'];
			$css['fonts'] .= <<<CSS

.single-resume-content .resume_contact .resume_contact_button,
.related_wrap .resume-card .resume_contact .resume_contact_button,
#submit-resume-form > fieldset:first-of-type .button {
	{$fonts['button_font-family']}
	font-size: var(--theme-font-button_font-size); /* replace with {$fonts['button_font-size']} */
	{$fonts['button_font-weight']}
	{$fonts['button_font-style']}
	{$fonts['button_line-height']}
	{$fonts['button_text-decoration']}
	{$fonts['button_text-transform']}
	{$fonts['button_letter-spacing']}
} 

.resume_preview > h1 {  
	{$fonts['h2_font-family']}
	font-size: var(--theme-font-h2_font-size); /* replace with {$fonts['h2_font-size']} */
	{$fonts['h2_font-weight']}
	{$fonts['h2_font-style']}
	{$fonts['h2_line-height']}
	{$fonts['h2_text-decoration']}
	{$fonts['h2_text-transform']}
	{$fonts['h2_letter-spacing']}
}

.resume_description ~ h2 {
	{$fonts['h5_font-family']}
	font-size: var(--theme-font-h5_font-size); /* replace with {$fonts['h5_font-size']} */
	{$fonts['h5_font-weight']}
	{$fonts['h5_font-style']}
	{$fonts['h5_line-height']}
	{$fonts['h5_text-decoration']}
	{$fonts['h5_text-transform']}
	{$fonts['h5_letter-spacing']}
	{$fonts['h5_margin-top']}
	{$fonts['h5_margin-bottom']}
}

div.resumes ul.resumes h3,
#resume-manager-candidate-dashboard .resume-title > a {
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