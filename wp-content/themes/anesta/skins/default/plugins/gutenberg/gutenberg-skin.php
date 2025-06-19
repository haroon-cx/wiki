<?php
/* Gutenberg skin-specific functions
------------------------------------------------------------------------------- */

// Theme init priorities:
//10 - standard Theme init procedures (not ordered)
if ( ! function_exists( 'anesta_gutenberg_skin_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'anesta_gutenberg_skin_theme_setup9', 9 );
	function anesta_gutenberg_skin_theme_setup9() {
		remove_action( 'anesta_action_skin_switched', 'anesta_gutenberg_fse_update_theme_json' );
		remove_action( 'anesta_action_save_options', 'anesta_gutenberg_fse_update_theme_json' );
		remove_action( 'trx_addons_action_save_options', 'anesta_gutenberg_fse_update_theme_json' );
		remove_action( 'anesta_filter_list_footer_styles', 'anesta_gutenberg_fse_list_footer_styles');
		remove_action( 'anesta_filter_list_header_styles', 'anesta_gutenberg_fse_list_header_styles');

		add_filter( 'anesta_filter_localize_script_admin',	'anesta_gutenberg_skin_localize_script');
	}
}

// Add plugin's specific variables to the scripts
if ( ! function_exists( 'anesta_gutenberg_skin_localize_script' ) ) {
	//Handler of the add_filter( 'anesta_filter_localize_script_admin',	'anesta_gutenberg_skin_localize_script');
	function anesta_gutenberg_skin_localize_script( $arr ) {
		// Color scheme
		$arr['page_content'] = anesta_skin_page_content_type();
		return $arr;
	}
}