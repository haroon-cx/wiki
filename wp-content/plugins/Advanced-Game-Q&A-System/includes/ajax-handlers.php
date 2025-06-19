<?php
// includes/ajax-handlers.php

add_action('wp_ajax_nopriv_agqa_get_categories', 'agqa_get_categories');
add_action('wp_ajax_agqa_get_categories', 'agqa_get_categories');
function agqa_get_categories()
{
    check_ajax_referer('agqa_nonce', 'nonce');
    global $wpdb;
    $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}agqa_categories ORDER BY name ASC");
    wp_send_json_success($results);
}

add_action('wp_ajax_agqa_add_category', 'agqa_add_category');
function agqa_add_category()
{
    check_ajax_referer('agqa_nonce', 'nonce');
    if (!current_user_can('administrator')) wp_send_json_error('Not allowed');
    global $wpdb;
    $name = sanitize_text_field($_POST['name']);
    $wpdb->insert("{$wpdb->prefix}agqa_categories", ['name' => $name]);
    wp_send_json_success(['id' => $wpdb->insert_id]);
}

add_action('wp_ajax_nopriv_agqa_get_posts', 'agqa_get_posts');
add_action('wp_ajax_agqa_get_posts', 'agqa_get_posts');
function agqa_get_posts()
{
    check_ajax_referer('agqa_nonce', 'nonce');
    global $wpdb;
    if (current_user_can('administrator') || current_user_can('editor') || current_user_can('contributor')) {
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}agqa_posts ORDER BY created_at DESC");
    } else {
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}agqa_posts WHERE visible = 1 AND status IN ('approve') ORDER BY created_at DESC");
    }
    wp_send_json_success($results);
}

add_action('wp_ajax_agqa_add_post', 'agqa_add_post');
function agqa_add_post()
{
    check_ajax_referer('agqa_nonce', 'nonce');

    if (!current_user_can('administrator') && !current_user_can('editor') && !current_user_can('contributor')) {
        wp_send_json_error('Not allowed');
    }

    global $wpdb;
    $message = [];
    // Determine status based on user role
    if (current_user_can('administrator')) {
        // Administrator can set status from POST or default to 'approve'
        $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : 'approve';
        $message = 'Add New Game Successfully.';
    } elseif (current_user_can('contributor')) {
        // Editor's posts go to pending automatically
        $status = 'pending';
        $message = 'New game has been created successfully and is currently pending approval.';
    } else {
        // Other users not allowed (just in case)
        wp_send_json_error('Not allowed');
    }

    $inserted = $wpdb->insert("{$wpdb->prefix}agqa_posts", [
        'category_id' => intval($_POST['category_id']),
        'title' => sanitize_text_field($_POST['title']),
        'content' => sanitize_textarea_field($_POST['content']),
        'image_url' => esc_url_raw($_POST['image_url']),
        'status' => $status
    ]);

    if ($inserted) {
        wp_send_json_success([
            'message' => $message,
            'status' => 'success',
            'id' => $wpdb->insert_id
        ]);
    } else {
        wp_send_json_error('Insert failed');
    }
}


add_action('wp_ajax_nopriv_agqa_get_questions', 'agqa_get_questions');
add_action('wp_ajax_agqa_get_questions', 'agqa_get_questions');
function agqa_get_questions()
{
    check_ajax_referer('agqa_nonce', 'nonce');
    global $wpdb;
    $post_id = intval($_POST['post_id']);
    $questions = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}agqa_questions WHERE post_id = %d", $post_id));
    foreach ($questions as &$q) {
        $q->featured = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}agqa_answers WHERE question_id = %d AND is_featured = 1", $q->id));
    }
    wp_send_json_success($questions);
}

add_action('wp_ajax_agqa_add_question', 'agqa_add_question');
function agqa_add_question()
{
    check_ajax_referer('agqa_nonce', 'nonce');
    if (!current_user_can('administrator')) wp_send_json_error('Not allowed');
    global $wpdb;
    $wpdb->insert("{$wpdb->prefix}agqa_questions", [
        'post_id' => intval($_POST['post_id']),
        'question' => sanitize_text_field($_POST['question']),
        'created_by' => get_current_user_id()
    ]);
    wp_send_json_success(['id' => $wpdb->insert_id]);
}

add_action('wp_ajax_nopriv_agqa_get_answers', 'agqa_get_answers');
add_action('wp_ajax_agqa_get_answers', 'agqa_get_answers');
function agqa_get_answers()
{
    check_ajax_referer('agqa_nonce', 'nonce');
    global $wpdb;
    $question_id = intval($_POST['question_id']);
    $answers = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}agqa_answers WHERE question_id = %d AND is_featured = 0 ORDER BY created_at ASC", $question_id));
    wp_send_json_success($answers);
}

add_action('wp_ajax_agqa_submit_answer', 'agqa_submit_answer');
function agqa_submit_answer()
{
    check_ajax_referer('agqa_nonce', 'nonce');
    global $wpdb;
    $wpdb->insert("{$wpdb->prefix}agqa_answers", [
        'question_id' => intval($_POST['question_id']),
        'user_id' => get_current_user_id(),
        'content' => sanitize_textarea_field($_POST['content'])
    ]);
    wp_send_json_success(['message' => 'Answer submitted.']);
}

// add_action('wp_ajax_agqa_submit_complaint', 'agqa_submit_complaint');
// function agqa_submit_complaint() {
//     check_ajax_referer('agqa_nonce', 'nonce');
//     global $wpdb;
//     $wpdb->insert("{$wpdb->prefix}agqa_complaints", [
//         'answer_id' => intval($_POST['answer_id']),
//         'user_id' => get_current_user_id(),
//         'reason' => sanitize_textarea_field($_POST['reason'])
//     ]);
//     wp_send_json_success(['message' => 'Complaint submitted.']);
// }


// Handle Reporting for Answers
add_action('wp_ajax_agqa_submit_complaint', 'agqa_submit_complaint');
add_action('wp_ajax_nopriv_agqa_submit_complaint', 'agqa_submit_complaint');

function agqa_submit_complaint()
{
    global $wpdb;

    // Verify nonce for security
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'agqa_nonce')) {
        wp_send_json_error(array('message' => 'Invalid nonce'));
    }

    $answer_id = intval($_POST['answer_id']);
    $reason = sanitize_text_field($_POST['reason']);
    $note = sanitize_textarea_field($_POST['note']);
    $user_id = get_current_user_id(); // If the user is logged in

    // Insert complaint into the answer complaints table
    $table_name = $wpdb->prefix . 'agqa_complaints';
    $wpdb->insert(
        $table_name,
        array(
            'answer_id' => $answer_id,
            'user_id' => $user_id,
            'reason' => $reason,
            'note' => $note,
            'status' => 'pending',
        )
    );

    wp_send_json_success();
}


// Handle Reporting for Questions
add_action('wp_ajax_agqa_submit_question_complaint', 'agqa_submit_question_complaint');
add_action('wp_ajax_nopriv_agqa_submit_question_complaint', 'agqa_submit_question_complaint');

function agqa_submit_question_complaint()
{
    global $wpdb;

    // Verify nonce for security
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'agqa_nonce')) {
        wp_send_json_error(array('message' => 'Invalid nonce'));
    }

    $question_id = intval($_POST['question_id']);
    $reason = sanitize_text_field($_POST['reason']);
    $note = sanitize_textarea_field($_POST['note']);
    $user_id = get_current_user_id(); // If the user is logged in

    // Insert complaint into the question complaints table
    $table_name = $wpdb->prefix . 'agqa_complaints_questions';
    $wpdb->insert(
        $table_name,
        array(
            'question_id' => $question_id,
            'user_id' => $user_id,
            'reason' => $reason,
            'note' => $note,
            'status' => 'pending',
        )
    );

    wp_send_json_success();
}

// END


add_action('wp_ajax_agqa_get_complaints', 'agqa_get_complaints');
function agqa_get_complaints()
{
    check_ajax_referer('agqa_nonce', 'nonce');
    if (!current_user_can('administrator')) wp_send_json_error('Unauthorized');
    global $wpdb;
    $q = "SELECT c.*, a.content AS answer_text FROM {$wpdb->prefix}agqa_complaints c
          JOIN {$wpdb->prefix}agqa_answers a ON c.answer_id = a.id
          WHERE c.status = 'pending' ORDER BY c.created_at DESC";
    $results = $wpdb->get_results($q);
    wp_send_json_success($results);
}

add_action('wp_ajax_agqa_moderate_complaint', 'agqa_moderate_complaint');
function agqa_moderate_complaint()
{
    check_ajax_referer('agqa_nonce', 'nonce');
    if (!current_user_can('administrator')) wp_send_json_error('Unauthorized');
    global $wpdb;
    $id = intval($_POST['complaint_id']);
    $decision = sanitize_text_field($_POST['decision']);
    $note = sanitize_textarea_field($_POST['note']);
    $complaint = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}agqa_complaints WHERE id = %d", $id));

    if ($decision === 'approved') {
        $wpdb->update("{$wpdb->prefix}agqa_answers", ['is_featured' => 1], ['id' => $complaint->answer_id]);
    }

    $wpdb->update("{$wpdb->prefix}agqa_complaints", [
        'status' => $decision,
        'admin_note' => $note
    ], ['id' => $id]);

    $user = get_user_by('id', $complaint->user_id);
    if ($user && $decision === 'rejected') {
        wp_mail($user->user_email, 'Your Complaint was Rejected', $note);
    }

    wp_send_json_success(['message' => 'Updated.']);
}
add_action('wp_ajax_nopriv_agqa_search_all', 'agqa_search_all');
add_action('wp_ajax_agqa_search_all', 'agqa_search_all');
function agqa_search_all()
{
    check_ajax_referer('agqa_nonce', 'nonce');
    global $wpdb;
    $term = '%' . $wpdb->esc_like($_POST['term']) . '%';

    // 🔄 Updated: now also returns question_id always
    $query = $wpdb->prepare(
        "SELECT 'question' AS type, q.id AS question_id, q.question AS content, p.title AS post_title
         FROM {$wpdb->prefix}agqa_questions q
         JOIN {$wpdb->prefix}agqa_posts p ON q.post_id = p.id
         WHERE q.question LIKE %s
         UNION
         SELECT 'answer' AS type, q.id AS question_id, a.content, p.title
         FROM {$wpdb->prefix}agqa_answers a
         JOIN {$wpdb->prefix}agqa_questions q ON a.question_id = q.id
         JOIN {$wpdb->prefix}agqa_posts p ON q.post_id = p.id
         WHERE a.content LIKE %s
         ORDER BY post_title ASC",
        $term,
        $term
    );

    $results = $wpdb->get_results($query);
    wp_send_json_success($results);
}


add_action('wp_ajax_agqa_edit_game_full', 'agqa_edit_game_full');

function agqa_edit_game_full()
{
    check_ajax_referer('agqa_nonce', 'nonce');

    $game_id = intval($_POST['game_id']);
    $new_title = sanitize_text_field($_POST['new_title']);
    $new_image = esc_url_raw($_POST['new_image']);
    $new_description = sanitize_textarea_field($_POST['new_description']);

    global $wpdb;
    $updated = $wpdb->update(
        "{$wpdb->prefix}agqa_posts",  // ✅ change if your table name is different
        [
            'title'     => $new_title,
            'image_url' => $new_image,
            'content'   => $new_description,
        ],
        ['id' => $game_id]
    );

    if ($updated !== false) {
        if ($updated === 0) {
            wp_send_json_success(['message' => 'No changes made (values are the same).']);
        } else {
            wp_send_json_success(['message' => 'Game updated successfully.']);
        }
    } else {
        wp_send_json_error(['message' => 'Database update failed.', 'error' => $wpdb->last_error]);
    }
}
add_action('wp_ajax_agqa_toggle_game_visibility', 'agqa_toggle_game_visibility');

function agqa_toggle_game_visibility()
{
    check_ajax_referer('agqa_nonce', 'nonce');

    $game_id = intval($_POST['game_id']);
    $status = ($_POST['status'] === 'hide') ? 0 : 1;

    global $wpdb;
    $updated = $wpdb->update(
        "{$wpdb->prefix}agqa_posts",  // ✅ change if needed
        ['visible' => $status],
        ['id' => $game_id]
    );

    if ($updated !== false) {
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
}
add_action('wp_ajax_agqa_update_status', 'agqa_update_status');

function agqa_update_status()
{
    check_ajax_referer('agqa_nonce', 'nonce');

    $game_id = intval($_POST['game_id']);
    $status = sanitize_text_field($_POST['status']);

    $allowed_status = ['pending', 'reject', 'approve'];
    if (!in_array($status, $allowed_status, true)) {
        wp_send_json_error(['message' => 'Invalid status']);
        wp_die();
    }

    global $wpdb;
    // Update the main post status
    $updated = $wpdb->update(
        $wpdb->prefix . 'agqa_posts',
        ['status' => $status],
        ['id' => $game_id],
        ['%s'],  // format for status (string)
        ['%d']   // format for id (integer)
    );

    // Update related questions status
    $questions_updated = $wpdb->update(
        $wpdb->prefix . 'agqa_questions',
        ['status' => $status],
        ['post_id' => $game_id],
        ['%s'],
        ['%d']
    );

    $questions = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT id FROM {$wpdb->prefix}agqa_questions WHERE post_id = %d",
            $game_id
        )
    );

    foreach ($questions as $question) {
        $question_id = $question->id;
        // Update related answers status
        $answers_updated = $wpdb->update(
            $wpdb->prefix . 'agqa_answers',
            ['status' => $status],
            ['question_id' => $question_id],
            ['%s'],
            ['%d']
        );
    }


    if ($updated !== false) {
        wp_send_json_success();
    } else {
        wp_send_json_error(['message' => 'Database update failed']);
    }
    wp_die();
}



add_action('wp_ajax_agqa_edit_question', 'agqa_edit_question');

function agqa_edit_question()
{
    check_ajax_referer('agqa_nonce', 'nonce');

    if (!current_user_can('administrator') && !current_user_can('contributor')) {
        wp_send_json_error('Permission denied');
    }

    $question_id = intval($_POST['question_id']);
    $new_question = sanitize_text_field($_POST['new_question']);

    if (!$question_id || empty($new_question)) {
        wp_send_json_error('Invalid data');
    }

    global $wpdb;

    // By default keep status as is
    $status_to_set = null;

    // If editor, force status to pending
    if (current_user_can('contributor')) {
        $status_to_set = 'pending';
    }

    $data = ['question' => $new_question];
    if ($status_to_set !== null) {
        $data['status'] = $status_to_set;
    }

    $updated = $wpdb->update(
        "{$wpdb->prefix}agqa_questions",
        $data,
        ['id' => $question_id]
    );

    if ($updated !== false) {
        wp_send_json_success();
    } else {
        wp_send_json_error('Update failed');
    }
}




add_action('wp_ajax_agqa_toggle_question_visibility', 'agqa_toggle_question_visibility');

function agqa_toggle_question_visibility()
{
    check_ajax_referer('agqa_nonce', 'nonce');

    $question_id = intval($_POST['question_id']);
    $status = sanitize_text_field($_POST['status']);

    if (!in_array($status, ['show', 'hide'], true)) {
        wp_send_json_error('Invalid status');
    }

    $visible = ($status === 'show') ? 1 : 0;

    global $wpdb;

    // By default keep status as is
    $status_to_set = null;

    // If editor, force status to pending
    if (current_user_can('editor') && !current_user_can('administrator')) {
        $status_to_set = 'pending';
    }

    $data = ['visible' => $visible];
    if ($status_to_set !== null) {
        $data['status'] = $status_to_set;
    }

    $updated = $wpdb->update(
        "{$wpdb->prefix}agqa_questions",
        $data,
        ['id' => $question_id]
    );

    if ($updated !== false) {
        wp_send_json_success();
    } else {
        wp_send_json_error('Update failed');
    }
}



add_action('wp_ajax_agqa_update_question_status', 'agqa_update_question_status');

function agqa_update_question_status()
{
    check_ajax_referer('agqa_nonce', 'nonce');

    $question_id = intval($_POST['question_id']);
    $status = sanitize_text_field($_POST['status']);

    $allowed_status = ['pending', 'approve', 'reject'];
    if (!in_array($status, $allowed_status, true)) {
        wp_send_json_error('Invalid status');
    }

    global $wpdb;
    $updated = $wpdb->update(
        "{$wpdb->prefix}agqa_questions",
        ['status' => $status],
        ['id' => $question_id]
    );

    if ($updated !== false) {
        wp_send_json_success();
    } else {
        wp_send_json_error('Database update failed');
    }
}



add_action('wp_ajax_agqa_dropdown_action', 'agqa_dropdown_action');
function agqa_dropdown_action()
{
    check_ajax_referer('agqa_nonce', 'nonce');

    if (!current_user_can('administrator')) {
        wp_send_json_error('Permission denied');
    }

    $answer_id = intval($_POST['answer_id']);
    $action = sanitize_text_field($_POST['dropdown_action']);

    if (!$answer_id || empty($action)) {
        wp_send_json_error('Invalid data');
    }

    // Example: update status column in DB
    global $wpdb;
    $updated = $wpdb->update(
        "{$wpdb->prefix}agqa_answers",
        ['status' => $action],
        ['id' => $answer_id]
    );

    if ($updated !== false) {
        wp_send_json_success();
    } else {
        wp_send_json_error('Database update failed');
    }
}


add_action('wp_ajax_agqa_like_answer', 'agqa_like_answer');
function agqa_like_answer()
{
    check_ajax_referer('agqa_nonce', 'nonce');

    // Ensure answer_id is set and valid
    $answer_id = isset($_POST['answer_id']) ? intval($_POST['answer_id']) : 0;
    $user_id = get_current_user_id();

    if ($answer_id > 0) {
        global $wpdb;

        // Check if the user has already liked this answer
        $existing_like = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->prefix}agqa_user_likes_dislikes WHERE user_id = %d AND answer_id = %d AND action_type = %s",
                $user_id,
                $answer_id,
                'like'
            )
        );

        if ($existing_like > 0) {
            wp_send_json_error(['message' => 'You have already liked this answer']);
            return;
        }

        // Add like to the database
        $wpdb->insert("{$wpdb->prefix}agqa_user_likes_dislikes", [
            'user_id' => $user_id,
            'answer_id' => $answer_id,
            'action_type' => 'like'
        ]);

        // Increment the like_count for the answer
        $wpdb->query(
            $wpdb->prepare(
                "UPDATE {$wpdb->prefix}agqa_answers SET like_count = like_count + 1 WHERE id = %d",
                $answer_id
            )
        );

        wp_send_json_success(['message' => 'Liked successfully']);
    } else {
        wp_send_json_error(['message' => 'Invalid answer ID']);
    }
}

add_action('wp_ajax_agqa_dislike_answer', 'agqa_dislike_answer');
function agqa_dislike_answer()
{
    check_ajax_referer('agqa_nonce', 'nonce');

    // Ensure answer_id is set and valid
    $answer_id = isset($_POST['answer_id']) ? intval($_POST['answer_id']) : 0;
    $user_id = get_current_user_id();

    if ($answer_id > 0) {
        global $wpdb;

        // Check if the user has already disliked this answer
        $existing_dislike = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->prefix}agqa_user_likes_dislikes WHERE user_id = %d AND answer_id = %d AND action_type = %s",
                $user_id,
                $answer_id,
                'dislike'
            )
        );

        if ($existing_dislike > 0) {
            wp_send_json_error(['message' => 'You have already disliked this answer']);
            return;
        }

        // Add dislike to the database
        $wpdb->insert("{$wpdb->prefix}agqa_user_likes_dislikes", [
            'user_id' => $user_id,
            'answer_id' => $answer_id,
            'action_type' => 'dislike'
        ]);

        // Increment the dislike_count for the answer
        $wpdb->query(
            $wpdb->prepare(
                "UPDATE {$wpdb->prefix}agqa_answers SET dislike_count = dislike_count + 1 WHERE id = %d",
                $answer_id
            )
        );

        wp_send_json_success(['message' => 'Disliked successfully']);
    } else {
        wp_send_json_error(['message' => 'Invalid answer ID']);
    }
}
