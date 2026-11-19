<?php
/**
 * Data tools in wp-admin
 *
 * The same jobs as the WP-CLI commands, behind buttons, so the shop can be
 * filled in without a terminal.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

/**
 * The jobs this page offers, in the order they should be run.
 *
 * @return array<string, array{label: string, uitleg: string, knop: string, soort: string}>
 */
function dkm_tools_jobs(): array {
	return array(
		'attributen-aanmaken' => array(
			'label'  => __( 'Attributen aanmaken', 'dkm' ),
			'uitleg' => __( 'Maakt Model, Maat en Wijzerplaat aan als ze nog niet bestaan. Dit moet een keer gebeuren voordat de rest werkt.', 'dkm' ),
			'knop'   => __( 'Aanmaken', 'dkm' ),
			'soort'  => 'veilig',
		),
		'attributen-proef'    => array(
			'label'  => __( 'Attributen proefdraaien', 'dkm' ),
			'uitleg' => __( 'Laat zien wat er zou veranderen aan je producten. Er wordt niets opgeslagen.', 'dkm' ),
			'knop'   => __( 'Proefdraaien', 'dkm' ),
			'soort'  => 'veilig',
		),
		'attributen-vullen'   => array(
			'label'  => __( 'Attributen bijvullen', 'dkm' ),
			'uitleg' => __( 'Zet Model, Maat en Wijzerplaat op je producten. Dit schrijft naar je producten.', 'dkm' ),
			'knop'   => __( 'Bijvullen', 'dkm' ),
			'soort'  => 'schrijft',
		),
		'skus-proef'          => array(
			'label'  => __( 'Artikelnummers proefdraaien', 'dkm' ),
			'uitleg' => __( 'Laat zien welke producten een artikelnummer zouden krijgen. Er wordt niets opgeslagen.', 'dkm' ),
			'knop'   => __( 'Proefdraaien', 'dkm' ),
			'soort'  => 'veilig',
		),
		'sale-proef'          => array(
			'label'  => __( 'Sale-categorie proefdraaien', 'dkm' ),
			'uitleg' => __( 'Laat zien welke afgeprijsde producten in Sale zouden komen en welke eruit zouden gaan. Er wordt niets opgeslagen.', 'dkm' ),
			'knop'   => __( 'Proefdraaien', 'dkm' ),
			'soort'  => 'veilig',
		),
		'sale-bijwerken'      => array(
			'label'  => __( 'Sale-categorie bijwerken', 'dkm' ),
			'uitleg' => __( 'Zet elk product met een actieprijs in de categorie Sale en haalt de producten zonder actieprijs eruit. Dit schrijft naar je producten.', 'dkm' ),
			'knop'   => __( 'Bijwerken', 'dkm' ),
			'soort'  => 'schrijft',
		),
		'skus-vullen'         => array(
			'label'  => __( 'Artikelnummers bijwerken', 'dkm' ),
			'uitleg' => __( 'Geeft elk product zonder artikelnummer het nummer DKM plus het product-id. Dit schrijft naar je producten.', 'dkm' ),
			'knop'   => __( 'Bijwerken', 'dkm' ),
			'soort'  => 'schrijft',
		),
	);
}

/**
 * Hangs the page under Gereedschap.
 */
function dkm_tools_menu(): void {
	add_management_page(
		__( 'DKM gereedschap', 'dkm' ),
		__( 'DKM gereedschap', 'dkm' ),
		'manage_woocommerce',
		'dkm-gereedschap',
		'dkm_tools_page'
	);
}
add_action( 'admin_menu', 'dkm_tools_menu' );

/**
 * Runs one job and hands back its report.
 *
 * @param string $job Key from dkm_tools_jobs().
 * @return array<int, array{0: string, 1: string}> Pairs of level and text.
 */
function dkm_tools_run( string $job ): array {
	if ( ! class_exists( 'DKM_CLI' ) ) {
		require_once DKM_CORE_DIR . '/includes/cli.php';
	}
	if ( ! class_exists( 'WooCommerce' ) ) {
		return array( array( 'fail', __( 'WooCommerce is niet actief.', 'dkm' ) ) );
	}

	// Een paar honderd producten doorlopen duurt langer dan een gewone pagina.
	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 300 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	}

	$cli = new DKM_CLI();

	try {
		switch ( $job ) {
			case 'attributen-aanmaken':
				$cli->create_attributes();
				break;
			case 'attributen-proef':
				$cli->backfill_attributes( array(), array( 'dry-run' => true ) );
				break;
			case 'attributen-vullen':
				$cli->backfill_attributes( array(), array() );
				break;
			case 'skus-proef':
				$cli->sync_skus( array(), array( 'dry-run' => true ) );
				break;
			case 'skus-vullen':
				$cli->sync_skus( array(), array() );
				break;
			case 'sale-proef':
				$cli->sync_sale( array(), array( 'dry-run' => true ) );
				break;
			case 'sale-bijwerken':
				$cli->sync_sale( array(), array() );
				break;
			default:
				return array( array( 'fail', __( 'Onbekende taak.', 'dkm' ) ) );
		}
	} catch ( Throwable $e ) {
		$lines   = $cli->take_lines();
		$lines[] = array( 'fail', $e->getMessage() );
		return $lines;
	}

	return $cli->take_lines();
}

/**
 * Draws the page.
 */
function dkm_tools_page(): void {
	if ( ! current_user_can( 'manage_woocommerce' ) ) {
		wp_die( esc_html__( 'Je hebt geen rechten voor deze pagina.', 'dkm' ) );
	}

	$jobs   = dkm_tools_jobs();
	$report = array();
	$gedaan = '';

	if ( isset( $_POST['dkm_job'] ) ) {
		check_admin_referer( 'dkm-gereedschap' );
		$gedaan = sanitize_key( wp_unslash( $_POST['dkm_job'] ) );
		if ( isset( $jobs[ $gedaan ] ) ) {
			$report = dkm_tools_run( $gedaan );
		} else {
			$report = array( array( 'fail', __( 'Onbekende taak.', 'dkm' ) ) );
		}
	}

	echo '<div class="wrap">';
	echo '<h1>' . esc_html__( 'DKM gereedschap', 'dkm' ) . '</h1>';
	echo '<p>' . esc_html__( 'Hiermee vul je de gegevens van de winkel bij. Draai eerst een proef, kijk naar het verslag en vul daarna pas echt bij.', 'dkm' ) . '</p>';

	if ( $report ) {
		$titel = isset( $jobs[ $gedaan ] ) ? $jobs[ $gedaan ]['label'] : __( 'Verslag', 'dkm' );
		echo '<h2>' . esc_html( $titel ) . '</h2>';
		echo '<div style="max-height:28rem;overflow:auto;padding:1rem;background:#fff;border:1px solid #c3c4c7;">';
		echo '<pre style="margin:0;white-space:pre-wrap;word-break:break-word;">';
		foreach ( $report as list( $niveau, $tekst ) ) {
			$kleur = '#1d2327';
			if ( 'warn' === $niveau ) {
				$kleur = '#8a6d00';
			} elseif ( 'fail' === $niveau ) {
				$kleur = '#b32d2e';
			} elseif ( 'ok' === $niveau ) {
				$kleur = '#107b10';
			}
			printf(
				'<span style="color:%s;">%s</span>%s',
				esc_attr( $kleur ),
				esc_html( '' === $tekst ? ' ' : $tekst ),
				"\n"
			);
		}
		echo '</pre></div>';
	}

	echo '<form method="post" action="">';
	wp_nonce_field( 'dkm-gereedschap' );
	echo '<table class="widefat striped" style="margin-top:1.5rem;max-width:60rem;">';
	echo '<tbody>';
	foreach ( $jobs as $sleutel => $job ) {
		$schrijft = 'schrijft' === $job['soort'];
		echo '<tr>';
		echo '<td style="padding:1rem;">';
		echo '<strong>' . esc_html( $job['label'] ) . '</strong><br>';
		echo '<span class="description">' . esc_html( $job['uitleg'] ) . '</span>';
		echo '</td>';
		echo '<td style="padding:1rem;width:12rem;text-align:right;">';
		printf(
			'<button type="submit" name="dkm_job" value="%1$s" class="button %2$s"%3$s>%4$s</button>',
			esc_attr( $sleutel ),
			$schrijft ? 'button-primary' : 'button-secondary',
			$schrijft ? ' onclick="return confirm(' . esc_attr( wp_json_encode( __( 'Dit schrijft naar je producten. Doorgaan?', 'dkm' ) ) ) . ');"' : '',
			esc_html( $job['knop'] )
		);
		echo '</td>';
		echo '</tr>';
	}
	echo '</tbody></table>';
	echo '</form>';
	echo '</div>';
}
