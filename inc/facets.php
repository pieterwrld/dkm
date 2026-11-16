<?php
/**
 * Faceted filtering
 *
 * One REST route returns the rendered cards and the counts in a single
 * request. The cards are built server side so they keep their srcset, their
 * alt text and the same markup as every other grid in the theme, which a
 * client side renderer against the Store API would have to duplicate.
 *
 * Counts follow the usual faceted rule: the tally for a facet ignores that
 * facet's own selection, so a shopper can see what switching to another model
 * would give them.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

const DKM_PER_PAGE = 24;

add_action( 'rest_api_init', 'dkm_register_routes' );

function dkm_register_routes(): void {
	register_rest_route(
		'dkm/v1',
		'/products',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'permission_callback' => '__return_true',
			'callback'            => 'dkm_rest_products',
			'args'                => array(
				'cat'      => array( 'type' => 'string', 'default' => '' ),
				'tier'     => array( 'type' => 'string', 'default' => '' ),
				'model'    => array( 'type' => 'string', 'default' => '' ),
				'maat'     => array( 'type' => 'string', 'default' => '' ),
				'max'      => array( 'type' => 'integer', 'default' => 0 ),
				'sort'     => array( 'type' => 'string', 'default' => 'rec' ),
				'page'     => array( 'type' => 'integer', 'default' => 1 ),
				'per_page' => array( 'type' => 'integer', 'default' => DKM_PER_PAGE ),
			),
		)
	);
}

/**
 * Normalises the request into a filter array.
 */
function dkm_parse_filters( $request ): array {
	$list = static function ( $value ): array {
		if ( is_array( $value ) ) {
			$parts = $value;
		} else {
			$parts = explode( ',', (string) $value );
		}
		$parts = array_map( 'sanitize_title', array_map( 'trim', $parts ) );
		return array_values( array_filter( array_unique( $parts ) ) );
	};

	return array(
		'cat'      => sanitize_title( (string) $request['cat'] ),
		'tier'     => $list( $request['tier'] ),
		'model'    => $list( $request['model'] ),
		'maat'     => $list( $request['maat'] ),
		'max'      => max( 0, (int) $request['max'] ),
		'sort'     => in_array( $request['sort'], array( 'rec', 'asc', 'desc', 'az', 'new' ), true ) ? $request['sort'] : 'rec',
		'page'     => max( 1, (int) $request['page'] ),
		'per_page' => min( 48, max( 1, (int) $request['per_page'] ) ),
	);
}

/**
 * Builds the WP_Query arguments for a filter set.
 *
 * @param array  $filters Parsed filters.
 * @param string $ignore  Facet to leave out, used while counting.
 */
function dkm_query_args( array $filters, string $ignore = '' ): array {
	$tax = array( 'relation' => 'AND' );

	if ( '' !== $filters['cat'] ) {
		$tax[] = array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => $filters['cat'],
		);
	}

	if ( 'tier' !== $ignore && $filters['tier'] ) {
		$tax[] = array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => $filters['tier'],
			'operator' => 'IN',
		);
	}

	foreach ( array( 'model' => 'pa_model', 'maat' => 'pa_maat' ) as $key => $taxonomy ) {
		if ( $key === $ignore || ! $filters[ $key ] ) {
			continue;
		}
		$tax[] = array(
			'taxonomy' => $taxonomy,
			'field'    => 'slug',
			'terms'    => $filters[ $key ],
			'operator' => 'IN',
		);
	}

	$args = array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => false,
		'posts_per_page'      => $filters['per_page'],
		'paged'               => $filters['page'],
	);

	if ( count( $tax ) > 1 ) {
		$args['tax_query'] = $tax;
	}

	if ( 'price' !== $ignore && $filters['max'] > 0 ) {
		$args['meta_query'] = array(
			array(
				'key'     => '_price',
				'value'   => $filters['max'],
				'compare' => '<=',
				'type'    => 'NUMERIC',
			),
		);
	}

	// Hide anything WooCommerce says is out of the catalogue.
	if ( function_exists( 'WC' ) ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'terms'    => array( 'exclude-from-catalog' ),
			'operator' => 'NOT IN',
		);
	}

	switch ( $filters['sort'] ) {
		case 'asc':
			$args['meta_key'] = '_price';
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'ASC';
			break;
		case 'desc':
			$args['meta_key'] = '_price';
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'DESC';
			break;
		case 'az':
			$args['orderby'] = 'title';
			$args['order']   = 'ASC';
			break;
		case 'new':
			$args['orderby'] = 'date';
			$args['order']   = 'DESC';
			break;
		default:
			$args['orderby'] = array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			);
			break;
	}

	return $args;
}

/**
 * Counts how many products each term of a taxonomy would match, with that
 * taxonomy's own selection ignored.
 *
 * @param array  $filters  Parsed filters.
 * @param string $facet    Facet key: tier, model or maat.
 * @param string $taxonomy Taxonomy to count.
 * @return array<string,int> Slug to count.
 */
function dkm_facet_counts( array $filters, string $facet, string $taxonomy ): array {
	global $wpdb;

	$args                   = dkm_query_args( $filters, $facet );
	$args['posts_per_page'] = -1;
	$args['paged']          = 1;
	$args['fields']         = 'ids';
	$args['no_found_rows']  = true;

	$ids = get_posts( $args );
	if ( ! $ids ) {
		return array();
	}

	$ids_in = implode( ',', array_map( 'absint', $ids ) );

	// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- ids are cast to int above.
	$rows = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT t.slug AS slug, COUNT(DISTINCT tr.object_id) AS total
			 FROM {$wpdb->term_relationships} tr
			 INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
			 INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id
			 WHERE tt.taxonomy = %s AND tr.object_id IN ($ids_in)
			 GROUP BY t.slug",
			$taxonomy
		),
		ARRAY_A
	);
	// phpcs:enable

	$counts = array();
	foreach ( (array) $rows as $row ) {
		$counts[ (string) $row['slug'] ] = (int) $row['total'];
	}

	return $counts;
}

/**
 * All facet data for a filter set, plus the total.
 */
function dkm_facets( array $filters ): array {
	$tier_counts = dkm_facet_counts( $filters, 'tier', 'product_cat' );

	$tiers = array();
	foreach ( dkm_tiers() as $slug => $tier ) {
		$tiers[] = array(
			'slug'  => $slug,
			'label' => $tier['name'],
			'count' => $tier_counts[ $slug ] ?? 0,
		);
	}

	return array(
		'tier'  => $tiers,
		'model' => dkm_facet_terms( $filters, 'model', 'pa_model' ),
		'maat'  => dkm_facet_terms( $filters, 'maat', 'pa_maat' ),
	);
}

/**
 * Facet options for an attribute taxonomy, including the ones that would give
 * no results so the list does not jump around while filtering.
 */
function dkm_facet_terms( array $filters, string $facet, string $taxonomy ): array {
	$counts = dkm_facet_counts( $filters, $facet, $taxonomy );

	$terms = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
			'orderby'    => 'name',
		)
	);
	if ( is_wp_error( $terms ) ) {
		return array();
	}

	$out = array();
	foreach ( $terms as $term ) {
		$out[] = array(
			'slug'  => $term->slug,
			'label' => $term->name,
			'count' => $counts[ $term->slug ] ?? 0,
		);
	}

	// Case sizes read better small to large than alphabetically.
	if ( 'pa_maat' === $taxonomy ) {
		usort(
			$out,
			static fn( $a, $b ) => (int) preg_replace( '/\D/', '', $a['label'] ) <=> (int) preg_replace( '/\D/', '', $b['label'] )
		);
	}

	return $out;
}

/**
 * The price range of everything in the current category, used for the slider
 * bounds.
 */
function dkm_price_bounds( array $filters ): array {
	$args                   = dkm_query_args( array( 'cat' => $filters['cat'] ) + $filters + array(), 'price' );
	$args['posts_per_page'] = -1;
	$args['fields']         = 'ids';
	$args['no_found_rows']  = true;

	$ids = get_posts( $args );
	if ( ! $ids ) {
		return array( 'min' => 0, 'max' => 0 );
	}

	$prices = array();
	foreach ( $ids as $id ) {
		$price = get_post_meta( $id, '_price', true );
		if ( '' !== $price ) {
			$prices[] = (float) $price;
		}
	}
	if ( ! $prices ) {
		return array( 'min' => 0, 'max' => 0 );
	}

	return array(
		'min' => (int) floor( min( $prices ) ),
		'max' => (int) ceil( max( $prices ) ),
	);
}

/**
 * REST handler: rendered cards plus counts.
 */
function dkm_rest_products( WP_REST_Request $request ) {
	$filters = dkm_parse_filters( $request );
	$query   = new WP_Query( dkm_query_args( $filters ) );

	$html = '';
	if ( $query->have_posts() ) {
		ob_start();
		while ( $query->have_posts() ) {
			$query->the_post();
			$product = wc_get_product( get_the_ID() );
			if ( $product ) {
				get_template_part( 'template-parts/product-card', null, array( 'product' => $product ) );
			}
		}
		$html = (string) ob_get_clean();
	}
	wp_reset_postdata();

	return rest_ensure_response(
		array(
			'html'   => $html,
			'total'  => (int) $query->found_posts,
			'pages'  => (int) $query->max_num_pages,
			'page'   => $filters['page'],
			'facets' => dkm_facets( $filters ),
			'price'  => dkm_price_bounds( $filters ),
		)
	);
}
