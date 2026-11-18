<?php
/**
 * AAA+ product page
 *
 * The expectations module comes first, because at this price the risk is a
 * buyer hoping for superclone detail and being disappointed.
 *
 * @package DKM
 *
 * @var array $args Expects 'product' and 'tier'.
 */

defined( 'ABSPATH' ) || exit;

$product = $args['product'] ?? null;
$tier    = $args['tier'] ?? null;
if ( ! $product instanceof WC_Product ) {
	return;
}

$not = array(
	__( 'Geen saffierglas, maar krasbestendig mineraalglas', 'dkm' ),
	__( 'Geen 904L staal en niet het gewicht van het origineel', 'dkm' ),
	__( 'Gravures komen in de buurt, maar zijn niet exact', 'dkm' ),
	__( 'Complicaties zijn deels decoratief', 'dkm' ),
);
?>
<div class="wrap">
	<div class="pd">
		<?php get_template_part( 'template-parts/product/gallery', null, $args ); ?>
		<?php get_template_part( 'template-parts/product/summary', null, $args ); ?>
	</div>
</div>

<section class="sec sec--tight">
	<div class="wrap">
		<div class="expect">
			<div class="expect__hd">
				<span class="eyebrow"><?php esc_html_e( 'Eerlijk verhaal', 'dkm' ); ?></span>
				<h2 class="h-l"><?php esc_html_e( 'Wat je voor deze prijs', 'dkm' ); ?> <span class="em gr-txt"><?php esc_html_e( 'wel en niet krijgt', 'dkm' ); ?></span></h2>
				<p class="lead"><?php echo esc_html( $tier['who'] ); ?></p>
			</div>

			<div class="expect__g">
				<div class="expect__col expect__col--yes">
					<h3 class="expect__t"><?php dkm_the_icon( 'check' ); ?><?php esc_html_e( 'Dit krijg je', 'dkm' ); ?></h3>
					<ul class="expect__l">
						<?php foreach ( $tier['bullets'] as $bullet ) : ?>
							<li>
								<?php echo dkm_mark( 'y' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<span><?php echo esc_html( $bullet ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>

				<div class="expect__col expect__col--no">
					<h3 class="expect__t"><?php dkm_the_icon( 'info' ); ?><?php esc_html_e( 'Dit krijg je niet', 'dkm' ); ?></h3>
					<ul class="expect__l">
						<?php foreach ( $not as $line ) : ?>
							<li>
								<?php echo dkm_mark( 'n' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<span><?php echo esc_html( $line ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
					<p class="expect__up">
						<?php esc_html_e( 'Wil je dat wel? Dan is BP Swiss Made de volgende stap.', 'dkm' ); ?>
						<?php
						$bp_term = get_term_by( 'slug', 'bp-swiss-made', 'product_cat' );
						if ( $bp_term && ! is_wp_error( $bp_term ) ) :
							?>
							<a class="lnk" href="<?php echo esc_url( (string) get_term_link( $bp_term ) ); ?>">
								<?php esc_html_e( 'Bekijk BP Swiss Made', 'dkm' ); ?>
								<?php dkm_the_icon( 'arrow' ); ?>
							</a>
						<?php endif; ?>
					</p>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_template_part( 'template-parts/product/quality-explainer', null, $args );
get_template_part( 'template-parts/product/delivery', null, $args );
?>
