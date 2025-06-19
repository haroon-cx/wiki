<?php
/**
 * The Header: Logo and main menu
 *
 * @package ANESTA
 * @since ANESTA 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js<?php
	// Class scheme_xxx need in the <html> as context for the <body>!
	echo ' scheme_' . esc_attr( anesta_get_theme_option( 'color_scheme' ) );
?>">

<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	} else {
		do_action( 'wp_body_open' );
	}
	do_action( 'anesta_action_before_body' );
	?>

	<div class="<?php echo esc_attr( apply_filters( 'anesta_filter_body_wrap_class', 'body_wrap' ) ); ?>" <?php do_action('anesta_action_body_wrap_attributes'); ?>>

		<?php do_action( 'anesta_action_before_page_wrap' ); ?>

		<div class="<?php echo esc_attr( apply_filters( 'anesta_filter_page_wrap_class', 'page_wrap' ) ); ?>" <?php do_action('anesta_action_page_wrap_attributes'); ?>>

			<?php do_action( 'anesta_action_page_wrap_start' ); ?>

			<?php
			$anesta_full_post_loading = ( anesta_is_singular( 'post' ) || anesta_is_singular( 'attachment' ) ) && anesta_get_value_gp( 'action' ) == 'full_post_loading';
			$anesta_prev_post_loading = ( anesta_is_singular( 'post' ) || anesta_is_singular( 'attachment' ) ) && anesta_get_value_gp( 'action' ) == 'prev_post_loading';

			// Don't display the header elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ! $anesta_full_post_loading && ! $anesta_prev_post_loading ) {

				// Short links to fast access to the content, sidebar and footer from the keyboard
				?>
				<a class="anesta_skip_link skip_to_content_link" href="#content_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'anesta_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to content", 'anesta' ); ?></a>
				<?php if ( anesta_sidebar_present() ) { ?>
				<a class="anesta_skip_link skip_to_sidebar_link" href="#sidebar_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'anesta_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to sidebar", 'anesta' ); ?></a>
				<?php } ?>
				<a class="anesta_skip_link skip_to_footer_link" href="#footer_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'anesta_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to footer", 'anesta' ); ?></a>

				<?php
				do_action( 'anesta_action_before_header' );

				// Header
				$anesta_header_type = anesta_get_theme_option( 'header_type' );
				if ( 'custom' == $anesta_header_type && ! anesta_is_layouts_available() ) {
					$anesta_header_type = 'default';
				}
				get_template_part( apply_filters( 'anesta_filter_get_template_part', "templates/header-" . sanitize_file_name( $anesta_header_type ) ) );

				// Side menu
				if ( in_array( anesta_get_theme_option( 'menu_side' ), array( 'left', 'right' ) ) ) {
					get_template_part( apply_filters( 'anesta_filter_get_template_part', 'templates/header-navi-side' ) );
				}

				// Mobile menu
				get_template_part( apply_filters( 'anesta_filter_get_template_part', 'templates/header-navi-mobile' ) );

				do_action( 'anesta_action_after_header' );

			}
			?>

			<?php do_action( 'anesta_action_before_page_content_wrap' ); ?>

			<div class="page_content_wrap<?php
				if ( anesta_is_off( anesta_get_theme_option( 'remove_margins' ) ) ) {
					if ( empty( $anesta_header_type ) ) {
						$anesta_header_type = anesta_get_theme_option( 'header_type' );
					}
					if ( 'custom' == $anesta_header_type && anesta_is_layouts_available() ) {
						$anesta_header_id = anesta_get_custom_header_id();
						if ( $anesta_header_id > 0 ) {
							$anesta_header_meta = anesta_get_custom_layout_meta( $anesta_header_id );
							if ( ! empty( $anesta_header_meta['margin'] ) ) {
								?> page_content_wrap_custom_header_margin<?php
							}
						}
					}
					$anesta_footer_type = anesta_get_theme_option( 'footer_type' );
					if ( 'custom' == $anesta_footer_type && anesta_is_layouts_available() ) {
						$anesta_footer_id = anesta_get_custom_footer_id();
						if ( $anesta_footer_id ) {
							$anesta_footer_meta = anesta_get_custom_layout_meta( $anesta_footer_id );
							if ( ! empty( $anesta_footer_meta['margin'] ) ) {
								?> page_content_wrap_custom_footer_margin<?php
							}
						}
					}
				}
				do_action( 'anesta_action_page_content_wrap_class', $anesta_prev_post_loading );
				?>"<?php
				if ( apply_filters( 'anesta_filter_is_prev_post_loading', $anesta_prev_post_loading ) ) {
					?> data-single-style="<?php echo esc_attr( anesta_get_theme_option( 'single_style' ) ); ?>"<?php
				}
				do_action( 'anesta_action_page_content_wrap_data', $anesta_prev_post_loading );
			?>>
				<?php
				do_action( 'anesta_action_page_content_wrap', $anesta_full_post_loading || $anesta_prev_post_loading );

				// Single posts banner
				if ( apply_filters( 'anesta_filter_single_post_header', anesta_is_singular( 'post' ) || anesta_is_singular( 'attachment' ) ) ) {
					if ( $anesta_prev_post_loading ) {
						if ( anesta_get_theme_option( 'posts_navigation_scroll_which_block' ) != 'article' ) {
							do_action( 'anesta_action_between_posts' );
						}
					}
					// Single post thumbnail and title
					$anesta_path = apply_filters( 'anesta_filter_get_template_part', 'templates/single-styles/' . anesta_get_theme_option( 'single_style' ) );
					if ( anesta_get_file_dir( $anesta_path . '.php' ) != '' ) {
						get_template_part( $anesta_path );
					}
				}

				// Widgets area above page
				$anesta_body_style   = anesta_get_theme_option( 'body_style' );
				$anesta_widgets_name = anesta_get_theme_option( 'widgets_above_page' );
				$anesta_show_widgets = ! anesta_is_off( $anesta_widgets_name ) && is_active_sidebar( $anesta_widgets_name );
				if ( $anesta_show_widgets ) {
					if ( 'fullscreen' != $anesta_body_style ) {
						?>
						<div class="content_wrap">
							<?php
					}
					anesta_create_widgets_area( 'widgets_above_page' );
					if ( 'fullscreen' != $anesta_body_style ) {
						?>
						</div>
						<?php
					}
				}

				// Content area
				do_action( 'anesta_action_before_content_wrap' );
				?>
				<div class="content_wrap<?php echo 'fullscreen' == $anesta_body_style ? '_fullscreen' : ''; ?>">

					<?php do_action( 'anesta_action_content_wrap_start' ); ?>

					<div class="content">
						<?php
						do_action( 'anesta_action_page_content_start' );

						// Skip link anchor to fast access to the content from keyboard
						?>
						<a id="content_skip_link_anchor" class="anesta_skip_link_anchor" href="#"></a>
						<?php
						// Single posts banner between prev/next posts
						if ( ( anesta_is_singular( 'post' ) || anesta_is_singular( 'attachment' ) )
							&& $anesta_prev_post_loading 
							&& anesta_get_theme_option( 'posts_navigation_scroll_which_block' ) == 'article'
						) {
							do_action( 'anesta_action_between_posts' );
						}

						// Widgets area above content
						anesta_create_widgets_area( 'widgets_above_content' );

						do_action( 'anesta_action_page_content_start_text' );