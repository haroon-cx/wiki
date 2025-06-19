<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package ANESTA
 * @since ANESTA 1.0.10
 */

// Footer sidebar
$anesta_footer_name    = anesta_get_theme_option( 'footer_widgets' );
$anesta_footer_present = ! anesta_is_off( $anesta_footer_name ) && is_active_sidebar( $anesta_footer_name );
if ( $anesta_footer_present ) {
	anesta_storage_set( 'current_sidebar', 'footer' );
	$anesta_footer_wide = anesta_get_theme_option( 'footer_wide' );
	ob_start();
	if ( is_active_sidebar( $anesta_footer_name ) ) {
		dynamic_sidebar( $anesta_footer_name );
	}
	$anesta_out = trim( ob_get_contents() );
	ob_end_clean();
	if ( ! empty( $anesta_out ) ) {
		$anesta_out          = preg_replace( "/<\\/aside>[\r\n\s]*<aside/", '</aside><aside', $anesta_out );
		$anesta_need_columns = true;   //or check: strpos($anesta_out, 'columns_wrap')===false;
		if ( $anesta_need_columns ) {
			$anesta_columns = max( 0, (int) anesta_get_theme_option( 'footer_columns' ) );			
			if ( 0 == $anesta_columns ) {
				$anesta_columns = min( 4, max( 1, anesta_tags_count( $anesta_out, 'aside' ) ) );
			}
			if ( $anesta_columns > 1 ) {
				$anesta_out = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $anesta_columns ) . ' widget', $anesta_out );
			} else {
				$anesta_need_columns = false;
			}
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo ! empty( $anesta_footer_wide ) ? ' footer_fullwidth' : ''; ?> sc_layouts_row sc_layouts_row_type_normal">
			<?php do_action( 'anesta_action_before_sidebar_wrap', 'footer' ); ?>
			<div class="footer_widgets_inner widget_area_inner">
				<?php
				if ( ! $anesta_footer_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $anesta_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'anesta_action_before_sidebar', 'footer' );
				anesta_show_layout( $anesta_out );
				do_action( 'anesta_action_after_sidebar', 'footer' );
				if ( $anesta_need_columns ) {
					?>
					</div>
					<?php
				}
				if ( ! $anesta_footer_wide ) {
					?>
					</div>
					<?php
				}
				?>
			</div>
			<?php do_action( 'anesta_action_after_sidebar_wrap', 'footer' ); ?>
		</div>
		<?php
	}
}
