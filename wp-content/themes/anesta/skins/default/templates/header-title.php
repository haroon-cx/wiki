<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package ANESTA
 * @since ANESTA 1.0
 */

// Page (category, tag, archive, author) title

if ( anesta_need_page_title() ) {
	anesta_sc_layouts_showed( 'title', true );
	anesta_sc_layouts_showed( 'postmeta', true );
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title sc_align_center">
						<?php
						// Post meta on the single post
						if ( is_single() ) {
							?>
							<div class="sc_layouts_title_meta">
							<?php
								anesta_show_post_meta(
									apply_filters(
										'anesta_filter_post_meta_args', array(
											'components' => join( ',', anesta_array_get_keys_by_value( anesta_get_theme_option( 'meta_parts' ) ) ),
											'counters'   => join( ',', anesta_array_get_keys_by_value( anesta_get_theme_option( 'counters' ) ) ),
											'seo'        => anesta_is_on( anesta_get_theme_option( 'seo_snippets' ) ),
										), 'header', 1
									)
								);
							?>
							</div>
							<?php
						}

						// Blog/Post title
						?>
						<div class="sc_layouts_title_title">
							<?php
							$anesta_blog_title           = anesta_get_blog_title();
							$anesta_blog_title_text      = '';
							$anesta_blog_title_class     = '';
							$anesta_blog_title_link      = '';
							$anesta_blog_title_link_text = '';
							if ( is_array( $anesta_blog_title ) ) {
								$anesta_blog_title_text      = $anesta_blog_title['text'];
								$anesta_blog_title_class     = ! empty( $anesta_blog_title['class'] ) ? ' ' . $anesta_blog_title['class'] : '';
								$anesta_blog_title_link      = ! empty( $anesta_blog_title['link'] ) ? $anesta_blog_title['link'] : '';
								$anesta_blog_title_link_text = ! empty( $anesta_blog_title['link_text'] ) ? $anesta_blog_title['link_text'] : '';
							} else {
								$anesta_blog_title_text = $anesta_blog_title;
							}
							?>
							<h1 itemprop="headline" class="sc_layouts_title_caption<?php echo esc_attr( $anesta_blog_title_class ); ?>">
								<?php
								$anesta_top_icon = anesta_get_term_image_small();
								if ( ! empty( $anesta_top_icon ) ) {
									$anesta_attr = anesta_getimagesize( $anesta_top_icon );
									?>
									<img src="<?php echo esc_url( $anesta_top_icon ); ?>" alt="<?php esc_attr_e( 'Site icon', 'anesta' ); ?>"
										<?php
										if ( ! empty( $anesta_attr[3] ) ) {
											anesta_show_layout( $anesta_attr[3] );
										}
										?>
									>
									<?php
								}
								echo wp_kses_data( $anesta_blog_title_text );
								?>
							</h1>
							<?php
							if ( ! empty( $anesta_blog_title_link ) && ! empty( $anesta_blog_title_link_text ) ) {
								?>
								<a href="<?php echo esc_url( $anesta_blog_title_link ); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html( $anesta_blog_title_link_text ); ?></a>
								<?php
							}

							// Category/Tag description
							if ( ! is_paged() && ( is_category() || is_tag() || is_tax() ) ) {
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
							}

							?>
						</div>
						<?php

						// Breadcrumbs
						ob_start();
						do_action( 'anesta_action_breadcrumbs' );
						$anesta_breadcrumbs = ob_get_contents();
						ob_end_clean();
						anesta_show_layout( $anesta_breadcrumbs, '<div class="sc_layouts_title_breadcrumbs">', '</div>' );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
