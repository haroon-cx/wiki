<?php

/**
 * Class WPML_Elementor_Trx_Elm_Team_Member
 * 
 * A helper class to translate the content of the widget Elementor Widgets - Team Member
 */
class WPML_Elementor_Trx_Elm_Team_Member extends WPML_Elementor_Trx_Module_With_Items  {

	/**
	 * Return a field name with type REPEATER
	 * 
	 * @return string  The field name
	 */
	public function get_items_field() {
		return 'team_member_social';
	}

	/**
	 * Return a field names to translate
	 * 
	 * @return array  The field names
	 */
	public function get_fields() {
		return array( 'social_link' => array( 'url' ) );
	}

	/**
	 * Return a title for a field by name
	 * 
	 * @param string $field  The field name
	 *
	 * @return string  	The field title
	 */
	protected function get_title( $field ) {
		$sc = __( 'Team Member', 'trx_addons' );
		switch( $field ) {
			case 'url':
				return esc_html( sprintf( __( '%s: Social URL', 'trx_addons' ), $sc ) );

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
			case 'url':
				return 'LINK';

			default:
				return '';
		}
	}

}
