<?php
/**
 * UGC: real clips from customers and ambassadors, fanned out in 3D.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

$clips = dkm_get_ugc( 9 );
if ( ! $clips ) {
	return;
}
?>
<section class="sec ugc">
	<div class="ugc__lines" aria-hidden="true"></div>

	<div class="ugc__orb" aria-hidden="true">
		<svg viewBox="0 0 200 200">
			<defs>
				<linearGradient id="dkm-orb" x1="0" y1="0" x2="1" y2="1">
					<stop offset="0" stop-color="#f2e4c3"/>
					<stop offset="1" stop-color="#a8864a"/>
				</linearGradient>
			</defs>
			<g fill="none" stroke="url(#dkm-orb)" stroke-width="1.4">
				<circle cx="100" cy="108" r="66"/>
				<circle cx="100" cy="108" r="56"/>
				<circle cx="100" cy="108" r="44" stroke-dasharray="3 7"/>
				<path d="M100 42v12M100 162v12M34 108h12M154 108h12"/>
				<path d="M86 30 90 14h20l4 16M86 186l4 16h20l4-16"/>
				<path d="M78 22 84 27 100 12l16 15 6-5-2 12H80l-2-12Z" fill="url(#dkm-orb)" stroke="none"/>
			</g>
			<g stroke="url(#dkm-orb)" stroke-width="2.4" stroke-linecap="round" class="ugc__hands">
				<path d="M100 108V70" class="ugc__hand ugc__hand--h"/>
				<path d="M100 108h30" class="ugc__hand ugc__hand--m"/>
			</g>
			<circle cx="100" cy="108" r="3" fill="#f2e4c3"/>
		</svg>
	</div>

	<div class="wrap ugc__in">
		<div class="sec-hd sec-hd--ctr">
			<span class="eyebrow"><?php esc_html_e( 'Van onze klanten', 'dkm' ); ?></span>
			<h2 class="h-l ugc__title"><?php esc_html_e( 'De Klokjesman', 'dkm' ); ?> <span class="em gr-txt"><?php esc_html_e( 'in het echt', 'dkm' ); ?></span></h2>
			<p class="lead"><?php esc_html_e( 'Echte beelden van klanten en ambassadors.', 'dkm' ); ?></p>
		</div>

		<div class="ugc__stage" data-dkm-ugc>
			<?php
			foreach ( $clips as $index => $clip ) :
				$video   = dkm_field( 'video', $clip->ID, array() );
				$caption = (string) dkm_field( 'caption', $clip->ID, get_the_title( $clip ) );
				$poster  = (int) get_post_thumbnail_id( $clip->ID );
				$src     = is_array( $video ) ? (string) ( $video['url'] ?? '' ) : (string) $video;
				?>
				<button type="button"
					class="ugc__card"
					data-dkm-ugc-card
					data-dkm-ugc-caption="<?php echo esc_attr( $caption ); ?>">

					<?php if ( $src ) : ?>
						<video
							playsinline
							muted
							loop
							preload="none"
							<?php if ( $poster ) : ?>
								poster="<?php echo esc_url( (string) wp_get_attachment_image_url( $poster, 'dkm-card-2x' ) ); ?>"
							<?php endif; ?>>
							<source src="<?php echo esc_url( $src ); ?>" type="<?php echo esc_attr( (string) ( $video['mime_type'] ?? 'video/mp4' ) ); ?>">
						</video>
					<?php elseif ( $poster ) : ?>
						<?php
						echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							$poster,
							'dkm-card-2x',
							false,
							array(
								'alt'     => $caption,
								'loading' => 'lazy',
							)
						);
						?>
					<?php endif; ?>

					<span class="ugc__ply" aria-hidden="true">
						<i><?php dkm_the_icon( 'play' ); ?></i>
					</span>

					<span class="ugc__cap">
						<b><?php echo esc_html( $caption ); ?></b>
						<span><?php esc_html_e( 'Tik om te spelen', 'dkm' ); ?></span>
					</span>
				</button>
			<?php endforeach; ?>
		</div>

		<div class="ugc__nav">
			<button type="button" class="cbtn" data-dkm-ugc-prev>
				<?php dkm_the_icon( 'left', '', __( 'Vorige video', 'dkm' ) ); ?>
			</button>
			<div class="ugc__meta">
				<b data-dkm-ugc-label>—</b>
				<span><?php esc_html_e( 'Bekijk', 'dkm' ); ?></span>
			</div>
			<button type="button" class="cbtn" data-dkm-ugc-next>
				<?php dkm_the_icon( 'arrow', '', __( 'Volgende video', 'dkm' ) ); ?>
			</button>
		</div>
	</div>
</section>
