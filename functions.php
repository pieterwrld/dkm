<?php
/**
 * DKM Theme - De Klokjesman
 *
 * @package DKM
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

define('DKM_VERSION', '1.0.0');
define('DKM_DIR', get_template_directory());
define('DKM_URI', get_template_directory_uri());

require_once DKM_DIR . '/inc/helpers.php';
require_once DKM_DIR . '/inc/walker.php';
require_once DKM_DIR . '/inc/setup.php';
require_once DKM_DIR . '/inc/enqueue.php';
require_once DKM_DIR . '/inc/woocommerce.php';
