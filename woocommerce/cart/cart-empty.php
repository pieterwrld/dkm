<?php
/**
 * Empty cart page
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package DKM
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_cart_is_empty' );
?>

<div class="dkm-cart-empty">
	<span class="dkm-cart-empty__ico" aria-hidden="true"><?php dkm_the_icon( 'cart' ); ?></span>

	<h2 class="h-m"><?php esc_html_e( 'Je winkelwagen is nog leeg', 'dkm' ); ?></h2>
	<p class="lead"><?php esc_html_e( 'Blader door de collectie en kies het horloge dat bij je past. Twijfel je tussen twee modellen, stuur ons dan een bericht.', 'dkm' ); ?></p>

	<div class="dkm-cart-empty__acts">
		<a class="btn btn--g btn--lg" href="<?php echo esc_url( dkm_shop_url() ); ?>">
			<?php esc_html_e( 'Bekijk de collectie', 'dkm' ); ?>
		</a>

		<?php
		$dkm_wa = function_exists( 'dkm_whatsapp_url' ) ? dkm_whatsapp_url( (string) dkm_opt( 'whatsapp_text', '' ) ) : '';
		if ( $dkm_wa ) :
			?>
			<a class="btn btn--o btn--lg" href="<?php echo esc_url( $dkm_wa ); ?>" target="_blank" rel="noopener">
				<?php dkm_the_icon( 'wa' ); ?><?php esc_html_e( 'Vraag advies', 'dkm' ); ?>
			</a>
		<?php endif; ?>
	</div>
</div>

<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
	<?php do_action( 'woocommerce_cart_is_empty_after' ); ?>
<?php endif; ?>
