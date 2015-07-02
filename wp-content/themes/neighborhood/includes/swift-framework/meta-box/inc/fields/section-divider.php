<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'RWMB_Section_Field' ) )
{
	class RWMB_Section_Field
	{
		/**
		 * Get field HTML
		 *
		 * @param string $html
		 * @param mixed  $meta
		 * @param array  $field
		 *
		 * @return string
		 */
		static function html( $html, $meta, $field )
		{
			$name    = "name='{$field['field_name']}'";
			$id      = " id='{$field['id']}'";
			$title 	 = $field['title'];
			$html    = "<h2 class='meta-box-section'>".$title."</h2>";

			return $html;
		}

	}
}