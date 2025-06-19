jQuery(document).ready(function() {
	'use strict';

	window.trx_addons_ai_helper_blog_generator = function( action, data, $button ) {
		trx_addons_msgbox_dialog(
			'<div class="ai_helper_blog_generator_form_field">'
				+ '<label for="ai_helper_blog_generator_posts_total">' + TRX_ADDONS_STORAGE['elm_ai_blog_generator_posts_total'] + '</label>'
				+ '<input type="number" min="1" max="100" value="9" step="1" id="ai_helper_blog_generator_posts_total" >'
			+ '</div>'
			+ '<div class="ai_helper_blog_generator_form_field">'
				+ '<label for="trx_addons_ai_helper_blog_generator_title_case_title">' + TRX_ADDONS_STORAGE['elm_ai_blog_generator_title_case'] + '</label>'
				+ '<select id="ai_helper_blog_generator_title_case_title">'
					+ '<option value="title">' + TRX_ADDONS_STORAGE['elm_ai_blog_generator_title_case_title'] + '</option>'
					+ '<option value="sentence" selected>' + TRX_ADDONS_STORAGE['elm_ai_blog_generator_title_case_sentence'] + '</option>'
				+ '</select>'
			+ '</div>'
			+ '<div class="ai_helper_blog_generator_form_field">'
				+ '<label for="ai_helper_blog_generator_cats_per_post">' + TRX_ADDONS_STORAGE['elm_ai_blog_generator_cats_per_post'] + '</label>'
				+ '<input type="number" min="0" max="10" value="1" step="1" id="ai_helper_blog_generator_cats_per_post" >'
			+ '</div>'
			+ '<div class="ai_helper_blog_generator_form_field">'
				+ '<label for="ai_helper_blog_generator_tags_per_post">' + TRX_ADDONS_STORAGE['elm_ai_blog_generator_tags_per_post'] + '</label>'
				+ '<input type="number" min="0" max="10" value="3" step="1" id="ai_helper_blog_generator_tags_per_post" >'
			+ '</div>'
			+ '<div class="ai_helper_blog_generator_form_field">'
				+ '<label for="ai_helper_blog_generator_comments_per_post">' + TRX_ADDONS_STORAGE['elm_ai_blog_generator_comments_per_post'] + '</label>'
				+ '<input type="number" min="0" max="10" value="2" step="1" id="ai_helper_blog_generator_comments_per_post" >'
			+ '</div>'
			+ '<div class="ai_helper_blog_generator_form_field">'
				+ '<label for="ai_helper_blog_generator_comments_every_post">' + TRX_ADDONS_STORAGE['elm_ai_blog_generator_comments_every_post'] + '</label>'
				+ '<input type="number" min="1" max="10" value="2" step="1" id="ai_helper_blog_generator_comments_every_post" >'
			+ '</div>',
			TRX_ADDONS_STORAGE[ 'elm_ai_blog_generator_dialog_caption' ],
			null,
			function( btn, box ) {
				if ( btn !== 1 ) {
					return;
				}

				// Add form data to the request
				data.posts_total = box.find( '#ai_helper_blog_generator_posts_total' ).val();
				if ( data.posts_total < 1 ) {
					return;
				}
				data.title_case = box.find( '#ai_helper_blog_generator_title_case_title' ).val();
				data.cats_per_post = box.find( '#ai_helper_blog_generator_cats_per_post' ).val();
				data.tags_per_post = box.find( '#ai_helper_blog_generator_tags_per_post' ).val();
				data.comments_per_post = box.find( '#ai_helper_blog_generator_comments_per_post' ).val();
				data.comments_every_post = box.find( '#ai_helper_blog_generator_comments_every_post' ).val();

				// Send data to the server
				$button.addClass('trx_addons_loading');
				jQuery.post( TRX_ADDONS_STORAGE['ajax_url'], data ).done( function( response ) {
					$button.removeClass('trx_addons_loading');
					var rez = {};
					if ( response === '' || response === 0 ) {
						rez = { error: TRX_ADDONS_STORAGE['msg_ajax_error'] };
					} else {
						try {
							rez = JSON.parse( response );
						} catch (e) {
							rez = { error: TRX_ADDONS_STORAGE['msg_ajax_error'] };
							console.log( response );
						}
					}
					if ( rez.error !== '' ) {
						alert( rez.error );
					} else if ( typeof rez.data == 'undefined' || typeof rez.data.posts == 'undefined' ) {
						alert( TRX_ADDONS_STORAGE['elm_ai_company_generator_bad_data'] );
					} else {
						if ( rez.data.posts.length == 0 ) {
							alert( TRX_ADDONS_STORAGE['elm_ai_blog_generator_no_posts'] );
						} else {
							alert( TRX_ADDONS_STORAGE['elm_ai_blog_generator_posts_inserted'].replace( '%d', rez.data.posts.length ) );
						}
					}
				} );
			}
		);
	};

} );