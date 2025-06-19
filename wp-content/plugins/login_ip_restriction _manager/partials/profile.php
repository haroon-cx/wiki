<?php
add_action('wp_footer', 'cuim_display_user_profile_box');
function cuim_display_user_profile_box() {
    if (!is_user_logged_in()) return;

    $user_id = get_current_user_id();
    $first = get_user_meta($user_id, 'first_name', true);
    $last = get_user_meta($user_id, 'last_name', true);
    $user = wp_get_current_user();
    $avatar_id = get_user_meta($user_id, 'cuim_profile_avatar', true);
    $avatar_url = $avatar_id ? wp_get_attachment_url($avatar_id) : get_avatar_url($user_id);
    $logout_url = wp_logout_url(home_url());
    ?>
    <div class="cuim-profile-box">
        <img src="<?php echo esc_url($avatar_url); ?>" alt="Avatar" />
        <div class="cuim-profile-dropdown">
            <div style="display: flex; align-items: center; margin-bottom: 24px;">
                <img src="<?php echo esc_url($avatar_url); ?>" alt="Avatar" />
                <div>
                    <div class="cuim-user-name"><?php echo esc_html($first . ' ' . $last); ?></div>
                    <span class="cuim-profile-name"><?php echo esc_html($user->user_email); ?></span>
                </div>
            </div>
            <div class="cuim-profile-button-box">
                <a href="#" class="cuim-edit-profile-button" style="background: #747474" data-load-profile>Edit Profile</a>
                <a href="<?php echo esc_url($logout_url); ?>" class="cuim-logout-button" style="background: #7644CE">Logout</a>
            </div>
        </div>
    </div>
    <?php
}
add_shortcode('cuim_frontend_profile', 'cuim_render_frontend_profile');
function cuim_render_frontend_profile() {
    $user_id = get_current_user_id();
    $first = get_user_meta($user_id, 'first_name', true);
    $last = get_user_meta($user_id, 'last_name', true);
    $avatar_id = get_user_meta($user_id, 'cuim_profile_avatar', true);
    $avatar_url = $avatar_id ? wp_get_attachment_url($avatar_id) : get_avatar_url($user_id);

    ob_start(); ?>
    <div class="cuim-profile-form-wrapper">
        <form method="post" enctype="multipart/form-data" id="cuim-profile-page-form">
            <div style="text-align: center">
                <label for="upload-file-button" class="cuim-file-upload-label" style="display: block; cursor: pointer">
                    <img id="cuim-avatar-preview" src="<?php echo esc_url($avatar_url); ?>" alt="Avatar" style="max-width:150px; border-radius: 50%;">
                </label>
                <input type="file" name="cuim_avatar" accept="image/*" id="upload-file-button" style="display: none;">

                <div class="cuim-name-block">
                    <h2><?php echo esc_html($first . ' ' . $last); ?></h2>
                </div>
            </div>
            <div id="cuim-edit-fields" >
                <input type="text" name="cuim_first" value="<?php echo esc_attr($first); ?>" required>
                <input type="text" name="cuim_last" value="<?php echo esc_attr($last); ?>" required>
                <div style="text-align: right">
                    <button type="submit">Update Profile</button>
                </div>
            </div>
        </form>
        <div id="cuim-profile-update-message"></div>
    </div>

    <script>
        jQuery(document).ready(function($) {
        var $input = $('#upload-file-button');

        $input.on('change', function() {
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#cuim-avatar-preview').attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        });
        });

    </script>
    <?php return ob_get_clean();
}
// Profile completeness check function
function cuim_is_profile_complete($user_id) {
    $first = get_user_meta($user_id, 'first_name', true);
    $last = get_user_meta($user_id, 'last_name', true);
    $avatar = get_user_meta($user_id, 'cuim_profile_avatar', true);

    return (!empty($first) && !empty($last) && !empty($avatar));
}

// Modified AJAX handler for profile HTML
add_action('wp_ajax_cuim_get_profile_html', 'cuim_get_profile_html');
function cuim_get_profile_html() {
    if (!is_user_logged_in()) {
        wp_send_json_error("You must be logged in.");
    }

    $user_id = get_current_user_id();
    $profile_complete = cuim_is_profile_complete($user_id);

    ob_start();
    echo do_shortcode('[cuim_frontend_profile]');
    $html = ob_get_clean();

    wp_send_json_success([
        'html' => $html,
        'profile_complete' => $profile_complete,
    ]);
}

add_action('wp_ajax_cuim_save_profile', 'cuim_save_profile');
function cuim_save_profile() {
    if (!is_user_logged_in()) wp_send_json_error("Not logged in.");

    $user_id = get_current_user_id();
    if (empty($_POST['cuim_first']) || empty($_POST['cuim_last'])) {
        wp_send_json_error("First and Last name are required.");
    }

    update_user_meta($user_id, 'first_name', sanitize_text_field($_POST['cuim_first']));
    update_user_meta($user_id, 'last_name', sanitize_text_field($_POST['cuim_last']));

    if (!empty($_FILES['cuim_avatar']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attachment_id = media_handle_upload('cuim_avatar', 0);
        if (is_wp_error($attachment_id)) {
            wp_send_json_error("Image upload failed: " . $attachment_id->get_error_message());
        }
        update_user_meta($user_id, 'cuim_profile_avatar', $attachment_id);
    }

    wp_send_json_success("Profile updated successfully.");
}


add_action('wp_body_open', 'cui_pm_add_logout_button_footer');
function cui_pm_add_logout_button_footer() {
    if (is_user_logged_in() && (
            current_user_can('administrator') ||
            current_user_can('editor') ||
            current_user_can('contributor')
        )) {

        // Get saved viewer mode flag for current user
        $user_id = get_current_user_id();
        $viewer_mode = get_user_meta($user_id, 'cuim_viewer_mode', true);
        $is_on = ($viewer_mode === '1'); // boolean
        echo '
<label class="viewer-toggle-wrapper">
    <span>Viewer Mode</span>
    <input type="checkbox" id="cuim-viewer-toggle" ' . ($is_on ? 'checked' : '') . ' />
    <span class="slider"></span>
</label>';

//        echo '<button class="button cuim-viewer-mode-button ' . esc_attr($active) . '" id="cuim-viewer-toggle">
//            🔍 Viewer Mode: <span>' . ($viewer_mode === '1' ? 'On' : 'Off') . '</span>
//        </button>';

    }

    echo '
        <div id="agqa-search-box">
            <input type="text" id="agqa-search-input" placeholder="Search all questions and answers...">
            <div id="agqa-search-results"></div>
        </div>
        ';
    ?>

    <style>
        html {
            background-image: linear-gradient(rgba(0, 0, 0, 0.67), rgba(0, 0, 0, 0.67)),
            url('<?php echo URIP_URL; ?>/assets/image/101-body-image.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
        }
        body {
            background: transparent !important;
        }
    </style>

    <?php
}



add_action('wp_ajax_cuim_toggle_viewer_mode', 'cuim_toggle_viewer_mode');
function cuim_toggle_viewer_mode() {
    if (!is_user_logged_in()) {
        wp_send_json_error('Not logged in.');
    }

    $user_id = get_current_user_id();
    $current = get_user_meta($user_id, 'cuim_viewer_mode', true);
    $new_value = ($current === '1') ? '0' : '1';
    update_user_meta($user_id, 'cuim_viewer_mode', $new_value);

    wp_send_json_success($new_value);
}

