<?php
/**
 * Announcement bar ticker
 *
 * @package DKM
 */

$messages = function_exists( 'dkm_get_announcement_messages' )
    ? dkm_get_announcement_messages()
    : array(
        'Vandaag besteld = morgen in huis',
        'Persoonlijk contact via WhatsApp',
        'Beste kwaliteit voor een lage prijs',
        'Gratis verzending vanaf &euro;200',
    );

if ( empty( $messages ) ) {
    return;
}
?>
<div class="announcement-bar" role="marquee" aria-label="<?php esc_attr_e( 'Aankondigingen', 'dkm' ); ?>">
    <div class="announcement-bar__track">
        <?php for ( $i = 0; $i < 3; $i++ ) : ?>
            <div class="announcement-bar__group" <?php echo $i > 0 ? 'aria-hidden="true"' : ''; ?>>
                <?php foreach ( $messages as $index => $message ) : ?>
                    <?php if ( $index > 0 ) : ?>
                        <span class="announcement-bar__separator" aria-hidden="true">&#9670;</span>
                    <?php endif; ?>
                    <span class="announcement-bar__message"><?php echo wp_kses_post( $message ); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endfor; ?>
    </div>
</div>
