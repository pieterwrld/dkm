<?php
/**
 * Superclone product page
 *
 * The verification module sits directly under the fold, because at this price
 * the question in a buyer's head is not what it costs but whether it holds up
 * to a close look.
 *
 * @package DKM
 *
 * @var array $args Expects 'product' and 'tier'.
 */

defined( 'ABSPATH' ) || exit;

$product = $args['product'] ?? null;
$tier    = $args['tier'] ?? null;
if ( ! $product instanceof WC_Product ) {
	return;
}
?>
<div class="wrap">
	<div class="pd">
		<?php get_template_part( 'template-parts/product/gallery', null, $args ); ?>
		<?php get_template_part( 'template-parts/product/summary', null, $args ); ?>
	</div>
</div>

<section class="sec sec--tight">
	<div class="wrap">
		<div class="verify">
			<div class="verify__hd">
				<span class="eyebrow"><?php esc_html_e( 'Waar je het aan herkent', 'dkm' ); ?></span>
				<h2 class="h-l"><?php esc_html_e( 'Drie dingen die een kenner', 'dkm' ); ?> <span class="em gr-txt"><?php esc_html_e( 'direct controleert', 'dkm' ); ?></span></h2>
				<p class="lead"><?php esc_html_e( 'Dit zijn de punten waarop een superclone zich onderscheidt, en precies waar wij op nakijken voordat je horloge de deur uit gaat.', 'dkm' ); ?></p>
			</div>

			<ol class="verify__l">
				<?php foreach ( $tier['explain'] as $index => [$icon, $title, $text] ) : ?>
					<li class="verify__i">
						<span class="verify__n"><?php echo esc_html( (string) ( $index + 1 ) ); ?></span>
						<div class="verify__b">
							<b><?php dkm_the_icon( $icon ); ?><?php echo esc_html( $title ); ?></b>
							<p><?php echo esc_html( $text ); ?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>

			<p class="verify__note">
				<?php dkm_the_icon( 'eye' ); ?>
				<span><?php esc_html_e( 'Elk exemplaar gaat door onze eigen controle op de wijzerplaat, het glas, de sluiting en de loop van het uurwerk. Pas daarna wordt hij ingepakt.', 'dkm' ); ?></span>
			</p>
		</div>
	</div>
</section>

<?php
get_template_part( 'template-parts/product/quality-explainer', null, $args );
get_template_part( 'template-parts/product/delivery', null, $args );
?>
