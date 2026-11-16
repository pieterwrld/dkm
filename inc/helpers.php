<?php
/**
 * Small helpers used across templates
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

/**
 * The WhatsApp link, with an optional prefilled message.
 */
function dkm_whatsapp_url( string $message = '' ): string {
	$number = preg_replace( '/\D/', '', (string) get_theme_mod( 'dkm_whatsapp', '31647974408' ) );
	if ( '' === $number ) {
		return '';
	}
	$url = 'https://wa.me/' . $number;
	if ( '' !== $message ) {
		$url .= '?text=' . rawurlencode( $message );
	}
	return $url;
}

/**
 * Price as the shop writes it: no decimals when the amount is whole.
 */
function dkm_price( $amount ): string {
	$amount = (float) $amount;
	$whole  = abs( $amount - round( $amount ) ) < 0.005;
	return '€' . number_format( $amount, $whole ? 0 : 2, ',', '.' );
}

/**
 * Whether the Black Friday mode is on. Red takes over from gold while it is.
 */
function dkm_black_friday_active(): bool {
	if ( ! get_theme_mod( 'dkm_bf_enabled', false ) ) {
		return false;
	}
	$ends = dkm_black_friday_ends();
	return $ends && $ends > time();
}

/**
 * Unix timestamp the Black Friday countdown runs to. Midnight at the end of the
 * configured day, in the site's own timezone.
 */
function dkm_black_friday_ends(): int {
	$date = (string) get_theme_mod( 'dkm_bf_until', '' );
	if ( '' === $date ) {
		return 0;
	}
	try {
		$tz  = wp_timezone();
		$end = new DateTimeImmutable( $date . ' 23:59:59', $tz );
		return $end->getTimestamp();
	} catch ( Exception $e ) {
		return 0;
	}
}

/**
 * Opens a full width section whose inner content stays within the container.
 */
function dkm_wrap_open( string $classes = '' ): string {
	return '<div class="wrap ' . esc_attr( $classes ) . '">';
}

/**
 * The brand a product title starts with, used as the kicker on a card.
 */
function dkm_product_brand( WC_Product $product ): string {
	$name = dkm_short_title( $product->get_name() );

	if ( has_term( 'rolex-dozen', 'product_cat', $product->get_id() ) ) {
		return __( 'Accessoire', 'dkm' );
	}
	if ( has_term( 'brillen', 'product_cat', $product->get_id() ) ) {
		$first = explode( '|', $name );
		return trim( $first[0] ) ?: __( 'Eyewear', 'dkm' );
	}

	$words = preg_split( '/\s+/', $name ) ?: array();
	if ( ! $words ) {
		return '';
	}
	if ( 'Rolex' === $words[0] && isset( $words[1] ) ) {
		return trim( str_replace( array( '"', '|', '″' ), '', $words[1] ) );
	}
	return $words[0];
}

/**
 * A product attribute as a plain string, empty when it is not set.
 */
function dkm_attr( WC_Product $product, string $taxonomy ): string {
	$terms = wc_get_product_terms( $product->get_id(), $taxonomy, array( 'fields' => 'names' ) );
	return $terms ? (string) $terms[0] : '';
}

/**
 * The tier badge that sits on a card and on the gallery.
 */
function dkm_tier_badge( ?array $tier, string $classes = '' ): string {
	if ( ! $tier ) {
		return '';
	}
	return sprintf(
		'<span class="tg tg--%s %s">%s</span>',
		esc_attr( $tier['key'] ),
		esc_attr( $classes ),
		esc_html( $tier['name'] )
	);
}

/**
 * Renders an escaped star rating as text, which is what the design shows.
 */
function dkm_stars( float $rating = 5.0 ): string {
	$full = max( 0, min( 5, (int) round( $rating ) ) );
	return '<span class="st" aria-hidden="true">' . str_repeat( '★', $full ) . str_repeat( '☆', 5 - $full ) . '</span>';
}

/**
 * Initials for an avatar, at most two letters.
 */
function dkm_initials( string $name ): string {
	$parts = preg_split( '/\s+/', trim( $name ) ) ?: array();
	$out   = '';
	foreach ( array_slice( $parts, 0, 2 ) as $part ) {
		$out .= mb_strtoupper( mb_substr( $part, 0, 1 ) );
	}
	return $out ?: '?';
}

/**
 * The discount on a product as a whole percentage, or 0 when it is not on sale.
 */
function dkm_sale_percentage( WC_Product $product ): int {
	if ( ! $product->is_on_sale() ) {
		return 0;
	}
	$regular = (float) $product->get_regular_price();
	$sale    = (float) $product->get_price();
	if ( $regular <= 0 || $sale >= $regular ) {
		return 0;
	}
	return (int) round( ( 1 - $sale / $regular ) * 100 );
}

/**
 * Writes a phone number the way a Dutch reader expects it.
 */
function dkm_format_phone( string $digits ): string {
	$digits = preg_replace( '/\D/', '', $digits );
	if ( str_starts_with( (string) $digits, '31' ) && strlen( (string) $digits ) === 11 ) {
		return '+31 ' . substr( $digits, 2, 1 ) . ' ' . substr( $digits, 3, 8 );
	}
	return '+' . $digits;
}

/**
 * The quality information page: whichever page uses that template, falling
 * back to a page with the expected slug and then to the shop.
 */
function dkm_info_page_url(): string {
	$found = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_wp_page_template',
			'meta_value'     => 'page-kwaliteiten.php',
		)
	);
	if ( $found ) {
		return (string) get_permalink( $found[0] );
	}

	$page = get_page_by_path( 'kwaliteitsinformatie' );
	if ( $page ) {
		return (string) get_permalink( $page );
	}

	return function_exists( 'wc_get_page_permalink' ) ? (string) wc_get_page_permalink( 'shop' ) : home_url( '/' );
}

/**
 * The shop root, used by every "all watches" link.
 */
function dkm_shop_url(): string {
	return function_exists( 'wc_get_page_permalink' ) ? (string) wc_get_page_permalink( 'shop' ) : home_url( '/' );
}

/**
 * True when the current request is the shop or any product taxonomy archive.
 */
function dkm_is_collection(): bool {
	return function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() );
}

/**
 * The category the collection page is currently showing, or null on the shop
 * root.
 */
function dkm_current_product_cat(): ?WP_Term {
	if ( ! is_tax( 'product_cat' ) ) {
		return null;
	}
	$term = get_queried_object();
	return $term instanceof WP_Term ? $term : null;
}
