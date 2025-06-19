<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'anesta_job_manager_get_css' ) ) {
	add_filter( 'anesta_filter_get_css', 'anesta_job_manager_get_css', 10, 2 );
	function anesta_job_manager_get_css( $css, $args ) {
		if ( isset( $css['fonts'] ) && isset( $args['fonts'] ) ) {
			$fonts         = $args['fonts'];
			$css['fonts'] .= <<<CSS


div.job_listings .load_more_job,
.single_job_listing .job_application .button,
#submit-job-form .fieldset-logged_in .button,
#submit-job-form .fieldset-login_required .button {
	{$fonts['button_font-family']}
	font-size: var(--theme-font-button_font-size); /* replace with {$fonts['button_font-size']} */
	{$fonts['button_font-weight']}
	{$fonts['button_font-style']}
	{$fonts['button_line-height']}
	{$fonts['button_text-decoration']}
	{$fonts['button_text-transform']}
	{$fonts['button_letter-spacing']}
}
div.job_listings[data-title]:before,
#submit-job-form h2 {
	{$fonts['h3_font-family']}
	{$fonts['h3_font-weight']}
	font-size: var(--theme-font-h3_font-size); /* replace with {$fonts['h3_font-size']} */
	{$fonts['h3_font-style']}
	{$fonts['h3_line-height']}
	{$fonts['h3_text-decoration']}
	{$fonts['h3_text-transform']}
	{$fonts['h3_letter-spacing']}
}
.job_summary_shortcode .job_summary_title {
	{$fonts['h4_font-family']}
	{$fonts['h4_font-weight']}
	font-size: var(--theme-font-h4_font-size); /* replace with {$fonts['h4_font-size']} */
	{$fonts['h4_font-style']}
	{$fonts['h4_line-height']}
	{$fonts['h4_text-decoration']}
	{$fonts['h4_text-transform']}
	{$fonts['h4_letter-spacing']}
}
div.job_listings ul.job_listings h3,
.wp-widget-widget_recent_jobs ul.job_listings h3,
#job-manager-job-dashboard .job_title > a {
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