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


/* ==========================================================================
   The Sale category follows the sale price
   ========================================================================== */

add_action( 'woocommerce_after_product_object_save', 'dkm_sale_category_follows', 20, 1 );

/**
 * Puts a watch in the Sale category the moment it gets a sale price, and takes
 * it out again when the price goes back up.
 *
 * Doing this by hand meant the Sale page was always a little behind the real
 * prices.
 *
 * @param WC_Product $product The product that was just saved.
 */
function dkm_sale_category_follows( $product ): void {
	static $bezig = false;
	if ( $bezig ) {
		return;
	}

	if ( ! $product instanceof WC_Product || 'product' !== get_post_type( $product->get_id() ) ) {
		return;
	}

	$term = get_term_by( 'slug', 'sale', 'product_cat' );
	if ( ! $term || is_wp_error( $term ) ) {
		return;
	}

	$id      = $product->get_id();
	$korting = $product->is_on_sale( 'edit' );
	$erin    = has_term( $term->term_id, 'product_cat', $id );

	if ( $korting === $erin ) {
		return;
	}

	$bezig = true;

	if ( $korting ) {
		wp_set_object_terms( $id, array( (int) $term->term_id ), 'product_cat', true );
	} else {
		$houden = wp_get_object_terms( $id, 'product_cat', array( 'fields' => 'ids' ) );
		$houden = array_values( array_diff( (array) $houden, array( (int) $term->term_id ) ) );
		wp_set_object_terms( $id, $houden, 'product_cat' );
	}

	$bezig = false;
}
