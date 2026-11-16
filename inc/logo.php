<?php
/**
 * DKM wordmark
 *
 * The O in KLOKJESMAN is a watch dial with a crown and hands that turn in real
 * time: the minute hand once every 24s, the hour hand once every 288s.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns the wordmark.
 *
 * @param array{light?:bool,size?:string,tag?:string} $args Light renders it on a dark surface.
 */
function dkm_logo( array $args = array() ): string {
	$light = ! empty( $args['light'] );
	$size  = $args['size'] ?? '';
	$class = 'brand' . ( $light ? ' brand--lt' : '' );
	$style = $size ? ' style="--bs:' . esc_attr( $size ) . '"' : '';

	$dial = <<<SVG
<svg class="brand__o" viewBox="0 0 40 40" aria-hidden="true" focusable="false">
	<circle class="brand__dial" cx="20" cy="21.5" r="14.6"/>
	<circle class="brand__dial brand__dial--inner" cx="20" cy="21.5" r="11.6"/>
	<path class="brand__tick" d="M20 9.3v2.6M20 31.1v2.6M7.8 21.5h2.6M29.6 21.5h2.6"/>
	<path class="brand__crown" d="M14.6 6.4 17 8.1 20 4.6l3 3.5 2.4-1.7-.7 3.9h-9.4l-.7-3.9Z"/>
	<g class="brand__hh"><path class="brand__hand" d="M20 21.5V15.4"/></g>
	<g class="brand__mh"><path class="brand__hand brand__hand--min" d="M20 21.5h5.6"/></g>
	<circle class="brand__pin" cx="20" cy="21.5" r="1.5"/>
</svg>
SVG;

	return sprintf(
		'<span class="%s"%s>DE KL%sKJESMAN</span>',
		esc_attr( $class ),
		$style,
		$dial
	);
}

/**
 * Returns the wordmark wrapped in a link to the front page.
 *
 * @param array $args See dkm_logo().
 */
function dkm_logo_link( array $args = array() ): string {
	$extra = isset( $args['class'] ) ? ' ' . $args['class'] : '';

	return sprintf(
		'<a class="brand-link%s" href="%s" rel="home" aria-label="%s">%s</a>',
		esc_attr( $extra ),
		esc_url( home_url( '/' ) ),
		esc_attr__( 'De Klokjesman, naar de homepage', 'dkm' ),
		dkm_logo( $args )
	);
}
