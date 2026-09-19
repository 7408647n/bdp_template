<?php
/**
 * Ported from Partials/News/List/Pagination.html.
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}

$links = paginate_links(
	array(
		'prev_text' => esc_html__( 'Previous', 'bdp-template' ),
		'next_text' => esc_html__( 'Next', 'bdp-template' ),
		'type'      => 'array',
	)
);

if ( ! $links ) {
	return;
}
?>
<div class="news-list__pagination">
	<ul class="news-list__pagination__list">
		<?php foreach ( $links as $link ) : ?>
			<li class="news-list__pagination__list__item"><?php echo wp_kses_post( $link ); ?></li>
		<?php endforeach; ?>
	</ul>
</div>
