<?php

/**
 * Class WPML_Elementor_Trx_Elm_Image_Accordion
 * 
 * A helper class to translate the content of the widget Elementor Widgets - Icon List
 */
class WPML_Elementor_Trx_Elm_Image_Accordion extends WPML_Elementor_Trx_Module_With_Items  {

	/**
	 * Return a field name with type REPEATER
	 * 
	 * @return string  The field name
	 */
	public function get_items_field() {
		return 'accordion_items';
	}

	/**
	 * Return a field names to translate
	 * 
	 * @return array  The field names
	 */
	public function get_fields() {
		return array( 'title', 'description', 'link' => array( 'url' ), 'button_text' );
	}

	/**
	 * Return a title for a field by name
	 * 
	 * @param string $field  The field name
	 *
	 * @return string  	The field title
	 */
	protected function get_title( $field ) {
		$sc = __( 'Image Accordion', 'trx_addons' );
		switch( $field ) {
			case 'title':
				return esc_html( sprintf( __( '%s: Title', 'trx_addons' ), $sc ) );

			case 'description':
				return esc_html( sprintf( __( '%s: Description', 'trx_addons' ), $sc ) );

			case 'url':
				return esc_html( sprintf( __( '%s: Link URL', 'trx_addons' ), $sc ) );

			case 'button_text':
					return esc_html( sprintf( __( '%s: Button text', 'trx_addons' ), $sc ) );
	
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
			case 'title':
				return 'LINE';

			case 'description':
				return 'VISUAL';
			
			case 'url':
				return 'LINK';

			case 'button_text':
				return 'LINE';

			default:
				return '';
		}
	}

}
