<?php
/**
 * News list (blog index / archives / search).
 *
 * Ported from Resources/Private/News/Templates/News/List.html.
 *
 * @package BdP_Scouting
 */

get_header();
?>
<div class="3xl:px-25 relative mx-auto w-full px-3 py-10 md:px-5 lg:px-10 xl:px-20">
	<?php if ( have_posts() ) : ?>
		<div id="news-container" class="grid grid-cols-6 gap-4 lg:gap-10 xl:gap-20 3xl:gap-25 mt-6 mb-6 md:mb-12">
			<?php
			$post_index = 0;
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/news/list-item', null, array( 'big' => $post_index < 2 ) );
				$post_index++;
			endwhile;
			?>
		</div>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<div class="no-news-found">
			<?php esc_html_e( 'No news items found.', 'bdp-scouting' ); ?>
		</div>
	<?php endif; ?>
</div>
<?php
get_footer();
