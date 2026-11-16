<?php
/**
 * Quality tiers section
 *
 * @package DKM
 */

$tiers = array(
    array(
        'name'     => 'Superclone',
        'badge'    => 'gold',
        'price'    => 'Vanaf &euro;900',
        'url'      => home_url( '/product-categorie/superclone/' ),
        'features' => array(
            'Exacte 1:1 replicatie',
            'Saffierglas',
            'Echte Zwitserse beweging',
            'Niet te onderscheiden van origineel',
        ),
    ),
    array(
        'name'     => 'BP Swiss Made',
        'badge'    => 'silver',
        'price'    => 'Vanaf &euro;370',
        'url'      => home_url( '/product-categorie/bp-swiss-made/' ),
        'features' => array(
            'Zwitsers uurwerk',
            '316L roestvrij staal',
            'Hoogwaardige afwerking',
            'Uitstekende prijs-kwaliteit',
        ),
    ),
    array(
        'name'     => 'AAA+',
        'badge'    => 'bronze',
        'price'    => 'Vanaf &euro;150',
        'url'      => home_url( '/product-categorie/aaa/' ),
        'features' => array(
            '30m waterdicht',
            'Krasbestendig glas',
            'Betaalbaar luxe',
            'Stijlvol dagelijks horloge',
        ),
    ),
);
?>
<section class="section section--quality-tiers">
    <div class="container">
        <div class="section-header">
            <span class="section-label">ONZE KWALITEITEN</span>
            <h2 class="section-title">Drie Niveaus van Excellentie</h2>
        </div>

        <div class="quality-grid">
            <?php foreach ( $tiers as $tier ) : ?>
                <div class="quality-card quality-card--<?php echo esc_attr( $tier['badge'] ); ?>">
                    <div class="quality-card__badge">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <h3 class="quality-card__name"><?php echo esc_html( $tier['name'] ); ?></h3>
                    <span class="quality-card__price"><?php echo wp_kses_post( $tier['price'] ); ?></span>

                    <ul class="quality-card__features">
                        <?php foreach ( $tier['features'] as $feature ) : ?>
                            <li class="quality-card__feature">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                <span><?php echo esc_html( $feature ); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <a href="<?php echo esc_url( $tier['url'] ); ?>" class="quality-card__cta">
                        Bekijk <?php echo esc_html( $tier['name'] ); ?>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="section-cta">
            <a href="<?php echo esc_url( home_url( '/informatie/' ) ); ?>" class="link-with-arrow">
                Ontdek de verschillen tussen onze kwaliteiten
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
    </div>
</section>
