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

/**
 * True when this page was actually built with the page builder.
 *
 * @param int $post_id Post to check.
 */
function dkm_is_builder_page( int $post_id ): bool {
	return $post_id > 0 && 'builder' === get_post_meta( $post_id, '_elementor_edit_mode', true );
}

/**
 * Keeps the page builder's global typography off the pages this theme draws.
 *
 * The builder prints a stylesheet that sets a font, a size and uppercase
 * headings for the whole site, under selectors that all hang off one class on
 * the body. Dropping that class on the pages this theme draws is enough: the
 * rules stop matching, and nothing has to be dequeued or outranked. A page the
 * builder really made keeps the class, so those pages look as they were made.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function dkm_drop_builder_kit_class( array $classes ): array {
	// The front page and the shop are drawn by this theme, whatever the builder
	// thinks it owns. Only an ordinary page that the builder really made keeps
	// its typography.
	$houdt_kit = is_page()
		&& ! is_front_page()
		&& dkm_is_builder_page( (int) get_queried_object_id() );

	if ( $houdt_kit ) {
		return $classes;
	}

	return array_values(
		array_filter(
			$classes,
			static function ( $class ) {
				return ! str_starts_with( (string) $class, 'elementor-kit-' );
			}
		)
	);
}
add_filter( 'body_class', 'dkm_drop_builder_kit_class', 20 );


/**
 * The theme's own shop templates win.
 *
 * Several of the page builder add-ons on this site hand WooCommerce their own
 * copies of cart and checkout templates. That is how the cart ended up as the
 * default table, which runs off the side of a phone and cuts the amount in
 * half. If this theme has a file for that template, that file is used.
 *
 * @param string $template      Path the shop settled on.
 * @param string $template_name Template being looked for, such as cart/cart.php.
 * @param string $template_path Folder the shop looks in.
 * @return string
 */
function dkm_own_shop_templates( $template, $template_name, $template_path ) {
	$eigen = get_template_directory() . '/woocommerce/' . $template_name;

	if ( $template_name && file_exists( $eigen ) ) {
		return $eigen;
	}

	return $template;
}
add_filter( 'woocommerce_locate_template', 'dkm_own_shop_templates', 999, 3 );
