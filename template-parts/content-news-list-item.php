<?php
/**
 * Single news teaser card. Ported from Partials/News/List/Item.html.
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<div class="news-list__items__item">
	<div class="news-list__items__item__box">
		<a href="<?php the_permalink(); ?>" class="news-list__items__item__box__link">
			<div class="news-list__items__item__box__header">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="news-list__items__item__box__header__media">
						<?php the_post_thumbnail( 'large', array( 'class' => 'news-list__items__item__box__header__media__image' ) ); ?>
					</div>
				<?php endif; ?>
				<div class="news-list__items__item__box__header__title<?php echo has_post_thumbnail() ? '' : ' news-list__items__item__box__header__title--no-media'; ?>">
					<h3 class="news-list__items__item__box__header__title__headline"><?php the_title(); ?></h3>
				</div>
			</div>
			<div class="news-list__items__item__box__teaser">
				<span class="news-list__items__item__box__teaser__date">
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?>:</time>
				</span>
				<div class="news-list__items__item__box__teaser__text">
					<?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?>
				</div>
			</div>
		</a>
	</div>
</div>
