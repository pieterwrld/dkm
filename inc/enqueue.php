<?php
/**
 * DKM Enqueue Scripts & Styles
 *
 * @package DKM
 */

defined('ABSPATH') || exit;

add_action('wp_enqueue_scripts', 'dkm_enqueue_styles', 10);

function dkm_enqueue_styles(): void {
    wp_enqueue_style(
        'dkm-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,500&family=Inter:wght@300;400;500;600;700;800&display=swap',
        [],
        null
    );

    $css_files = [
        'dkm-design-system' => 'design-system.css',
        'dkm-components'    => 'components.css',
        'dkm-layout'        => 'layout.css',
        'dkm-pages'         => 'pages.css',
        'dkm-woocommerce'   => 'woocommerce.css',
    ];

    $prev = 'dkm-fonts';
    foreach ($css_files as $handle => $file) {
        $path = DKM_DIR . '/assets/css/' . $file;
        if (file_exists($path)) {
            wp_enqueue_style($handle, DKM_URI . '/assets/css/' . $file, [$prev], DKM_VERSION);
            $prev = $handle;
        }
    }
}

add_action('wp_enqueue_scripts', 'dkm_enqueue_scripts', 10);

function dkm_enqueue_scripts(): void {
    $js_path = DKM_DIR . '/assets/js/main.js';
    if (file_exists($js_path)) {
        wp_enqueue_script('dkm-main', DKM_URI . '/assets/js/main.js', [], DKM_VERSION, true);

        wp_localize_script('dkm-main', 'dkm_ajax', [
            'ajax_url'        => admin_url('admin-ajax.php'),
            'nonce'           => wp_create_nonce('dkm_nonce'),
            'whatsapp_number' => '31647974408',
            'cart_url'        => wc_get_cart_url(),
            'checkout_url'    => wc_get_checkout_url(),
        ]);
    }
}

add_filter('script_loader_tag', 'dkm_defer_scripts', 10, 3);

function dkm_defer_scripts(string $tag, string $handle, string $src): string {
    if ($handle === 'dkm-main') {
        return str_replace(' src=', ' defer src=', $tag);
    }
    return $tag;
}

add_action('wp_head', 'dkm_preconnect_fonts', 1);

function dkm_preconnect_fonts(): void {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}

add_action('wp_enqueue_scripts', 'dkm_dequeue_defaults', 99);

function dkm_dequeue_defaults(): void {
    wp_dequeue_style('woocommerce-general');
    wp_dequeue_style('woocommerce-layout');
    wp_dequeue_style('woocommerce-smallscreen');
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-blocks-style');
    wp_dequeue_style('classic-theme-styles');
    wp_dequeue_style('global-styles');
}
