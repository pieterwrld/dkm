</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-about">
                <div class="site-logo" style="margin-bottom: var(--space-md);">
                    <?php if (has_custom_logo()) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo__text" rel="home">De Klokjesman</a>
                    <?php endif; ?>
                </div>
                <p>Luxe horloges van de beste kwaliteit, zorgvuldig geselecteerd voor de echte liefhebber.</p>
                <div class="footer-social">
                    <a href="https://wa.link/q23dxa" target="_blank" rel="noopener" aria-label="WhatsApp">
                        <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.611.611l4.458-1.495A11.952 11.952 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0z"/></svg>
                    </a>
                    <a href="https://snapchat.com/t/XvOpgrMG" target="_blank" rel="noopener" aria-label="Snapchat">
                        <svg viewBox="0 0 24 24"><path d="M12.206.793c.99 0 4.347.276 5.93 3.821.529 1.193.403 3.219.299 4.847l-.003.06c-.012.18-.022.345-.03.51.075.045.203.09.401.09.3-.016.659-.12.959-.289.054-.03.108-.046.162-.046.168 0 .337.09.456.24.09.12.144.3.12.465-.03.24-.21.42-.39.525-.21.12-.48.195-.72.24-.27.06-.48.105-.63.165-.046.016-.09.03-.135.06-.21.135-.165.39-.119.57l.015.06c.27.78.636 1.5 1.095 2.13.3.405.66.75 1.065 1.05.21.15.465.3.72.39.165.06.33.165.42.36.105.21.075.465-.045.645-.195.3-.525.465-.855.555-.39.105-.84.166-1.29.195a.806.806 0 00-.18.03c-.12.045-.18.165-.21.284l-.003.015a.586.586 0 01-.21.345.597.597 0 01-.39.135c-.12 0-.27-.015-.42-.045-.225-.045-.48-.105-.72-.105-.09 0-.195.009-.3.03-.195.045-.39.135-.585.225-.375.195-.795.405-1.365.465-1.005-.06-1.425-.27-1.8-.465-.195-.09-.39-.18-.585-.225a1.235 1.235 0 00-.3-.03c-.24 0-.495.06-.72.105-.15.03-.3.045-.42.045a.597.597 0 01-.39-.135.586.586 0 01-.21-.345l-.003-.015c-.03-.12-.09-.24-.21-.285a.806.806 0 00-.18-.03c-.45-.03-.9-.09-1.29-.195-.33-.09-.66-.255-.855-.555-.12-.18-.15-.435-.045-.645.09-.195.255-.3.42-.36.255-.09.51-.24.72-.39.405-.3.765-.645 1.065-1.05.459-.63.825-1.35 1.095-2.13l.015-.06c.045-.18.09-.435-.12-.57a.676.676 0 00-.135-.06c-.15-.06-.36-.105-.63-.165-.24-.045-.51-.12-.72-.24-.18-.105-.36-.285-.39-.525-.024-.165.03-.345.12-.465.12-.15.288-.24.456-.24.054 0 .108.015.162.046.3.165.66.273.959.289.198 0 .326-.045.401-.09-.007-.165-.018-.33-.03-.51l-.003-.06c-.105-1.628-.23-3.654.299-4.848C7.86 1.07 11.216.793 12.206.793z"/></svg>
                    </a>
                    <a href="mailto:info@deklokjesman.com" aria-label="Email">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </a>
                </div>
            </div>

            <div>
                <h4 class="footer-heading">Shop</h4>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer-shop',
                    'container'      => false,
                    'menu_class'     => 'footer-links',
                    'fallback_cb'    => 'dkm_footer_shop_fallback',
                    'depth'          => 1,
                ));
                ?>
            </div>

            <div>
                <h4 class="footer-heading">Informatie</h4>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer-links',
                    'container'      => false,
                    'menu_class'     => 'footer-links',
                    'fallback_cb'    => 'dkm_footer_links_fallback',
                    'depth'          => 1,
                ));
                ?>
            </div>

            <div>
                <h4 class="footer-heading">Contact</h4>
                <a href="https://wa.link/2g6iha" class="footer-contact-item" target="_blank" rel="noopener">
                    <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.611.611l4.458-1.495A11.952 11.952 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0z"/></svg>
                    <span>+31 6 47974408</span>
                </a>
                <a href="mailto:info@deklokjesman.com" class="footer-contact-item">
                    <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <span>info@deklokjesman.com</span>
                </a>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; <?php echo esc_html(date('Y')); ?> De Klokjesman. Alle rechten voorbehouden.</span>
            <div class="footer-payment">
                <span>iDEAL</span>
                <span>Bancontact</span>
                <span>Visa</span>
                <span>Mastercard</span>
            </div>
        </div>
    </div>
</footer>

<?php if (function_exists('WC')) : ?>
<div class="mini-cart__overlay" id="mini-cart-overlay"></div>
<div class="mini-cart" id="mini-cart">
    <div class="mini-cart__header">
        <h3 class="mini-cart__title">Winkelwagen <span class="mini-cart__count dkm-cart-count"><?php echo WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?></span></h3>
        <button class="mini-cart__close" type="button" aria-label="<?php esc_attr_e('Winkelwagen sluiten', 'dkm'); ?>">
            <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
    <div class="mini-cart__items">
        <div class="dkm-mini-cart-contents">
            <?php woocommerce_mini_cart(); ?>
        </div>
    </div>
    <div class="mini-cart__footer">
        <div class="mini-cart__subtotal">
            <span class="mini-cart__subtotal-label">Subtotaal</span>
            <span class="mini-cart__subtotal-amount"><?php echo WC()->cart ? WC()->cart->get_cart_subtotal() : '&euro;0,00'; ?></span>
        </div>
        <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="mini-cart__checkout btn btn--gold btn--full">Afrekenen</a>
        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="mini-cart__continue">Bekijk winkelwagen</a>
    </div>
</div>
<?php endif; ?>

<?php get_template_part('template-parts/whatsapp-button'); ?>

<a href="#" class="back-to-top" aria-label="<?php esc_attr_e('Terug naar boven', 'dkm'); ?>">
    <svg viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
</a>

<?php wp_footer(); ?>
</body>
</html>
