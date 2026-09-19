<?php
/**
 * News detail. Ported from Templates/News/News/Detail.html.
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
	<article class="news-article">
		<header class="header">
			<h1 class="ce-header"><?php the_title(); ?></h1>
		</header>
		<div class="news-article__box">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="news-article__box__media">
					<?php the_post_thumbnail( 'large', array( 'class' => 'news-article__box__image' ) ); ?>
				</div>
			<?php endif; ?>
			<div class="news-article__box__time">
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			</div>
			<div class="news-article__box__content">
				<?php if ( has_excerpt() ) : ?>
					<div class="news-article__box__content__teaser"><?php echo wp_kses_post( get_the_excerpt() ); ?></div>
				<?php endif; ?>
				<div class="news-article__box__content__main">
					<?php the_content(); ?>
				</div>
			</div>
		</div>
		<?php
		$gallery_images = get_post_gallery_images();
		if ( ! empty( $gallery_images ) ) :
			?>
			<div class="news-article__media">
				<?php foreach ( $gallery_images as $image_url ) : ?>
					<div class="news-article__media__item">
						<div class="news-article__media__item__box">
							<figure class="mediaelement mediaelement-image">
								<a href="<?php echo esc_url( $image_url ); ?>" class="mediaelement__link">
									<img src="<?php echo esc_url( $image_url ); ?>" alt="" loading="lazy" />
								</a>
							</figure>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</article>
	<?php
endwhile;

get_footer();
