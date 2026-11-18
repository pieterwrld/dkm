<?php
/**
 * Homepage
 *
 * @package DKM
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part( 'template-parts/home/bf-banner' );
get_template_part( 'template-parts/home/usp-marquee' );
get_template_part( 'template-parts/home/quality-rows' );
get_template_part( 'template-parts/home/quality-carousel' );
get_template_part( 'template-parts/home/bestsellers' );
get_template_part( 'template-parts/home/ugc' );
get_template_part( 'template-parts/home/reviews' );
get_template_part( 'template-parts/home/anatomy' );
get_template_part( 'template-parts/home/closing' );

get_footer();
