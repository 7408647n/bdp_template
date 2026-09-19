<?php
/**
 * Generic page template. Ported from Resources/Private/Templates/Page/*
 * and the content-element partials for the block-editor equivalents
 * (see template-parts/content-elements/*.php, used by block patterns).
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'page-content' ); ?>>
		<?php if ( get_the_title() ) : ?>
			<header class="header">
				<h1 class="ce-header"><?php the_title(); ?></h1>
			</header>
		<?php endif; ?>
		<div class="page-content__body">
			<?php the_content(); ?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
