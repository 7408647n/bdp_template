<?php
/**
 * Instagram feed slider.
 *
 * Ported from Resources/Private/InstagramBusiness/Templates/Post/List.html
 * and Partials/Post/RenderMedia.html. Use with:
 *   <?php get_template_part( 'template-parts/instagram/feed' ); ?>
 * anywhere in a page/content-block template.
 *
 * @package BdP_Scouting
 */

if ( ! bdp_scouting_instagram_feed_is_active() ) {
	return;
}

$posts = bdp_scouting_get_instagram_feed( 12 );

if ( empty( $posts ) ) {
	return;
}
?>
<div class="px-4 xl:px-8 py-8">
	<div class="ct-instagram swiper relative w-full">
		<div class="swiper-wrapper">
			<?php foreach ( $posts as $post ) : ?>
				<a
					class="swiper-slide block w-64"
					href="<?php echo esc_url( $post['permalink'] ); ?>"
					target="_blank"
					rel="noopener noreferrer"
					aria-label="<?php echo esc_attr( wp_strip_all_tags( $post['caption'] ) ); ?>"
				>
					<?php if ( 'VIDEO' === $post['type'] ) : ?>
						<video class="w-full h-64 object-cover" muted playsinline poster="<?php echo esc_url( $post['image_url'] ); ?>">
							<source src="<?php echo esc_url( $post['video_url'] ); ?>" type="video/mp4">
						</video>
					<?php else : ?>
						<img class="w-full h-64 object-cover" src="<?php echo esc_url( $post['image_url'] ); ?>" alt="<?php echo esc_attr( wp_strip_all_tags( $post['caption'] ) ); ?>" loading="lazy">
					<?php endif; ?>
				</a>
			<?php endforeach; ?>
		</div>
		<div class="swiper-button-prev !z-102 !h-full !top-0 !left-0 !w-15 !mt-0 pl-3 after:hidden hover:after:block"></div>
		<div class="swiper-button-next !z-102 !h-full !top-0 !right-0 !w-15 !mt-0 pr-3 after:hidden hover:after:block"></div>
	</div>
</div>
