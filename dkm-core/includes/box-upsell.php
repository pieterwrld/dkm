<?php
/**
 * The Rolex box upsell
 *
 * A chosen box is added to the cart as its own line, linked to the watch it
 * belongs to. That keeps it a real orderable product with its own stock and
 * its own line on the order, instead of a surcharge hidden in the watch price.
 * Remove the watch and its box leaves with it.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

const DKM_BOX_CAT      = 'rolex-dozen';
const DKM_BOX_PARENT    = 'dkm_box_parent';
const DKM_BOX_REQUESTED = 'dkm_box';

/**
 * The boxes on offer, cheapest first.
 *
 * @return WC_Product[]
 */
function dkm_boxes(): array {
	static $boxes = null;
	if ( null !== $boxes ) {
		return $boxes;
	}

	$ids = get_posts(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 10,
			'fields'         => 'ids',
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => DKM_BOX_CAT,
				),
			),
		)
	);

	$boxes = array();
	foreach ( $ids as $id ) {
		$product = wc_get_product( $id );
		if ( $product && $product->is_purchasable() ) {
			$boxes[] = $product;
		}
	}

	usort( $boxes, fn( $a, $b ) => (float) $a->get_price() <=> (float) $b->get_price() );

	return $boxes;
}

/**
 * Whether a product is one of the boxes.
 */
function dkm_is_box( $product ): bool {
	$product = $product instanceof WC_Product ? $product : wc_get_product( $product );
	if ( ! $product ) {
		return false;
	}
	return has_term( DKM_BOX_CAT, 'product_cat', $product->get_id() );
}

/**
 * Whether a product can be sold with a box, which is every watch.
 */
function dkm_takes_box( WC_Product $product ): bool {
	if ( dkm_is_box( $product ) ) {
		return false;
	}
	foreach ( array( 'brillen', 'accessoires' ) as $slug ) {
		if ( has_term( $slug, 'product_cat', $product->get_id() ) ) {
			return false;
		}
	}
	return (bool) dkm_boxes();
}

/* ==========================================================================
   Adding
   ========================================================================== */

add_action( 'woocommerce_add_to_cart', 'dkm_add_requested_box', 20, 6 );

/**
 * Adds the chosen box right after the watch it was chosen for.
 *
 * @param string $cart_item_key Key of the line just added.
 * @param int    $product_id    Product added.
 * @param int    $quantity      Quantity added.
 */
function dkm_add_requested_box( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ): void {
	if ( ! empty( $cart_item_data[ DKM_BOX_PARENT ] ) ) {
		return; // This is the box itself.
	}

	$requested = isset( $_REQUEST[ DKM_BOX_REQUESTED ] ) ? absint( wp_unslash( $_REQUEST[ DKM_BOX_REQUESTED ] ) ) : 0;
	if ( ! $requested ) {
		return;
	}

	dkm_attach_box( $cart_item_key, $requested, (int) $quantity );
}

/**
 * Puts a box in the cart linked to a parent line.
 *
 * @param string $parent_key Cart item key of the watch.
 * @param int    $box_id     Box product id.
 * @param int    $quantity   How many.
 * @return string|false New cart item key.
 */
function dkm_attach_box( string $parent_key, int $box_id, int $quantity = 1 ) {
	if ( ! dkm_is_box( $box_id ) ) {
		return false;
	}

	$cart = WC()->cart;
	if ( ! $cart || ! isset( $cart->get_cart()[ $parent_key ] ) ) {
		return false;
	}

	// One box per watch line.
	if ( dkm_find_child_key( $parent_key ) ) {
		return false;
	}

	return $cart->add_to_cart(
		$box_id,
		max( 1, $quantity ),
		0,
		array(),
		array( DKM_BOX_PARENT => $parent_key )
	);
}

/**
 * The box line that belongs to a watch line, if any.
 *
 * @return string|null Cart item key.
 */
function dkm_find_child_key( string $parent_key ): ?string {
	$cart = WC()->cart;
	if ( ! $cart ) {
		return null;
	}
	foreach ( $cart->get_cart() as $key => $item ) {
		if ( ! empty( $item[ DKM_BOX_PARENT ] ) && $item[ DKM_BOX_PARENT ] === $parent_key ) {
			return $key;
		}
	}
	return null;
}

/**
 * True when the cart holds any box at all, which is what the drawer checks
 * before offering one.
 */
function dkm_cart_has_box(): bool {
	$cart = WC()->cart;
	if ( ! $cart ) {
		return false;
	}
	foreach ( $cart->get_cart() as $item ) {
		if ( dkm_is_box( $item['product_id'] ) ) {
			return true;
		}
	}
	return false;
}

/* ==========================================================================
   Keeping parent and box in step
   ========================================================================== */

add_action( 'woocommerce_cart_item_removed', 'dkm_remove_orphaned_box', 10, 2 );

/**
 * Takes the box out when its watch is removed.
 *
 * @param string  $removed_key Key that was removed.
 * @param WC_Cart $cart        The cart.
 */
function dkm_remove_orphaned_box( $removed_key, $cart ): void {
	foreach ( $cart->get_cart() as $key => $item ) {
		if ( ! empty( $item[ DKM_BOX_PARENT ] ) && $item[ DKM_BOX_PARENT ] === $removed_key ) {
			$cart->remove_cart_item( $key );
		}
	}
}

add_action( 'woocommerce_after_cart_item_quantity_update', 'dkm_sync_box_quantity', 10, 4 );

/**
 * A box per watch, so the quantities stay equal.
 */
function dkm_sync_box_quantity( $cart_item_key, $quantity, $old_quantity, $cart ): void {
	$child = dkm_find_child_key( $cart_item_key );
	if ( $child ) {
		$cart->set_quantity( $child, $quantity, false );
	}
}

add_filter( 'woocommerce_cart_item_quantity', 'dkm_lock_box_quantity', 10, 3 );

/**
 * The box quantity follows its watch, so it is shown rather than editable.
 */
function dkm_lock_box_quantity( $html, $cart_item_key, $cart_item ) {
	if ( empty( $cart_item[ DKM_BOX_PARENT ] ) ) {
		return $html;
	}
	return '<span class="qty-fixed">' . esc_html( (string) $cart_item['quantity'] ) . '</span>';
}

/* ==========================================================================
   How it reads in the cart and on the order
   ========================================================================== */

add_filter( 'woocommerce_get_item_data', 'dkm_box_item_data', 10, 2 );

/**
 * Notes on the box line which watch it belongs to.
 */
function dkm_box_item_data( array $item_data, array $cart_item ): array {
	if ( empty( $cart_item[ DKM_BOX_PARENT ] ) ) {
		return $item_data;
	}

	$cart   = WC()->cart;
	$parent = $cart ? ( $cart->get_cart()[ $cart_item[ DKM_BOX_PARENT ] ] ?? null ) : null;
	if ( ! $parent ) {
		return $item_data;
	}

	$product = $parent['data'] ?? null;
	if ( ! $product instanceof WC_Product ) {
		return $item_data;
	}

	$item_data[] = array(
		'key'   => __( 'Hoort bij', 'dkm' ),
		'value' => esc_html( dkm_short_title( $product->get_name() ) ),
	);

	return $item_data;
}

add_action( 'woocommerce_checkout_create_order_line_item', 'dkm_box_order_meta', 10, 4 );

/**
 * Carries the link through onto the order, so the packer can see what belongs
 * together.
 */
function dkm_box_order_meta( $item, $cart_item_key, $values, $order ): void {
	if ( empty( $values[ DKM_BOX_PARENT ] ) ) {
		return;
	}

	$cart   = WC()->cart;
	$parent = $cart ? ( $cart->get_cart()[ $values[ DKM_BOX_PARENT ] ] ?? null ) : null;
	$name   = '';
	if ( $parent && ( $parent['data'] ?? null ) instanceof WC_Product ) {
		$name = dkm_short_title( $parent['data']->get_name() );
	}

	$item->add_meta_data( __( 'Hoort bij', 'dkm' ), $name ?: __( 'een horloge in deze bestelling', 'dkm' ), true );
}

/**
 * Two cart lines for the same box but different watches must not be merged.
 */
add_filter( 'woocommerce_add_to_cart_validation', 'dkm_validate_box_add', 10, 3 );

function dkm_validate_box_add( bool $passed, int $product_id, int $quantity ): bool {
	return $passed;
}
