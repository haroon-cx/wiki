<?php
/**
 * Required plugins
 *
 * @package ANESTA
 * @since ANESTA 1.76.0
 */

// THEME-SUPPORTED PLUGINS
// If plugin not need - remove its settings from next array
//----------------------------------------------------------
$anesta_theme_required_plugins_groups = array(
	'core'          => esc_html__( 'Core', 'anesta' ),
	'page_builders' => esc_html__( 'Page Builders', 'anesta' ),
	'ecommerce'     => esc_html__( 'E-Commerce & Donations', 'anesta' ),
	'socials'       => esc_html__( 'Socials and Communities', 'anesta' ),
	'events'        => esc_html__( 'Events and Appointments', 'anesta' ),
	'content'       => esc_html__( 'Content', 'anesta' ),
	'other'         => esc_html__( 'Other', 'anesta' ),
);
$anesta_theme_required_plugins        = array(
	'bbpress'                    => array(
		'title'       => esc_html__( 'bbPress and BuddyPress', 'anesta' ),
		'description' => '',
		'required'    => false,
		'logo'        => 'bbpress.png',
		'group'       => $anesta_theme_required_plugins_groups['socials'],
	),
	'bp-better-messages'      => array(
		'title'       => esc_html__( 'Better Messages', 'anesta' ),
		'description' => '',
		'required'    => false,
		'logo'        => anesta_get_file_url( 'plugins/bp-better-messages/bp-better-messages.png' ),
		'group'       => $anesta_theme_required_plugins_groups['socials'],
	),
	'bp-activity-shortcode'      => array(
		'title'       => esc_html__( 'BuddyPress Activity ShortCode', 'anesta' ),
		'description' => '',
		'required'    => false,
		'logo'        => anesta_get_file_url( 'plugins/bbpress/bbpress-sc.png' ),
		'group'       => $anesta_theme_required_plugins_groups['socials'],
	),
	'buddypress-docs'      => array(
		'title'       => esc_html__( 'BuddyPress Docs', 'anesta' ),
		'description' => '',
		'required'    => false,
		'logo'        => anesta_get_file_url( 'plugins/buddypress-docs/buddypress-docs.png' ),
		'group'       => $anesta_theme_required_plugins_groups['socials'],
	),
	'buddypress-learndash'      => array(
		'title'       => esc_html__( 'BuddyPress for LearnDash', 'anesta' ),
		'description' => '',
		'required'    => false,
		'logo'        => anesta_get_file_url( 'plugins/buddypress/buddypress-learndash.png' ),
		'group'       => $anesta_theme_required_plugins_groups['socials'],
	),
	'contact-form-7'             => array(
		'title'       => esc_html__( 'Contact Form 7', 'anesta' ),
		'description' => esc_html__( "CF7 allows you to create an unlimited number of contact forms", 'anesta' ),
		'required'    => false,
		'logo'        => 'contact-form-7.png',
		'group'       => $anesta_theme_required_plugins_groups['content'],
	),
	'democracy-poll'      => array(
		'title'       => esc_html__( 'Democracy Poll', 'anesta' ),
		'description' => '',
		'required'    => false,
		'logo'        => anesta_get_file_url( 'plugins/democracy-poll/democracy-poll.png' ),
		'group'       => $anesta_theme_required_plugins_groups['other'],
	),
	'elegro-payment'             => array(
		'title'       => esc_html__( 'Elegro Crypto Payment', 'anesta' ),
		'description' => esc_html__( "Extends WooCommerce Payment Gateways with an elegro Crypto Payment", 'anesta' ),
		'required'    => false,
		'logo'        => 'elegro-payment.png',
		'group'       => $anesta_theme_required_plugins_groups['ecommerce'],
	),
	'elementor'                  => array(
		'title'       => esc_html__( 'Elementor', 'anesta' ),
		'description' => esc_html__( "Is a beautiful PageBuilder, even the free version of which allows you to create great pages using a variety of modules.", 'anesta' ),
		'required'    => false,          // Leave this plugin unchecked on load Theme Dashboard
		'logo'        => 'elementor.png',
		'group'       => $anesta_theme_required_plugins_groups['page_builders'],
	),
	'echo-knowledge-base'      => array(
		'title'       => esc_html__( 'Knowledge Base for Documents and FAQs', 'anesta' ),
		'description' => '',
		'required'    => false,
		'logo'        => anesta_get_file_url( 'plugins/echo-knowledge-base/echo-knowledge-base.png' ),
		'group'       => $anesta_theme_required_plugins_groups['other'],
	),
	'sfwd-lms'      => array(
		'title'       => esc_html__( 'LearnDash LMS', 'anesta' ),
		'description' => '',
		'required'    => false,
		'logo'        => anesta_get_file_url( 'plugins/sfwd-lms/sfwd-lms.png' ),
		'group'       => $anesta_theme_required_plugins_groups['other'],
	),
	'learndash-course-grid'      => array(
		'title'       => esc_html__( 'LearnDash LMS - Course Grid', 'anesta' ),
		'description' => '',
		'required'    => false,
		'logo'        => anesta_get_file_url( 'plugins/sfwd-lms/sfwd-lms.png' ),
		'group'       => $anesta_theme_required_plugins_groups['other'],
	),
	'learnpress'                 => array(
        'title'       => esc_html__( 'LearnPress', 'anesta' ),
        'description' => '',
        'required'    => false,
        'logo'        => anesta_get_file_url( 'plugins/learnpress/learnpress.png' ),
        'group'       => $anesta_theme_required_plugins_groups['events'],
    ),
	'm-chart'              => array(
		'title'       => esc_html__( 'M Chart', 'anesta' ),
		'description' => '',
		'required'    => false,
		'install'     => true,
		'logo'        => anesta_get_file_url( 'plugins/m-chart/m-chart.png' ),
		'group'       => $anesta_theme_required_plugins_groups['other'],
	),
	'm-chart-highcharts-library'              => array(
		'title'       => esc_html__( 'M Chart Highcharts Library', 'anesta' ),
		'description' => '',
		'required'    => false,
		'install'     => true,
		'logo'        => anesta_get_file_url( 'plugins/m-chart/m-chart.png' ),
		'group'       => $anesta_theme_required_plugins_groups['other'],
	),
	'mailchimp-for-wp'           => array(
		'title'       => esc_html__( 'MailChimp for WP', 'anesta' ),
		'description' => esc_html__( "Allows visitors to subscribe to newsletters", 'anesta' ),
		'required'    => false,
		'logo'        => 'mailchimp-for-wp.png',
		'group'       => $anesta_theme_required_plugins_groups['socials'],
	),
	'paid-memberships-pro'      => array(
		'title'       => esc_html__( 'Paid Memberships Pro', 'anesta' ),
		'description' => '',
		'required'    => false,
		'logo'        => anesta_get_file_url( 'plugins/paid-memberships-pro/paid-memberships-pro.png' ),
		'group'       => $anesta_theme_required_plugins_groups['other'],
	),
	'buddypress-media'      => array(
		'title'       => esc_html__( 'rtMedia for WordPress, BuddyPress and bbPress', 'anesta' ),
		'description' => '',
		'required'    => false,
		'logo'        => anesta_get_file_url( 'plugins/bbpress/buddypress-media.png' ),
		'group'       => $anesta_theme_required_plugins_groups['socials'],
	),
	'the-events-calendar'        => array(
		'title'       => esc_html__( 'The Events Calendar', 'anesta' ),
		'description' => '',
		'required'    => false,
		'logo'        => 'the-events-calendar.png',
		'group'       => $anesta_theme_required_plugins_groups['events'],
	),
	'trx_addons'                 => array(
		'title'       => esc_html__( 'ThemeREX Addons', 'anesta' ),
		'description' => esc_html__( "Will allow you to install recommended plugins, demo content, and improve the theme's functionality overall with multiple theme options", 'anesta' ),
		'required'    => false,           // Check this plugin in the list on load Theme Dashboard
		'logo'        => 'trx_addons.png',
		'group'       => $anesta_theme_required_plugins_groups['core'],
	),
	'trx_updater'                => array(
		'title'       => esc_html__( 'ThemeREX Updater', 'anesta' ),
		'description' => esc_html__( "Update theme and theme-specific plugins from developer's upgrade server.", 'anesta' ),
		'required'    => false,
		'logo'        => 'trx_updater.png',
		'group'       => $anesta_theme_required_plugins_groups['other'],
	),
	'woocommerce'                => array(
		'title'       => esc_html__( 'WooCommerce', 'anesta' ),
		'description' => esc_html__( "Connect the store to your website and start selling now", 'anesta' ),
		'required'    => false,
		'logo'        => 'woocommerce.png',
		'group'       => $anesta_theme_required_plugins_groups['ecommerce'],
	),
	'wp-job-manager'      => array(
		'title'       => esc_html__( 'WP Job Manager', 'anesta' ),
		'description' => '',
		'required'    => false,
		'logo'        => anesta_get_file_url( 'plugins/wp-job-manager/wp-job-manager.png' ),
		'group'       => $anesta_theme_required_plugins_groups['other'],
	),
	'wp-job-manager-resumes'      => array(
		'title'       => esc_html__( 'WP Job Manager - Resume Manager', 'anesta' ),
		'description' => '',
		'required'    => false,
		'logo'        => anesta_get_file_url( 'plugins/wp-job-manager-resumes/wp-job-manager-resumes.png' ),
		'group'       => $anesta_theme_required_plugins_groups['other'],
	),
	'gutenberg'                  => array(
		'title'       => esc_html__( 'Gutenberg', 'anesta' ),
		'description' => esc_html__( "It's a posts editor coming in place of the classic TinyMCE. Can be installed and used in parallel with Elementor", 'anesta' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'gutenberg.png',
		'group'       => $anesta_theme_required_plugins_groups['page_builders'],
	),
	'sitepress-multilingual-cms' => array(
		'title'       => esc_html__( 'WPML - Sitepress Multilingual CMS', 'anesta' ),
		'description' => esc_html__( "Allows you to make your website multilingual", 'anesta' ),
		'required'    => false,
		'install'     => false,      // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'sitepress-multilingual-cms.png',
		'group'       => $anesta_theme_required_plugins_groups['content'],
	),
	'wp-gdpr-compliance'         => array(
		'title'       => esc_html__( 'WP GDPR Compliance', 'anesta' ),
		'description' => esc_html__( "Allow visitors to decide for themselves what personal data they want to store on your site", 'anesta' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'wp-gdpr-compliance.png',
		'group'       => $anesta_theme_required_plugins_groups['other'],
	),
	'trx_popup'                  => array(
		'title'       => esc_html__( 'ThemeREX Popup', 'anesta' ),
		'description' => esc_html__( "Add popup to your site.", 'anesta' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'trx_popup.png',
		'group'       => $anesta_theme_required_plugins_groups['other'],
	),
	'envato-market'              => array(
		'title'       => esc_html__( 'Envato Market', 'anesta' ),
		'description' => '',
		'required'    => false,
		'logo'        => 'envato-market.png',
		'group'       => $anesta_theme_required_plugins_groups['other'],
	)
);

if ( ANESTA_THEME_FREE ) {
	unset( $anesta_theme_required_plugins['js_composer'] );
	unset( $anesta_theme_required_plugins['vc-extensions-bundle'] );
	unset( $anesta_theme_required_plugins['easy-digital-downloads'] );
	unset( $anesta_theme_required_plugins['give'] );
	unset( $anesta_theme_required_plugins['bbpress'] );
	unset( $anesta_theme_required_plugins['booked'] );
	unset( $anesta_theme_required_plugins['content_timeline'] );
	unset( $anesta_theme_required_plugins['mp-timetable'] );
	unset( $anesta_theme_required_plugins['learnpress'] );
	unset( $anesta_theme_required_plugins['the-events-calendar'] );
	unset( $anesta_theme_required_plugins['calculated-fields-form'] );
	unset( $anesta_theme_required_plugins['essential-grid'] );
	unset( $anesta_theme_required_plugins['revslider'] );
	unset( $anesta_theme_required_plugins['ubermenu'] );
	unset( $anesta_theme_required_plugins['sitepress-multilingual-cms'] );
	unset( $anesta_theme_required_plugins['envato-market'] );
	unset( $anesta_theme_required_plugins['trx_updater'] );
	unset( $anesta_theme_required_plugins['trx_popup'] );
}

// Add plugins list to the global storage
anesta_storage_set( 'required_plugins', $anesta_theme_required_plugins );
