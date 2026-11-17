<?php
/**
 * Checkout coupon form
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package DKM
 * @version 9.8.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) {
	return;
}
?>
<div class="woocommerce-form-coupon-toggle co__coupon-toggle">
	<?php
	wc_print_notice(
		apply_filters(
			'woocommerce_checkout_coupon_message',
			esc_html__( 'Heb je een kortingscode?', 'dkm' ) . ' <a href="#" role="button" aria-label="' . esc_attr__( 'Vul je kortingscode in', 'dkm' ) . '" aria-controls="woocommerce-checkout-form-coupon" aria-expanded="false" class="showcoupon">' . esc_html__( 'Klik hier om hem in te vullen', 'dkm' ) . '</a>'
		),
		'notice'
	);
	?>
</div>

<form class="checkout_coupon woocommerce-form-coupon co__coupon" method="post" style="display:none" id="woocommerce-checkout-form-coupon">

	<p class="form-row form-row-first">
		<label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Kortingscode', 'dkm' ); ?></label>
		<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Kortingscode', 'dkm' ); ?>" id="coupon_code" value="" />
	</p>

	<p class="form-row form-row-last">
		<button type="submit" class="btn btn--o button" name="apply_coupon" value="<?php esc_attr_e( 'Toepassen', 'dkm' ); ?>">
			<?php esc_html_e( 'Toepassen', 'dkm' ); ?>
		</button>
	</p>

	<div class="clear"></div>
</form>
