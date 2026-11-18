<?php
/**
 * Customizer: the handful of settings that are not content
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

add_action( 'customize_register', 'dkm_customize_register' );

function dkm_customize_register( WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_panel(
		'dkm',
		array(
			'title'       => __( 'De Klokjesman', 'dkm' ),
			'description' => __( 'Instellingen voor de actiebalk, contactgegevens en social media.', 'dkm' ),
			'priority'    => 20,
		)
	);

	/* ---- Black Friday ---- */

	$wp_customize->add_section(
		'dkm_bf',
		array(
			'title'       => __( 'Actiebalk (Black Friday)', 'dkm' ),
			'panel'       => 'dkm',
			'description' => __( 'Zet de rode balk aan met een aftelling tot het einde van de actiedag. Staat de actie uit, dan is de hele site weer goud.', 'dkm' ),
		)
	);

	$wp_customize->add_setting(
		'dkm_bf_enabled',
		array(
			'default'           => false,
			'sanitize_callback' => 'wp_validate_boolean',
		)
	);
	$wp_customize->add_control(
		'dkm_bf_enabled',
		array(
			'label'   => __( 'Actiebalk aan', 'dkm' ),
			'section' => 'dkm_bf',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'dkm_bf_until',
		array(
			'default'           => '',
			'sanitize_callback' => 'dkm_sanitize_date',
		)
	);
	$wp_customize->add_control(
		'dkm_bf_until',
		array(
			'label'       => __( 'Laatste actiedag', 'dkm' ),
			'description' => __( 'De aftelling loopt tot middernacht aan het eind van deze dag. Daarna verdwijnt de balk automatisch.', 'dkm' ),
			'section'     => 'dkm_bf',
			'type'        => 'date',
		)
	);

	$wp_customize->add_setting(
		'dkm_bf_text',
		array(
			'default'           => __( 'Tot 30% korting op geselecteerde modellen', 'dkm' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'dkm_bf_text',
		array(
			'label'   => __( 'Tekst in de balk', 'dkm' ),
			'section' => 'dkm_bf',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'dkm_bf_label',
		array(
			'default'           => __( 'Black Friday', 'dkm' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'dkm_bf_label',
		array(
			'label'   => __( 'Naam van de actie', 'dkm' ),
			'section' => 'dkm_bf',
			'type'    => 'text',
		)
	);

	/* ---- contact ---- */

	$wp_customize->add_section(
		'dkm_contact',
		array(
			'title' => __( 'Contactgegevens', 'dkm' ),
			'panel' => 'dkm',
		)
	);

	$fields = array(
		'dkm_whatsapp'      => array(
			'label'   => __( 'WhatsApp-nummer', 'dkm' ),
			'default' => '31647974408',
			'desc'    => __( 'Alleen cijfers, met landnummer, dus 31647974408.', 'dkm' ),
		),
		'dkm_whatsapp_text' => array(
			'label'   => __( 'Standaardbericht', 'dkm' ),
			'default' => __( 'Hoi! Ik heb een vraag over een horloge.', 'dkm' ),
			'desc'    => __( 'Wordt voor de klant ingevuld als hij op een WhatsApp-knop klikt.', 'dkm' ),
		),
		'dkm_email'         => array(
			'label'   => __( 'E-mailadres', 'dkm' ),
			'default' => 'info@deklokjesman.com',
			'desc'    => '',
		),
		'dkm_snapchat'      => array(
			'label'   => __( 'Snapchat-link', 'dkm' ),
			'default' => '',
			'desc'    => '',
		),
		'dkm_sister_url'    => array(
			'label'   => __( 'Link zustermerk', 'dkm' ),
			'default' => 'https://deoutfitman.com',
			'desc'    => '',
		),
		'dkm_sister_name'   => array(
			'label'   => __( 'Naam zustermerk', 'dkm' ),
			'default' => 'De Outfitman',
			'desc'    => '',
		),
	);

	foreach ( $fields as $id => $field ) {
		$is_url = str_contains( $id, '_url' ) || 'dkm_snapchat' === $id;
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $field['default'],
				'sanitize_callback' => $is_url ? 'esc_url_raw' : ( 'dkm_email' === $id ? 'sanitize_email' : 'sanitize_text_field' ),
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'       => $field['label'],
				'description' => $field['desc'],
				'section'     => 'dkm_contact',
				'type'        => $is_url ? 'url' : 'text',
			)
		);
	}

	/* ---- numbers on the homepage ---- */

	$wp_customize->add_section(
		'dkm_stats',
		array(
			'title'       => __( 'Cijfers op de homepage', 'dkm' ),
			'panel'       => 'dkm',
			'description' => __( 'De drie getallen in de hero en bij de reviews.', 'dkm' ),
		)
	);

	$stats = array(
		'dkm_stat_delivered' => array( __( 'Horloges geleverd', 'dkm' ), '1200' ),
		'dkm_stat_reviews'   => array( __( 'reviews', 'dkm' ), '800' ),
		'dkm_stat_rating'    => array( __( 'Gemiddelde beoordeling', 'dkm' ), '4.9' ),
		'dkm_stat_recommend' => array( __( 'Beveelt ons aan, in procenten', 'dkm' ), '98' ),
	);

	foreach ( $stats as $id => [$label, $default] ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $default,
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $label,
				'section' => 'dkm_stats',
				'type'    => 'text',
			)
		);
	}
}

/**
 * Accepts an empty value or a Y-m-d date.
 */
function dkm_sanitize_date( $value ): string {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '';
	}
	$date = DateTimeImmutable::createFromFormat( 'Y-m-d', $value );
	return ( $date && $date->format( 'Y-m-d' ) === $value ) ? $value : '';
}
