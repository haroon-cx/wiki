<?php

/**
 * Class WPML_Elementor_Trx_Elm_Testimonials
 * 
 * A helper class to translate the content of the widget Elementor Widgets - Testimonials
 */
class WPML_Elementor_Trx_Elm_Testimonials extends WPML_Elementor_Trx_Module_With_Items  {

	/**
	 * Return a field name with type REPEATER
	 * 
	 * @return string  The field name
	 */
	public function get_items_field() {
		return 'testimonials';
	}

	/**
	 * Return a field names to translate
	 * 
	 * @return array  The field names
	 */
	public function get_fields() {
		return array( 'heading', 'person_name', 'company_name', 'link' => array( 'url' ), 'content' );
	}

	/**
	 * Return a title for a field by name
	 * 
	 * @param string $field  The field name
	 *
	 * @return string  	The field title
	 */
	protected function get_title( $field ) {
		$sc = __( 'Testimonials', 'trx_addons' );
		switch( $field ) {
			case 'heading':
				return esc_html( sprintf( __( '%s: Heading', 'trx_addons' ), $sc ) );

			case 'person_name':
				return esc_html( sprintf( __( '%s: Name', 'trx_addons' ), $sc ) );

			case 'company_name':
					return esc_html( sprintf( __( '%s: Job/Company', 'trx_addons' ), $sc ) );
	
			case 'url':
				return esc_html( sprintf( __( '%s: Link URL', 'trx_addons' ), $sc ) );

			case 'content':
				return esc_html( sprintf( __( '%s: Content', 'trx_addons' ), $sc ) );

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
			case 'heading':
				return 'LINE';

			case 'person_name':
				return 'LINE';

			case 'company_name':
				return 'LINE';
				
			case 'url':
				return 'LINK';

			case 'content':
				return 'VISUAL';
	
			default:
				return '';
		}
	}

}
