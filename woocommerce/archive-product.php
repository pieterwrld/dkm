<?php
/**
 * Collection: the shop root and every product category archive
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

$term      = dkm_current_product_cat();
$cat_slug  = $term instanceof WP_Term ? $term->slug : '';
$term_tier = $term instanceof WP_Term ? dkm_get_tier( $term->slug ) : null;

$filters = array(
	'cat'      => $cat_slug,
	'tier'     => array(),
	'model'    => array(),
	'maat'     => array(),
	'max'      => 0,
	'sort'     => 'rec',
	'page'     => 1,
	'per_page' => DKM_PER_PAGE,
);

$facets = dkm_facets( $filters );
$bounds = dkm_price_bounds( $filters );
$total  = (int) ( $GLOBALS['wp_query']->found_posts ?? 0 );
$pages  = (int) ( $GLOBALS['wp_query']->max_num_pages ?? 1 );

$heading = $term instanceof WP_Term ? $term->name : __( 'Alle horloges', 'dkm' );
if ( $term_tier ) {
	$heading = $term_tier['name'];
}

$intro = $term instanceof WP_Term ? wp_strip_all_tags( (string) term_description( $term ) ) : '';
if ( '' === $intro ) {
	$intro = $term_tier
		? $term_tier['claim']
		: __( 'Ontdek het volledige assortiment, van betaalbaar instapmodel tot superclone.', 'dkm' );
}

do_action( 'woocommerce_before_main_content' );
?>

<section class="colhead">
	<div class="wrap">
		<span class="eyebrow"><?php esc_html_e( 'Collectie', 'dkm' ); ?></span>
		<h1 class="h-l colhead__t"><?php echo esc_html( $heading ); ?></h1>
		<p class="lead colhead__p"><?php echo esc_html( $intro ); ?></p>
	</div>
</section>

<div class="wrap colnav">
	<?php get_template_part( 'template-parts/quality-cards', null, array( 'current' => $cat_slug ) ); ?>

	<?php
	$pills = array(
		'dames'       => __( 'Dames', 'dkm' ),
		'rolex-dozen' => __( 'Dozen', 'dkm' ),
		'brillen'     => __( 'Brillen', 'dkm' ),
		'2e-hands'    => __( '2e hands', 'dkm' ),
		'sale'        => __( 'Sale', 'dkm' ),
	);
	?>
	<nav class="fsmall colnav__pills" aria-label="<?php esc_attr_e( 'Andere collecties', 'dkm' ); ?>">
		<a class="chs<?php echo '' === $cat_slug ? ' is-on' : ''; ?>" href="<?php echo esc_url( dkm_shop_url() ); ?>">
			<?php esc_html_e( 'Alle horloges', 'dkm' ); ?>
		</a>
		<?php
		foreach ( $pills as $slug => $label ) :
			$pill_term = get_term_by( 'slug', $slug, 'product_cat' );
			if ( ! $pill_term || is_wp_error( $pill_term ) ) {
				continue;
			}
			?>
			<a class="chs<?php echo $cat_slug === $slug ? ' is-on' : ''; ?>"
				href="<?php echo esc_url( (string) get_term_link( $pill_term ) ); ?>">
				<?php echo esc_html( $label ); ?>
			</a>
		<?php endforeach; ?>
	</nav>
</div>

<div data-dkm-facets data-dkm-cat="<?php echo esc_attr( $cat_slug ); ?>">

	<div class="fbar">
		<div class="wrap">
			<?php if ( $term_tier ) : ?>
				<?php /* On a tier archive the tiers are separate pages, so these link rather than filter. */ ?>
				<nav class="fchips" aria-label="<?php esc_attr_e( 'Kwaliteit', 'dkm' ); ?>">
					<a class="ch" href="<?php echo esc_url( dkm_shop_url() ); ?>"><?php esc_html_e( 'Alles', 'dkm' ); ?></a>
					<?php foreach ( dkm_tiers() as $slug => $tier ) : ?>
						<?php
						$tier_term = get_term_by( 'slug', $slug, 'product_cat' );
						if ( ! $tier_term || is_wp_error( $tier_term ) ) {
							continue;
						}
						?>
						<a class="ch<?php echo $cat_slug === $slug ? ' is-on' : ''; ?>"
							href="<?php echo esc_url( (string) get_term_link( $tier_term ) ); ?>">
							<?php echo esc_html( $tier['name'] ); ?>
							<b><?php echo esc_html( (string) $tier_term->count ); ?></b>
						</a>
					<?php endforeach; ?>
				</nav>
			<?php else : ?>
				<div class="fchips" role="group" aria-label="<?php esc_attr_e( 'Filter op kwaliteit', 'dkm' ); ?>">
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
			<?php endif; ?>

			<div class="tools">
				<div class="tools__l">
					<button type="button"
						class="fbtn"
						data-dkm-filter-toggle
						aria-expanded="false"
						aria-controls="dkm-filter-panel">
						<?php dkm_the_icon( 'filter' ); ?>
						<?php esc_html_e( 'Filters', 'dkm' ); ?>
						<b data-dkm-active-count hidden>0</b>
					</button>

					<span class="cnt">
						<b data-dkm-count-total><?php echo esc_html( (string) $total ); ?></b>
						<?php esc_html_e( 'producten', 'dkm' ); ?>
					</span>
				</div>

				<label class="tools__sort">
					<span class="screen-reader-text"><?php esc_html_e( 'Sorteren', 'dkm' ); ?></span>
					<select class="sel" data-dkm-sort>
						<option value="rec"><?php esc_html_e( 'Aanbevolen', 'dkm' ); ?></option>
						<option value="asc"><?php esc_html_e( 'Prijs laag naar hoog', 'dkm' ); ?></option>
						<option value="desc"><?php esc_html_e( 'Prijs hoog naar laag', 'dkm' ); ?></option>
						<option value="az"><?php esc_html_e( 'Naam A tot Z', 'dkm' ); ?></option>
						<option value="new"><?php esc_html_e( 'Nieuwste eerst', 'dkm' ); ?></option>
					</select>
				</label>
			</div>

			<div class="drawer" id="dkm-filter-panel" data-dkm-filter-panel>
				<div class="drawer__in">
					<div class="fgrp">
						<h2 class="fgrp__h"><?php esc_html_e( 'Modelnaam', 'dkm' ); ?></h2>
						<div class="fopts">
							<?php if ( $facets['model'] ) : ?>
								<?php foreach ( $facets['model'] as $option ) : ?>
									<button type="button"
										class="chs"
										data-dkm-facet="model"
										data-value="<?php echo esc_attr( $option['slug'] ); ?>">
										<?php echo esc_html( $option['label'] ); ?>
										<b><?php echo esc_html( (string) $option['count'] ); ?></b>
									</button>
								<?php endforeach; ?>
							<?php else : ?>
								<span class="fopts__none"><?php esc_html_e( 'Niet beschikbaar', 'dkm' ); ?></span>
							<?php endif; ?>
						</div>
					</div>

					<div class="fgrp">
						<h2 class="fgrp__h"><?php esc_html_e( 'Millimeter', 'dkm' ); ?></h2>
						<div class="fopts">
							<?php if ( $facets['maat'] ) : ?>
								<?php foreach ( $facets['maat'] as $option ) : ?>
									<button type="button"
										class="chs"
										data-dkm-facet="maat"
										data-value="<?php echo esc_attr( $option['slug'] ); ?>">
										<?php echo esc_html( $option['label'] ); ?>
										<b><?php echo esc_html( (string) $option['count'] ); ?></b>
									</button>
								<?php endforeach; ?>
							<?php else : ?>
								<span class="fopts__none"><?php esc_html_e( 'Niet beschikbaar', 'dkm' ); ?></span>
							<?php endif; ?>
						</div>
					</div>

					<div class="fgrp">
						<h2 class="fgrp__h"><?php esc_html_e( 'Prijs', 'dkm' ); ?></h2>
						<?php if ( $bounds['max'] > $bounds['min'] ) : ?>
							<div class="rng">
								<label class="screen-reader-text" for="dkm-price"><?php esc_html_e( 'Maximumprijs', 'dkm' ); ?></label>
								<input type="range"
									id="dkm-price"
									data-dkm-price
									min="<?php echo esc_attr( (string) $bounds['min'] ); ?>"
									max="<?php echo esc_attr( (string) $bounds['max'] ); ?>"
									step="5"
									value="<?php echo esc_attr( (string) $bounds['max'] ); ?>">
							</div>
							<div class="rng__out">
								<span class="rng__v rng__v--min"><?php echo esc_html( dkm_price( $bounds['min'] ) ); ?></span>
								<span class="rng__v" data-dkm-price-value><?php echo esc_html( dkm_price( $bounds['max'] ) ); ?></span>
							</div>
						<?php endif; ?>
						<button type="button" class="chs drawer__clear" data-dkm-clear>
							<?php esc_html_e( 'Wis filters', 'dkm' ); ?>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="wrap colgrid">
		<?php get_template_part( 'template-parts/collection-banner', null, array( 'term' => $term ) ); ?>

		<div class="grid grid--c3" id="dkm-grid" data-dkm-grid>
			<?php
			if ( woocommerce_product_loop() && have_posts() ) {
				while ( have_posts() ) {
					the_post();
					wc_get_template_part( 'content', 'product' );
				}
			} else {
				echo '<p class="empty">' . esc_html__( 'Geen horloges gevonden met deze filters.', 'dkm' ) . '</p>';
			}
			wp_reset_postdata();
			?>
		</div>

		<div class="more">
			<button type="button" class="btn btn--o" data-dkm-more<?php echo $pages > 1 ? '' : ' hidden'; ?>>
				<?php esc_html_e( 'Meer laden', 'dkm' ); ?>
			</button>
		</div>
	</div>
</div>

<?php
do_action( 'woocommerce_after_main_content' );

get_footer( 'shop' );
