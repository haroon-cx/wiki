<?php
/**
 * Plugin support: Event Tickets
 *
 * @package ThemeREX Addons
 * @since v1.5
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	exit;
}


if ( ! function_exists( 'trx_addons_exists_event_tickets' ) ) {
	/**
	 * Check if plugin 'Event Tickets' is installed and activated
	 * 
	 * @return bool  True if plugin is installed and activated
	 */
	function trx_addons_exists_event_tickets() {
		return class_exists( 'Tribe__Tickets__Main' );
	}
}

if ( ! function_exists( 'trx_addons_is_event_tickets_page' ) ) {
	/**
	 * Check if current page is any Event Tickets page
	 * 
	 * @param bool $check_tribe_events  Check if current page is Tribe Events page
	 * 
	 * @return boolean  	  True if page is Event Tickets page
	 */
	function trx_addons_is_event_tickets_page( $check_tribe_events = false ) {
		$rez = false;
		if ( trx_addons_exists_event_tickets() ) {
			$current_page  = get_queried_object_id();
			$checkout_page = 0;
			$success_page  = 0;
			if ( function_exists( 'tribe_get_option' ) ) {
				$checkout_page = (int) tribe_get_option( 'tickets-commerce-checkout-page' );
				if ( ! empty( $checkout_page ) ) {
					$checkout_page = apply_filters( 'tec_tickets_commerce_checkout_page_id', $checkout_page );
				}
				$success_page  = (int) tribe_get_option( 'tickets-commerce-success-page' );
				if ( ! empty( $success_page ) ) {
					$success_page = apply_filters( 'tec_tickets_commerce_success_page_id', $success_page );
				}
			}
			$rez = ( $check_tribe_events && function_exists( 'trx_addons_is_tribe_events_page' ) && trx_addons_is_tribe_events_page() )
					|| ( ! empty( $current_page) && ( $current_page == $checkout_page || $current_page == $success_page ) );
		}
		return $rez;
	}
}

if ( ! function_exists( 'trx_addons_event_tickets_load_scripts_front' ) ) {
	add_action( 'wp_enqueue_scripts', 'trx_addons_event_tickets_load_scripts_front', TRX_ADDONS_ENQUEUE_SCRIPTS_PRIORITY );
	add_action( 'trx_addons_action_pagebuilder_preview_scripts', 'trx_addons_event_tickets_load_scripts_front', 10, 1 );
	/**
	 * Enqueue custom styles and scripts for the frontend
	 * 
	 * @hooked wp_enqueue_scripts
	 * @hooked trx_addons_action_pagebuilder_preview_scripts
	 * 
	 * @param bool $force  Load scripts forcibly
	 */
	function trx_addons_event_tickets_load_scripts_front( $force = false ) {
		if ( ! trx_addons_exists_event_tickets() ) {
			return;
		}
		trx_addons_enqueue_optimized( 'event_tickets', $force, array(
			'need' => trx_addons_is_event_tickets_page( true ),
		) );
	}
}

if ( ! function_exists( 'trx_addons_event_tickets_check_in_html_output' ) ) {
//	add_filter( 'trx_addons_filter_get_menu_cache_html', 'trx_addons_event_tickets_check_in_html_output', 10, 1 );
//	add_action( 'trx_addons_action_show_layout_from_cache', 'trx_addons_event_tickets_check_in_html_output', 10, 1 );
	add_action( 'trx_addons_action_check_page_content', 'trx_addons_event_tickets_check_in_html_output', 10, 1 );
	/**
	 * Check if Event Tickets classes is present in the current page content and force loading scripts and styles
	 * 
	 * @hooked trx_addons_action_check_page_content
	 *
	 * @param string $content  The text to check
	 * 
	 * @return string          Checked text
	 */
	function trx_addons_event_tickets_check_in_html_output( $content = '' ) {
		if ( ! trx_addons_exists_event_tickets() ) {
			return $content;
		}
		$args = array(
			'check' => array(
				'class=[\'"][^\'"]*tribe\\-tickets__',
			)
		);
		if ( trx_addons_check_in_html_output( 'event_tickets', $content, $args ) ) {
			trx_addons_event_tickets_load_scripts_front( true );
		}
		return $content;
	}
}

if ( ! function_exists( 'trx_addons_event_tickets_filter_head_output' ) ) {
	add_filter( 'trx_addons_filter_page_head', 'trx_addons_event_tickets_filter_head_output', 10, 1 );
	/**
	 * Remove plugin-specific styles and scripts from the page head on the frontend if it is not used on the current page
	 * and optimize CSS and JS loading is 'full' in the ThemeREX Addons Options
	 * 
	 * @hooked trx_addons_filter_page_head
	 *
	 * @param string $content  The text to filter
	 * 
	 * @return string          Filtered text
	 */
	function trx_addons_event_tickets_filter_head_output( $content = '' ) {
		if ( ! trx_addons_exists_event_tickets() ) {
			return $content;
		}
		return trx_addons_filter_head_output( 'event_tickets', $content, array(
			'check' => array(
				'#<link[^>]*href=[\'"][^\'"]*/event-tickets/[^>]*>#'
			)
		) );
	}
}

if ( ! function_exists( 'trx_addons_event_tickets_filter_body_output' ) ) {
	add_filter( 'trx_addons_filter_page_content', 'trx_addons_event_tickets_filter_body_output', 10, 1 );
	/**
	 * Remove plugin-specific styles and scripts from the page body on the frontend if it is not used on the current page
	 * and optimize CSS and JS loading is 'full' in the ThemeREX Addons Options
	 * 
	 * @hooked trx_addons_filter_page_content
	 *
	 * @param string $content  The text to filter
	 * 
	 * @return string          Filtered text
	 */
	function trx_addons_event_tickets_filter_body_output( $content = '' ) {
		if ( ! trx_addons_exists_event_tickets() ) {
			return $content;
		}
		return trx_addons_filter_body_output( 'event_tickets', $content, array(
			'check' => array(
				'#<link[^>]*href=[\'"][^\'"]*/event-tickets/[^>]*>#',
				'#<script[^>]*src=[\'"][^\'"]*/event-tickets/[^>]*>[\\s\\S]*</script>#U',
				'#<script[^>]*id=[\'"]event-tickets-[^>]*>[\\s\\S]*</script>#U'
			)
		) );
	}
}


// Demo data install
//----------------------------------------------------------------------------

// One-click import support
if ( is_admin() ) {
	require_once TRX_ADDONS_PLUGIN_DIR . TRX_ADDONS_PLUGIN_API . 'event-tickets/event-tickets-demo-importer.php';
}
