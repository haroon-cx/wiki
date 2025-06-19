<?php
/**
 * The style "default" of the IGenerator
 *
 * @package ThemeREX Addons
 * @since v2.20.2
 */

use TrxAddons\AiHelper\Lists;
use TrxAddons\AiHelper\Utils;

$args = get_query_var('trx_addons_args_sc_igenerator');
$styles_allowed = apply_filters( 'trx_addons_filter_allow_sc_styles_in_elementor', false, 'sc_igenerator' );

$models = Lists::get_list_ai_image_models();
$styles = Lists::get_list_stability_ai_styles();
$styles_openai = Lists::get_list_openai_styles();
$sizes = Lists::get_list_ai_image_sizes();
$openai_sizes = Lists::get_list_ai_image_sizes( 'openai' );

$id_suffix = trx_addons_instance_id_suffix( 'sc_igenerator' );

if ( count( $models ) > 0 ) {

	?><div <?php if ( ! empty( $args['id'] ) ) echo ' id="' . esc_attr( $args['id'] ) . '"'; ?> 
		class="sc_igenerator sc_igenerator_<?php
			echo esc_attr( $args['type'] );
			if ( $styles_allowed ) echo ' trx_addons_customizable';
			if ( ! empty( $args['class'] ) ) echo ' ' . esc_attr( $args['class'] );
			?>"<?php
		if ( ! empty( $args['css'] ) ) echo ' style="' . esc_attr( $args['css'] ) . '"';
		trx_addons_sc_show_attributes( 'sc_igenerator', $args, 'sc_wrapper' );
		?>><?php

		trx_addons_sc_show_titles('sc_igenerator', $args);

		?><div class="sc_igenerator_content sc_item_content"<?php trx_addons_sc_show_attributes( 'sc_igenerator', $args, 'sc_items_wrapper' ); ?>>
			<div class="sc_igenerator_form <?php
				echo esc_attr( str_replace( array( 'flex-start', 'flex-end' ), array( 'left', 'right' ), trx_addons_get_responsive_classes( 'sc_igenerator_form_align_', $args, 'align', '' ) ) );
				?>"
				data-igenerator-default-model="<?php echo esc_attr( $args['model'] ); ?>"
				data-igenerator-number="<?php echo esc_attr( $args['number'] ); ?>"
				data-igenerator-popup="<?php echo ! empty( $args['show_popup'] ) && (int) $args['show_popup'] > 0 ? '1' : ''; ?>"
				data-igenerator-demo-images="<?php echo ! empty( $args['demo_images'] ) && ! empty( $args['demo_images'][0]['url'] ) ? '1' : ''; ?>"
				data-igenerator-limit-exceed="<?php echo esc_attr( trx_addons_get_option( "ai_helper_sc_igenerator_limit_alert" . ( ! empty( $args['premium'] ) ? '_premium' : '' ) ) ); ?>"
				data-igenerator-download-icon="<?php echo ! empty( $args['button_download_icon'] ) ? esc_attr( $args['button_download_icon'] ) : 'trx_addons_icon-download'; ?>"
				data-igenerator-settings="<?php
					echo esc_attr( trx_addons_encode_settings( array(
						'number' => $args['number'],
						'columns' => $args['columns'],
						'columns_tablet' => $args['columns_tablet'],
						'columns_mobile' => $args['columns_mobile'],
						'size' => $args['size'],
						'width' => $args['width'],
						'height' => $args['height'],
						'demo_thumb_size' => $args['demo_thumb_size'],
						'demo_images' => $args['demo_images'],
						'model' => $args['model'],
						'premium' => ! empty( $args['premium'] ) ? 1 : 0,
						'show_download' => ! empty( $args['show_download'] ) ? 1 : 0,
						'show_upload' => ! empty( $args['show_upload'] ) ? 1 : 0,
						'show_prompt_translated' => ! empty( $args['show_prompt_translated'] ) ? 1 : 0,
						'safety_checker' => $args['safety_checker'],
						'quality' => $args['quality'],
						'system_prompt' => trim( $args['system_prompt'] ),
						// 'upscale' => ! empty( $args['upscale'] ) ? 1 : 0,
						// 'quality' => ! empty( $args['quality'] ) ? 1 : 0,
						// 'panorama' => ! empty( $args['panorama'] ) ? 1 : 0,
					) ) );
			?>">
				<div class="sc_igenerator_form_inner"<?php
					// If a shortcode is called not from Elementor, we need to add the width of the prompt field and alignment
					if ( empty( $args['prompt_width_extra'] ) ) {
						$css = '';
						if ( ! empty( $args['prompt_width'] ) && (int)$args['prompt_width'] < 100 ) {
							$css = 'width:' . esc_attr( $args['prompt_width'] ) . '%;';
						}
						if ( ! empty( $css ) ) {
							echo ' style="' . esc_attr( $css ) . '"';
						}
					}
				?>>
					<div class="sc_igenerator_form_field sc_igenerator_form_field_prompt<?php
						if ( ! empty( $args['show_settings'] ) && (int) $args['show_settings'] > 0 ) {
							echo ' sc_igenerator_form_field_prompt_with_settings';
						}
					?>">
						<div class="sc_igenerator_form_field_inner">
							<input type="text"
								id="sc_igenerator_form_field_prompt_text<?php echo esc_attr( $id_suffix ); ?>"
								class="sc_igenerator_form_field_prompt_text"
								value="<?php echo esc_attr( $args['prompt'] ); ?>"
								placeholder="<?php
									if ( ! empty( $args['placeholder_text'] ) ) {
										echo esc_attr( $args['placeholder_text'] );
									} else {
										esc_attr_e('Describe what you want or hit a tag below', 'trx_addons');
									}
								?>"
							>
							<a href="#" class="sc_igenerator_form_field_prompt_button<?php
								if ( empty( $args['prompt'] ) ) {
									echo ' sc_igenerator_form_field_prompt_button_disabled';
								}
								if ( $styles_allowed ) {
									echo ' sc_button_default';
								}
								echo ! empty( $args['button_image'] ) || ( ! empty( $args['button_icon'] ) && ! trx_addons_is_off( $args['button_icon'] ) )
									? ' sc_igenerator_form_field_prompt_button_with_icon'
									: ' sc_igenerator_form_field_prompt_button_without_icon';
							?>"><?php
								if ( ! empty( $args['button_image'] ) ) {
									$icon_type = trx_addons_get_file_ext( $args['button_image'] );
									if ( $icon_type == 'svg' ) {
										?><span class="sc_igenerator_form_field_prompt_button_svg"><?php
											trx_addons_show_layout( trx_addons_get_svg_from_file( $args['button_image'] ) );
										?></span><?php
									} else {
										?><img src="<?php echo esc_url( trx_addons_get_attachment_url( $args['button_image'], apply_filters('trx_addons_filter_thumb_size', trx_addons_get_thumb_size( 'tiny' ), 'sc_igenerator_field_prompt_button' ) ) ); ?>"
												alt="<?php esc_attr_e( 'Generate icon', 'trx_addons' ); ?>"
												class="sc_igenerator_form_field_prompt_button_image"><?php
									}
								} else if ( ! empty( $args['button_icon'] ) && ! trx_addons_is_off( $args['button_icon'] ) ) {
									?><span class="sc_igenerator_form_field_prompt_button_icon <?php echo esc_attr( $args['button_icon'] ); ?>"></span><?php
								}
								if ( isset( $args['button_text'] ) && $args['button_text'] != '#' ) {
									?><span class="sc_igenerator_form_field_prompt_button_text"><?php
										if ( ! empty( $args['button_text'] ) ) {
											echo esc_html( $args['button_text'] );
										} else {
											esc_html_e( 'Generate', 'trx_addons' );
										}
									?></span><?php
								}
							?></a>
						</div><?php
						if ( ! empty( $args['show_settings'] ) && (int) $args['show_settings'] > 0 ) {
							$settings_mode  = ! empty( $args['show_settings_size'] ) ? 'full' : 'light';
							$settings_image = ! empty( $args['settings_button_image'] ) ? $args['settings_button_image'] : '';
							$settings_icon = ! empty( $settings_image )
												? ''
												: ( ! empty( $args['settings_button_icon'] ) && ! trx_addons_is_off( $args['settings_button_icon'] )
													? $args['settings_button_icon']
													: 'trx_addons_icon-sliders'
													);
							?>
							<a href="#" class="sc_igenerator_form_settings_button <?php echo esc_attr( $settings_icon ) ?>"><?php
								if ( ! empty( $settings_image ) ) {
									$icon_type = trx_addons_get_file_ext( $settings_image );
									if ( $icon_type == 'svg' ) {
										?><span class="sc_igenerator_form_settings_button_svg"><?php
											trx_addons_show_layout( trx_addons_get_svg_from_file( $settings_image ) );
										?></span><?php
									} else {
										?><img src="<?php echo esc_url( trx_addons_get_attachment_url( $settings_image, apply_filters('trx_addons_filter_thumb_size', trx_addons_get_thumb_size( 'tiny' ), 'sc_igenerator_field_settings_button' ) ) ); ?>"
												alt="<?php esc_attr_e( 'Settings icon', 'trx_addons' ); ?>"
												class="sc_igenerator_form_settings_button_image"><?php
									}
								}
							?></a>
							<div class="sc_igenerator_form_settings sc_igenerator_form_settings_<?php echo esc_attr( $settings_mode ); ?>"><?php
								// Settings mode 'full' - visitors can change settings 'size', 'width' and 'height'
								if ( $settings_mode == 'full' ) {
									// Model
									if ( is_array( $models ) ) {
										?><div class="sc_igenerator_form_settings_field sc_igenerator_form_settings_field_model">
											<label for="sc_igenerator_form_settings_field_model<?php echo esc_attr( $id_suffix ); ?>"><?php esc_html_e('Model:', 'trx_addons'); ?></label>
											<select name="sc_igenerator_form_settings_field_model" id="sc_igenerator_form_settings_field_model<?php echo esc_attr( $id_suffix ); ?>"><?php
												$group = false;
												foreach ( $models as $model => $title ) {
													if ( substr( $model, -2 ) == '/-' || substr( $title, 0, 2 ) == '\\-' ) {
														if ( $group ) {
															?></optgroup><?php
														}
														$group = true;
														$title = substr( $title, 2 );
														?><optgroup label="<?php echo esc_attr( $title ); ?>"><?php
													} else {
														?><option value="<?php echo esc_attr( $model ); ?>"<?php
															if ( ! empty( $args['model'] ) && $args['model'] == $model ) {
																echo ' selected="selected"';
															}
														?>><?php
															echo esc_html( $title );
														?></option><?php
													}
												}
												if ( $group ) {
													?></optgroup><?php
												}
											?></select>
										</div><?php
									}
									// Style
									if ( is_array( $styles ) ) {
										?><div class="sc_igenerator_form_settings_field sc_igenerator_form_settings_field_style<?php if ( empty( $args['model'] ) || ! Utils::is_stability_ai_model( $args['model'] ) ) echo ' trx_addons_hidden'; ?>">
											<label for="sc_igenerator_form_settings_field_style<?php echo esc_attr( $id_suffix ); ?>"><?php esc_html_e('Style:', 'trx_addons'); ?></label>
											<select name="sc_igenerator_form_settings_field_style" id="sc_igenerator_form_settings_field_style<?php echo esc_attr( $id_suffix ); ?>"><?php
												foreach ( $styles as $style => $title ) {
													?><option value="<?php echo esc_attr( $style ); ?>"<?php
														if ( ! empty( $args['style'] ) && $args['style'] == $style ) {
															echo ' selected="selected"';
														}
													?>><?php
														echo esc_html( $title );
													?></option><?php
												}
											?></select>
										</div><?php
									}
									// Style for Open AI DALL-E-3 model
									if ( is_array( $styles_openai ) ) {
										?><div class="sc_igenerator_form_settings_field sc_igenerator_form_settings_field_style_openai<?php if ( empty( $args['model'] ) || ! Utils::is_openai_dall_e_3_model( $args['model'] ) ) echo ' trx_addons_hidden'; ?>">
											<label for="sc_igenerator_form_settings_field_style_openai<?php echo esc_attr( $id_suffix ); ?>"><?php esc_html_e('Style:', 'trx_addons'); ?></label>
											<select name="sc_igenerator_form_settings_field_style_openai" id="sc_igenerator_form_settings_field_style_openai<?php echo esc_attr( $id_suffix ); ?>"><?php
												foreach ( $styles_openai as $style => $title ) {
													?><option value="<?php echo esc_attr( $style ); ?>"<?php
														if ( ! empty( $args['style_openai'] ) && $args['style_openai'] == $style ) {
															echo ' selected="selected"';
														}
													?>><?php
														echo esc_html( $title );
													?></option><?php
												}
											?></select>
										</div><?php
									}
									// Size
									if ( is_array( $sizes ) ) {
										?><div class="sc_igenerator_form_settings_field sc_igenerator_form_settings_field_size">
											<label for="sc_igenerator_form_settings_field_size<?php echo esc_attr( $id_suffix ); ?>"><?php esc_html_e('Size (px):', 'trx_addons'); ?></label>
											<select name="sc_igenerator_form_settings_field_size" id="sc_igenerator_form_settings_field_size<?php echo esc_attr( $id_suffix ); ?>"><?php
												foreach ( $sizes as $size => $title ) {
													?><option value="<?php echo esc_attr( $size ); ?>"<?php
														if ( ! empty( $args['size'] ) && $args['size'] == $size ) {
															echo ' selected="selected"';
														}
														if ( ! empty( $args['model'] ) && strpos( $args['model'], 'openai/' ) !== false && ! isset( $openai_sizes[ $size ] ) ) {
															echo ' class="trx_addons_hidden"';
														}
													?>><?php
														echo esc_html( $title );
													?></option><?php
												}
											?></select>
										</div><?php
									}
									// Width (numeric field)
									?><div class="sc_igenerator_form_settings_field sc_igenerator_form_settings_field_width<?php if ( $args['size'] != 'custom' ) echo ' trx_addons_hidden'; ?>">
										<label for="sc_igenerator_form_settings_field_width<?php echo esc_attr( $id_suffix ); ?>"><?php esc_html_e('Width (px):', 'trx_addons'); ?></label>
										<div class="sc_igenerator_form_settings_field_numeric_wrap">
											<input
												type="number"
												name="sc_igenerator_form_settings_field_width"
												id="sc_igenerator_form_settings_field_width<?php echo esc_attr( $id_suffix ); ?>"
												min="0"
												max="<?php echo esc_attr( Utils::get_max_image_width() ); ?>"
												step="8"
												value="<?php echo esc_attr( $args['width'] ); ?>"
											>
											<div class="sc_igenerator_form_settings_field_numeric_wrap_buttons">
												<a href="#" class="sc_igenerator_form_settings_field_numeric_wrap_button sc_igenerator_form_settings_field_numeric_wrap_button_inc"></a>
												<a href="#" class="sc_igenerator_form_settings_field_numeric_wrap_button sc_igenerator_form_settings_field_numeric_wrap_button_dec"></a>
											</div>
										</div>
									</div><?php
									// Height (numeric field)
									?><div class="sc_igenerator_form_settings_field sc_igenerator_form_settings_field_height<?php if ( $args['size'] != 'custom' ) echo ' trx_addons_hidden'; ?>">
										<label for="sc_igenerator_form_settings_field_height<?php echo esc_attr( $id_suffix ); ?>"><?php esc_html_e('Height (px):', 'trx_addons'); ?></label>
										<div class="sc_igenerator_form_settings_field_numeric_wrap">
											<input
												type="number"
												name="sc_igenerator_form_settings_field_height"
												id="sc_igenerator_form_settings_field_height<?php echo esc_attr( $id_suffix ); ?>"
												min="0"
												max="<?php echo esc_attr( Utils::get_max_image_height() ); ?>"
												step="8"
												value="<?php echo esc_attr( $args['height'] ); ?>"
											>
											<div class="sc_igenerator_form_settings_field_numeric_wrap_buttons">
												<a href="#" class="sc_igenerator_form_settings_field_numeric_wrap_button sc_igenerator_form_settings_field_numeric_wrap_button_inc"></a>
												<a href="#" class="sc_igenerator_form_settings_field_numeric_wrap_button sc_igenerator_form_settings_field_numeric_wrap_button_dec"></a>
											</div>
										</div>
									</div><?php

								// Light mode settings
								} else {
									if ( is_array( $models ) ) {
										foreach ( $models as $model => $title ) {
											if ( substr( $model, -2 ) == '/-' || substr( $title, 0, 2 ) == '\\-' ) {
												?><div class="sc_igenerator_form_settings_field sc_igenerator_form_settings_field_group_title"><?php echo esc_html( substr( $title, 2 ) ); ?></div><?php
											} else {
												$id = 'sc_igenerator_form_settings_field_model_' . str_replace( '/', '-', $model ) . $id_suffix;
												?><div class="sc_igenerator_form_settings_field sc_igenerator_form_settings_field_model">
													<input type="radio"
														name="sc_igenerator_form_settings_field_model"
														value="<?php echo esc_attr( $model ); ?>"
														<?php
														if ( ! empty( $args['model'] ) && $args['model'] == $model ) {
															echo ' checked="checked"';
														}
														?>
														id="<?php echo esc_attr( $id ); ?>"
													><label for="<?php echo esc_attr( $id ); ?>"><?php
														echo esc_html( $title );
													?></label><?php
												?></div><?php
											}
										}
									}
								}
							?></div><?php
						}
					?></div><?php
					if ( ! empty( $args['show_negative_prompt'] ) ) {
						?><div class="sc_igenerator_form_field sc_igenerator_form_field_negative_prompt<?php
							if ( empty( $args['model'] ) || ! Utils::is_model_support_negative_prompt( $args['model'] ) ) {
								echo ' trx_addons_hidden';
							}
						?>">
							<div class="sc_igenerator_form_field_inner">
								<label for="sc_igenerator_form_field_negative_prompt_text<?php echo esc_attr( $id_suffix ); ?>"><?php esc_html_e('Negative prompt (optional):', 'trx_addons'); ?></label>
								<input type="text"
									id="sc_igenerator_form_field_negative_prompt_text<?php echo esc_attr( $id_suffix ); ?>"
									class="sc_igenerator_form_field_negative_prompt_text"
									value="<?php echo esc_attr( $args['negative_prompt'] ); ?>"
									placeholder="<?php
										if ( ! empty( $args['negative_placeholder_text'] ) ) {
											echo esc_attr( $args['negative_placeholder_text'] );
										} else {
											esc_attr_e( "Items you don't want in the image", 'trx_addons' );
										}
									?>"
								>
							</div>
						</div><?php
					}
					if ( ! empty( $args['show_upload'] ) ) {
						$decorated = apply_filters( 'trx_addons_filter_sc_igenerator_decorate_upload', true );
						?><div class="sc_igenerator_form_field sc_igenerator_form_field_upload_image">
							<div class="sc_igenerator_form_field_inner">
								<label for="sc_igenerator_form_field_upload_image_field<?php echo esc_attr( $id_suffix ); ?>"><?php esc_html_e('Upload image to make variations (optional):', 'trx_addons'); ?></label><?php
								if ( $decorated ) {
									?>
									<div class="sc_igenerator_form_field_upload_image_decorator theme_form_field_text">
										<span class="sc_igenerator_form_field_upload_image_text theme_form_field_placeholder"><?php esc_html_e( "Image is not selected", 'trx_addons' ); ?></span>
										<span class="sc_igenerator_form_field_upload_image_button trx_addons_icon-upload"><?php esc_html_e( "Browse", 'trx_addons' ); ?></span>
									<?php
								}
								?><input type="file"
									id="sc_igenerator_form_field_upload_image_field<?php echo esc_attr( $id_suffix ); ?>"
									class="sc_igenerator_form_field_upload_image_field"
								><?php
								if ( $decorated ) {
									?></div><?php
								}
							?></div>
						</div><?php
					}
					if ( ! empty( $args['tags'] ) && is_array( $args['tags'] ) && count( $args['tags'] ) > 0 && ! empty( $args['tags'][0]['title'] ) ) {
						?><div class="sc_igenerator_form_field sc_igenerator_form_field_tags"><?php
							if ( ! empty( $args['tags_label'] ) ) {
								?><span class="sc_igenerator_form_field_tags_label"><?php echo esc_html( $args['tags_label'] ); ?></span><?php
							}
							?><span class="sc_igenerator_form_field_tags_list"><?php
								foreach ( $args['tags'] as $tag ) {
									?><a href="#" class="sc_igenerator_form_field_tags_item" data-tag-prompt="<?php echo esc_attr( $tag['prompt'] ); ?>"><?php echo esc_html( $tag['title'] ); ?></a><?php
								}
							?></span><?php
						?></div><?php
					}
				?></div><?php
				
				// Loading placeholder
				trx_addons_loading_layout( array( 'hidden' => true ) );

				if ( ! empty( $args['show_limits'] ) ) {
					$premium = ! empty( $args['premium'] ) && (int)$args['premium'] == 1;
					$suffix = $premium ? '_premium' : '';
					$limits = (int)trx_addons_get_option( "ai_helper_sc_igenerator_limits{$suffix}" ) > 0;
					if ( $limits ) {
						$generated = 0;
						if ( $premium ) {
							$user_id = get_current_user_id();
							$user_level = apply_filters( 'trx_addons_filter_sc_igenerator_user_level', $user_id > 0 ? 'default' : '', $user_id );
							if ( ! empty( $user_level ) ) {
								$levels = trx_addons_get_option( "ai_helper_sc_igenerator_levels_premium" );
								$level_idx = trx_addons_array_search( $levels, 'level', $user_level );
								$user_limit = $level_idx !== false ? $levels[ $level_idx ] : false;
								if ( isset( $user_limit['limit'] ) && trim( $user_limit['limit'] ) !== '' ) {
									$generated = trx_addons_sc_igenerator_get_total_generated( $user_limit['per'], $suffix, $user_id );
								}
							}
						}
						if ( ! $premium || empty( $user_level ) || ! isset( $user_limit['limit'] ) || trim( $user_limit['limit'] ) === '' ) {
							$generated = trx_addons_sc_igenerator_get_total_generated( 'hour', $suffix );
							$user_limit = array(
								'limit' => (int)trx_addons_get_option( "ai_helper_sc_igenerator_limit_per_hour{$suffix}" ),
								'requests' => (int)trx_addons_get_option( "ai_helper_sc_igenerator_limit_per_visitor{$suffix}" ),
								'per' => 'hour'
							);
						}
						if ( isset( $user_limit['limit'] ) && trim( $user_limit['limit'] ) !== '' ) {
							?><div class="sc_igenerator_limits"<?php
								// If a shortcode is called not from Elementor, we need to add the width of the prompt field and alignment
								if ( empty( $args['prompt_width_extra'] ) ) {
									if ( ! empty( $args['prompt_width'] ) && (int)$args['prompt_width'] < 100 ) {
										echo ' style="max-width:' . esc_attr( $args['prompt_width'] ) . '%"';
									}
								}
							?>>
								<span class="sc_igenerator_limits_total"><?php
									$periods = Lists::get_list_periods();
									echo wp_kses( sprintf(
														__( 'Limits%s: %s%s.', 'trx_addons' ),
														! empty( $periods[ $user_limit['per'] ] ) ? ' ' . sprintf( __( 'per %s', 'trx_addons' ), strtolower( $periods[ $user_limit['per'] ] ) ) : '',
														sprintf( __( '%s images', 'trx_addons' ), '<span class="sc_igenerator_limits_total_value">' . (int)$user_limit['limit'] . '</span>' ),
														! empty( $user_limit['requests'] ) ? ' ' . sprintf( __( ' for all visitors and up to %s requests from a single visitor', 'trx_addons' ), '<span class="sc_igenerator_limits_total_requests">' . (int)$user_limit['requests'] . '</span>' ) : '',
													),
													'trx_addons_kses_content'
												);
								?></span>
								<span class="sc_igenerator_limits_used"><?php
									echo wp_kses( sprintf(
														__( 'Used: %s images%s.', 'trx_addons' ),
														'<span class="sc_igenerator_limits_used_value">' . min( $generated, (int)$user_limit['limit'] )  . '</span>',
														! empty( $user_limit['requests'] ) ? sprintf( __( ', %s requests', 'trx_addons' ), '<span class="sc_igenerator_limits_used_requests">' . (int)trx_addons_get_value_gpc( 'trx_addons_ai_helper_igenerator_count' ) . '</span>' ) : '',
													),
													'trx_addons_kses_content'
												);
								?></span>
							</div><?php
						}
					}
				}
				?><div class="sc_igenerator_message"<?php
					// If a shortcode is called not from Elementor, we need to add the width of the prompt field and alignment
					if ( empty( $args['prompt_width_extra'] ) ) {
						if ( ! empty( $args['prompt_width'] ) && (int)$args['prompt_width'] < 100 ) {
							echo ' style="max-width:' . esc_attr( $args['prompt_width'] ) . '%"';
						}
					}
				?>>
					<div class="sc_igenerator_message_inner"></div>
					<a href="#" class="sc_igenerator_message_close trx_addons_button_close" title="<?php esc_html_e( 'Close', 'trx_addons' ); ?>"><span class="trx_addons_button_close_icon"></span></a>
				</div>
			</div>
			<div class="sc_igenerator_images sc_igenerator_images_columns_<?php echo esc_attr( $args['columns'] ); ?> sc_igenerator_images_size_<?php echo esc_attr( $args['size'] ); ?>"></div>
		</div>

		<?php trx_addons_sc_show_links('sc_igenerator', $args); ?>

	</div><?php

} else if ( true || trx_addons_is_preview() ) {

	?><div class="sc_igenerator_error"><?php
		esc_html_e( 'Image Generator: No models available', 'trx_addons' );
	?></div><?php

}