<?php
/**
 * News detail.
 *
 * Ported from Resources/Private/News/Templates/News/Detail.html.
 *
 * @package BdP_Scouting
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'article' ); ?> itemscope itemtype="https://schema.org/Article">
		<?php if ( has_post_thumbnail() ) : ?>
			<header class="relative w-full aspect-192/97">
				<?php the_post_thumbnail( 'bdp-news-hero', array( 'class' => 'w-full h-auto max-w-full aspect-192/97 object-cover brightness-75' ) ); ?>
				<div class="absolute left-3 md:left-5 lg:left-10 xl:left-20 bottom-[18%]">
					<h1 itemprop="headline" class="text-yellow-500 dark:text-white text-4xl font-bold md:text-5xl lg:text-6xl xl:text-7xl 2xl:text-8xl"><?php the_title(); ?></h1>
				</div>
			</header>
		<?php else : ?>
			<header class="bg-yellow-500 pb-10 text-blue-500 dark:bg-blue-500 dark:text-yellow-500">
				<div class="3xl:px-25 relative mx-auto w-full px-3 md:px-5 lg:px-10 xl:px-20">
					<h1 itemprop="headline" class="relative pt-16 text-4xl font-bold md:text-5xl">
						<?php the_title(); ?>
						<?php echo bdp_scouting_inline_svg( 'lines/welle.svg', array( 'class' => 'mt-2 -ml-2 text-red-500 lg:-ml-6 dark:text-white', 'width' => '220px' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</h1>
				</div>
			</header>
		<?php endif; ?>

		<div class="relative mx-auto w-full px-3 py-8 md:px-5 lg:px-10 xl:px-20 3xl:px-25">
			<div class="footer">
				<p>
					<span class="news-list-date">
						<time itemprop="datePublished" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
					</span>
					<?php
					$categories = get_the_category();
					if ( $categories ) {
						echo ' &middot; ';
						the_category( ', ' );
					}
					if ( get_the_author() ) {
						echo ' &middot; <span class="news-list-author" itemprop="author" itemscope itemtype="https://schema.org/Person">';
						esc_html_e( 'Author:', 'bdp-scouting' );
						echo ' <span itemprop="name">' . esc_html( get_the_author() ) . '</span></span>';
					}
					?>
				</p>
			</div>

			<?php
			$excerpt = get_the_excerpt();
			if ( $excerpt ) :
				?>
				<div class="text-xl font-semibold" itemprop="description"><?php echo esc_html( $excerpt ); ?></div>
			<?php endif; ?>

			<div class="news-text-wrap text-md 2xl:text-lg xl:pt-4 [&_p]:mt-4 [&_p]:first:mt-0 xl:[&_p]:mt-6 [&_ul]:list-disc [&_ul]:pl-6 [&_ol]:list-decimal [&_ol]:pl-6" itemprop="articleBody">
				<?php the_content(); ?>
			</div>

			<?php
			$prev_post = get_previous_post();
			$next_post = get_next_post();
			if ( $prev_post || $next_post ) :
				?>
				<ul class="pager flex justify-between mt-10">
					<li class="previous">
						<?php if ( $prev_post ) : ?>
							<a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>"><span aria-hidden="true">&larr; </span><?php echo esc_html( get_the_title( $prev_post ) ); ?></a>
						<?php endif; ?>
					</li>
					<li class="next">
						<?php if ( $next_post ) : ?>
							<a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>"><?php echo esc_html( get_the_title( $next_post ) ); ?><span aria-hidden="true"> &rarr;</span></a>
						<?php endif; ?>
					</li>
				</ul>
			<?php endif; ?>

			<div class="news-backlink-wrap mt-10">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ); ?>"><?php esc_html_e( 'Back to overview', 'bdp-scouting' ); ?></a>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();
