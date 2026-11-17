<?php
/**
 * Helpers the plugin and the theme both use
 *
 * Each one is guarded, and the theme guards its own copies the same way, so
 * whichever loads first wins and neither can fatal on a redeclare.
 *
 * @package DKM_Core
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'dkm_short_title' ) ) {
	/**
	 * Strips the tier suffix a product title carries.
	 */
	function dkm_short_title( string $name ): string {
		return trim( (string) preg_replace( '/\s*\((SUPERCLONE|BP SWISS MADE|AAA\+)\)\s*$/i', '', $name ) );
	}
}

if ( ! function_exists( 'dkm_field' ) ) {
	/**
	 * Reads an ACF field with a fallback, and keeps working without ACF.
	 *
	 * @param string     $selector Field name.
	 * @param int|string $post_id  Post, term or option id.
	 * @param mixed      $default  Returned when empty or when ACF is absent.
	 * @return mixed
	 */
	function dkm_field( string $selector, $post_id = null, $default = '' ) {
		if ( ! function_exists( 'get_field' ) ) {
			return $default;
		}
		$value = get_field( $selector, $post_id );
		if ( null === $value || '' === $value || array() === $value || false === $value ) {
			return $default;
		}
		return $value;
	}
}
