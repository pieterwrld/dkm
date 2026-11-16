<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'dkm-site' ); ?>>
<?php wp_body_open(); ?>

<?php get_template_part( 'template-parts/announcement-bar' ); ?>

<header class="site-header" id="site-header">
    <div class="header-inner container">
        <div class="header-logo">
            <?php if ( has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo-text" rel="home">
                    De Klokjesman
                </a>
            <?php endif; ?>
        </div>

        <nav class="header-nav" id="header-nav" aria-label="<?php esc_attr_e( 'Hoofdnavigatie', 'dkm' ); ?>">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'nav-menu',
                'fallback_cb'    => false,
                'depth'          => 2,
            ) );
            ?>
        </nav>

        <div class="header-actions">
            <button class="header-action header-search-toggle" type="button" aria-label="<?php esc_attr_e( 'Zoeken', 'dkm' ); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
            </button>

            <?php if ( function_exists( 'WC' ) ) : ?>
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header-action header-cart" aria-label="<?php esc_attr_e( 'Winkelwagen', 'dkm' ); ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 01-8 0"/>
                    </svg>
                    <?php $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
                    <span class="cart-count<?php echo $cart_count ? ' has-items' : ''; ?>"><?php echo esc_html( $cart_count ); ?></span>
                </a>
            <?php endif; ?>

            <button class="header-action header-menu-toggle" id="menu-toggle" type="button" aria-label="<?php esc_attr_e( 'Menu', 'dkm' ); ?>" aria-expanded="false" aria-controls="mobile-menu">
                <span class="hamburger">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </span>
            </button>
        </div>
    </div>

    <div class="header-search-overlay" id="header-search-overlay" aria-hidden="true">
        <div class="container">
            <?php get_search_form(); ?>
            <button class="search-close" type="button" aria-label="<?php esc_attr_e( 'Sluiten', 'dkm' ); ?>">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
    </div>
</header>

<div class="mobile-menu" id="mobile-menu" aria-hidden="true">
    <div class="mobile-menu-inner">
        <div class="mobile-menu-header">
            <span class="mobile-menu-title">Menu</span>
            <button class="mobile-menu-close" id="mobile-menu-close" type="button" aria-label="<?php esc_attr_e( 'Menu sluiten', 'dkm' ); ?>">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <nav class="mobile-nav" aria-label="<?php esc_attr_e( 'Mobiele navigatie', 'dkm' ); ?>">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'mobile-nav-menu',
                'fallback_cb'    => false,
                'depth'          => 2,
            ) );
            ?>
        </nav>
        <?php if ( function_exists( 'WC' ) ) : ?>
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="mobile-cart-link btn btn--outline">
                Winkelwagen
                <span class="cart-count"><?php echo esc_html( WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ); ?></span>
            </a>
        <?php endif; ?>
    </div>
</div>

<main class="site-main">
