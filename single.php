<?php
/**
 * Single post template
 *
 * @package DKM
 */

get_header();
?>

<section class="section section--single">
    <div class="container container--narrow">
        <?php
        while ( have_posts() ) :
            the_post();
        ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post' ); ?>>
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="single-post__hero">
                        <?php the_post_thumbnail( 'large', array( 'class' => 'single-post__image' ) ); ?>
                    </div>
                <?php endif; ?>

                <header class="single-post__header">
                    <h1 class="single-post__title"><?php the_title(); ?></h1>
                    <div class="single-post__meta">
                        <time class="single-post__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                            <?php echo esc_html( get_the_date() ); ?>
                        </time>
                        <span class="single-post__separator">&middot;</span>
                        <span class="single-post__author"><?php the_author(); ?></span>
                    </div>
                </header>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <footer class="single-post__footer">
                    <?php
                    $tags = get_the_tags();
                    if ( $tags ) :
                    ?>
                        <div class="single-post__tags">
                            <?php foreach ( $tags as $tag ) : ?>
                                <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="tag-link">
                                    <?php echo esc_html( $tag->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </footer>
            </article>

            <nav class="post-navigation" aria-label="<?php esc_attr_e( 'Berichtnavigatie', 'dkm' ); ?>">
                <div class="post-navigation__inner">
                    <?php
                    $prev = get_previous_post();
                    $next = get_next_post();
                    ?>
                    <?php if ( $prev ) : ?>
                        <a href="<?php echo esc_url( get_permalink( $prev ) ); ?>" class="post-navigation__link post-navigation__link--prev">
                            <span class="post-navigation__label">Vorig artikel</span>
                            <span class="post-navigation__title"><?php echo esc_html( $prev->post_title ); ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if ( $next ) : ?>
                        <a href="<?php echo esc_url( get_permalink( $next ) ); ?>" class="post-navigation__link post-navigation__link--next">
                            <span class="post-navigation__label">Volgend artikel</span>
                            <span class="post-navigation__title"><?php echo esc_html( $next->post_title ); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </nav>
        <?php endwhile; ?>
    </div>
</section>

<?php
get_footer();
