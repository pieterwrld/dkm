<?php
/**
 * Deployroutes.
 *
 * Hiermee kan een beheerder op afstand een thema of plugin uit een zip zetten
 * en activeren, zonder SSH en zonder FTP. Het is dezelfde handeling als
 * uploaden in wp-admin, met dezelfde rechtencontrole: wie het daar niet mag,
 * mag het hier ook niet. Er zit geen sleutel in de URL, want die lekt via
 * serverlogs en browsergeschiedenis.
 *
 * Een bestaande map gaat eerst opzij en komt terug als de installatie mislukt,
 * zodat de winkel nooit zonder thema achterblijft.
 *
 * Zet DKM_DISABLE_DEPLOY op true in wp-config.php om alles hier uit te zetten.
 *
 * @package DKM_Core
 */

defined( 'ABSPATH' ) || exit;

const DKM_DEPLOY_MAX_BYTES = 33554432; // 32 MB.

add_action( 'rest_api_init', 'dkm_deploy_register_routes' );

/**
 * Hangt de routes onder dkm/v1.
 */
function dkm_deploy_register_routes(): void {
	if ( defined( 'DKM_DISABLE_DEPLOY' ) && DKM_DISABLE_DEPLOY ) {
		return;
	}

	$types = array(
		'required' => true,
		'type'     => 'string',
		'enum'     => array( 'theme', 'plugin' ),
	);

	register_rest_route(
		'dkm/v1',
		'/deploy/status',
		array(
			'methods'             => 'GET',
			'callback'            => 'dkm_deploy_status',
			'permission_callback' => 'dkm_deploy_may_read',
		)
	);

	register_rest_route(
		'dkm/v1',
		'/deploy/package',
		array(
			'methods'             => 'POST',
			'callback'            => 'dkm_deploy_package',
			'permission_callback' => 'dkm_deploy_may_read',
			'args'                => array( 'type' => $types ),
		)
	);

	register_rest_route(
		'dkm/v1',
		'/deploy/activate',
		array(
			'methods'             => 'POST',
			'callback'            => 'dkm_deploy_activate',
			'permission_callback' => 'dkm_deploy_may_read',
			'args'                => array(
				'type'   => $types,
				'target' => array(
					'required' => true,
					'type'     => 'string',
				),
			),
		)
	);
}

/**
 * Alleen een beheerder komt hier langs. Per handeling wordt daarna nog het
 * specifieke recht gecontroleerd.
 */
function dkm_deploy_may_read(): bool {
	return current_user_can( 'switch_themes' ) && current_user_can( 'manage_options' );
}

/**
 * Laadt de installatiecode van WordPress zelf. Die staat in wp-admin en komt
 * bij een REST-verzoek niet vanzelf mee.
 */
function dkm_deploy_load_upgrader(): void {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/misc.php';
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	require_once ABSPATH . 'wp-admin/includes/theme.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php';
}

/**
 * Vertelt wat er draait, zodat een wijziging achteraf te controleren is.
 */
function dkm_deploy_status( WP_REST_Request $request ): WP_REST_Response {
	dkm_deploy_load_upgrader();

	$theme   = wp_get_theme();
	$plugins = array();

	foreach ( get_plugins() as $file => $data ) {
		$plugins[ $file ] = array(
			'naam'   => $data['Name'],
			'versie' => $data['Version'],
			'actief' => is_plugin_active( $file ),
		);
	}

	return new WP_REST_Response(
		array(
			'wp'           => get_bloginfo( 'version' ),
			'php'          => PHP_VERSION,
			'actief_thema' => array(
				'stylesheet' => $theme->get_stylesheet(),
				'naam'       => $theme->get( 'Name' ),
				'versie'     => $theme->get( 'Version' ),
			),
			'themas'       => array_keys( wp_get_themes() ),
			'plugins'      => $plugins,
			'dkm_core'     => DKM_CORE_VERSION,
			'schrijfbaar'  => wp_is_writable( get_theme_root() ),
		),
		200
	);
}

/**
 * Neemt een zip in de body van het verzoek en zet die neer als thema of
 * plugin. Een bestaande map met dezelfde naam wordt vervangen.
 */
function dkm_deploy_package( WP_REST_Request $request ) {
	$type = $request->get_param( 'type' );
	$cap  = 'theme' === $type ? 'install_themes' : 'install_plugins';

	if ( ! current_user_can( $cap ) ) {
		return new WP_Error( 'dkm_forbidden', __( 'Je mag dit soort pakket niet installeren.', 'dkm' ), array( 'status' => 403 ) );
	}

	if ( defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS ) {
		return new WP_Error( 'dkm_file_mods', __( 'Deze site staat geen bestandswijzigingen toe.', 'dkm' ), array( 'status' => 409 ) );
	}

	$body = $request->get_body();

	if ( '' === $body ) {
		return new WP_Error( 'dkm_empty', __( 'Er zat geen zip in het verzoek.', 'dkm' ), array( 'status' => 400 ) );
	}

	if ( strlen( $body ) > DKM_DEPLOY_MAX_BYTES ) {
		return new WP_Error( 'dkm_too_large', __( 'Het pakket is te groot.', 'dkm' ), array( 'status' => 413 ) );
	}

	// Een zip begint altijd met deze vier bytes, dus er komt niets anders door.
	if ( 0 !== strncmp( $body, "PK\x03\x04", 4 ) ) {
		return new WP_Error( 'dkm_not_a_zip', __( 'Dit is geen zipbestand.', 'dkm' ), array( 'status' => 400 ) );
	}

	dkm_deploy_load_upgrader();

	$map = dkm_deploy_package_folder( $body );

	if ( '' === $map ) {
		return new WP_Error( 'dkm_bad_zip', __( 'De zip heeft geen bruikbare hoofdmap.', 'dkm' ), array( 'status' => 400 ) );
	}

	$basis  = 'theme' === $type ? get_theme_root() : WP_PLUGIN_DIR;
	$doel   = trailingslashit( $basis ) . $map;
	$backup = $doel . '.dkm-terugrol';

	// Een symlink volgen zou de map wissen waar hij naartoe wijst, en die ligt
	// buiten WordPress. Dat gebeurt hier niet.
	if ( is_link( $doel ) ) {
		return new WP_Error(
			'dkm_symlink',
			__( 'Die map is een snelkoppeling. Verwijder die eerst met de hand.', 'dkm' ),
			array( 'status' => 409 )
		);
	}

	global $wp_filesystem;
	WP_Filesystem();

	if ( ! $wp_filesystem ) {
		return new WP_Error( 'dkm_fs', __( 'Geen toegang tot het bestandssysteem.', 'dkm' ), array( 'status' => 500 ) );
	}

	$vervangen = false;

	if ( $wp_filesystem->is_dir( $doel ) ) {
		$wp_filesystem->delete( $backup, true );

		if ( ! $wp_filesystem->move( $doel, $backup ) ) {
			return new WP_Error( 'dkm_backup', __( 'De bestaande versie kon niet opzij worden gezet.', 'dkm' ), array( 'status' => 500 ) );
		}

		$vervangen = true;
	}

	$tmp = wp_tempnam( 'dkm-deploy.zip' );

	if ( ! $tmp || false === file_put_contents( $tmp, $body ) ) { // phpcs:ignore WordPress.WP.AlternativeFunctions
		dkm_deploy_restore( $doel, $backup, $vervangen );
		return new WP_Error( 'dkm_tmp', __( 'Het pakket kon niet worden weggeschreven.', 'dkm' ), array( 'status' => 500 ) );
	}

	$skin     = new WP_Ajax_Upgrader_Skin();
	$upgrader = 'theme' === $type ? new Theme_Upgrader( $skin ) : new Plugin_Upgrader( $skin );
	$result   = $upgrader->install( $tmp, array( 'overwrite_package' => true ) );

	wp_delete_file( $tmp );

	$fout = null;

	if ( is_wp_error( $result ) ) {
		$fout = $result->get_error_message();
	} elseif ( is_wp_error( $skin->result ) ) {
		$fout = $skin->result->get_error_message();
	} elseif ( false === $result || ! $wp_filesystem->is_dir( $doel ) ) {
		$meldingen = $skin->get_errors()->get_error_messages();
		$fout      = $meldingen ? implode( ' ', $meldingen ) : __( 'De installatie is niet gelukt.', 'dkm' );
	}

	if ( $fout ) {
		dkm_deploy_restore( $doel, $backup, $vervangen );
		return new WP_Error( 'dkm_install_failed', $fout, array( 'status' => 500 ) );
	}

	if ( $vervangen ) {
		$wp_filesystem->delete( $backup, true );
	}

	wp_clean_themes_cache();

	if ( function_exists( 'wp_clean_plugins_cache' ) ) {
		wp_clean_plugins_cache();
	}

	return new WP_REST_Response(
		array(
			'ok'        => true,
			'type'      => $type,
			'map'       => $map,
			'vervangen' => $vervangen,
			'meldingen' => $skin->get_upgrade_messages(),
		),
		200
	);
}

/**
 * Zet de weggezette versie terug nadat een installatie is mislukt.
 */
function dkm_deploy_restore( string $doel, string $backup, bool $vervangen ): void {
	global $wp_filesystem;

	if ( ! $vervangen || ! $wp_filesystem ) {
		return;
	}

	if ( $wp_filesystem->is_dir( $doel ) ) {
		$wp_filesystem->delete( $doel, true );
	}

	$wp_filesystem->move( $backup, $doel );
}

/**
 * Leest de naam van de hoofdmap uit de zip, zodat vooraf bekend is welke map
 * straks wordt vervangen.
 */
function dkm_deploy_package_folder( string $body ): string {
	$naam = '';

	if ( class_exists( 'ZipArchive' ) ) {
		$tmp = wp_tempnam( 'dkm-peek.zip' );

		if ( $tmp && false !== file_put_contents( $tmp, $body ) ) { // phpcs:ignore WordPress.WP.AlternativeFunctions
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

			wp_delete_file( $tmp );
		}
	}

	if ( '' === $naam && strlen( $body ) > 30 ) {
		// Terugval: de naam van het eerste bestand staat vanaf byte 30.
		$lengte = (int) unpack( 'v', substr( $body, 26, 2 ) )[1];
		$naam   = (string) strtok( substr( $body, 30, $lengte ), '/' );
	}

	$naam = sanitize_file_name( $naam );

	return ( '.' === $naam || '..' === $naam ) ? '' : $naam;
}

/**
 * Zet een thema aan, of een plugin.
 */
function dkm_deploy_activate( WP_REST_Request $request ) {
	$type   = $request->get_param( 'type' );
	$target = $request->get_param( 'target' );

	dkm_deploy_load_upgrader();

	if ( 'theme' === $type ) {
		if ( ! current_user_can( 'switch_themes' ) ) {
			return new WP_Error( 'dkm_forbidden', __( 'Je mag het thema niet wisselen.', 'dkm' ), array( 'status' => 403 ) );
		}

		$theme = wp_get_theme( $target );

		if ( ! $theme->exists() ) {
			return new WP_Error( 'dkm_no_theme', __( 'Dat thema staat niet op de server.', 'dkm' ), array( 'status' => 404 ) );
		}

		$kapot = $theme->errors();

		if ( $kapot ) {
			return new WP_Error( 'dkm_broken_theme', $kapot->get_error_message(), array( 'status' => 409 ) );
		}

		$vorige = wp_get_theme()->get_stylesheet();
		switch_theme( $theme->get_stylesheet() );

		return new WP_REST_Response(
			array(
				'ok'     => true,
				'vorige' => $vorige,
				'actief' => wp_get_theme()->get_stylesheet(),
			),
			200
		);
	}

	if ( ! current_user_can( 'activate_plugins' ) ) {
		return new WP_Error( 'dkm_forbidden', __( 'Je mag geen plugins activeren.', 'dkm' ), array( 'status' => 403 ) );
	}

	$result = activate_plugin( $target );

	if ( is_wp_error( $result ) ) {
		return new WP_Error( 'dkm_activate_failed', $result->get_error_message(), array( 'status' => 500 ) );
	}

	return new WP_REST_Response(
		array(
			'ok'     => true,
			'plugin' => $target,
			'actief' => is_plugin_active( $target ),
		),
		200
	);
}
