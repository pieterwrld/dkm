<?php
/**
 * Hero section for front page
 *
 * @package DKM
 */
?>
<section class="hero-section">
    <div class="hero-bg" aria-hidden="true">
        <div class="hero-gradient"></div>
    </div>

    <div class="hero-content container">
        <div class="hero-inner">
            <span class="hero-label">SINCE 2024</span>

            <div class="hero-rule" aria-hidden="true"></div>

            <h1 class="hero-heading">
                <span class="hero-heading__line">Elegant. Exclusief.</span>
                <span class="hero-heading__line"><span class="text-gold-gradient">Tijdloos.</span></span>
            </h1>

            <p class="hero-subtitle">Ontdek de finest collectie luxe horloges van Nederland</p>

            <div class="hero-actions">
                <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="btn btn--gold">
                    Ontdek de Collectie
                </a>
                <a href="<?php echo esc_url( home_url( '/informatie/' ) ); ?>" class="btn btn--ghost">
                    Meer informatie
                </a>
            </div>
        </div>
    </div>

    <div class="hero-scroll-indicator" aria-hidden="true">
        <div class="hero-scroll-indicator__line"></div>
        <span class="hero-scroll-indicator__text">Scroll</span>
    </div>
</section>
