<?php
// Create User
add_action('wp_ajax_cuim_create_user', 'cuim_create_user_callback');

function cuim_create_user_callback() {
    check_ajax_referer('cuim_nonce', 'security');

    $name     = sanitize_user($_POST['cuim_name']);
    $email    = sanitize_email($_POST['cuim_email']);
    $password = $_POST['cuim_password'];
    $selected_role = sanitize_text_field($_POST['cuim_role']);

    if (username_exists($name) || email_exists($email)) {
        wp_send_json_error('User already exists.');
    }
    // ✅ Restrict created user’s email to @101infinity.com — regardless of admin status
    if (!preg_match('/@101infinity\\.com$/', $email)) {
        wp_send_json_error('Email must be from @101infinity.com domain.');
    }

    $current_user = wp_get_current_user();
    $is_admin = current_user_can('administrator');

    // Real role map
    $real_role = cuim_map_role($selected_role);

    if ($is_admin) {
        // ✅ Admin creates user directly with real role
        $final_role = $real_role;
    } else {
        // ❌ Manager creates user → set as pending
        $final_role = 'pending_user';
    }

    $user_id = wp_create_user($name, $password, $email);
    if (is_wp_error($user_id)) {
        wp_send_json_error('Failed to create user.');
    }

    // Set role
    wp_update_user([
        'ID'   => $user_id,
        'role' => $final_role,
    ]);

    // If pending, store requested role for approval
    if (!$is_admin) {
        update_user_meta($user_id, 'cuim_requested_role', $real_role);
    }

    wp_send_json_success($is_admin ? 'User created successfully.' : 'User created and awaiting admin approval.');
}

// Helper to map role string to WP role
function cuim_map_role($role) {
    if ($role === 'editor') return 'manager';
    if ($role === 'contributor') return 'contributor';
    if ($role === 'viewer') return 'subscriber';
    return 'subscriber';
}



// Edit User
add_action('wp_ajax_cuim_update_user', 'cuim_update_user_callback');

function cuim_update_user_callback() {
    check_ajax_referer('cuim_nonce', 'security');

    $user_id  = intval($_POST['user_id']);
    $name     = sanitize_user($_POST['cuim_name']);
    $email    = sanitize_email($_POST['cuim_email']);
    $password = $_POST['cuim_password'];
    $role     = sanitize_text_field($_POST['cuim_role']);

    if (!$user_id || !get_user_by('ID', $user_id)) {
        wp_send_json_error('User not found.');
    }
    // ✅ Restrict created user’s email to @101infinity.com — regardless of admin status
    if (!preg_match('/@101infinity\\.com$/', $email)) {
        wp_send_json_error('Email must be from @101infinity.com domain.');
    }

    $wp_role = 'subscriber';
    if ($role === 'editor') {
        $wp_role = 'editor';
    } elseif ($role === 'contributor') {
        $wp_role = 'contributor';
    } elseif ($role === 'viewer') {
        $wp_role = 'subscriber';
    }

    $update_data = [
        'ID'         => $user_id,
        'user_login' => $name,
        'user_email' => $email,
        'role'       => $wp_role,
    ];

    if (!empty($password)) {
        $update_data['user_pass'] = $password;
    }

    $result = wp_update_user($update_data);

    if (is_wp_error($result)) {
        wp_send_json_error('Failed to update user.');
    }

    wp_send_json_success('User updated successfully.');
}


// Delete User
add_action('wp_ajax_cuim_delete_user', function () {
    if (!current_user_can('administrator')) wp_send_json_error('Permission denied.');
    check_ajax_referer('cuim_nonce', 'security');
    $uid = intval($_POST['user_id'] ?? 0);
    if ($uid <= 0) wp_send_json_error('Invalid user.');
    if ($uid == get_current_user_id()) wp_send_json_error('You cannot delete your own account.');

    require_once ABSPATH . 'wp-admin/includes/user.php';
    wp_delete_user($uid);
    wp_send_json_success();
});


add_action('wp_ajax_cuim_approve_user', 'cuim_approve_user_callback');

function cuim_approve_user_callback() {
    check_ajax_referer('cuim_nonce', 'security');

    if (!current_user_can('administrator')) {
        wp_send_json_error('Access denied.');
    }

    $user_id = intval($_POST['user_id']);
    $role = sanitize_text_field($_POST['role']);

    $user = get_user_by('ID', $user_id);
    if (!$user) {
        wp_send_json_error('User not found.');
    }

    // Update to approved role
    $result = wp_update_user([
        'ID'   => $user_id,
        'role' => $role
    ]);

    if (is_wp_error($result)) {
        wp_send_json_error('Failed to approve user.');
    }

    delete_user_meta($user_id, 'cuim_requested_role');

    wp_send_json_success('User approved successfully.');
}

