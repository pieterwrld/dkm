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

$thumb_id = (int) $product->get_image_id();
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

			<?php
			if ( $thumb_id ) {
				echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					$thumb_id,
					'dkm-card',
					false,
					array(
						'alt'     => $name,
						'loading' => 'lazy',
						'sizes'   => '(max-width:760px) 50vw, (max-width:1100px) 33vw, 320px',
					)
				);
			} else {
				echo '<span class="pc__ph" aria-hidden="true">' . dkm_icon( 'watch' ) . '</span>';
			}
			?>
		</div>

		<div class="pc__b">
			<span class="pc__m"><?php echo esc_html( $kicker ); ?></span>
			<h3 class="pc__n"><?php echo esc_html( $name ); ?></h3>
			<span class="pc__p"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
		</div>
	</a>
</article>
