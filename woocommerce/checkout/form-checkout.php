<?php
/**
 * Checkout form
 *
 * Two columns on desktop with the order beside the form, one column on a
 * phone. Every hook the default template fires is kept, and the payment
 * gateways still render through woocommerce_checkout_payment() inside
 * #order_review, so iDEAL and Mollie hook in exactly as they expect.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package DKM
 * @version 9.4.0
 *
 * @var WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}
?>

<form name="checkout"
	method="post"
	class="checkout woocommerce-checkout co"
	action="<?php echo esc_url( wc_get_checkout_url() ); ?>"
	enctype="multipart/form-data"
	aria-label="<?php echo esc_attr__( 'Checkout', 'woocommerce' ); ?>">

	<div class="co__main">
		<?php if ( $checkout->get_checkout_fields() ) : ?>

			<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

			<div class="col2-set co__fields" id="customer_details">
				<div class="col-1">
					<?php do_action( 'woocommerce_checkout_billing' ); ?>
				</div>

				<div class="col-2">
					<?php do_action( 'woocommerce_checkout_shipping' ); ?>
				</div>
			</div>

			<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

		<?php endif; ?>
	</div>

	<aside class="co__side">
		<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

		<div class="co__review-head">
			<span class="eyebrow"><?php esc_html_e( 'Jouw bestelling', 'dkm' ); ?></span>
			<h2 id="order_review_heading" class="h-m"><?php esc_html_e( 'Dit ga je afrekenen', 'dkm' ); ?></h2>
		</div>

		<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

		<div id="order_review" class="woocommerce-checkout-review-order">
			<?php do_action( 'woocommerce_checkout_order_review' ); ?>
		</div>

		<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

		<ul class="co__assure">
			<li><?php dkm_the_icon( 'shield' ); ?><?php esc_html_e( 'Gecontroleerd voor verzending', 'dkm' ); ?></li>
			<li><?php dkm_the_icon( 'truck' ); ?><?php esc_html_e( 'Vandaag besteld, morgen in huis', 'dkm' ); ?></li>
			<li><?php dkm_the_icon( 'refresh' ); ?><?php esc_html_e( '14 dagen omruilgarantie', 'dkm' ); ?></li>
		</ul>
	</aside>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
