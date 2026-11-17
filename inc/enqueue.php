<?php
/**
 * Styles and scripts
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

/**
 * Stylesheets, in cascade order. tokens.css first, always.
 */
function dkm_stylesheets(): array {
	return array(
		'dkm-tokens'     => 'tokens.css',
		'dkm-base'       => 'base.css',
		'dkm-components' => 'components.css',
		'dkm-layout'     => 'layout.css',
		'dkm-home'       => 'home.css',
		'dkm-collection' => 'collection.css',
		'dkm-product'    => 'product.css',
		'dkm-info'       => 'info.css',
		'dkm-cart'       => 'cart.css',
		'dkm-checkout'   => 'checkout.css',
	);
}

add_filter( 'wp_resource_hints', 'dkm_resource_hints', 10, 2 );

function dkm_resource_hints( array $urls, string $relation_type ): array {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href'        => 'https://fonts.googleapis.com',
			'crossorigin' => '',
		);
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}

add_action( 'wp_enqueue_scripts', 'dkm_enqueue_assets' );

function dkm_enqueue_assets(): void {
	wp_enqueue_style(
		'dkm-fonts',
		'https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600&display=swap',
		array(),
		null
	);

	$prev = 'dkm-fonts';
	foreach ( dkm_stylesheets() as $handle => $file ) {
		$path = DKM_DIR . '/assets/css/' . $file;
		if ( ! file_exists( $path ) ) {
			continue;
		}
		wp_enqueue_style( $handle, DKM_URI . '/assets/css/' . $file, array( $prev ), dkm_asset_version( $path ) );
		$prev = $handle;
	}

	$js = DKM_DIR . '/assets/js/main.js';
	if ( file_exists( $js ) ) {
		wp_enqueue_script( 'dkm-main', DKM_URI . '/assets/js/main.js', array(), dkm_asset_version( $js ), true );
		wp_script_add_data( 'dkm-main', 'defer', true );
		wp_localize_script( 'dkm-main', 'DKM', dkm_script_data() );
	}

	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'wc-blocks-style' );
	wp_dequeue_style( 'classic-theme-styles' );
	wp_dequeue_style( 'global-styles' );
}

/**
 * Data the front end needs. Nothing secret, nothing user specific.
 */
function dkm_script_data(): array {
	$data = array(
		'restUrl'   => esc_url_raw( rest_url( 'dkm/v1/' ) ),
		'storeUrl'  => esc_url_raw( rest_url( 'wc/store/v1/' ) ),
		'nonce'     => wp_create_nonce( 'wp_rest' ),
		'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
		'ajaxNonce' => wp_create_nonce( 'dkm_ajax' ),
		'whatsapp'  => dkm_whatsapp_url(),
		'i18n'      => array(
			'added'     => __( 'Toegevoegd aan je winkelwagen', 'dkm' ),
			'removed'   => __( 'Verwijderd', 'dkm' ),
			'error'     => __( 'Dat ging niet goed. Probeer het nog eens.', 'dkm' ),
			'empty'     => __( 'Je winkelwagen is nog leeg.', 'dkm' ),
			'noResults' => __( 'Geen horloges gevonden met deze filters.', 'dkm' ),
		),
	);

	if ( function_exists( 'WC' ) ) {
		$data['cartUrl']     = wc_get_cart_url();
		$data['checkoutUrl'] = wc_get_checkout_url();
	}

	if ( dkm_black_friday_active() ) {
		$data['blackFridayEnds'] = dkm_black_friday_ends();
	}

	return $data;
}

/**
 * File modification time as the asset version, so a deploy busts the cache
 * without anyone bumping a number by hand.
 */
function dkm_asset_version( string $path ): string {
	$mtime = file_exists( $path ) ? filemtime( $path ) : 0;
	return DKM_VERSION . ( $mtime ? '.' . $mtime : '' );
}

add_filter( 'script_loader_tag', 'dkm_defer_scripts', 10, 2 );

function dkm_defer_scripts( string $tag, string $handle ): string {
	if ( 'dkm-main' === $handle && ! str_contains( $tag, ' defer' ) ) {
		return str_replace( ' src=', ' defer src=', $tag );
	}
	return $tag;
}

add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
