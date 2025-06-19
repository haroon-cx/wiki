<?php
/**
 * The template to display Admin notices
 *
 * @package ANESTA
 * @since ANESTA 1.0.64
 */

$anesta_skins_url  = get_admin_url( null, 'admin.php?page=trx_addons_theme_panel#trx_addons_theme_panel_section_skins' );
$anesta_skins_args = get_query_var( 'anesta_skins_notice_args' );
?>
<div class="anesta_admin_notice anesta_skins_notice notice notice-info is-dismissible" data-notice="skins">
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
		<?php esc_html_e( 'New skins available', 'anesta' ); ?>
	</h3>
	<?php

	// Description
	$anesta_total      = $anesta_skins_args['update'];	// Store value to the separate variable to avoid warnings from ThemeCheck plugin!
	$anesta_skins_msg  = $anesta_total > 0
							// Translators: Add new skins number
							? '<strong>' . sprintf( _n( '%d new version', '%d new versions', $anesta_total, 'anesta' ), $anesta_total ) . '</strong>'
							: '';
	$anesta_total      = $anesta_skins_args['free'];
	$anesta_skins_msg .= $anesta_total > 0
							? ( ! empty( $anesta_skins_msg ) ? ' ' . esc_html__( 'and', 'anesta' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d free skin', '%d free skins', $anesta_total, 'anesta' ), $anesta_total ) . '</strong>'
							: '';
	$anesta_total      = $anesta_skins_args['pay'];
	$anesta_skins_msg .= $anesta_skins_args['pay'] > 0
							? ( ! empty( $anesta_skins_msg ) ? ' ' . esc_html__( 'and', 'anesta' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d paid skin', '%d paid skins', $anesta_total, 'anesta' ), $anesta_total ) . '</strong>'
							: '';
	?>
	<div class="anesta_notice_text">
		<p>
			<?php
			// Translators: Add new skins info
			echo wp_kses_data( sprintf( __( "We are pleased to announce that %s are available for your theme", 'anesta' ), $anesta_skins_msg ) );
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
