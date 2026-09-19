<?php
/**
 * Instagram feed block. Ported from Templates/InstagramBusiness/Post/List.html
 * and Partials/InstagramBusiness/Post/RenderMedia.html. Use via
 * `get_template_part( 'template-parts/instagram-feed' )` in a page template
 * or a block pattern.
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}

$posts = bdp_instagram_get_feed();
if ( empty( $posts ) ) {
	return;
}
?>
<div class="ce-social__i">
	<?php foreach ( $posts as $post ) : ?>
		<div class="ce-social__i__item">
			<a
				href="<?php echo esc_url( $post['link'] ); ?>"
				target="_blank"
				rel="noopener"
				class="ce-social__i__item__box"
				title="<?php echo esc_attr( wp_trim_words( $post['caption'], 20 ) ); ?>"
			>
				<?php if ( 'VIDEO' === $post['mediaType'] ) : ?>
					<div class="ce-social__i__item__box__video">
						<video width="332" height="415" preload="none" controls poster="<?php echo esc_url( $post['thumbUrl'] ); ?>" class="ce-social__i__item__box__video__player">
							<source src="<?php echo esc_url( $post['mediaUrl'] ); ?>" type="video/mp4" />
						</video>
					</div>
				<?php else : ?>
					<picture class="ce-social__i__item__box__picture">
						<img
							src="<?php echo esc_url( $post['mediaUrl'] ); ?>"
							alt="<?php echo esc_attr( wp_trim_words( $post['caption'], 20 ) ); ?>"
							class="ce-social__i__item__box__picture__image"
							width="332"
							height="415"
							loading="lazy"
						/>
					</picture>
				<?php endif; ?>
			</a>
		</div>
	<?php endforeach; ?>
</div>
