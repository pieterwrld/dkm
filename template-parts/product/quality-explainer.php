<?php
/**
 * What this quality level actually means
 *
 * @package DKM
 *
 * @var array $args Expects 'tier'.
 */

defined( 'ABSPATH' ) || exit;

$tier = $args['tier'] ?? null;
if ( ! $tier ) {
	return;
}
?>
<section class="sec sec--tight">
	<div class="wrap">
		<div class="qx">
			<span class="eyebrow"><?php esc_html_e( 'Wat houdt deze kwaliteit in?', 'dkm' ); ?></span>
			<h2 class="h-m qx__title"><?php echo esc_html( $tier['name'] ); ?>, <span class="em gr-txt"><?php echo esc_html( strtolower( $tier['sub'] ) ); ?></span></h2>
			<p class="lead qx__lead"><?php echo esc_html( $tier['who'] ); ?></p>

			<div class="qx__g">
				<?php foreach ( $tier['explain'] as [$icon, $title, $text] ) : ?>
					<div class="qx__i">
						<?php dkm_the_icon( $icon ); ?>
						<b><?php echo esc_html( $title ); ?></b>
						<p><?php echo esc_html( $text ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="qx__act">
				<a class="btn btn--o" href="<?php echo esc_url( dkm_info_page_url() ); ?>">
					<?php esc_html_e( 'Vergelijk alle kwaliteiten', 'dkm' ); ?>
					<?php dkm_the_icon( 'arrow' ); ?>
				</a>
			</div>
		</div>
	</div>
</section>
