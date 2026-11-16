<?php
/**
 * DKM Custom WooCommerce Shop / Category Archive
 */

defined('ABSPATH') || exit;

get_header('shop');

$is_category = is_product_category();
$current_cat  = $is_category ? get_queried_object() : null;
?>

<?php if ($is_category && $current_cat) : ?>
  <section class="shop-header">
    <div class="container">
      <h1 class="shop-header__title"><?php echo esc_html($current_cat->name); ?></h1>
      <?php if ($current_cat->description) : ?>
        <p class="shop-header__description"><?php echo wp_kses_post($current_cat->description); ?></p>
      <?php endif; ?>
    </div>
  </section>
<?php else : ?>
  <section class="shop-header">
    <div class="container">
      <h1 class="shop-header__title">Onze Collectie</h1>
      <p class="shop-header__description">Ontdek luxe horloges van de beste kwaliteit, zorgvuldig geselecteerd voor de echte liefhebber.</p>
    </div>
  </section>
<?php endif; ?>

<section class="dkm-shop-content">
  <div class="container">

    <?php
    $categories = get_terms(array(
      'taxonomy'   => 'product_cat',
      'hide_empty' => true,
      'parent'     => 0,
      'exclude'    => array(get_option('default_product_cat')),
    ));

    if (!is_wp_error($categories) && !empty($categories)) :
    ?>
      <div class="category-cards scroll-reveal">
        <?php foreach ($categories as $cat) :
          $thumb_id  = get_term_meta($cat->term_id, 'thumbnail_id', true);
          $thumb_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'thumbnail') : '';
          $cat_link  = get_term_link($cat);
        ?>
          <a href="<?php echo esc_url($cat_link); ?>" class="category-card">
            <div class="category-card__image">
              <?php if ($thumb_url) : ?>
                <img src="<?php echo esc_url($thumb_url); ?>"
                     alt="<?php echo esc_attr($cat->name); ?>"
                     loading="lazy" width="80" height="80">
              <?php endif; ?>
            </div>
            <span class="category-card__name"><?php echo esc_html($cat->name); ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if (woocommerce_product_loop()) : ?>

      <div class="filter-bar scroll-reveal">
        <div class="filter-bar__count">
          <?php woocommerce_result_count(); ?>
        </div>
        <div class="filter-bar__sort">
          <?php woocommerce_catalog_ordering(); ?>
        </div>
      </div>

      <?php woocommerce_product_loop_start(); ?>

        <?php while (have_posts()) : the_post();
          wc_get_template_part('content', 'product');
        endwhile; ?>

      <?php woocommerce_product_loop_end(); ?>

      <?php woocommerce_pagination(); ?>

    <?php else : ?>
      <div class="woocommerce-info scroll-reveal">
        <p>Er zijn momenteel geen producten beschikbaar in deze categorie.</p>
      </div>
    <?php endif; ?>

  </div>
</section>

<section class="trust-section">
  <div class="container">
    <div class="trust-grid scroll-reveal">
      <div class="trust-badge">
        <div class="trust-badge__icon">
          <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <h3 class="trust-badge__title">Kwaliteitsgarantie</h3>
        <p class="trust-badge__text">Onze horloges worden met de grootste zorg vervaardigd en gecontroleerd.</p>
      </div>
      <div class="trust-badge">
        <div class="trust-badge__icon">
          <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </div>
        <h3 class="trust-badge__title">Exclusieve Modellen</h3>
        <p class="trust-badge__text">Unieke modellen die nergens anders van deze kwaliteit te vinden zijn.</p>
      </div>
      <div class="trust-badge">
        <div class="trust-badge__icon">
          <svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </div>
        <h3 class="trust-badge__title">Snel &amp; Veilig</h3>
        <p class="trust-badge__text">Eenvoudig bestellen met veilige betaling en snelle verzending binnen 1-2 dagen.</p>
      </div>
    </div>
  </div>
</section>

<?php
get_footer('shop');
