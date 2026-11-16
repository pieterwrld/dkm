<?php
/**
 * Custom search form
 *
 * @package DKM
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label class="search-form__label" for="search-input">
        <span class="screen-reader-text"><?php esc_html_e( 'Zoeken naar:', 'dkm' ); ?></span>
    </label>
    <div class="search-form__inner">
        <svg class="search-form__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="11" cy="11" r="8"/>
            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="search" id="search-input" class="search-form__input" placeholder="<?php esc_attr_e( 'Zoek horloges...', 'dkm' ); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off">
        <input type="hidden" name="post_type" value="product">
        <button type="submit" class="search-form__submit" aria-label="<?php esc_attr_e( 'Zoeken', 'dkm' ); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
            </svg>
        </button>
    </div>
</form>
