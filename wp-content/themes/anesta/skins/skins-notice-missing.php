<?php
/**
 * The template to display Admin notices
 *
 * @package ANESTA
 * @since ANESTA 1.98.0
 */

$anesta_skins_url   = get_admin_url( null, 'admin.php?page=trx_addons_theme_panel#trx_addons_theme_panel_section_skins' );
$anesta_active_skin = anesta_skins_get_active_skin_name();
?>
<div class="anesta_admin_notice anesta_skins_notice notice notice-error">
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
		<?php esc_html_e( 'Active skin is missing!', 'anesta' ); ?>
	</h3>
	<div class="anesta_notice_text">
		<p>
			<?php
			// Translators: Add a current skin name to the message
			echo wp_kses_data( sprintf( __( "Your active skin <b>'%s'</b> is missing. Usually this happens when the theme is updated directly through the server or FTP.", 'anesta' ), ucfirst( $anesta_active_skin ) ) );
			?>
		</p>
		<p>
			<?php
			echo wp_kses_data( __( "Please use only <b>'ThemeREX Updater v.1.6.0+'</b> plugin for your future updates.", 'anesta' ) );
			?>
		</p>
		<p>
			<?php
			echo wp_kses_data( __( "But no worries! You can re-download the skin via 'Skins Manager' ( Theme Panel - Theme Dashboard - Skins ).", 'anesta' ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="anesta_notice_buttons">
		<?php
		// Link to the theme dashboard page
		?>
		<a href="<?php echo esc_url( $anesta_skins_url ); ?>" class="button button-primary"><i class="dashicons dashicons-update"></i> 
			<?php
			// Translators: Add theme name
			esc_html_e( 'Go to Skins manager', 'anesta' );
			?>
		</a>
	</div>
</div>
