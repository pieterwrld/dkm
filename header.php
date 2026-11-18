<?php
/**
 * Header: promo bar, ticker, floating pill, mega menu and mobile drawer
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Naar de inhoud', 'dkm' ); ?></a>

<?php
get_template_part( 'template-parts/promo-bar' );
get_template_part( 'template-parts/ticker' );
?>

<header class="hd" id="hd">
	<div class="hd__pill">
		<?php echo dkm_logo_link( array( 'class' => 'hd__logo', 'size' => '23px' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

		<nav class="nav" aria-label="<?php esc_attr_e( 'Hoofdmenu', 'dkm' ); ?>">
			<button type="button"
				class="nav__btn nav__btn--mega"
				id="mega-toggle"
				aria-expanded="false"
				aria-controls="mega">
				<?php esc_html_e( 'Collectie', 'dkm' ); ?>
				<?php dkm_the_icon( 'arrow', 'nav__chev' ); ?>
			</button>

			<?php
			$nav_items = dkm_nav_items();
			foreach ( array_slice( $nav_items, 0, 4 ) as $item ) :
				?>
				<a class="nav__btn" href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
			<?php endforeach; ?>

			<a class="nav__btn" href="<?php echo esc_url( dkm_info_page_url() ); ?>"><?php esc_html_e( 'Informatie', 'dkm' ); ?></a>

			<?php
			$sale = get_term_by( 'slug', 'sale', 'product_cat' );
			if ( $sale && ! is_wp_error( $sale ) ) :
				?>
				<a class="nav__btn nav__btn--red" href="<?php echo esc_url( (string) get_term_link( $sale ) ); ?>">
					<?php echo esc_html( dkm_black_friday_active() ? dkm_opt( 'bf_label', __( 'Black Friday', 'dkm' ) ) : __( 'Sale', 'dkm' ) ); ?>
				</a>
			<?php endif; ?>
		</nav>

		<div class="hd__act">
			<button type="button" class="iconbtn" id="search-toggle" aria-expanded="false" aria-controls="search-panel">
				<?php dkm_the_icon( 'search', '', __( 'Zoeken', 'dkm' ) ); ?>
			</button>

			<?php if ( function_exists( 'WC' ) ) : ?>
				<button type="button" class="iconbtn" id="cart-toggle" aria-expanded="false" aria-controls="cart-drawer">
					<?php dkm_the_icon( 'cart', '', __( 'Winkelwagen', 'dkm' ) ); ?>
					<b class="iconbtn__count" data-dkm-cart-count><?php echo esc_html( (string) ( WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ) ); ?></b>
				</button>
			<?php endif; ?>

			<button type="button" class="burger" id="burger" aria-expanded="false" aria-controls="mobile-drawer">
				<span></span>
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'dkm' ); ?></span>
			</button>
		</div>
	</div>
</header>

<div class="scrim" id="scrim" hidden></div>

<?php
get_template_part( 'template-parts/mega-menu' );
get_template_part( 'template-parts/search-panel' );
get_template_part( 'template-parts/mobile-drawer' );
?>

<main id="main" class="site-main">
