<?php
/**
 * Cart drawer shell. The contents are swapped out over AJAX.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
	return;
}
?>
<aside class="cart-drawer"
	id="cart-drawer"
	role="dialog"
	aria-modal="true"
	aria-label="<?php esc_attr_e( 'Winkelwagen', 'dkm' ); ?>"
	hidden>

	<div class="cart-drawer__h">
		<b><?php esc_html_e( 'Jouw winkelwagen', 'dkm' ); ?></b>
		<button type="button" class="iconbtn" data-dkm-close="cart">
			<?php dkm_the_icon( 'close', '', __( 'Winkelwagen sluiten', 'dkm' ) ); ?>
		</button>
	</div>

	<div class="cart-drawer__body" data-dkm-cart-body>
		<?php get_template_part( 'template-parts/cart-drawer-body' ); ?>
	</div>

	<div class="cart-drawer__f">
		<div class="cart-drawer__tot">
			<span><?php esc_html_e( 'Totaal', 'dkm' ); ?></span>
			<b data-dkm-cart-total><?php echo wp_kses_post( WC()->cart->get_cart_total() ); ?></b>
		</div>
		<a class="btn btn--g btn--full btn--lg" href="<?php echo esc_url( wc_get_checkout_url() ); ?>">
			<?php esc_html_e( 'Afrekenen', 'dkm' ); ?>
		</a>
		<a class="btn btn--o btn--full" style="margin-top:8px" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
			<?php esc_html_e( 'Winkelwagen bekijken', 'dkm' ); ?>
		</a>
	</div>
</aside>
