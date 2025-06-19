<?php
/**
 * Plugin support: MetForm (Importer support)
 *
 * @package ThemeREX Addons
 * @since v2.34.4
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	exit;
}


if ( ! function_exists( 'trx_addons_metform_importer_required_plugins' ) ) {
	add_filter( 'trx_addons_filter_importer_required_plugins',	'trx_addons_metform_importer_required_plugins', 10, 2 );
	/**
	 * Check if this plugin is required and installed
	 * 
	 * @hooked trx_addons_filter_importer_required_plugins
	 *
	 * @param string $not_installed  Not installed plugins list
	 * @param string $list           List of required plugins
	 * 
	 * @return string                Not installed plugins list
	 */
	function trx_addons_metform_importer_required_plugins( $not_installed = '', $list = '' ) {
		if ( strpos( $list, 'metform' ) !== false && ! trx_addons_exists_metform() ) {
			$not_installed .= '<br>' . esc_html__( 'MetForm', 'trx_addons' );
		}
		return $not_installed;
	}
}

if ( ! function_exists( 'trx_addons_metform_importer_set_options' ) ) {
	add_filter( 'trx_addons_filter_importer_options', 'trx_addons_metform_importer_set_options' );
	/**
	 * Add plugin's specific options to the export options list
	 * 
	 * @hooked trx_addons_filter_importer_options
	 *
	 * @param array $options		Options to export
	 * 
	 * @return array				Modified options
	 */
	function trx_addons_metform_importer_set_options( $options = array() ) {
		if ( trx_addons_exists_metform() && in_array( 'metform', $options['required_plugins'] ) ) {
			$options['additional_options'][] = 'metform_onboard_options';
			// Don't export MetForm settings because they are contain a secret keys and other private data
			// $options['additional_options'][] = 'metform_option__settings';
		}
		return $options;
	}
}

if ( ! function_exists( 'trx_addons_metform_importer_check_options' ) ) {
	add_filter( 'trx_addons_filter_import_theme_options', 'trx_addons_metform_importer_check_options', 10, 4 );
	/**
	 * Prevent to import plugin's specific options if plugin is not installed
	 * 
	 * @hooked trx_addons_filter_import_theme_options
	 *
	 * @param boolean $allow		Allow import or not
	 * @param string $k				Option name
	 * @param mixed $v				Option value. Not used in this hook
	 * @param array $options		Options of the current import
	 * 
	 * @return boolean				Allow import or not
	 */
	function trx_addons_metform_importer_check_options( $allow, $k, $v, $options ) {
		if ( $allow && strpos( $k, 'metform_' ) === 0 ) {
			$allow = trx_addons_exists_metform() && in_array( 'metform', $options['required_plugins'] );
		}
		return $allow;
	}
}

if ( ! function_exists( 'trx_addons_metform_importer_show_params' ) ) {
	add_action( 'trx_addons_action_importer_params', 'trx_addons_metform_importer_show_params', 10, 1 );
	/**
	 * Add plugin to the list with plugins for the importer
	 * 
	 * @hooked trx_addons_action_importer_params
	 *
	 * @param object $importer		Importer object
	 */
	function trx_addons_metform_importer_show_params( $importer ) {
		if ( trx_addons_exists_metform() && in_array( 'metform', $importer->options['required_plugins'] ) ) {
			$importer->show_importer_params( array(
				'slug' => 'metform',
				'title' => esc_html__('Import MetForm', 'trx_addons'),
				'part' => 1,
				'need_posts' => 1
			) );
		}
	}
}

if ( ! function_exists( 'trx_addons_metform_importer_check_row' ) ) {
	add_filter( 'trx_addons_filter_importer_import_row', 'trx_addons_metform_importer_check_row', 9, 4 );
	/**
	 * Check if the row will be imported
	 * 
	 * @hooked trx_addons_filter_importer_import_row
	 *
	 * @param boolean $flag		Allow import or not
	 * @param string $table		Table name
	 * @param array $row		Row data
	 * @param array $list		List of the required plugins
	 * 
	 * @return boolean			Allow import or not
	 */
	function trx_addons_metform_importer_check_row( $flag, $table, $row, $list ) {
		if ( $flag || strpos( $list, 'metform' ) === false ) {
			return $flag;
		}
		if ( trx_addons_exists_metform() ) {
			if ( $table == 'posts' ) {
				$flag = $row['post_type'] == 'metform-form';
			}
		}
		return $flag;
	}
}
