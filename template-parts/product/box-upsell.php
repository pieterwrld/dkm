<?php
/**
 * Box upsell: the watch on its own, or with one of the Rolex boxes
 *
 * The radios live inside the add to cart form, so the chosen box is posted
 * along with the watch and becomes a real line on the order.
 *
 * @package DKM
 *
 * @var array $args Expects 'product'.
 */

defined( 'ABSPATH' ) || exit;

$product = $args['product'] ?? null;
if ( ! $product instanceof WC_Product ) {
	return;
}

$boxes = function_exists( 'dkm_boxes' ) ? dkm_boxes() : array();
if ( ! $boxes ) {
	return;
}

/**
 * One line about a box. The product's own short description wins, so the client
 * can overrule this from the admin.
 */
$blurb = static function ( WC_Product $box ): string {
	$own = wp_strip_all_tags( (string) $box->get_short_description() );
	if ( '' !== trim( $own ) ) {
		return trim( $own );
	}
	if ( stripos( $box->get_name(), 'premium' ) !== false ) {
		return __( 'Complete set met buitendoos, boekje en pasje', 'dkm' );
	}
	return __( 'Originele presentatiedoos met kussen', 'dkm' );
};

/**
 * What is in the box, for the preview popup.
 *
 * @return string[]
 */
$contents = static function ( WC_Product $box ): array {
	if ( stripos( $box->get_name(), 'premium' ) !== false ) {
		return array(
			__( 'Binnendoos met horlogekussen', 'dkm' ),
			__( 'Buitendoos met opdruk', 'dkm' ),
			__( 'Boekje en garantiepasje', 'dkm' ),
			__( 'Hangtags en zegel', 'dkm' ),
			__( 'Compleet zoals je hem in de winkel krijgt', 'dkm' ),
		);
	}
	return array(
		__( 'Presentatiedoos met horlogekussen', 'dkm' ),
		__( 'Opdruk en afwerking zoals origineel', 'dkm' ),
		__( 'Geschikt om je horloge in te bewaren', 'dkm' ),
		__( 'Perfect als cadeauverpakking', 'dkm' ),
	);
};

$base   = (float) wc_get_price_to_display( $product );
$no_box = __( 'Alleen het horloge', 'dkm' );
$modals = array();
?>
<div class="ups" data-dkm-upsell data-dkm-base="<?php echo esc_attr( (string) $base ); ?>">
	<div class="ups__h">
		<b><?php dkm_the_icon( 'box' ); ?><?php esc_html_e( 'Maak het compleet', 'dkm' ); ?></b>
		<span data-dkm-upsell-hint data-default="<?php echo esc_attr( $no_box ); ?>"><?php echo esc_html( $no_box ); ?></span>
	</div>

	<label class="uopt is-on">
		<input type="radio" name="dkm_box" value="0" class="screen-reader-text"
			data-price="0"
			data-label="<?php echo esc_attr( $no_box ); ?>"
			checked>
		<span class="urad" aria-hidden="true"><i></i></span>
		<?php
		// The watch itself, so the first row shows what you are actually
		// buying rather than a drawing of a watch.
		$eigen_foto = (int) $product->get_image_id();
		if ( $eigen_foto ) {
			echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$eigen_foto,
				'thumbnail',
				false,
				array(
					'class'   => 'uimg',
					'alt'     => '',
					'loading' => 'lazy',
				)
			);
		} else {
			?>
			<span class="uimg uimg--none" aria-hidden="true"><?php dkm_the_icon( 'watch' ); ?></span>
			<?php
		}
		?>
		<span class="utxt">
			<b><?php echo esc_html( $no_box ); ?></b>
			<span><?php esc_html_e( 'In onze eigen verpakking, discreet verzonden', 'dkm' ); ?></span>
		</span>
		<span class="uprice uprice--free"><?php esc_html_e( 'Inbegrepen', 'dkm' ); ?></span>
	</label>

	<?php
	foreach ( $boxes as $box ) :
		$box_price = (float) wc_get_price_to_display( $box );
		$modal_id  = 'dkm-box-' . $box->get_id();
		$thumb_id  = (int) $box->get_image_id();
		$modals[]  = array(
			'id'       => $modal_id,
			'box'      => $box,
			'thumb_id' => $thumb_id,
			'blurb'    => $blurb( $box ),
			'contents' => $contents( $box ),
			'price'    => $box_price,
		);
		?>
		<label class="uopt">
			<input type="radio" name="dkm_box" value="<?php echo esc_attr( (string) $box->get_id() ); ?>" class="screen-reader-text"
				data-price="<?php echo esc_attr( (string) $box_price ); ?>"
				data-label="<?php echo esc_attr( $box->get_name() ); ?>">
			<span class="urad" aria-hidden="true"><i></i></span>

			<?php
			if ( $thumb_id ) {
				echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					$thumb_id,
					'thumbnail',
					false,
					array(
						'class'   => 'uimg',
						'alt'     => $box->get_name(),
						'loading' => 'lazy',
					)
				);
			}
			?>

			<span class="utxt">
				<b><?php echo esc_html( $box->get_name() ); ?></b>
				<button type="button" class="ulook" data-dkm-box-preview="<?php echo esc_attr( $modal_id ); ?>">
					<?php dkm_the_icon( 'eye' ); ?><?php esc_html_e( 'Bekijk deze doos', 'dkm' ); ?>
				</button>
			</span>

			<span class="uprice">+ <?php echo wp_kses_post( wc_price( $box_price ) ); ?></span>
		</label>
	<?php endforeach; ?>
</div>

<?php
foreach ( $modals as $modal ) :
	$head = '';
	if ( $modal['thumb_id'] ) {
		// Not a wide crop: these boxes are photographed upright, and cutting
		// them to a letterbox shape lost the lid and the booklet.
		$head = wp_get_attachment_image(
			$modal['thumb_id'],
			'large',
			false,
			array( 'alt' => $modal['box']->get_name() )
		);
	}
	?>
	<div id="<?php echo esc_attr( $modal['id'] ); ?>" hidden data-modal-head="<?php echo esc_attr( $head ); ?>">
		<h3><?php echo esc_html( $modal['box']->get_name() ); ?></h3>
		<p class="sub"><?php echo esc_html( $modal['blurb'] ); ?></p>

		<div class="modal__l">
			<?php foreach ( $modal['contents'] as $line ) : ?>
				<div><?php dkm_the_icon( 'check' ); ?><span><?php echo esc_html( $line ); ?></span></div>
			<?php endforeach; ?>
		</div>

		<p class="sub" style="margin:0">
			<?php
			printf(
				/* translators: %s is a price. */
				esc_html__( 'Meerprijs bij je horloge: %s', 'dkm' ),
				wp_kses_post( wc_price( $modal['price'] ) )
			);
			?>
		</p>
	</div>
<?php endforeach; ?>
