<?php
/**
 * The template to display menu in the footer
 *
 * @package ANESTA
 * @since ANESTA 1.0.10
 */

// Footer menu
$anesta_menu_footer = anesta_get_nav_menu( 'menu_footer' );
if ( ! empty( $anesta_menu_footer ) ) {
	?>
	<div class="footer_menu_wrap">
		<div class="footer_menu_inner">
			<?php
			anesta_show_layout(
				$anesta_menu_footer,
				'<nav class="menu_footer_nav_area sc_layouts_menu sc_layouts_menu_default"'
					. ' itemscope="itemscope" itemtype="' . esc_attr( anesta_get_protocol( true ) ) . '//schema.org/SiteNavigationElement"'
					. '>',
				'</nav>'
			);
			?>
		</div>
	</div>
	<?php
}
