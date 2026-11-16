<?php
/**
 * Related products
 *
 * Overridden so related products use the same card and grid as every other
 * listing instead of WooCommerce's own ul.products markup.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $related_products ) ) {
	return;
}
?>
<section class="sec sec--tight related">
	<div class="sec-hd">
		<span class="eyebrow"><?php esc_html_e( 'Gerelateerd', 'dkm' ); ?></span>
		<h2 class="h-m">
			<?php esc_html_e( 'Misschien vind je dit ook', 'dkm' ); ?>
			<span class="em gr-txt"><?php esc_html_e( 'mooi', 'dkm' ); ?></span>
		</h2>
	</div>

	<div class="grid">
		<?php foreach ( $related_products as $related_product ) : ?>
			<?php get_template_part( 'template-parts/product-card', null, array( 'product' => $related_product ) ); ?>
		<?php endforeach; ?>
	</div>
</section>
