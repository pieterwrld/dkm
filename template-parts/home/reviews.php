<?php
/**
 * Reviews: real WhatsApp conversations in a card with our own header, so the
 * screenshot's own interface is cropped away server side.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

$reviews = function_exists( 'dkm_get_reviews' ) ? dkm_get_reviews( 12 ) : array();
if ( ! $reviews ) {
	return;
}

$delivered = (int) preg_replace( '/\D/', '', (string) get_theme_mod( 'dkm_stat_delivered', '1200' ) );
$five      = (int) preg_replace( '/\D/', '', (string) get_theme_mod( 'dkm_stat_reviews', '800' ) );
$recommend = (int) preg_replace( '/\D/', '', (string) get_theme_mod( 'dkm_stat_recommend', '98' ) );
?>
<section class="sec">
	<div class="wrap">
		<div class="sec-hd sec-hd--ctr">
			<span class="eyebrow"><?php esc_html_e( 'Klantervaringen', 'dkm' ); ?></span>
			<h2 class="h-l"><?php esc_html_e( 'Wat klanten', 'dkm' ); ?> <span class="em gr-txt"><?php esc_html_e( 'écht zeggen', 'dkm' ); ?></span></h2>
			<p class="lead"><?php esc_html_e( 'Echte WhatsApp-gesprekken. Scroll door een bericht om alles te lezen.', 'dkm' ); ?></p>
		</div>

		<div class="rv-stats">
			<div class="rv-s">
				<b class="gr-txt" data-dkm-count="<?php echo esc_attr( (string) $delivered ); ?>"><?php echo esc_html( (string) $delivered ); ?></b>
				<span><?php esc_html_e( 'Horloges geleverd', 'dkm' ); ?></span>
			</div>
			<div class="rv-s">
				<b class="gr-txt" data-dkm-count="<?php echo esc_attr( (string) $five ); ?>"><?php echo esc_html( (string) $five ); ?></b>
				<span><?php esc_html_e( 'reviews', 'dkm' ); ?></span>
			</div>
			<div class="rv-s">
				<b class="gr-txt" data-dkm-count="<?php echo esc_attr( (string) $recommend ); ?>" data-dkm-suffix="%"><?php echo esc_html( (string) $recommend ); ?>%</b>
				<span><?php esc_html_e( 'Beveelt ons aan', 'dkm' ); ?></span>
			</div>
		</div>

		<div class="rvgrid" data-dkm-track>
			<?php
			foreach ( $reviews as $review ) :
				$image    = function_exists( 'dkm_review_image' ) ? dkm_review_image( $review->ID ) : null;
				$name     = (string) dkm_field( 'reviewer', $review->ID, get_the_title( $review ) );
				$rating   = (float) dkm_field( 'rating', $review->ID, 5 );
				$verified = (bool) dkm_field( 'verified', $review->ID, true );
				$initials = dkm_initials( $name );
				?>
				<article class="rvc rv">
					<span class="rvc__notch" aria-hidden="true"></span>
					<header class="rvc__h">
						<span class="rvc__av" aria-hidden="true"><?php echo esc_html( $initials ); ?></span>
						<span class="rvc__who">
							<b><?php echo esc_html( $name ); ?></b>
							<span><?php esc_html_e( 'Online', 'dkm' ); ?></span>
						</span>
						<span class="rvc__wa"><?php dkm_the_icon( 'wa' ); ?></span>
					</header>

					<?php if ( $image ) : ?>
						<div class="rvc__s scrollable" tabindex="0" role="group" aria-label="<?php esc_attr_e( 'Gesprek, scroll om verder te lezen', 'dkm' ); ?>">
							<img
								src="<?php echo esc_url( $image['url'] ); ?>"
								<?php if ( $image['width'] && $image['height'] ) : ?>
									width="<?php echo esc_attr( (string) $image['width'] ); ?>"
									height="<?php echo esc_attr( (string) $image['height'] ); ?>"
								<?php endif; ?>
								alt="<?php echo esc_attr( $image['alt'] ?: sprintf( /* translators: %s is a customer name. */ __( 'Gesprek met %s', 'dkm' ), $name ) ); ?>"
								loading="lazy"
								decoding="async">
						</div>
					<?php endif; ?>

					<footer class="rvc__f">
						<?php echo dkm_stars( $rating ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php if ( $verified ) : ?>
							<span class="rvc__vf"><?php dkm_the_icon( 'check' ); ?><?php esc_html_e( 'Geverifieerd', 'dkm' ); ?></span>
						<?php endif; ?>
					</footer>
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
