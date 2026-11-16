<?php
/**
 * Reviews carousel section
 *
 * @package DKM
 */

$review_images = array();
if ( function_exists( 'dkm_get_review_images' ) ) {
    $review_images = dkm_get_review_images();
}
?>
<section class="section section--reviews">
    <div class="container">
        <div class="section-header">
            <span class="section-label">KLANTERVARINGEN</span>
            <h2 class="section-title">Wat Onze Klanten Zeggen</h2>
        </div>

        <div class="reviews-stats">
            <div class="reviews-stat">
                <span class="reviews-stat__number" data-count="1200">1200+</span>
                <span class="reviews-stat__label">Horloges Geleverd</span>
            </div>
            <div class="reviews-stat__divider" aria-hidden="true"></div>
            <div class="reviews-stat">
                <span class="reviews-stat__number" data-count="800">800+</span>
                <span class="reviews-stat__label">5-Sterren Reviews</span>
            </div>
            <div class="reviews-stat__divider" aria-hidden="true"></div>
            <div class="reviews-stat">
                <span class="reviews-stat__number" data-count="328">328+</span>
                <span class="reviews-stat__label">Unieke Reviews</span>
            </div>
        </div>

        <div class="reviews-carousel" id="reviews-carousel">
            <div class="reviews-carousel__track" id="reviews-track">
                <?php if ( ! empty( $review_images ) ) : ?>
                    <?php foreach ( $review_images as $image ) : ?>
                        <div class="reviews-carousel__slide">
                            <img src="<?php echo esc_url( $image['url'] ); ?>"
                                 alt="<?php echo esc_attr( $image['alt'] ?? 'Klantreview' ); ?>"
                                 loading="lazy"
                                 width="300"
                                 height="400">
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <?php for ( $i = 1; $i <= 12; $i++ ) : ?>
                        <div class="reviews-carousel__slide">
                            <div class="reviews-carousel__placeholder">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                                </svg>
                                <span>Review <?php echo esc_html( $i ); ?></span>
                            </div>
                        </div>
                    <?php endfor; ?>
                <?php endif; ?>
            </div>

            <div class="reviews-carousel__controls">
                <button class="reviews-carousel__btn reviews-carousel__btn--prev" type="button" aria-label="<?php esc_attr_e( 'Vorige reviews', 'dkm' ); ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                </button>
                <button class="reviews-carousel__btn reviews-carousel__btn--next" type="button" aria-label="<?php esc_attr_e( 'Volgende reviews', 'dkm' ); ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</section>
