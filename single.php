<?php
/**
 * Single post
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article class="sec">
		<div class="wrap">
			<div class="sec-hd">
				<span class="eyebrow"><?php echo esc_html( get_the_date() ); ?></span>
				<h1 class="h-l"><?php the_title(); ?></h1>
			</div>
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="post__hero"><?php the_post_thumbnail( 'dkm-wide' ); ?></figure>
			<?php endif; ?>
			<div class="prose">
				<?php the_content(); ?>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();
