<?php
/**
 * Cart drawer and its AJAX endpoints
 *
 * Every action runs through WooCommerce's own cart, so what the drawer shows is
 * what the order will contain.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_ajax_dkm_cart', 'dkm_ajax_cart' );
add_action( 'wp_ajax_nopriv_dkm_cart', 'dkm_ajax_cart' );

/**
 * One endpoint for every cart action, so the drawer always gets a full, fresh
 * payload back and never has to guess at state.
 */
function dkm_ajax_cart(): void {
	check_ajax_referer( 'dkm_ajax', 'nonce' );

	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		wp_send_json_error( array( 'message' => __( 'De winkelwagen is nog niet beschikbaar.', 'dkm' ) ), 503 );
	}

	$action = isset( $_POST['task'] ) ? sanitize_key( wp_unslash( $_POST['task'] ) ) : 'get';

	switch ( $action ) {
		case 'add':
			dkm_cart_add();
			break;
		case 'add_box':
			dkm_cart_add_box();
			break;
		case 'remove':
			dkm_cart_remove();
			break;
		case 'quantity':
			dkm_cart_quantity();
			break;
		case 'get':
		default:
			break;
	}

	wp_send_json_success( dkm_cart_payload() );
}

/**
 * Adds a product, and its chosen box when there is one.
 */
function dkm_cart_add(): void {
	$product_id = isset( $_POST['product_id'] ) ? absint( wp_unslash( $_POST['product_id'] ) ) : 0;
	$quantity   = isset( $_POST['quantity'] ) ? max( 1, absint( wp_unslash( $_POST['quantity'] ) ) ) : 1;
	$box_id     = isset( $_POST['box_id'] ) ? absint( wp_unslash( $_POST['box_id'] ) ) : 0;

	$product = $product_id ? wc_get_product( $product_id ) : null;
	if ( ! $product || ! $product->is_purchasable() || ! $product->is_in_stock() ) {
		wp_send_json_error(
			array( 'message' => __( 'Dit horloge is op dit moment niet te bestellen.', 'dkm' ) ),
			400
		);
	}

	$key = WC()->cart->add_to_cart( $product_id, $quantity );
	if ( ! $key ) {
		wp_send_json_error( array( 'message' => dkm_first_wc_notice() ), 400 );
	}

	if ( $box_id ) {
		if ( function_exists( 'dkm_attach_box' ) ) {
			dkm_attach_box( $key, $box_id, $quantity );
		}
	}
}

/**
 * Adds a box from the drawer, linked to the first watch that has none yet.
 */
function dkm_cart_add_box(): void {
	$box_id = isset( $_POST['box_id'] ) ? absint( wp_unslash( $_POST['box_id'] ) ) : 0;
	if ( ! $box_id || ! function_exists( 'dkm_is_box' ) || ! dkm_is_box( $box_id ) ) {
		wp_send_json_error( array( 'message' => __( 'Deze doos bestaat niet.', 'dkm' ) ), 400 );
	}

	foreach ( WC()->cart->get_cart() as $key => $item ) {
		$product = $item['data'] ?? null;
		if ( ! $product instanceof WC_Product ) {
			continue;
		}
		if ( ! empty( $item[ DKM_BOX_PARENT ] ) || ! dkm_takes_box( $product ) ) {
			continue;
		}
		if ( dkm_find_child_key( $key ) ) {
			continue;
		}
		if ( dkm_attach_box( $key, $box_id, (int) $item['quantity'] ) ) {
			return;
		}
	}

	// No watch to hang it on, so it goes in on its own.
	if ( ! WC()->cart->add_to_cart( $box_id, 1 ) ) {
		wp_send_json_error( array( 'message' => dkm_first_wc_notice() ), 400 );
	}
}

/**
 * Removes a line.
 */
function dkm_cart_remove(): void {
	$key = isset( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';
	if ( '' === $key || ! isset( WC()->cart->get_cart()[ $key ] ) ) {
		wp_send_json_error( array( 'message' => __( 'Deze regel staat niet in je winkelwagen.', 'dkm' ) ), 400 );
	}
	WC()->cart->remove_cart_item( $key );
}

/**
 * Changes a quantity. Zero removes the line.
 */
function dkm_cart_quantity(): void {
	$key      = isset( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';
	$quantity = isset( $_POST['quantity'] ) ? max( 0, absint( wp_unslash( $_POST['quantity'] ) ) ) : 1;

	if ( '' === $key || ! isset( WC()->cart->get_cart()[ $key ] ) ) {
		wp_send_json_error( array( 'message' => __( 'Deze regel staat niet in je winkelwagen.', 'dkm' ) ), 400 );
	}

	if ( 0 === $quantity ) {
		WC()->cart->remove_cart_item( $key );
		return;
	}

	WC()->cart->set_quantity( $key, $quantity, true );
}

/**
 * Everything the front end needs after a cart change.
 */
function dkm_cart_payload(): array {
	WC()->cart->calculate_totals();

	ob_start();
	get_template_part( 'template-parts/cart-drawer-body' );
	$body = (string) ob_get_clean();

	return array(
		'count' => (int) WC()->cart->get_cart_contents_count(),
		'total' => wp_strip_all_tags( WC()->cart->get_cart_total() ),
		'body'  => $body,
		'hasBox' => function_exists( 'dkm_cart_has_box' ) && dkm_cart_has_box(),
	);
}

/**
 * The first WooCommerce error notice as plain text, so a failed add says why.
 */
function dkm_first_wc_notice(): string {
	$notices = function_exists( 'wc_get_notices' ) ? wc_get_notices( 'error' ) : array();
	if ( function_exists( 'wc_clear_notices' ) ) {
		wc_clear_notices();
	}
	if ( ! $notices ) {
		return __( 'Dat ging niet goed. Probeer het nog eens.', 'dkm' );
	}
	$first = reset( $notices );
	return wp_strip_all_tags( is_array( $first ) ? ( $first['notice'] ?? '' ) : (string) $first );
}

/**
 * The lines the drawer renders: watches with their box folded in underneath.
 *
 * @return array<int,array{key:string,item:array,product:WC_Product,child:?array}>
 */
function dkm_cart_lines(): array {
	$cart = WC()->cart;
	if ( ! $cart ) {
		return array();
	}

	$raw      = $cart->get_cart();
	$children = array();
	foreach ( $raw as $key => $item ) {
		if ( ! empty( $item[ DKM_BOX_PARENT ] ) ) {
			$children[ $item[ DKM_BOX_PARENT ] ] = array( 'key' => $key, 'item' => $item );
		}
	}

	$lines = array();
	foreach ( $raw as $key => $item ) {
		if ( ! empty( $item[ DKM_BOX_PARENT ] ) ) {
			continue;
		}
		$product = $item['data'] ?? null;
		if ( ! $product instanceof WC_Product ) {
			continue;
		}
		$lines[] = array(
			'key'     => $key,
			'item'    => $item,
			'product' => $product,
			'child'   => $children[ $key ] ?? null,
		);
	}

	return $lines;
}
