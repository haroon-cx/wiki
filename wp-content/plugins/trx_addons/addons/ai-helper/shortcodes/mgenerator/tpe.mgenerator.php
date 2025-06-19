<?php
/**
 * Template to represent shortcode as a widget in the Elementor preview area
 *
 * Written as a Backbone JavaScript template and using to generate the live preview in the Elementor's Editor
 *
 * @package ThemeREX Addons
 * @since v2.31.0
 */

extract( get_query_var( 'trx_addons_args_sc_mgenerator' ) );

$decorated = apply_filters( 'trx_addons_filter_sc_mgenerator_decorate_upload', true );
?><#
settings = trx_addons_elm_prepare_global_params( settings );
var styles_allowed = <?php echo apply_filters( 'trx_addons_filter_allow_sc_styles_in_elementor', false, 'sc_mgenerator' ) ? 'true' : 'false'; ?>;

var id = settings._element_id ? settings._element_id + '_sc' : 'sc_mgenerator_' + ( '' + Math.random() ).replace( '.', '' );

#><div id="{{ id }}" class="<# print( trx_addons_apply_filters('trx_addons_filter_sc_classes', 'sc_mgenerator sc_mgenerator_' + settings.type + ( styles_allowed ? ' trx_addons_customizable' : '' ), settings ) ); #>">

	<?php $element->sc_show_titles( 'sc_mgenerator' ); ?>

	<div class="sc_mgenerator_content sc_item_content"><#

		#><div class="sc_mgenerator_form sc_mgenerator_form_preview <#
			print( trx_addons_get_responsive_classes( 'sc_mgenerator_form_align_', settings, 'align', '' ).replace( /flex-start|flex-end/g, function( match ) {
				return match == 'flex-start' ? 'left' : 'right';
			} ) );
			#>"
			data-mgenerator-download-icon="{{ settings.button_download_icon }}"
		>
			<div class="sc_mgenerator_form_inner">
				<div class="sc_mgenerator_form_field sc_mgenerator_form_field_prompt<#
					if ( settings.show_settings ) {
						print( ' sc_mgenerator_form_field_prompt_with_settings' );
					}
				#>">
					<div class="sc_mgenerator_form_field_inner">
						<input type="text"
							value="{{ settings.prompt }}"
							id="sc_mgenerator_form_field_prompt_text"
							class="sc_mgenerator_form_field_prompt_text"
							placeholder="{{{ settings.placeholder_text || '<?php esc_attr_e('Describe what you want or hit a tag below', 'trx_addons'); ?>' }}}"
						>
						<a href="#" class="sc_mgenerator_form_field_prompt_button<#
							if ( ! settings.prompt ) {
								print( ' sc_mgenerator_form_field_disabled' );
							}
							if ( styles_allowed ) {
								print( ' sc_button_default' );
							}
							print( settings.button_image.url || ( settings.button_icon && ! trx_addons_is_off( settings.button_icon ) )
									? ' sc_mgenerator_form_field_prompt_button_with_icon'
									: ' sc_mgenerator_form_field_prompt_button_without_icon'
							);
						#>"><#
							if ( settings.button_image.url ) {
								image_type = trx_addons_get_file_ext( settings.button_image.url );
								if ( image_type == 'svg' ) {
									#><span class="sc_mgenerator_form_field_prompt_button_svg"><#
										print( trx_addons_get_inline_svg( settings.button_image.url, {
											render: function( html ) {
												if ( html ) {
													elementor.$previewContents.find( '#' + id + ' .sc_mgenerator_form_field_prompt_button_svg' ).html( html );
												}
											}
										} ) );
									#></span><#
								} else {
									#><img src="{{ settings.button_image.url }}" alt="{{ settings.button_image.alt }}" class="sc_mgenerator_form_field_prompt_button_image"><#
								}
							} else if ( settings.button_icon && ! trx_addons_is_off( settings.button_icon ) ) {
								#><span class="sc_mgenerator_form_field_prompt_button_icon {{ settings.button_icon }}"></span><#
							}
							if ( settings.button_text !== '#' ) {
								#><span class="sc_mgenerator_form_field_prompt_button_text">{{{ settings.button_text || '<?php esc_html_e( 'Generate', 'trx_addons' ); ?>' }}}</span><#
							}
						#></a>
					</div><#
					if ( settings.show_settings ) {
						var settings_image = settings.settings_button_image && settings.settings_button_image.url ? settings.settings_button_image.url : '',
							settings_icon = settings_image
												? ''
												: ( settings.settings_button_icon && ! trx_addons_is_off( settings.settings_button_icon )
													? settings.settings_button_icon
													: 'trx_addons_icon-sliders'
													);
						#><a href="#" class="sc_mgenerator_form_settings_button {{ settings_icon }}"><#
							if ( settings_image ) {
								image_type = trx_addons_get_file_ext( settings_image );
								if ( image_type == 'svg' ) {
									#><span class="sc_mgenerator_form_settings_button_svg"><#
										print( trx_addons_get_inline_svg( settings_image, {
											render: function( html ) {
												if ( html ) {
													elementor.$previewContents.find( '#' + id + ' .sc_mgenerator_form_settings_button_svg' ).html( html );
												}
											}
										} ) );
									#></span><#
								} else {
									#><img src="{{ settings_image }}" alt="{{ settings.settings_button_image.alt }}" class="sc_mgenerator_form_settings_button_image"><#
								}
							}
						#></a><#

						// Popup with settings
						#><div class="sc_mgenerator_form_settings"><#

							// Sample Rate (numeric field)
							#><div class="sc_mgenerator_form_settings_field sc_mgenerator_form_settings_field_sampling_rate">
								<label for="sc_mgenerator_form_settings_field_sampling_rate"><?php esc_html_e( 'Sampling Rate (Hz):', 'trx_addons' ); ?></label>
								<div class="sc_mgenerator_form_settings_field_numeric_wrap">
									<input
										type="number"
										name="sc_mgenerator_form_settings_field_sampling_rate"
										id="sc_mgenerator_form_settings_field_sampling_rate"
										min="10000"
										max="48000"
										step="1000"
										value="{{ settings.sampling_rate.value }}"
									>
									<div class="sc_mgenerator_form_settings_field_numeric_wrap_buttons">
										<a href="#" class="sc_mgenerator_form_settings_field_numeric_wrap_button sc_mgenerator_form_settings_field_numeric_wrap_button_inc"></a>
										<a href="#" class="sc_mgenerator_form_settings_field_numeric_wrap_button sc_mgenerator_form_settings_field_numeric_wrap_button_dec"></a>
									</div>
								</div>
							</div><#

							// Duration (sec) (numeric field)
							#><div class="sc_mgenerator_form_settings_field sc_mgenerator_form_settings_field_duration">
								<label for="sc_mgenerator_form_settings_field_duration"><?php esc_html_e( 'Duration (sec):', 'trx_addons'); ?></label>
								<div class="sc_mgenerator_form_settings_field_numeric_wrap">
									<input
										type="number"
										name="sc_mgenerator_form_settings_field_duration"
										id="sc_mgenerator_form_settings_field_duration"
										min="5"
										max="20"
										step="0.1"
										value="{{ settings.duration.value }}"
									>
									<div class="sc_mgenerator_form_settings_field_numeric_wrap_buttons">
										<a href="#" class="sc_mgenerator_form_settings_field_numeric_wrap_button sc_mgenerator_form_settings_field_numeric_wrap_button_inc"></a>
										<a href="#" class="sc_mgenerator_form_settings_field_numeric_wrap_button sc_mgenerator_form_settings_field_numeric_wrap_button_dec"></a>
									</div>
								</div>
							</div>
						</div><#
					}
				#></div><#

				// Upload the conditioning melody for audio generation
				if ( settings.show_upload_audio ) {
					#><div class="sc_mgenerator_form_field sc_mgenerator_form_field_upload_audio">
						<div class="sc_mgenerator_form_field_inner">
							<label for="sc_mgenerator_form_field_upload_audio_field"><?php esc_html_e( 'Upload the conditioning melody for audio generation (optional):', 'trx_addons' ); ?></label>
							<?php if ( $decorated ) { ?>
								<div class="sc_mgenerator_form_field_upload_audio_decorator theme_form_field_text">
									<span class="sc_mgenerator_form_field_upload_audio_text theme_form_field_placeholder"><?php esc_html_e( "Audio is not selected", 'trx_addons' ); ?></span>
									<span class="sc_mgenerator_form_field_upload_audio_button trx_addons_icon-upload"><?php esc_html_e( "Browse", 'trx_addons' ); ?></span>
							<?php } ?>
							<input type="file" id="sc_mgenerator_form_field_upload_audio_field" class="sc_mgenerator_form_field_upload_audio_field">
							<?php if ( $decorated ) { ?>
								</div>
							<?php } ?>
						</div>
					</div><#
				}
				if ( settings.tags && settings.tags.length && settings.tags[0].title ) {
					#><div class="sc_mgenerator_form_field sc_mgenerator_form_field_tags"><#
						if ( settings.tags_label ) {
							#><span class="sc_mgenerator_form_field_tags_label">{{ settings.tags_label }}</span><#
						}
						#><span class="sc_mgenerator_form_field_tags_list"><#
							_.each( settings.tags, function( tag ) {
								#><a href="#" class="sc_mgenerator_form_field_tags_item" data-tag-prompt="{{ tag.prompt }}">{{ tag.title }}</a><#
							} );
						#></span><#
					#></div><#
				}
			#></div><?php
				// Loading placeholder
				trx_addons_loading_layout( array( 'hidden' => true ) );
			?><#
			if ( settings.show_limits ) {
				#><div class="sc_mgenerator_limits">
					<span class="sc_mgenerator_limits_label"><?php
						echo wp_kses_post( sprintf( __( 'Limits per hour (day/week/month/year): %s audios.', 'trx_addons' ), '<span class="sc_mgenerator_limits_total_value">XX</span>' ) );
					?></span>
					<span class="sc_mgenerator_limits_value"><?php
						echo wp_kses_post( sprintf( __( 'Used: %s audios.', 'trx_addons' ), '<span class="sc_mgenerator_limits_used_value">YY</span>' ) );
					?></span>
				</div><#
			}
			#><div class="sc_mgenerator_message">
				<div class="sc_mgenerator_message_inner"></div>
				<a href="#" class="sc_mgenerator_message_close trx_addons_button_close" title="<?php esc_html_e( 'Close', 'trx_addons' ); ?>"><span class="trx_addons_button_close_icon"></span></a>
			</div>
		</div>
		<div class="sc_mgenerator_music"></div>
	</div>

	<?php $element->sc_show_links('sc_mgenerator'); ?>

</div><#

settings = trx_addons_elm_restore_global_params( settings );

#>