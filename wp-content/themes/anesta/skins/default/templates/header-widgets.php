<?php
/**
 * The template to display the widgets area in the header
 *
 * @package ANESTA
 * @since ANESTA 1.0
 */

// Header sidebar
$anesta_header_name    = anesta_get_theme_option( 'header_widgets' );
$anesta_header_present = ! anesta_is_off( $anesta_header_name ) && is_active_sidebar( $anesta_header_name );
if ( $anesta_header_present ) {
	anesta_storage_set( 'current_sidebar', 'header' );
	$anesta_header_wide = anesta_get_theme_option( 'header_wide' );
	ob_start();
	if ( is_active_sidebar( $anesta_header_name ) ) {
		dynamic_sidebar( $anesta_header_name );
	}
	$anesta_widgets_output = ob_get_contents();
	ob_end_clean();
	if ( ! empty( $anesta_widgets_output ) ) {
		$anesta_widgets_output = preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $anesta_widgets_output );
		$anesta_need_columns   = strpos( $anesta_widgets_output, 'columns_wrap' ) === false;
		if ( $anesta_need_columns ) {
			$anesta_columns = max( 0, (int) anesta_get_theme_option( 'header_columns' ) );
			if ( 0 == $anesta_columns ) {
				$anesta_columns = min( 6, max( 1, anesta_tags_count( $anesta_widgets_output, 'aside' ) ) );
			}
			if ( $anesta_columns > 1 ) {
				$anesta_widgets_output = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $anesta_columns ) . ' widget', $anesta_widgets_output );
			} else {
				$anesta_need_columns = false;
			}
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo ! empty( $anesta_header_wide ) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<?php do_action( 'anesta_action_before_sidebar_wrap', 'header' ); ?>
			<div class="header_widgets_inner widget_area_inner">
				<?php
				if ( ! $anesta_header_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $anesta_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'anesta_action_before_sidebar', 'header' );
				anesta_show_layout( $anesta_widgets_output );
				do_action( 'anesta_action_after_sidebar', 'header' );
				if ( $anesta_need_columns ) {
					?>
					</div>
					<?php
				}
				if ( ! $anesta_header_wide ) {
					?>
					</div>
					<?php
				}
				?>
			</div>
			<?php do_action( 'anesta_action_after_sidebar_wrap', 'header' ); ?>
		</div>
		<?php
	}
}
