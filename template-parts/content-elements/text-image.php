<?php
/**
 * Markup reference for the "Text + Image" content element
 * (Resources/Private/Templates/ContentElements/TextImage.html). Corresponds
 * to Gutenberg's core/media-text block with the `.ce-text-image` classes
 * applied via a block pattern.
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<div class="ce-text-image">
	<div class="ce-text-image__media"><?php echo wp_kses_post( $args['image_html'] ?? '' ); ?></div>
	<div class="ce-text-image__content">
		<?php if ( ! empty( $args['header'] ) ) : ?>
			<header class="ce-header"><?php echo esc_html( $args['header'] ); ?></header>
		<?php endif; ?>
		<div class="ce-text-image__content__bodytext"><?php echo wp_kses_post( $args['content'] ?? '' ); ?></div>
	</div>
</div>
