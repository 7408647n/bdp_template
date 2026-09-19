<?php
/**
 * Markup reference for the "Text" content element
 * (Resources/Private/Templates/ContentElements/Text.html). Gutenberg's
 * core/paragraph + core/heading blocks already render into `.ce-*`-free
 * markup, so this partial documents the class names to add via a block
 * pattern (see README.md) rather than being called directly.
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<div class="ce-text">
	<?php if ( ! empty( $args['header'] ) ) : ?>
		<header class="ce-header"><?php echo esc_html( $args['header'] ); ?></header>
	<?php endif; ?>
	<div class="ce-text__bodytext">
		<?php echo wp_kses_post( $args['content'] ?? '' ); ?>
	</div>
</div>
