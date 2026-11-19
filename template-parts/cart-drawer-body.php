<?php
/**
 * The lines inside the cart drawer, plus the box offer when none is in the cart
 * yet. Rendered server side and swapped in whole after every cart change.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
	return;
}

$lines = dkm_cart_lines();

if ( ! $lines ) :
	?>
	<div class="cart-empty">
		<?php dkm_the_icon( 'cart' ); ?>
		<p><?php esc_html_e( 'Je winkelwagen is nog leeg.', 'dkm' ); ?></p>
		<p style="margin-top:14px">
			<a class="btn btn--o" href="<?php echo esc_url( dkm_shop_url() ); ?>"><?php esc_html_e( 'Bekijk de collectie', 'dkm' ); ?></a>
		</p>
	</div>
	<?php
	return;
endif;

$tekort = function_exists( 'dkm_free_shipping_left' ) ? dkm_free_shipping_left() : 0.0;
?>
<div class="cart-ship<?php echo $tekort > 0 ? '' : ' is-free'; ?>">
	<?php dkm_the_icon( 'truck' ); ?>
	<span>
		<?php
		if ( $tekort > 0 ) {
			printf(
				/* translators: %s is an amount such as EUR 40. */
				esc_html__( 'Nog %s en de verzending is gratis', 'dkm' ),
				esc_html( dkm_price( $tekort ) )
			);
		} else {
			esc_html_e( 'Je verzending is gratis', 'dkm' );
		}
		?>
	</span>
</div>
<?php

foreach ( $lines as $line ) :
	$product = $line['product'];
	$item    = $line['item'];
	$child   = $line['child'];
	$name    = dkm_short_title( $product->get_name() );
	$tier    = dkm_product_tier( $product );
	?>
	<div class="cart-row">
		<?php
		$thumb_id = (int) $product->get_image_id();
		if ( $thumb_id ) {
			echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$thumb_id,
				'thumbnail',
				false,
				array(
					'class'   => 'cart-row__img',
					'alt'     => $name,
					'loading' => 'lazy',
				)
			);
		}
		?>

		<div class="cart-row__t">
			<b><?php echo esc_html( $name ); ?></b>
			<span class="cart-row__meta">
				<?php
				printf(
					/* translators: %1$s is a tier name, %2$d a quantity. */
					esc_html__( '%1$s · %2$d stuk', 'dkm' ),
					esc_html( $tier ? $tier['name'] : __( 'Accessoire', 'dkm' ) ),
					(int) $item['quantity']
				);
				?>
			</span>

			<button type="button" class="cart-row__x" data-dkm-cart-remove="<?php echo esc_attr( $line['key'] ); ?>">
				<?php esc_html_e( 'Verwijderen', 'dkm' ); ?>
			</button>
		</div>

		<span class="cart-row__p">
			<?php echo wp_kses_post( wc_price( (float) $item['line_subtotal'] + (float) $item['line_subtotal_tax'] ) ); ?>
		</span>
	</div>

	<?php
	// The box gets a line of its own, with its photograph, its price and its
	// own way out. It used to be a word under the watch that you could only
	// get rid of by throwing the watch away with it.
	$box = $child ? ( $child['item']['data'] ?? null ) : null;
	if ( $box instanceof WC_Product ) :
		$box_thumb = (int) $box->get_image_id();
		?>
		<div class="cart-row cart-row--box">
			<?php
			if ( $box_thumb ) {
				echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					$box_thumb,
					'thumbnail',
					false,
					array(
						'class'   => 'cart-row__img',
						'alt'     => $box->get_name(),
						'loading' => 'lazy',
					)
				);
			}
			?>

			<div class="cart-row__t">
				<b><?php dkm_the_icon( 'box' ); ?><?php echo esc_html( $box->get_name() ); ?></b>
				<span class="cart-row__meta">
					<?php
					printf(
						/* translators: %s is a watch name. */
						esc_html__( 'Bij je %s', 'dkm' ),
						esc_html( $name )
					);
					?>
				</span>

				<button type="button" class="cart-row__x" data-dkm-cart-remove="<?php echo esc_attr( $child['key'] ); ?>">
					<?php esc_html_e( 'Doos verwijderen', 'dkm' ); ?>
				</button>
			</div>

			<span class="cart-row__p">
				<?php echo wp_kses_post( wc_price( (float) $child['item']['line_subtotal'] + (float) $child['item']['line_subtotal_tax'] ) ); ?>
			</span>
		</div>
	<?php endif; ?>
<?php endforeach; ?>

<?php
$boxes = function_exists( 'dkm_boxes' ) ? dkm_boxes() : array();
if ( $boxes && ! dkm_cart_has_box() ) :
	?>
	<div class="cart-up">
		<span class="eyebrow"><?php esc_html_e( 'Maak het compleet', 'dkm' ); ?></span>
		<?php foreach ( $boxes as $box ) : ?>
			<div class="cart-up__row">
				<?php
				$box_thumb = (int) $box->get_image_id();
				if ( $box_thumb ) {
					echo wp_get_attachment_image( $box_thumb, 'thumbnail', false, array( 'alt' => $box->get_name(), 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
				<div class="cart-up__t">
					<b><?php echo esc_html( $box->get_name() ); ?></b>
					<span><?php echo wp_kses_post( $box->get_price_html() ); ?></span>
				</div>
				<button type="button" class="cart-up__add" data-dkm-add-box="<?php echo esc_attr( (string) $box->get_id() ); ?>">
					<?php esc_html_e( 'Toevoegen', 'dkm' ); ?>
				</button>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
