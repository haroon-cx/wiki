<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'anesta_trx_addons_get_css' ) ) {
	add_filter( 'anesta_filter_get_css', 'anesta_trx_addons_get_css', 10, 2 );
	function anesta_trx_addons_get_css( $css, $args ) {

		if ( isset( $css['fonts'] ) && isset( $args['fonts'] ) ) {
			$fonts         = $args['fonts'];
			$css['fonts'] .= <<<CSS

.sc_skills_pie.sc_skills_compact_off .sc_skills_item_title,
.sc_dishes_compact .sc_services_item_title,
.sc_services_iconed .sc_services_item_title {
	{$fonts['p_font-family']}
}
.toc_menu_item .toc_menu_description,
.sc_recent_news .post_item .post_footer .post_meta .post_meta_item,
.sc_item_subtitle,
.sc_icons_item_title,
.sc_price_item_title, .sc_price_item_price,
.sc_courses_default .sc_courses_item_price,
.sc_courses_default .trx_addons_hover_content .trx_addons_hover_links a,
.sc_events_classic .sc_events_item_price,
.sc_events_classic .trx_addons_hover_content .trx_addons_hover_links a,
.sc_promo_modern .sc_promo_link2 span+span,
.sc_skills_counter .sc_skills_total,
.sc_skills_pie.sc_skills_compact_off .sc_skills_total,
.slider_container .slide_info.slide_info_large .slide_title,
.slider_style_modern .slider_controls_label span + span,
.slider_pagination_wrap,
.sc_slider_controller_info {
	{$fonts['h5_font-family']}
}
.sc_recent_news .post_item .post_meta,
.sc_action_item_description,
.sc_price_item_description,
.sc_price_item_details,
.sc_courses_default .sc_courses_item_date,
.courses_single .courses_page_meta,
.sc_events_classic .sc_events_item_date,
.sc_promo_modern .sc_promo_link2 span,
.sc_skills_counter .sc_skills_item_title,
.slider_style_modern .slider_controls_label span,
.slider_titles_outside_wrap .slide_cats,
.slider_titles_outside_wrap .slide_subtitle,
.sc_slider_controller_item_info_date,
.sc_team .sc_team_item_subtitle,
.sc_dishes .sc_dishes_item_subtitle,
.sc_services .sc_services_item_subtitle,
.team_member_page .team_member_brief_info_text,
.sc_testimonials_item_author_title {
	{$fonts['info_font-family']}
}
.slider_outer_wrap .sc_slider_controller .sc_slider_controller_item_info_date {
	{$fonts['info_font-size']}
	{$fonts['info_font-weight']}
	{$fonts['info_font-style']}
	{$fonts['info_line-height']}
	{$fonts['info_text-decoration']}
	{$fonts['info_text-transform']}
	{$fonts['info_letter-spacing']}	
}
.sc_button,
.sc_button.sc_button_simple,
.sc_form button {
	{$fonts['button_font-family']}
	font-size: var(--theme-font-button_font-size); /* replace with {$fonts['button_font-size']} */
	{$fonts['button_font-weight']}
	{$fonts['button_font-style']}
	{$fonts['button_line-height']}
	{$fonts['button_text-decoration']}
	{$fonts['button_text-transform']}
	{$fonts['button_letter-spacing']}
}

.sc_blogger_item_default_over .sc_blogger_item_title  {
	{$fonts['h1_font-family']}
	font-size: var(--theme-font-h1_font-size); /* replace with {$fonts['h1_font-size']} */
	line-height: var(--theme-font-h1_line-height); /* replace with {$fonts['h1_line-height']} */
	letter-spacing: var(--theme-font-h1_letter-spacing); /* replace with {$fonts['h1_letter-spacing']} */
	{$fonts['h1_font-weight']}
	{$fonts['h1_font-style']}
	{$fonts['h1_text-decoration']}
	{$fonts['h1_text-transform']}
}

.sc_price .sc_price_item_price,
.sc_price .sc_price_item_title  {
	{$fonts['h2_font-family']}
	font-size: var(--theme-font-h2_font-size); /* replace with {$fonts['h2_font-size']} */
	{$fonts['h2_font-weight']}
	{$fonts['h2_font-style']}
	{$fonts['h2_line-height']}
	{$fonts['h2_text-decoration']}
	{$fonts['h2_text-transform']}
	{$fonts['h2_letter-spacing']}
}

.sc_blogger_item_default_classic .sc_blogger_item_title,
.sc_blogger_item_default_modern .sc_blogger_item_title,
.sc_icons_default .sc_icons_item_title,
.sc_blogger_band .post_layout_band .post_title  {
	{$fonts['h4_font-family']}
	font-size: var(--theme-font-h4_font-size); /* replace with {$fonts['h4_font-size']} */
	{$fonts['h4_font-weight']}
	{$fonts['h4_font-style']}
	{$fonts['h4_line-height']}
	{$fonts['h4_text-decoration']}
	{$fonts['h4_text-transform']}
	{$fonts['h4_letter-spacing']}
}

.sc_blogger_list_simple .sc_blogger_item_title,
.sc_price_item_subtitle,
.sc_icons_modern .sc_icons_item_title,
.sc_icons_classic .sc_icons_item_title  {
	{$fonts['h5_font-family']}
	font-size: var(--theme-font-h5_font-size); /* replace with {$fonts['h5_font-size']} */
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
