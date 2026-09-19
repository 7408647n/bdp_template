<?php
/**
 * News list. Maps 1:1 onto WordPress core `post` (see README.md) and is
 * ported from Templates/News/News/List.html + Partials/News/List/*.html.
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}

get_header();
?>
<div class="news-list">
	<?php if ( have_posts() ) : ?>
		<div class="news-list__items" id="news-container">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', 'news-list-item' );
			endwhile;
			?>
		</div>
		<?php get_template_part( 'template-parts/pagination' ); ?>
	<?php else : ?>
		<div class="no-news-found"><?php echo esc_html__( 'No news found.', 'bdp-template' ); ?></div>
	<?php endif; ?>
</div>
<?php
get_footer();
