</main>

<footer class="site-footer">
    <div class="footer-top">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col footer-brand">
                    <div class="footer-logo">
                        <?php if ( has_custom_logo() ) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo-text" rel="home">De Klokjesman</a>
                        <?php endif; ?>
                    </div>
                    <p class="footer-tagline">Luxe horloges van de beste kwaliteit</p>
                    <div class="footer-contact-links">
                        <a href="https://wa.link/2g6iha" class="footer-contact-item" target="_blank" rel="noopener">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.611.611l4.458-1.495A11.952 11.952 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.37 0-4.567-.82-6.3-2.188l-.44-.352-2.874.964.964-2.874-.352-.44A9.953 9.953 0 012 12C2 6.486 6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/></svg>
                            <span>+31 6 47974408</span>
                        </a>
                        <a href="mailto:info@deklokjesman.com" class="footer-contact-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <span>info@deklokjesman.com</span>
                        </a>
                    </div>
                </div>

                <div class="footer-col">
                    <h4 class="footer-heading">Shop</h4>
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'footer-shop',
                        'container'      => false,
                        'menu_class'     => 'footer-menu',
                        'fallback_cb'    => 'dkm_footer_shop_fallback',
                        'depth'          => 1,
                    ) );
                    ?>
                </div>

                <div class="footer-col">
                    <h4 class="footer-heading">Informatie</h4>
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'footer-links',
                        'container'      => false,
                        'menu_class'     => 'footer-menu',
                        'fallback_cb'    => 'dkm_footer_links_fallback',
                        'depth'          => 1,
                    ) );
                    ?>
                </div>

                <div class="footer-col">
                    <h4 class="footer-heading">Contact</h4>
                    <div class="footer-contact">
                        <p><strong>WhatsApp</strong><br>+31 6 47974408</p>
                        <p><strong>Email</strong><br>info@deklokjesman.com</p>
                        <div class="footer-socials">
                            <a href="https://wa.link/q23dxa" class="social-link social-link--whatsapp" target="_blank" rel="noopener" aria-label="WhatsApp">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.611.611l4.458-1.495A11.952 11.952 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.37 0-4.567-.82-6.3-2.188l-.44-.352-2.874.964.964-2.874-.352-.44A9.953 9.953 0 012 12C2 6.486 6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/></svg>
                            </a>
                            <a href="https://snapchat.com/t/XvOpgrMG" class="social-link social-link--snapchat" target="_blank" rel="noopener" aria-label="Snapchat">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12.206.793c.99 0 4.347.276 5.93 3.821.529 1.193.403 3.219.299 4.847l-.003.06c-.012.18-.022.345-.03.51.075.045.203.09.401.09.3-.016.659-.12.959-.289.075-.045.149-.06.224-.06.12 0 .224.045.33.12.12.09.18.224.18.36 0 .24-.135.42-.3.525-.225.149-.585.3-1.035.42a1.1 1.1 0 00-.3.12c-.06.045-.12.12-.135.24-.03.18.015.345.06.494.21.569.57 1.095 1.065 1.56.18.165.39.3.615.42.254.135.524.24.779.3.195.045.375.135.42.33.045.195-.06.42-.225.57-.375.33-.855.479-1.32.555-.06.015-.12.06-.135.12l-.03.09c-.06.195-.135.42-.39.465-.285.06-.6-.075-.93-.165-.195-.06-.39-.12-.6-.135-.27-.015-.54.045-.795.195-.375.225-.72.54-1.14.72-.27.12-.555.18-.84.18h-.06c-.285 0-.57-.06-.84-.18-.42-.18-.765-.495-1.14-.72-.255-.15-.525-.21-.795-.195-.21.015-.405.075-.6.135-.33.09-.645.225-.93.165-.255-.045-.33-.27-.39-.465l-.03-.09c-.015-.06-.075-.105-.135-.12-.465-.075-.945-.225-1.32-.555-.165-.15-.27-.375-.225-.57.045-.195.225-.285.42-.33.255-.06.525-.165.779-.3.225-.12.435-.255.615-.42.495-.465.855-.99 1.065-1.56.045-.15.09-.315.06-.494-.015-.12-.075-.195-.135-.24a1.1 1.1 0 00-.3-.12c-.45-.12-.81-.27-1.035-.42-.165-.105-.3-.285-.3-.525 0-.136.06-.27.18-.36.106-.075.21-.12.33-.12.075 0 .149.015.224.06.3.165.659.273.959.289.198 0 .326-.045.401-.09-.008-.165-.018-.33-.03-.51l-.003-.06c-.104-1.628-.23-3.654.299-4.847C7.859 1.069 11.216.793 12.206.793z"/></svg>
                            </a>
                            <a href="mailto:info@deklokjesman.com" class="social-link social-link--email" aria-label="Email">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </a>
                        </div>
                        <a href="https://wa.link/q23dxa" class="btn btn--gold btn--sm" target="_blank" rel="noopener">
                            Stuur een bericht via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-inner">
                <p class="footer-copyright">&copy; <?php echo esc_html( date( 'Y' ) ); ?> De Klokjesman. Alle rechten voorbehouden.</p>
                <div class="footer-payments">
                    <span class="payment-icon" aria-label="iDEAL">iDEAL</span>
                    <span class="payment-icon" aria-label="Bancontact">Bancontact</span>
                    <span class="payment-icon" aria-label="Visa">Visa</span>
                    <span class="payment-icon" aria-label="Mastercard">MC</span>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php get_template_part( 'template-parts/whatsapp-button' ); ?>

<?php wp_footer(); ?>
</body>
</html>
