<?php
/**
 * The template to display the Author bio
 *
 * @package ANESTA
 * @since ANESTA 1.0
 */
?>

<div class="author_info author vcard" itemprop="author" itemscope="itemscope" itemtype="<?php echo esc_attr( anesta_get_protocol( true ) ); ?>//schema.org/Person">

	<div class="author_avatar" itemprop="image">
		<?php
		$anesta_mult = anesta_get_retina_multiplier();
		echo get_avatar( get_the_author_meta( 'user_email' ), 120 * $anesta_mult );
		?>
	</div>

	<div class="author_description">
		<div class="author_label"><?php esc_html_e( 'Written by', 'anesta' ); ?></div>
		<h5 class="author_title" itemprop="name"><a class="author_link fn" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><?php
			the_author();
		?></a></h5>
		<div class="author_bio" itemprop="description">
			<?php echo wp_kses( wpautop( get_the_author_meta( 'description' ) ), 'anesta_kses_content' ); ?>
			<div class="author_links">
				<?php do_action( 'anesta_action_user_meta', 'author-bio' ); ?>
			</div>
		</div>

	</div>

</div>
