<?php
/**
 * The template to display the background video in the header
 *
 * @package ANESTA
 * @since ANESTA 1.0.14
 */
$anesta_header_video = anesta_get_header_video();
$anesta_embed_video  = '';
if ( ! empty( $anesta_header_video ) && ! anesta_is_from_uploads( $anesta_header_video ) ) {
	if ( anesta_is_youtube_url( $anesta_header_video ) && preg_match( '/[=\/]([^=\/]*)$/', $anesta_header_video, $matches ) && ! empty( $matches[1] ) ) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr( $matches[1] ); ?>"></div>
		<?php
	} else {
		?>
		<div id="background_video"><?php anesta_show_layout( anesta_get_embed_video( $anesta_header_video ) ); ?></div>
		<?php
	}
}
