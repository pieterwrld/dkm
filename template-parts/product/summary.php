<?php
/**
 * Product summary: tier, title, claim, price, box upsell, add to cart, USPs and
 * the detail accordion
 *
 * @package DKM
 *
 * @var array $args Expects 'product', 'tier' and optionally 'show_upsell'.
 */

defined( 'ABSPATH' ) || exit;

$product = $args['product'] ?? null;
$tier    = $args['tier'] ?? null;
if ( ! $product instanceof WC_Product ) {
	return;
}

$show_upsell = $args['show_upsell'] ?? ( function_exists( 'dkm_takes_box' ) && dkm_takes_box( $product ) );
$name        = dkm_short_title( $product->get_name() );
$rating      = (float) $product->get_average_rating();
$reviews     = (int) $product->get_review_count();
$model       = dkm_attr( $product, 'pa_model' );
$size        = dkm_attr( $product, 'pa_maat' );
$dial        = dkm_attr( $product, 'pa_wijzerplaat' );
$percent     = dkm_sale_percentage( $product );
$base        = (float) wc_get_price_to_display( $product );
?>
<div class="pd__info">
	<?php if ( $tier ) : ?>
		<span class="pd__tier">
			<?php dkm_the_icon( $tier['icon'] ); ?><?php echo esc_html( $tier['name'] ); ?>
		</span>
	<?php endif; ?>

	<h1 class="pd__title"><?php echo esc_html( $name ); ?></h1>

	<?php if ( $tier ) : ?>
		<p class="pd__claim"><?php echo esc_html( $tier['claim'] ); ?></p>
	<?php endif; ?>

	<?php if ( $reviews > 0 ) : ?>
		<div class="pd__rate">
			<?php echo dkm_stars( $rating ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<span>
				<?php
				printf(
					/* translators: %1$s is an average rating, %2$d a number of reviews. */
					esc_html( _n( '%1$s uit %2$d review', '%1$s uit %2$d reviews', $reviews, 'dkm' ) ),
					esc_html( number_format_i18n( $rating, 1 ) ),
					(int) $reviews
				);
				?>
			</span>
		</div>
	<?php else : ?>
		<div class="pd__rate">
			<?php echo dkm_stars( 5.0 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<span><?php esc_html_e( '800+ vijfsterrenreviews op onze collectie', 'dkm' ); ?></span>
		</div>
	<?php endif; ?>

	<div class="pd__pr">
		<?php echo wp_kses_post( $product->get_price_html() ); ?>
		<?php if ( $percent ) : ?>
			<span class="pd__off"><?php echo esc_html( '-' . $percent . '%' ); ?></span>
		<?php endif; ?>
	</div>

	<?php if ( ! $product->is_in_stock() ) : ?>
		<p class="pd__oos">
			<?php dkm_the_icon( 'info' ); ?>
			<?php esc_html_e( 'Dit model is tijdelijk uitverkocht. Stuur ons een bericht, dan laten we weten wanneer hij terug is.', 'dkm' ); ?>
		</p>
	<?php endif; ?>

	<form class="pd__form" data-dkm-add-to-cart="<?php echo esc_attr( (string) $product->get_id() ); ?>">
		<?php
		if ( $show_upsell ) {
			get_template_part( 'template-parts/product/box-upsell', null, array( 'product' => $product ) );
		}
		?>

		<div class="pd__acts">
			<div class="qty" data-dkm-qty>
				<button type="button" data-dir="down" aria-label="<?php esc_attr_e( 'Eén minder', 'dkm' ); ?>">
					<?php dkm_the_icon( 'minus' ); ?>
				</button>
				<label class="screen-reader-text" for="dkm-qty-<?php echo esc_attr( (string) $product->get_id() ); ?>">
					<?php esc_html_e( 'Aantal', 'dkm' ); ?>
				</label>
				<input
					id="dkm-qty-<?php echo esc_attr( (string) $product->get_id() ); ?>"
					type="number"
					name="quantity"
					value="1"
					min="1"
					step="1"
					inputmode="numeric">
				<button type="button" data-dir="up" aria-label="<?php esc_attr_e( 'Eén meer', 'dkm' ); ?>">
					<?php dkm_the_icon( 'close', 'ico--plus' ); ?>
				</button>
			</div>

			<button type="submit" class="btn btn--g btn--lg pd__add"<?php echo $product->is_in_stock() ? '' : ' disabled'; ?>>
				<?php dkm_the_icon( 'cart' ); ?>
				<span><?php esc_html_e( 'In winkelwagen', 'dkm' ); ?></span>
				<span aria-hidden="true">·</span>
				<span data-dkm-upsell-total><?php echo esc_html( dkm_price( $base ) ); ?></span>
			</button>
		</div>
	</form>

	<?php $wa = dkm_whatsapp_url( sprintf( /* translators: %s is a product name. */ __( 'Hoi! Ik heb een vraag over de %s.', 'dkm' ), $name ) ); ?>
	<?php if ( $wa ) : ?>
		<a class="btn btn--o btn--full pd__wa" href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener">
			<?php dkm_the_icon( 'wa', 'pd__wa-ico' ); ?><?php esc_html_e( 'Vraag advies via WhatsApp', 'dkm' ); ?>
		</a>
	<?php endif; ?>

	<div class="pd__usp">
		<?php foreach ( dkm_usps() as [$icon, $title, $sub] ) : ?>
			<div>
				<?php dkm_the_icon( $icon ); ?>
				<span><?php echo esc_html( $title ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="acc" data-dkm-accordion>
		<?php if ( $tier ) : ?>
			<div class="acc__i is-on">
				<button type="button" class="acc__h">
					<?php esc_html_e( 'Specificaties', 'dkm' ); ?>
					<?php dkm_the_icon( 'close' ); ?>
				</button>
				<div class="acc__c">
					<div>
						<div class="acc__b">
							<dl class="spec">
								<?php
								$rows = array(
									__( 'Kwaliteit', 'dkm' )  => $tier['name'],
									__( 'Glas', 'dkm' )       => $tier['specs']['glass'],
									__( 'Uurwerk', 'dkm' )    => $tier['specs']['movement'],
									__( 'Staal', 'dkm' )      => $tier['specs']['steel'],
									__( 'Waterdicht', 'dkm' ) => $tier['specs']['water'],
								);
								if ( $model ) {
									$rows[ __( 'Model', 'dkm' ) ] = $model;
								}
								if ( $size ) {
									$rows[ __( 'Kastmaat', 'dkm' ) ] = $size;
								}
								if ( $dial ) {
									$rows[ __( 'Wijzerplaat', 'dkm' ) ] = $dial;
								}
								if ( $product->get_sku() ) {
									$rows[ __( 'Artikelnummer', 'dkm' ) ] = $product->get_sku();
								}
								foreach ( $rows as $label => $value ) :
									?>
									<div>
										<dt><?php echo esc_html( $label ); ?></dt>
										<dd><?php echo esc_html( $value ); ?></dd>
									</div>
								<?php endforeach; ?>
							</dl>
						</div>
					</div>
				</div>
			</div>

			<div class="acc__i">
				<button type="button" class="acc__h">
					<?php esc_html_e( 'Wat je krijgt', 'dkm' ); ?>
					<?php dkm_the_icon( 'close' ); ?>
				</button>
				<div class="acc__c">
					<div>
						<div class="acc__b">
							<ul class="pd__list">
								<?php foreach ( $tier['bullets'] as $bullet ) : ?>
									<li><?php dkm_the_icon( 'check' ); ?><span><?php echo esc_html( $bullet ); ?></span></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $product->get_description() ) : ?>
			<div class="acc__i">
				<button type="button" class="acc__h">
					<?php esc_html_e( 'Beschrijving', 'dkm' ); ?>
					<?php dkm_the_icon( 'close' ); ?>
				</button>
				<div class="acc__c">
					<div>
						<div class="acc__b"><?php echo wp_kses_post( wpautop( $product->get_description() ) ); ?></div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<div class="acc__i">
			<button type="button" class="acc__h">
				<?php esc_html_e( 'Verzending', 'dkm' ); ?>
				<?php dkm_the_icon( 'close' ); ?>
			</button>
			<div class="acc__c">
				<div>
					<div class="acc__b">
						<p><?php esc_html_e( 'Bestel je op werkdagen voor 17:00, dan ligt je horloge de volgende dag op de mat. Alles gaat discreet verpakt de deur uit, aan de buitenkant zie je niet wat erin zit.', 'dkm' ); ?></p>
						<p style="margin-top:10px"><?php printf( esc_html__( 'Vanaf %s is de verzending gratis. Blijf je daaronder, dan zie je de verzendkosten in je winkelwagen voordat je afrekent.', 'dkm' ), esc_html( dkm_price( dkm_free_shipping_from() ) ) ); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
