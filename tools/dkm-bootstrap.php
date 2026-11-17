<?php
/**
 * DKM bootstrap.
 *
 * Eenmalig hulpbestand om DKM Core op de server te krijgen zonder SSH en
 * zonder FTP. Zodra de plugin draait neemt die het over en kan dit bestand weg.
 *
 * Gebruik: zet dit bestand in de WordPress-hoofdmap, naast wp-config.php.
 *
 * Er zit met opzet GEEN sleutel in de URL. Zo'n sleutel belandt in serverlogs,
 * in de browsergeschiedenis en in verwijzende pagina's, en dan ligt de deur
 * open voor iedereen die hem tegenkomt. In plaats daarvan vraagt dit bestand
 * om een wachtwoord voor applicaties van WordPress zelf, precies zoals de REST
 * API dat doet, en controleert het of die gebruiker plugins mag installeren.
 *
 * Drie sloten:
 *   1. Inloggen met een wachtwoord voor applicaties is verplicht.
 *   2. Die gebruiker moet het recht install_plugins hebben.
 *   3. Na 24 uur doet het bestand niets meer en wist het zichzelf.
 *
 * @package DKM_Core
 */

const DKM_BOOTSTRAP_MAX_AGE = 86400; // 24 uur.
const DKM_BOOTSTRAP_MAX_BYTES = 33554432; // 32 MB.

header( 'Content-Type: application/json; charset=UTF-8' );
header( 'X-Robots-Tag: noindex, nofollow' );

/**
 * Antwoordt en stopt.
 */
function dkm_bs_out( int $code, array $data ): void {
	http_response_code( $code );
	echo json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
	exit;
}

/**
 * Wist dit bestand.
 */
function dkm_bs_self_destruct(): bool {
	return @unlink( __FILE__ );
}

// ---- slot 3: vervaldatum ----

$leeftijd = time() - (int) @filemtime( __FILE__ );

if ( $leeftijd > DKM_BOOTSTRAP_MAX_AGE ) {
	dkm_bs_self_destruct();
	dkm_bs_out( 410, array( 'fout' => 'Dit bestand is verlopen en heeft zichzelf gewist.' ) );
}

// ---- WordPress laden ----

define( 'DKM_BOOTSTRAP', true );

$root = __DIR__ . '/';

if ( ! file_exists( $root . 'wp-load.php' ) ) {
	dkm_bs_out( 500, array( 'fout' => 'Dit bestand hoort in de WordPress-hoofdmap, naast wp-config.php.' ) );
}

require_once $root . 'wp-load.php';

// ---- slot 1: inloggen ----

/**
 * Haalt de inloggegevens op. Niet elke server vult PHP_AUTH_USER, dus de
 * Authorization-kop wordt ook met de hand gelezen.
 */
function dkm_bs_credentials(): array {
	if ( ! empty( $_SERVER['PHP_AUTH_USER'] ) && isset( $_SERVER['PHP_AUTH_PW'] ) ) {
		return array( (string) $_SERVER['PHP_AUTH_USER'], (string) $_SERVER['PHP_AUTH_PW'] );
	}

	$kop = '';

	foreach ( array( 'HTTP_AUTHORIZATION', 'REDIRECT_HTTP_AUTHORIZATION' ) as $sleutel ) {
		if ( ! empty( $_SERVER[ $sleutel ] ) ) {
			$kop = (string) $_SERVER[ $sleutel ];
			break;
		}
	}

	if ( 0 !== stripos( $kop, 'basic ' ) ) {
		return array( '', '' );
	}

	$rauw = base64_decode( substr( $kop, 6 ), true );

	if ( false === $rauw || ! str_contains( $rauw, ':' ) ) {
		return array( '', '' );
	}

	[ $naam, $wachtwoord ] = explode( ':', $rauw, 2 );

	return array( $naam, $wachtwoord );
}

// WordPress accepteert een wachtwoord voor applicaties alleen bij een
// API-verzoek. Dit is er een, dus dat wordt hier gezegd.
add_filter( 'application_password_is_api_request', '__return_true' );

[ $naam, $wachtwoord ] = dkm_bs_credentials();

if ( '' === $naam || '' === $wachtwoord ) {
	header( 'WWW-Authenticate: Basic realm="DKM"' );
	dkm_bs_out( 401, array( 'fout' => 'Inloggen met een wachtwoord voor applicaties is verplicht.' ) );
}

$gebruiker = wp_authenticate_application_password( null, $naam, $wachtwoord );

if ( is_wp_error( $gebruiker ) || ! $gebruiker instanceof WP_User ) {
	dkm_bs_out( 401, array( 'fout' => 'Inloggen is niet gelukt.' ) );
}

wp_set_current_user( $gebruiker->ID );

// ---- slot 2: rechten ----

if ( ! user_can( $gebruiker, 'install_plugins' ) || ! user_can( $gebruiker, 'manage_options' ) ) {
	dkm_bs_out( 403, array( 'fout' => 'Deze gebruiker mag geen plugins installeren.' ) );
}

if ( defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS ) {
	dkm_bs_out( 409, array( 'fout' => 'Deze site staat geen bestandswijzigingen toe.' ) );
}

require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/misc.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';
require_once ABSPATH . 'wp-admin/includes/theme.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php';

$actie = isset( $_GET['actie'] ) ? sanitize_key( wp_unslash( $_GET['actie'] ) ) : 'status';

// ---- status ----

if ( 'status' === $actie ) {
	$plugins = array();

	foreach ( get_plugins() as $bestand => $data ) {
		$plugins[ $bestand ] = array(
			'naam'   => $data['Name'],
			'versie' => $data['Version'],
			'actief' => is_plugin_active( $bestand ),
		);
	}

	dkm_bs_out(
		200,
		array(
			'ok'           => true,
			'gebruiker'    => $gebruiker->user_login,
			'wp'           => get_bloginfo( 'version' ),
			'php'          => PHP_VERSION,
			'actief_thema' => wp_get_theme()->get_stylesheet(),
			'themas'       => array_keys( wp_get_themes() ),
			'plugins'      => $plugins,
			'schrijfbaar'  => array(
				'themas'  => wp_is_writable( get_theme_root() ),
				'plugins' => wp_is_writable( WP_PLUGIN_DIR ),
			),
			'verloopt_over' => DKM_BOOTSTRAP_MAX_AGE - $leeftijd,
		)
	);
}

// ---- zichzelf wissen ----

if ( 'weg' === $actie ) {
	dkm_bs_out(
		200,
		array(
			'ok'     => dkm_bs_self_destruct(),
			'bericht' => 'Dit bestand is gewist.',
		)
	);
}

// ---- activeren ----

if ( 'activeer' === $actie ) {
	$type   = isset( $_GET['type'] ) ? sanitize_key( wp_unslash( $_GET['type'] ) ) : '';
	$doelwit = isset( $_GET['target'] ) ? sanitize_text_field( wp_unslash( $_GET['target'] ) ) : '';

	if ( 'theme' === $type ) {
		$thema = wp_get_theme( $doelwit );

		if ( ! $thema->exists() ) {
			dkm_bs_out( 404, array( 'fout' => 'Dat thema staat niet op de server.' ) );
		}

		$kapot = $thema->errors();

		if ( $kapot ) {
			dkm_bs_out( 409, array( 'fout' => $kapot->get_error_message() ) );
		}

		$vorige = wp_get_theme()->get_stylesheet();
		switch_theme( $thema->get_stylesheet() );

		dkm_bs_out( 200, array( 'ok' => true, 'vorige' => $vorige, 'actief' => wp_get_theme()->get_stylesheet() ) );
	}

	if ( 'plugin' === $type ) {
		$uitkomst = activate_plugin( $doelwit );

		if ( is_wp_error( $uitkomst ) ) {
			dkm_bs_out( 500, array( 'fout' => $uitkomst->get_error_message() ) );
		}

		dkm_bs_out( 200, array( 'ok' => true, 'plugin' => $doelwit, 'actief' => is_plugin_active( $doelwit ) ) );
	}

	dkm_bs_out( 400, array( 'fout' => 'Onbekend type.' ) );
}

// ---- installeren ----

if ( 'installeer' !== $actie ) {
	dkm_bs_out( 400, array( 'fout' => 'Onbekende actie.' ) );
}

$type = isset( $_GET['type'] ) ? sanitize_key( wp_unslash( $_GET['type'] ) ) : '';

if ( 'theme' !== $type && 'plugin' !== $type ) {
	dkm_bs_out( 400, array( 'fout' => 'Geef type=theme of type=plugin op.' ) );
}

if ( 'theme' === $type && ! user_can( $gebruiker, 'install_themes' ) ) {
	dkm_bs_out( 403, array( 'fout' => 'Deze gebruiker mag geen themas installeren.' ) );
}

$body = file_get_contents( 'php://input' );

if ( '' === $body || false === $body ) {
	dkm_bs_out( 400, array( 'fout' => 'Er zat geen zip in het verzoek.' ) );
}

if ( strlen( $body ) > DKM_BOOTSTRAP_MAX_BYTES ) {
	dkm_bs_out( 413, array( 'fout' => 'Het pakket is te groot.' ) );
}

if ( 0 !== strncmp( $body, "PK\x03\x04", 4 ) ) {
	dkm_bs_out( 400, array( 'fout' => 'Dit is geen zipbestand.' ) );
}

/**
 * Leest de naam van de hoofdmap uit de zip.
 */
function dkm_bs_package_folder( string $body ): string {
	$naam = '';
	$tmp  = wp_tempnam( 'dkm-peek.zip' );

	if ( class_exists( 'ZipArchive' ) && $tmp && false !== file_put_contents( $tmp, $body ) ) {
		$zip = new ZipArchive();

		if ( true === $zip->open( $tmp ) ) {
			for ( $i = 0; $i < $zip->numFiles; $i++ ) {
				$pad = (string) $zip->getNameIndex( $i );

				if ( '' !== $pad && 0 !== strpos( $pad, '__MACOSX' ) ) {
					$naam = (string) strtok( $pad, '/' );
					break;
				}
			}

			$zip->close();
		}
	}

	if ( $tmp ) {
		@unlink( $tmp );
	}

	if ( '' === $naam && strlen( $body ) > 30 ) {
		$lengte = (int) unpack( 'v', substr( $body, 26, 2 ) )[1];
		$naam   = (string) strtok( substr( $body, 30, $lengte ), '/' );
	}

	$naam = sanitize_file_name( $naam );

	return ( '.' === $naam || '..' === $naam ) ? '' : $naam;
}

$map = dkm_bs_package_folder( $body );

if ( '' === $map ) {
	dkm_bs_out( 400, array( 'fout' => 'De zip heeft geen bruikbare hoofdmap.' ) );
}

$basis  = 'theme' === $type ? get_theme_root() : WP_PLUGIN_DIR;
$doel   = trailingslashit( $basis ) . $map;
$backup = $doel . '.dkm-terugrol';

// Een snelkoppeling volgen zou de map wissen waar hij naartoe wijst, en die
// ligt buiten WordPress.
if ( is_link( $doel ) ) {
	dkm_bs_out( 409, array( 'fout' => 'Die map is een snelkoppeling. Verwijder die eerst met de hand.' ) );
}

global $wp_filesystem;
WP_Filesystem();

if ( ! $wp_filesystem ) {
	dkm_bs_out( 500, array( 'fout' => 'Geen toegang tot het bestandssysteem.' ) );
}

$vervangen = false;

if ( $wp_filesystem->is_dir( $doel ) ) {
	$wp_filesystem->delete( $backup, true );

	if ( ! $wp_filesystem->move( $doel, $backup ) ) {
		dkm_bs_out( 500, array( 'fout' => 'De bestaande versie kon niet opzij worden gezet.' ) );
	}

	$vervangen = true;
}

/**
 * Zet de weggezette versie terug na een mislukking.
 */
function dkm_bs_restore( string $doel, string $backup, bool $vervangen ): void {
	global $wp_filesystem;

	if ( ! $vervangen || ! $wp_filesystem ) {
		return;
	}

	if ( $wp_filesystem->is_dir( $doel ) ) {
		$wp_filesystem->delete( $doel, true );
	}

	$wp_filesystem->move( $backup, $doel );
}

$tmp = wp_tempnam( 'dkm-deploy.zip' );

if ( ! $tmp || false === file_put_contents( $tmp, $body ) ) {
	dkm_bs_restore( $doel, $backup, $vervangen );
	dkm_bs_out( 500, array( 'fout' => 'Het pakket kon niet worden weggeschreven.' ) );
}

$skin     = new WP_Ajax_Upgrader_Skin();
$upgrader = 'theme' === $type ? new Theme_Upgrader( $skin ) : new Plugin_Upgrader( $skin );
$uitkomst = $upgrader->install( $tmp, array( 'overwrite_package' => true ) );

@unlink( $tmp );

$fout = null;

if ( is_wp_error( $uitkomst ) ) {
	$fout = $uitkomst->get_error_message();
} elseif ( is_wp_error( $skin->result ) ) {
	$fout = $skin->result->get_error_message();
} elseif ( false === $uitkomst || ! $wp_filesystem->is_dir( $doel ) ) {
	$meldingen = $skin->get_errors()->get_error_messages();
	$fout      = $meldingen ? implode( ' ', $meldingen ) : 'De installatie is niet gelukt.';
}

if ( $fout ) {
	dkm_bs_restore( $doel, $backup, $vervangen );
	dkm_bs_out( 500, array( 'fout' => $fout ) );
}

if ( $vervangen ) {
	$wp_filesystem->delete( $backup, true );
}

wp_clean_themes_cache();

if ( function_exists( 'wp_clean_plugins_cache' ) ) {
	wp_clean_plugins_cache();
}

dkm_bs_out(
	200,
	array(
		'ok'        => true,
		'type'      => $type,
		'map'       => $map,
		'vervangen' => $vervangen,
		'meldingen' => $skin->get_upgrade_messages(),
	)
);
