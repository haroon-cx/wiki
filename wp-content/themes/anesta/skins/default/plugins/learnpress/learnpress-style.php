<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'anesta_learnpress_get_css' ) ) {
    add_filter( 'anesta_filter_get_css', 'anesta_learnpress_get_css', 10, 2 );
    function anesta_learnpress_get_css( $css, $args ) {

        if ( isset( $css['fonts'] ) && isset( $args['fonts'] ) ) {
            $fonts         = $args['fonts'];
            $css['fonts'] .= <<<CSS

.learnpress .learn-press-form-login button[type="submit"],
.learnpress .learn-press-form-register button[type="submit"],
.learnpress #learn-press-profile-basic-information button[type="submit"],
.learnpress-page .lp-button,
.learnpress-page #lp-button,
.lp-user-profile .lp-profile-content .lp-button,
#popup-course #popup-header .lp-button,
#popup-course #popup-content .lp-button,
#popup-course #popup-content .lp-button.completed,
.learnpress.widget_course_featured .lp-widget-featured-courses__footer__link, 
.elementor-widget-wp-widget-learnpress_widget_course_featured .lp-widget-featured-courses__footer__link,
.learnpress.widget_course_recent .lp-widget-recent-courses__footer__link, 
.elementor-widget-wp-widget-learnpress_widget_course_recent .lp-widget-recent-courses__footer__link,
.learnpress.widget_course_popular .lp-widget-popular-courses__footer__link,
.elementor-widget-wp-widget-learnpress_widget_course_popular .lp-widget-popular-courses__footer__link,
.lp-archive-courses .learn-press-courses[data-layout="list"] .course .course-item .course-content .course-readmore a {
	{$fonts['button_font-family']}
	{$fonts['button_font-size']}
	{$fonts['button_font-weight']}
	{$fonts['button_font-style']}
	{$fonts['button_line-height']}
	{$fonts['button_text-decoration']}
	{$fonts['button_text-transform']}
	{$fonts['button_letter-spacing']}
}
#popup-course #popup-footer .course-item-nav a,
.learn-press-course-tab-enrolled .lp_profile_course_progress__item a,
.learnpress.widget_course_info .lp_widget_course_info ul label, .elementor-widget-wp-widget-learnpress_widget_course_info .lp_widget_course_info ul label,
#profile-content .recover-order__title,
#learn-press-course .course-sidebar-preview .course-time .course-time-row strong,
#learn-press-profile .wrapper-profile-header .lp-profile-username,
#learn-press-profile .statistic-box .statistic-box__text,
#learn-press-profile #profile-nav .lp-profile-nav-tabs > li a,
.learn-press-profile-course__tab__inner a,
.learn-press-filters > li a,
#popup-course #popup-footer .course-item-nav .course-item-nav__name,
#popup-course #popup-sidebar .course-item .item-name,
.course-tab-panel-faqs .course-faqs-box__title,
.lp-widget-course__meta,
.learn-press-courses .course .course-price .price, .learnpress-widget-wrapper .lp-widget-course__price, .course-summary-sidebar .course-price,
#learn-press-course-tabs .course-nav label,
.lp-badge.featured-course,
.lp-archive-courses .course .course-item .course-content .course-categories a,
.lp-archive-courses .course .course-item .course-content .meta-item,
.lp-archive-courses .course-summary .course-summary-content .course-detail-info .course-info-left .course-meta .course-meta__pull-left .meta-item {
	{$fonts['h5_font-family']}
}
#learn-press-profile.lp-user-profile .wrapper-profile-header .lp-profile-content-area .lp-profile-user-bio,
#learn-press-checkout .lp-terms-and-conditions,
.lp-checkout-form .lp-guest-switch-login,
.content-item-wrap .quiz-content,
.course-tab-panel-faqs .course-faqs-box__content-inner,
.course-tab-panel .course-description,
.course-extra-box__content li,
.learnpress-widget-wrapper .lp-widget-course__description,
#popup-course #popup-content #learn-press-content-item .content-item-wrap .content-item-summary .content-item-description p,
.lp-archive-courses .learn-press-courses .course .course-item .course-content .course-info .course-excerpt,
.lp-archive-courses .learn-press-courses .course .course-item .course-content .course-info,
#learn-press-course .lp-course-author .course-author__pull-right .author-description,
#learn-press-course-tabs.course-tabs #learn-press-course-curriculum.course-curriculum ul.curriculum-sections .section-header .section-desc,
#learn-press-course-tabs .course-tab-panels .course-tab-panel .course-description p {
	{$fonts['p_font-family']}
	{$fonts['p_font-size']}
	{$fonts['p_font-weight']}
	{$fonts['p_line-height']}
	{$fonts['p_font-style']}
}
.learnpress #learn-press-profile-basic-information .form-fields .form-field input[type="text"],
.learnpress #learn-press-profile-basic-information .form-fields .form-field input[type="email"],
.learnpress #learn-press-profile-basic-information .form-fields .form-field input[type="number"],
.learnpress #learn-press-profile-basic-information .form-fields .form-field input[type="password"], 
.learnpress #learn-press-profile-basic-information .form-fields .form-field textarea, 
.learnpress .learn-press-form .form-fields .form-field input[type="text"],
.learnpress .learn-press-form .form-fields .form-field input[type="email"],
.learnpress .learn-press-form .form-fields .form-field input[type="number"], 
.learnpress .learn-press-form .form-fields .form-field input[type="password"],
.learnpress .learn-press-form .form-fields .form-field textarea,
div.order-recover input[type="text"],
#popup-course #popup-sidebar .search-course input[name="s"] {
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