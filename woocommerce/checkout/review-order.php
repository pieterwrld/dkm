<?php
/**
 * Review order table
 *
 * A Rolex box is shown indented under the watch it belongs to, because it is a
 * real order line that travels with that watch rather than a separate purchase.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package DKM
 * @version 11.2.2
 */

defined( 'ABSPATH' ) || exit;

/**
 * Renders one line, optionally as the box that belongs to the line above it.
 *
 * @param string $cart_item_key Cart item key.
 * @param array  $cart_item     Cart item.
 * @param bool   $is_child      True for a box tied to a watch.
 */
$dkm_review_row = static function ( $cart_item_key, $cart_item, $is_child = false ) {
	$product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

	/** This filter is documented in woocommerce/templates/checkout/review-order.php */
	$visible = apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key );

	if ( ! $product instanceof WC_Product || ! $product->exists() || $cart_item['quantity'] <= 0 || ! $visible ) {
		return;
	}

	$cart_item_name = WC()->cart->get_item_product_name( $cart_item, $product );

	/** This filter is documented in woocommerce/templates/cart/cart.php */
	$product_name = apply_filters( 'woocommerce_cart_item_name', $cart_item_name, $cart_item, $cart_item_key );

	$classes = apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key );
	if ( $is_child ) {
		$classes .= ' cart_item--child';
	}
	?>
	<tr class="<?php echo esc_attr( $classes ); ?>">
		<td class="product-name">
			<div class="co__line">
				<?php
				// The box has a photograph of its own, and it is the thing being
				// bought, so it is shown like any other line. The drawn box is
				// only for a line that has no picture at all.
				$thumb_id = (int) $product->get_image_id();
				if ( $thumb_id ) {
					echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						$thumb_id,
						'thumbnail',
						false,
						array(
							'class'   => 'co__thumb' . ( $is_child ? ' co__thumb--child' : '' ),
							'alt'     => wp_strip_all_tags( $product_name ),
							'loading' => 'lazy',
						)
					);
				} else {
					echo '<span class="co__thumb co__thumb--box" aria-hidden="true">' . dkm_icon( 'box' ) . '</span>';
				}
				?>

				<div class="co__line-t">
					<span class="co__name"><?php echo wp_kses_post( $product_name ); ?></span>

					<?php
					echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, WooCommerce.Commenting.CommentHooks.MissingHookComment
						'woocommerce_checkout_cart_item_quantity',
						' <span class="product-quantity">' . sprintf( esc_html__( 'Aantal: %s', 'dkm' ), esc_html( $cart_item['quantity'] ) ) . '</span>',
						$cart_item,
						$cart_item_key
					);
					?>

					<?php echo wc_get_formatted_cart_item_data( $cart_item, false, $cart_item_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</td>
		<td class="product-total">
			<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</td>
	</tr>
	<?php
};
?>
<table class="shop_table woocommerce-checkout-review-order-table">
	<thead>
		<tr>
			<th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
			<th class="product-total"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );

		foreach ( dkm_cart_lines() as $line ) {
			$dkm_review_row( $line['key'], $line['item'], false );

			if ( ! empty( $line['child'] ) ) {
				$dkm_review_row( $line['child']['key'], $line['child']['item'], true );
			}
		}

		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
	</tbody>
	<tfoot>

		<tr class="cart-subtotal">
			<th><?php esc_html_e( 'Subtotaal', 'dkm' ); ?></th>
			<td><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<tr class="order-total">
			<th><?php esc_html_e( 'Totaal', 'dkm' ); ?></th>
			<td><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

	</tfoot>
</table>
