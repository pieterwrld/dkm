<?php
/**
 * Reviews carousel section
 *
 * @package DKM
 */

$review_images = array();
if (function_exists('dkm_get_review_images')) {
    $review_images = dkm_get_review_images();
}
?>
<section class="reviews-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">KLANTERVARINGEN</span>
            <h2 class="section-title">Wat Onze Klanten Zeggen</h2>
        </div>

        <div class="reviews-stats">
            <div class="review-stat">
                <span class="review-stat__number">1200+</span>
                <span class="review-stat__label">Horloges Geleverd</span>
            </div>
            <div class="review-stat">
                <span class="review-stat__number">800+</span>
                <span class="review-stat__label">5-Sterren Reviews</span>
            </div>
            <div class="review-stat">
                <span class="review-stat__number">328+</span>
                <span class="review-stat__label">Unieke Reviews</span>
            </div>
        </div>

        <div class="reviews-carousel" id="reviews-carousel">
            <?php if (!empty($review_images)) : ?>
                <?php foreach ($review_images as $image) : ?>
                    <div class="review-card">
                        <img src="<?php echo esc_url($image['url']); ?>"
                             alt="<?php echo esc_attr($image['alt'] ?? 'Klantreview'); ?>"
                             loading="lazy"
                             width="280"
                             height="373">
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <?php for ($i = 1; $i <= 12; $i++) : ?>
                    <div class="review-card">
                        <div class="review-card__placeholder">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" aria-hidden="true">
                                <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                            </svg>
                            <span>Review <?php echo esc_html($i); ?></span>
                        </div>
                    </div>
                <?php endfor; ?>
            <?php endif; ?>
        </div>

        <div class="reviews-nav">
            <button type="button" data-dir="prev" aria-label="<?php esc_attr_e('Vorige reviews', 'dkm'); ?>">
                <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <button type="button" data-dir="next" aria-label="<?php esc_attr_e('Volgende reviews', 'dkm'); ?>">
                <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
        </div>
    </div>
</section>
