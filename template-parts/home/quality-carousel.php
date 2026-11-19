<?php
/**
 * Pick your quality: a snapping carousel with arrows and dots on desktop and
 * on mobile alike. BP comes first and is the highlighted card.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

$tiers = dkm_tiers();

// BP leads, because it is the one most customers land on.
$order = array( 'bp-swiss-made', 'superclone', 'aaa' );
$matrix = dkm_matrix();
?>
<section class="sec sec--tight">
	<div class="wrap">
		<div class="sec-hd sec-hd--ctr">
			<span class="eyebrow"><?php esc_html_e( 'Kies je kwaliteit', 'dkm' ); ?></span>
			<h2 class="h-l"><?php esc_html_e( 'Welke kwaliteit', 'dkm' ); ?> <span class="em gr-txt"><?php esc_html_e( 'kies jij?', 'dkm' ); ?></span></h2>
			<p class="lead"><?php esc_html_e( 'Alle drie zijn goed. Het verschil zit in het uurwerk, het glas en hoe dicht het bij het origineel komt.', 'dkm' ); ?></p>
		</div>

		<div class="car-wrap" data-dkm-carousel>
			<div class="car" data-dkm-track>
				<?php
				foreach ( $order as $position => $slug ) :
					if ( ! isset( $tiers[ $slug ] ) ) {
						continue;
					}
					$tier = $tiers[ $slug ];
					$term = get_term_by( 'slug', $slug, 'product_cat' );
					$url  = ( $term && ! is_wp_error( $term ) ) ? (string) get_term_link( $term ) : dkm_shop_url();
					$feat = 'bp-swiss-made' === $slug;
					?>
					<div class="qc<?php echo $feat ? ' qc--feat' : ''; ?>"
						style="<?php echo esc_attr( dkm_tier_vars( $tier ) ); ?>"
						<?php echo $feat ? 'data-dkm-featured' : ''; ?>>

						<?php if ( $feat ) : ?>
							<span class="qc__rib gr"><?php esc_html_e( 'Meest gekozen', 'dkm' ); ?></span>
						<?php endif; ?>

						<?php
						$image = dkm_tier_image( $slug, 'pick', $term && ! is_wp_error( $term ) ? $term : null, 'dkm-wide', '(max-width:900px) 86vw, 420px' );
						if ( $image ) {
							echo '<span class="qc__phw">' . $image . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>

						<h3><?php echo esc_html( $tier['name'] ); ?></h3>
						<p class="qc__sub"><?php echo esc_html( $tier['sub'] ); ?></p>
						<p class="qc__pr">
							<?php echo esc_html( dkm_price( $tier['from'] ) ); ?>
							<small><?php esc_html_e( 'en hoger', 'dkm' ); ?></small>
						</p>

						<ul class="qc__l">
							<?php
							foreach ( $matrix as [$label, $verdicts, $explain] ) :
								$verdict = $verdicts[ $tier['key'] ] ?? 'n';
								if ( 'n' === $verdict && 'superclone' === $tier['key'] ) {
									continue;
								}
								?>
								<li class="qc__li<?php echo 'y' === $verdict ? '' : ' qc__li--off'; ?>">
									<?php echo dkm_mark( $verdict ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<span><?php echo esc_html( $label ); ?></span>
									<button type="button" class="inf" aria-expanded="false">
										<span aria-hidden="true">i</span>
										<span class="screen-reader-text"><?php esc_html_e( 'Uitleg', 'dkm' ); ?></span>
										<span class="inf__tip"><?php echo esc_html( $explain ); ?></span>
									</button>
								</li>
							<?php endforeach; ?>
						</ul>

						<a class="btn <?php echo $feat ? 'btn--g' : 'btn--o'; ?> btn--full" href="<?php echo esc_url( $url ); ?>">
							<?php
							/* translators: %s is a tier name. */
							printf( esc_html__( 'Bekijk %s', 'dkm' ), esc_html( $tier['name'] ) );
							?>
						</a>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="car-nav">
				<button type="button" class="cbtn" data-dkm-prev>
					<?php dkm_the_icon( 'left', '', __( 'Vorige kwaliteit', 'dkm' ) ); ?>
				</button>
				<div class="cdots" data-dkm-dots></div>
				<button type="button" class="cbtn" data-dkm-next>
					<?php dkm_the_icon( 'arrow', '', __( 'Volgende kwaliteit', 'dkm' ) ); ?>
				</button>
			</div>
		</div>
	</div>
</section>
