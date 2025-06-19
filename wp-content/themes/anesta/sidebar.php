<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package ANESTA
 * @since ANESTA 1.0
 */

if ( anesta_sidebar_present() ) {
	
	$anesta_sidebar_type = anesta_get_theme_option( 'sidebar_type' );
	if ( 'custom' == $anesta_sidebar_type && ! anesta_is_layouts_available() ) {
		$anesta_sidebar_type = 'default';
	}
	
	// Catch output to the buffer
	ob_start();
	if ( 'default' == $anesta_sidebar_type ) {
		// Default sidebar with widgets
		$anesta_sidebar_name = anesta_get_theme_option( 'sidebar_widgets' );
		anesta_storage_set( 'current_sidebar', 'sidebar' );
		if ( is_active_sidebar( $anesta_sidebar_name ) ) {
			dynamic_sidebar( $anesta_sidebar_name );
		}
	} else {
		// Custom sidebar from Layouts Builder
		$anesta_sidebar_id = anesta_get_custom_sidebar_id();
		do_action( 'anesta_action_show_layout', $anesta_sidebar_id );
	}
	$anesta_out = trim( ob_get_contents() );
	ob_end_clean();
	
	// If any html is present - display it
	if ( ! empty( $anesta_out ) ) {
		$anesta_sidebar_position    = anesta_get_theme_option( 'sidebar_position' );
		$anesta_sidebar_position_ss = anesta_get_theme_option( 'sidebar_position_ss' );
		?>
		<div class="sidebar widget_area
			<?php
			echo ' ' . esc_attr( $anesta_sidebar_position );
			echo ' sidebar_' . esc_attr( $anesta_sidebar_position_ss );
			echo ' sidebar_' . esc_attr( $anesta_sidebar_type );

			$anesta_sidebar_scheme = apply_filters( 'anesta_filter_sidebar_scheme', anesta_get_theme_option( 'sidebar_scheme' ) );
			if ( ! empty( $anesta_sidebar_scheme ) && ! anesta_is_inherit( $anesta_sidebar_scheme ) && 'custom' != $anesta_sidebar_type ) {
				echo ' scheme_' . esc_attr( $anesta_sidebar_scheme );
			}
			?>
		" role="complementary">
			<?php

			// Skip link anchor to fast access to the sidebar from keyboard
			?>
			<a id="sidebar_skip_link_anchor" class="anesta_skip_link_anchor" href="#"></a>
			<?php

			do_action( 'anesta_action_before_sidebar_wrap', 'sidebar' );

			// Button to show/hide sidebar on mobile
			if ( in_array( $anesta_sidebar_position_ss, array( 'above', 'float' ) ) ) {
				$anesta_title = apply_filters( 'anesta_filter_sidebar_control_title', 'float' == $anesta_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'anesta' ) : '' );
				$anesta_text  = apply_filters( 'anesta_filter_sidebar_control_text', 'above' == $anesta_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'anesta' ) : '' );
				?>
				<a href="#" class="sidebar_control" title="<?php echo esc_attr( $anesta_title ); ?>"><?php echo esc_html( $anesta_text ); ?></a>
				<?php
			}
			?>
			<div class="sidebar_inner">
				<?php
				do_action( 'anesta_action_before_sidebar', 'sidebar' );
				anesta_show_layout( preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $anesta_out ) );
				do_action( 'anesta_action_after_sidebar', 'sidebar' );
				?>
			</div>
			<?php

			do_action( 'anesta_action_after_sidebar_wrap', 'sidebar' );

			?>
		</div>
		<div class="clearfix"></div>
		<?php
	}
}
