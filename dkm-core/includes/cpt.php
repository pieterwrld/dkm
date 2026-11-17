<?php
/**
 * Custom post types: WhatsApp reviews and UGC clips
 *
 * Both are post types rather than repeater fields, so the client can keep
 * adding items without a licence for a paid ACF tier, and so each item has its
 * own media library entry.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'dkm_register_post_types' );

function dkm_register_post_types(): void {
	register_post_type(
		'dkm_review',
		array(
			'labels'          => array(
				'name'               => __( 'Reviews', 'dkm' ),
				'singular_name'      => __( 'Review', 'dkm' ),
				'add_new_item'       => __( 'Nieuwe review', 'dkm' ),
				'edit_item'          => __( 'Review bewerken', 'dkm' ),
				'menu_name'          => __( 'Reviews', 'dkm' ),
				'all_items'          => __( 'Alle reviews', 'dkm' ),
				'featured_image'     => __( 'Screenshot van het gesprek', 'dkm' ),
				'set_featured_image' => __( 'Screenshot kiezen', 'dkm' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_rest'    => true,
			'menu_icon'       => 'dashicons-format-chat',
			'supports'        => array( 'title', 'thumbnail', 'page-attributes' ),
			'has_archive'     => false,
			'rewrite'         => false,
			'capability_type' => 'post',
		)
	);

	register_post_type(
		'dkm_ugc',
		array(
			'labels'          => array(
				'name'               => __( 'UGC-video’s', 'dkm' ),
				'singular_name'      => __( 'UGC-video', 'dkm' ),
				'add_new_item'       => __( 'Nieuwe UGC-video', 'dkm' ),
				'edit_item'          => __( 'UGC-video bewerken', 'dkm' ),
				'menu_name'          => __( 'UGC-video’s', 'dkm' ),
				'all_items'          => __( 'Alle video’s', 'dkm' ),
				'featured_image'     => __( 'Poster (stilstaand beeld)', 'dkm' ),
				'set_featured_image' => __( 'Poster kiezen', 'dkm' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_rest'    => true,
			'menu_icon'       => 'dashicons-video-alt3',
			'supports'        => array( 'title', 'thumbnail', 'page-attributes' ),
			'has_archive'     => false,
			'rewrite'         => false,
			'capability_type' => 'post',
		)
	);
}

/**
 * Published reviews, newest first unless the client has ordered them by hand.
 *
 * @param int $limit How many to return.
 * @return WP_Post[]
 */
function dkm_get_reviews( int $limit = 12 ): array {
	return get_posts(
		array(
			'post_type'      => 'dkm_review',
			'post_status'    => 'publish',
			'posts_per_page' => $limit,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
		)
	);
}

/**
 * Published UGC clips.
 *
 * @param int $limit How many to return.
 * @return WP_Post[]
 */
function dkm_get_ugc( int $limit = 10 ): array {
	return get_posts(
		array(
			'post_type'      => 'dkm_ugc',
			'post_status'    => 'publish',
			'posts_per_page' => $limit,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
		)
	);
}

/* ==========================================================================
   Server side crop of the WhatsApp chrome
   ========================================================================== */

/**
 * How much of a WhatsApp screenshot is interface rather than conversation.
 */
const DKM_REVIEW_CROP_TOP    = 0.11;
const DKM_REVIEW_CROP_BOTTOM = 0.06;

add_action( 'added_post_meta', 'dkm_maybe_crop_review', 10, 3 );
add_action( 'updated_post_meta', 'dkm_maybe_crop_review', 10, 3 );

/**
 * Crops the status bar and the input bar off a review screenshot as soon as one
 * is attached, so no CSS offset is needed at render time.
 *
 * @param int    $meta_id  Ignored.
 * @param int    $post_id  Post the meta belongs to.
 * @param string $meta_key Meta key.
 */
function dkm_maybe_crop_review( $meta_id, $post_id, $meta_key ): void {
	if ( '_thumbnail_id' !== $meta_key || 'dkm_review' !== get_post_type( $post_id ) ) {
		return;
	}
	dkm_crop_review_image( (int) get_post_thumbnail_id( $post_id ) );
}

/**
 * Writes a cropped copy of an attachment and remembers where it is.
 *
 * Runs once per attachment: the result is stored in meta, so repeated saves of
 * the same review do no further work.
 *
 * @param int $attachment_id Attachment to crop.
 * @return string URL of the cropped file, or the original when cropping fails.
 */
function dkm_crop_review_image( int $attachment_id ): string {
	if ( ! $attachment_id ) {
		return '';
	}

	$existing = (string) get_post_meta( $attachment_id, '_dkm_cropped_url', true );
	if ( '' !== $existing ) {
		return $existing;
	}

	$file = get_attached_file( $attachment_id );
	if ( ! $file || ! file_exists( $file ) ) {
		return (string) wp_get_attachment_image_url( $attachment_id, 'full' );
	}

	$editor = wp_get_image_editor( $file );
	if ( is_wp_error( $editor ) ) {
		return (string) wp_get_attachment_image_url( $attachment_id, 'full' );
	}

	$size = $editor->get_size();
	$w    = (int) ( $size['width'] ?? 0 );
	$h    = (int) ( $size['height'] ?? 0 );
	if ( $w < 10 || $h < 10 ) {
		return (string) wp_get_attachment_image_url( $attachment_id, 'full' );
	}

	$top    = (int) round( $h * DKM_REVIEW_CROP_TOP );
	$height = (int) round( $h * ( 1 - DKM_REVIEW_CROP_TOP - DKM_REVIEW_CROP_BOTTOM ) );

	$cropped = $editor->crop( 0, $top, $w, $height );
	if ( is_wp_error( $cropped ) ) {
		return (string) wp_get_attachment_image_url( $attachment_id, 'full' );
	}

	$target = $editor->generate_filename( 'chat', dirname( $file ) );
	$saved  = $editor->save( $target );
	if ( is_wp_error( $saved ) || empty( $saved['path'] ) ) {
		return (string) wp_get_attachment_image_url( $attachment_id, 'full' );
	}

	$uploads = wp_get_upload_dir();
	$url     = str_replace( $uploads['basedir'], $uploads['baseurl'], $saved['path'] );

	update_post_meta( $attachment_id, '_dkm_cropped_url', $url );
	update_post_meta( $attachment_id, '_dkm_cropped_w', (int) ( $saved['width'] ?? $w ) );
	update_post_meta( $attachment_id, '_dkm_cropped_h', (int) ( $saved['height'] ?? $height ) );

	return $url;
}

/**
 * The conversation image for a review: the cropped copy when there is one.
 *
 * @param int $post_id Review id.
 * @return array{url:string,width:int,height:int,alt:string}|null
 */
function dkm_review_image( int $post_id ): ?array {
	$attachment_id = (int) get_post_thumbnail_id( $post_id );
	if ( ! $attachment_id ) {
		return null;
	}

	$url = dkm_crop_review_image( $attachment_id );
	if ( '' === $url ) {
		return null;
	}

	return array(
		'url'    => $url,
		'width'  => (int) get_post_meta( $attachment_id, '_dkm_cropped_w', true ),
		'height' => (int) get_post_meta( $attachment_id, '_dkm_cropped_h', true ),
		'alt'    => (string) get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
	);
}
