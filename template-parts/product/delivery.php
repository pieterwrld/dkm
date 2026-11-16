<?php
/**
 * How we deliver, as a scrollable row
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

$items = dkm_delivery();
if ( ! $items ) {
	return;
}
?>
<section class="sec sec--tight">
	<div class="wrap">
		<div class="sec-hd">
			<span class="eyebrow"><?php esc_html_e( 'Goed om te weten', 'dkm' ); ?></span>
			<h2 class="h-m"><?php esc_html_e( 'Zo leveren wij', 'dkm' ); ?> <span class="em gr-txt"><?php esc_html_e( 'jouw horloge', 'dkm' ); ?></span></h2>
		</div>

		<div class="icar scrollable" tabindex="0" role="group" aria-label="<?php esc_attr_e( 'Zo leveren wij jouw horloge', 'dkm' ); ?>">
			<?php foreach ( $items as [$icon, $title, $text] ) : ?>
				<article class="icar__c">
					<div class="icar__b">
						<?php dkm_the_icon( $icon ); ?>
						<b><?php echo esc_html( $title ); ?></b>
						<p><?php echo esc_html( $text ); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
