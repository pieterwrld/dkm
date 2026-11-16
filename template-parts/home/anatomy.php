<?php
/**
 * Where you can tell a superclone: hotspots on a photo, tied to the list next
 * to it. Positions are set per image in ACF, because a new photo would put
 * hardcoded percentages in the wrong place.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

$front_id = (int) get_option( 'page_on_front' );
$image_id = (int) dkm_field( 'anatomy_image', $front_id, 0 );

$spots = array();
for ( $i = 1; $i <= 5; $i++ ) {
	$title = (string) dkm_field( "hotspot_{$i}_title", $front_id, '' );
	if ( '' === $title ) {
		continue;
	}
	$spots[] = array(
		'title' => $title,
		'text'  => (string) dkm_field( "hotspot_{$i}_text", $front_id, '' ),
		'top'   => (float) dkm_field( "hotspot_{$i}_top", $front_id, 50 ),
		'left'  => (float) dkm_field( "hotspot_{$i}_left", $front_id, 50 ),
	);
}

// Nothing filled in yet, so the section still reads correctly on a new install.
if ( ! $spots ) {
	$spots = dkm_anatomy_defaults();
}

if ( ! $image_id ) {
	return;
}
?>
<section class="sec sec--tight" data-dkm-anatomy>
	<div class="wrap">
		<div class="sec-hd">
			<span class="eyebrow"><?php esc_html_e( 'Het verschil van dichtbij', 'dkm' ); ?></span>
			<h2 class="h-l"><?php esc_html_e( 'Waar je een', 'dkm' ); ?> <span class="em gr-txt"><?php esc_html_e( 'superclone', 'dkm' ); ?></span> <?php esc_html_e( 'aan herkent', 'dkm' ); ?></h2>
			<p class="lead"><?php esc_html_e( 'Klik op een punt om te zien waar wij op controleren voordat een horloge de deur uit gaat.', 'dkm' ); ?></p>
		</div>

		<div class="ana">
			<div class="ana__img">
				<?php
				echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					$image_id,
					'dkm-card-2x',
					false,
					array(
						'alt'     => __( 'Close-up van een horloge', 'dkm' ),
						'loading' => 'lazy',
						'sizes'   => '(max-width:900px) 100vw, 640px',
					)
				);
				?>

				<?php foreach ( $spots as $index => $spot ) : ?>
					<button type="button"
						class="hot"
						data-dkm-spot="<?php echo esc_attr( (string) $index ); ?>"
						aria-expanded="false"
						style="top:<?php echo esc_attr( (string) $spot['top'] ); ?>%;left:<?php echo esc_attr( (string) $spot['left'] ); ?>%">
						<span aria-hidden="true"><?php echo esc_html( (string) ( $index + 1 ) ); ?></span>
						<span class="screen-reader-text"><?php echo esc_html( $spot['title'] ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>

			<div class="ana__l">
				<?php foreach ( $spots as $index => $spot ) : ?>
					<div class="ana__i" data-dkm-spot-item="<?php echo esc_attr( (string) $index ); ?>">
						<button type="button" class="ana__t" aria-expanded="false">
							<span class="ana__n" aria-hidden="true"><?php echo esc_html( (string) ( $index + 1 ) ); ?></span>
							<b><?php echo esc_html( $spot['title'] ); ?></b>
						</button>
						<div class="ana__c">
							<div><p><?php echo esc_html( $spot['text'] ); ?></p></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
