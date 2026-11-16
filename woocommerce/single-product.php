<?php
/**
 * DKM Custom Single Product Page
 */

defined('ABSPATH') || exit;

get_header('shop');

while (have_posts()) :
  the_post();
?>

<div class="container">
  <div class="dkm-single-product">
    <?php wc_get_template_part('content', 'single-product'); ?>
  </div>
</div>

<?php
  // Related Products
  woocommerce_output_related_products();
?>

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
endwhile;

get_footer('shop');
