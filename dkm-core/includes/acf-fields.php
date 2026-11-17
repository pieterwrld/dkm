<?php
/**
 * ACF field groups for the post types this plugin owns
 *
 * They live here rather than in the theme because they describe the content
 * itself, so switching theme must not take the fields with it.
 *
 * @package DKM_Core
 */

defined( 'ABSPATH' ) || exit;

add_action( 'acf/init', 'dkm_core_register_fields' );

function dkm_core_register_fields(): void {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

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
			'active'       => true,
			'show_in_rest' => true,
		)
	);

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
			'active'       => true,
			'show_in_rest' => true,
		)
	);
}
