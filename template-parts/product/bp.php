<?php
/**
 * BP Swiss Made product page
 *
 * The comparison against Superclone comes first, because this is the price
 * point where a buyer is weighing up whether to spend more.
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

$super = dkm_get_tier( 'superclone' );
$rows  = array();

if ( $super ) {
	$labels = array(
		'glass'    => __( 'Glas', 'dkm' ),
		'movement' => __( 'Uurwerk', 'dkm' ),
		'steel'    => __( 'Staal', 'dkm' ),
		'water'    => __( 'Waterdicht', 'dkm' ),
	);
	foreach ( $labels as $key => $label ) {
		$rows[] = array(
			'label' => $label,
			'bp'    => $tier['specs'][ $key ],
			'super' => $super['specs'][ $key ],
			'same'  => $tier['specs'][ $key ] === $super['specs'][ $key ],
		);
	}
}

$gap = $super ? max( 0, (int) $super['from'] - (int) $tier['from'] ) : 0;
?>
<div class="wrap">
	<div class="pd">
		<?php get_template_part( 'template-parts/product/gallery', null, $args ); ?>
		<?php get_template_part( 'template-parts/product/summary', null, $args ); ?>
	</div>
</div>

<?php if ( $rows ) : ?>
	<section class="sec sec--tight">
		<div class="wrap">
			<div class="versus">
				<div class="versus__hd">
					<span class="eyebrow"><?php esc_html_e( 'BP of Superclone', 'dkm' ); ?></span>
					<h2 class="h-l"><?php esc_html_e( 'Wat je wel en niet krijgt', 'dkm' ); ?> <span class="em gr-txt"><?php esc_html_e( 'voor het prijsverschil', 'dkm' ); ?></span></h2>
					<p class="lead">
						<?php
						if ( $gap ) {
							printf(
								/* translators: %s is a price difference. */
								esc_html__( 'Een superclone kost ongeveer %s meer. Dit is waar dat verschil in zit, zonder opsmuk.', 'dkm' ),
								esc_html( dkm_price( $gap ) )
							);
						} else {
							esc_html_e( 'Dit is waar het verschil tussen de twee niveaus in zit, zonder opsmuk.', 'dkm' );
						}
						?>
					</p>
				</div>

				<div class="versus__g">
					<div class="versus__col versus__col--this">
						<span class="versus__tag"><?php echo esc_html( $tier['name'] ); ?></span>
						<span class="versus__from">
							<?php
							printf(
								/* translators: %s is a price. */
								esc_html__( 'vanaf %s', 'dkm' ),
								esc_html( dkm_price( (int) $tier['from'] ) )
							);
							?>
						</span>
						<dl class="versus__l">
							<?php foreach ( $rows as $row ) : ?>
								<div>
									<dt><?php echo esc_html( $row['label'] ); ?></dt>
									<dd>
										<?php echo dkm_mark( $row['same'] ? 'y' : 'p' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										<span><?php echo esc_html( $row['bp'] ); ?></span>
									</dd>
								</div>
							<?php endforeach; ?>
						</dl>
					</div>

					<div class="versus__col versus__col--other">
						<span class="versus__tag"><?php echo esc_html( $super['name'] ); ?></span>
						<span class="versus__from">
							<?php
							printf(
								/* translators: %s is a price. */
								esc_html__( 'vanaf %s', 'dkm' ),
								esc_html( dkm_price( (int) $super['from'] ) )
							);
							?>
						</span>
						<dl class="versus__l">
							<?php foreach ( $rows as $row ) : ?>
								<div>
									<dt><?php echo esc_html( $row['label'] ); ?></dt>
									<dd>
										<?php echo dkm_mark( 'y' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										<span><?php echo esc_html( $row['super'] ); ?></span>
									</dd>
								</div>
							<?php endforeach; ?>
						</dl>
					</div>
				</div>

				<p class="versus__note">
					<?php esc_html_e( 'Op saffierglas en een echt automatisch uurwerk levert BP niets in. Het verschil zit in het staal, het gewicht en hoe dicht de gravures bij het origineel komen.', 'dkm' ); ?>
				</p>

				<div class="versus__act">
					<?php
					$super_term = get_term_by( 'slug', 'superclone', 'product_cat' );
					if ( $super_term && ! is_wp_error( $super_term ) ) :
						?>
						<a class="btn btn--o" href="<?php echo esc_url( (string) get_term_link( $super_term ) ); ?>">
							<?php esc_html_e( 'Bekijk de superclones', 'dkm' ); ?>
							<?php dkm_the_icon( 'arrow' ); ?>
						</a>
					<?php endif; ?>
					<a class="btn btn--o" href="<?php echo esc_url( dkm_info_page_url() ); ?>">
						<?php esc_html_e( 'Volledige vergelijking', 'dkm' ); ?>
					</a>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php
get_template_part( 'template-parts/product/quality-explainer', null, $args );
get_template_part( 'template-parts/product/delivery', null, $args );
?>

<section class="sec sec--tight">
	<div class="wrap">
		<?php woocommerce_output_related_products(); ?>
	</div>
</section>
