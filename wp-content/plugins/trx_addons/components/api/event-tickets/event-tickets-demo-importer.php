<?php
/**
 * Plugin support: Event Tickets (Importer support)
 *
 * @package ThemeREX Addons
 * @since v1.2
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	exit;
}


if ( ! function_exists( 'trx_addons_event_tickets_importer_required_plugins' ) ) {
	add_filter( 'trx_addons_filter_importer_required_plugins', 'trx_addons_event_tickets_importer_required_plugins', 10, 2 );
	/**
	 * Check if the plugin in the required plugins and its is installed and activated
	 * 
	 * @hooked trx_addons_filter_importer_required_plugins
	 * 
	 * @param string $not_installed  HTML list with plugins names
	 * @param string $list           Required plugins slugs
	 * 
	 * @return string                HTML list with plugins names
	 */
	function trx_addons_event_tickets_importer_required_plugins( $not_installed = '', $list = '' ) {
		if ( strpos( $list, 'event-tickets' ) !== false && ! trx_addons_exists_event_tickets() ) {
			$not_installed .= '<br>' . esc_html__( 'Event Tickets', 'trx_addons' );
		}
		return $not_installed;
	}
}

if ( ! function_exists( 'trx_addons_event_tickets_importer_check_row' ) ) {
	add_filter( 'trx_addons_filter_importer_import_row', 'trx_addons_event_tickets_importer_check_row', 9, 4 );
	/**
	 * Check if the row will be imported to the table
	 * 
	 * @hooked trx_addons_filter_importer_import_row
	 * 
	 * @param boolean $flag   Allow import or not
	 * @param string  $table  Table name
	 * @param array   $row    Row data
	 * @param array   $list   List of the required plugins
	 * 
	 * @return boolean        Allow import or not
	 */
	function trx_addons_event_tickets_importer_check_row( $flag, $table, $row, $list ) {
		if ( $flag || strpos( $list, 'event-tickets' ) === false ) {
			return $flag;
		}
		if ( trx_addons_exists_event_tickets() ) {
			if ( $table == 'posts' ) {
				$flag = in_array( $row['post_type'], array( 'tec_tc_ticket' ) );
			}
		}
		return $flag;
	}
}
