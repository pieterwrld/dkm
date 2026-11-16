<?php
/**
 * The red promo bar with its countdown. Red, never gold, and only while the
 * action is actually running.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

if ( ! dkm_black_friday_active() ) {
	return;
}

$ends  = dkm_black_friday_ends();
$label = (string) get_theme_mod( 'dkm_bf_label', __( 'Black Friday', 'dkm' ) );
$text  = (string) get_theme_mod( 'dkm_bf_text', __( 'Tot 30% korting op geselecteerde modellen', 'dkm' ) );
?>
<div class="bf">
	<div class="bf__in">
		<span class="bf__tag"><i class="bf__dot" aria-hidden="true"></i><?php echo esc_html( $label ); ?></span>
		<span><?php echo esc_html( $text ); ?></span>
		<span class="bf__hide" aria-hidden="true">•</span>
		<span class="bf__hide"><?php esc_html_e( 'Eindigt over', 'dkm' ); ?></span>
		<span class="bf__cd"
			data-dkm-countdown="<?php echo esc_attr( (string) $ends ); ?>"
			role="timer"
			aria-label="<?php esc_attr_e( 'Resterende tijd van de actie', 'dkm' ); ?>">
			<b>00</b><b>00</b><b>00</b>
		</span>
	</div>
</div>
