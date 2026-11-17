<?php
/**
 * Mini cart
 *
 * Kept deliberately close to template-parts/cart-drawer-body.php, because the
 * slide-out drawer is what shoppers actually see; this template only appears
 * where a widget or a block asks WooCommerce for the mini cart.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package DKM
 * @version 9.9.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_mini_cart' );

$dkm_lines = function_exists( 'dkm_cart_lines' ) ? dkm_cart_lines() : array();
?>

<?php if ( ! WC()->cart->is_empty() ) : ?>

	<ul class="woocommerce-mini-cart cart_list product_list_widget dkm-mini">
		<?php do_action( 'woocommerce_before_mini_cart_contents' ); ?>

		<?php foreach ( $dkm_lines as $dkm_line ) : ?>
			<?php
			$product   = $dkm_line['product'];
			$item      = $dkm_line['item'];
			$child     = $dkm_line['child'];
			$name      = dkm_short_title( $product->get_name() );
			$tier      = dkm_product_tier( $product );
			$permalink = $product->is_visible() ? $product->get_permalink( $item ) : '';
			$thumb_id  = (int) $product->get_image_id();
			?>
			<li class="woocommerce-mini-cart-item mini_cart_item cart-row">
				<?php
				if ( $thumb_id ) {
					echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						$thumb_id,
						'thumbnail',
						false,
						array(
							'class'   => 'cart-row__img',
							'alt'     => $name,
							'loading' => 'lazy',
						)
					);
				}
				?>

				<div class="cart-row__t">
					<b>
						<?php if ( $permalink ) : ?>
							<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $name ); ?></a>
						<?php else : ?>
							<?php echo esc_html( $name ); ?>
						<?php endif; ?>
					</b>

					<span class="cart-row__meta">
						<?php
						printf(
							/* translators: %1$s is a tier name, %2$d a quantity. */
							esc_html__( '%1$s · %2$d stuk', 'dkm' ),
							esc_html( $tier ? $tier['name'] : __( 'Accessoire', 'dkm' ) ),
							(int) $item['quantity']
						);
						?>
					</span>

					<?php
					$box = $child ? ( $child['item']['data'] ?? null ) : null;
					if ( $box instanceof WC_Product ) :
						?>
						<span class="cart-row__box">
							<?php dkm_the_icon( 'box' ); ?><?php echo esc_html( $box->get_name() ); ?>
						</span>
					<?php endif; ?>

					<?php
					echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'woocommerce_cart_item_remove_link',
						sprintf(
							'<a href="%s" class="remove remove_from_cart_button cart-row__x" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">%s</a>',
							esc_url( wc_get_cart_remove_url( $dkm_line['key'] ) ),
							/* translators: %s is the product name. */
							esc_attr( sprintf( __( 'Verwijder %s uit je winkelwagen', 'dkm' ), wp_strip_all_tags( $name ) ) ),
							esc_attr( $product->get_id() ),
							esc_attr( $dkm_line['key'] ),
							esc_attr( $product->get_sku() ),
							esc_html__( 'Verwijderen', 'dkm' )
						),
						$dkm_line['key']
					);
					?>
				</div>

				<span class="cart-row__p">
					<?php
					$total = (float) $item['line_subtotal'] + (float) $item['line_subtotal_tax'];
					if ( $child ) {
						$total += (float) $child['item']['line_subtotal'] + (float) $child['item']['line_subtotal_tax'];
					}
					echo wp_kses_post( wc_price( $total ) );
					?>
				</span>
			</li>
		<?php endforeach; ?>

		<?php do_action( 'woocommerce_mini_cart_contents' ); ?>
	</ul>

	<p class="woocommerce-mini-cart__total total dkm-mini__total">
		<?php do_action( 'woocommerce_widget_shopping_cart_total' ); ?>
	</p>

	<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

	<p class="woocommerce-mini-cart__buttons buttons dkm-mini__buttons">
		<?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?>
	</p>

<?php else : ?>

	<div class="woocommerce-mini-cart__empty-message cart-empty">
		<?php dkm_the_icon( 'cart' ); ?>
		<p><?php esc_html_e( 'Je winkelwagen is nog leeg.', 'dkm' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>
