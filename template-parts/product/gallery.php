<?php
/**
 * Product gallery: one large image with thumbnails underneath
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

$gallery_id = 'dkm-gal-' . $product->get_id();
?>
<div class="gal" id="<?php echo esc_attr( $gallery_id ); ?>">
	<div class="gal__main">
		<?php if ( $tier ) : ?>
			<span class="gal__badge gr"><?php echo esc_html( $tier['name'] ); ?></span>
		<?php endif; ?>

		<?php
		if ( $ids ) {
			echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$ids[0],
				'dkm-card-2x',
				false,
				array(
					'alt'           => $name,
					'class'         => 'gal__img dkm-eager',
					'data-gal-main' => 'true',
					'sizes'         => '(max-width:900px) 100vw, 640px',
				)
			);
		} else {
			echo '<span class="gal__ph" aria-hidden="true">' . dkm_icon( 'watch' ) . '</span>';
		}
		?>
	</div>

	<?php if ( count( $ids ) > 1 ) : ?>
		<div class="gal__th" role="group" aria-label="<?php esc_attr_e( 'Meer foto’s van dit horloge', 'dkm' ); ?>">
			<?php foreach ( $ids as $index => $id ) : ?>
				<?php
				$full = wp_get_attachment_image_url( $id, 'dkm-card-2x' );
				$srcset = wp_get_attachment_image_srcset( $id, 'dkm-card-2x' );
				?>
				<button
					type="button"
					class="gal__thumb<?php echo 0 === $index ? ' is-on' : ''; ?>"
					data-gal-thumb
					data-full="<?php echo esc_url( (string) $full ); ?>"
					data-srcset="<?php echo esc_attr( (string) $srcset ); ?>"
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

		<script>
		( function () {
			var root = document.getElementById( <?php echo wp_json_encode( $gallery_id ); ?> );
			if ( ! root ) {
				return;
			}
			var main = root.querySelector( '[data-gal-main]' );
			var thumbs = root.querySelectorAll( '[data-gal-thumb]' );
			if ( ! main || ! thumbs.length ) {
				return;
			}
			Array.prototype.forEach.call( thumbs, function ( thumb ) {
				thumb.addEventListener( 'click', function () {
					main.src = thumb.getAttribute( 'data-full' );
					main.srcset = thumb.getAttribute( 'data-srcset' ) || '';
					Array.prototype.forEach.call( thumbs, function ( other ) {
						other.classList.toggle( 'is-on', other === thumb );
					} );
				} );
			} );
		} )();
		</script>
	<?php endif; ?>
</div>
