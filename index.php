<?php
/**
 * Default template fallback
 *
 * @package DKM
 */

get_header();
?>

<section class="page-content" style="padding-top: calc(var(--header-height) + var(--announcement-height) + var(--space-3xl));">
    <div class="container">

        <?php if ( have_posts() ) : ?>

            <?php if ( is_home() && ! is_front_page() ) : ?>
                <header class="page-header">
                    <h1 class="page-header__title"><?php single_post_title(); ?></h1>
                </header>
            <?php endif; ?>

            <div class="posts-grid">
                <?php
                while ( have_posts() ) :
                    the_post();
                ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>" class="post-card__image">
                                <?php the_post_thumbnail( 'medium_large' ); ?>
                            </a>
                        <?php endif; ?>
                        <div class="post-card__content">
                            <h2 class="post-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <div class="post-card__excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="btn btn--ghost">Lees meer</a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php
            the_posts_pagination( array(
                'prev_text' => '&laquo;',
                'next_text' => '&raquo;',
            ) );
            ?>

        <?php else : ?>
            <div class="no-results">
                <h1>Niets gevonden</h1>
                <p>Probeer een andere zoekterm.</p>
                <?php get_search_form(); ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php
get_footer();
