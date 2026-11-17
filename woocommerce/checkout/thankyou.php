<?php
/**
 * Thank you page
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package DKM
 * @version 11.2.0
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order ty">

	<?php
	if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<?php
			$default_order_failed_text = esc_html__( 'De betaling is niet gelukt. Je bank of de betaaldienst heeft hem geweigerd. Probeer het nog een keer, of stuur ons een bericht.', 'dkm' );

			/** This filter is documented in woocommerce/templates/checkout/thankyou.php */
			$order_failed_text = apply_filters( 'woocommerce_thankyou_order_failed_text', $default_order_failed_text, $order );
			$order_failed_text = is_string( $order_failed_text ) ? $order_failed_text : $default_order_failed_text;
			?>

			<div class="ty__head ty__head--failed">
				<span class="ty__mark ty__mark--failed" aria-hidden="true"><?php dkm_the_icon( 'close' ); ?></span>
				<h1 class="h-l"><?php esc_html_e( 'De betaling is niet gelukt', 'dkm' ); ?></h1>
				<p class="lead woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php echo wp_kses_post( $order_failed_text ); ?></p>
			</div>

			<p class="ty__acts woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="btn btn--g btn--lg pay"><?php esc_html_e( 'Opnieuw betalen', 'dkm' ); ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn btn--o btn--lg"><?php esc_html_e( 'Naar mijn account', 'dkm' ); ?></a>
				<?php endif; ?>
			</p>

		<?php else : ?>

			<div class="ty__head">
				<span class="ty__mark gr" aria-hidden="true"><?php dkm_the_icon( 'check' ); ?></span>
				<span class="eyebrow"><?php esc_html_e( 'Bedankt voor je bestelling', 'dkm' ); ?></span>
				<h1 class="h-l">
					<?php esc_html_e( 'Je horloge is', 'dkm' ); ?>
					<span class="em gr-txt"><?php esc_html_e( 'onderweg', 'dkm' ); ?></span>
				</h1>
				<p class="lead">
					<?php esc_html_e( 'Je krijgt een bevestiging per mail. Bestel je op een werkdag voor 17:00, dan gaat hij vandaag nog de deur uit.', 'dkm' ); ?>
				</p>
			</div>

			<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details ty__facts">

				<li class="woocommerce-order-overview__order order">
					<span><?php esc_html_e( 'Bestelnummer', 'dkm' ); ?></span>
					<strong><?php echo esc_html( $order->get_order_number() ); ?></strong>
				</li>

				<li class="woocommerce-order-overview__date date">
					<span><?php esc_html_e( 'Datum', 'dkm' ); ?></span>
					<strong><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></strong>
				</li>

				<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
					<li class="woocommerce-order-overview__email email">
						<span><?php esc_html_e( 'E-mail', 'dkm' ); ?></span>
						<strong><?php echo esc_html( $order->get_billing_email() ); ?></strong>
					</li>
				<?php endif; ?>

				<li class="woocommerce-order-overview__total total">
					<span><?php esc_html_e( 'Totaal', 'dkm' ); ?></span>
					<strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
				</li>

				<?php if ( $order->get_payment_method_title() ) : ?>
					<li class="woocommerce-order-overview__payment-method method">
						<span><?php esc_html_e( 'Betaalmethode', 'dkm' ); ?></span>
						<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
					</li>
				<?php endif; ?>

			</ul>

			<?php
			$dkm_wa = dkm_whatsapp_url(
				sprintf(
					/* translators: %s is an order number. */
					__( 'Hoi! Ik heb een vraag over bestelling %s.', 'dkm' ),
					$order->get_order_number()
				)
			);
			?>
			<?php if ( $dkm_wa ) : ?>
				<div class="ty__wa">
					<div>
						<b><?php esc_html_e( 'Een vraag over je bestelling?', 'dkm' ); ?></b>
						<span><?php esc_html_e( 'Stuur ons gerust een bericht, we reageren snel.', 'dkm' ); ?></span>
					</div>
					<a class="btn btn--o" href="<?php echo esc_url( $dkm_wa ); ?>" target="_blank" rel="noopener">
						<?php dkm_the_icon( 'wa' ); ?><?php esc_html_e( 'Vraag via WhatsApp', 'dkm' ); ?>
					</a>
				</div>
			<?php endif; ?>

		<?php endif; ?>

		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

	<?php else : ?>

		<?php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); ?>

	<?php endif; ?>

</div>
