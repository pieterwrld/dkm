<?php
/**
 * Page template
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
				<h1 class="h-l"><?php the_title(); ?></h1>
			</div>
			<div class="prose">
				<?php the_content(); ?>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();
