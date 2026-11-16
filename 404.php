<?php
/**
 * 404 page template
 *
 * @package DKM
 */

get_header();
?>

<section class="section section--404">
    <div class="container">
        <div class="error-page">
            <div class="error-page__number" aria-hidden="true">404</div>
            <h1 class="error-page__title">Pagina niet gevonden</h1>
            <p class="error-page__description">Deze pagina bestaat niet meer of is verplaatst.</p>
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="btn btn--gold">
                Terug naar de shop
            </a>
        </div>
    </div>
</section>

<?php
get_footer();
