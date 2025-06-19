
<?php
$users = get_users(['role__not_in' => ['administrator']]);
?>
<div class="cuim-ip-whitelist">
    <?php wp_nonce_field('cuim_nonce', 'security'); ?>
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <h3>Whitelisted IPs</h3>
        <?php if (current_user_can('administrator')) { ?>
        <button class="button button-primary cuim-show-ip-create"> + Add New IP</button>
        <?php } ?>
    </div>

    <!-- Add IP Modal -->
    <div id="cuim-create-ip-modal" class="cuim-modal cuim-form">
        <div class="cuim-modal-content">
            <span class="cuim-close">&times;</span>
            <h3>Add New IP</h3>
            <select id="cuim-user-select">
                <option value=""><?php esc_html_e('Select User', 'custom-user-ip-manager'); ?></option>
                <?php foreach (get_users(['role__not_in' => ['administrator']]) as $u): ?>
                    <option value="<?php echo esc_attr($u->ID); ?>"><?php echo esc_html($u->user_email); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" id="cuim-ip-input" placeholder="Enter allowed IP" />
            <button id="cuim-ip-save" class="button button-primary" style="float: right">Save IP</button>
            <div id="cuim-ip-status"></div>
        </div>
    </div>

    <table class="cuim-table" id="cuim-ip-list">
        <thead>
        <tr><th>User</th><th>Allowed IP</th>
            <?php if (current_user_can('administrator')) {?>
            <th style="text-align: center">Actions</th>
            <?php  }?>
        </tr>
        </thead>
        <tbody>
      <?php foreach ($users as $user) {  ?>
          <?php
          $ip = get_user_meta($user->ID, 'allowed_ip', true);
          if (empty($ip)) continue; // Skip users without IP
          ?>
          <tr data-user-id="<?php echo esc_html($user->ID); ?>" data-user-email="<?php echo esc_html($user->user_email); ?>" data-ip="<?php echo esc_html($ip); ?>">
              <td><?php echo esc_html($user->user_email); ?></td>
              <td class="cuim-ip"><?php echo esc_html($ip); ?></td>
              <?php if (current_user_can('administrator')) {?>
              <td style="text-align: center;">
                  <button class="cuim-edit-ip button sc_button_hover_slide_left"><i class="fas fa-pencil-alt"></i></button>
                  <button class="cuim-delete-ip button sc_button_hover_slide_left"> <i class="far fa-trash-alt"></i></button>
              </td>
              <?php }?>
          </tr>
      <?php }?>
        </tbody>
    </table>

    <!-- Edit IP Modal -->
    <div id="cuim-edit-ip-modal" class="cuim-modal">
        <div class="cuim-modal-content">
            <span class="cuim-close">&times;</span>
            <h3>Edit Allowed IP</h3>
            <form id="cuim-edit-ip-form" class="cuim-form">
                <input type="hidden" name="security" value="<?php echo wp_create_nonce('cuim_nonce'); ?>">
                <input type="hidden" name="user_id" id="cuim-edit-ip-user-id">
                <input type="text" name="ip" id="cuim-edit-ip-input" placeholder="New IP" required>
                <button type="submit" class="button button-primary" style="float: right">Update</button>
                <div id="cuim-edit-ip-message"></div>
            </form>
        </div>
    </div>
</div>

