jQuery(document).ready(function($) {

    // console.log(cuim_ajax); // ✅ Check if this prints object

    // $.post(cuim_ajax.ajax_url, {
    //     action: 'test_ajax',
    //     security: cuim_ajax.nonce
    // }, function(response){
    //     console.log('RES' , response);
    // });

    function showModal(modal) {
        $(modal).fadeIn();
    }

    $('.cuim-show-ip-create').on('click', function () {
        $('#cuim-create-ip-modal').fadeIn();
    });
    $('.cuim-close').on('click', function () {
        $('.cuim-modal').fadeOut();
    });

    $('.cuim-show-create').on('click', function () {
        showModal('#cuim-create-modal');
    });

    // Create User (Corrected)
    $('#cuim-create-form').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.post(ajaxurl, formData + '&action=cuim_create_user', function(response) {
            if (response.success) {
                $('#cuim-create-message').html('<span style="color: green;">' + response.data + '</span>');
                $('#cuim-create-form')[0].reset();
                location.reload();
            } else {
                $('#cuim-create-message').html('<span style="color: red;">' + response.data + '</span>');
            }
        });
    });
    // Edit User
    $('.cuim-edit-button').on('click', function() {
        const user = $(this).data('user'); // assumed to be a JS object

        $('#cuim-edit-form [name="user_id"]').val(user.ID);
        $('#cuim-edit-form [name="cuim_name"]').val(user.name);
        $('#cuim-edit-form [name="cuim_email"]').val(user.email);
        $('#cuim-edit-form [name="cuim_role"]').val(user.role); // set role
        $('#cuim-edit-modal').show();
    });

    // 💾 Save edited user via AJAX
    $('#cuim-edit-form').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        $.post(ajaxurl, formData + '&action=cuim_update_user', function(response) {
            if (response.success) {
                $('#cuim-edit-message').html('<span style="color: green;">' + response.data + '</span>');
                location.reload();
            } else {
                $('#cuim-edit-message').html('<span style="color: red;">' + response.data + '</span>');
            }
        });
    });

    let deleteUserId = 0;
    $('.cuim-delete').on('click', function () {
        let row = $(this).closest('tr');
        deleteUserId = row.data('user-id');
        $('#cuim-delete-email').text(row.find('.cuim-email').text());
        showModal('#cuim-delete-modal');
    });

    $('#cuim-confirm-delete').on('click', function () {
        $.post(cuim_ajax.ajax_url, {
            action: 'cuim_delete_user',
            user_id: deleteUserId,
            security: cuim_ajax.nonce
        }, function (res) {
            if (res.success) {
                location.reload();
            } else {
                alert(res.data);
            }
        });
    });

    // Save IP (Corrected)
    $('#cuim-ip-save').on('click', function () {
        const uid = $('#cuim-user-select').val();
        const ip = $('#cuim-ip-input').val();

        const ipData = {
            action: 'cuim_save_ip',
            user_id: uid,
            ip: ip,
            security: cuim_ajax.nonce
        };

        $.post(cuim_ajax.ajax_url, ipData, function (res) {
            $('#cuim-ip-status').text(res.data);
            if (res.success) loadIpList();
        });
    });



    function loadIpList() {
        $.post(cuim_ajax.ajax_url, {
            action: 'cuim_get_ip_list',
            security: cuim_ajax.nonce
        }, function (res) {
            // console.log('IP LIST RESPONSE:', res); // Add this line

            if (!res.success) {
                console.warn('IP list load failed:', res.data);
                return;
            }
            const tbody = $('#cuim-ip-list tbody').empty();
            res.data.forEach(row => {
                if (!row.ip) return;
                tbody.append(`
                <tr data-user-id="${row.id}" data-user-email="${row.email}" data-ip="${row.ip}" class="${row.role}">
                    <td>${row.email}</td>
                    <td class="cuim-ip">${row.ip}</td>
                    <td style="text-align: center;">
                        <button class="cuim-edit-ip button sc_button_hover_slide_left"><i class="fas fa-pencil-alt"></i></button>
                        <button class="cuim-delete-ip button sc_button_hover_slide_left"> <i class="far fa-trash-alt"></i></button>
                    </td>
                </tr>
            `);
            });
        });
    }


    loadIpList();

    $(document).on('click', '.cuim-edit-ip', function () {
        const row = $(this).closest('tr');
        $('#cuim-edit-ip-user-id').val(row.data('user-id'));
        $('#cuim-edit-ip-input').val(row.data('ip'));
        showModal('#cuim-edit-ip-modal');
    });

    $('#cuim-edit-ip-form').on('submit', function (e) {
        e.preventDefault();
        $.post(cuim_ajax.ajax_url, $(this).serialize() + '&action=cuim_save_ip', function (res) {
            $('#cuim-edit-ip-message').text(res.data);
            if (res.success) {
                $('.cuim-modal').fadeOut();
                loadIpList();
            }
        });
    });

    $(document).on('click', '.cuim-delete-ip', function () {
        const uid = $(this).closest('tr').data('user-id');
        $.post(cuim_ajax.ajax_url, {
            action: 'cuim_delete_ip',
            user_id: uid,
            security: cuim_ajax.nonce
        }, function (res) {
            if (res.success) loadIpList();
        });
    });


    jQuery(document).on('click', '.cuim-approve-user', function () {
        var userId = jQuery(this).data('user-id');
        var role = jQuery(this).data('requested-role');

        jQuery.post(ajaxurl, {
            action: 'cuim_approve_user',
            security: cuim_ajax.nonce,
            user_id: userId,
            role: role
        }, function (response) {
            alert(response.data);
            if (response.success) location.reload();
        });
    });

    $('#cuim-viewer-toggle').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true);
        $.post(cuim_ajax.ajax_url, {
            action: 'cuim_toggle_viewer_mode'
        }, function(response) {
            if (response.success) {
                const state = response.data === '1' ? 'On' : 'Off';
                btn.find('span').text(state);
                btn.toggleClass('active', response.data === '1');
                window.location.href = '/';
            } else {
                alert('❌ ' + response.data);
            }
            btn.prop('disabled', false);
        });
    });

    $(document).on('click', '[data-load-profile]', function(e) {
        e.preventDefault();
        $('.post_content.entry-content').html('<p>🔄 Loading profile...</p>');
        $.post(cuim_ajax.ajax_url, { action: 'cuim_get_profile_html' }, function(response) {
            if (response.success) {
                $('.post_content.entry-content').html(response.data.html);
            } else {
                $('.post_content.entry-content').html('<p style="color:red;">❌ ' + response.data + '</p>');
            }
        });
    });

    $(document).on('submit', '#cuim-profile-page-form', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('action', 'cuim_save_profile');
        $.ajax({
            url: cuim_ajax.ajax_url,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                const msg = $('#cuim-profile-update-message');
                if (response.success) {
                    msg.html('<p style="color:green;">✅ ' + response.data + '</p>');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    msg.html('<p style="color:red;">❌ ' + response.data + '</p>');
                }
            }
        });
    });

    $(document).on('click', '#cuim-edit-name', function(e) {
        e.preventDefault();
        $('#cuim-edit-fields').slideToggle();
    });

    $.post(cuim_ajax.ajax_url, { action: 'cuim_get_profile_html' }, function(response) {
        if (response.success) {
            var isComplete = response.data.profile_complete === true || response.data.profile_complete === '1' || response.data.profile_complete === 1;
            if (!isComplete) {
                $('#cuim-edit-modal').fadeIn();
                $('.post_content.entry-content').html(response.data.html);
                alert('Please complete your profile before proceeding.');
            } else {

            }
        } else {
            $('.post_content.entry-content').html('<p>Error loading profile.</p>');
        }
    });

    
});
