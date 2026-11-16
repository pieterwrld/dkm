<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>
<body <?php body_class('dkm-site'); ?>>
<?php wp_body_open(); ?>

<?php get_template_part('template-parts/announcement-bar'); ?>

<header class="site-header" id="site-header">
    <div class="header-inner">
        <div class="site-logo">
            <?php if (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo__text" rel="home">
                    De Klokjesman
                </a>
            <?php endif; ?>
        </div>

        <nav class="header-nav" aria-label="<?php esc_attr_e('Hoofdnavigatie', 'dkm'); ?>">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container'      => false,
                'items_wrap'     => '%3$s',
                'walker'         => new DKM_Nav_Walker(),
                'fallback_cb'    => false,
                'depth'          => 2,
            ));
            ?>
        </nav>

        <div class="header-actions">
            <button class="header-action-btn" type="button" data-open-search aria-label="<?php esc_attr_e('Zoeken', 'dkm'); ?>">
                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </button>

            <?php if (function_exists('WC')) : ?>
                <button class="header-action-btn" type="button" data-open-cart aria-label="<?php esc_attr_e('Winkelwagen', 'dkm'); ?>">
                    <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                    <?php $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
                    <span class="header-action-btn__count<?php echo $cart_count ? '' : ' hidden'; ?>"><?php echo esc_html($cart_count); ?></span>
                </button>
            <?php endif; ?>

            <button class="header-hamburger" type="button" aria-label="<?php esc_attr_e('Menu', 'dkm'); ?>" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
</header>

<div class="search-overlay" id="search-overlay" aria-hidden="true">
    <form class="search-overlay__inner" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <input type="hidden" name="post_type" value="product">
        <input type="search" class="search-overlay__input" placeholder="<?php esc_attr_e('Zoek horloges...', 'dkm'); ?>" autocomplete="off" name="s" value="<?php echo get_search_query(); ?>">
    </form>
    <button class="search-overlay__close" type="button" aria-label="<?php esc_attr_e('Sluiten', 'dkm'); ?>">
        <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
</div>

<div class="mobile-menu" id="mobile-menu" aria-hidden="true">
    <div class="mobile-menu__header">
        <span class="site-logo__text">De Klokjesman</span>
        <button class="mobile-menu__close" type="button" aria-label="<?php esc_attr_e('Menu sluiten', 'dkm'); ?>">
            <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
    <nav class="mobile-menu__nav" aria-label="<?php esc_attr_e('Mobiele navigatie', 'dkm'); ?>">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'container'      => false,
            'items_wrap'     => '%3$s',
            'walker'         => new DKM_Mobile_Nav_Walker(),
            'fallback_cb'    => false,
            'depth'          => 1,
        ));
        ?>
    </nav>
    <div class="mobile-menu__footer">
        <a href="<?php echo esc_url(dkm_get_whatsapp_link()); ?>" class="mobile-menu__whatsapp" target="_blank" rel="noopener">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.611.611l4.458-1.495A11.952 11.952 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0z"/></svg>
            Chat via WhatsApp
        </a>
    </div>
</div>

<main class="site-main">
