<?php
/**
 * The screen where the shop is edited
 *
 * Tabs down the side, fields drawn from the schema, pictures picked from the
 * media library and lists you can add rows to. Everything lands in one stored
 * value, so there is one place to look and one place to change.
 *
 * @package DKM_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Hangs the screen in the menu, at the top rather than buried under Tools.
 */
function dkm_panel_menu(): void {
	add_menu_page(
		__( 'De Klokjesman', 'dkm' ),
		__( 'De Klokjesman', 'dkm' ),
		'edit_theme_options',
		'dkm-beheer',
		'dkm_panel_page',
		'dashicons-clock',
		3
	);

	add_submenu_page( 'dkm-beheer', __( 'Inhoud', 'dkm' ), __( 'Inhoud', 'dkm' ), 'edit_theme_options', 'dkm-beheer', 'dkm_panel_page' );

	add_submenu_page(
		'dkm-beheer',
		__( 'Video\'s', 'dkm' ),
		__( 'Video\'s', 'dkm' ),
		'edit_posts',
		'edit.php?post_type=dkm_ugc'
	);

	add_submenu_page(
		'dkm-beheer',
		__( 'Gesprekken', 'dkm' ),
		__( 'Gesprekken', 'dkm' ),
		'edit_posts',
		'edit.php?post_type=dkm_review'
	);

	add_submenu_page(
		'dkm-beheer',
		__( 'Gegevens bijwerken', 'dkm' ),
		__( 'Gegevens bijwerken', 'dkm' ),
		'manage_woocommerce',
		'tools.php?page=dkm-gereedschap'
	);
}
add_action( 'admin_menu', 'dkm_panel_menu' );

/**
 * The tabs, with the levels filled in from the theme.
 *
 * @return array
 */
function dkm_panel_tabs(): array {
	$schema = dkm_settings_schema();

	if ( isset( $schema['kwaliteiten'] ) ) {
		$schema['kwaliteiten']['groepen'] = dkm_settings_tier_groups();
	}

	return $schema;
}

/**
 * Loads the media picker and this screen's own script.
 *
 * @param string $hook Current admin page.
 */
function dkm_panel_assets( string $hook ): void {
	if ( 'toplevel_page_dkm-beheer' !== $hook ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_style( 'dkm-panel', DKM_CORE_URL . 'assets/panel.css', array(), DKM_CORE_VERSION );
	wp_enqueue_script( 'dkm-panel', DKM_CORE_URL . 'assets/panel.js', array(), DKM_CORE_VERSION, true );
	wp_localize_script(
		'dkm-panel',
		'dkmPanel',
		array(
			'kies'      => __( 'Kies een afbeelding', 'dkm' ),
			'gebruik'   => __( 'Gebruik deze', 'dkm' ),
			'verwijder' => __( 'Rij verwijderen', 'dkm' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'dkm_panel_assets' );

/**
 * Draws the screen.
 */
function dkm_panel_page(): void {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'Je hebt geen rechten voor deze pagina.', 'dkm' ) );
	}

	$tabs    = dkm_panel_tabs();
	$actief  = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'home'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$actief  = isset( $tabs[ $actief ] ) ? $actief : 'home';
	$bewaard = false;

	if ( isset( $_POST['dkm_panel_save'] ) ) {
		check_admin_referer( 'dkm-panel' );
		dkm_panel_save( $tabs[ $actief ] );
		$bewaard = true;
	}

	$waarden = dkm_settings_all();
	?>
	<div class="wrap dkmp">
		<h1 class="dkmp__h"><?php esc_html_e( 'De Klokjesman', 'dkm' ); ?></h1>
		<p class="dkmp__lead"><?php esc_html_e( 'Alles wat er op de site staat, op één plek. Wat je hier leeg laat, houdt de tekst die het thema zelf meebrengt.', 'dkm' ); ?></p>

		<?php if ( $bewaard ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Opgeslagen. De site is bijgewerkt.', 'dkm' ); ?></p></div>
		<?php endif; ?>

		<nav class="nav-tab-wrapper dkmp__tabs">
			<?php foreach ( $tabs as $sleutel => $tab ) : ?>
				<a class="nav-tab<?php echo $sleutel === $actief ? ' nav-tab-active' : ''; ?>"
					href="<?php echo esc_url( admin_url( 'admin.php?page=dkm-beheer&tab=' . $sleutel ) ); ?>">
					<?php echo esc_html( $tab['titel'] ); ?>
				</a>
			<?php endforeach; ?>
		</nav>

		<?php if ( ! empty( $tabs[ $actief ]['uitleg'] ) ) : ?>
			<p class="dkmp__uitleg"><?php echo esc_html( $tabs[ $actief ]['uitleg'] ); ?></p>
		<?php endif; ?>

		<form method="post" action="">
			<?php wp_nonce_field( 'dkm-panel' ); ?>

			<?php foreach ( $tabs[ $actief ]['groepen'] as $groep ) : ?>
				<section class="dkmp__groep">
					<h2><?php echo esc_html( $groep['titel'] ); ?></h2>
					<?php foreach ( $groep['velden'] as $naam => $veld ) : ?>
						<?php dkm_panel_field( $naam, $veld, $waarden[ $naam ] ?? '' ); ?>
					<?php endforeach; ?>
				</section>
			<?php endforeach; ?>

			<?php if ( 'kwaliteiten' === $actief && ! $tabs[ $actief ]['groepen'] ) : ?>
				<p><?php esc_html_e( 'De niveaus komen uit het thema en zijn hier pas te zien als het DKM-thema actief is.', 'dkm' ); ?></p>
			<?php endif; ?>

			<p class="dkmp__save">
				<button type="submit" name="dkm_panel_save" value="1" class="button button-primary button-hero">
					<?php esc_html_e( 'Opslaan', 'dkm' ); ?>
				</button>
			</p>
		</form>
	</div>
	<?php
}

/**
 * One field.
 *
 * @param string $naam   Field name.
 * @param array  $veld   Its definition.
 * @param mixed  $waarde Its current value.
 */
function dkm_panel_field( string $naam, array $veld, $waarde ): void {
	$id     = 'dkm-' . $naam;
	$soort  = $veld['type'];
	$label  = $veld['label'];
	$uitleg = $veld['uitleg'] ?? '';

	echo '<div class="dkmp__veld dkmp__veld--' . esc_attr( $soort ) . '">';

	if ( 'bool' !== $soort ) {
		printf( '<label class="dkmp__label" for="%s">%s</label>', esc_attr( $id ), esc_html( $label ) );
	}

	switch ( $soort ) {
		case 'textarea':
			printf(
				'<textarea id="%s" name="dkm[%s]" rows="3" class="large-text">%s</textarea>',
				esc_attr( $id ),
				esc_attr( $naam ),
				esc_textarea( (string) $waarde )
			);
			break;

		case 'bool':
			printf(
				'<label class="dkmp__check"><input type="checkbox" id="%s" name="dkm[%s]" value="1" %s> %s</label>',
				esc_attr( $id ),
				esc_attr( $naam ),
				checked( (bool) $waarde, true, false ),
				esc_html( $label )
			);
			break;

		case 'number':
			printf(
				'<input type="number" id="%s" name="dkm[%s]" value="%s" class="small-text" step="1">',
				esc_attr( $id ),
				esc_attr( $naam ),
				esc_attr( (string) $waarde )
			);
			break;

		case 'date':
			printf(
				'<input type="date" id="%s" name="dkm[%s]" value="%s">',
				esc_attr( $id ),
				esc_attr( $naam ),
				esc_attr( (string) $waarde )
			);
			break;

		case 'icon':
			dkm_panel_icon_select( $id, 'dkm[' . $naam . ']', (string) $waarde );
			break;

		case 'image':
			dkm_panel_image( $id, 'dkm[' . $naam . ']', (int) $waarde );
			break;

		case 'repeater':
			dkm_panel_repeater( $naam, $veld, is_array( $waarde ) ? $waarde : array() );
			break;

		default:
			printf(
				'<input type="text" id="%s" name="dkm[%s]" value="%s" class="regular-text">',
				esc_attr( $id ),
				esc_attr( $naam ),
				esc_attr( (string) $waarde )
			);
	}

	if ( '' !== $uitleg ) {
		printf( '<span class="dkmp__uitleg-klein">%s</span>', esc_html( $uitleg ) );
	}

	echo '</div>';
}

/**
 * A picture, with a preview and a button that opens the library.
 *
 * @param string $id    Element id.
 * @param string $naam  Field name.
 * @param int    $bijl  Attachment id.
 */
function dkm_panel_image( string $id, string $naam, int $bijl ): void {
	$src = $bijl ? wp_get_attachment_image_url( $bijl, 'medium' ) : '';
	?>
	<div class="dkmp__img" data-dkm-image>
		<div class="dkmp__img-prev">
			<?php if ( $src ) : ?>
				<img src="<?php echo esc_url( $src ); ?>" alt="">
			<?php endif; ?>
		</div>
		<input type="hidden" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $naam ); ?>" value="<?php echo esc_attr( (string) $bijl ); ?>" data-dkm-image-value>
		<button type="button" class="button" data-dkm-image-pick><?php esc_html_e( 'Kies afbeelding', 'dkm' ); ?></button>
		<button type="button" class="button-link dkmp__weg" data-dkm-image-clear<?php echo $bijl ? '' : ' hidden'; ?>><?php esc_html_e( 'Weghalen', 'dkm' ); ?></button>
	</div>
	<?php
}

/**
 * One of the theme's own icons.
 *
 * @param string $id     Element id.
 * @param string $naam   Field name.
 * @param string $waarde Current icon name.
 */
function dkm_panel_icon_select( string $id, string $naam, string $waarde ): void {
	$iconen = function_exists( 'dkm_icon_choices' ) ? array_keys( dkm_icon_choices() ) : array();

	if ( ! $iconen ) {
		printf(
			'<input type="text" id="%s" name="%s" value="%s" class="regular-text">',
			esc_attr( $id ),
			esc_attr( $naam ),
			esc_attr( $waarde )
		);
		return;
	}

	printf( '<select id="%s" name="%s">', esc_attr( $id ), esc_attr( $naam ) );
	foreach ( $iconen as $icoon ) {
		printf(
			'<option value="%s" %s>%s</option>',
			esc_attr( $icoon ),
			selected( $waarde, $icoon, false ),
			esc_html( $icoon )
		);
	}
	echo '</select>';
}

/**
 * A list you can add rows to.
 *
 * @param string $naam   Field name.
 * @param array  $veld   Its definition.
 * @param array  $rijen  Current rows.
 */
function dkm_panel_repeater( string $naam, array $veld, array $rijen ): void {
	?>
	<div class="dkmp__rep" data-dkm-rep data-dkm-rep-name="<?php echo esc_attr( $naam ); ?>">
		<div class="dkmp__rep-rijen" data-dkm-rep-rows>
			<?php foreach ( array_values( $rijen ) as $i => $rij ) : ?>
				<?php dkm_panel_repeater_row( $naam, $veld['velden'], (array) $rij, (int) $i ); ?>
			<?php endforeach; ?>
		</div>

		<template data-dkm-rep-template>
			<?php dkm_panel_repeater_row( $naam, $veld['velden'], array(), -1 ); ?>
		</template>

		<button type="button" class="button" data-dkm-rep-add><?php esc_html_e( 'Rij toevoegen', 'dkm' ); ?></button>
	</div>
	<?php
}

/**
 * One row of such a list.
 *
 * @param string $naam   Field name.
 * @param array  $velden Columns.
 * @param array  $rij    Current values.
 * @param int    $i      Row number, or -1 for the blank template.
 */
function dkm_panel_repeater_row( string $naam, array $velden, array $rij, int $i ): void {
	$index = $i < 0 ? '__i__' : (string) $i;
	?>
	<div class="dkmp__rep-rij" data-dkm-rep-row>
		<span class="dkmp__greep" aria-hidden="true">⋮⋮</span>
		<?php foreach ( $velden as $kolom => $kolomveld ) : ?>
			<?php
			$veldnaam = sprintf( 'dkm[%s][%s][%s]', $naam, $index, $kolom );
			$waarde   = (string) ( $rij[ $kolom ] ?? '' );
			?>
			<label class="dkmp__rep-kolom dkmp__rep-kolom--<?php echo esc_attr( $kolomveld['type'] ); ?>">
				<span><?php echo esc_html( $kolomveld['label'] ); ?></span>
				<?php if ( 'textarea' === $kolomveld['type'] ) : ?>
					<textarea name="<?php echo esc_attr( $veldnaam ); ?>" rows="2"><?php echo esc_textarea( $waarde ); ?></textarea>
				<?php elseif ( 'icon' === $kolomveld['type'] ) : ?>
					<?php dkm_panel_icon_select( '', $veldnaam, $waarde ); ?>
				<?php else : ?>
					<input type="text" name="<?php echo esc_attr( $veldnaam ); ?>" value="<?php echo esc_attr( $waarde ); ?>">
				<?php endif; ?>
			</label>
		<?php endforeach; ?>
		<button type="button" class="button-link dkmp__weg" data-dkm-rep-remove><?php esc_html_e( 'Weg', 'dkm' ); ?></button>
	</div>
	<?php
}

/**
 * Writes what was sent, for the fields on this tab only.
 *
 * Every other tab is left exactly as it was, so saving one screen cannot wipe
 * another.
 *
 * @param array $tab The tab that was open.
 */
function dkm_panel_save( array $tab ): void {
	$binnen  = isset( $_POST['dkm'] ) ? wp_unslash( $_POST['dkm'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$binnen  = is_array( $binnen ) ? $binnen : array();
	$bewaard = get_option( DKM_SETTINGS_OPTION, array() );
	$bewaard = is_array( $bewaard ) ? $bewaard : array();

	foreach ( $tab['groepen'] as $groep ) {
		foreach ( $groep['velden'] as $naam => $veld ) {
			switch ( $veld['type'] ) {
				case 'bool':
					$bewaard[ $naam ] = ! empty( $binnen[ $naam ] );
					break;

				case 'number':
					$bewaard[ $naam ] = isset( $binnen[ $naam ] ) ? (int) $binnen[ $naam ] : 0;
					break;

				case 'image':
					$bewaard[ $naam ] = isset( $binnen[ $naam ] ) ? (int) $binnen[ $naam ] : 0;
					break;

				case 'textarea':
					$bewaard[ $naam ] = isset( $binnen[ $naam ] ) ? sanitize_textarea_field( (string) $binnen[ $naam ] ) : '';
					break;

				case 'repeater':
					$rijen = array();
					foreach ( (array) ( $binnen[ $naam ] ?? array() ) as $rij ) {
						$schoon = array();
						$leeg   = true;
						foreach ( $veld['velden'] as $kolom => $kolomveld ) {
							$ruw = (string) ( $rij[ $kolom ] ?? '' );
							$ruw = 'textarea' === $kolomveld['type']
								? sanitize_textarea_field( $ruw )
								: sanitize_text_field( $ruw );
							$schoon[ $kolom ] = $ruw;
							if ( '' !== $ruw ) {
								$leeg = false;
							}
						}
						if ( ! $leeg ) {
							$rijen[] = $schoon;
						}
					}
					$bewaard[ $naam ] = $rijen;
					break;

				default:
					$bewaard[ $naam ] = isset( $binnen[ $naam ] ) ? sanitize_text_field( (string) $binnen[ $naam ] ) : '';
			}
		}
	}

	update_option( DKM_SETTINGS_OPTION, $bewaard );

	// The level pictures are also the category pictures, so the shop keeps up.
	if ( function_exists( 'dkm_tiers' ) ) {
		foreach ( array_keys( dkm_tiers() ) as $slug ) {
			$foto = (int) ( $bewaard[ 'tier_' . $slug . '_row' ] ?? 0 );
			if ( ! $foto ) {
				continue;
			}
			$term = get_term_by( 'slug', $slug, 'product_cat' );
			if ( $term && ! is_wp_error( $term ) ) {
				update_term_meta( $term->term_id, 'thumbnail_id', $foto );
			}
		}
	}
}
