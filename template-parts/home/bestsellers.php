<?php
/**
 * Bestsellers with live filters on quality and model.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wc_get_product' ) ) {
	return;
}

$cat      = 'meest-verkocht';
$term     = get_term_by( 'slug', $cat, 'product_cat' );
$per_page = 8;

$filters = array(
	'cat'      => $term ? $cat : '',
	'tier'     => array(),
	'model'    => array(),
	'maat'     => array(),
	'max'      => 0,
	'sort'     => 'rec',
	'page'     => 1,
	'per_page' => $per_page,
);

$facets = dkm_facets( $filters );
$query  = new WP_Query( dkm_query_args( $filters ) );

if ( ! $query->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>
<section class="sec sec--tight"
	data-dkm-facets
	data-dkm-cat="<?php echo esc_attr( $filters['cat'] ); ?>"
	data-dkm-per-page="<?php echo esc_attr( (string) $per_page ); ?>">
	<div class="wrap">
		<div class="sec-hd sec-row">
			<div>
				<span class="eyebrow"><?php esc_html_e( 'Bestsellers', 'dkm' ); ?></span>
				<h2 class="h-l"><?php esc_html_e( 'Meest gekozen', 'dkm' ); ?> <span class="em gr-txt"><?php esc_html_e( 'modellen', 'dkm' ); ?></span></h2>
			</div>
			<a class="lnk" href="<?php echo esc_url( dkm_shop_url() ); ?>">
				<?php esc_html_e( 'Alle horloges', 'dkm' ); ?>
				<?php dkm_the_icon( 'arrow' ); ?>
			</a>
		</div>

		<div class="fchips" role="group" aria-label="<?php esc_attr_e( 'Filter op kwaliteit', 'dkm' ); ?>">
			<button type="button"
				class="ch is-on"
				data-dkm-facet-all="tier"
				aria-pressed="true">
				<?php esc_html_e( 'Alles', 'dkm' ); ?>
				<b><?php echo esc_html( (string) $query->found_posts ); ?></b>
			</button>
			<?php foreach ( $facets['tier'] as $option ) : ?>
				<button type="button"
					class="ch"
					data-dkm-facet="tier"
					data-value="<?php echo esc_attr( $option['slug'] ); ?>">
					<?php echo esc_html( $option['label'] ); ?>
					<b><?php echo esc_html( (string) $option['count'] ); ?></b>
				</button>
			<?php endforeach; ?>
		</div>

		<div class="fsmall" role="group" aria-label="<?php esc_attr_e( 'Filter op model', 'dkm' ); ?>">
			<button type="button"
				class="chs is-on"
				data-dkm-facet-all="model"
				aria-pressed="true">
				<?php esc_html_e( 'Alle modellen', 'dkm' ); ?>
			</button>
			<?php foreach ( $facets['model'] as $option ) : ?>
				<button type="button"
					class="chs"
					data-dkm-facet="model"
					data-value="<?php echo esc_attr( $option['slug'] ); ?>">
					<?php echo esc_html( $option['label'] ); ?>
					<b><?php echo esc_html( (string) $option['count'] ); ?></b>
				</button>
			<?php endforeach; ?>
		</div>

		<div class="grid" id="dkm-bestsellers-grid" data-dkm-grid>
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				$product = wc_get_product( get_the_ID() );
				if ( $product ) {
					get_template_part( 'template-parts/product-card', null, array( 'product' => $product ) );
				}
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
