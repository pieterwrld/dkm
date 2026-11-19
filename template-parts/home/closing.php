<?php
/**
 * Closing section: one photograph that grows while you scroll past it.
 *
 * The picture starts as a framed panel in the middle of the screen and opens
 * out to fill it. Four reasons to buy sit on top, two by two at every width.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

$image_id = (int) dkm_opt( 'closing_image', 0 );
if ( ! $image_id ) {
	return;
}

$usps = array(
	array( 'shield', __( 'Kwaliteitsgarantie', 'dkm' ), __( 'Elk horloge gecontroleerd voor verzending', 'dkm' ) ),
	array( 'truck', __( 'Morgen in huis', 'dkm' ), __( 'Vandaag besteld, discreet verpakt onderweg', 'dkm' ) ),
	array( 'chat', __( 'Persoonlijk advies', 'dkm' ), __( 'Direct contact via WhatsApp, ook na je aankoop', 'dkm' ) ),
	array( 'truck', __( 'Gratis verzending', 'dkm' ), sprintf( /* translators: %s is an amount such as €350. */ __( 'Vanaf %s betaal je geen verzendkosten', 'dkm' ), dkm_price( dkm_free_shipping_from() ) ) ),
);
?>
<section class="cls" data-dkm-grow>
	<div class="cls__stage">
		<div class="cls__frame">
			<?php
			echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$image_id,
				'full',
				false,
				array(
					'class'    => 'cls__img',
					'alt'      => '',
					'sizes'    => '100vw',
					'loading'  => 'lazy',
					'decoding' => 'async',
				)
			);
			?>
			<span class="cls__veil" aria-hidden="true"></span>

			<div class="cls__in">
				<span class="eyebrow"><?php esc_html_e( 'Waarom De Klokjesman', 'dkm' ); ?></span>
				<h2 class="h-l cls__t"><?php
					// Printed without a break, so the line wraps on the space
					// itself and not on the indentation of this file.
					esc_html_e( 'Een horloge kopen ', 'dkm' );
					echo '<span class="em gr-txt">' . esc_html__( 'zonder twijfel', 'dkm' ) . '</span>';
				?></h2>

				<ul class="cls__usps">
					<?php foreach ( $usps as $usp ) : ?>
						<li class="cls__usp">
							<span class="cls__ico"><?php dkm_the_icon( $usp[0] ); ?></span>
							<b><?php echo esc_html( $usp[1] ); ?></b>
							<span><?php echo esc_html( $usp[2] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>

				<a class="btn btn--g btn--lg cls__cta" href="<?php echo esc_url( dkm_shop_url() ); ?>">
					<?php esc_html_e( 'Bekijk de collectie', 'dkm' ); ?>
					<?php dkm_the_icon( 'arrow' ); ?>
				</a>
			</div>
		</div>
	</div>
</section>
