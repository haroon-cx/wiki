<?php
// includes/install.php

// Register the activation hook
// register_activation_hook(__FILE__, 'agqa_create_tables');

function agqa_create_tables()
{
    global $wpdb;
    $charset = $wpdb->get_charset_collate();

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    // Categories Table
    $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}agqa_categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset;");

    // Posts Table (Games)
    $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}agqa_posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_id INT NOT NULL,
        title VARCHAR(255),
        content TEXT,
        image_url TEXT,
        visible TINYINT(1) DEFAULT 1, 
        status VARCHAR(20) DEFAULT 'pending',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset;");

    // Questions Table
    $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}agqa_questions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        post_id INT NOT NULL,
        question TEXT NOT NULL,
        visible TINYINT(1) DEFAULT 1,
        status VARCHAR(20) DEFAULT 'pending',
        created_by INT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset;");

    // Answers Table
    $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}agqa_answers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        question_id INT NOT NULL,
        user_id INT,
        content TEXT NOT NULL,
        display_name VARCHAR(255),
        is_featured TINYINT(1) DEFAULT 0,
        visible TINYINT(1) DEFAULT 1,
        status VARCHAR(20) DEFAULT 'pending',
        like_count INT DEFAULT 0,   -- New column for like count
        dislike_count INT DEFAULT 0, -- New column for dislike count
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset;");

    // Complaints Table
    $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}agqa_complaints (
        id INT AUTO_INCREMENT PRIMARY KEY,
        answer_id INT NOT NULL,
        user_id INT, 
        reason TEXT,
        note TEXT,
        status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        admin_note TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT NULL
    ) $charset;");
    // Create table for question complaints
    $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}agqa_complaints_questions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            question_id INT NOT NULL,
            user_id INT, 
            reason TEXT,
            note TEXT,
            status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
            admin_note TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT NULL
        ) $charset;");

    // New Table for Tracking User Likes and Dislikes
    $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}agqa_user_likes_dislikes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        answer_id INT NOT NULL,
        action_type ENUM('like', 'dislike') NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE(user_id, answer_id, action_type)  -- Ensure user can only like/dislike once per answer
    ) $charset;");
}
