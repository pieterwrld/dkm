<?php
/**
 * Front page template
 *
 * @package DKM
 */

get_header();
?>

<?php get_template_part( 'template-parts/hero' ); ?>

<?php get_template_part( 'template-parts/quality-tiers' ); ?>

<section class="section section--featured-products">
    <div class="container">
        <div class="section-header">
            <span class="section-label">NIEUW BINNEN</span>
            <h2 class="section-title">Nieuwste Collectie</h2>
        </div>

        <?php
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => 8,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post_status'    => 'publish',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'exclude-from-catalog',
                    'operator' => 'NOT IN',
                ),
            ),
        );
        $products = new WP_Query( $args );

        if ( $products->have_posts() ) :
        ?>
            <div class="product-grid">
                <?php
                while ( $products->have_posts() ) :
                    $products->the_post();
                    global $product;
                    get_template_part( 'template-parts/product-card' );
                endwhile;
                ?>
            </div>
        <?php
            wp_reset_postdata();
        endif;
        ?>

        <div class="section-cta">
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="btn btn--outline">
                Bekijk alle horloges
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
    </div>
</section>

<?php get_template_part( 'template-parts/reviews-carousel' ); ?>

<?php get_template_part( 'template-parts/ambassadors' ); ?>

<?php get_template_part( 'template-parts/trust-badges' ); ?>

<?php
get_footer();
