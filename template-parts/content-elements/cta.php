<?php
/**
 * Markup reference for the "Call to Action" content element
 * (Resources/Private/Templates/ContentElements/Cta.html). Corresponds to
 * Gutenberg's core/buttons block with the `.ce-cta` classes applied via a
 * block pattern.
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<div class="ce-cta">
	<?php if ( ! empty( $args['header'] ) ) : ?>
		<header class="ce-header"><?php echo esc_html( $args['header'] ); ?></header>
	<?php endif; ?>
	<a href="<?php echo esc_url( $args['url'] ?? '#' ); ?>" class="ce-cta__button">
		<?php echo esc_html( $args['label'] ?? '' ); ?>
	</a>
</div>
