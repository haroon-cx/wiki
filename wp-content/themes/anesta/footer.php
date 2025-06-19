<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package ANESTA
 * @since ANESTA 1.0
 */

							do_action( 'anesta_action_page_content_end_text' );
							
							// Widgets area below the content
							anesta_create_widgets_area( 'widgets_below_content' );
						
							do_action( 'anesta_action_page_content_end' );
							?>
						</div>
						<?php
						
						do_action( 'anesta_action_after_page_content' );

						// Show main sidebar
						get_sidebar();

						do_action( 'anesta_action_content_wrap_end' );
						?>
					</div>
					<?php

					do_action( 'anesta_action_after_content_wrap' );

					// Widgets area below the page and related posts below the page
					$anesta_body_style = anesta_get_theme_option( 'body_style' );
					$anesta_widgets_name = anesta_get_theme_option( 'widgets_below_page' );
					$anesta_show_widgets = ! anesta_is_off( $anesta_widgets_name ) && is_active_sidebar( $anesta_widgets_name );
					$anesta_show_related = anesta_is_single() && anesta_get_theme_option( 'related_position' ) == 'below_page';
					if ( $anesta_show_widgets || $anesta_show_related ) {
						if ( 'fullscreen' != $anesta_body_style ) {
							?>
							<div class="content_wrap">
							<?php
						}
						// Show related posts before footer
						if ( $anesta_show_related ) {
							do_action( 'anesta_action_related_posts' );
						}

						// Widgets area below page content
						if ( $anesta_show_widgets ) {
							anesta_create_widgets_area( 'widgets_below_page' );
						}
						if ( 'fullscreen' != $anesta_body_style ) {
							?>
							</div>
							<?php
						}
					}
					do_action( 'anesta_action_page_content_wrap_end' );
					?>
			</div>
			<?php
			do_action( 'anesta_action_after_page_content_wrap' );

			// Don't display the footer elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ( ! anesta_is_singular( 'post' ) && ! anesta_is_singular( 'attachment' ) ) || ! in_array ( anesta_get_value_gp( 'action' ), array( 'full_post_loading', 'prev_post_loading' ) ) ) {
				
				// Skip link anchor to fast access to the footer from keyboard
				?>
				<a id="footer_skip_link_anchor" class="anesta_skip_link_anchor" href="#"></a>
				<?php

				do_action( 'anesta_action_before_footer' );

				// Footer
				$anesta_footer_type = anesta_get_theme_option( 'footer_type' );
				if ( 'custom' == $anesta_footer_type && ! anesta_is_layouts_available() ) {
					$anesta_footer_type = 'default';
				}
				get_template_part( apply_filters( 'anesta_filter_get_template_part', "templates/footer-" . sanitize_file_name( $anesta_footer_type ) ) );

				do_action( 'anesta_action_after_footer' );

			}
			?>

			<?php do_action( 'anesta_action_page_wrap_end' ); ?>

		</div>

		<?php do_action( 'anesta_action_after_page_wrap' ); ?>

	</div>

	<?php do_action( 'anesta_action_after_body' ); ?>

	<?php wp_footer(); ?>

</body>
      
</html>