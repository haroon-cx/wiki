<?php
?>
<div class="cuim-container">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <h3>Existing Users</h3>
        <?php if (current_user_can('administrator') || current_user_can('editor')) {?>
            <button class="button button-primary cuim-show-create"> + Add New User</button>
        <?php }?>
    </div>

    <!-- Create Modal -->
    <div id="cuim-create-modal" class="cuim-modal">
        <div class="cuim-modal-content">
            <span class="cuim-close"><i class="fas fa-times"></i></span>
            <h3>Create User</h3>
            <form id="cuim-create-form" class="cuim-form">
                <input type="hidden" name="security" value="<?php echo wp_create_nonce('cuim_nonce'); ?>">
                 <label for="cuim_name">Name</label>
                <input type="text" name="cuim_name" placeholder="Enter your name..." required />
                   <label for="cuim_email">Email</label>
                <input type="email" name="cuim_email" placeholder="Enter your email..." required />
                   <label for="cuim_password">Password</label>
                <input type="password" name="cuim_password" placeholder="Enter your password..." required />

                <!-- 🔽 User Role Dropdown -->
                <label for="cuim_role">User Role</label>
                <select name="cuim_role" id="cuim_role" required>
                    <option value="">Select Role</option>
                    <?php if (current_user_can('administrator')) { ?>
                        <option value="editor">Manager</option>
                    <?php } ?>
                    <option value="contributor">Contributor</option>
                    <option value="viewer">Viewer</option>
                </select>

                <button type="submit" class="button button-primary" style="float: right">Create</button>
                <div class="cuim-message" id="cuim-create-message"></div>
            </form>
        </div>
    </div>

    <table class="cuim-table" style="overflow: unset !important;">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <?php if (current_user_can('administrator')) {?>
                <th style="text-align: center">Actions</th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach (get_users(['role__not_in' => ['administrator']]) as $u):
            // Get user role (only first role is used)
            $roles = $u->roles;
            $role = !empty($roles) ? $roles[0] : 'subscriber';
            $requested = get_user_meta($u->ID, 'cuim_requested_role', true);
            // Map WordPress role to display label
            if ($role === 'editor') {
                $role_key = 'editor';
            } elseif ($role === 'contributor') {
                $role_key = 'contributor';
            } elseif ($role === 'pending_user') {
                $role_key = $requested;
            } else {
                $role_key = 'viewer';
            }

            // Prepare user data for JS
            $user_data = [
                'ID'    => $u->ID,
                'name'  => $u->display_name ?: $u->user_login,
                'email' => $u->user_email,
                'role'  => $role_key,
            ];

            // Fetch requested role if user is pending
            $is_pending = ($role === 'pending_user');
            ?>
            <tr data-user-id="<?php echo esc_attr($u->ID); ?>">
                <td class="cuim-name"><?php echo esc_html($user_data['name']); ?></td>
                <td class="cuim-email"><?php echo esc_html($user_data['email']); ?></td>
                <td class="cuim-role">
                    <?php if ($role_key == 'editor') {?>
                        <?php echo 'Manager'; ?>
                    <?php }else{?>
                        <?php echo $role_key; ?>
                    <?php }?>
                    <?php if ($is_pending): ?>
                        <span style="color: #e67e22;">(Awaiting Approval)</span>
                        <!-- ✅ Approve Button (Admins only for pending users) -->
                        <?php if ($is_pending && current_user_can('administrator')): ?>
                            <button
                                    class="cuim-approve-user button"
                                    data-user-id="<?php echo esc_attr($u->ID); ?>"
                                    data-requested-role="<?php echo esc_attr($requested); ?>">
                                <i class="fas fa-check-square"></i>
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
                <?php if (current_user_can('administrator')) { ?>
                    <td style="text-align: center">
                        <!-- 🔄 Edit Button -->
                        <button
                                class="cuim-edit-ip button cuim-edit-button"
                                data-user='<?php echo json_encode($user_data); ?>'>
                            <i class="fas fa-pencil-alt"></i>
                        </button>

                        <!-- ❌ Delete Button -->
                        <button class="cuim-delete-ip button cuim-delete">
                            <i class="far fa-trash-alt"></i>
                        </button>

                    </td>
                <?php } ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>


    <!-- Edit Modal -->
    <div id="cuim-edit-modal" class="cuim-modal">
        <div class="cuim-modal-content">
            <span class="cuim-close">&times;</span>
            <h3>Edit User</h3>
            <form id="cuim-edit-form" class="cuim-form">
                <input type="hidden" name="security" value="<?php echo wp_create_nonce('cuim_nonce'); ?>">
                <input type="hidden" name="user_id" />

                <input type="text" name="cuim_name" placeholder="Name" required />
                <input type="email" name="cuim_email" placeholder="Email" required />
                <input type="password" name="cuim_password" placeholder="New Password (optional)" />

                <!-- 🔽 User Role Dropdown -->
                <!-- <label for="cuim_role">User Role</label> -->
                <select name="cuim_role" id="cuim_role" required>
                    <option value="">Select Role</option>
                    <option value="editor">Manager</option>
                    <option value="contributor">Contributor</option>
                    <option value="viewer">Viewer</option>
                </select>

                <button type="submit" class="button button-primary" style="float: right">Save</button>
                <div class="cuim-message" id="cuim-edit-message"></div>
            </form>
        </div>
    </div>


    <!-- Delete Modal -->
    <div id="cuim-delete-modal" class="cuim-modal">
        <div class="cuim-modal-content">
            <span class="cuim-close">&times;</span>
            <h3>Confirm Delete</h3>
            <p>Delete user <strong id="cuim-delete-email"></strong>?</p>
            <button id="cuim-confirm-delete" class="button button-danger" style="float: right">Delete</button>
        </div>
    </div>
</div>