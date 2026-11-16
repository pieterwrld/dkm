<?php
/**
 * The running USP strip under the hero. Printed twice so the loop is seamless.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

$usps = dkm_usps();
if ( ! $usps ) {
	return;
}
?>
<div class="umq" aria-hidden="true">
	<div class="umq__t">
		<?php for ( $copy = 0; $copy < 2; $copy++ ) : ?>
			<div class="umq__s">
				<?php foreach ( $usps as [$icon, $title, $sub] ) : ?>
					<div class="umq__i">
						<?php dkm_the_icon( $icon ); ?>
						<div>
							<b><?php echo esc_html( $title ); ?></b>
							<span><?php echo esc_html( $sub ); ?></span>
						</div>
					</div>
					<i class="umq__d"></i>
				<?php endforeach; ?>
			</div>
		<?php endfor; ?>
	</div>
</div>
