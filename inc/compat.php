<?php
/**
 * Living next to the plugins that were already on the site
 *
 * The shop ran on another theme before this one, and the plugins that came
 * with it are still active because the content pages are built with them.
 * They may stay, but they must not take over the parts this theme draws.
 *
 * The Elementor footer and archive templates were unassigned from their
 * locations in the library itself, so nothing is needed here for those.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

/**
 * Scripts from the previous theme's companion plugin.
 *
 * They are written for markup this theme does not have. The add to cart one
 * matters most: it would fight the cart drawer over the same click.
 *
 * @return string[]
 */
function dkm_old_theme_handles(): array {
	return array(
		'ecomus-add-to-cart-ajax',
		'ecomus-ajax-search',
		'ecomus-popup',
		'ecomus-size-guide-content',
		'ecomus-size-guide-tab',
		'ecomus-variation-images-frontend',
		'ecomus-scripts',
		'ecomus-frontend',

		// A plugin that expects the previous theme's file layout and points at
		// files inside the active theme that this one does not have. Left in
		// place it asks the server for two files that are not there.
		'driff-style',
		'driff-js',
	);
}

/**
 * Drops those scripts and their stylesheets.
 *
 * Runs twice because a plugin may enqueue in the head or just before the
 * closing body tag, and only one of the two hooks catches each case.
 */
function dkm_drop_old_theme_assets(): void {
	foreach ( dkm_old_theme_handles() as $handle ) {
		wp_dequeue_script( $handle );
		wp_dequeue_style( $handle );
	}
}
add_action( 'wp_enqueue_scripts', 'dkm_drop_old_theme_assets', 99 );
add_action( 'wp_print_footer_scripts', 'dkm_drop_old_theme_assets', 1 );
