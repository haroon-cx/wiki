<?php
/* Elementor support functions
------------------------------------------------------------------------------- */


// Return true if Elementor exists and current mode is edit
if ( !function_exists( 'anesta_elm_skin_is_edit_mode' ) ) {
	function anesta_elm_skin_is_edit_mode() {
		static $is_edit_mode = -1;
		if ( $is_edit_mode === -1 ) {
			$is_edit_mode = anesta_exists_elementor()
								&& ( \Elementor\Plugin::instance()->editor->is_edit_mode()
									|| ( anesta_get_value_gp( 'post' ) > 0
										&& anesta_get_value_gp( 'action' ) == 'elementor'
										)
									|| ( is_admin()
										&& in_array( anesta_get_value_gp( 'action' ), array( 'elementor', 'elementor_ajax', 'wp_ajax_elementor_ajax' ) )
										)
									);
		}
		return $is_edit_mode;
	}
}

// Return list of the empty_space heights
if ( ! function_exists( 'anesta_trx_addons_get_list_sc_empty_space_heights' ) ) {
	add_filter( 'trx_addons_filter_get_list_sc_empty_space_heights', 'anesta_trx_addons_get_list_sc_empty_space_heights' );
	function anesta_trx_addons_get_list_sc_empty_space_heights( $array ) {
		anesta_array_insert_after( $array, 'huge', array( 'ginormous' => esc_html__( 'Ginormous', 'anesta' ) ) );
		return $array;
	}
}