<?php
/**
 * Hero section for front page
 *
 * @package DKM
 */
?>
<section class="hero-section">
    <div class="hero-content">
        <span class="hero-label">SINCE 2024</span>

        <h1 class="hero-title">
            Elegant. Exclusief.<br>
            <span class="gold">Tijdloos.</span>
        </h1>

        <p class="hero-subtitle">Ontdek de finest collectie luxe horloges van Nederland</p>

        <div class="hero-cta">
            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn--gold">
                Ontdek de Collectie
            </a>
            <a href="<?php echo esc_url(home_url('/informatie/')); ?>" class="btn btn--ghost">
                Meer informatie
            </a>
        </div>
    </div>

    <div class="hero-scroll" aria-hidden="true">
        <span>Scroll</span>
        <div class="hero-scroll__arrow">
            <svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
    </div>
</section>
