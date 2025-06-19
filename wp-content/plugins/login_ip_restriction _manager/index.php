<?php
/**
 * Plugin Name: Custom User & IP Manager
 * Description: Provides [user_manager] and [user_ip_whitelist] shortcodes for admin user CRUD with popups and per-user IP whitelist via AJAX.
 * Version:     1.1
 * Author:      Your Name
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define('URIP_PATH', plugin_dir_path(__FILE__));
define('URIP_URL', plugin_dir_url(__FILE__));

// AJAX handlers
include_once URIP_PATH . 'Includes/ajax-user-handlers.php';
include_once URIP_PATH . 'Includes/ajax-ip-handlers.php';

// Enqueue assets
add_action('wp_enqueue_scripts', function () {
    if (is_user_logged_in()) {
        wp_enqueue_style('cuim-style', plugin_dir_url(__FILE__) . 'assets/css/cuim.css');
        wp_enqueue_script('cuim-script', plugin_dir_url(__FILE__) . 'assets/js/cuim.js', ['jquery'], null, true);
        wp_localize_script('cuim-script', 'cuim_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('cuim_nonce'),
        ]);
    }
});

// Shortcode: [user_manager]
add_shortcode('user_manager', function () {
    if (!is_user_logged_in() ||  (!current_user_can('administrator') && !current_user_can('editor') && !current_user_can('contributor'))) {
        return '<p>You must be an administrator to view this.</p>';
    }
    ob_start();
    include URIP_PATH . 'partials/user-manager.php';
    return ob_get_clean();
});

// Shortcode: [user_ip_whitelist]
add_shortcode('user_ip_whitelist', function () {
    if (!is_user_logged_in()) {
        return '<p>Please login.</p>';
    }
    ob_start();
    include URIP_PATH . 'partials/ip-whitelist.php';
    return ob_get_clean();
});
include URIP_PATH . 'partials/profile.php';

/**
 * Return the real client IP, accounting for Cloudflare and proxies.
 *
 * @return string
 */
function ipum_get_client_ip() {
    // Cloudflare header (preferred)
    if ( ! empty( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
        return sanitize_text_field( $_SERVER['HTTP_CF_CONNECTING_IP'] );
    }

    // Other common proxy headers
    $headers = [
        'HTTP_X_FORWARDED_FOR',
        'X_FORWARDED_FOR',
        'HTTP_CLIENT_IP',
        'CLIENT_IP',
    ];
    foreach ( $headers as $hdr ) {
        if ( ! empty( $_SERVER[ $hdr ] ) ) {
            // The header can contain a comma‐separated list of IPs; take the first one
            $ips = explode( ',', $_SERVER[ $hdr ] );
            return sanitize_text_field( trim( $ips[0] ) );
        }
    }

    // Fallback to REMOTE_ADDR
    return sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '' );
}
add_filter( 'authenticate', 'cui_pm_admin_bypass_ip_whitelist', 30, 3 );
function cui_pm_admin_bypass_ip_whitelist( $user, $username, $password ) {
    if ( is_wp_error( $user ) || ! $user instanceof WP_User ) {
        return $user;
    }

    // 1) Admins bypass IP checks
    if ( user_can( $user, 'administrator' ) ) {
        return $user;
    }

    // 2) Skip REST/AJAX/cron
    if (
        ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ||
        ( defined( 'DOING_AJAX'    ) && DOING_AJAX    ) ||
        ( defined( 'DOING_CRON'    ) && DOING_CRON    )
    ) {
        return $user;
    }

    // 3) Get whitelist and real client IP
    $allowed_ip = get_user_meta( $user->ID, 'allowed_ip', true );
    $current_ip = ipum_get_client_ip();

    // 4) Deny if no whitelist or mismatch
    if ( empty( $allowed_ip ) || $allowed_ip !== $current_ip ) {
        return new WP_Error(
            'ip_blocked',
            sprintf(
            /* translators: IP */
                __( 'Access denied. Your IP (%s) is not whitelisted.', 'custom-user-ip-manager' ),
                esc_html( $current_ip )
            )
        );
    }

    return $user;
}


add_filter('show_admin_bar', function() {
    return is_admin(); // true in wp-admin, false on frontend
});

add_filter( 'login_redirect', 'cui_pm_role_based_login_redirect', 1, 3 );
function cui_pm_role_based_login_redirect( $redirect_to, $requested_redirect_to, $user ) {
    // If it’s not a real WP_User, just fall back
    if ( is_wp_error( $user ) || ! $user instanceof WP_User ) {
        return $redirect_to;
    }



    // All others go to the front‐page
    return home_url();
}

add_action( 'login_enqueue_scripts', 'cui_pm_login_css_for_non_admins' );
function cui_pm_login_css_for_non_admins() {
    // Only apply to non-administrators
    if ( ! current_user_can( 'administrator' ) ) {
        ?>
        <style type="text/css">
            /* Your custom styling for the login logo container */
            .loginlogo {
                background-color: #1F2632 !important;
            }
            /* Your custom styling for the logo image */
            .loginlogo img {
                width: 9%;
                height: 50%;
            }
            body.login.js.login-action-login{
                background: #071021 !important;
                flex-direction: column;
                justify-content: flex-start;
                align-items: center;
            }
            div#login #nav {
                display: none !important;
            }
            div#login form .submit .button{
                background: #7644CE !important;
            }
        </style>
        <?php
    }
}



function cuim_register_custom_roles() {
    add_role('pending_user', 'Pending User', [
        'read' => false,
    ]);
}
register_activation_hook(__FILE__, 'cuim_register_custom_roles');


add_action('user_register', 'cuim_set_default_viewer_mode');

function cuim_set_default_viewer_mode($user_id) {
    update_user_meta($user_id, 'cuim_viewer_mode', 0);
}

