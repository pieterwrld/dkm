<?php
/**
 * Navigation: the header menu and the mega menu panel
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

/**
 * The categories the mega menu shows, in order. Each entry is resolved against
 * product_cat, so a category that does not exist is simply skipped.
 *
 * @return string[]
 */
function dkm_mega_slugs(): array {
	/**
	 * Filters the categories in the Collectie mega menu.
	 *
	 * @param string[] $slugs product_cat slugs.
	 */
	return (array) apply_filters(
		'dkm_mega_slugs',
		array( 'superclone', 'bp-swiss-made', 'aaa', 'dames', 'brillen' )
	);
}

/**
 * The top level navigation. Falls back to the quality tiers plus the usual
 * category pages when no menu is assigned.
 *
 * @return array<int,array{label:string,url:string,slug:string,red:bool}>
 */
function dkm_nav_items(): array {
	$items = array();

	$defaults = array(
		array( 'superclone', __( 'Superclone', 'dkm' ) ),
		array( 'bp-swiss-made', __( 'BP Swiss Made', 'dkm' ) ),
		array( 'aaa', __( 'AAA+', 'dkm' ) ),
		array( 'dames', __( 'Dames', 'dkm' ) ),
		array( 'brillen', __( 'Brillen', 'dkm' ) ),
		array( 'rolex-dozen', __( 'Dozen', 'dkm' ) ),
		array( 'sale', __( 'Sale', 'dkm' ) ),
	);

	foreach ( $defaults as [$slug, $label] ) {
		$term = get_term_by( 'slug', $slug, 'product_cat' );
		if ( ! $term || is_wp_error( $term ) ) {
			continue;
		}
		$items[] = array(
			'label' => $label,
			'url'   => (string) get_term_link( $term ),
			'slug'  => $slug,
			'red'   => 'sale' === $slug && dkm_black_friday_active(),
		);
	}

	return $items;
}

/**
 * Mega menu cards: a category with its own WooCommerce category image, the
 * product count, and a link.
 *
 * @return array<int,array{name:string,url:string,count:int,image:string,slug:string}>
 */
function dkm_mega_cards(): array {
	$cards = array();

	foreach ( dkm_mega_slugs() as $slug ) {
		$term = get_term_by( 'slug', $slug, 'product_cat' );
		if ( ! $term || is_wp_error( $term ) ) {
			continue;
		}

		$tier  = dkm_get_tier( $slug );
		$image = dkm_product_cat_image( $term, 'dkm-card' );

		$cards[] = array(
			'name'  => $tier ? $tier['name'] : $term->name,
			'sub'   => $tier
				? $tier['sub']
				/* translators: %d is a number of products. */
				: sprintf( _n( '%d product', '%d producten', $term->count, 'dkm' ), $term->count ),
			'url'   => (string) get_term_link( $term ),
			'count' => (int) $term->count,
			'image' => $image,
			'slug'  => $slug,
		);
	}

	return $cards;
}

/**
 * The image for a product category: its own thumbnail when the client has set
 * one, otherwise the newest product in it, so the menu is never empty.
 *
 * @param WP_Term $term Category.
 * @param string  $size Image size.
 * @return string Ready to echo img markup, or an empty string.
 */
function dkm_product_cat_image( WP_Term $term, string $size = 'dkm-card' ): string {
	$attachment_id = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );

	if ( ! $attachment_id ) {
		$product_ids = get_posts(
			array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'orderby'        => 'date',
				'order'          => 'DESC',
				'tax_query'      => array(
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'slug',
						'terms'    => $term->slug,
					),
				),
				'meta_query'     => array(
					array(
						'key'     => '_thumbnail_id',
						'compare' => 'EXISTS',
					),
				),
			)
		);
		if ( $product_ids ) {
			$attachment_id = (int) get_post_thumbnail_id( $product_ids[0] );
		}
	}

	if ( ! $attachment_id ) {
		return '';
	}

	return wp_get_attachment_image(
		$attachment_id,
		$size,
		false,
		array(
			'loading' => 'lazy',
			'alt'     => $term->name,
		)
	);
}

/**
 * Walker for the header menu, so an assigned WordPress menu gets the same
 * markup as the fallback.
 */
class DKM_Nav_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '<ul class="nav__sub">';
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</ul>';
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'nav__item';
		if ( in_array( 'current-menu-item', $classes, true ) ) {
			$classes[] = 'is-current';
		}

		$output .= sprintf( '<li class="%s">', esc_attr( implode( ' ', array_filter( $classes ) ) ) );
		$output .= sprintf(
			'<a class="nav__link" href="%s">%s</a>',
			esc_url( $item->url ),
			esc_html( $item->title )
		);
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= '</li>';
	}
}

/**
 * Walker for the footer columns, which are plain links rather than a list.
 */
class DKM_Footer_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {}

	public function end_lvl( &$output, $depth = 0, $args = null ) {}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$output .= sprintf(
			'<a class="ft__l" href="%s">%s</a>',
			esc_url( $item->url ),
			esc_html( $item->title )
		);
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {}
}


/**
 * The newest published product that has a picture.
 *
 * Used where there is no category to take a picture from, such as the shop
 * page itself.
 *
 * @param string $size Image size to render.
 */
function dkm_latest_product_image( string $size = 'dkm-card' ): string {
	$ids = get_posts(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'orderby'        => 'date',
			'order'          => 'DESC',
			'meta_query'     => array(
				array(
					'key'     => '_thumbnail_id',
					'compare' => 'EXISTS',
				),
			),
		)
	);

	if ( ! $ids ) {
		return '';
	}

	return (string) get_the_post_thumbnail(
		(int) $ids[0],
		$size,
		array(
			'alt'     => '',
			'loading' => 'lazy',
			'sizes'   => '(max-width:720px) 100vw, 480px',
		)
	);
}
