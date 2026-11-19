<?php
/**
 * Reviews: real WhatsApp conversations, each one shown on a handset.
 *
 * The screenshot fills the whole screen of the phone. The only thing drawn on
 * top of it is the contact header, so that the header, the conversation and
 * the edge of the phone read as one object rather than a photo in a frame.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

$reviews = function_exists( 'dkm_get_reviews' ) ? dkm_get_reviews( 12 ) : array();
if ( ! $reviews ) {
	return;
}

$cijfers = array(
	array( 'stat_delivered', '1200', __( 'Horloges geleverd', 'dkm' ), '' ),
	array( 'stat_reviews', '800', __( 'reviews', 'dkm' ), '' ),
	array( 'stat_recommend', '98', __( 'Beveelt ons aan', 'dkm' ), '%' ),
);
?>
<section class="sec rvs">
	<div class="wrap">
		<div class="sec-hd sec-hd--ctr">
			<span class="eyebrow"><?php esc_html_e( 'Klantervaringen', 'dkm' ); ?></span>
			<h2 class="h-l"><?php esc_html_e( 'Wat klanten', 'dkm' ); ?> <span class="em gr-txt"><?php esc_html_e( 'écht zeggen', 'dkm' ); ?></span></h2>
			<p class="lead"><?php esc_html_e( 'Echte WhatsApp-gesprekken. Scroll door een bericht om alles te lezen.', 'dkm' ); ?></p>
		</div>

		<div class="rvn">
			<?php
			foreach ( $cijfers as list( $sleutel, $terugval, $label, $teken ) ) :
				$getal = (int) preg_replace( '/\D/', '', (string) dkm_opt( $sleutel, $terugval ) );
				?>
				<div class="rvn__i">
					<b class="gr-txt" data-dkm-count="<?php echo esc_attr( (string) $getal ); ?>"<?php echo $teken ? ' data-dkm-suffix="' . esc_attr( $teken ) . '"' : ''; ?>><?php echo esc_html( number_format_i18n( $getal ) . $teken ); ?></b>
					<span><?php echo esc_html( $label ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="rvp" data-dkm-track>
			<?php
			foreach ( $reviews as $review ) :
				$image = function_exists( 'dkm_review_image' ) ? dkm_review_image( $review->ID ) : null;
				if ( ! $image ) {
					continue;
				}
				$naam = (string) dkm_field( 'reviewer', $review->ID, get_the_title( $review ) );
				?>
				<article class="rvp__ph">
					<div class="rvp__scr">
						<span class="rvp__isl" aria-hidden="true"></span>

						<header class="rvp__wa">
							<span class="rvp__back" aria-hidden="true"><?php dkm_the_icon( 'chev' ); ?></span>
							<span class="rvp__av" aria-hidden="true">DK</span>
							<span class="rvp__naam">De Klokjesman</span>
							<span class="rvp__act" aria-hidden="true">
								<?php dkm_the_icon( 'video' ); ?>
								<?php dkm_the_icon( 'call' ); ?>
							</span>
						</header>

						<div class="rvp__chat" tabindex="0" role="group" aria-label="<?php esc_attr_e( 'Gesprek, scroll om verder te lezen', 'dkm' ); ?>">
							<img
								src="<?php echo esc_url( $image['url'] ); ?>"
								<?php if ( $image['width'] && $image['height'] ) : ?>
									width="<?php echo esc_attr( (string) $image['width'] ); ?>"
									height="<?php echo esc_attr( (string) $image['height'] ); ?>"
								<?php endif; ?>
								alt="<?php echo esc_attr( $image['alt'] ?: sprintf( /* translators: %s is a customer name. */ __( 'WhatsApp-gesprek met %s', 'dkm' ), $naam ) ); ?>"
								loading="lazy"
								decoding="async">
						</div>

						<footer class="rvp__bar" aria-hidden="true">
							<span class="rvp__plus"><?php dkm_the_icon( 'plus' ); ?></span>
							<span class="rvp__veld"></span>
							<span class="rvp__cam"><?php dkm_the_icon( 'cam' ); ?></span>
							<span class="rvp__mic"><?php dkm_the_icon( 'mic' ); ?></span>
						</footer>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

		<div class="rvnav">
			<button type="button" class="cbtn" data-dkm-track-prev aria-label="<?php esc_attr_e( 'Vorige gesprekken', 'dkm' ); ?>">
				<?php dkm_the_icon( 'left' ); ?>
			</button>
			<button type="button" class="cbtn" data-dkm-track-next aria-label="<?php esc_attr_e( 'Volgende gesprekken', 'dkm' ); ?>">
				<?php dkm_the_icon( 'arrow' ); ?>
			</button>
		</div>
	</div>
</section>
