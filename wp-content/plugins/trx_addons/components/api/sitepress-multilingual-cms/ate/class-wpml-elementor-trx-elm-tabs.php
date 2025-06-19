<?php

/**
 * Class WPML_Elementor_Trx_Elm_Tabs
 * 
 * A helper class to translate the content of the widget Elementor Widgets - Tabs
 */
class WPML_Elementor_Trx_Elm_Tabs extends WPML_Elementor_Trx_Module_With_Items  {

	/**
	 * Return a field name with type REPEATER
	 * 
	 * @return string  The field name
	 */
	public function get_items_field() {
		return 'tabs';
	}

	/**
	 * Return a field names to translate
	 * 
	 * @return array  The field names
	 */
	public function get_fields() {
		return array( 'tabs_title', 'tabs_subtitle', 'tabs_content', 'tabs_content_shortcode', 'tabs_section_id', 'tabs_details_btn_text', 'tabs_details_btn_link' => array( 'url' ) );
	}

	/**
	 * Return a title for a field by name
	 * 
	 * @param string $field  The field name
	 *
	 * @return string  	The field title
	 */
	protected function get_title( $field ) {
		$sc = __( 'Tabs', 'trx_addons' );
		switch( $field ) {
			case 'tabs_title':
				return esc_html( sprintf( __( '%s: Title', 'trx_addons' ), $sc ) );

			case 'tabs_subtitle':
				return esc_html( sprintf( __( '%s: Description', 'trx_addons' ), $sc ) );
			
			case 'tabs_content':
				return esc_html( sprintf( __( '%s: Content', 'trx_addons' ), $sc ) );

			case 'tabs_content_shortcode':
				return esc_html( sprintf( __( '%s: Shortcode', 'trx_addons' ), $sc ) );	
			
			case 'tabs_section_id':
				return esc_html( sprintf( __( '%s: Section ID', 'trx_addons' ), $sc ) );
			
			case 'tabs_details_btn_text':
				return esc_html( sprintf( __( '%s: Button Text', 'trx_addons' ), $sc ) );

			case 'url':
				return esc_html( sprintf( __( '%s: Button URL', 'trx_addons' ), $sc ) );

			default:
				return '';
		}
	}

	/**
	 * Return a field type by name
	 * 
	 * @param string $field  The field name
	 *
	 * @return string  	The field type
	 */
	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'tabs_title':
				return 'LINE';

			case 'tabs_subtitle':
				return 'AREA';

			case 'tabs_content':
				return 'VISUAL';
			
			case 'tabs_content_shortcode':
				return 'LINE';

			case 'tabs_section_id':
				return 'LINE';
			
			case 'tabs_details_btn_text':
				return 'LINE';
			
			case 'url':
				return 'LINK';

			default:
				return '';
		}
	}

}
