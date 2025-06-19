<article <?php post_class( 'post_item_single post_item_404' ); ?>>
	<div class="post_content">
		<h1 class="page_title"><?php esc_html_e( '404', 'anesta' ); ?></h1>
		<div class="page_info">
			<h1 class="page_subtitle"><?php esc_html_e( 'Page Not Found', 'anesta' ); ?></h1>
			<p class="page_description">
				<?php echo wp_kses( __( "It looks like nothing was found at this location.", 'anesta' ), 'anesta_kses_content' ); ?>
				<br>
				<?php echo wp_kses( __( "Maybe try a search below?", 'anesta' ), 'anesta_kses_content' ); ?>
			</p>
			<!--<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="go_home theme_button"><?php esc_html_e( 'Homepage', 'anesta' ); ?></a>-->
			<?php 
			ob_start();
			do_action(
				'anesta_action_search',
				array(
					'style' => 'normal',
					'class' => '',
					'ajax'  => false
				)
			);
			$anesta_action_output = ob_get_contents();
			ob_end_clean();
			if ( ! empty( $anesta_action_output ) ) {
				anesta_show_layout( $anesta_action_output );
			}
			?>
		</div>
	</div>
</article>
