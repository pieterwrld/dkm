<?php
/**
 * Product gallery: a row of photos you can swipe, with arrows and thumbnails
 *
 * The photos sit in a track that scrolls sideways, so a finger moves them the
 * way it does anywhere else on a phone. The arrows and the thumbnails scroll
 * that same track, which keeps one source of truth for which photo is showing.
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

$name = dkm_short_title( $product->get_name() );

$ids = array();
if ( $product->get_image_id() ) {
	$ids[] = (int) $product->get_image_id();
}
foreach ( $product->get_gallery_image_ids() as $gallery_id ) {
	$ids[] = (int) $gallery_id;
}
$ids = array_values( array_unique( array_filter( $ids ) ) );

$meer = count( $ids ) > 1;
?>
<div class="gal">
	<div class="gal__main shots" data-dkm-shots>
		<?php if ( $tier ) : ?>
			<span class="gal__badge gr"><?php echo esc_html( $tier['name'] ); ?></span>
		<?php endif; ?>

		<?php if ( $ids ) : ?>
			<div class="shots__track"
				data-dkm-shots-track
				<?php if ( $meer ) : ?>
					tabindex="0"
					role="group"
					aria-label="<?php esc_attr_e( 'Foto’s van dit horloge, veeg om verder te kijken', 'dkm' ); ?>"
				<?php endif; ?>>
				<?php foreach ( $ids as $index => $id ) : ?>
					<?php
					echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						$id,
						'dkm-card-2x',
						false,
						array(
							'class'   => 'gal__img' . ( 0 === $index ? ' dkm-eager' : '' ),
							'alt'     => 0 === $index ? $name : '',
							'loading' => 0 === $index ? 'eager' : 'lazy',
							'sizes'   => '(max-width:900px) 100vw, 640px',
						)
					);
					?>
				<?php endforeach; ?>
			</div>

			<?php if ( $meer ) : ?>
				<button type="button" class="gal__nav gal__nav--prev" data-dkm-shots-prev>
					<?php dkm_the_icon( 'left', '', __( 'Vorige foto', 'dkm' ) ); ?>
				</button>
				<button type="button" class="gal__nav gal__nav--next" data-dkm-shots-next>
					<?php dkm_the_icon( 'arrow', '', __( 'Volgende foto', 'dkm' ) ); ?>
				</button>

				<span class="shots__bar" aria-hidden="true">
					<?php foreach ( $ids as $index => $id ) : ?>
						<i<?php echo 0 === $index ? ' class="is-on"' : ''; ?>></i>
					<?php endforeach; ?>
				</span>
			<?php endif; ?>
		<?php else : ?>
			<span class="gal__ph" aria-hidden="true"><?php dkm_the_icon( 'watch' ); ?></span>
		<?php endif; ?>
	</div>

	<?php if ( $meer ) : ?>
		<div class="gal__th" role="group" aria-label="<?php esc_attr_e( 'Meer foto’s van dit horloge', 'dkm' ); ?>">
			<?php foreach ( $ids as $index => $id ) : ?>
				<button
					type="button"
					class="gal__thumb<?php echo 0 === $index ? ' is-on' : ''; ?>"
					data-dkm-shots-go="<?php echo esc_attr( (string) $index ); ?>"
					aria-label="<?php
						/* translators: %d is the photo number. */
						printf( esc_attr__( 'Foto %d bekijken', 'dkm' ), (int) $index + 1 );
					?>">
					<?php
					echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						$id,
						'thumbnail',
						false,
						array(
							'alt'     => '',
							'loading' => 'lazy',
						)
					);
					?>
				</button>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
