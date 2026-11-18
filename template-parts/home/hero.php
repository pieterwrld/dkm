<?php
/**
 * Hero: full bleed image with Ken Burns, a gold sweep and a drifting grid
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

$front_id = (int) get_option( 'page_on_front' );

$image_id = (int) dkm_field( 'hero_image', $front_id, 0 );
$badge    = (string) dkm_field( 'hero_badge', $front_id, __( 'Op voorraad · vandaag besteld, morgen in huis', 'dkm' ) );
$title    = (string) dkm_field( 'hero_title', $front_id, __( 'Elegant. Exclusief.', 'dkm' ) );
$accent   = (string) dkm_field( 'hero_accent', $front_id, __( 'Tijdloos.', 'dkm' ) );
$text     = (string) dkm_field( 'hero_text', $front_id, __( 'Drie kwaliteitsniveaus, één standaard. Van een scherp geprijsd instapmodel tot een 1:1 superclone met Zwitsers uurwerk en saffierglas.', 'dkm' ) );

$delivered = (string) get_theme_mod( 'dkm_stat_delivered', '1200' );
$reviews   = (string) get_theme_mod( 'dkm_stat_reviews', '800' );
$rating    = (string) get_theme_mod( 'dkm_stat_rating', '4.9' );
?>
<section class="hero">
	<div class="hero__bg">
		<?php
		if ( $image_id ) {
			echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$image_id,
				'full',
				false,
				array(
					'class' => 'dkm-eager',
					'alt'   => get_bloginfo( 'name' ),
					'sizes' => '100vw',
				)
			);
		}
		?>
	</div>
	<div class="hero__grid" aria-hidden="true"></div>
	<div class="hero__sweep" aria-hidden="true"></div>

	<div class="wrap hero__in">
		<?php if ( $badge ) : ?>
			<span class="hero__badge"><i aria-hidden="true"></i><?php echo esc_html( $badge ); ?></span>
		<?php endif; ?>

		<h1 class="h-xl hero__title"><?php
			// Printed without a break between the two, so the line wraps on the
			// space itself and not on the indentation of this file.
			echo esc_html( $title );
			if ( $accent ) {
				echo ' <span class="em gr-txt">' . esc_html( $accent ) . '</span>';
			}
		?></h1>

		<?php if ( $text ) : ?>
			<p class="lead hero__lead"><?php echo esc_html( $text ); ?></p>
		<?php endif; ?>

		<div class="hero__cta">
			<a class="btn btn--g btn--lg" href="<?php echo esc_url( dkm_shop_url() ); ?>"><?php esc_html_e( 'Bekijk de collectie', 'dkm' ); ?></a>
			<a class="btn btn--gh btn--lg" href="<?php echo esc_url( dkm_info_page_url() ); ?>"><?php esc_html_e( 'Vergelijk kwaliteiten', 'dkm' ); ?></a>
		</div>

		<div class="hero__stats">
			<div class="hero__stat">
				<b><?php echo esc_html( $delivered ); ?>+</b>
				<span><?php esc_html_e( 'Horloges geleverd', 'dkm' ); ?></span>
			</div>
			<div class="hero__stat">
				<b><?php echo esc_html( $reviews ); ?>+</b>
				<span><?php esc_html_e( 'Vijfsterrenreviews', 'dkm' ); ?></span>
			</div>
			<div class="hero__stat">
				<b class="hero__stars" aria-hidden="true">★★★★★</b>
				<span>
					<?php
					/* translators: %s is an average rating such as 4.9. */
					printf( esc_html__( '%s gemiddeld', 'dkm' ), esc_html( $rating ) );
					?>
				</span>
			</div>
		</div>
	</div>

	<div class="hero__scroll" aria-hidden="true">
		<span><?php esc_html_e( 'Scroll', 'dkm' ); ?></span>
		<i></i>
	</div>
</section>
