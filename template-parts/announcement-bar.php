<?php
/**
 * Announcement bar ticker
 *
 * @package DKM
 */

$messages = function_exists('dkm_get_announcement_messages')
    ? dkm_get_announcement_messages()
    : array(
        'Vandaag besteld = morgen in huis',
        'Persoonlijk contact via WhatsApp',
        'Beste kwaliteit voor een lage prijs',
        'Gratis verzending vanaf &euro;200',
    );

if (empty($messages)) {
    return;
}
?>
<div class="announcement-bar" role="marquee" aria-label="<?php esc_attr_e('Aankondigingen', 'dkm'); ?>">
    <div class="announcement-bar__track">
        <?php for ($set = 0; $set < 3; $set++) : ?>
            <?php foreach ($messages as $message) : ?>
                <span class="announcement-bar__item" <?php echo $set > 0 ? 'aria-hidden="true"' : ''; ?>><?php echo wp_kses_post($message); ?></span>
            <?php endforeach; ?>
        <?php endfor; ?>
    </div>
</div>
