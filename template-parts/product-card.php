<?php
/**
 * Product card for use in product grids
 *
 * @package DKM
 */

global $product;

if ( ! is_a( $product, 'WC_Product' ) ) {
    return;
}

$product_id    = $product->get_id();
$permalink     = $product->get_permalink();
$title         = $product->get_name();
$price_html    = $product->get_price_html();
$is_in_stock   = $product->is_in_stock();
$image_id      = $product->get_image_id();
$gallery_ids   = $product->get_gallery_image_ids();
$second_image  = ! empty( $gallery_ids ) ? $gallery_ids[0] : null;

$quality_terms = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'slugs' ) );
$tier_badge    = '';
$tier_class    = '';
if ( in_array( 'superclone', $quality_terms, true ) ) {
    $tier_badge = 'Superclone';
    $tier_class = 'gold';
} elseif ( in_array( 'bp-swiss-made', $quality_terms, true ) ) {
    $tier_badge = 'BP Swiss';
    $tier_class = 'silver';
} elseif ( in_array( 'aaa', $quality_terms, true ) ) {
    $tier_badge = 'AAA+';
    $tier_class = 'bronze';
}
?>
<div class="product-card <?php echo ! $is_in_stock ? 'product-card--sold-out' : ''; ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>">
    <a href="<?php echo esc_url( $permalink ); ?>" class="product-card__link" aria-label="<?php echo esc_attr( $title ); ?>">
        <div class="product-card__image">
            <?php if ( $image_id ) : ?>
                <?php echo wp_get_attachment_image( $image_id, 'woocommerce_thumbnail', false, array(
                    'class'   => 'product-card__img product-card__img--primary',
                    'loading' => 'lazy',
                ) ); ?>
            <?php else : ?>
                <div class="product-card__img-placeholder">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
            <?php endif; ?>

            <?php if ( $second_image ) : ?>
                <?php echo wp_get_attachment_image( $second_image, 'woocommerce_thumbnail', false, array(
                    'class'   => 'product-card__img product-card__img--hover',
                    'loading' => 'lazy',
                ) ); ?>
            <?php endif; ?>

            <div class="product-card__badges">
                <?php if ( $tier_badge ) : ?>
                    <span class="product-card__badge product-card__badge--<?php echo esc_attr( $tier_class ); ?>">
                        <?php echo esc_html( $tier_badge ); ?>
                    </span>
                <?php endif; ?>

                <?php if ( ! $is_in_stock ) : ?>
                    <span class="product-card__badge product-card__badge--sold-out">Uitverkocht</span>
                <?php endif; ?>

                <?php if ( $product->is_on_sale() ) : ?>
                    <span class="product-card__badge product-card__badge--sale">Sale</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="product-card__info">
            <h3 class="product-card__title"><?php echo esc_html( $title ); ?></h3>
            <div class="product-card__price"><?php echo wp_kses_post( $price_html ); ?></div>
        </div>
    </a>

    <div class="product-card__actions">
        <?php if ( $is_in_stock ) : ?>
            <?php
            echo apply_filters(
                'woocommerce_loop_add_to_cart_link',
                sprintf(
                    '<a href="%s" data-quantity="1" class="product-card__atc btn btn--sm btn--gold" data-product_id="%d" data-product_sku="%s" aria-label="%s" rel="nofollow">%s</a>',
                    esc_url( $product->add_to_cart_url() ),
                    esc_attr( $product_id ),
                    esc_attr( $product->get_sku() ),
                    esc_attr( sprintf( 'Voeg %s toe aan winkelwagen', $title ) ),
                    'In winkelmand'
                ),
                $product
            );
            ?>
        <?php else : ?>
            <span class="product-card__sold-out-label">Uitverkocht</span>
        <?php endif; ?>
    </div>
</div>
