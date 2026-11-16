<?php
/**
 * DKM Theme Setup
 *
 * @package DKM
 */

defined('ABSPATH') || exit;

add_action('after_setup_theme', 'dkm_theme_setup');

function dkm_theme_setup(): void {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'gallery',
        'caption',
        'style',
        'script',
    ]);
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    register_nav_menus([
        'primary'      => __('Hoofdnavigatie', 'dkm'),
        'footer-shop'  => __('Footer Shop', 'dkm'),
        'footer-links' => __('Footer Links', 'dkm'),
    ]);

    add_image_size('product-card', 600, 600, true);
    add_image_size('product-large', 1200, 1200, true);
    add_image_size('hero', 1920, 1080, true);
}

add_action('after_setup_theme', function () {
    $GLOBALS['content_width'] = 1400;
}, 0);

add_action('init', 'dkm_cleanup_head');

function dkm_cleanup_head(): void {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_resource_hints', 2);
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
}

add_filter('tiny_mce_plugins', function (array $plugins): array {
    return array_diff($plugins, ['wpemoji']);
});

add_filter('wp_resource_hints', function (array $urls, string $relation_type): array {
    if ($relation_type === 'dns-prefetch') {
        $urls = array_filter($urls, function ($url) {
            return !str_contains((string) $url, 's.w.org');
        });
    }
    return $urls;
}, 10, 2);
