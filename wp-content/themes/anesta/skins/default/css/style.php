<?php
/**
 * Generate custom CSS
 *
 * @package ANESTA
 * @since ANESTA 1.0
 */

// Return CSS with custom colors and fonts
if ( ! function_exists( 'anesta_customizer_get_css' ) ) {

	function anesta_customizer_get_css( $args = array() ) {

		$colors        = isset( $args['colors'] ) ? $args['colors'] : null;
		$scheme        = isset( $args['scheme'] ) ? $args['scheme'] : null;
		$fonts         = isset( $args['fonts'] ) ? $args['fonts'] : null;
		$vars          = isset( $args['vars'] ) ? $args['vars'] : null;
		$remove_spaces = isset( $args['remove_spaces'] ) ? $args['remove_spaces'] : true;

		$css = array(
			'vars'   => '',
			'fonts'  => '',
			'colors' => '',
		);

		// Theme fonts
		//---------------------------------------------
		if ( null === $fonts ) {
			$fonts = anesta_get_theme_fonts();
		}

		if ( $fonts ) {

			// Make theme-specific fonts rules
			$fonts        = anesta_customizer_add_theme_fonts( $fonts );
			$rez          = array();
			$article_font = ( ! empty( $fonts['post_font-family'] )
								|| ! empty( $fonts['post_font-weight'] )
								|| ! empty( $fonts['post_font-style'] )
								|| ! empty( $fonts['post_text-decoration'] )
								|| ! empty( $fonts['post_text-transform'] )
								|| ! empty( $fonts['post_letter-spacing'] )
								? "
/* Article text*/
.post_item_single.post_type_post .post_content_single,
body.post-type-post .block-editor-block-list__layout {
	{$fonts['post_font-family']}
	{$fonts['post_font-weight']}
	{$fonts['post_font-style']}
	{$fonts['post_text-decoration']}
	{$fonts['post_text-transform']}
	{$fonts['post_letter-spacing']}
}
"
								: ''
							)
							. ( ! empty( $fonts['post_margin-top'] )
								|| ! empty( $fonts['post_margin-bottom'] )
								? "
.post_item_single.post_type_post .post_content_single p,
.post_item_single.post_type_post .post_content_single ul,
.post_item_single.post_type_post .post_content_single ol,
.post_item_single.post_type_post .post_content_single dl,
.post_item_single.post_type_post .post_content_single table,
.post_item_single.post_type_post .post_content_single blockquote,
.post_item_single.post_type_post .post_content_single address,
.post_item_single.post_type_post .post_content_single .wp-block-button,
.post_item_single.post_type_post .post_content_single .wp-block-cover,
.post_item_single.post_type_post .post_content_single .wp-block-image,
.post_item_single.post_type_post .post_content_single .wp-block-video,
.post_item_single.post_type_post .post_content_single .wp-block-media-text,
body.post-type-post .block-editor-block-list__layout p,
body.post-type-post .block-editor-block-list__layout ul,
body.post-type-post .block-editor-block-list__layout ol,
body.post-type-post .block-editor-block-list__layout dl,
body.post-type-post .block-editor-block-list__layout table,
body.post-type-post .block-editor-block-list__layout blockquote,
body.post-type-post .block-editor-block-list__layout address,
body.post-type-post .block-editor-block-list__layout .wp-block-button,
body.post-type-post .block-editor-block-list__layout .wp-block-cover,
body.post-type-post .block-editor-block-list__layout .wp-block-image,
body.post-type-post .block-editor-block-list__layout .wp-block-video,
body.post-type-post .block-editor-block-list__layout .wp-block-media-text {
	{$fonts['post_margin-top']}
	{$fonts['post_margin-bottom']}
}
"
								: ''
							)
							. ( ! empty( $fonts['post_font-size'] )
								? '
.post_item_single.post_type_post .post_content_single p:not([class*="-font-size"]):not(.wp-block-cover-text),
.post_item_single.post_type_post .post_content_single ul:not([class*="-font-size"]),
.post_item_single.post_type_post .post_content_single ol:not([class*="-font-size"]),
.post_item_single.post_type_post .post_content_single dl:not([class*="-font-size"]),
.post_item_single.post_type_post .post_content_single table:not([class*="-font-size"]),
.post_item_single.post_type_post .post_content_single blockquote:not([class*="-font-size"]),
.post_item_single.post_type_post .post_content_single address:not([class*="-font-size"]),
.post_item_single.post_type_post .post_content_single .sc_blogger_item_excerpt,
body.post-type-post .block-editor-block-list__layout p:not([class*="-font-size"]):not(.wp-block-cover-text),
body.post-type-post .block-editor-block-list__layout ul:not([class*="-font-size"]),
body.post-type-post .block-editor-block-list__layout ol:not([class*="-font-size"]),
body.post-type-post .block-editor-block-list__layout dl:not([class*="-font-size"]),
body.post-type-post .block-editor-block-list__layout table:not([class*="-font-size"]),
body.post-type-post .block-editor-block-list__layout blockquote:not([class*="-font-size"]),
body.post-type-post .block-editor-block-list__layout address:not([class*="-font-size"]),
body.post-type-post .block-editor-block-list__layout .sc_blogger_item_excerpt {
' . $fonts['post_font-size'] . '
}
.post_item_single.post_type_post .post_content_single form p:not([style*="font-size"]) {
	font-size: 1em;
}
'
								: ''
							)
							. ( ! empty( $fonts['post_line-height'] )
								? '
.post_item_single.post_type_post .post_content_single p:not([style*="font-size"]):not(.wp-block-cover-text),
.post_item_single.post_type_post .post_content_single ul:not([style*="font-size"]),
.post_item_single.post_type_post .post_content_single ol:not([style*="font-size"]),
.post_item_single.post_type_post .post_content_single dl:not([style*="font-size"]),
.post_item_single.post_type_post .post_content_single table:not([style*="font-size"]),
.post_item_single.post_type_post .post_content_single blockquote:not([style*="font-size"]),
.post_item_single.post_type_post .post_content_single address:not([style*="font-size"]),
body.post-type-post .block-editor-block-list__layout p:not([style*="font-size"]):not(.wp-block-cover-text),
body.post-type-post .block-editor-block-list__layout ul:not([style*="font-size"]),
body.post-type-post .block-editor-block-list__layout ol:not([style*="font-size"]),
body.post-type-post .block-editor-block-list__layout dl:not([style*="font-size"]),
body.post-type-post .block-editor-block-list__layout table:not([style*="font-size"]),
body.post-type-post .block-editor-block-list__layout blockquote:not([style*="font-size"]),
body.post-type-post .block-editor-block-list__layout address:not([style*="font-size"]) {
' . $fonts['post_line-height'] . '
}
'
								: ''
							);

			$rez['fonts'] = <<<CSS

/* Main text*/
body {
	{$fonts['p_font-family']}
	{$fonts['p_font-size']}
	{$fonts['p_font-weight']}
	{$fonts['p_font-style']}
	{$fonts['p_line-height']}
	{$fonts['p_text-decoration']}
	{$fonts['p_text-transform']}
	{$fonts['p_letter-spacing']}
}

p[style*="font-size"],	/* tag p need if custom font size to the paragraph is applied. Thanks to @goodkindman */
.has-small-font-size,
.has-normal-font-size,
.has-medium-font-size {
	{$fonts['p_line-height']}	
}

/* Article text*/
{$article_font}

h1, .front_page_section_caption {
	{$fonts['h1_font-family']}
	font-size: var(--theme-font-h1_font-size); /* replace with {$fonts['h1_font-size']} */
	line-height: var(--theme-font-h1_line-height); /* replace with {$fonts['h1_line-height']} */
	letter-spacing: var(--theme-font-h1_letter-spacing); /* replace with {$fonts['h1_letter-spacing']} */
	{$fonts['h1_font-weight']}
	{$fonts['h1_font-style']}
	{$fonts['h1_text-decoration']}
	{$fonts['h1_text-transform']}
	{$fonts['h1_margin-top']}
	{$fonts['h1_margin-bottom']}
}
.top_panel .slider_engine_revo .slide_title {
	{$fonts['h1_font-family']}
}
h2, .post_item_404 .page_subtitle {
	{$fonts['h2_font-family']}
	font-size: var(--theme-font-h2_font-size); /* replace with {$fonts['h2_font-size']} */
	{$fonts['h2_font-weight']}
	{$fonts['h2_font-style']}
	{$fonts['h2_line-height']}
	{$fonts['h2_text-decoration']}
	{$fonts['h2_text-transform']}
	{$fonts['h2_letter-spacing']}
	{$fonts['h2_margin-top']}
	{$fonts['h2_margin-bottom']}
}
.sc_layouts_title_caption {
	font-size: var(--theme-font-h2_font-size); /* replace with {$fonts['h2_font-size']} */
	{$fonts['h2_line-height']}
}
h3 {
	{$fonts['h3_font-family']}
	font-size: var(--theme-font-h3_font-size); /* replace with {$fonts['h3_font-size']} */
	{$fonts['h3_font-weight']}
	{$fonts['h3_font-style']}
	{$fonts['h3_line-height']}
	{$fonts['h3_text-decoration']}
	{$fonts['h3_text-transform']}
	{$fonts['h3_letter-spacing']}
	{$fonts['h3_margin-top']}
	{$fonts['h3_margin-bottom']}
}
.posts_header .sc_layouts_title_caption {
	font-size: var(--theme-font-h3_font-size); /* replace with {$fonts['h3_font-size']} */
	{$fonts['h3_line-height']}
}
.widget .widget_title, 
.widget .widgettitle,
.widget .wp-block-search__label {
	{$fonts['h3_font-family']}
	font-size: var(--theme-font-h3_font-size); /* replace with {$fonts['h3_font-size']} */
	{$fonts['h3_font-weight']}
	{$fonts['h3_font-style']}
	{$fonts['h3_line-height']}
	{$fonts['h3_text-decoration']}
	{$fonts['h3_text-transform']}
	{$fonts['h3_letter-spacing']}
}
h4 {
	{$fonts['h4_font-family']}
	font-size: var(--theme-font-h4_font-size); /* replace with {$fonts['h4_font-size']} */
	{$fonts['h4_font-weight']}
	{$fonts['h4_font-style']}
	{$fonts['h4_line-height']}
	{$fonts['h4_text-decoration']}
	{$fonts['h4_text-transform']}
	{$fonts['h4_letter-spacing']}
	{$fonts['h4_margin-top']}
	{$fonts['h4_margin-bottom']}
}
h5 {
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
blockquote,
mark, ins,
.logo_text,
.post_price.price,
.theme_scroll_down {
	{$fonts['h5_font-family']}
}
table caption,
.nav-links-single .nav-links .post-title {
	{$fonts['h5_font-family']}
	font-size: var(--theme-font-h5_font-size); /* replace with {$fonts['h5_font-size']} */
	{$fonts['h5_font-weight']}
	{$fonts['h5_font-style']}
	{$fonts['h5_line-height']}
	{$fonts['h5_text-decoration']}
	{$fonts['h5_text-transform']}
	{$fonts['h5_letter-spacing']}
}
h6 {
	{$fonts['h6_font-family']}
	{$fonts['h6_font-size']}
	{$fonts['h6_font-weight']}
	{$fonts['h6_font-style']}
	{$fonts['h6_line-height']}
	{$fonts['h6_text-decoration']}
	{$fonts['h6_text-transform']}
	{$fonts['h6_letter-spacing']}
	{$fonts['h6_margin-top']}
	{$fonts['h6_margin-bottom']}
}

input[type="text"],
input[type="number"],
input[type="email"],
input[type="url"],
input[type="tel"],
input[type="search"],
input[type="password"],
textarea,
textarea.wp-editor-area,
.select_container,
select,
.select_container select,
.select2-container.select2-container--default span.select2-choice,
.select2-container.select2-container--default span.select2-selection {
	{$fonts['input_font-family']}
	{$fonts['input_font-size']}
	{$fonts['input_font-weight']}
	{$fonts['input_font-style']}
	{$fonts['input_line-height']}
	{$fonts['input_text-decoration']}
	{$fonts['input_text-transform']}
	{$fonts['input_letter-spacing']}
}

form button:not(.components-button),
input[type="button"],
input[type="reset"],
input[type="submit"],
.theme_button,
.sc_layouts_row .sc_button,
.sc_portfolio_preview_show .post_readmore,
.wp-block-button__link,
.post_item .more-link,

.mptt-navigation-tabs li a,
.anesta_tabs .anesta_tabs_titles li a {
	{$fonts['button_font-family']}
	font-size: var(--theme-font-button_font-size); /* replace with {$fonts['button_font-size']} */
	{$fonts['button_font-weight']}
	{$fonts['button_font-style']}
	{$fonts['button_line-height']}
	{$fonts['button_text-decoration']}
	{$fonts['button_text-transform']}
	{$fonts['button_letter-spacing']}
}

.post_meta {
	{$fonts['info_font-family']}
	{$fonts['info_font-size']}
	{$fonts['info_font-weight']}
	{$fonts['info_font-style']}
	{$fonts['info_line-height']}
	{$fonts['info_text-decoration']}
	{$fonts['info_text-transform']}
	{$fonts['info_letter-spacing']}
	{$fonts['info_margin-top']}
	{$fonts['info_margin-bottom']}
}


.post-date, .rss-date,
.post_date, .post_meta_item,
.post_meta .vc_inline-link,
.comments_list_wrap .comment_date,
.comments_list_wrap .comment_time,
.comments_list_wrap .comment_counters,
.top_panel .slider_engine_revo .slide_subtitle,
.logo_slogan,
fieldset legend,
figure figcaption,
.wp-caption .wp-caption-text,
.wp-caption .wp-caption-dd,
.wp-caption-overlay .wp-caption .wp-caption-text,
.wp-caption-overlay .wp-caption .wp-caption-dd,
.format-audio .post_featured .post_audio_author,
.trx_addons_audio_player .audio_author,
.post_item_single .post_content .post_meta,
.author_bio .author_link,
.comments_list_wrap .comment_posted,
.comments_list_wrap .comment_reply {
	{$fonts['info_font-family']}
}
.mejs-container *,
.search_wrap .search_results .post_meta_item {
	{$fonts['p_font-family']}
}

.logo_text {
	{$fonts['logo_font-family']}
	{$fonts['logo_font-size']}
	{$fonts['logo_font-weight']}
	{$fonts['logo_font-style']}
	{$fonts['logo_line-height']}
	{$fonts['logo_text-decoration']}
	{$fonts['logo_text-transform']}
	{$fonts['logo_letter-spacing']}
}
.logo_footer_text {
	{$fonts['logo_font-family']}
}

.menu_main_nav_area > ul,
.menu_mobile_nav_area > ul,
.sc_layouts_menu_nav,
.sc_layouts_menu_dir_vertical .sc_layouts_menu_nav {
	{$fonts['menu_font-family']}
	{$fonts['menu_font-size']}
	{$fonts['menu_line-height']}
}
.menu_main_nav > li > a,
.menu_mobile_nav > li > a,
.sc_layouts_menu_nav > li > a {
	{$fonts['menu_font-weight']}
	{$fonts['menu_font-style']}
	{$fonts['menu_text-decoration']}
	{$fonts['menu_text-transform']}
	{$fonts['menu_letter-spacing']}
}
.menu_main_nav > li[class*="current-menu-"] > a .sc_layouts_menu_item_description,
.menu_mobile_nav > li[class*="current-menu-"] > a .sc_layouts_menu_item_description,
.sc_layouts_menu_nav > li[class*="current-menu-"] > a .sc_layouts_menu_item_description {
	{$fonts['menu_font-weight']}
}
.menu_main_nav > li > ul,
.menu_mobile_nav > li > ul,
.sc_layouts_menu_nav > li > ul,
.sc_layouts_menu_popup .sc_layouts_menu_nav {
	{$fonts['submenu_font-family']}
	{$fonts['submenu_font-size']}
	{$fonts['submenu_line-height']}
}
.menu_main_nav > li ul > li > a,
.menu_mobile_nav > li ul > li > a,
.sc_layouts_menu_nav > li ul > li > a,
.sc_layouts_menu_popup .sc_layouts_menu_nav > li > a {
	{$fonts['submenu_font-weight']}
	{$fonts['submenu_font-style']}
	{$fonts['submenu_text-decoration']}
	{$fonts['submenu_text-transform']}
	{$fonts['submenu_letter-spacing']}
}


CSS;
			$rez          = apply_filters( 'anesta_filter_get_css', $rez, array( 'fonts' => $fonts ) );
			$css['fonts'] = $rez['fonts'];
		}

		// Theme vars
		//---------------------------------------------
		if ( null === $vars ) {
			$vars = anesta_get_theme_vars();
		}

		if ( $vars ) {

			// Make theme-specific vars
			$vars = anesta_customizer_add_theme_vars( $vars );

			$rez         = array();
			$rez['vars'] = '';
			$rez         = apply_filters( 'anesta_filter_get_css', $rez, array( 'vars' => $vars ) );
			$css['vars'] = $rez['vars'];
		}

		// Theme colors
		//--------------------------------------
		if ( false !== $colors ) {
			$schemes = empty( $scheme ) ? array_keys( anesta_get_sorted_schemes() ) : array( $scheme );
			if ( count( $schemes ) > 0 ) {
				$rez = array();
				foreach ( $schemes as $s ) {
					// Prepare colors
					if ( empty( $scheme ) ) {
						$colors = anesta_get_scheme_colors( $s );
					}

					// Make theme-specific colors and tints
					$colors         = anesta_customizer_add_theme_colors( $colors );
					$rez['colors']  = '';
					$rez            = apply_filters( 'anesta_filter_get_css', $rez, array( 'colors' => $colors, 'scheme' => $s ) );
					$css['colors']  = $css['colors'] . $rez['colors'];
				}
			}
		}

		$css_str = ( ! empty( $css['vars'] ) ? $css['vars'] : '' )
				. ( ! empty( $css['fonts'] ) ? $css['fonts'] : '' )
				. ( ! empty( $css['colors'] ) ? $css['colors'] : '' );

		return apply_filters( 'anesta_filter_prepare_css', $css_str, $remove_spaces );
	}
}
