<?php
/**
 * Boxes and eyewear: no quality tier, so no tier modules and no box upsell
 *
 * @package DKM
 *
 * @var array $args Expects 'product' and 'tier'.
 */

defined( 'ABSPATH' ) || exit;

$product = $args['product'] ?? null;
if ( ! $product instanceof WC_Product ) {
	return;
}

$summary_args = array_merge( $args, array( 'show_upsell' => false ) );
?>
<div class="wrap">
	<div class="pd">
		<?php get_template_part( 'template-parts/product/gallery', null, $args ); ?>
		<?php get_template_part( 'template-parts/product/summary', null, $summary_args ); ?>
	</div>
</div>

<?php get_template_part( 'template-parts/product/delivery', null, $args ); ?>
