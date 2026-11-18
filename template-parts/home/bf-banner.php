<?php
/**
 * The Black Friday banner.
 *
 * Two artworks, one upright for a phone and one wide for a desktop, each with
 * a strip of empty space along the bottom. The call to action sits in that
 * strip rather than over the artwork, so nothing is covered up.
 *
 * It appears and disappears with the campaign itself, through the same switch
 * as the red bar at the top of the page.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

if ( ! dkm_black_friday_active() ) {
	return;
}

$mobiel  = (int) dkm_opt( 'bf_banner_mobile', 0 );
$desktop = (int) dkm_opt( 'bf_banner_desktop', 0 );

if ( ! $mobiel && ! $desktop ) {
	return;
}

$breed  = $desktop ? $desktop : $mobiel;
$smal   = $mobiel ? $mobiel : $desktop;
$link   = (string) dkm_opt( 'bf_banner_link', '' );
$knop   = (string) dkm_opt( 'bf_banner_cta', __( 'Shop de Black Friday deals', 'dkm' ) );
$alt    = __( 'Black Friday bij De Klokjesman, kortingen tot 200 euro', 'dkm' );

if ( '' === $link ) {
	$sale = get_term_by( 'slug', 'sale', 'product_cat' );
	$link = ( $sale && ! is_wp_error( $sale ) ) ? (string) get_term_link( $sale ) : dkm_shop_url();
}
?>
<section class="bfb" aria-label="<?php esc_attr_e( 'Black Friday', 'dkm' ); ?>">
	<a class="bfb__link" href="<?php echo esc_url( $link ); ?>">
		<span class="bfb__media">
			<?php
			// Two separate images rather than one picture element. The two
			// artworks have different shapes, and a picture keeps the width and
			// height of whichever image the tag itself carries, which squashed
			// the upright one into the wide one's proportions.
			echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$smal,
				'full',
				false,
				array(
					'class'    => 'bfb__img bfb__img--smal',
					'alt'      => $alt,
					'sizes'    => '100vw',
					'decoding' => 'async',
					'loading'  => 'lazy',
				)
			);

			if ( $breed !== $smal ) {
				echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					$breed,
					'full',
					false,
					array(
						'class'    => 'bfb__img bfb__img--breed',
						'alt'      => '',
						'sizes'    => '100vw',
						'decoding' => 'async',
						'loading'  => 'lazy',
						'aria-hidden' => 'true',
					)
				);
			}
			?>
		</span>

		<span class="bfb__cta">
			<span class="bfb__btn">
				<?php echo esc_html( $knop ); ?>
				<?php dkm_the_icon( 'arrow' ); ?>
			</span>
		</span>
	</a>
</section>
