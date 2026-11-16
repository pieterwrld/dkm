<?php
/**
 * Product in the loop
 *
 * Delegates to the shared card so the WooCommerce loop and the dkm/v1/products
 * route render byte identical markup.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product instanceof WC_Product ) {
	$product = wc_get_product( get_the_ID() );
}

if ( ! $product || ! $product->is_visible() ) {
	return;
}

get_template_part( 'template-parts/product-card', null, array( 'product' => $product ) );
