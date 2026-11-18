<?php
/**
 * Dispatches to the template for this product's quality tier.
 *
 * Each tier has its own file, so the three pages differ in what they argue and
 * in what order, not only in accent colour.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

global $product;

$product = is_a( $product, 'WC_Product' ) ? $product : wc_get_product( get_the_ID() );
if ( ! $product ) {
	return;
}

$tier = dkm_product_tier( $product );
$slug = dkm_product_template_slug( $tier );
?>
<div class="pd-page" style="<?php echo esc_attr( dkm_tier_vars( $tier ) ); ?>">
	<?php woocommerce_breadcrumb(); ?>

	<?php
	do_action( 'woocommerce_before_single_product' );

	get_template_part(
		'template-parts/product/' . $slug,
		null,
		array(
			'product' => $product,
			'tier'    => $tier,
		)
	);

	do_action( 'woocommerce_after_single_product' );
	?>
</div>
