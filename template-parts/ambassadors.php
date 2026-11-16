<?php
/**
 * Brand ambassadors section
 *
 * @package DKM
 */

$ambassadors = array(
    array(
        'name'  => 'Furkan',
        'role'  => 'Content Creator',
        'image' => '',
    ),
    array(
        'name'  => 'Djezja',
        'role'  => 'Influencer',
        'image' => '',
    ),
    array(
        'name'  => 'Fato YT',
        'role'  => 'YouTuber',
        'image' => '',
    ),
);

if ( function_exists( 'dkm_get_ambassadors' ) ) {
    $ambassadors = dkm_get_ambassadors();
}
?>
<section class="ambassadors-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">AMBASSADORS</span>
            <h2 class="section-title">Brand Ambassadors</h2>
        </div>

        <div class="ambassadors-grid">
            <?php foreach ( $ambassadors as $ambassador ) : ?>
                <div class="ambassador-card">
                    <div class="ambassador-card__image">
                        <?php if ( ! empty( $ambassador['image'] ) ) : ?>
                            <img src="<?php echo esc_url( $ambassador['image'] ); ?>"
                                 alt="<?php echo esc_attr( $ambassador['name'] ); ?>"
                                 loading="lazy"
                                 width="200"
                                 height="200">
                        <?php else : ?>
                            <div class="ambassador-card__placeholder" aria-hidden="true">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h3 class="ambassador-card__name"><?php echo esc_html( $ambassador['name'] ); ?></h3>
                    <span class="ambassador-card__role"><?php echo esc_html( $ambassador['role'] ); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
