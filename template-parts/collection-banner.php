<?php
/**
 * Collection banner between the filters and the grid.
 *
 * Content is per category and editable in ACF. The defaults in
 * dkm_banner_defaults() are what shows before anything has been filled in.
 *
 * @package DKM
 *
 * @var array $args Optional 'term' (WP_Term) for the current category.
 */

defined( 'ABSPATH' ) || exit;

$term     = $args['term'] ?? null;
$defaults = dkm_banner_defaults();
$slug     = $term instanceof WP_Term ? $term->slug : '';
$fallback = $defaults[ $slug ] ?? $defaults['_default'];
$acf_id   = $term instanceof WP_Term ? 'term_' . $term->term_id : 0;

$eyebrow = $acf_id ? (string) dkm_field( 'banner_eyebrow', $acf_id, $fallback['eyebrow'] ) : $fallback['eyebrow'];
$title   = $acf_id ? (string) dkm_field( 'banner_title', $acf_id, $fallback['title'] ) : $fallback['title'];

// The picture set for this level on the editing screen leads, so the banner
// shows the same watch as the bars on the home page.
$image_id = $slug ? (int) dkm_opt( 'tier_' . $slug . '_row', 0 ) : 0;
if ( ! $image_id && $acf_id ) {
	$image_id = (int) dkm_field( 'banner_image', $acf_id, 0 );
}
if ( ! $image_id && $term instanceof WP_Term ) {
	$image_id = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
}

$usps = array();
for ( $i = 1; $i <= 4; $i++ ) {
	$text = $acf_id ? (string) dkm_field( "banner_usp_{$i}_text", $acf_id, '' ) : '';
	$icon = $acf_id ? (string) dkm_field( "banner_usp_{$i}_icon", $acf_id, '' ) : '';
	if ( '' === $text ) {
		continue;
	}
	$usps[] = array( $icon ?: 'check', $text );
}
if ( ! $usps ) {
	$usps = $fallback['usps'];
}
?>
<div class="cban">
	<div class="cban__img">
		<?php
		if ( $image_id ) {
			echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$image_id,
				'dkm-wide',
				false,
				array(
					'alt'     => '',
					'loading' => 'lazy',
					'sizes'   => '(max-width:720px) 100vw, 480px',
				)
			);
		} elseif ( $term instanceof WP_Term ) {
			echo dkm_product_cat_image( $term, 'dkm-card' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			// The shop itself has no category to borrow a picture from, so it
			// takes the newest watch that has one. Without this the banner was
			// a block of colour with nothing in it.
			echo dkm_latest_product_image( 'dkm-wide' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>
	</div>

	<div class="cban__glow" aria-hidden="true"></div>

	<div class="cban__b">
		<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
		<h2 class="cban__t"><?php echo esc_html( $title ); ?></h2>
		<div class="cban__u">
			<?php foreach ( $usps as [$icon, $text] ) : ?>
				<div>
					<?php dkm_the_icon( $icon ); ?>
					<span><?php echo esc_html( $text ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
