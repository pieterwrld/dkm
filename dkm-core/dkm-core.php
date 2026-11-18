<?php
/**
 * Plugin Name:       DKM Core
 * Plugin URI:        https://deklokjesman.com
 * Description:       De gegevenslaag van De Klokjesman: productattributen, reviews, UGC-video's en de doos bij een horloge. Hoort in een plugin en niet in het thema, zodat een themawissel geen content of bestelgegevens meeneemt.
 * Version:           1.6.0
 * Requires at least: 6.4
 * Requires PHP:      8.0
 * Author:            De Klokjesman
 * Text Domain:       dkm
 * Domain Path:       /languages
 *
 * @package DKM_Core
 */

defined( 'ABSPATH' ) || exit;

define( 'DKM_CORE_VERSION', '1.6.0' );
define( 'DKM_CORE_DIR', __DIR__ );
define( 'DKM_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once DKM_CORE_DIR . '/includes/shared.php';
require_once DKM_CORE_DIR . '/includes/cpt.php';
require_once DKM_CORE_DIR . '/includes/acf-fields.php';
require_once DKM_CORE_DIR . '/includes/deploy.php';
require_once DKM_CORE_DIR . '/includes/tools-rest.php';

add_action( 'plugins_loaded', 'dkm_core_boot' );

/**
 * The commerce side only makes sense once WooCommerce is there.
 */
function dkm_core_boot(): void {
	if ( class_exists( 'WooCommerce' ) ) {
		require_once DKM_CORE_DIR . '/includes/box-upsell.php';
	}
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once DKM_CORE_DIR . '/includes/cli.php';
}

if ( is_admin() ) {
	require_once DKM_CORE_DIR . '/includes/admin-tools.php';
}

register_activation_hook( __FILE__, 'dkm_core_activate' );

/**
 * Registers the post types once on activation so their permalinks exist
 * straight away.
 */
function dkm_core_activate(): void {
	dkm_register_post_types();
	flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );

add_action( 'admin_notices', 'dkm_core_dependency_notice' );

/**
 * Says plainly what is missing, rather than failing quietly.
 */
function dkm_core_dependency_notice(): void {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	$missing = array();
	if ( ! class_exists( 'WooCommerce' ) ) {
		$missing[] = 'WooCommerce';
	}
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		$missing[] = 'Advanced Custom Fields';
	}
	if ( ! $missing ) {
		return;
	}

	printf(
		'<div class="notice notice-warning"><p>%s</p></div>',
		esc_html(
			sprintf(
				/* translators: %s is a comma separated list of plugin names. */
				__( 'DKM Core mist: %s. Zonder die plugins werken de dozen bij een horloge en de invulvelden niet.', 'dkm' ),
				implode( ', ', $missing )
			)
		)
	);
}
