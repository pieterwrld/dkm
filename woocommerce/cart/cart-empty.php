<?php
/**
 * Empty cart page
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_cart_is_empty' );

?>

<div class="dkm-cart-empty">
    <div class="dkm-cart-empty__icon">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 0 1-8 0"/>
        </svg>
    </div>
    <h2 class="dkm-cart-empty__title"><?php esc_html_e( 'Je winkelwagen is leeg', 'dkm' ); ?></h2>
    <p class="dkm-cart-empty__text"><?php esc_html_e( 'Ontdek onze exclusieve collectie luxe horloges.', 'dkm' ); ?></p>
    <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="btn btn--gold">
        <?php esc_html_e( 'Bekijk onze collectie', 'dkm' ); ?>
    </a>
</div>
