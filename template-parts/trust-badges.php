<?php
/**
 * Trust badges section
 *
 * @package DKM
 */

$badges = array(
    array(
        'icon'        => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>',
        'title'       => 'Kwaliteitsgarantie',
        'description' => 'Onze horloges worden met de grootste zorg vervaardigd en gecontroleerd.',
    ),
    array(
        'icon'        => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
        'title'       => 'Exclusieve Modellen',
        'description' => 'Unieke modellen die nergens anders van deze kwaliteit te vinden zijn.',
    ),
    array(
        'icon'        => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
        'title'       => 'Snel &amp; Veilig',
        'description' => 'Eenvoudig proces met snelle verzending binnen 1-2 dagen.',
    ),
);
?>
<section class="section section--trust">
    <div class="container">
        <div class="trust-grid">
            <?php foreach ( $badges as $badge ) : ?>
                <div class="trust-badge">
                    <div class="trust-badge__icon" aria-hidden="true">
                        <?php echo $badge['icon']; ?>
                    </div>
                    <h3 class="trust-badge__title"><?php echo wp_kses_post( $badge['title'] ); ?></h3>
                    <p class="trust-badge__description"><?php echo esc_html( $badge['description'] ); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
