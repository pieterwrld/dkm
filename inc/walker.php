<?php
/**
 * Custom nav menu walker for DKM header navigation
 *
 * @package DKM
 */

defined('ABSPATH') || exit;

class DKM_Nav_Walker extends Walker_Nav_Menu {

    public function start_lvl( &$output, $depth = 0, $args = null ): void {
        $output .= '<div class="header-nav__dropdown">';
    }

    public function end_lvl( &$output, $depth = 0, $args = null ): void {
        $output .= '</div>';
    }

    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ): void {
        $classes = [];

        if ( $depth === 0 ) {
            $classes[] = 'header-nav__item';
            if ( in_array( 'current-menu-item', $item->classes, true ) || in_array( 'current-menu-parent', $item->classes, true ) ) {
                $classes[] = 'header-nav__item--current';
            }
            if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
                $classes[] = 'header-nav__item--has-children';
            }
        }

        if ( ! empty( $classes ) ) {
            $output .= '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">';
        }

        $link_classes = $depth === 0 ? 'header-nav__link' : '';
        if ( in_array( 'current-menu-item', $item->classes, true ) ) {
            $link_classes .= ' header-nav__link--active';
        }

        $title_lower = strtolower( $item->title );
        if ( str_contains( $title_lower, 'sale' ) || str_contains( $title_lower, 'aanbieding' ) ) {
            $link_classes .= ' header-nav__link--sale';
        }

        $output .= sprintf(
            '<a href="%s" class="%s">%s</a>',
            esc_url( $item->url ),
            esc_attr( trim( $link_classes ) ),
            esc_html( $item->title )
        );
    }

    public function end_el( &$output, $item, $depth = 0, $args = null ): void {
        if ( $depth === 0 ) {
            $output .= '</div>';
        }
    }
}

class DKM_Mobile_Nav_Walker extends Walker_Nav_Menu {

    public function start_lvl( &$output, $depth = 0, $args = null ): void {
        $output .= '<div class="mobile-menu__submenu">';
    }

    public function end_lvl( &$output, $depth = 0, $args = null ): void {
        $output .= '</div>';
    }

    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ): void {
        $classes = 'mobile-menu__link';

        $title_lower = strtolower( $item->title );
        if ( str_contains( $title_lower, 'sale' ) || str_contains( $title_lower, 'aanbieding' ) ) {
            $classes .= ' mobile-menu__link--sale';
        }

        $output .= sprintf(
            '<a href="%s" class="%s">%s</a>',
            esc_url( $item->url ),
            esc_attr( $classes ),
            esc_html( $item->title )
        );
    }

    public function end_el( &$output, $item, $depth = 0, $args = null ): void {
    }
}
