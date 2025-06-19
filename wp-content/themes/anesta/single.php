<?php
/**
 * The template to display single post
 *
 * @package ANESTA
 * @since ANESTA 1.0
 */

// Full post loading
$full_post_loading          = anesta_get_value_gp( 'action' ) == 'full_post_loading';

// Prev post loading
$prev_post_loading          = anesta_get_value_gp( 'action' ) == 'prev_post_loading';
$prev_post_loading_type     = anesta_get_theme_option( 'posts_navigation_scroll_which_block' );

// Position of the related posts
$anesta_related_position   = anesta_get_theme_option( 'related_position' );

// Type of the prev/next post navigation
$anesta_posts_navigation   = anesta_get_theme_option( 'posts_navigation' );
$anesta_prev_post          = false;
$anesta_prev_post_same_cat = anesta_get_theme_option( 'posts_navigation_scroll_same_cat' );

// Rewrite style of the single post if current post loading via AJAX and featured image and title is not in the content
if ( ( $full_post_loading 
		|| 
		( $prev_post_loading && 'article' == $prev_post_loading_type )
	) 
	&& 
	! in_array( anesta_get_theme_option( 'single_style' ), array( 'style-6' ) )
) {
	anesta_storage_set_array( 'options_meta', 'single_style', 'style-6' );
}

do_action( 'anesta_action_prev_post_loading', $prev_post_loading, $prev_post_loading_type );

get_header();

while ( have_posts() ) {

	the_post();

	// Type of the prev/next post navigation
	if ( 'scroll' == $anesta_posts_navigation ) {
		$anesta_prev_post = get_previous_post( $anesta_prev_post_same_cat );  // Get post from same category
		if ( ! $anesta_prev_post && $anesta_prev_post_same_cat ) {
			$anesta_prev_post = get_previous_post( false );                    // Get post from any category
		}
		if ( ! $anesta_prev_post ) {
			$anesta_posts_navigation = 'links';
		}
	}

	// Override some theme options to display featured image, title and post meta in the dynamic loaded posts
	if ( $full_post_loading || ( $prev_post_loading && $anesta_prev_post ) ) {
		anesta_sc_layouts_showed( 'featured', false );
		anesta_sc_layouts_showed( 'title', false );
		anesta_sc_layouts_showed( 'postmeta', false );
	}

	// If related posts should be inside the content
	if ( strpos( $anesta_related_position, 'inside' ) === 0 ) {
		ob_start();
	}

	// Display post's content
	get_template_part( apply_filters( 'anesta_filter_get_template_part', 'templates/content', 'single-' . anesta_get_theme_option( 'single_style' ) ), 'single-' . anesta_get_theme_option( 'single_style' ) );

	// If related posts should be inside the content
	if ( strpos( $anesta_related_position, 'inside' ) === 0 ) {
		$anesta_content = ob_get_contents();
		ob_end_clean();

		ob_start();
		do_action( 'anesta_action_related_posts' );
		$anesta_related_content = ob_get_contents();
		ob_end_clean();

		if ( ! empty( $anesta_related_content ) ) {
			$anesta_related_position_inside = max( 0, min( 9, anesta_get_theme_option( 'related_position_inside' ) ) );
			if ( 0 == $anesta_related_position_inside ) {
				$anesta_related_position_inside = mt_rand( 1, 9 );
			}

			$anesta_p_number         = 0;
			$anesta_related_inserted = false;
			$anesta_in_block         = false;
			$anesta_content_start    = strpos( $anesta_content, '<div class="post_content' );
			$anesta_content_end      = strrpos( $anesta_content, '</div>' );

			for ( $i = max( 0, $anesta_content_start ); $i < min( strlen( $anesta_content ) - 3, $anesta_content_end ); $i++ ) {
				if ( $anesta_content[ $i ] != '<' ) {
					continue;
				}
				if ( $anesta_in_block ) {
					if ( strtolower( substr( $anesta_content, $i + 1, 12 ) ) == '/blockquote>' ) {
						$anesta_in_block = false;
						$i += 12;
					}
					continue;
				} else if ( strtolower( substr( $anesta_content, $i + 1, 10 ) ) == 'blockquote' && in_array( $anesta_content[ $i + 11 ], array( '>', ' ' ) ) ) {
					$anesta_in_block = true;
					$i += 11;
					continue;
				} else if ( 'p' == $anesta_content[ $i + 1 ] && in_array( $anesta_content[ $i + 2 ], array( '>', ' ' ) ) ) {
					$anesta_p_number++;
					if ( $anesta_related_position_inside == $anesta_p_number ) {
						$anesta_related_inserted = true;
						$anesta_content = ( $i > 0 ? substr( $anesta_content, 0, $i ) : '' )
											. $anesta_related_content
											. substr( $anesta_content, $i );
					}
				}
			}
			if ( ! $anesta_related_inserted ) {
				if ( $anesta_content_end > 0 ) {
					$anesta_content = substr( $anesta_content, 0, $anesta_content_end ) . $anesta_related_content . substr( $anesta_content, $anesta_content_end );
				} else {
					$anesta_content .= $anesta_related_content;
				}
			}
		}

		anesta_show_layout( $anesta_content );
	}

	// Comments
	do_action( 'anesta_action_before_comments' );
	comments_template();
	do_action( 'anesta_action_after_comments' );

	// Related posts
	if ( 'below_content' == $anesta_related_position
		&& ( 'scroll' != $anesta_posts_navigation || anesta_get_theme_option( 'posts_navigation_scroll_hide_related' ) == 0 )
		&& ( ! $full_post_loading || anesta_get_theme_option( 'open_full_post_hide_related' ) == 0 )
	) {
		do_action( 'anesta_action_related_posts' );
	}

	// Post navigation: type 'scroll'
	if ( 'scroll' == $anesta_posts_navigation && ! $full_post_loading ) {
		?>
		<div class="nav-links-single-scroll"
			data-post-id="<?php echo esc_attr( get_the_ID( $anesta_prev_post ) ); ?>"
			data-post-link="<?php echo esc_attr( get_permalink( $anesta_prev_post ) ); ?>"
			data-post-title="<?php the_title_attribute( array( 'post' => $anesta_prev_post ) ); ?>"
			<?php do_action( 'anesta_action_nav_links_single_scroll_data', $anesta_prev_post ); ?>
		></div>
		<?php
	}
}

get_footer();
