<?php
/**
 * The running ticker above the header. The list is printed twice so the
 * translation loops seamlessly.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

$items = dkm_ticker();
if ( ! $items ) {
	return;
}
?>
<div class="tick" aria-hidden="true">
	<div class="tick__t">
		<?php for ( $copy = 0; $copy < 2; $copy++ ) : ?>
			<div class="tick__i">
				<?php foreach ( $items as $item ) : ?>
					<span><?php echo esc_html( $item ); ?></span><em>◆</em>
				<?php endforeach; ?>
			</div>
		<?php endfor; ?>
	</div>
</div>
