<?php
/**
 * Fallback template: blog and archive listings
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<section class="sec">
	<div class="wrap">
		<div class="sec-hd">
			<span class="eyebrow"><?php esc_html_e( 'De Klokjesman', 'dkm' ); ?></span>
			<h1 class="h-l">
				<?php
				if ( is_search() ) {
					/* translators: %s is the search term. */
					printf( esc_html__( 'Resultaten voor %s', 'dkm' ), '<span class="em gr-txt">' . esc_html( get_search_query() ) . '</span>' );
				} elseif ( is_archive() ) {
					the_archive_title();
				} else {
					esc_html_e( 'Laatste berichten', 'dkm' );
				}
				?>
			</h1>
			<?php if ( is_archive() ) : ?>
				<?php the_archive_description( '<p class="lead">', '</p>' ); ?>
			<?php endif; ?>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="grid grid--c3">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article class="pc rv">
						<a class="pc__link" href="<?php the_permalink(); ?>">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="pc__img"><?php the_post_thumbnail( 'dkm-card', array( 'loading' => 'lazy' ) ); ?></div>
							<?php endif; ?>
							<div class="pc__b">
								<span class="pc__m"><?php echo esc_html( get_the_date() ); ?></span>
								<h2 class="pc__n"><?php the_title(); ?></h2>
							</div>
						</a>
					</article>
				<?php endwhile; ?>
			</div>

			<div class="more">
				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 1,
						'prev_text' => esc_html__( 'Vorige', 'dkm' ),
						'next_text' => esc_html__( 'Volgende', 'dkm' ),
					)
				);
				?>
			</div>
		<?php else : ?>
			<p class="empty"><?php esc_html_e( 'Niets gevonden. Probeer een andere zoekterm.', 'dkm' ); ?></p>
			<div class="more">
				<a class="btn btn--g" href="<?php echo esc_url( dkm_shop_url() ); ?>"><?php esc_html_e( 'Bekijk de collectie', 'dkm' ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
