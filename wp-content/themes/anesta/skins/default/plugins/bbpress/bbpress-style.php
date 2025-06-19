<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'anesta_bbpress_get_css' ) ) {
	add_filter( 'anesta_filter_get_css', 'anesta_bbpress_get_css', 10, 2 );
	function anesta_bbpress_get_css( $css, $args ) {
		if ( isset( $css['fonts'] ) && isset( $args['fonts'] ) ) {
			$fonts         = $args['fonts'];
			$css['fonts'] .= <<<CSS

/* Buttons */
div#buddypress .comment-reply-link,
div#buddypress .generic-button a,
div#buddypress input[type="reset"],
div#buddypress input[type="submit"],
div#buddypress ul.button-nav li a,
a.bp-title-button,

div#buddypress #aw-whats-new-submit,
div#buddypress #avatar-crop-actions a.button,
div#buddypress .avatar-history-actions .avatar-history-action,
div#buddypress .bp-profile-button .button,

div#buddypress .activity-comments form input,
div#buddypress .activity-comments form div.ac-reply-content a,
div#buddypress .activity-list li.load-more a, 
div#buddypress .activity-list li.load-newest a,

div#buddypress .standard-form div.submit input,
div#buddypress #bp-browse-button,
div#buddypress #bp-delete-cover-image, 
div#buddypress #bp-delete-avatar,
div#buddypress #notification-bulk-manage,

div#buddypress #friend-list a.button:not(.remove),
div#buddypress #members-list a.friendship-button,
div#buddypress #groups_search_submit,
div#buddypress #members_search_submit,

div#buddypress #bbp_search_submit,
div#buddypress #drag-drop-area input[type="button"],
div#buddypress #subnav #bp-create-doc-button,
div#buddypress #group-settings-form input[type="submit"],

div#buddypress #item-body #members-list li .action a, 
div#buddypress #item-body #friend-list li .action a, 
div#buddypress #item-body #admins-list li .action a,

div#buddypress #item-body > .rtmedia-container .rtm-load-more a,
div#buddypress .wp_attachment_image .rtmedia-image-edit,
div#buddypress #rtmedia_media_single_edit input[type="button"],
div#buddypress #rtmedia_media_single_edit .button,
div#buddypress #rtmedia-add-media-button-post-update,
div#buddypress .rtmedia-comment-media-upload,
div#buddypress #rt_media_comment_submit,
div#buddypress .rtmedia-editor-buttons .button,
.rtmedia-popup #rtmedia_create_new_album,

div#bbpress-forums #subscription-toggle a,
div#bbpress-forums #favorite-toggle a {
	{$fonts['button_font-family']}
	font-size: var(--theme-font-button_font-size); /* replace with {$fonts['button_font-size']} */
	{$fonts['button_font-weight']}
	{$fonts['button_font-style']}
	{$fonts['button_line-height']}
	{$fonts['button_text-decoration']}
	{$fonts['button_text-transform']}
	{$fonts['button_letter-spacing']}
}
.bbp-meta .bbp-reply-post-date,
div#buddypress table.profile-fields tr td.data,
div#buddypress .current-visibility-level,
div#buddypress div#item-header div#item-meta,
div#buddypress .activity-list .activity-content .activity-inner  {
	{$fonts['info_font-family']}
}
div#buddypress .dir-search input[type="search"],
div#buddypress .dir-search input[type="text"],
div#buddypress .groups-members-search input[type="search"],
div#buddypress .groups-members-search input[type="text"],
div#buddypress .standard-form input[type="color"],
div#buddypress .standard-form input[type="date"],
div#buddypress .standard-form input[type="datetime-local"],
div#buddypress .standard-form input[type="datetime"],
div#buddypress .standard-form input[type="email"],
div#buddypress .standard-form input[type="month"],
div#buddypress .standard-form input[type="number"],
div#buddypress .standard-form input[type="password"],
div#buddypress .standard-form input[type="range"],
div#buddypress .standard-form input[type="search"],
div#buddypress .standard-form input[type="tel"],
div#buddypress .standard-form input[type="text"],
div#buddypress .standard-form input[type="time"],
div#buddypress .standard-form input[type="url"],
div#buddypress .standard-form input[type="week"],
div#buddypress .standard-form select,
div#buddypress .standard-form textarea,
div#buddypress #whats-new-form textarea,
div#buddypress .activity-comments form textarea,
div#buddypress .standard-form input[type="text"],
div#buddypress .standard-form select,
div#buddypress .rtmedia-item-comments textarea,
div#bbpress-forums div.bbp-the-content-wrapper textarea.bbp-the-conten,
div#bbpress-forums fieldset.bbp-form input[type="password"], 
div#bbpress-forums fieldset.bbp-form input[type="text"], 
div#bbpress-forums fieldset.bbp-form select {
	{$fonts['input_font-family']}
	{$fonts['input_font-size']}
	{$fonts['input_font-weight']}
	{$fonts['input_font-style']}
	{$fonts['input_line-height']}
	{$fonts['input_text-decoration']}
	{$fonts['input_text-transform']}
	{$fonts['input_letter-spacing']}
}
div#buddypress div#item-header-cover-image .user-publicname {
	{$fonts['h2_font-family']}
	font-size: var(--theme-font-h2_font-size); /* replace with {$fonts['h2_font-size']} */
	{$fonts['h2_font-weight']}
	{$fonts['h2_font-style']}
	{$fonts['h2_line-height']}
	{$fonts['h2_text-decoration']}
	{$fonts['h2_text-transform']}
	{$fonts['h2_letter-spacing']}
}
.rtmedia-popup .rtm-modal-title {
	font-size: var(--theme-font-h2_font-size); /* replace with {$fonts['h2_font-size']} */
}
div#bbpress-forums .bbp-reply-form .bbp-form legend,
div#bbpress-forums .bbp-topic-form .bbp-form legend,
div#bbpress-forums .bbp-topic-split .bbp-form legend,
div#bbpress-forums .bbp-reply-move .bbp-form legend {
	{$fonts['h3_font-family']}
	font-size: var(--theme-font-h3_font-size); /* replace with {$fonts['h3_font-size']} */
	{$fonts['h3_font-weight']}
	{$fonts['h3_font-style']}
	{$fonts['h3_line-height']}
	{$fonts['h3_text-decoration']}
	{$fonts['h3_text-transform']}
	{$fonts['h3_letter-spacing']}
}
div#buddypress #groups-dir-search .bp-screen-reader-text,
div#buddypress #members-dir-search .bp-screen-reader-text {
	{$fonts['h4_font-family']}
	font-size: var(--theme-font-h4_font-size); /* replace with {$fonts['h4_font-size']} */
	{$fonts['h4_font-weight']}
	{$fonts['h4_font-style']}
	{$fonts['h4_line-height']}
	{$fonts['h4_text-decoration']}
	{$fonts['h4_text-transform']}
	{$fonts['h4_letter-spacing']}
	{$fonts['h4_margin-bottom']}
}
div#buddypress #groups-list > li .item .item-title,
div#buddypress #members-list > li .item .item-title,
div#buddypress #group-list > li h4,
div#bbpress-forums .bbp-forums .bbp-forum-title,
div#bbpress-forums .bbp-topics .bbp-topic-permalink,
div#bbpress-forums .bbp-topic-form .bbp-form .bbp-form legend,
div#bbpress-forums .bbp-reply-form .bbp-form .bbp-form legend {
	{$fonts['h5_font-family']}
	font-size: var(--theme-font-h5_font-size); /* replace with {$fonts['h5_font-size']} */
	{$fonts['h5_font-style']}
	{$fonts['h5_line-height']}
	{$fonts['h5_text-decoration']}
	{$fonts['h5_text-transform']}
	{$fonts['h5_letter-spacing']}
}

.widget.widget_bp_core_members_widget ul li .item .item-title, 
.wp-widget-bp_core_members_widget ul li .item .item-title,
div#buddypress .rtm-user-meta-details .username  {
	{$fonts['h6_font-family']}
	{$fonts['h6_font-size']}
	{$fonts['h6_font-style']}
	{$fonts['h6_line-height']}
	{$fonts['h6_text-decoration']}
	{$fonts['h6_text-transform']}
	{$fonts['h6_letter-spacing']}
}

CSS;
		}

		return $css;
	}
}

// Load skin-specific functions
$fdir = anesta_get_file_dir( 'plugins/bbpress/bbpress-skin.php' );
if ( ! empty( $fdir ) ) {
	require_once $fdir;
}