<?php
/**
 * Generic page template.
 *
 * The TYPO3 page body is composed of ContentBlocks elements
 * (ContentBlocks/ContentElements/*: text, image, textmedia, table,
 * text-image-mask, quote-image-text-mask, small-cta-image, header-block,
 * headear-slider, …). Each one is pure Fluid markup with no controller
 * logic, so on the WordPress side they map conceptually onto the Gutenberg
 * core blocks (paragraph, image, media-text, columns, table, cover) that
 * editors already compose pages from — see README.md "Content elements"
 * for the full mapping table. `the_content()` therefore renders the
 * block editor output directly; the wrapper classes below reproduce the
 * typography rules from Resources/Private/ContentElements/Templates/Text.html
 * so that ported CSS applies with no changes.
 *
 * @package BdP_Scouting
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( '3xl:px-25 relative mx-auto w-full px-3 py-10 md:px-5 lg:px-10 xl:px-20' ); ?>>
		<h1 class="text-4xl font-bold md:text-5xl mb-8"><?php the_title(); ?></h1>
		<div class="text-md 2xl:text-lg xl:pt-4 [&_p]:mt-4 [&_p]:first:mt-0 xl:[&_p]:mt-6 [&_ul]:list-disc [&_ul]:pl-6 [&_ol]:list-decimal [&_ol]:pl-6">
			<?php the_content(); ?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
