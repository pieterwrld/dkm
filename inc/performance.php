<?php
/**
 * DKM Performance Optimizations
 *
 * @package DKM
 */

defined('ABSPATH') || exit;

add_action('wp_enqueue_scripts', 'dkm_disable_block_editor_assets', 100);

function dkm_disable_block_editor_assets(): void {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-blocks-style');
    wp_dequeue_style('classic-theme-styles');
    wp_dequeue_style('global-styles');

    if (!is_admin()) {
        wp_deregister_script('jquery-migrate');
    }
}

add_action('wp_enqueue_scripts', 'dkm_conditional_wc_scripts', 99);

function dkm_conditional_wc_scripts(): void {
    if (!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page()) {
        wp_dequeue_script('wc-cart-fragments');
        wp_dequeue_script('woocommerce');
        wp_dequeue_script('wc-add-to-cart');
    }
}

add_filter('woocommerce_enqueue_styles', '__return_empty_array');

add_action('wp_head', 'dkm_critical_preloads', 1);

function dkm_critical_preloads(): void {
    if (is_front_page()) {
        echo '<link rel="preload" as="style" href="' . esc_url(DKM_URI . '/assets/css/design-system.css') . '">' . "\n";
    }
}

add_filter('wp_lazy_loading_enabled', function (bool $default, string $tag_name): bool {
    if ($tag_name === 'img' && is_front_page()) {
        return true;
    }
    return $default;
}, 10, 2);

add_filter('big_image_size_threshold', function (): int {
    return 2560;
});

add_filter('jpeg_quality', function (): int {
    return 82;
});

add_filter('wp_editor_set_quality', function (): int {
    return 82;
});

add_action('init', function (): void {
    if (!is_admin()) {
        remove_action('wp_head', 'wp_oembed_add_host_js');
        wp_deregister_script('wp-embed');
    }
});

add_filter('woocommerce_resize_images', '__return_true');

add_action('send_headers', function (): void {
    if (!is_admin()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }
});
