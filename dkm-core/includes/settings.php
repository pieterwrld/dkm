<?php
/**
 * One place for everything the shop writes and shows
 *
 * Texts and pictures used to sit in four different places: theme settings,
 * fields on the front page, fields on each post type, and arrays in the theme
 * files. This holds all of it in a single stored value, and dkm_opt() reads
 * from it with the theme's own text as the fallback, so nothing changes on the
 * page until someone edits it.
 *
 * @package DKM_Core
 */

defined( 'ABSPATH' ) || exit;

const DKM_SETTINGS_OPTION = 'dkm_settings';

/**
 * Everything that can be edited, in the order the screen shows it.
 *
 * Each field is: type, label, and whatever that type needs. The screen is
 * drawn from this, so adding a field here is enough to make it editable.
 *
 * @return array<string, array{titel: string, uitleg?: string, groepen: array}>
 */
function dkm_settings_schema(): array {
	return array(
		'home'       => array(
			'titel'   => __( 'Homepage', 'dkm' ),
			'uitleg'  => __( 'De balk bovenaan, de banner, de lopende regels en de slotsectie.', 'dkm' ),
			'groepen' => array(
				array(
					'titel'  => __( 'Balk bovenaan', 'dkm' ),
					'velden' => array(
						'bf_enabled' => array( 'type' => 'bool', 'label' => __( 'Actie loopt', 'dkm' ), 'uitleg' => __( 'Zet de rode balk en de banner aan of uit.', 'dkm' ) ),
						'bf_label'   => array( 'type' => 'text', 'label' => __( 'Naam van de actie', 'dkm' ) ),
						'bf_text'    => array( 'type' => 'text', 'label' => __( 'Tekst in de balk', 'dkm' ) ),
						'bf_until'   => array( 'type' => 'date', 'label' => __( 'Loopt tot en met', 'dkm' ) ),
					),
				),
				array(
					'titel'  => __( 'Banner', 'dkm' ),
					'velden' => array(
						'bf_banner_mobile'  => array( 'type' => 'image', 'label' => __( 'Beeld voor de telefoon', 'dkm' ), 'uitleg' => __( 'Staand. Houd onderin ruimte vrij voor de knop.', 'dkm' ) ),
						'bf_banner_desktop' => array( 'type' => 'image', 'label' => __( 'Beeld voor het grote scherm', 'dkm' ), 'uitleg' => __( 'Liggend, met dezelfde vrije strook onderin.', 'dkm' ) ),
						'bf_banner_cta'     => array( 'type' => 'text', 'label' => __( 'Tekst op de knop', 'dkm' ) ),
						'bf_banner_link'    => array( 'type' => 'text', 'label' => __( 'Waar de knop heen gaat', 'dkm' ), 'uitleg' => __( 'Leeg laten gaat naar de sale-categorie.', 'dkm' ) ),
					),
				),
				array(
					'titel'  => __( 'Cijfers', 'dkm' ),
					'velden' => array(
						'stat_delivered' => array( 'type' => 'text', 'label' => __( 'Horloges geleverd', 'dkm' ) ),
						'stat_reviews'   => array( 'type' => 'text', 'label' => __( 'Aantal reviews', 'dkm' ) ),
						'stat_rating'    => array( 'type' => 'text', 'label' => __( 'Gemiddeld cijfer', 'dkm' ) ),
						'stat_recommend' => array( 'type' => 'text', 'label' => __( 'Percentage dat aanbeveelt', 'dkm' ) ),
					),
				),
				array(
					'titel'  => __( 'Lopende regel', 'dkm' ),
					'velden' => array(
						'ticker' => array(
							'type'   => 'repeater',
							'label'  => __( 'Zinnen die langsschuiven', 'dkm' ),
							'velden' => array(
								'tekst' => array( 'type' => 'text', 'label' => __( 'Zin', 'dkm' ) ),
							),
						),
					),
				),
				array(
					'titel'  => __( 'Strook onder de banner', 'dkm' ),
					'velden' => array(
						'usps' => array(
							'type'   => 'repeater',
							'label'  => __( 'Punten', 'dkm' ),
							'velden' => array(
								'icoon' => array( 'type' => 'icon', 'label' => __( 'Icoon', 'dkm' ) ),
								'titel' => array( 'type' => 'text', 'label' => __( 'Kop', 'dkm' ) ),
								'sub'   => array( 'type' => 'text', 'label' => __( 'Regel eronder', 'dkm' ) ),
							),
						),
					),
				),
				array(
					'titel'  => __( 'Slotsectie', 'dkm' ),
					'velden' => array(
						'closing_image'   => array( 'type' => 'image', 'label' => __( 'Foto', 'dkm' ), 'uitleg' => __( 'Groeit mee terwijl de bezoeker scrolt.', 'dkm' ) ),
						'closing_eyebrow' => array( 'type' => 'text', 'label' => __( 'Bovenkopje', 'dkm' ) ),
						'closing_title'   => array( 'type' => 'text', 'label' => __( 'Kop', 'dkm' ) ),
						'closing_accent'  => array( 'type' => 'text', 'label' => __( 'Schuine staart van de kop', 'dkm' ) ),
						'closing_cta'     => array( 'type' => 'text', 'label' => __( 'Tekst op de knop', 'dkm' ) ),
						'closing_usps'    => array(
							'type'   => 'repeater',
							'label'  => __( 'Vier punten', 'dkm' ),
							'velden' => array(
								'icoon' => array( 'type' => 'icon', 'label' => __( 'Icoon', 'dkm' ) ),
								'titel' => array( 'type' => 'text', 'label' => __( 'Kop', 'dkm' ) ),
								'tekst' => array( 'type' => 'text', 'label' => __( 'Regel eronder', 'dkm' ) ),
							),
						),
					),
				),
			),
		),
		'kwaliteiten' => array(
			'titel'   => __( 'Kwaliteiten', 'dkm' ),
			'uitleg'  => __( 'Per niveau: de naam, de prijs, de foto en wat er over verteld wordt.', 'dkm' ),
			'groepen' => array(),
		),
		'secties'    => array(
			'titel'   => __( 'Secties', 'dkm' ),
			'uitleg'  => __( 'De koppen boven de blokken op de homepage en de productpagina.', 'dkm' ),
			'groepen' => array(
				array(
					'titel'  => __( 'Video\'s', 'dkm' ),
					'velden' => array(
						'ugc_eyebrow' => array( 'type' => 'text', 'label' => __( 'Bovenkopje', 'dkm' ) ),
						'ugc_title'   => array( 'type' => 'text', 'label' => __( 'Kop', 'dkm' ) ),
						'ugc_accent'  => array( 'type' => 'text', 'label' => __( 'Schuine staart', 'dkm' ) ),
						'ugc_lead'    => array( 'type' => 'text', 'label' => __( 'Regel eronder', 'dkm' ) ),
					),
				),
				array(
					'titel'  => __( 'Gesprekken', 'dkm' ),
					'velden' => array(
						'rev_eyebrow' => array( 'type' => 'text', 'label' => __( 'Bovenkopje', 'dkm' ) ),
						'rev_title'   => array( 'type' => 'text', 'label' => __( 'Kop', 'dkm' ) ),
						'rev_accent'  => array( 'type' => 'text', 'label' => __( 'Schuine staart', 'dkm' ) ),
						'rev_lead'    => array( 'type' => 'text', 'label' => __( 'Regel eronder', 'dkm' ) ),
					),
				),
				array(
					'titel'  => __( 'Zo leveren wij', 'dkm' ),
					'velden' => array(
						'delivery' => array(
							'type'   => 'repeater',
							'label'  => __( 'Stappen', 'dkm' ),
							'velden' => array(
								'icoon' => array( 'type' => 'icon', 'label' => __( 'Icoon', 'dkm' ) ),
								'titel' => array( 'type' => 'text', 'label' => __( 'Kop', 'dkm' ) ),
								'tekst' => array( 'type' => 'textarea', 'label' => __( 'Uitleg', 'dkm' ) ),
							),
						),
					),
				),
				array(
					'titel'  => __( 'Op elk horloge', 'dkm' ),
					'velden' => array(
						'premium' => array(
							'type'   => 'repeater',
							'label'  => __( 'Punten', 'dkm' ),
							'velden' => array(
								'icoon' => array( 'type' => 'icon', 'label' => __( 'Icoon', 'dkm' ) ),
								'titel' => array( 'type' => 'text', 'label' => __( 'Kop', 'dkm' ) ),
								'tekst' => array( 'type' => 'textarea', 'label' => __( 'Uitleg', 'dkm' ) ),
							),
						),
					),
				),
			),
		),
		'contact'    => array(
			'titel'   => __( 'Contact', 'dkm' ),
			'uitleg'  => __( 'Wat er in de footer en op de knoppen staat.', 'dkm' ),
			'groepen' => array(
				array(
					'titel'  => __( 'Bereikbaarheid', 'dkm' ),
					'velden' => array(
						'whatsapp'      => array( 'type' => 'text', 'label' => __( 'WhatsApp-nummer', 'dkm' ) ),
						'whatsapp_text' => array( 'type' => 'textarea', 'label' => __( 'Eerste bericht', 'dkm' ) ),
						'email'         => array( 'type' => 'text', 'label' => __( 'E-mailadres', 'dkm' ) ),
						'snapchat'      => array( 'type' => 'text', 'label' => __( 'Snapchat', 'dkm' ) ),
						'sister_name'   => array( 'type' => 'text', 'label' => __( 'Naam ander merk', 'dkm' ) ),
						'sister_url'    => array( 'type' => 'text', 'label' => __( 'Link ander merk', 'dkm' ) ),
					),
				),
			),
		),
	);
}

/**
 * The three levels, as fields, built from the theme's own list.
 *
 * @return array
 */
function dkm_settings_tier_groups(): array {
	if ( ! function_exists( 'dkm_tiers_base' ) ) {
		return array();
	}

	$groepen = array();

	foreach ( dkm_tiers_base() as $slug => $tier ) {
		$groepen[] = array(
			'titel'  => $tier['name'],
			'velden' => array(
				'tier_' . $slug . '_name'  => array( 'type' => 'text', 'label' => __( 'Volledige naam', 'dkm' ) ),
				'tier_' . $slug . '_short' => array( 'type' => 'text', 'label' => __( 'Korte naam op de pil', 'dkm' ) ),
				'tier_' . $slug . '_sub'   => array( 'type' => 'text', 'label' => __( 'Eén zin die het samenvat', 'dkm' ) ),
				'tier_' . $slug . '_from'  => array( 'type' => 'number', 'label' => __( 'Laagste prijs', 'dkm' ) ),
				'tier_' . $slug . '_row'   => array( 'type' => 'image', 'label' => __( 'Foto in de balk', 'dkm' ) ),
				'tier_' . $slug . '_pick'  => array( 'type' => 'image', 'label' => __( 'Foto op de keuzekaart', 'dkm' ) ),
				'tier_' . $slug . '_claim' => array( 'type' => 'textarea', 'label' => __( 'Wat dit niveau is', 'dkm' ) ),
				'tier_' . $slug . '_who'   => array( 'type' => 'textarea', 'label' => __( 'Voor wie het bedoeld is', 'dkm' ) ),
				'tier_' . $slug . '_usps'  => array(
					'type'   => 'repeater',
					'label'  => __( 'Redenen om dit te kiezen', 'dkm' ),
					'uitleg' => __( 'De eerste twee staan op de homepage.', 'dkm' ),
					'velden' => array(
						'titel' => array( 'type' => 'text', 'label' => __( 'Kop', 'dkm' ) ),
						'tekst' => array( 'type' => 'textarea', 'label' => __( 'Uitleg', 'dkm' ) ),
					),
				),
			),
		);
	}

	return $groepen;
}

/**
 * The value every field falls back to: what the site shows today.
 *
 * @return array<string, mixed>
 */
function dkm_settings_defaults(): array {
	$uit = array(
		'bf_enabled'        => false,
		'bf_label'          => __( 'Black Friday', 'dkm' ),
		'bf_text'           => __( 'Kortingen tot €200', 'dkm' ),
		'bf_until'          => '',
		'bf_banner_mobile'  => 0,
		'bf_banner_desktop' => 0,
		'bf_banner_cta'     => __( 'Shop de Black Friday deals', 'dkm' ),
		'bf_banner_link'    => '',
		'stat_delivered'    => '1200',
		'stat_reviews'      => '800',
		'stat_rating'       => '4.9',
		'stat_recommend'    => '98',
		'closing_image'     => 0,
		'closing_eyebrow'   => __( 'Waarom De Klokjesman', 'dkm' ),
		'closing_title'     => __( 'Een horloge kopen', 'dkm' ),
		'closing_accent'    => __( 'zonder twijfel', 'dkm' ),
		'closing_cta'       => __( 'Bekijk de collectie', 'dkm' ),
		'ugc_eyebrow'       => __( 'Videos gedragen door', 'dkm' ),
		'ugc_title'         => __( 'De Klokjesman', 'dkm' ),
		'ugc_accent'        => __( 'in het echt', 'dkm' ),
		'ugc_lead'          => __( 'Echte beelden van klanten en ambassadors.', 'dkm' ),
		'rev_eyebrow'       => __( 'Klantervaringen', 'dkm' ),
		'rev_title'         => __( 'Wat klanten', 'dkm' ),
		'rev_accent'        => __( 'écht zeggen', 'dkm' ),
		'rev_lead'          => __( 'Echte WhatsApp-gesprekken. Scroll door een bericht om alles te lezen.', 'dkm' ),
		'whatsapp'          => '',
		'whatsapp_text'     => '',
		'email'             => '',
		'snapchat'          => '',
		'sister_name'       => '',
		'sister_url'        => '',
	);

	// The lists the theme already carries.
	$uit['ticker']       = array();
	$uit['usps']         = array();
	$uit['delivery']     = array();
	$uit['premium']      = array();
	$uit['closing_usps'] = array();

	if ( function_exists( 'dkm_ticker_base' ) ) {
		foreach ( dkm_ticker_base() as $zin ) {
			$uit['ticker'][] = array( 'tekst' => $zin );
		}
	}

	if ( function_exists( 'dkm_usps_base' ) ) {
		foreach ( dkm_usps_base() as $usp ) {
			$uit['usps'][] = array( 'icoon' => $usp[0], 'titel' => $usp[1], 'sub' => $usp[2] );
		}
	}

	if ( function_exists( 'dkm_delivery_base' ) ) {
		foreach ( dkm_delivery_base() as $stap ) {
			$uit['delivery'][] = array( 'icoon' => $stap[0], 'titel' => $stap[1], 'tekst' => $stap[2] );
		}
	}

	if ( function_exists( 'dkm_premium_features_base' ) ) {
		foreach ( dkm_premium_features_base() as $punt ) {
			$uit['premium'][] = array( 'icoon' => $punt[0], 'titel' => $punt[1], 'tekst' => $punt[2] );
		}
	}

	if ( function_exists( 'dkm_tiers_base' ) ) {
		foreach ( dkm_tiers_base() as $slug => $tier ) {
			$uit[ 'tier_' . $slug . '_name' ]  = $tier['name'];
			$uit[ 'tier_' . $slug . '_short' ] = $tier['short'] ?? $tier['name'];
			$uit[ 'tier_' . $slug . '_sub' ]   = $tier['sub'];
			$uit[ 'tier_' . $slug . '_from' ]  = $tier['from'];
			$uit[ 'tier_' . $slug . '_claim' ] = $tier['claim'];
			$uit[ 'tier_' . $slug . '_who' ]   = $tier['who'];
			$uit[ 'tier_' . $slug . '_row' ]   = 0;
			$uit[ 'tier_' . $slug . '_pick' ]  = 0;

			$punten = array();
			foreach ( $tier['explain'] ?? array() as $uitleg ) {
				$punten[] = array( 'titel' => $uitleg[1], 'tekst' => $uitleg[2] );
			}
			$uit[ 'tier_' . $slug . '_usps' ] = $punten;
		}
	}

	return $uit;
}

/**
 * Everything as it stands: what was saved, over what the theme carries.
 *
 * @return array<string, mixed>
 */
function dkm_settings_all(): array {
	static $alles = null;

	if ( null !== $alles ) {
		return $alles;
	}

	$bewaard = get_option( DKM_SETTINGS_OPTION, array() );
	$bewaard = is_array( $bewaard ) ? $bewaard : array();

	$alles = array_merge( dkm_settings_defaults(), $bewaard );

	return $alles;
}

/**
 * One setting.
 *
 * @param string $naam     Field name.
 * @param mixed  $fallback Returned when there is nothing stored and no default.
 * @return mixed
 */
function dkm_opt( string $naam, $fallback = '' ) {
	$alles = dkm_settings_all();

	if ( ! array_key_exists( $naam, $alles ) ) {
		return $fallback;
	}

	$waarde = $alles[ $naam ];

	if ( '' === $waarde || null === $waarde || array() === $waarde ) {
		return $fallback;
	}

	return $waarde;
}

/**
 * Carries over what was set in the old places, once.
 *
 * The theme settings and the fields on the front page were the only way to
 * change any of this before. They are read here so nothing is lost the moment
 * the screen takes over.
 */
function dkm_settings_migrate(): void {
	if ( get_option( 'dkm_settings_migrated' ) ) {
		return;
	}

	$bewaard = get_option( DKM_SETTINGS_OPTION, array() );
	$bewaard = is_array( $bewaard ) ? $bewaard : array();

	$oud = array(
		'bf_enabled', 'bf_label', 'bf_text', 'bf_until',
		'bf_banner_mobile', 'bf_banner_desktop', 'bf_banner_cta', 'bf_banner_link',
		'stat_delivered', 'stat_reviews', 'stat_rating', 'stat_recommend',
		'closing_image', 'whatsapp', 'whatsapp_text', 'email', 'snapchat',
		'sister_name', 'sister_url',
	);

	foreach ( $oud as $naam ) {
		$waarde = get_theme_mod( 'dkm_' . $naam, null );
		if ( null !== $waarde && '' !== $waarde && ! isset( $bewaard[ $naam ] ) ) {
			$bewaard[ $naam ] = $waarde;
		}
	}

	foreach ( array( 'superclone', 'bp-swiss-made', 'aaa' ) as $slug ) {
		$pick = get_theme_mod( 'dkm_pick_' . str_replace( '-', '_', $slug ), 0 );
		if ( $pick && ! isset( $bewaard[ 'tier_' . $slug . '_pick' ] ) ) {
			$bewaard[ 'tier_' . $slug . '_pick' ] = (int) $pick;
		}

		$term = get_term_by( 'slug', $slug, 'product_cat' );
		if ( $term && ! is_wp_error( $term ) && ! isset( $bewaard[ 'tier_' . $slug . '_row' ] ) ) {
			$thumb = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
			if ( $thumb ) {
				$bewaard[ 'tier_' . $slug . '_row' ] = $thumb;
			}
		}
	}

	update_option( DKM_SETTINGS_OPTION, $bewaard );
	update_option( 'dkm_settings_migrated', 1 );
}
// On init, not in the admin only: the front page reads these values too, and
// waiting for someone to open wp-admin would leave the shop with the defaults
// until then.
add_action( 'init', 'dkm_settings_migrate', 5 );
