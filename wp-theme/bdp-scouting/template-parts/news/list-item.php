<?php
/**
 * A single teaser in the News list.
 *
 * Ported from Resources/Private/News/Partials/List/Item.html. TYPO3's
 * "news" extension has no business logic in this repo (pure Fluid
 * presentation over GeorgRinger\News), so this maps 1:1 onto WordPress's
 * own `post` type: get_the_date/title/excerpt/post_thumbnail replace
 * newsItem.datetime/title/teaser/mediaPreviews.
 *
 * @package BdP_Scouting
 */

$big = ! empty( $args['big'] ) && $args['big'];
?>
<div class="col-span-6 <?php echo $big ? 'sm:col-span-3' : 'sm:col-span-3 lg:col-span-2'; ?>" <?php post_class( 'article' ); ?> itemscope itemtype="https://schema.org/Article">
	<a href="<?php the_permalink(); ?>" class="group/n-link block relative w-full" title="<?php the_title_attribute(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="mb-4">
				<figure class="relative">
					<?php the_post_thumbnail( $big ? 'bdp-news-teaser-big' : 'bdp-news-teaser', array( 'class' => 'w-full h-auto max-w-full', 'loading' => 'lazy' ) ); ?>
				</figure>
			</div>
		<?php endif; ?>
		<div class="text-md 2xl:text-lg">
			<time itemprop="datePublished" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
				<?php echo esc_html( get_the_date() ); ?>
			</time>
		</div>
		<h3 class="mt-4 text-3xl font-semibold">
			<span itemprop="headline"><?php the_title(); ?></span>
		</h3>
		<div class="text-md 2xl:text-lg mt-3 [&_p]:mt-4 [&_p]:first:mt-0 xl:[&_p]:mt-6 [&_ul]:list-disc [&_ul]:pl-6 [&_ol]:list-decimal [&_ol]:pl-6" itemprop="description">
			<?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 30 ) ); ?>
		</div>
	</a>
</div>
