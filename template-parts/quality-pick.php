<?php
/**
 * Three clickable quality cards, each opening the full explanation in a modal.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

$tiers = dkm_tiers();
if ( ! $tiers ) {
	return;
}

$spec_labels = array(
	'glass'    => __( 'Glas', 'dkm' ),
	'movement' => __( 'Uurwerk', 'dkm' ),
	'steel'    => __( 'Materiaal', 'dkm' ),
	'water'    => __( 'Waterdicht', 'dkm' ),
);
?>
<section class="sec info-pick">
	<div class="wrap">
		<div class="sec-hd sec-hd--ctr">
			<span class="eyebrow"><?php esc_html_e( 'Kies je kwaliteit', 'dkm' ); ?></span>
			<h2 class="h-l"><?php esc_html_e( 'Welke kwaliteit', 'dkm' ); ?> <span class="em gr-txt"><?php esc_html_e( 'kies jij?', 'dkm' ); ?></span></h2>
			<p class="lead"><?php esc_html_e( 'Klik op een kwaliteit voor de volledige uitleg.', 'dkm' ); ?></p>
		</div>

		<div class="qpick">
			<?php
			foreach ( $tiers as $slug => $tier ) :
				$term     = get_term_by( 'slug', $slug, 'product_cat' );
				$archive  = ( $term && ! is_wp_error( $term ) ) ? (string) get_term_link( $term ) : dkm_shop_url();
				$image    = ( $term && ! is_wp_error( $term ) ) ? dkm_product_cat_image( $term, 'dkm-card' ) : '';
				$modal_id = 'dkm-tier-' . $tier['key'];
				?>
				<button type="button"
					class="qpick-c rv"
					style="<?php echo esc_attr( dkm_tier_vars( $tier ) ); ?>"
					data-dkm-modal="<?php echo esc_attr( $modal_id ); ?>">
					<span class="qpick-c__img">
						<?php
						if ( $image ) {
							echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image output.
						}
						?>
					</span>
					<span class="qpick-b">
						<span class="qpick-b__h">
							<?php dkm_the_icon( $tier['icon'], 'qpick-b__ico' ); ?>
							<b><?php echo esc_html( $tier['name'] ); ?></b>
						</span>
						<span class="qpick-b__pr">
							<?php
							/* translators: %s is a price. */
							printf( esc_html__( 'Vanaf %s', 'dkm' ), esc_html( dkm_price( $tier['from'] ) ) );
							?>
						</span>
						<span class="qpick-b__claim"><?php echo esc_html( $tier['claim'] ); ?></span>
						<span class="qpick-b__go">
							<?php esc_html_e( 'Lees de volledige uitleg', 'dkm' ); ?>
							<?php dkm_the_icon( 'arrow' ); ?>
						</span>
					</span>
				</button>

				<?php // The modal body: main.js copies this innerHTML, and reads the header image off data-modal-head. ?>
				<div id="<?php echo esc_attr( $modal_id ); ?>"
					class="dkm-modal-source"
					data-modal-head="<?php echo esc_attr( $image ); ?>"
					hidden>
					<h3><?php echo esc_html( $tier['name'] ); ?></h3>
					<p class="sub">
						<?php
						/* translators: %1$s is a price, %2$s a short tier description. */
						printf(
							esc_html__( 'Vanaf %1$s · %2$s', 'dkm' ),
							esc_html( dkm_price( $tier['from'] ) ),
							esc_html( $tier['sub'] )
						);
						?>
					</p>

					<p class="info-modal__claim"><?php echo esc_html( $tier['claim'] ); ?></p>

					<div class="modal__l">
						<?php foreach ( $tier['bullets'] as $bullet ) : ?>
							<div>
								<?php dkm_the_icon( 'check' ); ?>
								<span><?php echo esc_html( $bullet ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>

					<div class="spec info-modal__spec">
						<?php foreach ( $spec_labels as $key => $label ) : ?>
							<?php if ( ! empty( $tier['specs'][ $key ] ) ) : ?>
								<div>
									<span><?php echo esc_html( $label ); ?></span>
									<span><?php echo esc_html( $tier['specs'][ $key ] ); ?></span>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>

					<p class="info-modal__who">
						<b><?php esc_html_e( 'Voor wie?', 'dkm' ); ?></b><br>
						<?php echo esc_html( $tier['who'] ); ?>
					</p>

					<a class="btn btn--g btn--full btn--lg" href="<?php echo esc_url( $archive ); ?>">
						<?php
						/* translators: %s is a tier name. */
						printf( esc_html__( 'Shop %s', 'dkm' ), esc_html( $tier['name'] ) );
						?>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
