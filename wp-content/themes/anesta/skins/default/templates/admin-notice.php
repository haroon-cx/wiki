<?php
/**
 * The template to display Admin notices
 *
 * @package ANESTA
 * @since ANESTA 1.0.1
 */

$anesta_theme_slug = get_template();
$anesta_theme_obj  = wp_get_theme( $anesta_theme_slug );
?>
<div class="anesta_admin_notice anesta_welcome_notice notice notice-info is-dismissible" data-notice="admin">
	<?php
	// Theme image
	$anesta_theme_img = anesta_get_file_url( 'screenshot.jpg' );
	if ( '' != $anesta_theme_img ) {
		?>
		<div class="anesta_notice_image"><img src="<?php echo esc_url( $anesta_theme_img ); ?>" alt="<?php esc_attr_e( 'Theme screenshot', 'anesta' ); ?>"></div>
		<?php
	}

	// Title
	?>
	<h3 class="anesta_notice_title">
		<?php
		echo esc_html(
			sprintf(
				// Translators: Add theme name and version to the 'Welcome' message
				__( 'Welcome to %1$s v.%2$s', 'anesta' ),
				$anesta_theme_obj->get( 'Name' ) . ( ANESTA_THEME_FREE ? ' ' . __( 'Free', 'anesta' ) : '' ),
				$anesta_theme_obj->get( 'Version' )
			)
		);
		?>
	</h3>
	<?php

	// Description
	?>
	<div class="anesta_notice_text">
		<p class="anesta_notice_text_description">
			<?php
			echo str_replace( '. ', '.<br>', wp_kses_data( $anesta_theme_obj->description ) );
			?>
		</p>
		<p class="anesta_notice_text_info">
			<?php
			echo wp_kses_data( __( 'Attention! Plugin "ThemeREX Addons" is required! Please, install and activate it!', 'anesta' ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="anesta_notice_buttons">
		<?php
		// Link to the page 'About Theme'
		?>
		<a href="<?php echo esc_url( admin_url() . 'themes.php?page=anesta_about' ); ?>" class="button button-primary"><i class="dashicons dashicons-nametag"></i> 
			<?php
			echo esc_html__( 'Install plugin "ThemeREX Addons"', 'anesta' );
			?>
		</a>
	</div>
</div>
