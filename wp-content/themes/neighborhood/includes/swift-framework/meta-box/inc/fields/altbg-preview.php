<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'RWMB_Altbgpreview_Field' ) )
{
	class RWMB_Altbgpreview_Field
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
			$html    = '<div class="meta-altbg-preview"><p>Alt Background Preview</p></div>';

			return $html;
		}

	}
}