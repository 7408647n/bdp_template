<?php
/**
 * Header image slider ("headear-slider" ContentBlocks element).
 *
 * Usage: pass an array of slides via $args['slides'] = [['image_url' => ..., 'alt' => ...], ...]
 * from a page template, e.g.:
 *   get_template_part( 'template-parts/content-blocks/headerslider', null, [ 'slides' => $slides ] );
 *
 * @package BdP_Scouting
 */

bdp_scouting_enqueue_headerslider();

$slides = ! empty( $args['slides'] ) && is_array( $args['slides'] ) ? $args['slides'] : array();
?>
<div class="ct-headerslider swiper relative w-full">
	<div class="swiper-wrapper">
		<?php foreach ( $slides as $slide ) : ?>
			<div class="swiper-slide">
				<img
					class="w-full h-auto max-w-full aspect-192/97 object-cover"
					src="<?php echo esc_url( $slide['image_url'] ?? '' ); ?>"
					alt="<?php echo esc_attr( $slide['alt'] ?? '' ); ?>"
					loading="lazy"
				>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="swiper-pagination"></div>
	<div class="swiper-button-prev"></div>
	<div class="swiper-button-next"></div>
</div>
