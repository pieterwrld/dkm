<?php
/**
 * WooCommerce integration
 *
 * The theme draws every storefront template itself, so most of WooCommerce's
 * own hooks are removed rather than restyled.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

/* ==========================================================================
   Strip the default storefront furniture
   ========================================================================== */

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

// The sale flash is drawn inside the card, not floated over the image.
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );

// The design has no tabs, no upsells and no VAT line.
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

add_filter( 'woocommerce_product_tabs', '__return_empty_array' );
add_filter( 'wc_get_template_part', 'dkm_template_part', 10, 3 );

/**
 * Lets the theme answer for the loop parts without a woocommerce/ copy.
 */
function dkm_template_part( string $template, string $slug, string $name ): string {
	if ( 'content' === $slug && 'product' === $name ) {
		$own = DKM_DIR . '/woocommerce/content-product.php';
		if ( file_exists( $own ) ) {
			return $own;
		}
	}
	return $template;
}

/* ==========================================================================
   Catalogue shape
   ========================================================================== */

add_filter( 'loop_shop_columns', fn() => 3 );
add_filter( 'loop_shop_per_page', fn() => DKM_PER_PAGE, 20 );

add_filter( 'woocommerce_breadcrumb_defaults', 'dkm_breadcrumb_defaults' );

function dkm_breadcrumb_defaults( array $args ): array {
	$args['delimiter']   = '<span class="crumb__sep" aria-hidden="true">›</span>';
	// The shop prints this outside the content column, so it carries its own
	// gutter and the same maximum width as everything else.
	$args['wrap_before'] = '<div class="crumbwrap"><nav class="crumb" aria-label="' . esc_attr__( 'Kruimelpad', 'dkm' ) . '">';
	$args['wrap_after']  = '</nav></div>';
	$args['before']      = '';
	$args['after']       = '';
	$args['home']        = __( 'Home', 'dkm' );
	return $args;
}

/* ==========================================================================
   Wording
   ========================================================================== */

add_filter( 'woocommerce_product_single_add_to_cart_text', fn() => __( 'In winkelwagen', 'dkm' ) );
add_filter( 'woocommerce_product_add_to_cart_text', fn() => __( 'In winkelwagen', 'dkm' ) );

add_filter( 'woocommerce_get_availability_text', 'dkm_availability_text', 10, 2 );

function dkm_availability_text( string $text, WC_Product $product ): string {
	if ( $product->is_in_stock() ) {
		return __( 'Op voorraad, vandaag verzonden', 'dkm' );
	}
	return __( 'Tijdelijk uitverkocht', 'dkm' );
}

/**
 * Prices are whole euros in this shop, so the decimals are dropped unless a
 * product genuinely has cents.
 */
add_filter( 'wc_price_args', 'dkm_price_args' );

function dkm_price_args( array $args ): array {
	$args['decimals'] = 0;
	return $args;
}

add_filter( 'woocommerce_cart_item_price', 'dkm_keep_price_plain', 10, 1 );

function dkm_keep_price_plain( $price ) {
	return $price;
}

/* ==========================================================================
   Related products: four, same tier where possible
   ========================================================================== */

add_filter( 'woocommerce_output_related_products_args', 'dkm_related_args' );

function dkm_related_args( array $args ): array {
	$args['posts_per_page'] = 4;
	$args['columns']        = 4;
	return $args;
}

add_filter( 'woocommerce_related_products', 'dkm_related_same_tier', 10, 3 );

/**
 * Prefers related products from the same quality tier, because a shopper
 * looking at a superclone is not shopping for an AAA+.
 *
 * @param int[] $related_ids Candidate ids.
 * @param int   $product_id  Current product.
 */
function dkm_related_same_tier( array $related_ids, int $product_id, array $args ): array {
	$tier = dkm_product_tier( $product_id );
	if ( ! $tier ) {
		return $related_ids;
	}

	$slug = array_search( $tier, dkm_tiers(), true );
	if ( ! $slug ) {
		return $related_ids;
	}

	$same = get_posts(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 8,
			'fields'         => 'ids',
			'post__not_in'   => array( $product_id ),
			'orderby'        => 'rand',
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $slug,
				),
			),
		)
	);

	return $same ? $same : $related_ids;
}

/* ==========================================================================
   Body classes
   ========================================================================== */

add_filter( 'body_class', 'dkm_body_class' );

function dkm_body_class( array $classes ): array {
	if ( dkm_black_friday_active() ) {
		$classes[] = 'dkm-bf';
	}
	if ( function_exists( 'is_product' ) && is_product() ) {
		$tier      = dkm_product_tier( get_the_ID() );
		$classes[] = 'dkm-pdp';
		$classes[] = 'dkm-tier-' . ( $tier ? $tier['key'] : 'other' );
	}
	if ( dkm_is_collection() ) {
		$classes[] = 'dkm-collection';
	}
	return $classes;
}

/* ==========================================================================
   The three product templates
   ========================================================================== */

/**
 * Which product template part to load. Each tier has its own file, so the
 * layout can genuinely differ and not just the colour.
 */
function dkm_product_template_slug( ?array $tier ): string {
	$slug = $tier ? $tier['template'] : 'other';

	/**
	 * Filters the product template part for a tier.
	 *
	 * @param string     $slug Template name under template-parts/product/.
	 * @param array|null $tier Tier definition.
	 */
	return (string) apply_filters( 'dkm_product_template_slug', $slug, $tier );
}


/* ==========================================================================
   Free delivery over a set amount
   ========================================================================== */

/**
 * The amount from which delivery is free, as WooCommerce has it.
 *
 * The figure is read from the shipping zones rather than written out here, so
 * what the page promises and what the basket charges can never drift apart.
 * The fallback is only used before a zone has been set up.
 */
function dkm_free_shipping_from(): float {
	static $bedrag = null;
	if ( null !== $bedrag ) {
		return $bedrag;
	}

	$bedrag = 0.0;

	if ( class_exists( 'WC_Shipping_Zones' ) ) {
		$zones   = WC_Shipping_Zones::get_zones();
		$zones[] = array( 'shipping_methods' => WC_Shipping_Zones::get_zone( 0 )->get_shipping_methods() );

		foreach ( $zones as $zone ) {
			foreach ( (array) ( $zone['shipping_methods'] ?? array() ) as $methode ) {
				if ( 'free_shipping' !== $methode->id || 'yes' !== $methode->enabled ) {
					continue;
				}
				$grens = (float) ( $methode->min_amount ?? 0 );
				if ( $grens <= 0 ) {
					continue;
				}
				$bedrag = $bedrag ? min( $bedrag, $grens ) : $grens;
			}
		}
	}

	if ( ! $bedrag ) {
		$bedrag = 350.0;
	}

	return $bedrag;
}

/**
 * What is still missing before delivery is free, or zero when it already is.
 */
function dkm_free_shipping_left(): float {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return 0.0;
	}

	$grens = dkm_free_shipping_from();
	$nu    = (float) WC()->cart->get_displayed_subtotal();

	if ( WC()->cart->display_prices_including_tax() ) {
		$nu = round( $nu - (float) WC()->cart->get_discount_tax(), wc_get_price_decimals() );
	}
	$nu -= (float) WC()->cart->get_discount_total();

	return max( 0.0, $grens - $nu );
}

add_filter( 'woocommerce_package_rates', 'dkm_only_free_when_free', 100, 2 );

/**
 * Hides the paid rates as soon as free delivery applies.
 *
 * Without this the basket offers both, and a customer who has earned free
 * delivery still sees a charge sitting next to it.
 *
 * @param array $rates The rates WooCommerce found.
 * @return array
 */
function dkm_only_free_when_free( $rates ) {
	$gratis = array();

	foreach ( (array) $rates as $sleutel => $tarief ) {
		if ( 'free_shipping' === $tarief->get_method_id() ) {
			$gratis[ $sleutel ] = $tarief;
		}
	}

	return $gratis ? $gratis : $rates;
}
