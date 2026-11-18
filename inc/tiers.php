<?php
/**
 * Quality tiers
 *
 * Keyed by the product_cat slug that already exists in the shop, so the tier of
 * a product is read from its category and never from a second taxonomy.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

/**
 * Every tier, in the order the shop presents them.
 *
 * @return array<string,array<string,mixed>>
 */
function dkm_tiers(): array {
	static $tiers = null;
	if ( null !== $tiers ) {
		return $tiers;
	}

	$tiers = dkm_tiers_overschrijven( dkm_tiers_base() );

	return $tiers;
}

/**
 * The three levels exactly as this file writes them, with nothing laid over.
 *
 * The editing screen reads this one, because the list that follows asks the
 * screen for its values and the two would otherwise call each other forever.
 *
 * @return array
 */
function dkm_tiers_base(): array {
	static $tiers = null;
	if ( null !== $tiers ) {
		return $tiers;
	}

	$tiers = array(
		'superclone'    => array(
			'key'      => 'superclone',
			'name'     => __( 'Superclone', 'dkm' ),
			'short'    => __( 'Superclone', 'dkm' ),
			'sub'      => __( 'Niet van echt te onderscheiden', 'dkm' ),
			'icon'     => 'crown',
			'from'     => 900,
			'claim'    => __( 'De absolute topklasse. 1:1 nagemaakt met 904L staal, saffierglas en een Zwitsers uurwerk, tot en met het gewicht en de gravures.', 'dkm' ),
			'who'      => __( 'Voor wie geen enkel compromis wil en het verschil met het origineel niet wil zien, ook niet van dichtbij.', 'dkm' ),
			'feats'    => array(
				__( '1:1 replica, 904L staal', 'dkm' ),
				__( 'Saffierglas', 'dkm' ),
				__( 'Zwitsers clone-uurwerk', 'dkm' ),
				__( 'Gewicht als origineel', 'dkm' ),
			),
			'bullets'  => array(
				__( '1:1 replica met premium materialen (904L staal, saffierglas)', 'dkm' ),
				__( 'Zwitsers of hoogwaardig clone-uurwerk (28.800 vph, vloeiende secondewijzer)', 'dkm' ),
				__( 'Perfecte gravures, logo’s en gewicht zoals origineel', 'dkm' ),
				__( 'Werkende complicaties (GMT, chronograaf, datum)', 'dkm' ),
				__( 'Goede waterdichtheid (meestal 50m tot 100m)', 'dkm' ),
			),
			'specs'    => array(
				'glass'    => __( 'Saffierglas', 'dkm' ),
				'movement' => __( 'Zwitsers clone-uurwerk, 28.800 vph', 'dkm' ),
				'steel'    => __( '904L Oystersteel', 'dkm' ),
				'water'    => __( '50m tot 100m', 'dkm' ),
			),
			'explain'  => array(
				array( 'gem', __( 'Saffierglas', 'dkm' ), __( 'Nagenoeg onkrasbaar glas met AR-coating, exact zoals het origineel.', 'dkm' ) ),
				array( 'swiss', __( '28.800 vph uurwerk', 'dkm' ), __( 'Een vloeiende secondewijzer in plaats van een tikkende. Dit is het eerste waar kenners naar kijken.', 'dkm' ) ),
				array( 'weight', __( 'Correct gewicht', 'dkm' ), __( '904L staal weegt zwaarder dan 316L. Je voelt direct dat het klopt zodra je hem omdoet.', 'dkm' ) ),
			),
			'template' => 'superclone',
		),
		'bp-swiss-made' => array(
			'key'      => 'bp',
			'name'     => __( 'BP Swiss Made', 'dkm' ),
			'short'    => __( 'BP', 'dkm' ),
			'sub'      => __( 'De slimste keuze', 'dkm' ),
			'icon'     => 'swiss',
			'from'     => 370,
			'claim'    => __( 'Zwitsers of ETA-uurwerk in 316L staal met saffierglas. De beste verhouding tussen prijs en kwaliteit die wij voeren.', 'dkm' ),
			'who'      => __( 'Voor wie een serieus horloge wil met een echt uurwerk, zonder de prijs van een superclone te betalen.', 'dkm' ),
			'feats'    => array(
				__( '316L staal + saffierglas', 'dkm' ),
				__( 'Zwitsers of ETA-uurwerk', 'dkm' ),
				__( 'Nauwkeurige gravures', 'dkm' ),
				__( 'Werkende complicaties', 'dkm' ),
			),
			'bullets'  => array(
				__( 'Mooie afwerking met roestvrij staal (316L) en saffierglas', 'dkm' ),
				__( 'Betrouwbaar Zwitsers of Aziatisch ETA-uurwerk', 'dkm' ),
				__( 'Nauwkeurige gravures en logo’s', 'dkm' ),
				__( 'Werkende complicaties en soepele secondewijzer', 'dkm' ),
				__( 'Betaalbaarder dan een superclone', 'dkm' ),
			),
			'specs'    => array(
				'glass'    => __( 'Saffierglas', 'dkm' ),
				'movement' => __( 'Zwitsers of ETA automatisch', 'dkm' ),
				'steel'    => __( '316L roestvrij staal', 'dkm' ),
				'water'    => __( '30m tot 50m', 'dkm' ),
			),
			'explain'  => array(
				array( 'gem', __( 'Saffierglas', 'dkm' ), __( 'Ook op dit niveau krijg je saffierglas, dus geen krassen van dagelijks dragen.', 'dkm' ) ),
				array( 'swiss', __( 'Betrouwbaar automaat', 'dkm' ), __( 'Een ETA-gebaseerd automatisch uurwerk dat jarenlang meegaat en soepel loopt.', 'dkm' ) ),
				array( 'engrave', __( 'Nauwkeurige gravures', 'dkm' ), __( 'Logo’s en graveringen zijn scherp uitgevoerd, al net niet op superclone-niveau.', 'dkm' ) ),
			),
			'template' => 'bp',
		),
		'aaa'           => array(
			'key'      => 'aaa',
			'name'     => __( 'AAA+', 'dkm' ),
			'short'    => __( 'AAA+', 'dkm' ),
			'sub'      => __( 'Betaalbaar instapmodel', 'dkm' ),
			'icon'     => 'spark',
			'from'     => 150,
			'claim'    => __( 'Een verzorgd horloge voor een scherpe prijs. Basis automatisch uurwerk, lichtgewicht en comfortabel voor dagelijks gebruik.', 'dkm' ),
			'who'      => __( 'Voor wie voor het eerst zo’n horloge koopt, of iets zoekt om zorgeloos elke dag te dragen.', 'dkm' ),
			'feats'    => array(
				__( 'Scherpe prijs', 'dkm' ),
				__( 'Basis automaat', 'dkm' ),
				__( 'Lichtgewicht', 'dkm' ),
				__( 'Dagelijks dragen', 'dkm' ),
			),
			'bullets'  => array(
				__( 'Mooie uitstraling voor een lage prijs', 'dkm' ),
				__( 'Basis automatisch uurwerk', 'dkm' ),
				__( 'Goede imitaties van bekende merken', 'dkm' ),
				__( 'Lichtgewicht en comfortabel', 'dkm' ),
				__( 'Perfect voor dagelijks gebruik', 'dkm' ),
			),
			'specs'    => array(
				'glass'    => __( 'Krasbestendig mineraalglas', 'dkm' ),
				'movement' => __( 'Basis automatisch uurwerk', 'dkm' ),
				'steel'    => __( 'Roestvrij staal', 'dkm' ),
				'water'    => __( '30m', 'dkm' ),
			),
			'explain'  => array(
				array( 'spark', __( 'Scherpe prijs', 'dkm' ), __( 'Vanaf €150 draag je een horloge dat er van een meter afstand uitstekend uitziet.', 'dkm' ) ),
				array( 'refresh', __( 'Basis automaat', 'dkm' ), __( 'Een automatisch uurwerk zonder batterij, dat oploopt door de beweging van je pols.', 'dkm' ) ),
				array( 'watch', __( 'Licht en comfortabel', 'dkm' ), __( 'Lichter dan een superclone, waardoor je hem de hele dag vergeet te dragen.', 'dkm' ) ),
			),
			'template' => 'aaa',
		),
	);

	return $tiers;
}

/**
 * Lays whatever was typed in the panel over the list above.
 *
 * Only a field that was actually filled in wins, so an untouched shop reads
 * exactly as it was written here.
 *
 * @param array $tiers The list as this file defines it.
 * @return array
 */
function dkm_tiers_overschrijven( array $tiers ): array {
	foreach ( $tiers as $slug => $tier ) {
		foreach ( array( 'name', 'short', 'sub', 'claim', 'who' ) as $sleutel ) {
			$waarde = dkm_opt( 'tier_' . $slug . '_' . $sleutel, '' );
			if ( '' !== $waarde ) {
				$tiers[ $slug ][ $sleutel ] = $waarde;
			}
		}

		$vanaf = (int) dkm_opt( 'tier_' . $slug . '_from', 0 );
		if ( $vanaf > 0 ) {
			$tiers[ $slug ]['from'] = $vanaf;
		}

		$punten = dkm_opt( 'tier_' . $slug . '_usps', array() );
		if ( is_array( $punten ) && $punten ) {
			$tiers[ $slug ]['explain'] = array_map(
				static fn( $rij ) => array( 'check', $rij['titel'] ?? '', $rij['tekst'] ?? '' ),
				$punten
			);
		}
	}

	return $tiers;
}

/**
 * The tier a product belongs to, read from its categories.
 *
 * @param int|WC_Product|null $product Product or id.
 * @return array<string,mixed>|null Null for boxes, eyewear and second hand.
 */
function dkm_product_tier( $product = null ): ?array {
	$product = $product instanceof WC_Product ? $product : wc_get_product( $product );
	if ( ! $product ) {
		return null;
	}

	$slugs = wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'slugs' ) );
	if ( is_wp_error( $slugs ) ) {
		return null;
	}

	foreach ( dkm_tiers() as $slug => $tier ) {
		if ( in_array( $slug, $slugs, true ) ) {
			return $tier;
		}
	}

	return null;
}

/**
 * Looks a tier up by either its category slug or its short key.
 */
function dkm_get_tier( string $slug_or_key ): ?array {
	$tiers = dkm_tiers();
	if ( isset( $tiers[ $slug_or_key ] ) ) {
		return $tiers[ $slug_or_key ];
	}
	foreach ( $tiers as $tier ) {
		if ( $tier['key'] === $slug_or_key ) {
			return $tier;
		}
	}
	return null;
}

/**
 * The inline custom properties that colour a tier-scoped block.
 */
function dkm_tier_vars( ?array $tier ): string {
	if ( ! $tier ) {
		return '--qc:var(--ink2);--qbg:var(--surf2);--qs:var(--sh-card)';
	}
	$k = $tier['key'];
	return sprintf(
		'--qc:var(--tier-%1$s);--qbg:var(--tier-%1$s-bg);--qs:var(--tier-%1$s-shadow)',
		'bp' === $k ? 'bp' : $k
	);
}

if ( ! function_exists( 'dkm_short_title' ) ) {
	/**
	 * Strips the tier suffix a product title carries, so headings stay short.
	 *
	 * Guarded because DKM Core defines the same helper and loads first.
	 */
	function dkm_short_title( string $name ): string {
		return trim( (string) preg_replace( '/\s*\((SUPERCLONE|BP SWISS MADE|AAA\+)\)\s*$/i', '', $name ) );
	}
}
