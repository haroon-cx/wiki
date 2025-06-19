<?php

/**
 * Class WPML_Elementor_Trx_Elm_Accordion
 * 
 * A helper class to translate the content of the widget Elementor Widgets - Accordion
 */
class WPML_Elementor_Trx_Elm_Accordion extends WPML_Elementor_Trx_Module_With_Items  {

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
		return array( 'tab_title', 'accordion_content' );
	}

	/**
	 * Return a title for a field by name
	 * 
	 * @param string $field  The field name
	 *
	 * @return string  	The field title
	 */
	protected function get_title( $field ) {
		$sc = __( 'Accordion', 'trx_addons' );
		switch( $field ) {
			case 'tab_title':
				return esc_html( sprintf( __( '%s: title', 'trx_addons' ), $sc ) );

			case 'accordion_content':
				return esc_html( sprintf( __( '%s: content', 'trx_addons' ), $sc ) );

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
			case 'tab_title':
				return 'LINE';

			case 'accordion_content':
				return 'VISUAL';

			default:
				return '';
		}
	}

}
