<?php
/**
 * Template to represent shortcode as a widget in the Elementor preview area
 *
 * Written as a Backbone JavaScript template and using to generate the live preview in the Elementor's Editor
 *
 * @package ThemeREX Addons
 * @since v2.22.0
 */

extract( get_query_var( 'trx_addons_args_sc_tgenerator' ) );
?><#
settings = trx_addons_elm_prepare_global_params( settings );

var id = settings._element_id ? settings._element_id + '_sc' : 'sc_tgenerator_' + ( '' + Math.random() ).replace( '.', '' );
var styles_allowed = <?php echo apply_filters( 'trx_addons_filter_allow_sc_styles_in_elementor', false, 'sc_tgenerator' ) ? 'true' : 'false'; ?>;

#><div id="{{ id }}" class="<# print( trx_addons_apply_filters('trx_addons_filter_sc_classes', 'sc_tgenerator sc_tgenerator_' + settings.type + ( styles_allowed ? ' trx_addons_customizable' : '' ), settings ) ); #>">

	<?php $element->sc_show_titles( 'sc_tgenerator' ); ?>

	<div class="sc_tgenerator_content sc_item_content">
		<div class="sc_tgenerator_form sc_tgenerator_form_preview <#
					print( trx_addons_get_responsive_classes( 'sc_tgenerator_form_align_', settings, 'align', '' ).replace( /flex-start|flex-end/g, function( match ) {
						return match == 'flex-start' ? 'left' : 'right';
					} ) );
		#>">
			<div class="sc_tgenerator_form_inner"<#
				if ( settings.prompt_width.size && settings.prompt_width.size < 100 ) {
					print( ' style="width:' + settings.prompt_width.size + '%"' );
				}
			#>">
				<div class="sc_tgenerator_form_field sc_tgenerator_form_field_prompt">
					<div class="sc_tgenerator_form_field_inner">
						<input type="text"
							id="sc_tgenerator_form_field_prompt_text"
							class="sc_tgenerator_form_field_prompt_text"
							value="{{ settings.prompt }}"
							placeholder="{{{ settings.placeholder_text || '<?php esc_attr_e('Describe what you want or select a "Text type" or a "Process text" below', 'trx_addons'); ?>' }}}"
						>
						<a href="#" class="sc_tgenerator_form_field_prompt_button<#
							if ( ! settings.prompt ) {
								print( ' sc_tgenerator_form_field_disabled' );
							}
							if ( styles_allowed ) {
								print( ' sc_button_default' );
							}
							print( settings.button_image.url || ( settings.button_icon && ! trx_addons_is_off( settings.button_icon ) )
									? ' sc_tgenerator_form_field_prompt_button_with_icon'
									: ' sc_tgenerator_form_field_prompt_button_without_icon'
							);
						#>"><#
							if ( settings.button_image.url ) {
								image_type = trx_addons_get_file_ext( settings.button_image.url );
								if ( image_type == 'svg' ) {
									#><span class="sc_tgenerator_form_field_prompt_button_svg"><#
										print( trx_addons_get_inline_svg( settings.button_image.url, {
											render: function( html ) {
												if ( html ) {
													elementor.$previewContents.find( '#' + id + ' .sc_tgenerator_form_field_prompt_button_svg' ).html( html );
												}
											}
										} ) );
									#></span><#
								} else {
									#><img src="{{ settings.button_image.url }}" alt="{{ settings.button_image.alt }}" class="sc_tgenerator_form_field_prompt_button_image"><#
								}
							} else if ( settings.button_icon && ! trx_addons_is_off( settings.button_icon ) ) {
								#><span class="sc_tgenerator_form_field_prompt_button_icon {{ settings.button_icon }}"></span><#
							}
							if ( settings.button_text !== '#' ) {
								#><span class="sc_tgenerator_form_field_prompt_button_text">{{{ settings.button_text || '<?php esc_html_e( 'Generate', 'trx_addons' ); ?>' }}}</span><#
							}
						#></a>
					</div>
				</div>
				<textarea class="sc_tgenerator_text sc_tgenerator_form_field_hidden" placeholder="<?php esc_attr_e( 'Text to process...', 'trx_addons' ); ?>"></textarea>
				<div class="sc_tgenerator_form_field sc_tgenerator_form_field_tags">
					<span class="sc_tgenerator_form_field_tags_label"><?php esc_html_e( 'Write a', 'trx_addons' ); ?></span>
					<?php trx_addons_show_layout( trx_addons_sc_tgenerator_get_list_commands( 'write' ) ); ?>
					<span class="sc_tgenerator_form_field_tags_label"><?php esc_html_e( 'or', 'trx_addons' ); ?></span>
					<?php trx_addons_show_layout( trx_addons_sc_tgenerator_get_list_commands( 'process' ) ); ?>
					<span class="sc_tgenerator_form_field_tags_label sc_tgenerator_form_field_hidden"><?php esc_html_e( 'to', 'trx_addons' ); ?></span>
					<?php
					trx_addons_show_layout( trx_addons_sc_tgenerator_get_list_tones() );
					trx_addons_show_layout( trx_addons_sc_tgenerator_get_list_languages() );
					?>
				</div><#
				if ( settings.show_limits ) {
					#><div class="sc_tgenerator_limits">
						<span class="sc_tgenerator_limits_label"><?php
							echo wp_kses_post( sprintf( __( 'Limits per hour (day/week/month/year): %s requests.', 'trx_addons' ), '<span class="sc_tgenerator_limits_total_requests">XX</span>' ) );
						?></span>
						<span class="sc_tgenerator_limits_value"><?php
							echo wp_kses_post( sprintf( __( 'Used: %s requests.', 'trx_addons' ), '<span class="sc_tgenerator_limits_used_requests">YY</span>' ) );
						?></span>
					</div><#
				}
			#></div><?php
			// Loading placeholder
			trx_addons_loading_layout( array( 'hidden' => true ) );
		?></div>
		<div class="sc_tgenerator_result">
			<div class="sc_tgenerator_result_label"><?php esc_html_e( 'Result:', 'trx_addons' ); ?></div>
			<div class="sc_tgenerator_result_content"></div>
			<div class="sc_tgenerator_result_copy"><#
				var button_icon = settings.result_copy_icon ? settings.result_copy_icon : "trx_addons_icon-copy",
					button_image = settings.result_copy_image && settings.result_copy_image.url ? settings.result_copy_image.url : '',
					link_class = "<?php echo apply_filters( 'trx_addons_filter_sc_item_link_classes', 'sc_button sc_button_size_small', 'sc_tgenerator' ); ?>"
								+ ( button_icon && ! trx_addons_is_off( button_icon ) || button_image
									? ' sc_tgenerator_form_field_generate_button_with_icon sc_button_icon_left'
									: ' sc_tgenerator_form_field_generate_button_without_icon'
									);
				#><a href="#" class="{{ link_class }}"><#
					if ( button_icon && ! trx_addons_is_off( button_icon ) || button_image ) {
						#><span class="sc_button_icon"><#
						if ( button_image ) {
							var image_type = trx_addons_get_file_ext( button_image );
							if ( image_type == 'svg' ) {
								#><span class="sc_button_svg"><#
									print( trx_addons_get_inline_svg( button_image, {
										render: function( html ) {
											if ( html ) {
												elementor.$previewContents.find( '#' + id + ' .sc_button_svg' ).html( html );
											}
										}
									} ) );
								#></span><#
							} else {
								#><img src="{{ button_image }}" alt="{{ settings.button_image.alt }}" class="sc_button_image"><#
							}
						} else {
							#><span class="{{ button_icon }}"></span><#
						}
						#></span><#
					}
					#><span class="sc_button_text"><?php esc_html_e( 'Copy', 'trx_addons' ); ?></span><#
				#></a>
			</div>
		</div>
	</div>

	<?php $element->sc_show_links('sc_tgenerator'); ?>

</div><#

settings = trx_addons_elm_restore_global_params( settings );
#>