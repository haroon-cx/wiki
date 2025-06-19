<?php
/**
 * The template to display the user's avatar, bio and socials on the Author page
 *
 * @package ANESTA
 * @since ANESTA 1.71.0
 */
?>

<div class="author_page author vcard" itemprop="author" itemscope="itemscope" itemtype="<?php echo esc_attr( anesta_get_protocol( true ) ); ?>//schema.org/Person">

	<div class="author_avatar" itemprop="image">
		<?php
		$anesta_mult = anesta_get_retina_multiplier();
		echo get_avatar( get_the_author_meta( 'user_email' ), 120 * $anesta_mult );
		?>
	</div>

	<h4 class="author_title" itemprop="name"><span class="fn"><?php the_author(); ?></span></h4>

	<?php
	$anesta_author_description = get_the_author_meta( 'description' );
	if ( ! empty( $anesta_author_description ) ) {
		?>
		<div class="author_bio" itemprop="description"><?php echo wp_kses( wpautop( $anesta_author_description ), 'anesta_kses_content' ); ?></div>
		<?php
	}
	?>

	<div class="author_details">
		<span class="author_posts_total">
			<?php
			$anesta_posts_total = count_user_posts( get_the_author_meta('ID'), 'post' );	// get_the_author_posts() return posts number by post_type from first post in the result
			if ( $anesta_posts_total > 0 ) {
				// Translators: Add the author's posts number to the message
				echo wp_kses( sprintf( _n( '%s article published', '%s articles published', $anesta_posts_total, 'anesta' ),
										'<span class="author_posts_total_value">' . number_format_i18n( $anesta_posts_total ) . '</span>'
								 		),
							'anesta_kses_content'
							);
			} else {
				esc_html_e( 'No posts published.', 'anesta' );
			}
			?>
		</span><?php
			ob_start();
			do_action( 'anesta_action_user_meta', 'author-page' );
			$anesta_socials = ob_get_contents();
			ob_end_clean();
			anesta_show_layout( $anesta_socials,
				'<span class="author_socials"><span class="author_socials_caption">' . esc_html__( 'Follow:', 'anesta' ) . '</span>',
				'</span>'
			);
		?>
	</div>

</div>
