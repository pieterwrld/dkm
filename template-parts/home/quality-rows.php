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
				$image = dkm_tier_image( $slug, 'row', $term, 'dkm-card', '(max-width:900px) 32vw, 260px' );
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
							<small class="qshop">
								<?php esc_html_e( 'Shop nu', 'dkm' ); ?>
								<?php dkm_the_icon( 'arrow' ); ?>
							</small>

						</span>
					</span>

					<span class="qbar qlong">
						<?php
						// Three reasons to pick this level, each on one line. The
						// line of explanation that used to sit under every point
						// is gone: the labels say it well enough on their own.
						$punten = array();
						foreach ( $tier['explain'] ?? array() as $uitleg ) {
							$punten[] = (string) $uitleg[1];
						}
						foreach ( $tier['feats'] as $feat ) {
							if ( count( $punten ) >= 3 ) {
								break;
							}
							$punten[] = (string) $feat;
						}
						$punten = array_slice( $punten, 0, 3 );
						?>
						<?php foreach ( $punten as $punt ) : ?>
							<span class="qusp">
								<span class="qusp__ico"><?php dkm_the_icon( 'check' ); ?></span>
								<span class="qusp__t"><b><?php echo esc_html( $punt ); ?></b></span>
							</span>
						<?php endforeach; ?>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
