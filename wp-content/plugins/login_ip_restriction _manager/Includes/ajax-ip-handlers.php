<?php
// Save IP
add_action('wp_ajax_cuim_save_ip', function () {
    check_ajax_referer('cuim_nonce', 'security');
    if (!current_user_can('administrator')) wp_send_json_error(__('Permission denied.', 'custom-user-ip-manager'));

    $uid = intval($_POST['user_id'] ?? 0);
    $ip  = sanitize_text_field($_POST['ip'] ?? '');

    if ($uid <= 0) wp_send_json_error(__('Invalid user.', 'custom-user-ip-manager'));

    update_user_meta($uid, 'allowed_ip', $ip);
    wp_send_json_success(__('IP updated.', 'custom-user-ip-manager'));
});

// Get All User IPs (no nonce for now to fix 400 error)
add_action('wp_ajax_cuim_get_ip_list', function () {
    check_ajax_referer('cuim_nonce', 'security');

    if (!current_user_can('administrator') && !current_user_can('editor')) {
        wp_send_json_error('Permission denied.');
    }

    $users = get_users(['role__not_in' => ['administrator']]);
    $result = [];

    foreach ($users as $user) {
        $result[] = [
            'id'    => $user->ID,
            'email' => $user->user_email,
            'ip'    => get_user_meta($user->ID, 'allowed_ip', true),
        ];
    }


    wp_send_json_success($result);
});


// Delete IP
add_action('wp_ajax_cuim_delete_ip', function () {
    check_ajax_referer('cuim_nonce', 'security');
    if (!current_user_can('administrator')) wp_send_json_error('Permission denied.');

    $uid = intval($_POST['user_id'] ?? 0);
    if ($uid <= 0) wp_send_json_error('Invalid user.');

    delete_user_meta($uid, 'allowed_ip');
    wp_send_json_success('IP deleted.');
});
