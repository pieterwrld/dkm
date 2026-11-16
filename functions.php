<?php
/**
 * DKM, the De Klokjesman theme
 *
 * @package DKM
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

define( 'DKM_VERSION', '2.0.0' );
define( 'DKM_DIR', get_template_directory() );
define( 'DKM_URI', get_template_directory_uri() );

require_once DKM_DIR . '/inc/helpers.php';
require_once DKM_DIR . '/inc/icons.php';
require_once DKM_DIR . '/inc/logo.php';
require_once DKM_DIR . '/inc/tiers.php';
require_once DKM_DIR . '/inc/content.php';
require_once DKM_DIR . '/inc/setup.php';
require_once DKM_DIR . '/inc/enqueue.php';
require_once DKM_DIR . '/inc/customizer.php';
require_once DKM_DIR . '/inc/acf.php';
require_once DKM_DIR . '/inc/cpt.php';
require_once DKM_DIR . '/inc/nav.php';
require_once DKM_DIR . '/inc/facets.php';
require_once DKM_DIR . '/inc/performance.php';

if ( class_exists( 'WooCommerce' ) ) {
	require_once DKM_DIR . '/inc/woocommerce.php';
	require_once DKM_DIR . '/inc/box-upsell.php';
	require_once DKM_DIR . '/inc/cart.php';
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once DKM_DIR . '/inc/cli.php';
}
