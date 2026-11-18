<?php
/**
 * The red promo bar. Red, never gold, and only while the action is really
 * running. One line, no clock: a sale that lasts days counts in hundreds of
 * hours, and a row of three numbers reading 311 says nothing to a shopper.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

if ( ! dkm_black_friday_active() ) {
	return;
}

$label = (string) dkm_opt( 'bf_label', __( 'Black Friday', 'dkm' ) );
$text  = (string) dkm_opt( 'bf_text', __( 'Tot 30% korting op geselecteerde modellen', 'dkm' ) );
?>
<div class="bf">
	<div class="bf__in">
		<span class="bf__tag"><i class="bf__dot" aria-hidden="true"></i><?php echo esc_html( $label ); ?></span>
		<span class="bf__txt"><?php echo esc_html( $text ); ?></span>
	</div>
</div>
