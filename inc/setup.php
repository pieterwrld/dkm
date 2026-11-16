<?php
/**
 * Theme setup
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', 'dkm_setup' );

function dkm_setup(): void {
	load_theme_textdomain( 'dkm', DKM_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 48,
			'width'       => 260,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// The theme draws every WooCommerce template itself.
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 600,
		'single_image_width'    => 1200,
	) );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	register_nav_menus(
		array(
			'primary'     => __( 'Hoofdmenu', 'dkm' ),
			'footer-shop' => __( 'Footer: shop', 'dkm' ),
			'footer-info' => __( 'Footer: informatie', 'dkm' ),
		)
	);

	// Square cards, and one wide crop for heroes and banners.
	add_image_size( 'dkm-card', 600, 600, true );
	add_image_size( 'dkm-card-2x', 1200, 1200, true );
	add_image_size( 'dkm-wide', 1600, 900, true );

	add_filter( 'should_load_separate_core_block_assets', '__return_true' );
}

/**
 * The container width, exposed so templates do not hardcode it.
 */
add_filter( 'dkm_wrap_width', fn() => 1340 );

/**
 * Trims the admin bar offset the theme does not want in its sticky header.
 */
add_action( 'wp_head', 'dkm_admin_bar_offset', 20 );

function dkm_admin_bar_offset(): void {
	if ( ! is_admin_bar_showing() ) {
		return;
	}
	echo '<style>@media screen and (max-width:782px){#wpadminbar{position:fixed}}</style>';
}
