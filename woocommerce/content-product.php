<?php
/**
 * DKM Custom Product Card for shop/archive loops
 */

defined('ABSPATH') || exit;

global $product;

if (empty($product) || !$product->is_visible()) {
  return;
}

$product_id = $product->get_id();
$is_sold_out = !$product->is_in_stock();
$permalink   = $product->get_permalink();
$title       = $product->get_name();

// Images
$primary_image_id   = $product->get_image_id();
$gallery_image_ids  = $product->get_gallery_image_ids();
$secondary_image_id = !empty($gallery_image_ids) ? $gallery_image_ids[0] : null;

// Quality tier
$quality_tier = '';
$categories   = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'slugs'));
if (in_array('superclone', $categories, true)) {
  $quality_tier = 'superclone';
} elseif (in_array('bp-swiss-made', $categories, true)) {
  $quality_tier = 'bp';
} elseif (in_array('aaa', $categories, true)) {
  $quality_tier = 'aaa';
}

// Sale
$is_on_sale = $product->is_on_sale();

$card_classes = array('product-card');
if ($is_sold_out) {
  $card_classes[] = 'product-card--sold-out';
}
?>

<li <?php wc_product_class('', $product); ?>>
  <div class="<?php echo esc_attr(implode(' ', $card_classes)); ?>">

    <a href="<?php echo esc_url($permalink); ?>" class="product-card__image">
      <?php if ($primary_image_id) : ?>
        <?php echo wp_get_attachment_image($primary_image_id, 'woocommerce_thumbnail', false, array(
          'loading' => 'lazy',
        )); ?>
      <?php else : ?>
        <?php echo wc_placeholder_img(); ?>
      <?php endif; ?>

      <?php if ($secondary_image_id) : ?>
        <div class="product-card__image-secondary">
          <?php echo wp_get_attachment_image($secondary_image_id, 'woocommerce_thumbnail', false, array(
            'loading' => 'lazy',
          )); ?>
        </div>
      <?php endif; ?>

      <div class="product-card__badges">
        <?php if ($quality_tier) : ?>
          <span class="badge badge--<?php echo esc_attr($quality_tier); ?>">
            <?php
            switch ($quality_tier) {
              case 'superclone': echo 'Superclone'; break;
              case 'bp':         echo 'BP Swiss'; break;
              case 'aaa':        echo 'AAA+'; break;
            }
            ?>
          </span>
        <?php endif; ?>

        <?php if ($is_on_sale && !$is_sold_out) : ?>
          <span class="badge badge--sale">Sale</span>
        <?php endif; ?>

        <?php if ($is_sold_out) : ?>
          <span class="badge badge--sold-out">Uitverkocht</span>
        <?php endif; ?>
      </div>
    </a>

    <div class="product-card__info">
      <a href="<?php echo esc_url($permalink); ?>">
        <h3 class="product-card__title"><?php echo esc_html($title); ?></h3>
      </a>
      <div class="product-card__price">
        <?php if ($is_on_sale && $product->get_regular_price()) : ?>
          <span class="price--was"><?php echo wc_price($product->get_regular_price()); ?></span>
        <?php endif; ?>
        <?php echo wc_price($product->get_price()); ?>
      </div>
    </div>

    <div class="product-card__actions">
      <?php if ($is_sold_out) : ?>
        <a href="<?php echo esc_url($permalink); ?>" class="btn btn--outline btn--sm btn--full">Bekijk</a>
      <?php else : ?>
        <?php
        echo apply_filters(
          'woocommerce_loop_add_to_cart_link',
          sprintf(
            '<a href="%s" data-quantity="1" class="btn btn--outline btn--sm btn--full ajax_add_to_cart add_to_cart_button" data-product_id="%d" data-product_sku="%s" aria-label="%s" rel="nofollow">%s</a>',
            esc_url($product->add_to_cart_url()),
            esc_attr($product_id),
            esc_attr($product->get_sku()),
            esc_attr(sprintf('Voeg "%s" toe aan je winkelwagen', $title)),
            'In winkelwagen'
          ),
          $product
        );
        ?>
      <?php endif; ?>
    </div>

  </div>
</li>
