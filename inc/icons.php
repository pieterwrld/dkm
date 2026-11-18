<?php
/**
 * DKM icon set
 *
 * Thirty hand-drawn SVG paths on a 24 viewBox with round caps. No icon font,
 * no external library.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

/**
 * Icons that are drawn as a filled shape instead of a stroke.
 */
const DKM_FILLED_ICONS = array( 'star', 'play', 'wa', 'snap' );

/**
 * The path data for every icon, keyed by name.
 *
 * @return array<string,string>
 */
function dkm_icon_paths(): array {
	static $paths = null;
	if ( null !== $paths ) {
		return $paths;
	}

	$paths = array(
		'truck'   => 'M3 7.5h10.5v8H3zM13.5 10h3.8L21 13.2v2.3h-7.5M7 19a1.8 1.8 0 1 0 0-3.6A1.8 1.8 0 0 0 7 19Zm10 0a1.8 1.8 0 1 0 0-3.6 1.8 1.8 0 0 0 0 3.6Z',
		'shield'  => 'M12 21s7-3.5 7-9.3V5.6L12 3 5 5.6v6.1C5 17.5 12 21 12 21Zm-2.6-9.4 1.9 1.9 3.5-3.6',
		'chat'    => 'M20 14.4a3 3 0 0 1-3 3H8.6L4.5 20.5V6.6a3 3 0 0 1 3-3h9.5a3 3 0 0 1 3 3v7.8Z',
		'box'     => 'M3.2 7.4 12 3.2l8.8 4.2v9.2L12 20.8l-8.8-4.2V7.4Zm0 0L12 11.7l8.8-4.3M12 11.7v9.1',
		'gem'     => 'M6.2 3.2h11.6l3.2 5.6L12 20.8 3 8.8l3.2-5.6ZM3 8.8h18M9.4 3.2 6.6 8.8 12 20.8l5.4-12-2.8-5.6',
		'watch'   => 'M12 19.2a5.6 5.6 0 1 0 0-11.2 5.6 5.6 0 0 0 0 11.2ZM9.3 7.8 9.8 3h4.4l.5 4.8M9.3 19.4l.5 4.3M12 10.8v2.9h2.2',
		'swiss'   => 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18ZM12 7.8v8.4M7.8 12h8.4',
		'drop'    => 'M12 3.2s5.8 6.3 5.8 10.2a5.8 5.8 0 0 1-11.6 0C6.2 9.5 12 3.2 12 3.2Z',
		'glow'    => 'M12 4.2V6m0 12v1.8M4.2 12H6m12 0h1.8M6.6 6.6 7.9 7.9m8.2 8.2 1.3 1.3m0-10.8-1.3 1.3M7.9 16.1l-1.3 1.3M12 8.4a3.6 3.6 0 1 0 0 7.2 3.6 3.6 0 0 0 0-7.2Z',
		'weight'  => 'M5.2 8.2h13.6L17.2 20.6H6.8L5.2 8.2ZM9 8.2a3 3 0 1 1 6 0',
		'engrave' => 'M3.4 17.3 14.6 6.1l3.4 3.4L6.8 20.7H3.4v-3.4ZM13.2 7.5l3.4 3.4M16.2 4.2l3.6 3.6',
		'crown'   => 'M3.6 17.4h16.8l1.2-9.6-5.4 3.8L12 5.2 7.8 11.6 2.4 7.8l1.2 9.6Zm0 2.6h16.8',
		'star'    => 'm12 3.2 2.7 5.7 6.2.9-4.5 4.3 1.1 6.1L12 17.3l-5.5 2.9 1.1-6.1-4.5-4.3 6.2-.9L12 3.2Z',
		'check'   => 'm4.5 12.2 5 5L19.5 6.6',
		'minus'   => 'M5.4 12h13.2',
		'close'   => 'M6 6l12 12M18 6 6 18',
		'info'    => 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18ZM12 11v5.2M12 7.6v.4',
		'search'  => 'M11 18a7 7 0 1 0 0-14 7 7 0 0 0 0 14ZM16.3 16.3 21 21',
		'cart'    => 'M4.8 7h14.4l-1.3 12.5H6.1L4.8 7ZM9 7V5.4a3 3 0 0 1 6 0V7',
		'arrow'   => 'M4.8 12h14.4M13.2 6l6 6-6 6',
		'left'    => 'M19.2 12H4.8M10.8 6l-6 6 6 6',
		'play'    => 'M8.4 5.4v13.2L19 12 8.4 5.4Z',
		'sound'   => 'M4 9.5h3.2L12 5.4v13.2L7.2 14.5H4v-5ZM15.4 9a4.2 4.2 0 0 1 0 6M18 6.5a7.6 7.6 0 0 1 0 11',
		'mute'    => 'M4 9.5h3.2L12 5.4v13.2L7.2 14.5H4v-5ZM16 9.8l4.4 4.4M20.4 9.8L16 14.2',
		'lock'    => 'M5.8 10.8h12.4v9.4H5.8v-9.4ZM8.8 10.8V8.2a3.2 3.2 0 0 1 6.4 0v2.6',
		'refresh' => 'M20.4 12a8.4 8.4 0 1 1-2.5-5.9M20.4 3.6v5h-5',
		'spark'   => 'm12 3 1.9 5.4 5.4 1.9-5.4 1.9L12 17.6l-1.9-5.4-5.4-1.9 5.4-1.9L12 3Z',
		'filter'  => 'M3.6 6.6h16.8M6.6 12h10.8M10.2 17.4h3.6',
		'clock'   => 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18ZM12 7.2V12l3.2 2.1',
		'tag'     => 'M11.3 3.4H20v8.7l-8.6 8.6-8.7-8.7 8.6-8.6ZM16.4 8.2h.01',
		'eye'     => 'M2.4 12S5.8 5.8 12 5.8 21.6 12 21.6 12 18.2 18.2 12 18.2 2.4 12 2.4 12Zm9.6 2.8a2.8 2.8 0 1 0 0-5.6 2.8 2.8 0 0 0 0 5.6Z',
		'wa'      => 'M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 2.1.55 4.08 1.6 5.85L2 22l4.37-1.68a9.86 9.86 0 0 0 5.67 1.76c5.46 0 9.91-4.45 9.91-9.91S17.5 2 12.04 2Zm5.8 14.1c-.25.69-1.44 1.32-2 1.4-.51.08-1.16.11-1.87-.12-.43-.13-.98-.32-1.69-.62-2.98-1.29-4.92-4.28-5.07-4.48-.15-.2-1.21-1.61-1.21-3.07 0-1.46.77-2.18 1.04-2.48.27-.3.59-.37.79-.37h.57c.18 0 .43-.07.67.51.25.6.85 2.06.92 2.21.07.15.12.32.02.52-.1.2-.15.32-.3.5l-.44.52c-.15.15-.3.31-.13.61.17.3.75 1.24 1.62 2.01 1.11.99 2.05 1.3 2.35 1.45.3.15.47.12.64-.07.17-.2.74-.86.94-1.16.2-.3.4-.25.67-.15.27.1 1.72.81 2.01.96.3.15.5.22.57.35.07.12.07.72-.18 1.41Z',
		'snap'    => 'M12 2.6c2.8 0 4.4 1.9 4.4 4.6 0 .7-.05 1.4-.08 1.9.5.2.9 0 1.4-.2.6-.2 1.1.5.6 1.05-.4.5-1.25.86-1.72 1.05-.3.1-.4.3-.3.6.5 1.45 1.9 2.9 3.25 3.3.5.2.5.75 0 .95-.67.3-1.53.4-1.82.67-.2.2-.1.58-.3.77-.2.2-.67.1-1.25 0-.86-.1-1.82-.1-2.59.48-.67.48-1.25 1.15-2.59 1.15s-1.92-.67-2.59-1.15c-.77-.58-1.73-.58-2.59-.48-.58.1-1.05.2-1.25 0-.2-.2-.1-.58-.3-.77-.29-.28-1.15-.38-1.82-.67-.5-.2-.5-.76 0-.96 1.35-.38 2.78-1.82 3.26-3.28.1-.29 0-.48-.3-.58-.48-.2-1.34-.57-1.73-1.05-.48-.57.1-1.24.67-1.05.48.2.86.38 1.34.2-.03-.58-.07-1.25-.07-1.92C7.65 4.5 9.2 2.6 12 2.6Z',
		'mail'    => 'M3.4 5.6h17.2v12.8H3.4V5.6Zm.6.6 8 5.7 8-5.7',
	);

	return $paths;
}

/**
 * Returns an inline SVG icon.
 *
 * @param string $name  Icon name from dkm_icon_paths().
 * @param string $class Extra classes on the svg element.
 * @param string $title Accessible label. Empty renders the icon as decorative.
 */
function dkm_icon( string $name, string $class = '', string $title = '' ): string {
	$paths = dkm_icon_paths();
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	$filled = in_array( $name, DKM_FILLED_ICONS, true );
	$attrs  = $filled ? ' fill="currentColor" stroke="none"' : '';

	if ( '' !== $title ) {
		$label = ' role="img" aria-label="' . esc_attr( $title ) . '"';
	} else {
		$label = ' aria-hidden="true" focusable="false"';
	}

	return sprintf(
		'<svg class="ico %s" viewBox="0 0 24 24"%s%s><path d="%s"/></svg>',
		esc_attr( $class ),
		$attrs,
		$label,
		esc_attr( $paths[ $name ] )
	);
}

/**
 * Echoes an inline SVG icon.
 */
function dkm_the_icon( string $name, string $class = '', string $title = '' ): void {
	echo dkm_icon( $name, $class, $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from a fixed path table.
}
