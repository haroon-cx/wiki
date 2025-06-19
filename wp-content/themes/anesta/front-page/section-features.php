<div class="front_page_section front_page_section_features<?php
	$anesta_scheme = anesta_get_theme_option( 'front_page_features_scheme' );
	if ( ! empty( $anesta_scheme ) && ! anesta_is_inherit( $anesta_scheme ) ) {
		echo ' scheme_' . esc_attr( $anesta_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( anesta_get_theme_option( 'front_page_features_paddings' ) );
	if ( anesta_get_theme_option( 'front_page_features_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$anesta_css      = '';
		$anesta_bg_image = anesta_get_theme_option( 'front_page_features_bg_image' );
		if ( ! empty( $anesta_bg_image ) ) {
			$anesta_css .= 'background-image: url(' . esc_url( anesta_get_attachment_url( $anesta_bg_image ) ) . ');';
		}
		if ( ! empty( $anesta_css ) ) {
			echo ' style="' . esc_attr( $anesta_css ) . '"';
		}
		?>
>
<?php
	// Add anchor
	$anesta_anchor_icon = anesta_get_theme_option( 'front_page_features_anchor_icon' );
	$anesta_anchor_text = anesta_get_theme_option( 'front_page_features_anchor_text' );
if ( ( ! empty( $anesta_anchor_icon ) || ! empty( $anesta_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_features"'
									. ( ! empty( $anesta_anchor_icon ) ? ' icon="' . esc_attr( $anesta_anchor_icon ) . '"' : '' )
									. ( ! empty( $anesta_anchor_text ) ? ' title="' . esc_attr( $anesta_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_features_inner
	<?php
	if ( anesta_get_theme_option( 'front_page_features_fullheight' ) ) {
		echo ' anesta-full-height sc_layouts_flex sc_layouts_columns_middle';
	}
	?>
			"
			<?php
			$anesta_css      = '';
			$anesta_bg_mask  = anesta_get_theme_option( 'front_page_features_bg_mask' );
			$anesta_bg_color_type = anesta_get_theme_option( 'front_page_features_bg_color_type' );
			if ( 'custom' == $anesta_bg_color_type ) {
				$anesta_bg_color = anesta_get_theme_option( 'front_page_features_bg_color' );
			} elseif ( 'scheme_bg_color' == $anesta_bg_color_type ) {
				$anesta_bg_color = anesta_get_scheme_color( 'bg_color', $anesta_scheme );
			} else {
				$anesta_bg_color = '';
			}
			if ( ! empty( $anesta_bg_color ) && $anesta_bg_mask > 0 ) {
				$anesta_css .= 'background-color: ' . esc_attr(
					1 == $anesta_bg_mask ? $anesta_bg_color : anesta_hex2rgba( $anesta_bg_color, $anesta_bg_mask )
				) . ';';
			}
			if ( ! empty( $anesta_css ) ) {
				echo ' style="' . esc_attr( $anesta_css ) . '"';
			}
			?>
	>
		<div class="front_page_section_content_wrap front_page_section_features_content_wrap content_wrap">
			<?php
			// Caption
			$anesta_caption = anesta_get_theme_option( 'front_page_features_caption' );
			if ( ! empty( $anesta_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<h2 class="front_page_section_caption front_page_section_features_caption front_page_block_<?php echo ! empty( $anesta_caption ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( $anesta_caption, 'anesta_kses_content' ); ?></h2>
				<?php
			}

			// Description (text)
			$anesta_description = anesta_get_theme_option( 'front_page_features_description' );
			if ( ! empty( $anesta_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_description front_page_section_features_description front_page_block_<?php echo ! empty( $anesta_description ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( wpautop( $anesta_description ), 'anesta_kses_content' ); ?></div>
				<?php
			}

			// Content (widgets)
			?>
			<div class="front_page_section_output front_page_section_features_output">
				<?php
				if ( is_active_sidebar( 'front_page_features_widgets' ) ) {
					dynamic_sidebar( 'front_page_features_widgets' );
				} elseif ( current_user_can( 'edit_theme_options' ) ) {
					if ( ! anesta_exists_trx_addons() ) {
						anesta_customizer_need_trx_addons_message();
					} else {
						anesta_customizer_need_widgets_message( 'front_page_features_caption', 'ThemeREX Addons - Services' );
					}
				}
				?>
			</div>
		</div>
	</div>
</div>
