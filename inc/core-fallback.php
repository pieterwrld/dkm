<?php
/**
 * The theme's dependency on DKM Core
 *
 * The plugin owns the reviews, the UGC clips and the box that travels with a
 * watch, because those are content and order data rather than design. The
 * templates call its functions behind function_exists, so the shop keeps
 * selling without it; this file only makes sure nobody wonders why those
 * sections are empty.
 *
 * No stand in functions are declared here on purpose. WordPress loads the
 * theme before the plugin while a plugin is being activated, so a duplicate
 * name would fatal at exactly that moment.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

// Only a meta key, and identical on both sides, so defining it is harmless.
if ( ! defined( 'DKM_BOX_PARENT' ) ) {
	define( 'DKM_BOX_PARENT', 'dkm_box_parent' );
}

add_action( 'admin_notices', 'dkm_core_missing_notice' );

function dkm_core_missing_notice(): void {
	if ( defined( 'DKM_CORE_VERSION' ) || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	printf(
		'<div class="notice notice-error"><p><strong>%s</strong> %s</p></div>',
		esc_html__( 'DKM Core is niet actief.', 'dkm' ),
		esc_html__( 'De reviews, de UGC-video’s en de doos bij een horloge blijven leeg zolang die plugin uit staat.', 'dkm' )
	);
}


if ( ! function_exists( 'dkm_opt' ) ) {
	/**
	 * Reads a setting from the panel in DKM Core.
	 *
	 * Without that plugin there is nothing to read, so every caller gets its
	 * own text back and the page looks exactly as it was built.
	 *
	 * @param string $naam     Setting name.
	 * @param mixed  $fallback What to use when there is nothing stored.
	 * @return mixed
	 */
	function dkm_opt( string $naam, $fallback = '' ) {
		return $fallback;
	}
}
