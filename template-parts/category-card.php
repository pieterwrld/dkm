<?php
/**
 * Category card for shop/archive pages
 *
 * @package DKM
 */

$category = isset( $args['category'] ) ? $args['category'] : null;

if ( ! $category ) {
    return;
}

$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
$image_url    = $thumbnail_id ? wp_get_attachment_url( $thumbnail_id ) : '';
$cat_link     = get_term_link( $category );
$count        = $category->count;
?>
<a href="<?php echo esc_url( $cat_link ); ?>" class="category-card">
    <div class="category-card__image">
        <?php if ( $image_url ) : ?>
            <img src="<?php echo esc_url( $image_url ); ?>"
                 alt="<?php echo esc_attr( $category->name ); ?>"
                 loading="lazy"
                 class="category-card__img">
        <?php else : ?>
            <div class="category-card__placeholder" aria-hidden="true">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
        <?php endif; ?>
        <div class="category-card__overlay"></div>
    </div>
    <div class="category-card__content">
        <h3 class="category-card__name"><?php echo esc_html( $category->name ); ?></h3>
        <span class="category-card__count">
            <?php
            printf(
                esc_html( _n( '%d product', '%d producten', $count, 'dkm' ) ),
                $count
            );
            ?>
        </span>
    </div>
</a>
