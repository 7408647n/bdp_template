<?php
/**
 * Markup reference for the "Image" content element
 * (Resources/Private/Templates/ContentElements/Image.html).
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<figure class="ce-teaserimage">
	<?php if ( ! empty( $args['image_html'] ) ) : ?>
		<div class="ce-teaserimage__image"><?php echo wp_kses_post( $args['image_html'] ); ?></div>
	<?php endif; ?>
	<?php if ( ! empty( $args['caption'] ) ) : ?>
		<figcaption class="ce-teaserimage__caption"><?php echo esc_html( $args['caption'] ); ?></figcaption>
	<?php endif; ?>
</figure>
