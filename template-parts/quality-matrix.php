<?php
/**
 * The comparison table: green is present, orange is partly, red is not
 * applicable. Every feature carries an i with the reason behind the verdict.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

$matrix = dkm_matrix();
$tiers  = dkm_tiers();
if ( ! $matrix || ! $tiers ) {
	return;
}

// The coloured marks carry meaning, so each one also reads out as a word.
$readable = array(
	'y' => __( 'aanwezig', 'dkm' ),
	'p' => __( 'gedeeltelijk', 'dkm' ),
	'n' => __( 'niet van toepassing', 'dkm' ),
);
?>
<section class="sec sec--tight">
	<div class="wrap">
		<div class="sec-hd">
			<span class="eyebrow"><?php esc_html_e( 'Vergelijking', 'dkm' ); ?></span>
			<h2 class="h-l"><?php esc_html_e( 'Alles naast', 'dkm' ); ?> <span class="em gr-txt"><?php esc_html_e( 'elkaar', 'dkm' ); ?></span></h2>
			<p class="lead"><?php esc_html_e( 'Groen is aanwezig, oranje is gedeeltelijk, rood betekent niet van toepassing.', 'dkm' ); ?></p>
		</div>

		<div class="tblwrap" tabindex="0" role="region" aria-label="<?php esc_attr_e( 'Vergelijking van de kwaliteiten', 'dkm' ); ?>">
			<table class="cmp-tbl">
				<thead>
					<tr>
						<th scope="col"><?php esc_html_e( 'Kenmerk', 'dkm' ); ?></th>
						<?php foreach ( $tiers as $tier ) : ?>
							<th scope="col"><?php echo esc_html( $tier['name'] ); ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $matrix as [$label, $verdicts, $explanation] ) : ?>
						<tr>
							<th scope="row">
								<span class="cmp-tbl__label"><?php echo esc_html( $label ); ?></span>
								<button type="button" class="inf" aria-expanded="false">
									i
									<span class="inf__tip"><?php echo esc_html( $explanation ); ?></span>
								</button>
							</th>
							<?php foreach ( $tiers as $tier ) : ?>
								<td>
									<?php
									$verdict = $verdicts[ $tier['key'] ] ?? '';
									echo dkm_mark( $verdict ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed markup from a verdict table.
									?>
									<span class="screen-reader-text"><?php echo esc_html( $readable[ $verdict ] ?? '' ); ?></span>
								</td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</section>
