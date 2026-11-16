<?php
/**
 * ACF field groups, registered in code so they travel with the theme
 *
 * The groups use fixed numbered fields rather than repeaters. That is not a
 * workaround: the design calls for exactly four USPs on a banner and exactly
 * five hotspots on the anatomy image, so a fixed set keeps the layout intact
 * and works on every ACF licence.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

/**
 * Reads an ACF field with a fallback, and keeps working when ACF is switched
 * off so the shop never white screens over a missing plugin.
 *
 * @param string     $selector Field name.
 * @param int|string $post_id  Post, term ('term_12') or option id.
 * @param mixed      $default  Returned when empty or when ACF is absent.
 * @return mixed
 */
function dkm_field( string $selector, $post_id = null, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$value = get_field( $selector, $post_id );
	if ( null === $value || '' === $value || array() === $value || false === $value ) {
		return $default;
	}
	return $value;
}

/**
 * The icon names offered in a select, built from the theme's own icon set.
 *
 * @return array<string,string>
 */
function dkm_icon_choices(): array {
	$choices = array();
	foreach ( array_keys( dkm_icon_paths() ) as $name ) {
		$choices[ $name ] = $name;
	}
	return $choices;
}

add_action( 'acf/init', 'dkm_register_field_groups' );

function dkm_register_field_groups(): void {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	dkm_register_home_fields();
	dkm_register_category_fields();
	dkm_register_ugc_fields();
	dkm_register_review_fields();
}

/**
 * Homepage: the hero and the anatomy section.
 */
function dkm_register_home_fields(): void {
	$fields = array(
		array(
			'key'   => 'dkm_tab_hero',
			'label' => __( 'Hero', 'dkm' ),
			'name'  => '',
			'type'  => 'tab',
		),
		array(
			'key'           => 'dkm_hero_image',
			'label'         => __( 'Hero-afbeelding', 'dkm' ),
			'name'          => 'hero_image',
			'type'          => 'image',
			'return_format' => 'id',
			'preview_size'  => 'medium',
			'instructions'  => __( 'Een brede liggende foto, minstens 1600 bij 900 pixels. Een vierkante productfoto werkt hier niet.', 'dkm' ),
		),
		array(
			'key'          => 'dkm_hero_badge',
			'label'        => __( 'Label boven de kop', 'dkm' ),
			'name'         => 'hero_badge',
			'type'         => 'text',
			'placeholder'  => __( 'Op voorraad · vandaag besteld, morgen in huis', 'dkm' ),
		),
		array(
			'key'         => 'dkm_hero_title',
			'label'       => __( 'Kop', 'dkm' ),
			'name'        => 'hero_title',
			'type'        => 'text',
			'placeholder' => __( 'Elegant. Exclusief.', 'dkm' ),
		),
		array(
			'key'          => 'dkm_hero_accent',
			'label'        => __( 'Accentwoord', 'dkm' ),
			'name'         => 'hero_accent',
			'type'         => 'text',
			'placeholder'  => __( 'Tijdloos.', 'dkm' ),
			'instructions' => __( 'Komt schuin en in bewegend goud achter de kop te staan.', 'dkm' ),
		),
		array(
			'key'         => 'dkm_hero_text',
			'label'       => __( 'Tekst onder de kop', 'dkm' ),
			'name'        => 'hero_text',
			'type'        => 'textarea',
			'rows'        => 3,
			'placeholder' => __( 'Drie kwaliteitsniveaus, één standaard.', 'dkm' ),
		),
		array(
			'key'   => 'dkm_tab_anatomy',
			'label' => __( 'Anatomie', 'dkm' ),
			'name'  => '',
			'type'  => 'tab',
		),
		array(
			'key'           => 'dkm_anatomy_image',
			'label'         => __( 'Foto voor de hotspots', 'dkm' ),
			'name'          => 'anatomy_image',
			'type'          => 'image',
			'return_format' => 'id',
			'preview_size'  => 'medium',
			'instructions'  => __( 'Een vierkante close-up. Zet daarna per punt de positie, gemeten vanaf de linkerbovenhoek van de foto.', 'dkm' ),
		),
	);

	for ( $i = 1; $i <= 5; $i++ ) {
		$fields[] = array(
			'key'   => "dkm_hotspot_{$i}_msg",
			'label' => sprintf( __( 'Punt %d', 'dkm' ), $i ),
			'name'  => '',
			'type'  => 'message',
			'message' => __( 'Laat de titel leeg om dit punt te verbergen.', 'dkm' ),
		);
		$fields[] = array(
			'key'       => "dkm_hotspot_{$i}_title",
			'label'     => __( 'Titel', 'dkm' ),
			'name'      => "hotspot_{$i}_title",
			'type'      => 'text',
			'wrapper'   => array( 'width' => '50' ),
		);
		$fields[] = array(
			'key'     => "dkm_hotspot_{$i}_text",
			'label'   => __( 'Uitleg', 'dkm' ),
			'name'    => "hotspot_{$i}_text",
			'type'    => 'textarea',
			'rows'    => 2,
			'wrapper' => array( 'width' => '50' ),
		);
		$fields[] = array(
			'key'     => "dkm_hotspot_{$i}_top",
			'label'   => __( 'Positie van boven (%)', 'dkm' ),
			'name'    => "hotspot_{$i}_top",
			'type'    => 'number',
			'min'     => 0,
			'max'     => 100,
			'append'  => '%',
			'wrapper' => array( 'width' => '25' ),
		);
		$fields[] = array(
			'key'     => "dkm_hotspot_{$i}_left",
			'label'   => __( 'Positie van links (%)', 'dkm' ),
			'name'    => "hotspot_{$i}_left",
			'type'    => 'number',
			'min'     => 0,
			'max'     => 100,
			'append'  => '%',
			'wrapper' => array( 'width' => '25' ),
		);
	}

	acf_add_local_field_group(
		array(
			'key'                   => 'group_dkm_home',
			'title'                 => __( 'Homepage', 'dkm' ),
			'fields'                => $fields,
			'location'              => array(
				array(
					array(
						'param'    => 'page_type',
						'operator' => '==',
						'value'    => 'front_page',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'hide_on_screen'        => array( 'the_content' ),
			'active'                => true,
			'show_in_rest'          => false,
		)
	);
}

/**
 * Per product category: the banner between the filters and the grid.
 */
function dkm_register_category_fields(): void {
	$fields = array(
		array(
			'key'           => 'dkm_banner_image',
			'label'         => __( 'Banner-afbeelding', 'dkm' ),
			'name'          => 'banner_image',
			'type'          => 'image',
			'return_format' => 'id',
			'preview_size'  => 'medium',
			'instructions'  => __( 'Liggend. Laat leeg om de categorie-afbeelding te gebruiken.', 'dkm' ),
		),
		array(
			'key'   => 'dkm_banner_eyebrow',
			'label' => __( 'Label', 'dkm' ),
			'name'  => 'banner_eyebrow',
			'type'  => 'text',
		),
		array(
			'key'   => 'dkm_banner_title',
			'label' => __( 'Kop', 'dkm' ),
			'name'  => 'banner_title',
			'type'  => 'text',
		),
	);

	for ( $i = 1; $i <= 4; $i++ ) {
		$fields[] = array(
			'key'     => "dkm_banner_usp_{$i}_icon",
			'label'   => sprintf( __( 'USP %d: icoon', 'dkm' ), $i ),
			'name'    => "banner_usp_{$i}_icon",
			'type'    => 'select',
			'choices' => dkm_icon_choices(),
			'allow_null' => 1,
			'wrapper' => array( 'width' => '30' ),
		);
		$fields[] = array(
			'key'     => "dkm_banner_usp_{$i}_text",
			'label'   => sprintf( __( 'USP %d: tekst', 'dkm' ), $i ),
			'name'    => "banner_usp_{$i}_text",
			'type'    => 'text',
			'wrapper' => array( 'width' => '70' ),
		);
	}

	acf_add_local_field_group(
		array
		(
			'key'      => 'group_dkm_cat',
			'title'    => __( 'Collectiebanner', 'dkm' ),
			'fields'   => $fields,
			'location' => array(
				array(
					array(
						'param'    => 'taxonomy',
						'operator' => '==',
						'value'    => 'product_cat',
					),
				),
			),
			'active'   => true,
		)
	);
}

/**
 * UGC clips: the video itself plus a caption. The poster is the featured image.
 */
function dkm_register_ugc_fields(): void {
	acf_add_local_field_group(
		array(
			'key'      => 'group_dkm_ugc',
			'title'    => __( 'Video', 'dkm' ),
			'fields'   => array(
				array(
					'key'           => 'dkm_ugc_video',
					'label'         => __( 'Videobestand', 'dkm' ),
					'name'          => 'video',
					'type'          => 'file',
					'return_format' => 'array',
					'mime_types'    => 'mp4,mov,webm',
					'instructions'  => __( 'Een TikTok- of Reels-bestand, staand formaat 9 bij 16. Zet ook een poster als uitgelichte afbeelding, dan is het eerste beeld scherp.', 'dkm' ),
				),
				array(
					'key'          => 'dkm_ugc_caption',
					'label'        => __( 'Onderschrift', 'dkm' ),
					'name'         => 'caption',
					'type'         => 'text',
					'instructions' => __( 'Bijvoorbeeld: Unboxing, Pols shot, Levering binnen.', 'dkm' ),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'dkm_ugc',
					),
				),
			),
			'active'   => true,
		)
	);
}

/**
 * Reviews: who sent it and how many stars.
 */
function dkm_register_review_fields(): void {
	acf_add_local_field_group(
		array(
			'key'      => 'group_dkm_review',
			'title'    => __( 'Review', 'dkm' ),
			'fields'   => array(
				array(
					'key'          => 'dkm_review_name',
					'label'        => __( 'Naam van de klant', 'dkm' ),
					'name'         => 'reviewer',
					'type'         => 'text',
					'instructions' => __( 'Alleen een voornaam is genoeg. Wordt als initialen in de avatar gezet.', 'dkm' ),
				),
				array(
					'key'           => 'dkm_review_rating',
					'label'         => __( 'Sterren', 'dkm' ),
					'name'          => 'rating',
					'type'          => 'number',
					'min'           => 1,
					'max'           => 5,
					'default_value' => 5,
				),
				array(
					'key'           => 'dkm_review_verified',
					'label'         => __( 'Geverifieerd', 'dkm' ),
					'name'          => 'verified',
					'type'          => 'true_false',
					'ui'            => 1,
					'default_value' => 1,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'dkm_review',
					),
				),
			),
			'active'   => true,
		)
	);
}
