<?php

/**
 * Class WPML_Elementor_Trx_Elm_Pricing_Table
 * 
 * A helper class to translate the content of the widget Elementor Widgets - Pricing Table
 */
class WPML_Elementor_Trx_Elm_Pricing_Table extends WPML_Elementor_Trx_Module_With_Items  {

	/**
	 * Return a field name with type REPEATER
	 * 
	 * @return string  The field name
	 */
	public function get_items_field() {
		return 'table_features';
	}

	/**
	 * Return a field names to translate
	 * 
	 * @return array  The field names
	 */
	public function get_fields() {
		return array( 'feature_text', 'tooltip_content' );
	}

	/**
	 * Return a title for a field by name
	 * 
	 * @param string $field  The field name
	 *
	 * @return string  	The field title
	 */
	protected function get_title( $field ) {
		$sc = __( 'Nav Menu', 'trx_addons' );
		switch( $field ) {
			case 'feature_text':
				return esc_html( sprintf( __( '%s: Text', 'trx_addons' ), $sc ) );

			case 'tooltip_content':
				return esc_html( sprintf( __( '%s: Tooltip Content', 'trx_addons' ), $sc ) );

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
			case 'feature_text':
				return 'AREA';
	
			case 'tooltip_content':
				return 'AREA';

			default:
				return '';
		}
	}

}
