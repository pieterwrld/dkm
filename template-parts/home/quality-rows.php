<?php
/**
 * Three quality rows. Each row stays on a single line at every width, and the
 * whole row is one link into that collection.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="sec">
	<div class="wrap">
		<div class="sec-hd sec-row">
			<div>
				<span class="eyebrow"><?php esc_html_e( 'Onze kwaliteiten', 'dkm' ); ?></span>
				<h2 class="h-l"><?php esc_html_e( 'Drie niveaus,', 'dkm' ); ?> <span class="em gr-txt"><?php esc_html_e( 'één standaard', 'dkm' ); ?></span></h2>
				<p class="lead"><?php esc_html_e( 'Klik op een balk om direct die collectie te openen.', 'dkm' ); ?></p>
			</div>
			<a class="lnk" href="<?php echo esc_url( dkm_info_page_url() ); ?>">
				<?php esc_html_e( 'Volledige vergelijking', 'dkm' ); ?>
				<?php dkm_the_icon( 'arrow' ); ?>
			</a>
		</div>

		<div class="qrows">
			<?php
			foreach ( dkm_tiers() as $slug => $tier ) :
				$term = get_term_by( 'slug', $slug, 'product_cat' );
				if ( ! $term || is_wp_error( $term ) ) {
					continue;
				}
				$image = dkm_product_cat_image( $term, 'dkm-card' );
				?>
				<a class="qrow rv" href="<?php echo esc_url( (string) get_term_link( $term ) ); ?>" style="<?php echo esc_attr( dkm_tier_vars( $tier ) ); ?>">
					<span class="qbar qshort">
						<span class="qshort__img">
							<?php
							if ( $image ) {
								echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							}
							?>
						</span>
						<span class="qshort__t">
							<span class="qpill"><?php echo esc_html( $tier['short'] ?? $tier['name'] ); ?></span>
							<b>
								<?php
								/* translators: %s is a price such as €900. */
								printf( esc_html__( 'Vanaf %s', 'dkm' ), esc_html( dkm_price( $tier['from'] ) ) );
								?>
							</b>
							<small>
								<?php
								/* translators: %d is a number of models. */
								printf( esc_html( _n( '%d model', '%d modellen', (int) $term->count, 'dkm' ) ), (int) $term->count );
								?>
							</small>
						</span>
					</span>

					<span class="qbar qlong">
						<span class="qfeat">
							<?php foreach ( array_slice( $tier['feats'], 0, 2 ) as $feat ) : ?>
								<span class="qfeat__i"><?php dkm_the_icon( 'check' ); ?><?php echo esc_html( $feat ); ?></span>
							<?php endforeach; ?>
						</span>
						<span class="qmeta">
							<b><?php echo esc_html( $tier['sub'] ); ?></b>
							<span><?php esc_html_e( 'Bekijk', 'dkm' ); ?></span>
						</span>
						<span class="qgo"><?php dkm_the_icon( 'arrow' ); ?></span>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
