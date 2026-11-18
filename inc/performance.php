<?php
/**
 * Performance and head cleanup
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'dkm_clean_head' );

function dkm_clean_head(): void {
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );

	if ( ! is_admin() ) {
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
		wp_deregister_script( 'wp-embed' );
	}
}

/**
 * Drops jquery-migrate without deregistering it, because jquery itself lists it
 * as a dependency and a missing handle makes WordPress complain on every load.
 */
add_action( 'wp_default_scripts', 'dkm_drop_jquery_migrate' );

function dkm_drop_jquery_migrate( WP_Scripts $scripts ): void {
	if ( is_admin() || empty( $scripts->registered['jquery'] ) ) {
		return;
	}
	$scripts->registered['jquery']->deps = array_diff(
		$scripts->registered['jquery']->deps,
		array( 'jquery-migrate' )
	);
}

/**
 * WooCommerce ships cart fragments and add-to-cart handlers on every page. The
 * theme does its own, so they only load where WooCommerce itself needs them.
 */
add_action( 'wp_enqueue_scripts', 'dkm_trim_wc_scripts', 99 );

function dkm_trim_wc_scripts(): void {
	if ( ! function_exists( 'is_woocommerce' ) ) {
		return;
	}
	if ( is_cart() || is_checkout() || is_account_page() ) {
		return;
	}
	wp_dequeue_script( 'wc-cart-fragments' );
	wp_dequeue_script( 'wc-password-strength-meter' );
	wp_dequeue_script( 'selectWoo' );
	// Not this one. WooCommerce swaps the country field for a widget of its
	// own, and without this stylesheet that widget is a one pixel line.
}

/**
 * The hero image is the largest paint on the homepage, so it is fetched early
 * and never lazy loaded.
 */
add_filter( 'wp_get_attachment_image_attributes', 'dkm_eager_hero', 10, 2 );

function dkm_eager_hero( array $attr, $attachment ): array {
	if ( ! empty( $attr['class'] ) && str_contains( (string) $attr['class'], 'dkm-eager' ) ) {
		$attr['loading']       = 'eager';
		$attr['fetchpriority'] = 'high';
		$attr['decoding']      = 'sync';
	}
	return $attr;
}

add_filter( 'jpeg_quality', fn() => 82 );
add_filter( 'wp_editor_set_quality', fn() => 82 );
add_filter( 'big_image_size_threshold', fn() => 2560 );

add_action( 'send_headers', 'dkm_security_headers' );

function dkm_security_headers(): void {
	if ( is_admin() ) {
		return;
	}
	header( 'X-Content-Type-Options: nosniff' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'X-Frame-Options: SAMEORIGIN' );
}

/**
 * Keeps the product query light: the cards only need what they render.
 */
add_action( 'pre_get_posts', 'dkm_tune_product_query' );

function dkm_tune_product_query( WP_Query $query ): void {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) ) {
		$query->set( 'update_post_term_cache', true );
		$query->set( 'update_post_meta_cache', true );
	}
}
