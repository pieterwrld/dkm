<?php
/**
 * Off canvas menu.
 *
 * The panel is hidden rather than only translated, otherwise it still takes up
 * space and stretches the page sideways on a phone.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

$items = dkm_nav_items();
?>
<div class="mob" id="mobile-drawer" hidden>
	<nav class="mob__nav" aria-label="<?php esc_attr_e( 'Mobiel menu', 'dkm' ); ?>">
		<a class="mob__link" href="<?php echo esc_url( dkm_shop_url() ); ?>">
			<?php esc_html_e( 'Alle horloges', 'dkm' ); ?>
			<?php dkm_the_icon( 'arrow' ); ?>
		</a>

		<?php foreach ( $items as $item ) : ?>
			<a class="mob__link<?php echo $item['red'] ? ' mob__link--red' : ''; ?>" href="<?php echo esc_url( $item['url'] ); ?>">
				<?php echo esc_html( $item['label'] ); ?>
				<?php dkm_the_icon( 'arrow' ); ?>
			</a>
		<?php endforeach; ?>

		<a class="mob__link" href="<?php echo esc_url( dkm_info_page_url() ); ?>">
			<?php esc_html_e( 'Kwaliteitsinformatie', 'dkm' ); ?>
			<?php dkm_the_icon( 'arrow' ); ?>
		</a>

		<?php $wa = dkm_whatsapp_url( (string) get_theme_mod( 'dkm_whatsapp_text', '' ) ); ?>
		<?php if ( $wa ) : ?>
			<a class="mob__link mob__link--wa" href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener">
				<?php esc_html_e( 'WhatsApp ons', 'dkm' ); ?>
				<?php dkm_the_icon( 'wa' ); ?>
			</a>
		<?php endif; ?>
	</nav>
</div>
