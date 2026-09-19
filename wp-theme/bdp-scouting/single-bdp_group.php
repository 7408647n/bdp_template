<?php
/**
 * Single group / team member detail.
 *
 * @package BdP_Scouting
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( '3xl:px-25 relative mx-auto w-full px-3 py-10 md:px-5 lg:px-10 xl:px-20' ); ?>>
		<div class="flex flex-wrap -mx-3 xl:-mx-6">
			<?php get_template_part( 'template-parts/address/team-item' ); ?>
		</div>
		<div class="mt-8 text-md 2xl:text-lg [&_p]:mt-4 [&_p]:first:mt-0">
			<?php the_content(); ?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
