<?php
/**
 * The three quality cards above the collection filters.
 *
 * They stay on one line at every width, which is why the card body shrinks
 * rather than the grid reflowing.
 *
 * @package DKM
 *
 * @var array $args Optional 'current' slug to mark the active card.
 */

defined( 'ABSPATH' ) || exit;

$current = $args['current'] ?? '';
$tiers   = dkm_tiers();
if ( ! $tiers ) {
	return;
}
?>
<div class="qcards">
	<?php
	foreach ( $tiers as $slug => $tier ) :
		$term = get_term_by( 'slug', $slug, 'product_cat' );
		if ( ! $term || is_wp_error( $term ) ) {
			continue;
		}
		$image = dkm_product_cat_image( $term, 'dkm-card' );
		?>
		<a class="qcard<?php echo $current === $slug ? ' is-on' : ''; ?>"
			href="<?php echo esc_url( (string) get_term_link( $term ) ); ?>"
			style="<?php echo esc_attr( dkm_tier_vars( $tier ) ); ?>">

			<span class="qcard__img">
				<?php
				if ( $image ) {
					echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image output.
				}
				?>
			</span>

			<span class="qcard__b">
				<span class="qcard__h"><?php echo esc_html( $tier['name'] ); ?></span>
				<span class="qcard__sub"><?php echo esc_html( $tier['sub'] ); ?></span>
				<span class="qcard__row">
					<b>
						<?php
						/* translators: %s is a price. */
						printf( esc_html__( 'Vanaf %s', 'dkm' ), esc_html( dkm_price( $tier['from'] ) ) );
						?>
					</b>
					<span class="qcard__go">
						<?php esc_html_e( 'Bekijk', 'dkm' ); ?>
						<?php dkm_the_icon( 'arrow' ); ?>
					</span>
				</span>
			</span>
		</a>
	<?php endforeach; ?>
</div>
