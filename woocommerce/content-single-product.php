<?php
/**
 * DKM Custom Single Product Content
 */

defined('ABSPATH') || exit;

global $product;

if (post_password_required()) {
  echo get_the_password_form();
  return;
}

$product_id = $product->get_id();

// Quality tier
$quality_tier = '';
$tier_label   = '';
$categories   = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'slugs'));
if (in_array('superclone', $categories, true)) {
  $quality_tier = 'superclone';
  $tier_label   = 'Superclone';
} elseif (in_array('bp-swiss-made', $categories, true)) {
  $quality_tier = 'bp';
  $tier_label   = 'BP Swiss Made';
} elseif (in_array('aaa', $categories, true)) {
  $quality_tier = 'aaa';
  $tier_label   = 'AAA+';
}

// WhatsApp
$whatsapp_number  = '31647974408';
$whatsapp_message = rawurlencode('Hoi, ik heb een vraag over: ' . $product->get_name() . ' (' . get_permalink() . ')');
$whatsapp_url     = 'https://wa.me/' . $whatsapp_number . '?text=' . $whatsapp_message;
?>

<div id="product-<?php echo esc_attr($product_id); ?>" <?php wc_product_class('', $product); ?>>

  <?php // --- Gallery (left column) --- ?>
  <div class="dkm-product-gallery">
    <?php
    do_action('woocommerce_before_single_product_summary');
    ?>
  </div>

  <?php // --- Product Info (right column) --- ?>
  <div class="dkm-product-info">

    <?php // Breadcrumbs ?>
    <nav class="dkm-product-breadcrumbs">
      <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
      <span class="separator">/</span>
      <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">Shop</a>
      <?php
      $product_cats = wp_get_post_terms($product_id, 'product_cat');
      if (!is_wp_error($product_cats) && !empty($product_cats)) :
        $primary_cat = $product_cats[0];
      ?>
        <span class="separator">/</span>
        <a href="<?php echo esc_url(get_term_link($primary_cat)); ?>"><?php echo esc_html($primary_cat->name); ?></a>
      <?php endif; ?>
      <span class="separator">/</span>
      <span><?php the_title(); ?></span>
    </nav>

    <?php // Quality tier badge ?>
    <?php if ($quality_tier) : ?>
      <div style="margin-bottom: var(--space-lg);">
        <span class="badge badge--<?php echo esc_attr($quality_tier); ?>"><?php echo esc_html($tier_label); ?></span>
      </div>
    <?php endif; ?>

    <?php // Title ?>
    <h1 class="product_title entry-title"><?php the_title(); ?></h1>

    <?php // Price ?>
    <div class="dkm-product-price">
      <?php echo $product->get_price_html(); ?>
    </div>

    <?php // Short description ?>
    <?php if ($product->get_short_description()) : ?>
      <div class="woocommerce-product-details__short-description">
        <?php echo wp_kses_post(wpautop($product->get_short_description())); ?>
      </div>
    <?php endif; ?>

    <?php // Add to cart ?>
    <?php
    do_action('woocommerce_single_product_summary');
    ?>

    <?php // WhatsApp inquiry ?>
    <a href="<?php echo esc_url($whatsapp_url); ?>"
       target="_blank"
       rel="noopener noreferrer"
       class="dkm-whatsapp-inquiry">
      <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 2C6.477 2 2 6.477 2 12c0 1.89.525 3.66 1.438 5.168L2 22l4.832-1.438A9.955 9.955 0 0 0 12 22c5.523 0 10-4.477 10-10S17.523 2 12 2z"/></svg>
      Stel een vraag via WhatsApp
    </a>

    <?php // Product Meta ?>
    <div class="product_meta">
      <?php do_action('woocommerce_product_meta_start'); ?>

      <?php if (wc_product_sku_enabled() && ($product->get_sku() || $product->is_type('variable'))) : ?>
        <span class="sku_wrapper">SKU: <span class="sku"><?php echo ($sku = $product->get_sku()) ? esc_html($sku) : esc_html__('N/A', 'woocommerce'); ?></span></span>
      <?php endif; ?>

      <?php echo wc_get_product_category_list($product_id, ', ', '<span class="posted_in">Categorie: ', '</span>'); ?>

      <?php do_action('woocommerce_product_meta_end'); ?>
    </div>

  </div>

</div>

<?php // Tabs ?>
<div class="dkm-product-tabs" style="grid-column: 1 / -1; margin-top: var(--space-3xl);">
  <?php
  woocommerce_output_product_data_tabs();
  ?>
</div>
