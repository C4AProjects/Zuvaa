<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'RWMB_Sidebars_Field' ) )
{
	class RWMB_Sidebars_Field
	{
		/**
		 * Enqueue scripts and styles
		 *
		 * @return	void
		 */
		static function admin_enqueue_scripts( )
		{
			wp_enqueue_style( 'rwmb-select', RWMB_CSS_URL.'select.css', RWMB_VER );
		}

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

			$html .= '<select name="'.$field['id'].'" id="'.$field['id'].'">';
			foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) {
				if (!empty($meta) && !strcmp($meta, ucwords( $sidebar['id'] ))) {
					$html .= '<option value="'. ucwords( $sidebar['id'] ) .'" selected="selected">';
					$html .= ucwords( $sidebar['name'] );
					$html .= '</option>';
				} else {
			    	$html .= '<option value="'. ucwords( $sidebar['id'] ) .'">';
			    	$html .= ucwords( $sidebar['name'] );
			    	$html .= '</option>';
				}
			}
			$html .= '</select>';

			return $html;
		}
	}
}