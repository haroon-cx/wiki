<?php
/**
 * The template to show mobile menu
 *
 * @package ANESTA
 * @since ANESTA 1.0
 */
?>
<div class="menu_mobile menu_mobile_<?php echo esc_attr( anesta_get_theme_option( 'menu_mobile_fullscreen' ) > 0 ? 'fullscreen' : 'narrow' );
echo esc_attr( in_array( anesta_get_theme_option( 'menu_side' ), array( 'left', 'right' ) ) && anesta_get_theme_option( 'menu_side_open' ) > 0 ? ' is_opened' : '' );
$anesta_menu_scheme = anesta_get_theme_option( 'menu_scheme' );
$anesta_header_scheme = anesta_get_theme_option( 'header_scheme' );
if ( ! empty( $anesta_menu_scheme ) && ! anesta_is_inherit( $anesta_menu_scheme  ) ) {
	echo ' scheme_' . esc_attr( $anesta_menu_scheme );
} elseif ( ! empty( $anesta_header_scheme ) && ! anesta_is_inherit( $anesta_header_scheme ) ) {
	echo ' scheme_' . esc_attr( $anesta_header_scheme );
}
?>">
	<div class="menu_mobile_inner">
		<div class="menu_mobile_top_panel">
			<a class="menu_mobile_close theme_button_close" tabindex="0"><span class="theme_button_close_icon"></span></a>
			<?php

			// Logo
			set_query_var( 'anesta_logo_args', array( 'type' => 'side' ) );
			get_template_part( apply_filters( 'anesta_filter_get_template_part', 'templates/header-logo' ) );
			set_query_var( 'anesta_logo_args', array() ); 
		?></div><?php

		// Mobile menu
		$anesta_menu_mobile = anesta_get_nav_menu( 'menu_mobile' );
		if ( empty( $anesta_menu_mobile ) ) {
			$anesta_menu_mobile = apply_filters( 'anesta_filter_get_mobile_menu', '' );
			if ( empty( $anesta_menu_mobile ) ) {
				$anesta_menu_mobile = anesta_get_nav_menu( 'menu_main' );
				if ( empty( $anesta_menu_mobile ) ) {
					$anesta_menu_mobile = anesta_get_nav_menu();
				}
			}
		}
		if ( ! empty( $anesta_menu_mobile ) ) {
			// Change attribute 'id' - add prefix 'mobile-' to prevent duplicate id on the page
			$anesta_menu_mobile = preg_replace( '/([\s]*id=")/', '${1}mobile-', $anesta_menu_mobile );
			// Change main menu classes
			$anesta_menu_mobile = str_replace(
				array( 'menu_main',   'sc_layouts_menu_nav', 'sc_layouts_menu ' ),	// , 'sc_layouts_hide_on_mobile', 'hide_on_mobile'
				array( 'menu_mobile', '',                    ' ' ),					// , '',                          ''
				$anesta_menu_mobile
			);
			// Wrap menu to the <nav> if not present
			if ( strpos( $anesta_menu_mobile, '<nav ' ) !== 0 ) {	// condition !== false is not allowed, because menu can contain inner <nav> elements (in the submenu layouts)
				$anesta_menu_mobile = sprintf( '<nav class="menu_mobile_nav_area" itemscope="itemscope" itemtype="%1$s//schema.org/SiteNavigationElement">%2$s</nav>', esc_attr( anesta_get_protocol( true ) ), $anesta_menu_mobile );
			}
			// Show menu
			anesta_show_layout( apply_filters( 'anesta_filter_menu_mobile_layout', $anesta_menu_mobile ) );
		}

		// Search field
		if ( anesta_get_theme_option( 'menu_mobile_search' ) > 0 ) {
			do_action(
				'anesta_action_search',
				array(
					'style' => 'normal',
					'class' => 'search_mobile',
					'ajax'  => false
				)
			);
		}

		// Social icons
		if ( anesta_get_theme_option( 'menu_mobile_socials' ) > 0 ) {
			anesta_show_layout( anesta_get_socials_links(), '<div class="socials_mobile">', '</div>' );
		}
		?>
	</div>
</div>
