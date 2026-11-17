<?php
/**
 * The Collectie mega menu. Thumbnails come from each category's own
 * WooCommerce image, with the newest product in it as a fallback.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

$cards = dkm_mega_cards();
if ( ! $cards ) {
	return;
}
?>
<div class="mega"
	id="mega"
	role="group"
	aria-label="<?php esc_attr_e( 'Collectie', 'dkm' ); ?>"
	hidden>
	<div class="mega__in">
		<div class="mega__g wrap">
			<?php foreach ( $cards as $card ) : ?>
				<a class="mega__c" href="<?php echo esc_url( $card['url'] ); ?>">
					<span class="mega__img">
						<?php
						if ( $card['image'] ) {
							echo $card['image']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image output.
						}
						?>
					</span>
					<span class="mega__b">
						<b><?php echo esc_html( $card['name'] ); ?></b>
						<span><?php echo esc_html( $card['sub'] ); ?></span>
					</span>
				</a>
			<?php endforeach; ?>

			<a class="mega__c mega__c--all" href="<?php echo esc_url( dkm_shop_url() ); ?>">
				<span class="mega__b">
					<b><?php esc_html_e( 'Alle horloges', 'dkm' ); ?></b>
					<span><?php esc_html_e( 'Het volledige assortiment', 'dkm' ); ?></span>
					<?php dkm_the_icon( 'arrow' ); ?>
				</span>
			</a>
		</div>
	</div>
</div>
