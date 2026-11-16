<?php
/**
 * Standard page template
 *
 * @package DKM
 */

get_header();
?>

<section class="section section--page">
    <div class="container container--narrow">
        <?php
        while ( have_posts() ) :
            the_post();
        ?>
            <article id="page-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
                <header class="page-header">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                </header>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</section>

<?php
get_footer();
