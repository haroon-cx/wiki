<?php
/**
 * Template to represent shortcode as a widget in the Elementor preview area
 *
 * Written as a Backbone JavaScript template and using to generate the live preview in the Elementor's Editor
 *
 * @package ThemeREX Addons
 * @since v2.20.2
 */

use TrxAddons\AiHelper\Lists;
use TrxAddons\AiHelper\Utils;
use TrxAddons\AiHelper\StableDiffusion;

extract( get_query_var( 'trx_addons_args_sc_vgenerator' ) );

$decorated = apply_filters( 'trx_addons_filter_sc_vgenerator_decorate_upload', true );
?><#
settings = trx_addons_elm_prepare_global_params( settings );

var id = settings._element_id ? settings._element_id + '_sc' : 'sc_vgenerator_' + ( '' + Math.random() ).replace( '.', '' );
var styles_allowed = <?php echo apply_filters( 'trx_addons_filter_allow_sc_styles_in_elementor', false, 'sc_vgenerator' ) ? 'true' : 'false'; ?>;

var models = JSON.parse( '<?php echo addslashes( json_encode( Lists::get_list_ai_video_models() ) ); ?>' );

if ( typeof models == 'object' ) {

    var aspect_ratios = JSON.parse( '<?php echo addslashes( json_encode( Lists::get_list_ai_video_ar() ) ); ?>' );
    var resolutions = JSON.parse( '<?php echo addslashes( json_encode( Lists::get_list_ai_video_resolutions() ) ); ?>' );
    var durations = JSON.parse( '<?php echo addslashes( json_encode( Lists::get_list_ai_video_durations() ) ); ?>' );

	#><div id="{{ id }}" class="test <# print( trx_addons_apply_filters('trx_addons_filter_sc_classes', 'sc_vgenerator sc_vgenerator_' + settings.type + ( styles_allowed ? ' trx_addons_customizable' : '' ), settings ) ); #>">

		<?php $element->sc_show_titles( 'sc_vgenerator' ); ?>

		<div class="sc_vgenerator_content sc_item_content"><#

			// Layout 'Default' -------------------------------------------------------
			if ( settings.type == 'default' ) {
				#><div class="sc_vgenerator_form sc_vgenerator_form_preview <#
					print( trx_addons_get_responsive_classes( 'sc_vgenerator_form_align_', settings, 'align', '' ).replace( /flex-start|flex-end/g, function( match ) {
						return match == 'flex-start' ? 'left' : 'right';
					} ) );
					#>"
					data-vgenerator-download-icon="{{ settings.button_download_icon }}"
				>
					<div class="sc_vgenerator_form_inner">
						<div class="sc_vgenerator_form_field sc_vgenerator_form_field_prompt<#
							if ( settings.show_settings ) {
								print( ' sc_vgenerator_form_field_prompt_with_settings' );
							}
						#>">
							<div class="sc_vgenerator_form_field_inner">
								<input type="text"
									value="{{ settings.prompt }}"
									id="sc_vgenerator_form_field_prompt_text"
									class="sc_vgenerator_form_field_prompt_text"
									placeholder="{{{ settings.placeholder_text || '<?php esc_attr_e('Describe what you want or hit a tag below', 'trx_addons'); ?>' }}}"
								>
								<a href="#" class="sc_vgenerator_form_field_prompt_button<#
									if ( ! settings.prompt ) {
										print( ' sc_vgenerator_form_field_disabled' );
									}
									if ( styles_allowed ) {
										print( ' sc_button_default' );
									}
									print( settings.button_image.url || ( settings.button_icon && ! trx_addons_is_off( settings.button_icon ) )
											? ' sc_vgenerator_form_field_prompt_button_with_icon'
											: ' sc_vgenerator_form_field_prompt_button_without_icon'
									);
								#>"><#
									if ( settings.button_image.url ) {
										image_type = trx_addons_get_file_ext( settings.button_image.url );
										if ( image_type == 'svg' ) {
											#><span class="sc_vgenerator_form_field_prompt_button_svg"><#
												print( trx_addons_get_inline_svg( settings.button_image.url, {
													render: function( html ) {
														if ( html ) {
															elementor.$previewContents.find( '#' + id + ' .sc_vgenerator_form_field_prompt_button_svg' ).html( html );
														}
													}
												} ) );
											#></span><#
										} else {
											#><img src="{{ settings.button_image.url }}" alt="{{ settings.button_image.alt }}" class="sc_vgenerator_form_field_prompt_button_image"><#
										}
									} else if ( settings.button_icon && ! trx_addons_is_off( settings.button_icon ) ) {
										#><span class="sc_vgenerator_form_field_prompt_button_icon {{ settings.button_icon }}"></span><#
									}
									if ( settings.button_text !== '#' ) {
										#><span class="sc_vgenerator_form_field_prompt_button_text">{{{ settings.button_text || '<?php esc_html_e( 'Generate', 'trx_addons' ); ?>' }}}</span><#
									}
								#></a>
							</div>
                            <# if ( settings.show_settings ) {
								var settings_image = settings.settings_button_image && settings.settings_button_image.url ? settings.settings_button_image.url : '',
									settings_icon = settings_image
														? ''
														: ( settings.settings_button_icon && ! trx_addons_is_off( settings.settings_button_icon )
															? settings.settings_button_icon
															: 'trx_addons_icon-sliders'
															);
								#><a href="#" class="sc_vgenerator_form_settings_button {{ settings_icon }}"><#
									if ( settings_image ) {
										image_type = trx_addons_get_file_ext( settings_image );
										if ( image_type == 'svg' ) {
											#><span class="sc_vgenerator_form_settings_button_svg"><#
												print( trx_addons_get_inline_svg( settings_image, {
													render: function( html ) {
														if ( html ) {
															elementor.$previewContents.find( '#' + id + ' .sc_vgenerator_form_settings_button_svg' ).html( html );
														}
													}
												} ) );
											#></span><#
										} else {
											#><img src="{{ settings_image }}" alt="{{ settings.settings_button_image.alt }}" class="sc_vgenerator_form_settings_button_image"><#
										}
									}
								#></a><#

								// Popup with settings
								#><div class="sc_vgenerator_form_settings"><#
									// Model
									#><div class="sc_vgenerator_form_settings_field">
										<label for="sc_vgenerator_form_settings_field_model"><?php esc_html_e('Model:', 'trx_addons'); ?></label>
										<select name="sc_vgenerator_form_settings_field_model" id="sc_vgenerator_form_settings_field_model"><#
											var group = false;
											for ( var model in models ) {
												if ( model.slice( -2 ) == '/-' || models[model].slice( 0, 2 ) == '\\-' ) {
													if ( group ) {
														#></optgroup><#
													}
													group = true;
													#><optgroup label="{{{ models[model].slice( 2 ) }}}"><#
												} else {
													#><option value="{{ model }}"<# if ( settings.model == model ) print( ' selected="selected"' ); #>>{{ models[model] }}</option><#
												}
											}
											if ( group ) {
												#></optgroup><#
											}
										#></select>
									</div><#

									//Aspect Ratio
									#><div class="sc_vgenerator_form_settings_field sc_vgenerator_form_settings_field_aspect_ratio">
										<label for="sc_vgenerator_form_settings_field_aspect_ratio"><?php esc_html_e( 'Aspect ratio:', 'trx_addons' ); ?></label>
										<select name="sc_vgenerator_form_settings_field_aspect_ratio" id="sc_vgenerator_form_settings_field_aspect_ratio"><#
											for ( var aspect_ratio in aspect_ratios ) {
												#><option value="{{ aspect_ratio }}" <# if ( settings.aspect_ratio == aspect_ratio ) print( ' selected="selected"' ); #>>{{ aspect_ratios[aspect_ratio] }}</option><#
											}
										#></select>
									</div><#

									//Resolutions
									#><div class="sc_vgenerator_form_settings_field sc_vgenerator_form_settings_field_resolution">
										<label for="sc_vgenerator_form_settings_field_resolution"><?php esc_html_e( 'Resolution:', 'trx_addons' ); ?></label>
										<select name="sc_vgenerator_form_settings_field_resolution" id="sc_vgenerator_form_settings_field_resolution"><#
											for ( var resolution in resolutions ) {
												#><option value="{{ resolution }}" <# if ( settings.resolution == resolution ) print( ' selected="selected"' ); #>>{{ resolutions[resolution] }}</option><#
											}
										#></select>
									</div><#

									//Durations
									#><div class="sc_vgenerator_form_settings_field sc_vgenerator_form_settings_field_duration">
										<label for="sc_vgenerator_form_settings_field_duration"><?php esc_html_e( 'Durations:', 'trx_addons' ); ?></label>
										<select name="sc_vgenerator_form_settings_field_duration" id="sc_vgenerator_form_settings_field_duration"><#
											for ( var duration in durations ) {
												#><option value="{{ duration }}" <# if ( settings.duration == duration ) print( ' selected="selected"' ); #>>{{ durations[duration] }}</option><#
											}
										#></select>
									</div>
                                </div><#
							}
						#></div><#
						if ( ! settings.allow_loop && ( settings.show_upload_frame0 || settings.show_upload_frame1 ) ) {
							var models_with_keyframes = JSON.parse( '<?php echo addslashes( json_encode( Lists::get_list_models_for_access_ai_video_keyframes() ) ); ?>' );
							#><div class="sc_vgenerator_form_field sc_vgenerator_form_field_upload_keyframe_wrap<# if ( settings.model && models_with_keyframes.indexOf( settings.model ) == -1 ) print( ' trx_addons_hidden' ); #>"><#
								if ( settings.show_upload_frame0 ) {
									#><div class="sc_vgenerator_form_field sc_vgenerator_form_field_upload_start_keyframe<# if ( settings.model && models_with_keyframes.indexOf( settings.model ) == -1 ) print( ' trx_addons_hidden' ); #>">
										<div class="sc_vgenerator_form_field_inner">
											<label for="sc_vgenerator_form_field_upload_keyframe_field"><?php esc_html_e('Upload start keyframe (optional):', 'trx_addons'); ?></label>
											<?php if ( $decorated ) { ?>
												<div class="sc_vgenerator_form_field_upload_keyframe_decorator theme_form_field_text">
													<span class="sc_vgenerator_form_field_upload_keyframe_text theme_form_field_placeholder"><?php esc_html_e( "Image is not selected", 'trx_addons' ); ?></span>
													<span class="sc_vgenerator_form_field_upload_keyframe_button trx_addons_icon-upload"><?php esc_html_e( "Browse", 'trx_addons' ); ?></span>
											<?php } ?>
											<input type="file"
												id="sc_vgenerator_form_field_upload_start_keyframe_field"
												class="sc_vgenerator_form_field_upload_start_keyframe_field sc_vgenerator_form_field_upload_keyframe_field"
												data-text-placeholder="<?php esc_html_e( "Image is not selected", 'trx_addons' ); ?>"
											>
											<?php if ( $decorated ) { ?>
												</div>
											<?php } ?>
										</div>
									</div><#
								}
								if ( settings.show_upload_frame1 ) {
									#><div class="sc_vgenerator_form_field sc_vgenerator_form_field_upload_end_keyframe<# if ( settings.model && models_with_keyframes.indexOf( settings.model ) == -1 ) print( ' trx_addons_hidden' ); #>">
										<div class="sc_vgenerator_form_field_inner">
											<label for="sc_vgenerator_form_field_upload_keyframe_field"><?php esc_html_e('Upload end keyframe (optional):', 'trx_addons'); ?></label>
											<?php if ( $decorated ) { ?>
												<div class="sc_vgenerator_form_field_upload_keyframe_decorator theme_form_field_text">
													<span class="sc_vgenerator_form_field_upload_keyframe_text theme_form_field_placeholder"><?php esc_html_e( "Image is not selected", 'trx_addons' ); ?></span>
													<span class="sc_vgenerator_form_field_upload_keyframe_button trx_addons_icon-upload"><?php esc_html_e( "Browse", 'trx_addons' ); ?></span>
											<?php } ?>
											<input type="file"
												id="sc_vgenerator_form_field_upload_end_keyframe_field"
												class="sc_vgenerator_form_field_upload_end_keyframe_field sc_vgenerator_form_field_upload_keyframe_field"
												data-text-placeholder="<?php esc_html_e( "Image is not selected", 'trx_addons' ); ?>"
											>
											<?php if ( $decorated ) { ?>
												</div>
											<?php } ?>
										</div>
									</div><#
								}
							#></div><#
						}
						if ( settings.tags && settings.tags.length && settings.tags[0].title ) {
							#><div class="sc_vgenerator_form_field sc_vgenerator_form_field_tags"><#
								if ( settings.tags_label ) {
									#><span class="sc_vgenerator_form_field_tags_label">{{ settings.tags_label }}</span><#
								}
								#><span class="sc_vgenerator_form_field_tags_list"><#
									_.each( settings.tags, function( tag ) {
										#><a href="#" class="sc_vgenerator_form_field_tags_item" data-tag-prompt="{{ tag.prompt }}">{{ tag.title }}</a><#
									} );
								#></span><#
							#></div><#
						}
					#></div><?php
						// Loading placeholder
						trx_addons_loading_layout( array( 'hidden' => true ) );
					?><#
					if ( settings.show_limits ) {
						#><div class="sc_vgenerator_limits">
							<span class="sc_vgenerator_limits_label"><?php
								echo wp_kses_post( sprintf( __( 'Limits per hour (day/week/month/year): %s videos.', 'trx_addons' ), '<span class="sc_vgenerator_limits_total_value">XX</span>' ) );
							?></span>
							<span class="sc_vgenerator_limits_value"><?php
								echo wp_kses_post( sprintf( __( 'Used: %s videos.', 'trx_addons' ), '<span class="sc_vgenerator_limits_used_value">YY</span>' ) );
							?></span>
						</div><#
					}
					#><div class="sc_vgenerator_message">
						<div class="sc_vgenerator_message_inner"></div>
						<a href="#" class="sc_vgenerator_message_close trx_addons_button_close" title="<?php esc_html_e( 'Close', 'trx_addons' ); ?>"><span class="trx_addons_button_close_icon"></span></a>
					</div>
				</div><#

				// Preview area with a generated video
				#><div class="sc_vgenerator_videos"></div><#

            // Custom layout from the theme ----------------------------------------------
			} else {
				trx_addons_do_action( 'trx_addons_action_sc_vgenerator_show_layout', settings );
			}
		#></div>

		<?php $element->sc_show_links('sc_vgenerator'); ?>

	</div><#

	settings = trx_addons_elm_restore_global_params( settings );

} else {

	#><div class="sc_vgenerator_error"><?php
		esc_html_e( 'Video Generator: No models available', 'trx_addons' );
	?></div><#

}
#>