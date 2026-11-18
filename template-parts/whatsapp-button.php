<?php
/**
 * Floating WhatsApp button
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

$wa = dkm_whatsapp_url( (string) dkm_opt( 'whatsapp_text', '' ) );
if ( ! $wa ) {
	return;
}
?>
<div class="fab">
	<span class="fab__t" aria-hidden="true"><?php esc_html_e( 'Advies nodig? Chat met ons', 'dkm' ); ?></span>
	<a class="fab__b" href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener">
		<?php dkm_the_icon( 'wa', '', __( 'Chat met ons via WhatsApp', 'dkm' ) ); ?>
	</a>
</div>
