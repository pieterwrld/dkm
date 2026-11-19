<?php
/**
 * WP-CLI commands
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

/**
 * Data maintenance for De Klokjesman.
 */
class DKM_CLI {

	/**
	 * The attribute taxonomies the shop filters on.
	 */
	private const ATTRIBUTES = array(
		'pa_model'       => 'Model',
		'pa_maat'        => 'Maat',
		'pa_wijzerplaat' => 'Wijzerplaat',
	);

	/**
	 * Models that appear in a product title. Longest first, because
	 * "GMT-Master II" has to win over "GMT-Master".
	 */
	private const MODEL_PATTERNS = array(
		'GMT-Master'   => '/\bGMT[\s-]?Master(\s+II)?\b/i',
		'Day-Date'     => '/\bDay[\s-]?Date\b/i',
		'Datejust'     => '/\b(Lady[\s-])?Datejust\b/i',
		'Daytona'      => '/\bDaytona\b/i',
		'Submariner'   => '/\bSubmariner\b/i',
		'Yacht-Master' => '/\bYacht[\s-]?Master\b/i',
		'Royal Oak'    => '/\bRoyal\s+Oak\b/i',
		'Dumont'       => '/\bDumont\b/i',
		'Santos'       => '/\bSantos\b/i',
		'Large Size'   => '/\bLarge\s+Size\b/i',
		'Midsize'      => '/\bMid[\s-]?size\b/i',
	);

	/**
	 * Creates pa_model, pa_maat and pa_wijzerplaat if they do not exist yet.
	 *
	 * ## EXAMPLES
	 *
	 *     wp dkm create-attributes
	 *
	 * @subcommand create-attributes
	 * @when after_wp_load
	 */
	public function create_attributes(): void {
		foreach ( self::ATTRIBUTES as $taxonomy => $label ) {
			$slug = str_replace( 'pa_', '', $taxonomy );
			if ( wc_attribute_taxonomy_id_by_name( $slug ) ) {
				$this->log( sprintf( /* translators: %s is an attribute name. */ __( '%s bestaat al', 'dkm' ), $taxonomy ) );
				continue;
			}
			$res = wc_create_attribute(
				array(
					'name'         => $label,
					'slug'         => $slug,
					'type'         => 'select',
					'order_by'     => 'menu_order',
					'has_archives' => false,
				)
			);
			if ( is_wp_error( $res ) ) {
				$this->warn( "$taxonomy: " . $res->get_error_message() );
				continue;
			}
			$this->ok( sprintf( /* translators: %s is an attribute name. */ __( '%s aangemaakt', 'dkm' ), $taxonomy ) );
		}
		$this->flush_attribute_cache();
	}

	/**
	 * Fills pa_model, pa_maat and pa_wijzerplaat from the parsed CSV.
	 *
	 * Safe to run more than once: a product whose attributes already match is
	 * left alone. Values missing from the CSV are derived from the product
	 * title where that is unambiguous, and anything still unresolved is listed
	 * at the end so it can be completed by hand.
	 *
	 * ## OPTIONS
	 *
	 * [--file=<path>]
	 * : CSV with the columns id, slug, name, pa_model, pa_maat, pa_wijzerplaat.
	 * Defaults to data/product-attributes.csv in this plugin.
	 *
	 * [--dry-run]
	 * : Report what would change without writing anything.
	 *
	 * [--no-derive]
	 * : Only use the CSV, never fall back to parsing the title.
	 *
	 * ## EXAMPLES
	 *
	 *     wp dkm backfill-attributes --dry-run
	 *     wp dkm backfill-attributes
	 *     wp dkm backfill-attributes --file=/tmp/attrs.csv
	 *
	 * @subcommand backfill-attributes
	 * @when after_wp_load
	 */
	public function backfill_attributes( array $args, array $assoc ): void {
		$file    = $assoc['file'] ?? DKM_CORE_DIR . '/data/product-attributes.csv';
		$dry     = ! empty( $assoc['dry-run'] );
		$derive  = empty( $assoc['no-derive'] );

		if ( ! is_readable( $file ) ) {
			$this->fail( sprintf( /* translators: %s is a file path. */ __( 'Kan %s niet lezen.', 'dkm' ), $file ) );
		}

		foreach ( self::ATTRIBUTES as $taxonomy => $label ) {
			if ( ! taxonomy_exists( $taxonomy ) ) {
				$this->fail( sprintf( /* translators: %s is an attribute name. */ __( '%s bestaat nog niet. Gebruik eerst Attributen aanmaken.', 'dkm' ), $taxonomy ) );
			}
		}

		$rows = $this->read_csv( $file );
		$this->log( sprintf( /* translators: 1: number of rows, 2: file name. */ __( '%1$d regels in %2$s', 'dkm' ), count( $rows ), basename( $file ) ) );
		if ( $dry ) {
			$this->log( __( 'Proefdraai, er wordt niets opgeslagen.', 'dkm' ) );
		}

		$changed    = 0;
		$unchanged  = 0;
		$derived    = array();
		$unresolved = array();
		$incomplete = array();

		$behandeld = array();

		foreach ( $rows as $row ) {
			$product = $this->resolve_product( $row );
			if ( ! $product ) {
				$unresolved[] = $row;
				continue;
			}

			$behandeld[ $product->get_id() ] = true;

			$values = array(
				'pa_model'       => trim( (string) ( $row['pa_model'] ?? '' ) ),
				'pa_maat'        => $this->normalise_size( (string) ( $row['pa_maat'] ?? '' ) ),
				'pa_wijzerplaat' => trim( (string) ( $row['pa_wijzerplaat'] ?? '' ) ),
			);

			if ( $derive ) {
				$title = $product->get_name();
				if ( '' === $values['pa_model'] ) {
					$guess = $this->derive_model( $title );
					if ( '' !== $guess ) {
						$values['pa_model'] = $guess;
						$derived[]          = array( $product->get_id(), 'pa_model', $guess, $title );
					}
				}
				if ( '' === $values['pa_maat'] ) {
					$guess = $this->derive_size( $title );
					if ( '' !== $guess ) {
						$values['pa_maat'] = $guess;
						$derived[]         = array( $product->get_id(), 'pa_maat', $guess, $title );
					}
				}
			}

			$values = array_filter( $values, fn( $v ) => '' !== $v );

			// A watch with no model left is worth reporting; a box or a pair of
			// glasses legitimately has none.
			if ( ! isset( $values['pa_model'] ) && $this->is_watch( $product ) ) {
				$incomplete[] = array( $product->get_id(), $product->get_name() );
			}

			if ( $this->attributes_match( $product, $values ) ) {
				++$unchanged;
				continue;
			}

			++$changed;
			if ( ! $dry ) {
				$this->apply_attributes( $product, $values );
			}
		}

		// Producten die niet in het databestand staan, zoals concepten. Daar is
		// de titel het enige wat we hebben, dus die wordt uitgelezen.
		$overig = 0;
		if ( $derive ) {
			foreach ( $this->all_product_ids() as $id ) {
				if ( isset( $behandeld[ $id ] ) ) {
					continue;
				}
				$product = wc_get_product( $id );
				if ( ! $product ) {
					continue;
				}

				$title  = $product->get_name();
				$values = array_filter(
					array(
						'pa_model' => $this->derive_model( $title ),
						'pa_maat'  => $this->derive_size( $title ),
					),
					fn( $v ) => '' !== $v
				);

				if ( ! $values ) {
					if ( $this->is_watch( $product ) ) {
						$incomplete[] = array( $id, $title );
					}
					continue;
				}

				foreach ( $values as $tax => $value ) {
					$derived[] = array( $id, $tax, $value, $title );
				}

				if ( $this->attributes_match( $product, $values ) ) {
					++$unchanged;
					continue;
				}

				++$changed;
				++$overig;
				if ( ! $dry ) {
					$this->apply_attributes( $product, $values );
				}
			}
		}

		$this->flush_attribute_cache();
		if ( ! $dry ) {
			wc_delete_product_transients();
		}

		$this->log( '' );
		$this->log( sprintf( /* translators: %d is a number of products. */ __( 'gewijzigd:            %d', 'dkm' ), $changed ) );
		$this->log( sprintf( /* translators: %d is a number of products. */ __( 'ongewijzigd:          %d', 'dkm' ), $unchanged ) );
		$this->log( sprintf( /* translators: %d is a number of values. */ __( 'uit de titel gehaald: %d', 'dkm' ), count( $derived ) ) );
		$this->log( sprintf( /* translators: %d is a number of products. */ __( 'buiten het databestand: %d', 'dkm' ), $overig ) );

		if ( $derived ) {
			$this->log( '' );
			$this->log( __( 'Uit de titel gehaald omdat de kolom leeg was:', 'dkm' ) );
			foreach ( $derived as [$id, $tax, $value, $title] ) {
				$this->log( sprintf( '  #%d %s = %s  (%s)', $id, $tax, $value, $title ) );
			}
		}

		if ( $unresolved ) {
			$this->log( '' );
			$this->warn( sprintf( /* translators: %d is a number of rows. */ __( '%d regels horen bij geen enkel product:', 'dkm' ), count( $unresolved ) ) );
			foreach ( $unresolved as $row ) {
				$this->log( sprintf( '  %s  %s', $row['id'] ?? '?', $row['name'] ?? '?' ) );
			}
		}

		if ( $incomplete ) {
			$this->log( '' );
			$this->warn( sprintf( /* translators: %d is a number of watches. */ __( '%d horloges hebben nog geen model en moeten met de hand worden ingevuld:', 'dkm' ), count( $incomplete ) ) );
			foreach ( $incomplete as [$id, $name] ) {
				$this->log( sprintf( '  #%d %s', $id, $name ) );
			}
		}

		$this->ok( $dry ? __( 'Proefdraai klaar.', 'dkm' ) : __( 'Bijvullen klaar.', 'dkm' ) );
	}

	/**
	 * Gives every product a SKU, using DKM-{id} where one is missing.
	 *
	 * ## OPTIONS
	 *
	 * [--dry-run]
	 * : Report what would change without writing anything.
	 *
	 * [--force]
	 * : Overwrite SKUs that are already set.
	 *
	 * ## EXAMPLES
	 *
	 *     wp dkm sync-skus --dry-run
	 *     wp dkm sync-skus
	 *
	 * @subcommand sync-skus
	 * @when after_wp_load
	 */
	public function sync_skus( array $args, array $assoc ): void {
		$dry   = ! empty( $assoc['dry-run'] );
		$force = ! empty( $assoc['force'] );

		$ids = get_posts(
			array(
				'post_type'      => 'product',
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'fields'         => 'ids',
			)
		);

		$set     = 0;
		$kept    = 0;
		$clashed = array();

		foreach ( $ids as $id ) {
			$product = wc_get_product( $id );
			if ( ! $product ) {
				continue;
			}
			$current = $product->get_sku();
			$wanted  = 'DKM-' . $id;

			if ( '' !== $current && ! $force ) {
				++$kept;
				continue;
			}
			if ( $current === $wanted ) {
				++$kept;
				continue;
			}

			$owner = wc_get_product_id_by_sku( $wanted );
			if ( $owner && (int) $owner !== (int) $id ) {
				$clashed[] = array( $id, $wanted, $owner );
				continue;
			}

			++$set;
			if ( ! $dry ) {
				$product->set_sku( $wanted );
				$product->save();
			}
		}

		$this->log( sprintf( /* translators: 1: number set, 2: number left alone. */ __( 'ingesteld: %1$d, ongemoeid gelaten: %2$d', 'dkm' ), $set, $kept ) );
		foreach ( $clashed as [$id, $sku, $owner] ) {
			$this->warn( sprintf( /* translators: 1: product id, 2: article number, 3: other product id. */ __( '#%1$d wilde %2$s maar #%3$d heeft dat nummer al', 'dkm' ), $id, $sku, $owner ) );
		}
		$this->ok( $dry ? __( 'Proefdraai klaar.', 'dkm' ) : __( 'Artikelnummers bijgewerkt.', 'dkm' ) );
	}

	/* ------------------------------------------------------------------ */

	/**
	 * Every product in the shop, drafts included.
	 *
	 * @return array<int, int> Product ids.
	 */
	private function all_product_ids(): array {
		return get_posts(
			array(
				'post_type'      => 'product',
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'orderby'        => 'ID',
				'order'          => 'ASC',
			)
		);
	}

	/**
	 * Reads a CSV into an array of associative rows.
	 */
	private function read_csv( string $file ): array {
		$handle = fopen( $file, 'r' );
		if ( ! $handle ) {
			$this->fail( sprintf( /* translators: %s is a file path. */ __( 'Kan %s niet openen.', 'dkm' ), $file ) );
		}

		$header = fgetcsv( $handle );
		if ( ! $header ) {
			$this->fail( __( 'Het CSV-bestand heeft geen kopregel.', 'dkm' ) );
		}
		$header = array_map( 'trim', $header );

		$rows = array();
		while ( ( $line = fgetcsv( $handle ) ) !== false ) {
			if ( count( $line ) === 1 && ( null === $line[0] || '' === trim( (string) $line[0] ) ) ) {
				continue;
			}
			$line   = array_pad( array_slice( $line, 0, count( $header ) ), count( $header ), '' );
			$rows[] = array_combine( $header, $line );
		}
		fclose( $handle );

		return $rows;
	}

	/**
	 * Finds the product a CSV row refers to. The slug is the most reliable
	 * bridge between the export and the shop, so it is tried first.
	 */
	private function resolve_product( array $row ): ?WC_Product {
		$slug = trim( (string) ( $row['slug'] ?? '' ) );
		if ( '' !== $slug ) {
			$found = get_posts(
				array(
					'post_type'      => 'product',
					'post_status'    => 'any',
					'name'           => $slug,
					'posts_per_page' => 1,
					'fields'         => 'ids',
				)
			);
			if ( $found ) {
				$product = wc_get_product( $found[0] );
				if ( $product ) {
					return $product;
				}
			}
		}

		$src_id = (int) ( $row['id'] ?? 0 );
		if ( $src_id ) {
			// Imported copies keep the original id in meta.
			$found = get_posts(
				array(
					'post_type'      => 'product',
					'post_status'    => 'any',
					'posts_per_page' => 1,
					'fields'         => 'ids',
					'meta_key'       => '_dkm_src_id',
					'meta_value'     => $src_id,
				)
			);
			if ( $found ) {
				$product = wc_get_product( $found[0] );
				if ( $product ) {
					return $product;
				}
			}

			$product = wc_get_product( $src_id );
			if ( $product ) {
				return $product;
			}
		}

		return null;
	}

	/**
	 * "40", "40mm" and "40 mm" all become "40 mm".
	 */
	private function normalise_size( string $value ): string {
		$value = trim( $value );
		if ( '' === $value ) {
			return '';
		}
		if ( preg_match( '/(\d{2})/', $value, $m ) ) {
			return $m[1] . ' mm';
		}
		return $value;
	}

	/**
	 * Reads the model out of a product title.
	 */
	private function derive_model( string $title ): string {
		foreach ( self::MODEL_PATTERNS as $model => $pattern ) {
			if ( preg_match( $pattern, $title ) ) {
				return $model;
			}
		}
		return '';
	}

	/**
	 * Reads the case size out of a product title, which writes it as 40" or 40″.
	 */
	private function derive_size( string $title ): string {
		if ( preg_match( '/\b(\d{2})\s*(?:mm|"|″|”)/iu', $title, $m ) ) {
			return $m[1] . ' mm';
		}
		return '';
	}

	/**
	 * Boxes and eyewear have no model, and that is correct.
	 */
	private function is_watch( WC_Product $product ): bool {
		$slugs = wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'slugs' ) );
		if ( is_wp_error( $slugs ) ) {
			return true;
		}
		foreach ( array( 'rolex-dozen', 'brillen', 'accessoires' ) as $not_a_watch ) {
			if ( in_array( $not_a_watch, $slugs, true ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * True when the product already carries exactly these values.
	 */
	private function attributes_match( WC_Product $product, array $values ): bool {
		foreach ( self::ATTRIBUTES as $taxonomy => $label ) {
			$current = wc_get_product_terms( $product->get_id(), $taxonomy, array( 'fields' => 'names' ) );
			$current = $current ? (string) $current[0] : '';
			$wanted  = $values[ $taxonomy ] ?? '';
			if ( $current !== $wanted ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Writes the attribute values, creating terms as needed, and keeps any
	 * attribute the shop already had that this command does not own.
	 */
	private function apply_attributes( WC_Product $product, array $values ): void {
		$attributes = $product->get_attributes();

		foreach ( self::ATTRIBUTES as $taxonomy => $label ) {
			if ( ! isset( $values[ $taxonomy ] ) ) {
				unset( $attributes[ $taxonomy ] );
				wp_set_object_terms( $product->get_id(), array(), $taxonomy );
				continue;
			}

			$value = $values[ $taxonomy ];
			$term  = get_term_by( 'name', $value, $taxonomy );
			if ( ! $term ) {
				$created = wp_insert_term( $value, $taxonomy );
				if ( is_wp_error( $created ) ) {
					$this->warn( sprintf( '#%d %s: %s', $product->get_id(), $taxonomy, $created->get_error_message() ) );
					continue;
				}
				$term_id = (int) $created['term_id'];
			} else {
				$term_id = (int) $term->term_id;
			}

			wp_set_object_terms( $product->get_id(), array( $term_id ), $taxonomy );

			$attribute = new WC_Product_Attribute();
			$attribute->set_id( wc_attribute_taxonomy_id_by_name( str_replace( 'pa_', '', $taxonomy ) ) );
			$attribute->set_name( $taxonomy );
			$attribute->set_options( array( $term_id ) );
			$attribute->set_visible( true );
			$attribute->set_variation( false );
			$attributes[ $taxonomy ] = $attribute;
		}

		$product->set_attributes( $attributes );
		$product->save();
	}

	/**
	 * Attribute taxonomies are cached hard, so both layers get cleared.
	 */
	private function flush_attribute_cache(): void {
		delete_transient( 'wc_attribute_taxonomies' );
		if ( class_exists( 'WC_Cache_Helper' ) ) {
			WC_Cache_Helper::invalidate_cache_group( 'woocommerce-attributes' );
		}
	}

	/**
	 * Puts every reduced watch in the Sale category and takes the rest out.
	 *
	 * The shop sets its sale prices in WooCommerce, but the Sale page is a
	 * category, so the two drift apart the moment a price changes. This walks
	 * the whole catalogue and makes them agree again.
	 *
	 * @param array $args  Unused.
	 * @param array $assoc Takes dry-run.
	 */
	public function sync_sale( array $args, array $assoc ): void {
		$dry = ! empty( $assoc['dry-run'] );

		$term = get_term_by( 'slug', 'sale', 'product_cat' );
		if ( ! $term || is_wp_error( $term ) ) {
			$this->warn( __( 'De categorie Sale bestaat niet.', 'dkm' ) );
			return;
		}

		$ids = get_posts(
			array(
				'post_type'      => 'product',
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'fields'         => 'ids',
			)
		);

		$erbij = 0;
		$eraf  = 0;
		$gelijk = 0;

		foreach ( $ids as $id ) {
			$product = wc_get_product( $id );
			if ( ! $product ) {
				continue;
			}

			$korting = $product->is_on_sale( 'edit' );
			$erin    = has_term( $term->term_id, 'product_cat', $id );

			if ( $korting === $erin ) {
				++$gelijk;
				continue;
			}

			if ( $korting ) {
				++$erbij;
			} else {
				++$eraf;
			}

			if ( ! $dry ) {
				wp_set_object_terms( $id, array( (int) $term->term_id ), 'product_cat', $korting );

				if ( ! $korting ) {
					$houden = wp_get_object_terms( $id, 'product_cat', array( 'fields' => 'ids' ) );
					$houden = array_values( array_diff( (array) $houden, array( (int) $term->term_id ) ) );
					wp_set_object_terms( $id, $houden, 'product_cat' );
				}
			}
		}

		$this->log(
			sprintf(
				/* translators: 1: number added, 2: number removed, 3: number left alone. */
				__( 'erbij: %1$d, eraf: %2$d, ongemoeid gelaten: %3$d', 'dkm' ),
				$erbij,
				$eraf,
				$gelijk
			)
		);
		$this->ok( $dry ? __( 'Proefdraai klaar.', 'dkm' ) : __( 'De categorie Sale klopt weer.', 'dkm' ) );
	}

	/* ------------------------------------------------------------------ */
	/* Output. On the command line this goes to the terminal, in wp-admin  */
	/* it is collected so the page can show it.                            */
	/* ------------------------------------------------------------------ */

	/**
	 * Collected output lines, used when there is no command line.
	 */
	private array $lines = array();

	/**
	 * Hands back everything that was reported since the last reset.
	 *
	 * @return array<int, array{0: string, 1: string}> Pairs of level and text.
	 */
	public function take_lines(): array {
		$lines       = $this->lines;
		$this->lines = array();
		return $lines;
	}

	/**
	 * True when WP-CLI is driving this.
	 */
	private function on_cli(): bool {
		return defined( 'WP_CLI' ) && WP_CLI;
	}

	/**
	 * Reports a plain line.
	 */
	private function log( string $text ): void {
		if ( $this->on_cli() ) {
			WP_CLI::log( $text );
			return;
		}
		$this->lines[] = array( 'log', $text );
	}

	/**
	 * Reports something that deserves attention but is not fatal.
	 */
	private function warn( string $text ): void {
		if ( $this->on_cli() ) {
			WP_CLI::warning( $text );
			return;
		}
		$this->lines[] = array( 'warn', $text );
	}

	/**
	 * Reports that a job finished.
	 */
	private function ok( string $text ): void {
		if ( $this->on_cli() ) {
			WP_CLI::success( $text );
			return;
		}
		$this->lines[] = array( 'ok', $text );
	}

	/**
	 * Stops the job. WP-CLI ends the process here, in wp-admin the exception
	 * is caught by the page so the rest of the screen still renders.
	 *
	 * @throws RuntimeException When there is no command line to end.
	 */
	private function fail( string $text ): void {
		if ( $this->on_cli() ) {
			WP_CLI::error( $text );
		}
		$this->lines[] = array( 'fail', $text );
		throw new RuntimeException( $text );
	}
}


if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'dkm', 'DKM_CLI' );
}
