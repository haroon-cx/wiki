<?php

/**
 * Class WPML_Elementor_Trx_Elm_Info_List
 * 
 * A helper class to translate the content of the widget Elementor Widgets - Icon List
 */
class WPML_Elementor_Trx_Elm_Info_List extends WPML_Elementor_Trx_Module_With_Items  {

	/**
	 * Return a field name with type REPEATER
	 * 
	 * @return string  The field name
	 */
	public function get_items_field() {
		return 'list_items';
	}

	/**
	 * Return a field names to translate
	 * 
	 * @return array  The field names
	 */
	public function get_fields() {
		return array( 'text', 'description', 'icon_text', 'link' => array( 'url' ), 'button_text' );
	}

	/**
	 * Return a title for a field by name
	 * 
	 * @param string $field  The field name
	 *
	 * @return string  	The field title
	 */
	protected function get_title( $field ) {
		$sc = __( 'Info List', 'trx_addons' );
		switch( $field ) {
			case 'text':
				return esc_html( sprintf( __( '%s: Title', 'trx_addons' ), $sc ) );

			case 'description':
				return esc_html( sprintf( __( '%s: Description', 'trx_addons' ), $sc ) );

			case 'icon_text':
				return esc_html( sprintf( __( '%s: Icon text', 'trx_addons' ), $sc ) );
	
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
			case 'text':
				return 'LINE';

			case 'description':
				return 'AREA';

			case 'icon_text':
				return 'LINE';
				
			case 'url':
				return 'LINK';

			case 'button_text':
				return 'LINE';

			default:
				return '';
		}
	}

}
