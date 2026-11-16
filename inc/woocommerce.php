<?php
/**
 * DKM WooCommerce Integration
 *
 * @package DKM
 */

defined('ABSPATH') || exit;

add_filter('woocommerce_enqueue_styles', '__return_empty_array');

add_action('after_setup_theme', 'dkm_wc_setup');

function dkm_wc_setup(): void {
    add_theme_support('woocommerce', [
        'thumbnail_image_width' => 600,
        'gallery_thumbnail_image_width' => 300,
        'single_image_width' => 1200,
        'product_grid' => [
            'default_rows'    => 6,
            'min_rows'        => 1,
            'default_columns' => 4,
            'min_columns'     => 1,
            'max_columns'     => 4,
        ],
    ]);
}

// Custom wrapper
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', function (): void {
    echo '<div class="dkm-shop-content">';
}, 10);

add_action('woocommerce_after_main_content', function (): void {
    echo '</div>';
}, 10);

// Products per page & columns
add_filter('loop_shop_per_page', function (): int {
    return 24;
});

add_filter('loop_shop_columns', function (): int {
    return 4;
});

// Remove breadcrumbs
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

// Remove sidebar
add_action('template_redirect', function (): void {
    if (is_woocommerce()) {
        remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
    }
});

// Custom product loop hooks
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

add_action('woocommerce_before_shop_loop_item', 'dkm_product_card_open', 5);
add_action('woocommerce_before_shop_loop_item', 'dkm_product_card_image', 10);
add_action('woocommerce_shop_loop_item_title', 'dkm_product_card_info_open', 5);
add_action('woocommerce_shop_loop_item_title', 'dkm_product_card_title', 10);
add_action('woocommerce_after_shop_loop_item_title', 'dkm_product_card_price', 10);
add_action('woocommerce_after_shop_loop_item', 'dkm_product_card_info_close', 15);
add_action('woocommerce_after_shop_loop_item', 'dkm_product_card_close', 20);

function dkm_product_card_open(): void {
    global $product;
    $tier = dkm_get_quality_tier($product);
    $classes = ['dkm-product-card'];
    if ($tier) {
        $classes[] = 'dkm-tier--' . $tier;
    }
    if (!$product->is_in_stock()) {
        $classes[] = 'dkm-product-card--sold-out';
    }
    printf('<div class="%s">', esc_attr(implode(' ', $classes)));
}

function dkm_product_card_image(): void {
    global $product;

    echo '<a href="' . esc_url(get_the_permalink()) . '" class="dkm-product-card__image-link">';
    echo '<div class="dkm-product-card__image-wrap">';

    $image_id = $product->get_image_id();
    $gallery  = $product->get_gallery_image_ids();

    if ($image_id) {
        echo wp_get_attachment_image($image_id, 'product-card', false, [
            'class'   => 'dkm-product-card__img dkm-product-card__img--primary',
            'loading' => 'lazy',
        ]);
    }

    if (!empty($gallery[0])) {
        echo wp_get_attachment_image($gallery[0], 'product-card', false, [
            'class'   => 'dkm-product-card__img dkm-product-card__img--hover',
            'loading' => 'lazy',
        ]);
    }

    // Badges overlay
    echo '<div class="dkm-product-card__badges">';

    if (!$product->is_in_stock()) {
        echo '<span class="dkm-badge dkm-badge--sold-out">Uitverkocht</span>';
    } elseif ($product->is_on_sale()) {
        echo '<span class="dkm-badge dkm-badge--sale">Sale</span>';
    }

    $tier = dkm_get_quality_tier($product);
    if ($tier) {
        printf(
            '<span class="dkm-badge dkm-badge--tier" style="--tier-color:%s">%s</span>',
            esc_attr(dkm_get_tier_color($tier)),
            esc_html(dkm_get_tier_label($tier))
        );
    }

    echo '</div>'; // badges

    echo '</div>'; // image-wrap
    echo '</a>';
}

function dkm_product_card_info_open(): void {
    echo '<div class="dkm-product-card__info">';
}

function dkm_product_card_title(): void {
    echo '<a href="' . esc_url(get_the_permalink()) . '" class="dkm-product-card__title-link">';
    echo '<h3 class="dkm-product-card__title">' . get_the_title() . '</h3>';
    echo '</a>';
}

function dkm_product_card_price(): void {
    global $product;
    echo '<div class="dkm-product-card__price">';

    if ($product->is_on_sale() && $product->get_regular_price()) {
        echo '<span class="dkm-product-card__price--regular">' . wc_price($product->get_regular_price()) . '</span>';
        echo '<span class="dkm-product-card__price--sale">' . wc_price($product->get_sale_price()) . '</span>';
    } else {
        echo '<span class="dkm-product-card__price--current">' . $product->get_price_html() . '</span>';
    }

    echo '</div>';
}

function dkm_product_card_info_close(): void {
    global $product;

    if ($product->is_in_stock()) {
        if ($product->is_type('simple')) {
            printf(
                '<a href="%s" data-product_id="%d" data-quantity="1" class="dkm-product-card__atc ajax_add_to_cart add_to_cart_button">%s</a>',
                esc_url($product->add_to_cart_url()),
                esc_attr($product->get_id()),
                esc_html__('In winkelwagen', 'dkm')
            );
        } else {
            printf(
                '<a href="%s" class="dkm-product-card__atc dkm-product-card__atc--options">%s</a>',
                esc_url(get_the_permalink()),
                esc_html__('Bekijk opties', 'dkm')
            );
        }
    } else {
        echo '<span class="dkm-product-card__atc dkm-product-card__atc--disabled">';
        esc_html_e('Uitverkocht', 'dkm');
        echo '</span>';
    }

    echo '</div>'; // info
}

function dkm_product_card_close(): void {
    echo '</div>'; // product-card
}

// Related products
add_filter('woocommerce_output_related_products_args', function (array $args): array {
    $args['posts_per_page'] = 4;
    $args['columns']        = 4;
    return $args;
});

// Product tabs
add_filter('woocommerce_product_tabs', function (array $tabs): array {
    unset($tabs['reviews']);
    unset($tabs['additional_information']);
    return $tabs;
});

// Cart button text
add_filter('woocommerce_product_single_add_to_cart_text', function (): string {
    return __('In winkelwagen', 'dkm');
});

add_filter('woocommerce_product_add_to_cart_text', function (string $text, $product): string {
    if (!$product->is_in_stock()) {
        return __('Uitverkocht', 'dkm');
    }
    if ($product->is_type('simple')) {
        return __('In winkelwagen', 'dkm');
    }
    return __('Bekijk opties', 'dkm');
}, 10, 2);

// AJAX cart fragments
add_filter('woocommerce_add_to_cart_fragments', 'dkm_cart_fragment');

function dkm_cart_fragment(array $fragments): array {
    $count = WC()->cart->get_cart_contents_count();
    $fragments['.dkm-cart-count'] = sprintf(
        '<span class="dkm-cart-count"%s>%d</span>',
        $count === 0 ? ' style="display:none"' : '',
        $count
    );
    return $fragments;
}

// Body class for product categories
add_filter('body_class', function (array $classes): array {
    if (is_product()) {
        $tier = dkm_get_quality_tier();
        if ($tier) {
            $classes[] = 'dkm-product-tier--' . $tier;
        }
    }
    if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
        $classes[] = 'dkm-woocommerce';
    }
    return $classes;
});

// Remove default sale flash
add_filter('woocommerce_sale_flash', '__return_empty_string');

// Change number of thumbnails per row in product gallery
add_filter('woocommerce_product_thumbnails_columns', function (): int {
    return 5;
});

// Ensure cart contents update on AJAX add-to-cart
add_filter('woocommerce_add_to_cart_fragments', 'dkm_mini_cart_fragment');

function dkm_mini_cart_fragment(array $fragments): array {
    ob_start();
    echo '<div class="dkm-mini-cart-contents">';
    woocommerce_mini_cart();
    echo '</div>';
    $fragments['.dkm-mini-cart-contents'] = ob_get_clean();
    return $fragments;
}
