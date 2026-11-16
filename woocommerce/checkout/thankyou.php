<?php
/**
 * Thankyou page
 *
 * @package DKM
 * @see     https://woocommerce.com/document/template-structure/
 */

defined('ABSPATH') || exit;
?>

<div class="woocommerce-order">
    <?php if ($order) :
        do_action('woocommerce_before_thankyou', $order->get_id());
    ?>

        <?php if ($order->has_status('failed')) : ?>
            <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
                <?php esc_html_e('Helaas is je betaling niet gelukt. Probeer het opnieuw of kies een andere betaalmethode.', 'dkm'); ?>
            </p>
            <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
                <a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="button pay btn btn--gold"><?php esc_html_e('Opnieuw betalen', 'dkm'); ?></a>
            </p>
        <?php else : ?>
            <div class="dkm-thankyou">
                <div class="dkm-thankyou__icon">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--dkm-success, #4ade80)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <h2 class="dkm-thankyou__title"><?php esc_html_e('Bedankt voor je bestelling!', 'dkm'); ?></h2>
                <p class="dkm-thankyou__subtitle"><?php esc_html_e('Je bestelling is ontvangen en wordt zo snel mogelijk verwerkt.', 'dkm'); ?></p>

                <ul class="dkm-thankyou__details">
                    <li>
                        <span class="dkm-thankyou__label"><?php esc_html_e('Bestelnummer', 'dkm'); ?></span>
                        <strong class="dkm-thankyou__value"><?php echo esc_html($order->get_order_number()); ?></strong>
                    </li>
                    <li>
                        <span class="dkm-thankyou__label"><?php esc_html_e('Datum', 'dkm'); ?></span>
                        <strong class="dkm-thankyou__value"><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></strong>
                    </li>
                    <?php if (is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email()) : ?>
                    <li>
                        <span class="dkm-thankyou__label"><?php esc_html_e('E-mail', 'dkm'); ?></span>
                        <strong class="dkm-thankyou__value"><?php echo esc_html($order->get_billing_email()); ?></strong>
                    </li>
                    <?php endif; ?>
                    <li>
                        <span class="dkm-thankyou__label"><?php esc_html_e('Totaal', 'dkm'); ?></span>
                        <strong class="dkm-thankyou__value"><?php echo wp_kses_post($order->get_formatted_order_total()); ?></strong>
                    </li>
                    <?php if ($order->get_payment_method_title()) : ?>
                    <li>
                        <span class="dkm-thankyou__label"><?php esc_html_e('Betaalmethode', 'dkm'); ?></span>
                        <strong class="dkm-thankyou__value"><?php echo esc_html($order->get_payment_method_title()); ?></strong>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); ?>
        <?php do_action('woocommerce_thankyou', $order->get_id()); ?>

    <?php else : ?>
        <div class="dkm-thankyou">
            <div class="dkm-thankyou__icon">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--dkm-success, #4ade80)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
            <h2 class="dkm-thankyou__title"><?php esc_html_e('Bedankt voor je bestelling!', 'dkm'); ?></h2>
            <p class="dkm-thankyou__subtitle"><?php esc_html_e('Je bestelling is ontvangen.', 'dkm'); ?></p>
        </div>
    <?php endif; ?>
</div>
