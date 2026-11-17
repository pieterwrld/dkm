<?php
/**
 * Cart page
 *
 * Overrides WooCommerce's own template. The line items keep the markup and the
 * hooks WooCommerce's cart script and other plugins depend on, so updating a
 * quantity, removing a line and applying a coupon all still work the standard
 * way. What changes is the layout and the way a Rolex box reads: a box that was
 * bought with a watch is shown underneath that watch instead of as a line of
 * its own.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package DKM
 * @version 11.3.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );

$dkm_lines = function_exists( 'dkm_cart_lines' ) ? dkm_cart_lines() : array();

/**
 * Renders one line item row.
 *
 * @param string $cart_item_key Cart item key.
 * @param array  $cart_item     Cart item.
 * @param bool   $is_box        True when this is a box bound to the watch above it.
 */
$dkm_row = static function ( string $cart_item_key, array $cart_item, bool $is_box = false ): void {
	$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
	$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
	$visible    = apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key );

	if ( ! $_product instanceof WC_Product || ! $_product->exists() || $cart_item['quantity'] <= 0 || ! $visible ) {
		return;
	}

	$cart_item_name    = WC()->cart->get_item_product_name( $cart_item, $_product );
	$product_name      = apply_filters( 'woocommerce_cart_item_name', $cart_item_name, $cart_item, $cart_item_key );
	$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

	$tier  = dkm_product_tier( $_product );
	$model = dkm_attr( $_product, 'pa_model' );
	$size  = dkm_attr( $_product, 'pa_maat' );

	$classes = apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key );
	if ( $is_box ) {
		$classes .= ' dkm-line--box';
	}
	?>
	<tr class="woocommerce-cart-form__cart-item dkm-line <?php echo esc_attr( $classes ); ?>">

		<td class="product-thumbnail dkm-line__thumb">
			<?php
			$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'thumbnail' ), $cart_item, $cart_item_key );
			if ( $is_box || ! $product_permalink ) {
				echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		</td>

		<td role="rowheader" class="product-name dkm-line__name" data-title="<?php esc_attr_e( 'Product', 'dkm' ); ?>">
			<?php if ( $is_box ) : ?>
				<span class="dkm-line__tag"><?php dkm_the_icon( 'box' ); ?><?php esc_html_e( 'Bij je horloge', 'dkm' ); ?></span>
			<?php elseif ( $tier ) : ?>
				<?php echo dkm_tier_badge( $tier ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php endif; ?>

			<span class="dkm-line__title">
				<?php
				$short = dkm_short_title( $_product->get_name() );
				if ( $is_box || ! $product_permalink ) {
					echo esc_html( $short );
				} else {
					printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), esc_html( $short ) );
				}
				?>
			</span>

			<?php if ( ! $is_box && ( $model || $size ) ) : ?>
				<span class="dkm-line__meta">
					<?php echo esc_html( trim( implode( ' · ', array_filter( array( $model, $size ) ) ) ) ); ?>
				</span>
			<?php endif; ?>

			<?php
			do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo wc_get_formatted_cart_item_data( $cart_item, false, $cart_item_name );

			if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
				echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Beschikbaar op nabestelling', 'dkm' ) . '</p>', $product_id, $cart_item, $cart_item_key ) );
			}
			?>

			<?php if ( ! $is_box ) : ?>
				<span class="dkm-line__remove">
					<?php
					echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'woocommerce_cart_item_remove_link',
						sprintf(
							'<a role="button" href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
							esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
							/* translators: %s is the product name. */
							esc_attr( sprintf( __( 'Verwijder %s uit je winkelwagen', 'dkm' ), wp_strip_all_tags( $product_name ) ) ),
							esc_attr( $product_id ),
							esc_attr( $_product->get_sku() ),
							esc_html__( 'Verwijderen', 'dkm' )
						),
						$cart_item_key
					);
					?>
				</span>
			<?php endif; ?>
		</td>

		<td class="product-price dkm-line__price" data-title="<?php esc_attr_e( 'Prijs', 'dkm' ); ?>">
			<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</td>

		<td class="product-quantity dkm-line__qty" data-title="<?php esc_attr_e( 'Aantal', 'dkm' ); ?>">
			<?php
			if ( $_product->is_sold_individually() ) {
				$min_quantity = 1;
				$max_quantity = 1;
			} else {
				$min_quantity = 0;
				$max_quantity = $_product->get_max_purchase_quantity();
			}

			$product_quantity = woocommerce_quantity_input(
				array(
					'input_name'   => "cart[{$cart_item_key}][qty]",
					'input_value'  => $cart_item['quantity'],
					'max_value'    => $max_quantity,
					'min_value'    => $min_quantity,
					'product_name' => $product_name,
					'classes'      => array( 'input-text', 'qty', 'text', 'dkm-qty__input' ),
				),
				$_product,
				false
			);

			// A linked box follows its watch, so its quantity is shown, not set.
			echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</td>

		<td class="product-subtotal dkm-line__sub" data-title="<?php esc_attr_e( 'Subtotaal', 'dkm' ); ?>">
			<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</td>
	</tr>
	<?php
};
?>

<div class="dkm-cartpage">
	<div class="dkm-cartpage__main">
		<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
			<?php do_action( 'woocommerce_before_cart_table' ); ?>

			<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents dkm-cart-table" cellspacing="0">
				<thead>
					<tr>
						<th scope="col" class="product-thumbnail"><span class="screen-reader-text"><?php esc_html_e( 'Afbeelding', 'dkm' ); ?></span></th>
						<th scope="col" class="product-name"><?php esc_html_e( 'Product', 'dkm' ); ?></th>
						<th scope="col" class="product-price"><?php esc_html_e( 'Prijs', 'dkm' ); ?></th>
						<th scope="col" class="product-quantity"><?php esc_html_e( 'Aantal', 'dkm' ); ?></th>
						<th scope="col" class="product-subtotal"><?php esc_html_e( 'Subtotaal', 'dkm' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php do_action( 'woocommerce_before_cart_contents' ); ?>

					<?php
					if ( $dkm_lines ) {
						foreach ( $dkm_lines as $dkm_line ) {
							$dkm_row( $dkm_line['key'], $dkm_line['item'], false );
							if ( ! empty( $dkm_line['child'] ) ) {
								$dkm_row( $dkm_line['child']['key'], $dkm_line['child']['item'], true );
							}
						}
					} else {
						// Fallback for a cart the grouping helper cannot read.
						foreach ( WC()->cart->get_cart() as $dkm_key => $dkm_item ) {
							$dkm_row( $dkm_key, $dkm_item, false );
						}
					}
					?>

					<?php do_action( 'woocommerce_cart_contents' ); ?>

					<tr class="dkm-cart-actions">
						<td colspan="5" class="actions">
							<?php if ( wc_coupons_enabled() ) { ?>
								<div class="coupon dkm-coupon">
									<label for="coupon_code" class="dkm-coupon__label"><?php esc_html_e( 'Kortingscode', 'dkm' ); ?></label>
									<div class="dkm-coupon__row">
										<input type="text" name="coupon_code" class="input-text dkm-coupon__input" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Vul je code in', 'dkm' ); ?>" />
										<button type="submit" class="btn btn--o dkm-coupon__btn" name="apply_coupon" value="<?php esc_attr_e( 'Toepassen', 'dkm' ); ?>"><?php esc_html_e( 'Toepassen', 'dkm' ); ?></button>
									</div>
									<?php do_action( 'woocommerce_cart_coupon' ); ?>
								</div>
							<?php } ?>

							<button type="submit" class="btn btn--o dkm-cart-actions__update" name="update_cart" value="<?php esc_attr_e( 'Winkelwagen bijwerken', 'dkm' ); ?>"><?php esc_html_e( 'Winkelwagen bijwerken', 'dkm' ); ?></button>

							<?php do_action( 'woocommerce_cart_actions' ); ?>

							<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
						</td>
					</tr>

					<?php do_action( 'woocommerce_after_cart_contents' ); ?>
				</tbody>
			</table>

			<?php do_action( 'woocommerce_after_cart_table' ); ?>
		</form>

		<?php
		// The box upsell ships in the DKM Core plugin, so the cart still renders
		// when that plugin is switched off.
		$dkm_box_support = function_exists( 'dkm_boxes' ) && function_exists( 'dkm_takes_box' ) && function_exists( 'dkm_cart_has_box' );
		$dkm_boxes       = $dkm_box_support ? dkm_boxes() : array();

		$dkm_takes_a_box = false;
		if ( $dkm_box_support ) {
			foreach ( $dkm_lines as $dkm_line ) {
				if ( dkm_takes_box( $dkm_line['product'] ) ) {
					$dkm_takes_a_box = true;
					break;
				}
			}
		}

		if ( $dkm_boxes && $dkm_takes_a_box && ! dkm_cart_has_box() ) :
			?>
			<div class="cart-up dkm-cartpage__boxes">
				<span class="eyebrow"><?php esc_html_e( 'Maak het compleet', 'dkm' ); ?></span>
				<p class="dkm-cartpage__boxes-lead"><?php esc_html_e( 'Een originele Rolex doos maakt het af, en is het verschil tussen een horloge en een cadeau.', 'dkm' ); ?></p>

				<?php foreach ( $dkm_boxes as $dkm_box ) : ?>
					<div class="cart-up__row">
						<?php
						$dkm_box_thumb = (int) $dkm_box->get_image_id();
						if ( $dkm_box_thumb ) {
							echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								$dkm_box_thumb,
								'thumbnail',
								false,
								array(
									'alt'     => $dkm_box->get_name(),
									'loading' => 'lazy',
								)
							);
						}
						?>
						<div class="cart-up__t">
							<b><?php echo esc_html( $dkm_box->get_name() ); ?></b>
							<span><?php echo wp_kses_post( $dkm_box->get_price_html() ); ?></span>
						</div>
						<button type="button" class="cart-up__add" data-dkm-add-box="<?php echo esc_attr( (string) $dkm_box->get_id() ); ?>">
							<?php esc_html_e( 'Toevoegen', 'dkm' ); ?>
						</button>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="dkm-cartpage__side">
		<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

		<div class="cart-collaterals">
			<?php
			/**
			 * Cart collaterals hook.
			 *
			 * @hooked woocommerce_cross_sell_display
			 * @hooked woocommerce_cart_totals - 10
			 */
			do_action( 'woocommerce_cart_collaterals' );
			?>
		</div>
	</div>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
