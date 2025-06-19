<?php
/**
 * Plugin Name: Advanced Game Q&A System
 * Description: Custom front-end Q&A system for users and admins with AJAX, search, and moderation.
 * Version: 1.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;
// Plugin Setup
define('AGQA_PATH', plugin_dir_path(__FILE__));
define('AGQA_URL', plugin_dir_url(__FILE__));

// Includes
include_once AGQA_PATH . 'includes/install.php';
include_once AGQA_PATH . 'includes/shortcodes.php';
include_once AGQA_PATH . 'includes/ajax-handlers.php';

register_activation_hook(__FILE__, 'agqa_create_tables');

// Enqueue Scripts
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('agqa-style', AGQA_URL . 'assets/style.css');
    wp_enqueue_style('agqa-style-font-icon', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
    wp_enqueue_script('agqa-script', AGQA_URL . 'assets/main.js', ['jquery'], null, true);
    wp_localize_script('agqa-script', 'agqa_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('agqa_nonce'),
        'is_admin' => current_user_can('administrator'),
        'user_logged_in' => is_user_logged_in(),
        'current_user_id' => get_current_user_id()
    ]);

        if (!is_admin()) {
            wp_enqueue_media();
        }
});


add_action('wp_head', 'hide_mobile_menu_for_non_admin');
function hide_mobile_menu_for_non_admin() {
    if (is_user_logged_in() && (
            !current_user_can('administrator') &&
            !current_user_can('editor') &&
            !current_user_can('contributor')
        )) {
        ?>
        <style>
            ul#menu-main-menu .menu-item {
                display: none;
            }
            ul#menu-main-menu .menu-item:nth-child(2){
                display: block;
                font-size: 0;
            }
            ul#menu-main-menu .menu-item:nth-child(2) a{
                font-size: 0 !important;
            }
            ul#menu-main-menu .menu-item:nth-child(2) a:before{
                content: "Games";
                font-size: 20px !important;
            }
            .sidebar > .sidebar_inner > .widget ul#menu-main-menu li {
                background: var(--cuim-color-accent);
                border-radius: 8px;
            }
        </style>
        <?php
        }
    $user_id = get_current_user_id();
    if ( get_user_meta($user_id, 'cuim_viewer_mode', true)){ ?>

        <style>
            ul#menu-main-menu .menu-item {
                display: none;
            }
            ul#menu-main-menu .menu-item:nth-child(2){
                display: block;
                font-size: 0;
            }
            ul#menu-main-menu .menu-item:nth-child(2) a{
                font-size: 0 !important;
            }
            ul#menu-main-menu .menu-item:nth-child(2) a:before{
                content: "Games";
                font-size: 20px !important;
                color: #fff;
            }
            .sidebar > .sidebar_inner > .widget ul#menu-main-menu li {
                background: var(--cuim-color-accent);
                border-radius: 8px;
            }
        </style>
   <?php
    }
}
function cuim_allow_contributor_uploads() {
    $role = get_role('contributor');
    if ($role && !$role->has_cap('upload_files')) {
        $role->add_cap('upload_files');
    }
}
add_action('admin_init', 'cuim_allow_contributor_uploads');