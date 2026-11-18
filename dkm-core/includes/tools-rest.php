<?php
/**
 * The data tools over the REST API
 *
 * The same five jobs as the page under Gereedschap. They live here as well so
 * the shop can be filled in from outside wp-admin, which is what keeps the
 * site maintainable without a terminal.
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

/**
 * Who may run these.
 */
function dkm_tools_may_run(): bool {
	return current_user_can( 'manage_woocommerce' ) && current_user_can( 'manage_options' );
}

add_action( 'rest_api_init', 'dkm_tools_routes' );

/**
 * Registers the routes.
 */
function dkm_tools_routes(): void {
	register_rest_route(
		'dkm/v1',
		'/tools',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'dkm_tools_rest_list',
			'permission_callback' => 'dkm_tools_may_run',
		)
	);

	register_rest_route(
		'dkm/v1',
		'/tools/run',
		array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => 'dkm_tools_rest_run',
			'permission_callback' => 'dkm_tools_may_run',
			'args'                => array(
				'taak' => array(
					'required' => true,
					'type'     => 'string',
				),
			),
		)
	);

	register_rest_route(
		'dkm/v1',
		'/winkel/zichtbaarheid',
		array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => 'dkm_winkel_zichtbaarheid_lees',
				'permission_callback' => 'dkm_tools_may_run',
			),
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => 'dkm_winkel_zichtbaarheid_zet',
				'permission_callback' => 'dkm_tools_may_run',
				'args'                => array(
					'binnenkort'      => array(
						'required' => true,
						'type'     => 'boolean',
					),
					'alleen_winkel'   => array(
						'type' => 'boolean',
					),
				),
			),
		)
	);
}

/**
 * WooCommerce has its own coming soon mode, apart from any plugin. These two
 * options are the whole of it, and nothing else here touches them.
 *
 * @return array{binnenkort: bool, alleen_winkel: bool}
 */
function dkm_winkel_zichtbaarheid_waarden(): array {
	return array(
		'binnenkort'    => 'yes' === get_option( 'woocommerce_coming_soon' ),
		'alleen_winkel' => 'yes' === get_option( 'woocommerce_store_pages_only' ),
	);
}

/**
 * Reports whether the shop is hidden from visitors.
 */
function dkm_winkel_zichtbaarheid_lees(): WP_REST_Response {
	return new WP_REST_Response( dkm_winkel_zichtbaarheid_waarden() );
}

/**
 * Opens or closes the shop to visitors.
 *
 * @param WP_REST_Request $request The request.
 */
function dkm_winkel_zichtbaarheid_zet( WP_REST_Request $request ): WP_REST_Response {
	$binnenkort = (bool) $request->get_param( 'binnenkort' );
	update_option( 'woocommerce_coming_soon', $binnenkort ? 'yes' : 'no' );

	if ( null !== $request->get_param( 'alleen_winkel' ) ) {
		update_option( 'woocommerce_store_pages_only', $request->get_param( 'alleen_winkel' ) ? 'yes' : 'no' );
	}

	return new WP_REST_Response(
		array(
			'ok'     => true,
			'nu'     => dkm_winkel_zichtbaarheid_waarden(),
		)
	);
}

/**
 * Lists the jobs that can be run.
 */
function dkm_tools_rest_list(): WP_REST_Response {
	if ( ! function_exists( 'dkm_tools_jobs' ) ) {
		require_once DKM_CORE_DIR . '/includes/admin-tools.php';
	}

	$uit = array();
	foreach ( dkm_tools_jobs() as $sleutel => $job ) {
		$uit[ $sleutel ] = array(
			'label'  => $job['label'],
			'uitleg' => $job['uitleg'],
			'soort'  => $job['soort'],
		);
	}

	return new WP_REST_Response( array( 'taken' => $uit ) );
}

/**
 * Runs one job and hands back its report.
 *
 * @param WP_REST_Request $request The request.
 */
function dkm_tools_rest_run( WP_REST_Request $request ) {
	if ( ! function_exists( 'dkm_tools_jobs' ) ) {
		require_once DKM_CORE_DIR . '/includes/admin-tools.php';
	}

	$taak = (string) $request->get_param( 'taak' );
	if ( ! isset( dkm_tools_jobs()[ $taak ] ) ) {
		return new WP_Error(
			'dkm_onbekende_taak',
			__( 'Die taak bestaat niet.', 'dkm' ),
			array( 'status' => 400 )
		);
	}

	$regels = dkm_tools_run( $taak );
	$mislukt = false;
	$uit     = array();
	foreach ( $regels as list( $niveau, $tekst ) ) {
		if ( 'fail' === $niveau ) {
			$mislukt = true;
		}
		$uit[] = array(
			'niveau' => $niveau,
			'tekst'  => $tekst,
		);
	}

	return new WP_REST_Response(
		array(
			'ok'     => ! $mislukt,
			'taak'   => $taak,
			'regels' => $uit,
		)
	);
}
