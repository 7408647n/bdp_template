<?php
/**
 * Block pattern equivalents of the ContentBlocks content elements.
 *
 * ContentBlocks/ContentElements/* (text, image, textmedia, table,
 * text-left-image, text-top-image, text-image-mask, text-images-mask,
 * text-mask, text-mask-image, quote-image-text-mask, small-cta-image,
 * header-block, headear-slider) are pure Fluid markup with configurable
 * layout/mask/color options and no controller logic. Rather than
 * reimplementing 13 near-duplicate PHP templates, the two structurally
 * distinct shapes (a plain text block, and an image+text "card") are
 * provided here as registered block patterns built from Gutenberg core
 * blocks, styled with the ported Tailwind classes; editors compose pages
 * from these (and from core/columns, core/table, core/quote, core/cover
 * for the remaining variants) the same way TYPO3 editors picked a
 * ContentBlocks element. See README.md for the full mapping table.
 *
 * @package BdP_Scouting
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the "BdP Scouting" pattern category and its patterns.
 */
function bdp_scouting_register_block_patterns() {
	register_block_pattern_category( 'bdp-scouting', array(
		'label' => __( 'BdP Scouting', 'bdp-scouting' ),
	) );

	// Equivalent of ContentBlocks/ContentElements/small-cta-image.
	register_block_pattern(
		'bdp-scouting/cta-image',
		array(
			'title'      => __( 'CTA with image', 'bdp-scouting' ),
			'categories' => array( 'bdp-scouting' ),
			'content'    => '<!-- wp:group {"backgroundColor":"luminous-vivid-amber","className":"bdp-cta-image md:aspect-5/8 max-w-[500px]"} -->
<div class="wp-block-group bdp-cta-image md:aspect-5/8 max-w-[500px] has-luminous-vivid-amber-background-color has-background">
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="" alt=""/></figure>
<!-- /wp:image -->
<!-- wp:group {"className":"px-5 lg:px-8 xl:px-10 pb-5"} -->
<div class="wp-block-group px-5 lg:px-8 xl:px-10 pb-5">
<!-- wp:heading {"className":"mt-3 text-3xl font-semibold"} -->
<h2 class="wp-block-heading mt-3 text-3xl font-semibold">' . esc_html__( 'Headline', 'bdp-scouting' ) . '</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"text-md mt-6"} -->
<p class="text-md mt-6">' . esc_html__( 'Body text goes here.', 'bdp-scouting' ) . '</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->',
		)
	);

	// Equivalent of ContentBlocks/ContentElements/text-left-image / text-top-image.
	register_block_pattern(
		'bdp-scouting/text-image',
		array(
			'title'      => __( 'Text with image', 'bdp-scouting' ),
			'categories' => array( 'bdp-scouting' ),
			'content'    => '<!-- wp:columns {"className":"items-center gap-6"} -->
<div class="wp-block-columns items-center gap-6">
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="" alt=""/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:heading {"className":"text-3xl font-semibold"} -->
<h2 class="wp-block-heading text-3xl font-semibold">' . esc_html__( 'Headline', 'bdp-scouting' ) . '</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"text-md xl:pt-4"} -->
<p class="text-md xl:pt-4">' . esc_html__( 'Body text goes here.', 'bdp-scouting' ) . '</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->',
		)
	);
}
add_action( 'init', 'bdp_scouting_register_block_patterns' );
