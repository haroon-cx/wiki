<?php

/**
 * Class WPML_Elementor_Trx_Elm_Advanced_Title
 * 
 * A helper class to translate the content of the widget Elementor Widgets - Advanced Title
 */
class WPML_Elementor_Trx_Elm_Advanced_Title extends WPML_Elementor_Trx_Module_With_Items  {

	/**
	 * Return a field name with type REPEATER
	 * 
	 * @return string  The field name
	 */
	public function get_items_field() {
		return 'content';
	}

	/**
	 * Return a field names to translate
	 * 
	 * @return array  The field names
	 */
	public function get_fields() {
		return array( 'text', 'item_link' => array( 'url' ) );
	}

	/**
	 * Return a title for a field by name
	 * 
	 * @param string $field  The field name
	 *
	 * @return string  	The field title
	 */
	protected function get_title( $field ) {
		$sc = __( 'Advanced Title', 'trx_addons' );
		switch( $field ) {
			case 'text':
				return esc_html( sprintf( __( '%s: text', 'trx_addons' ), $sc ) );

			case 'url':
				return esc_html( sprintf( __( '%s: link URL', 'trx_addons' ), $sc ) );
	
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
			case 'text':
				return 'AREA';

			case 'url':
				return 'LINK';
	
			default:
				return '';
		}
	}

}
