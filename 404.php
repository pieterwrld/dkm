<?php
/**
 * Not found
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<section class="sec" style="text-align:center">
	<div class="wrap">
		<span class="eyebrow"><?php esc_html_e( '404', 'dkm' ); ?></span>
		<h1 class="h-l" style="margin:10px 0 8px">
			<?php esc_html_e( 'Deze pagina is', 'dkm' ); ?> <span class="em gr-txt"><?php esc_html_e( 'niet te vinden', 'dkm' ); ?></span>
		</h1>
		<p class="lead" style="margin:0 auto 24px;max-width:46ch">
			<?php esc_html_e( 'Misschien is het horloge uitverkocht of is de link verouderd. Onze collectie staat er nog wel.', 'dkm' ); ?>
		</p>
		<div class="hero__cta" style="justify-content:center">
			<a class="btn btn--g btn--lg" href="<?php echo esc_url( dkm_shop_url() ); ?>"><?php esc_html_e( 'Bekijk de collectie', 'dkm' ); ?></a>
			<a class="btn btn--o btn--lg" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Naar de homepage', 'dkm' ); ?></a>
		</div>
	</div>
</section>

<?php
get_footer();
