<?php
/**
 * Skin Setup
 *
 * @package ANESTA
 * @since ANESTA 1.76.0
 */


//--------------------------------------------
// SKIN DEFAULTS
//--------------------------------------------

// Return theme's (skin's) default value for the specified parameter
if ( ! function_exists( 'anesta_theme_defaults' ) ) {
	function anesta_theme_defaults( $name='', $value='' ) {
		$defaults = array(
			'page_width'          => 1470,
			'page_boxed_extra'    => 60,
			'page_fullwide_max'   => 1920,
			'page_fullwide_extra' => 130,
			'sidebar_width'       => 345,
			'sidebar_gap'         => 30,
			'grid_gap'            => 30,
			'rad'                 => 26,
		);
		if ( empty( $name ) ) {
			return $defaults;
		} else {
			if ( empty( $value ) && isset( $defaults[ $name ] ) ) {
				$value = $defaults[ $name ];
			}
			return $value;
		}
	}
}


// Theme init priorities:
// Action 'after_setup_theme'
// 1 - register filters to add/remove lists items in the Theme Options
// 2 - create Theme Options
// 3 - add/remove Theme Options elements
// 5 - load Theme Options. Attention! After this step you can use only basic options (not overriden)
// 9 - register other filters (for installer, etc.)
//10 - standard Theme init procedures (not ordered)
// Action 'wp_loaded'
// 1 - detect override mode. Attention! Only after this step you can use overriden options (separate values for the shop, courses, etc.)


//--------------------------------------------
// SKIN SETTINGS
//--------------------------------------------
if ( ! function_exists( 'anesta_skin_setup' ) ) {
	add_action( 'after_setup_theme', 'anesta_skin_setup', 1 );
	function anesta_skin_setup() {

		$GLOBALS['ANESTA_STORAGE'] = array_merge( $GLOBALS['ANESTA_STORAGE'], array(

			// Key validator: market[env|loc]-vendor[axiom|ancora|themerex]
			'theme_pro_key'       => 'env-ancora',

			'theme_doc_url'       => '//anesta.ancorathemes.com/doc',

			'theme_demofiles_url' => '//demofiles.ancorathemes.com/anesta/',
			
			'theme_rate_url'      => '//themeforest.net/download',

			'theme_custom_url'    => '//themerex.net/offers/?utm_source=offers&utm_medium=click&utm_campaign=themeinstall',

			'theme_support_url'   => '//themerex.net/support/',

			'theme_download_url'  => '//themeforest.net/user/ancorathemes/portfolio',        // Ancora

			'theme_video_url'     => '//www.youtube.com/channel/UCdIjRh7-lPVHqTTKpaf8PLA',   // Ancora

			'theme_privacy_url'   => '//ancorathemes.com/privacy-policy/',                   // Ancora

			'portfolio_url'       => '//themeforest.net/user/ancorathemes/portfolio',        // Ancora

			// Comma separated slugs of theme-specific categories (for get relevant news in the dashboard widget)
			// (i.e. 'children,kindergarten')
			'theme_categories'    => '',
		) );
	}
}


// Add/remove/change Theme Settings
if ( ! function_exists( 'anesta_skin_setup_settings' ) ) {
	add_action( 'after_setup_theme', 'anesta_skin_setup_settings', 1 );
	function anesta_skin_setup_settings() {
		// Example: enable (true) / disable (false) thumbs in the prev/next navigation
		anesta_storage_set_array( 'settings', 'thumbs_in_navigation', true );
	}
}



//--------------------------------------------
// SKIN FONTS
//--------------------------------------------
if ( ! function_exists( 'anesta_skin_setup_fonts' ) ) {
	add_action( 'after_setup_theme', 'anesta_skin_setup_fonts', 1 );
	function anesta_skin_setup_fonts() {
		// Fonts to load when theme start
		// It can be:
		// - Google fonts (specify name, family and styles)
		// - Adobe fonts (specify name, family and link URL)
		// - uploaded fonts (specify name, family), placed in the folder css/font-face/font-name inside the skin folder
		// Attention! Font's folder must have name equal to the font's name, with spaces replaced on the dash '-'
		// example: font name 'TeX Gyre Termes', folder 'TeX-Gyre-Termes'
		$load_fonts = array(
			// Google font
			array(
				'name'   => 'Inter',
				'family' => 'sans-serif',
				'link'   => '',                                                 // Parameter 'link' used only for the Adobe fonts
				'styles' => 'wght@400;500;600;700',    // Parameter 'styles' used only for the Google fonts
																				// Google Fonts CSS API v.1: 300,400,700,300italic,400italic,700italic
																				// Google Fonts CSS API v.2: ital,wght@0,300;0,400;0,700;1,300;1,400;1,700
																				//                           Attention! For v.2 fonts list must be sorted by tuples (first digit in each pair)
			),
		);
		anesta_storage_set( 'load_fonts', $load_fonts );

		// Characters subset for the Google fonts. Available values are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese
		anesta_storage_set( 'load_fonts_subset', 'latin,latin-ext' );

		// Settings of the main tags.
		// Default value of 'font-family' may be specified as reference to the array $load_fonts (see above)
		// or as comma-separated string.
		// In the second case (if 'font-family' is specified manually as comma-separated string):
		//    1) Font name with spaces in the parameter 'font-family' will be enclosed in quotes and no spaces after comma!
		//    2) If font-family inherit a value from the 'Main text' - specify 'inherit' as a value
		// example:
		// Correct:   'font-family' => anesta_get_load_fonts_family_string( $load_fonts[0] )
		// Correct:   'font-family' => 'inherit'
		// Correct:   'font-family' => 'Roboto,sans-serif'
		// Correct:   'font-family' => '"PT Serif",sans-serif'
		// Incorrect: 'font-family' => 'Roboto, sans-serif'      // A space after a comma is prohibited
		// Incorrect: 'font-family' => 'PT Serif,sans-serif'     // A font family with spaces must be enclosed with quotes

		$font_description = esc_html__( 'Press "Reload preview area" button at the top of this panel after the all font parameters are changed. Please use only the following units: "rem" or "em"', 'anesta' );

		anesta_storage_set(
			'theme_fonts', array(
				'p'       => array(
					'title'           => esc_html__( 'Main text', 'anesta' ),
					'description'     => sprintf( $font_description, esc_html__( 'main text', 'anesta' ) ),
					'font-family'     => anesta_get_load_fonts_family_string( $load_fonts[0] ),
					'font-size'       => '15px',
					'font-weight'     => '400',
					'font-style'      => 'normal',
					'line-height'     => '1.6em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '',
					'margin-top'      => '0em',
					'margin-bottom'   => '24px',
				),
				'post'    => array(
					'title'           => esc_html__( 'Article text', 'anesta' ),
					'description'     => sprintf( $font_description, esc_html__( 'article text', 'anesta' ) ),
					'font-family'     => '',			// Example: anesta_get_load_fonts_family_string( $load_fonts[0] ),
					'font-size'       => '',			// Example: '1.286rem',
					'font-weight'     => '',			// Example: '400',
					'font-style'      => '',			// Example: 'normal',
					'line-height'     => '',			// Example: '1.75em',
					'text-decoration' => '',			// Example: 'none',
					'text-transform'  => '',			// Example: 'none',
					'letter-spacing'  => '',			// Example: '',
					'margin-top'      => '',			// Example: '0em',
					'margin-bottom'   => '',			// Example: '1.4em',
				),
				'h1'      => array(
					'title'           => esc_html__( 'Heading 1', 'anesta' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H1', 'anesta' ) ),
					'font-family'     => anesta_get_load_fonts_family_string( $load_fonts[0] ),
					'font-size'       => '45px',
					'font-weight'     => '600',
					'font-style'      => 'normal',
					'line-height'     => '1.111em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '-0.02em',
					'margin-top'      => '1.444em',
					'margin-bottom'   => '0.555em',
				),
				'h2'      => array(
					'title'           => esc_html__( 'Heading 2', 'anesta' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H2', 'anesta' ) ),
					'font-family'     => anesta_get_load_fonts_family_string( $load_fonts[0] ),
					'font-size'       => '28px',
					'font-weight'     => '600',
					'font-style'      => 'normal',
					'line-height'     => '1.142em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '1.3214em',
					'margin-bottom'   => '0.7em',
				),
				'h3'      => array(
					'title'           => esc_html__( 'Heading 3', 'anesta' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H3', 'anesta' ) ),
					'font-family'     => anesta_get_load_fonts_family_string( $load_fonts[0] ),
					'font-size'       => '23px',
					'font-weight'     => '600',
					'font-style'      => 'normal',
					'line-height'     => '1.217em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '1.7em',
					'margin-bottom'   => '0.739em',
				),
				'h4'      => array(
					'title'           => esc_html__( 'Heading 4', 'anesta' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H4', 'anesta' ) ),
					'font-family'     => anesta_get_load_fonts_family_string( $load_fonts[0] ),
					'font-size'       => '20px',
					'font-weight'     => '600',
					'font-style'      => 'normal',
					'line-height'     => '1.3em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '1.9em',
					'margin-bottom'   => '0.85em',
				),
				'h5'      => array(
					'title'           => esc_html__( 'Heading 5', 'anesta' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H5', 'anesta' ) ),
					'font-family'     => anesta_get_load_fonts_family_string( $load_fonts[0] ),
					'font-size'       => '18px',
					'font-weight'     => '600',
					'font-style'      => 'normal',
					'line-height'     => '1.335em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '2.166em',
					'margin-bottom'   => '0.7em',
				),
				'h6'      => array(
					'title'           => esc_html__( 'Heading 6', 'anesta' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H6', 'anesta' ) ),
					'font-family'     => anesta_get_load_fonts_family_string( $load_fonts[0] ),
					'font-size'       => '15px',
					'font-weight'     => '600',
					'font-style'      => 'normal',
					'line-height'     => '1.333em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '2.7em',
					'margin-bottom'   => '1em',
				),
				'logo'    => array(
					'title'           => esc_html__( 'Logo text', 'anesta' ),
					'description'     => sprintf( $font_description, esc_html__( 'text of the logo', 'anesta' ) ),
					'font-family'     => anesta_get_load_fonts_family_string( $load_fonts[0] ),
					'font-size'       => '32px',
					'font-weight'     => '700',
					'font-style'      => 'normal',
					'line-height'     => '1.25em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
				),
				'button'  => array(
					'title'           => esc_html__( 'Buttons', 'anesta' ),
					'description'     => sprintf( $font_description, esc_html__( 'buttons', 'anesta' ) ),
					'font-family'     => anesta_get_load_fonts_family_string( $load_fonts[0] ),
					'font-size'       => '12px',
					'font-weight'     => '600',
					'font-style'      => 'normal',
					'line-height'     => '21px',
					'text-decoration' => 'none',
					'text-transform'  => 'uppercase',
					'letter-spacing'  => '0.06em',
				),
				'input'   => array(
					'title'           => esc_html__( 'Input fields', 'anesta' ),
					'description'     => sprintf( $font_description, esc_html__( 'input fields, dropdowns and textareas', 'anesta' ) ),
					'font-family'     => anesta_get_load_fonts_family_string( $load_fonts[0] ),
					'font-size'       => '13px',
					'font-weight'     => '400',
					'font-style'      => 'normal',
					'line-height'     => '19px',     // Attention! Firefox don't allow line-height less then 1.5em in the select
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
				),
				'info'    => array(
					'title'           => esc_html__( 'Post meta', 'anesta' ),
					'description'     => sprintf( $font_description, esc_html__( 'post meta (author, categories, publish date, counters, share, etc.)', 'anesta' ) ),
					'font-family'     => anesta_get_load_fonts_family_string( $load_fonts[0] ),
					'font-size'       => '12px',  // Old value '13px' don't allow using 'font zoom' in the custom blog items
					'font-weight'     => '400',
					'font-style'      => 'normal',
					'line-height'     => '18px',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
				),
				'menu'    => array(
					'title'           => esc_html__( 'Main menu', 'anesta' ),
					'description'     => sprintf( $font_description, esc_html__( 'main menu items', 'anesta' ) ),
					'font-family'     => anesta_get_load_fonts_family_string( $load_fonts[0] ),
					'font-size'       => '14px',
					'font-weight'     => '500',
					'font-style'      => 'normal',
					'line-height'     => '1.5em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
				),
				'submenu' => array(
					'title'           => esc_html__( 'Dropdown menu', 'anesta' ),
					'description'     => sprintf( $font_description, esc_html__( 'dropdown menu items', 'anesta' ) ),
					'font-family'     => anesta_get_load_fonts_family_string( $load_fonts[0] ),
					'font-size'       => '14px',
					'font-weight'     => '500',
					'font-style'      => 'normal',
					'line-height'     => '1.5em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
				),
			)
		);

		// Font presets
		anesta_storage_set(
			'font_presets', array(
				'default' => array(
								'title'  => esc_html__( 'Default', 'anesta' ),
								'load_fonts' => $load_fonts,
								'theme_fonts' => anesta_storage_get('theme_fonts'),
							),
				'karla' => array(
								'title'  => esc_html__( 'Karla', 'anesta' ),
								'load_fonts' => array(
													// Google font
													array(
														'name'   => 'Dancing Script',
														'family' => 'fantasy',
														'link'   => '',
														'styles' => 'wght@300;400;700',	// 300,400,700
													),
													// Google font
													array(
														'name'   => 'Sansita Swashed',
														'family' => 'fantasy',
														'link'   => '',
														'styles' => 'wght@300;400;700',	// 300,400,700
													),
												),
								'theme_fonts' => array(
													'p'       => array(
														'font-family'     => '"Dancing Script",fantasy',
														'font-size'       => '1.25rem',
													),
													'post'    => array(
														'font-family'     => '',
													),
													'h1'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
														'font-size'       => '4em',
													),
													'h2'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'h3'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'h4'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'h5'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'h6'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'logo'    => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'button'  => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'input'   => array(
														'font-family'     => 'inherit',
													),
													'info'    => array(
														'font-family'     => 'inherit',
													),
													'menu'    => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'submenu' => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
												),
							),
				'roboto' => array(
								'title'  => esc_html__( 'Roboto', 'anesta' ),
								'load_fonts' => array(
													// Google font
													array(
														'name'   => 'Noto Sans JP',
														'family' => 'serif',
														'link'   => '',
														'styles' => 'ital,wght@0,300;0,400;0,700;1,300;1,400;1,700',   // 300,300italic,400,400italic,700,700italic
													),
													// Google font
													array(
														'name'   => 'Merriweather',
														'family' => 'sans-serif',
														'link'   => '',
														'styles' => 'ital,wght@0,300;0,400;0,700;1,300;1,400;1,700',   // 300,300italic,400,400italic,700,700italic
													),
												),
								'theme_fonts' => array(
													'p'       => array(
														'font-family'     => '"Noto Sans JP",serif',
													),
													'post'    => array(
														'font-family'     => '',
													),
													'h1'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'h2'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'h3'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'h4'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'h5'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'h6'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'logo'    => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'button'  => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'input'   => array(
														'font-family'     => 'inherit',
													),
													'info'    => array(
														'font-family'     => 'inherit',
													),
													'menu'    => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'submenu' => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
												),
							),
				'garamond' => array(
								'title'  => esc_html__( 'Garamond', 'anesta' ),
								'load_fonts' => array(
													// Adobe font
													array(
														'name'   => 'Europe',
														'family' => 'sans-serif',
														'link'   => 'https://use.typekit.net/qmj1tmx.css',
														'styles' => '',
													),
													// Adobe font
													array(
														'name'   => 'Sofia Pro',
														'family' => 'sans-serif',
														'link'   => 'https://use.typekit.net/qmj1tmx.css',
														'styles' => '',
													),
												),
								'theme_fonts' => array(
													'p'       => array(
														'font-family'     => '"Sofia Pro",sans-serif',
													),
													'post'    => array(
														'font-family'     => '',
													),
													'h1'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'h2'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'h3'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'h4'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'h5'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'h6'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'logo'    => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'button'  => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'input'   => array(
														'font-family'     => 'inherit',
													),
													'info'    => array(
														'font-family'     => 'inherit',
													),
													'menu'    => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'submenu' => array(
														'font-family'     => 'Europe,sans-serif',
													),
												),
							),
			)
		);
	}
}


//--------------------------------------------
// COLOR SCHEMES
//--------------------------------------------
if ( ! function_exists( 'anesta_skin_setup_schemes' ) ) {
	add_action( 'after_setup_theme', 'anesta_skin_setup_schemes', 1 );
	function anesta_skin_setup_schemes() {

		// Theme colors for customizer
		// Attention! Inner scheme must be last in the array below
		anesta_storage_set(
			'scheme_color_groups', array(
				'main'    => array(
					'title'       => esc_html__( 'Main', 'anesta' ),
					'description' => esc_html__( 'General colors', 'anesta' ),
				),
				'extra'   => array(
					'title'       => esc_html__( 'Extra', 'anesta' ),
					'description' => esc_html__( 'Extra block colors (audio, video, image posts, etc.)', 'anesta' ),
				),
				'input'   => array(
					'title'       => esc_html__( 'Input', 'anesta' ),
					'description' => esc_html__( 'Form field colors (text field, textarea, select, etc.)', 'anesta' ),
				),
				'accent'   => array(
					'title'       => esc_html__( 'Accent', 'anesta' ),
					'description' => esc_html__( 'Accent block colors (blockquotes, countdown, alert box, etc.)', 'anesta' ),
				),
			)
		);

		anesta_storage_set(
			'scheme_color_names', array(
				'content_bg'    => array(
					'title'       => esc_html__( 'Content background', 'anesta' ),
					'description' => esc_html__( 'Background color of the layout panels for displaying content. Also used as a background color for posts content, sidebars, headers, etc.', 'anesta' ),
				),
				'navigate_bg'    => array(
					'title'       => esc_html__( 'Navigation background', 'anesta' ),
					'description' => esc_html__( 'Background color of navigation elements (e.g. pagination), table headers, accordion tabs, etc.', 'anesta' ),
				),
				'menu_bg'    => array(
					'title'       => esc_html__( 'Menu background', 'anesta' ),
					'description' => esc_html__( 'Background color of the menu panel.', 'anesta' ),
				),
				'bg_color'    => array(
					'title'       => esc_html__( 'Background color', 'anesta' ),
					'description' => esc_html__( 'Background color of the pages', 'anesta' ),
				),
				'bg_hover'    => array(
					'title'       => esc_html__( 'Background hover', 'anesta' ),
					'description' => esc_html__( 'Background hover color of the pages', 'anesta' ),
				),
				'bd_color'    => array(
					'title'       => esc_html__( 'Border color', 'anesta' ),
					'description' => esc_html__( 'The border color of the blocks', 'anesta' ),
				),
				'text'        => array(
					'title'       => esc_html__( 'Text', 'anesta' ),
					'description' => esc_html__( 'The color of the text', 'anesta' ),
				),
				'text_dark'   => array(
					'title'       => esc_html__( 'Text dark', 'anesta' ),
					'description' => esc_html__( 'The color of dark text (bold, header, menu etc.)', 'anesta' ),
				),
				'text_light'  => array(
					'title'       => esc_html__( 'Text light', 'anesta' ),
					'description' => esc_html__( 'The color of light text (post date, counters, categories, tags, submenu etc.)', 'anesta' ),
				),

				'text_link'   => array(
					'title'       => esc_html__( 'Accent', 'anesta' ),
					'description' => esc_html__( 'The color of the accented layouts elements', 'anesta' ),
				),
				'text_hover'   => array(
					'title'       => esc_html__( 'Accent hover', 'anesta' ),
					'description' => esc_html__( 'The hover of the accented layouts elements', 'anesta' ),
				),
				'text_link2'  => array(
					'title'       => esc_html__( 'Accent 2', 'anesta' ),
					'description' => esc_html__( 'The color of other accented layouts elements', 'anesta' ),
				),
				'text_hover2'   => array(
					'title'       => esc_html__( 'Accent 2 hover', 'anesta' ),
					'description' => esc_html__( 'The hover of the accented layouts elements', 'anesta' ),
				),
				'text_link3'  => array(
					'title'       => esc_html__( 'Accent 3', 'anesta' ),
					'description' => esc_html__( 'The color of other accented layouts elements', 'anesta' ),
				),
				'text_hover3'   => array(
					'title'       => esc_html__( 'Accent 3 hover', 'anesta' ),
					'description' => esc_html__( 'The hover of the accented layouts elements', 'anesta' ),
				),
				'text_link4'  => array(
					'title'       => esc_html__( 'Accent 4', 'anesta' ),
					'description' => esc_html__( 'The color of other accented layouts elements', 'anesta' ),
				),
				'text_hover4'   => array(
					'title'       => esc_html__( 'Accent 4 hover', 'anesta' ),
					'description' => esc_html__( 'The hover of the accented layouts elements', 'anesta' ),
				),
				'text_link5'  => array(
					'title'       => esc_html__( 'Accent 5', 'anesta' ),
					'description' => esc_html__( 'The color of other accented layouts elements', 'anesta' ),
				),
				'text_hover5'   => array(
					'title'       => esc_html__( 'Accent 5 hover', 'anesta' ),
					'description' => esc_html__( 'The hover of the accented layouts elements', 'anesta' ),
				),
			)
		);

		// Default values for each color scheme
		$schemes = array(

			// Color scheme: 'default'
			'default' => array(
				'title'    => esc_html__( 'Default', 'anesta' ),
				'internal' => true,
				'colors'   => array(

					// Whole block border and background
					'content_bg'   	   	=> '#ffffff',
					'navigate_bg'    	=> '#F9FAFC',
					'menu_bg'       	=> '#FCFCFC',

					'bg_color'         	=> '#f1f3f6',
					'bd_color'         	=> '#E4E4E4',

					// Text and links colors
					'text'             	=> '#7A7E83',
					'text_light'       	=> '#9C9DA1',
					'text_dark'        	=> '#071021',

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'   => '#071021',
					'extra_bd_color'   => '#2D3036',
					'extra_text'       => '#BFC2C9',
					'extra_light'      => '#96999F',
					'extra_dark'       => '#FCFCFC',

					// Input fields (form's fields and textarea)
					'input_bg_color'   => '#FFFFFF',
					'input_bg_hover'   => '#f9fafc',
					'input_bd_color'   => '#E4E4E4',
					'input_text'       => '#7A7E83',
					'input_light'      => '#9C9DA1',
					'input_dark'       => '#071021',

					// Accent blocks		
					'accent_text'       => '#FCFCFC',
					'accent_light'      => '#BFC2C9',

					'accent_link'       => '#0D4BC1',
					'accent_hover'      => '#083FA9',
					'accent_link2'      => '#FB582A',
					'accent_hover2'     => '#EA4B1E',
					'accent_link3'      => '#FFB039',
					'accent_hover3'     => '#DC9222',
					'accent_link4'      => '#C6A78E',
					'accent_hover4'     => '#AE8D73',
					'accent_link5'      => '#8CC80C',
					'accent_hover5'     => '#83BA0E',

					// Additional (skin-specific) colors.
					// Attention! Set of colors must be equal in all color schemes.
					//---> For example:
					//---> 'new_color1'         => '#rrggbb',
					//---> 'alter_new_color1'   => '#rrggbb',
					//---> 'inverse_new_color1' => '#rrggbb',
				),
			),

			// Color scheme: 'dark'
			'dark'    => array(
				'title'    => esc_html__( 'Dark', 'anesta' ),
				'internal' => true,
				'colors'   => array(

					// Whole block border and background
					'content_bg'   	   	=> '#1f2632',
					'navigate_bg'    	=> '#181f2d',
					'menu_bg'       	=> '#131c2c',

					'bg_color'         	=> '#071021',
					'bd_color'         	=> '#383c43',

					// Text and links colors
					'text'             	=> '#BFC2C9',
					'text_light'       	=> '#96999F',
					'text_dark'        	=> '#FCFCFC',

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'   => '#040B1A',
					'extra_bd_color'   => '#E4E4E4',
					'extra_text'       => '#7A7E83',
					'extra_light'      => '#9C9DA1',
					'extra_dark'       => '#071021',

					// Input fields (form's fields and textarea)
					'input_bg_color'   => '#071021',
					'input_bg_hover'   => '#131c2c',
					'input_bd_color'   => '#383c43',
					'input_text'       => '#FCFCFC',
					'input_light'      => '#96999F',
					'input_dark'       => '#FCFCFC',

					// Accent blocks
					'accent_text'       => '#ffffff',
					'accent_light'      => '#BFC2C9',

					'accent_link'       => '#2a8ffb',
					'accent_hover'      => '#1f7bdd',
					'accent_link2'      => '#FB582A',
					'accent_hover2'     => '#EA4B1E',
					'accent_link3'      => '#FFB039',
					'accent_hover3'     => '#DC9222',
					'accent_link4'      => '#C6A78E',
					'accent_hover4'     => '#AE8D73',
					'accent_link5'      => '#8CC80C',
					'accent_hover5'     => '#83BA0E',

					// Additional (skin-specific) colors.
					// Attention! Set of colors must be equal in all color schemes.
					//---> For example:
					//---> 'new_color1'         => '#rrggbb',
					//---> 'alter_new_color1'   => '#rrggbb',
					//---> 'inverse_new_color1' => '#rrggbb',
				),
			),
		);
		anesta_storage_set( 'schemes', $schemes );
		anesta_storage_set( 'schemes_original', $schemes );

		// Add names of additional colors
		//---> For example:
		//---> anesta_storage_set_array( 'scheme_color_names', 'new_color1', array(
		//---> 	'title'       => __( 'New color 1', 'anesta' ),
		//---> 	'description' => __( 'Description of the new color 1', 'anesta' ),
		//---> ) );


		// Additional colors for each scheme
		// Parameters:	'color' - name of the color from the scheme that should be used as source for the transformation
		//				'alpha' - to make color transparent (0.0 - 1.0)
		//				'hue', 'saturation', 'brightness' - inc/dec value for each color's component
		anesta_storage_set(
			'scheme_colors_add', array(
				'bg_color_0'        => array(
					'color' => 'bg_color',
					'alpha' => 0,
				),
				'bg_color_02'       => array(
					'color' => 'bg_color',
					'alpha' => 0.2,
				),
				'bg_color_07'       => array(
					'color' => 'bg_color',
					'alpha' => 0.7,
				),
				'bg_color_08'       => array(
					'color' => 'bg_color',
					'alpha' => 0.8,
				),
				'bg_color_09'       => array(
					'color' => 'bg_color',
					'alpha' => 0.9,
				),	
				'content_bg_03'       => array(
					'color' => 'content_bg',
					'alpha' => 0.3,
				),			
				'text_light_04'      => array(
					'color' => 'text_light',
					'alpha' => 0.4,
				),				
				'text_light_06'      => array(
					'color' => 'text_light',
					'alpha' => 0.6,
				),				
				'text_light_08'      => array(
					'color' => 'text_light',
					'alpha' => 0.8,
				),	
				'text_dark_005'      => array(
					'color' => 'text_dark',
					'alpha' => 0.05,
				),
				'text_dark_016'      => array(
					'color' => 'text_dark',
					'alpha' => 0.16,
				),
				'text_dark_02'      => array(
					'color' => 'text_dark',
					'alpha' => 0.2,
				),	
				'text_dark_025'      => array(
					'color' => 'text_dark',
					'alpha' => 0.25,
				),	
				'text_dark_03'      => array(
					'color' => 'text_dark',
					'alpha' => 0.3,
				),		
				'text_dark_07'      => array(
					'color' => 'text_dark',
					'alpha' => 0.7,
				),	
				'text_dark_08'      => array(
					'color' => 'text_dark',
					'alpha' => 0.8,
				),
				'extra_bg_color_08'      => array(
					'color' => 'extra_bg_color',
					'alpha' => 0.8,
				),
				'extra_dark_06'      => array(
					'color' => 'extra_dark',
					'alpha' => 0.6,
				),
				'accent_text_012'      => array(
					'color' => 'accent_text',
					'alpha' => 0.12,
				),
				'accent_text_03'      => array(
					'color' => 'accent_text',
					'alpha' => 0.3,
				),
				'accent_text_06'      => array(
					'color' => 'accent_text',
					'alpha' => 0.6,
				),
				'accent_text_07'      => array(
					'color' => 'accent_text',
					'alpha' => 0.7,
				),
				'accent_text_08'      => array(
					'color' => 'accent_text',
					'alpha' => 0.8,
				),
				'accent_link_005'      => array(
					'color' => 'accent_link',
					'alpha' => 0.05,
				),
				'accent_link_007'      => array(
					'color' => 'accent_link',
					'alpha' => 0.07,
				),
				'accent_link_01'      => array(
					'color' => 'accent_link',
					'alpha' => 0.1,
				),
				'accent_link_02'      => array(
					'color' => 'accent_link',
					'alpha' => 0.2,
				),
				'accent_link_05'      => array(
					'color' => 'accent_link',
					'alpha' => 0.5,
				),
				'accent_link_07'      => array(
					'color' => 'accent_link',
					'alpha' => 0.7,
				),
				'accent_hover_02'      => array(
					'color' => 'accent_hover',
					'alpha' => 0.2,
				),
				'accent_link2_005'      => array(
					'color' => 'accent_link2',
					'alpha' => 0.05,
				),
				'accent_link2_007'      => array(
					'color' => 'accent_link2',
					'alpha' => 0.07,
				),
				'accent_link2_01'      => array(
					'color' => 'accent_link2',
					'alpha' => 0.1,
				),
				'accent_link2_02'      => array(
					'color' => 'accent_link2',
					'alpha' => 0.2,
				),
				'accent_link2_05'      => array(
					'color' => 'accent_link2',
					'alpha' => 0.5,
				),
				'accent_hover2_02'      => array(
					'color' => 'accent_hover2',
					'alpha' => 0.2,
				),
				'accent_link3_005'      => array(
					'color' => 'accent_link3',
					'alpha' => 0.05,
				),
				'accent_link3_007'      => array(
					'color' => 'accent_link3',
					'alpha' => 0.07,
				),
				'accent_link3_01'      => array(
					'color' => 'accent_link3',
					'alpha' => 0.1,
				),
				'accent_link3_02'      => array(
					'color' => 'accent_link3',
					'alpha' => 0.2,
				),
				'accent_link3_05'      => array(
					'color' => 'accent_link3',
					'alpha' => 0.5,
				),
				'accent_hover3_02'      => array(
					'color' => 'accent_hover3',
					'alpha' => 0.2,
				),
				'accent_link4_01'      => array(
					'color' => 'accent_link4',
					'alpha' => 0.1,
				),
				'accent_link4_02'      => array(
					'color' => 'accent_link4',
					'alpha' => 0.2,
				),
				'accent_hover4_02'      => array(
					'color' => 'accent_hover4',
					'alpha' => 0.2,
				),
				'accent_link5_005'      => array(
					'color' => 'accent_link5',
					'alpha' => 0.05,
				),
				'accent_link5_007'      => array(
					'color' => 'accent_link5',
					'alpha' => 0.07,
				),
				'accent_link5_01'      => array(
					'color' => 'accent_link5',
					'alpha' => 0.1,
				),
				'accent_link5_02'      => array(
					'color' => 'accent_link5',
					'alpha' => 0.2,
				),
				'accent_link5_05'      => array(
					'color' => 'accent_link5',
					'alpha' => 0.5,
				),
				'accent_hover5_02'      => array(
					'color' => 'accent_hover5',
					'alpha' => 0.2,
				),
				'accent_link_blend'   => array(
					'color'      => 'accent_link',
					'hue'        => 2,
					'saturation' => -5,
					'brightness' => 5,
				),
			)
		);

		// Simple scheme editor: lists the colors to edit in the "Simple" mode.
		// For each color you can set the array of 'slave' colors and brightness factors that are used to generate new values,
		// when 'main' color is changed
		// Leave 'slave' arrays empty if your scheme does not have a color dependency
		anesta_storage_set(
			'schemes_simple', array(
				'accent_link'        => array(
				),
				'accent_link2'        => array(
				),
				'accent_link3'        => array(
				),
				'accent_link4'        => array(
				),
				'accent_link5'        => array(
				),
			)
		);

		// Parameters to set order of schemes in the css
		anesta_storage_set(
			'schemes_sorted', array(
				'color_scheme',
				'header_scheme',
				'menu_scheme',
				'sidebar_scheme',
				'footer_scheme',
			)
		);

		// Color presets
		anesta_storage_set(
			'color_presets', array(
				'default' => array(
								'title'  => esc_html__( 'Default', 'anesta' ),
								'colors' => array(
												'default' => $schemes['default']['colors'],
												'dark'    => $schemes['dark']['colors'],
												)
							),
				'autumn'  => array(
								'title'  => esc_html__( 'Autumn', 'anesta' ),
								'colors' => array(
												'default' => array(
																	'accent_link'  => '#d83938',
																	'accent_hover' => '#f2b232',
																	),
												'dark' => array(
																	'accent_link'  => '#d83938',
																	'accent_hover' => '#f2b232',
																	)
												)
							),
				'green'   => array(
								'title'  => esc_html__( 'Natural Green', 'anesta' ),
								'colors' => array(
												'default' => array(
																	'accent_link'  => '#75ac78',
																	'accent_hover' => '#378e6d',
																	),
												'dark' => array(
																	'accent_link'  => '#75ac78',
																	'accent_hover' => '#378e6d',
																	)
												)
							),
			)
		);
	}
}
