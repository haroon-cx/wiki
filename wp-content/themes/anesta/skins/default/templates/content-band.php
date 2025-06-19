<?php
/**
 * 'Band' template to display the content
 *
 * Used for index/archive/search.
 *
 * @package ANESTA
 * @since ANESTA 1.71.0
 */

$anesta_template_args = get_query_var( 'anesta_template_args' );
if ( ! is_array( $anesta_template_args ) ) {
	$anesta_template_args = array(
								'type'    => 'band',
								'columns' => 1
								);
}

$anesta_columns       = 1;

$anesta_expanded      = ! anesta_sidebar_present() && anesta_get_theme_option( 'expand_content' ) == 'expand';

$anesta_post_format   = get_post_format();
$anesta_post_format   = empty( $anesta_post_format ) ? 'standard' : str_replace( 'post-format-', '', $anesta_post_format );

if ( is_array( $anesta_template_args ) ) {
	$anesta_columns    = empty( $anesta_template_args['columns'] ) ? 1 : max( 1, $anesta_template_args['columns'] );
	$anesta_blog_style = array( $anesta_template_args['type'], $anesta_columns );
	if ( ! empty( $anesta_template_args['slider'] ) ) {
		?><div class="slider-slide swiper-slide"><?php
	} elseif ( $anesta_columns > 1 ) {
		$anesta_columns_class = anesta_get_column_class( 1, $anesta_columns, ! empty( $anesta_template_args['columns_tablet']) ? $anesta_template_args['columns_tablet'] : '', ! empty($anesta_template_args['columns_mobile']) ? $anesta_template_args['columns_mobile'] : '' );
		?><div class="<?php echo esc_attr( $anesta_columns_class ); ?>"><?php
	}
}
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class( 'post_item post_item_container post_layout_band post_format_' . esc_attr( $anesta_post_format ) );
	anesta_add_blog_animation( $anesta_template_args );
	?>
>
	<?php

	// Sticky label
	if ( is_sticky() && ! is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	$anesta_hover      = ! empty( $anesta_template_args['hover'] ) && ! anesta_is_inherit( $anesta_template_args['hover'] )
							? $anesta_template_args['hover']
							: anesta_get_theme_option( 'image_hover' );
	$anesta_components = ! empty( $anesta_template_args['meta_parts'] )
							? ( is_array( $anesta_template_args['meta_parts'] )
								? $anesta_template_args['meta_parts']
								: array_map( 'trim', explode( ',', $anesta_template_args['meta_parts'] ) )
								)
							: anesta_array_get_keys_by_value( anesta_get_theme_option( 'meta_parts' ) );
	anesta_show_post_featured( apply_filters( 'anesta_filter_args_featured', 
		array(
			'no_links'   => ! empty( $anesta_template_args['no_links'] ),
			'hover'      => $anesta_hover,
			'meta_parts' => $anesta_components,
			'thumb_bg'   => true,
			'thumb_size' => ! empty( $anesta_template_args['thumb_size'] )
								? $anesta_template_args['thumb_size']
								: anesta_get_thumb_size( 
									in_array( $anesta_post_format, array( 'gallery', 'audio', 'video' ) )
										? ( strpos( anesta_get_theme_option( 'body_style' ), 'full' ) !== false
											? 'full'
											: ( $anesta_expanded 
												? 'big' 
												: 'med'
												)
											)
										: 'masonry-big'
									)
		),
		'content-band',
		$anesta_template_args
	) );

	?><div class="post_content_wrap"><?php

		// Title and post meta
		$anesta_show_title = get_the_title() != '';
		$anesta_show_meta  = count( $anesta_components ) > 0 && ! in_array( $anesta_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );
		if ( $anesta_show_title ) {
			?>
			<div class="post_header entry-header">
				<?php
				// Categories
				if ( apply_filters( 'anesta_filter_show_blog_categories', $anesta_show_meta && in_array( 'categories', $anesta_components ), array( 'categories' ), 'band' ) ) {
					do_action( 'anesta_action_before_post_category' );

					anesta_show_post_meta( apply_filters(
													'anesta_filter_post_meta_args',
													array(
														'components' => 'categories,date',
														'seo'        => false,
														'echo'       => true,
														),
													'hover_' . $anesta_hover, 1
													)
									);

					$anesta_components = anesta_array_delete_by_value( $anesta_components, 'categories' );
					$anesta_components = anesta_array_delete_by_value( $anesta_components, 'date' );
					do_action( 'anesta_action_after_post_category' );
				}
				// Post title
				if ( apply_filters( 'anesta_filter_show_blog_title', true, 'band' ) ) {
					do_action( 'anesta_action_before_post_title' );
					if ( empty( $anesta_template_args['no_links'] ) ) {
						the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
					} else {
						the_title( '<h3 class="post_title entry-title">', '</h3>' );
					}
					do_action( 'anesta_action_after_post_title' );
				}
				?>
			</div>
			<?php
		}

		// Post content
		if ( ! isset( $anesta_template_args['excerpt_length'] ) && ! in_array( $anesta_post_format, array( 'gallery', 'audio', 'video' ) ) ) {
			$anesta_template_args['excerpt_length'] = 30;
		}
		$anesta_show_excerpt = in_array( $anesta_post_format, array('quote', 'aside', 'status', 'link') ) ? true : (isset( $anesta_template_args['hide_excerpt'] ) ? (int) $anesta_template_args['hide_excerpt'] == 0 : (int) anesta_get_theme_option( 'excerpt_length' ) > 0);
		if ( apply_filters( 'anesta_filter_show_blog_excerpt', $anesta_show_excerpt, 'band' ) ) {
			?>
			<div class="post_content entry-content">
				<?php
				// Post content area
				anesta_show_post_content( $anesta_template_args, '<div class="post_content_inner">', '</div>' );
				?>
			</div>
			<?php
		}
		// Post meta
		if ( apply_filters( 'anesta_filter_show_blog_meta', $anesta_show_meta, $anesta_components, 'band' ) ) {
			if ( count( $anesta_components ) > 0 ) {
				do_action( 'anesta_action_before_post_meta' );
				anesta_show_post_meta(
					apply_filters(
						'anesta_filter_post_meta_args', array(
							'components' => join( ',', $anesta_components ),
							'seo'        => false,
							'echo'       => true,
						), 'band', 1
					)
				);
				do_action( 'anesta_action_after_post_meta' );
			}
		}
		// More button
		if ( apply_filters( 'anesta_filter_show_blog_readmore', ( ! $anesta_show_title || ! empty( $anesta_template_args['more_button'] ) ) && ! empty( $args['more_text'] ), 'band' ) ) {
			if ( empty( $anesta_template_args['no_links'] ) ) {
				do_action( 'anesta_action_before_post_readmore' );
				anesta_show_post_more_link( $anesta_template_args, '<p>', '</p>' );
				do_action( 'anesta_action_after_post_readmore' );
			}
		}
		?>
	</div>
</article>
<?php

if ( is_array( $anesta_template_args ) ) {
	if ( ! empty( $anesta_template_args['slider'] ) || $anesta_columns > 1 ) {
		?>
		</div>
		<?php
	}
}
