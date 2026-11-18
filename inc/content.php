<?php
/**
 * Editorial content that is the same on every shop: the comparison matrix, the
 * USP strips and the delivery promises. Anything the client should be able to
 * change per category or per page lives in ACF instead.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

/**
 * Comparison matrix. Each row is label, verdict per tier, and the explanation
 * behind the i.
 *
 * Verdicts: y present, p partly, n not applicable.
 *
 * @return array<int,array{0:string,1:array<string,string>,2:string}>
 */
function dkm_matrix(): array {
	return array(
		array(
			__( 'Automatisch uurwerk', 'dkm' ),
			array( 'superclone' => 'y', 'bp' => 'y', 'aaa' => 'y' ),
			__( 'Alle drie de niveaus lopen op een automatisch uurwerk, dus zonder batterij.', 'dkm' ),
		),
		array(
			__( 'Zwitsers of ETA-kaliber', 'dkm' ),
			array( 'superclone' => 'y', 'bp' => 'y', 'aaa' => 'n' ),
			__( 'Superclone en BP gebruiken een Zwitsers of ETA-gebaseerd kaliber. AAA+ heeft een basis automaat.', 'dkm' ),
		),
		array(
			__( 'Saffierglas', 'dkm' ),
			array( 'superclone' => 'y', 'bp' => 'y', 'aaa' => 'n' ),
			__( 'Saffierglas is nagenoeg onkrasbaar. AAA+ heeft krasbestendig mineraalglas.', 'dkm' ),
		),
		array(
			__( '904L Oystersteel', 'dkm' ),
			array( 'superclone' => 'y', 'bp' => 'p', 'aaa' => 'n' ),
			__( 'Superclone gebruikt 904L, hetzelfde staal als het origineel. BP gebruikt 316L, AAA+ standaard roestvrij staal.', 'dkm' ),
		),
		array(
			__( 'Correct gewicht en balans', 'dkm' ),
			array( 'superclone' => 'y', 'bp' => 'p', 'aaa' => 'n' ),
			__( 'Een superclone weegt exact hetzelfde als het origineel. Dat voel je direct om je pols.', 'dkm' ),
		),
		array(
			__( 'Perfecte gravures en logo’s', 'dkm' ),
			array( 'superclone' => 'y', 'bp' => 'p', 'aaa' => 'p' ),
			__( 'Bij superclone zijn de graveringen identiek. BP en AAA+ komen in de buurt maar niet exact.', 'dkm' ),
		),
		array(
			__( 'Werkende complicaties', 'dkm' ),
			array( 'superclone' => 'y', 'bp' => 'y', 'aaa' => 'p' ),
			__( 'GMT, chronograaf en datum werken bij superclone en BP volledig. Bij AAA+ deels decoratief.', 'dkm' ),
		),
		array(
			__( 'Waterdicht', 'dkm' ),
			array( 'superclone' => 'y', 'bp' => 'y', 'aaa' => 'p' ),
			__( 'Superclone 50m tot 100m, BP 30m tot 50m, AAA+ 30m. Allemaal bestand tegen regen en spatten.', 'dkm' ),
		),
		array(
			__( 'Glow in the dark', 'dkm' ),
			array( 'superclone' => 'y', 'bp' => 'y', 'aaa' => 'y' ),
			__( 'Alle horloges hebben oplichtende wijzers, zodat je ook in het donker de tijd afleest.', 'dkm' ),
		),
		array(
			__( 'Niet van echt te onderscheiden', 'dkm' ),
			array( 'superclone' => 'y', 'bp' => 'p', 'aaa' => 'n' ),
			__( 'Alleen de superclone doorstaat inspectie van dichtbij door iemand die weet waar hij op moet letten.', 'dkm' ),
		),
	);
}

/**
 * Maps a matrix verdict onto its icon.
 */
function dkm_mark( string $verdict ): string {
	$icons = array(
		'y' => 'check',
		'p' => 'minus',
		'n' => 'close',
	);
	if ( ! isset( $icons[ $verdict ] ) ) {
		return '';
	}
	return sprintf(
		'<span class="mk mk--%s">%s</span>',
		esc_attr( $verdict ),
		dkm_icon( $icons[ $verdict ] )
	);
}

/**
 * The four USPs in the marquee under the hero and in the footer.
 *
 * @return array<int,array{0:string,1:string,2:string}>
 */
function dkm_usps(): array {
	$eigen = dkm_opt( 'usps', array() );
	if ( is_array( $eigen ) && $eigen ) {
		return array_map(
			static fn( $rij ) => array( $rij['icoon'] ?? 'check', $rij['titel'] ?? '', $rij['sub'] ?? '' ),
			$eigen
		);
	}


	return dkm_usps_base();
}

/**
 * The same list as this file writes it, with nothing laid over.
 *
 * The editing screen reads this one: the list above asks the screen for
 * its values, and the two would otherwise call each other forever.
 *
 * @return array
 */
function dkm_usps_base(): array {
	return array(
		array( 'truck', __( 'Vandaag besteld', 'dkm' ), __( 'Morgen in huis', 'dkm' ) ),
		array( 'shield', __( 'Kwaliteitsgarantie', 'dkm' ), __( 'Op elk horloge', 'dkm' ) ),
		array( 'chat', __( 'Persoonlijk advies', 'dkm' ), __( 'Direct via WhatsApp', 'dkm' ) ),
		array( 'box', __( 'Discreet verzonden', 'dkm' ), __( 'Neutrale verpakking', 'dkm' ) ),
	);
}

/**
 * The ticker line above the header.
 *
 * @return string[]
 */
function dkm_ticker(): array {
	$eigen = dkm_opt( 'ticker', array() );
	if ( is_array( $eigen ) && $eigen ) {
		return array_values(
			array_filter( array_map( static fn( $rij ) => (string) ( $rij['tekst'] ?? '' ), $eigen ) )
		);
	}


	return dkm_ticker_base();
}

/**
 * The same list as this file writes it, with nothing laid over.
 *
 * The editing screen reads this one: the list above asks the screen for
 * its values, and the two would otherwise call each other forever.
 *
 * @return array
 */
function dkm_ticker_base(): array {
	return array(
		__( 'Gratis verzending vanaf €150', 'dkm' ),
		__( 'Vandaag besteld, morgen in huis', 'dkm' ),
		__( '1200+ horloges geleverd', 'dkm' ),
		__( '14 dagen omruilgarantie', 'dkm' ),
		__( 'Persoonlijk advies via WhatsApp', 'dkm' ),
		__( 'Discreet verpakt verzonden', 'dkm' ),
	);
}

/**
 * The delivery carousel on the product page.
 *
 * @return array<int,array{0:string,1:string,2:string}>
 */
function dkm_delivery(): array {
	$eigen = dkm_opt( 'delivery', array() );
	if ( is_array( $eigen ) && $eigen ) {
		return array_map(
			static fn( $rij ) => array( $rij['icoon'] ?? 'check', $rij['titel'] ?? '', $rij['tekst'] ?? '' ),
			$eigen
		);
	}


	return dkm_delivery_base();
}

/**
 * The same list as this file writes it, with nothing laid over.
 *
 * The editing screen reads this one: the list above asks the screen for
 * its values, and the two would otherwise call each other forever.
 *
 * @return array
 */
function dkm_delivery_base(): array {
	return array(
		array( 'box', __( 'In onze eigen verpakking', 'dkm' ), __( 'Elk horloge gaat beschermd en discreet de deur uit. Aan de buitenkant zie je niet wat erin zit.', 'dkm' ) ),
		array( 'eye', __( 'Gecontroleerd voor verzending', 'dkm' ), __( 'We lopen elk horloge na op de wijzerplaat, het glas, de sluiting en de loop van het uurwerk.', 'dkm' ) ),
		array( 'truck', __( 'Vandaag besteld, morgen in huis', 'dkm' ), __( 'Bestel je op werkdagen voor 17:00, dan ligt het de volgende dag op de mat.', 'dkm' ) ),
		array( 'chat', __( 'Vragen onderweg', 'dkm' ), __( 'Twijfel je over de maat of de kleur? Stuur een bericht, we sturen extra foto’s of een video.', 'dkm' ) ),
		array( 'refresh', __( '14 dagen omruilen', 'dkm' ), __( 'Zit hij toch niet lekker? Dan ruilen we hem binnen 14 dagen om voor een ander model.', 'dkm' ) ),
	);
}

/**
 * Premium features that every watch has, whatever the tier.
 *
 * @return array<int,array{0:string,1:string,2:string}>
 */
function dkm_premium_features(): array {
	$eigen = dkm_opt( 'premium', array() );
	if ( is_array( $eigen ) && $eigen ) {
		return array_map(
			static fn( $rij ) => array( $rij['icoon'] ?? 'check', $rij['titel'] ?? '', $rij['tekst'] ?? '' ),
			$eigen
		);
	}


	return dkm_premium_features_base();
}

/**
 * The same list as this file writes it, with nothing laid over.
 *
 * The editing screen reads this one: the list above asks the screen for
 * its values, and the two would otherwise call each other forever.
 *
 * @return array
 */
function dkm_premium_features_base(): array {
	return array(
		array( 'glow', __( 'Glow in the dark', 'dkm' ), __( 'Oplichtende wijzers en indexen, zodat je ook in het donker de tijd afleest.', 'dkm' ) ),
		array( 'drop', __( 'Waterdicht', 'dkm' ), __( 'Bestand tegen regen en spatten. Hoeveel meter precies hangt af van de kwaliteit.', 'dkm' ) ),
		array( 'eye', __( 'Gecontroleerd voor verzending', 'dkm' ), __( 'Elk horloge gaat door onze eigen controle voordat het de deur uit gaat.', 'dkm' ) ),
	);
}

/**
 * The default hotspots for the anatomy section, used when the page has no
 * positions of its own yet. Positions are per image and editable in ACF.
 *
 * @return array<int,array{title:string,text:string,top:int,left:int}>
 */
function dkm_anatomy_defaults(): array {
	return array(
		array(
			'title' => __( 'Wijzerplaat en index', 'dkm' ),
			'text'  => __( 'De print moet haarscherp zijn en de indexen moeten perfect recht staan. Bij een superclone is er geen enkele afwijking te zien.', 'dkm' ),
			'top'   => 22,
			'left'  => 46,
		),
		array(
			'title' => __( 'Secondewijzer', 'dkm' ),
			'text'  => __( 'Een echt automatisch uurwerk loopt vloeiend, niet in tikjes. Dat is het snelste wat je zelf kunt controleren.', 'dkm' ),
			'top'   => 44,
			'left'  => 30,
		),
		array(
			'title' => __( 'Bezel en gravering', 'dkm' ),
			'text'  => __( 'De randen moeten strak zijn en de gravering even diep over de hele cirkel. Hier zie je het verschil tussen de niveaus.', 'dkm' ),
			'top'   => 36,
			'left'  => 70,
		),
		array(
			'title' => __( 'Kroon en schouders', 'dkm' ),
			'text'  => __( 'De kroon moet soepel draaien en de kroonbescherming moet symmetrisch zijn. Bij ons draait hij zoals het hoort.', 'dkm' ),
			'top'   => 58,
			'left'  => 80,
		),
		array(
			'title' => __( 'Band en sluiting', 'dkm' ),
			'text'  => __( 'Een goede band rammelt niet en de sluiting klikt met één duidelijke klik dicht. Dat voel je meteen.', 'dkm' ),
			'top'   => 74,
			'left'  => 44,
		),
	);
}

/**
 * Default USPs for a collection banner, per category slug. ACF overrides these
 * per category; this is what shows before the client has filled anything in.
 *
 * @return array<string,array{eyebrow:string,title:string,usps:array<int,array{0:string,1:string}>}>
 */
function dkm_banner_defaults(): array {
	return array(
		'_default'      => array(
			'eyebrow' => __( 'Alle horloges', 'dkm' ),
			'title'   => __( 'Van instapmodel tot superclone, allemaal uit voorraad', 'dkm' ),
			'usps'    => array(
				array( 'truck', __( 'Vandaag besteld, morgen in huis', 'dkm' ) ),
				array( 'shield', __( 'Kwaliteitscontrole voor verzending', 'dkm' ) ),
				array( 'refresh', __( '14 dagen omruilgarantie', 'dkm' ) ),
				array( 'chat', __( 'Persoonlijk advies via WhatsApp', 'dkm' ) ),
			),
		),
		'superclone'    => array(
			'eyebrow' => __( 'Superclone', 'dkm' ),
			'title'   => __( 'De absolute topklasse, tot in het gewicht nagemaakt', 'dkm' ),
			'usps'    => array(
				array( 'gem', __( 'Saffierglas met AR-coating', 'dkm' ) ),
				array( 'swiss', __( 'Zwitsers uurwerk, 28.800 vph', 'dkm' ) ),
				array( 'weight', __( '904L staal, correct gewicht', 'dkm' ) ),
				array( 'eye', __( 'Niet van echt te onderscheiden', 'dkm' ) ),
			),
		),
		'bp-swiss-made' => array(
			'eyebrow' => __( 'BP Swiss Made', 'dkm' ),
			'title'   => __( 'De slimste keuze van het moment', 'dkm' ),
			'usps'    => array(
				array( 'swiss', __( 'Zwitsers of ETA-uurwerk', 'dkm' ) ),
				array( 'gem', __( 'Saffierglas', 'dkm' ) ),
				array( 'shield', __( '316L roestvrij staal', 'dkm' ) ),
				array( 'star', __( 'Meest gekozen door klanten', 'dkm' ) ),
			),
		),
		'aaa'           => array(
			'eyebrow' => __( 'AAA+', 'dkm' ),
			'title'   => __( 'Betaalbaar beginnen zonder in te leveren op uitstraling', 'dkm' ),
			'usps'    => array(
				array( 'tag', __( 'Al vanaf €150', 'dkm' ) ),
				array( 'drop', __( '30m waterdicht', 'dkm' ) ),
				array( 'shield', __( 'Krasbestendig glas', 'dkm' ) ),
				array( 'watch', __( 'Licht en comfortabel', 'dkm' ) ),
			),
		),
		'dames'         => array(
			'eyebrow' => __( 'Dames', 'dkm' ),
			'title'   => __( 'Verfijnd, in 31 en 36 millimeter', 'dkm' ),
			'usps'    => array(
				array( 'watch', __( 'Kleinere kastmaten', 'dkm' ) ),
				array( 'spark', __( 'Diamantwijzerplaten', 'dkm' ) ),
				array( 'crown', __( 'Rosé, goud en staal', 'dkm' ) ),
				array( 'truck', __( 'Gratis verzending', 'dkm' ) ),
			),
		),
		'sale'          => array(
			'eyebrow' => __( 'Sale', 'dkm' ),
			'title'   => __( 'Tijdelijke actieprijzen, zolang de voorraad strekt', 'dkm' ),
			'usps'    => array(
				array( 'tag', __( 'Tijdelijke actieprijzen', 'dkm' ) ),
				array( 'clock', __( 'Op = op, geen nalevering', 'dkm' ) ),
				array( 'shield', __( 'Dezelfde garantie', 'dkm' ) ),
				array( 'truck', __( 'Vandaag besteld, morgen in huis', 'dkm' ) ),
			),
		),
		'rolex-dozen'   => array(
			'eyebrow' => __( 'Rolex dozen', 'dkm' ),
			'title'   => __( 'Maak je horloge compleet met de juiste doos', 'dkm' ),
			'usps'    => array(
				array( 'box', __( 'Originele presentatiedoos', 'dkm' ) ),
				array( 'crown', __( 'Standaard of premium set', 'dkm' ) ),
				array( 'spark', __( 'Perfect als cadeau', 'dkm' ) ),
				array( 'tag', __( 'Los of bij je horloge', 'dkm' ) ),
			),
		),
		'brillen'       => array(
			'eyebrow' => __( 'Brillen', 'dkm' ),
			'title'   => __( 'Dezelfde standaard, ander product', 'dkm' ),
			'usps'    => array(
				array( 'eye', __( 'Dita, Cartier en John Dalia', 'dkm' ) ),
				array( 'shield', __( 'UV-bescherming', 'dkm' ) ),
				array( 'box', __( 'Inclusief etui', 'dkm' ) ),
				array( 'truck', __( 'Gratis verzending', 'dkm' ) ),
			),
		),
		'2e-hands'      => array(
			'eyebrow' => __( '2e hands', 'dkm' ),
			'title'   => __( 'Zorgvuldig gecontroleerde tweedehands horloges', 'dkm' ),
			'usps'    => array(
				array( 'refresh', __( 'Volledig nagekeken', 'dkm' ) ),
				array( 'shield', __( 'Met garantie', 'dkm' ) ),
				array( 'tag', __( 'Scherpere prijs', 'dkm' ) ),
				array( 'chat', __( 'Vraag naar de voorraad', 'dkm' ) ),
			),
		),
	);
}
