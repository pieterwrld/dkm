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


<?php
get_template_part( 'template-parts/product/quality-explainer', null, $args );
get_template_part( 'template-parts/product/delivery', null, $args );
?>
