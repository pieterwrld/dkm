<?php
/**
 * Template Name: Kwaliteitsinformatie
 *
 * The standalone page that explains the three quality tiers side by side.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

get_header();

the_post();

$heading     = get_the_title();
// A page called Informatie or Kwaliteitsinformatie says nothing on its own, so
// the heading this template was written with is used instead. A title the shop
// chose itself still wins.
$algemeen    = array( __( 'Kwaliteitsinformatie', 'dkm' ), __( 'Informatie', 'dkm' ) );
$has_heading = '' !== trim( (string) $heading ) && ! in_array( $heading, $algemeen, true );
// A page that was once laid out in the builder still carries that layout in
// its content, and here it would print the three levels a second time, in the
// builder's own styling. This template draws them itself, so on such a page
// the written intro is left out and the standard line is used.
$intro = dkm_is_builder_page( get_the_ID() ) ? '' : trim( (string) get_the_content() );
?>

<section class="info-head">
	<div class="wrap">
		<nav class="crumb" aria-label="<?php esc_attr_e( 'Kruimelpad', 'dkm' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'dkm' ); ?></a>
			<span class="crumb__sep" aria-hidden="true">›</span>
			<span><?php esc_html_e( 'Kwaliteitsinformatie', 'dkm' ); ?></span>
		</nav>

		<span class="eyebrow"><?php esc_html_e( 'Kwaliteitsinformatie', 'dkm' ); ?></span>

		<h1 class="h-l info-head__title">
			<?php if ( $has_heading ) : ?>
				<?php echo esc_html( $heading ); ?>
			<?php else : ?>
				<?php esc_html_e( 'Het verschil tussen', 'dkm' ); ?>
				<span class="em gr-txt"><?php esc_html_e( 'onze drie kwaliteiten', 'dkm' ); ?></span>
			<?php endif; ?>
		</h1>

		<div class="lead info-head__lead">
			<?php if ( '' !== $intro ) : ?>
				<?php the_content(); ?>
			<?php else : ?>
				<p><?php esc_html_e( 'Superclone, BP Swiss Made en AAA+. Hieronder leggen we per niveau uit wat je krijgt, waar het verschil in zit en voor wie het bedoeld is.', 'dkm' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php
get_template_part( 'template-parts/quality-pick' );
get_template_part( 'template-parts/quality-matrix' );
?>

<section class="sec sec--tight">
	<div class="wrap">
		<div class="sec-hd sec-hd--ctr">
			<span class="eyebrow"><?php esc_html_e( 'Premium features', 'dkm' ); ?></span>
			<h2 class="h-l"><?php esc_html_e( 'Op elk', 'dkm' ); ?> <span class="em gr-txt"><?php esc_html_e( 'horloge', 'dkm' ); ?></span></h2>
		</div>

		<div class="qx-g">
			<?php foreach ( dkm_premium_features() as [$icon, $title, $text] ) : ?>
				<div class="qx-i rv">
					<?php dkm_the_icon( $icon ); ?>
					<b><?php echo esc_html( $title ); ?></b>
					<p><?php echo esc_html( $text ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
get_footer();
