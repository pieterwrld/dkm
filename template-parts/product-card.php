<?php
/**
 * Product card
 *
 * @package DKM
 *
 * @var array $args Expects 'product'.
 */

defined( 'ABSPATH' ) || exit;

$product = $args['product'] ?? null;
if ( ! $product instanceof WC_Product ) {
	global $product;
}
if ( ! $product instanceof WC_Product ) {
	return;
}

$tier  = dkm_product_tier( $product );
$name  = dkm_short_title( $product->get_name() );
$model = dkm_attr( $product, 'pa_model' );
$size  = dkm_attr( $product, 'pa_maat' );
$kicker = $model ?: dkm_product_brand( $product );
if ( $size ) {
	$kicker = trim( $kicker . ' · ' . $size );
}

// Up to four photos per card. More than that is a lot of weight on a page
// that may hold two dozen cards, and nobody swipes further than that here.
$fotos = array();
if ( $product->get_image_id() ) {
	$fotos[] = (int) $product->get_image_id();
}
foreach ( $product->get_gallery_image_ids() as $extra ) {
	$fotos[] = (int) $extra;
}
$fotos = array_slice( array_values( array_unique( array_filter( $fotos ) ) ), 0, 4 );
$meer  = count( $fotos ) > 1;
?>
<article class="pc rv" data-dkm-product="<?php echo esc_attr( (string) $product->get_id() ); ?>">
	<a class="pc__link" href="<?php echo esc_url( (string) get_permalink( $product->get_id() ) ); ?>">
		<div class="pc__img">
			<div class="pc__tags">
				<?php
				echo dkm_tier_badge( $tier ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				if ( $product->is_on_sale() ) :
					$percent = dkm_sale_percentage( $product );
					?>
					<span class="tg tg--sale">
						<?php echo $percent ? esc_html( '-' . $percent . '%' ) : esc_html__( 'Sale', 'dkm' ); ?>
					</span>
				<?php endif; ?>
				<?php if ( ! $product->is_in_stock() ) : ?>
					<span class="tg"><?php esc_html_e( 'Uitverkocht', 'dkm' ); ?></span>
				<?php endif; ?>
			</div>

			<?php if ( $fotos ) : ?>
				<span class="shots" data-dkm-shots>
					<span class="shots__track" data-dkm-shots-track>
						<?php foreach ( $fotos as $index => $foto ) : ?>
							<?php
							echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								$foto,
								'dkm-card',
								false,
								array(
									'alt'     => 0 === $index ? $name : '',
									'loading' => 'lazy',
									'sizes'   => '(max-width:760px) 50vw, (max-width:1100px) 33vw, 320px',
								)
							);
							?>
						<?php endforeach; ?>
					</span>

					<?php if ( $meer ) : ?>
						<span class="shots__bar" aria-hidden="true">
							<?php foreach ( $fotos as $index => $foto ) : ?>
								<i<?php echo 0 === $index ? ' class="is-on"' : ''; ?>></i>
							<?php endforeach; ?>
						</span>
					<?php endif; ?>
				</span>
			<?php else : ?>
				<span class="pc__ph" aria-hidden="true"><?php dkm_the_icon( 'watch' ); ?></span>
			<?php endif; ?>
		</div>

		<div class="pc__b">
			<span class="pc__m"><?php echo esc_html( $kicker ); ?></span>
			<h3 class="pc__n"><?php echo esc_html( $name ); ?></h3>
			<span class="pc__p"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
		</div>
	</a>
</article>
