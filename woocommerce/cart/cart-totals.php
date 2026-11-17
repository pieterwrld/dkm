<?php
/**
 * Cart totals
 *
 * A sticky summary card. No VAT line and no payment icons: prices in this shop
 * are what the customer pays, and the design does not carry card logos.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package DKM
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="cart_totals dkm-totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<h2 class="dkm-totals__h"><?php esc_html_e( 'Overzicht', 'dkm' ); ?></h2>

	<table cellspacing="0" class="shop_table shop_table_responsive dkm-totals__table">

		<tr class="cart-subtotal">
			<th><?php esc_html_e( 'Subtotaal', 'dkm' ); ?></th>
			<td data-title="<?php esc_attr_e( 'Subtotaal', 'dkm' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

		<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

			<tr class="shipping">
				<th><?php esc_html_e( 'Verzending', 'dkm' ); ?></th>
				<td data-title="<?php esc_attr_e( 'Verzending', 'dkm' ); ?>"><?php woocommerce_shipping_calculator(); ?></td>
			</tr>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<tr class="order-total">
			<th><?php esc_html_e( 'Totaal', 'dkm' ); ?></th>
			<td data-title="<?php esc_attr_e( 'Totaal', 'dkm' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

	</table>

	<div class="wc-proceed-to-checkout dkm-totals__go">
		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	</div>

	<ul class="dkm-totals__usp">
		<li><?php dkm_the_icon( 'truck' ); ?><?php esc_html_e( 'Vandaag besteld, morgen in huis', 'dkm' ); ?></li>
		<li><?php dkm_the_icon( 'refresh' ); ?><?php esc_html_e( '14 dagen omruilgarantie', 'dkm' ); ?></li>
		<li><?php dkm_the_icon( 'box' ); ?><?php esc_html_e( 'Discreet verpakt verzonden', 'dkm' ); ?></li>
	</ul>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
