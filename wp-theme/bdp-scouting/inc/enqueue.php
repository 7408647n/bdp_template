<?php
/**
 * Asset enqueueing.
 *
 * Assets are built by `bun run build` (see scripts/build.ts) from
 * assets/src into assets/build. This mirrors the original vite.config.js
 * output split into Css/ and Js/ folders.
 *
 * @package BdP_Scouting
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Resolve a built asset path and return its URI plus a cache-busting
 * version derived from the file's mtime (falls back to the theme version
 * when the build hasn't run yet, e.g. during unit tests).
 *
 * @param string $relative Path relative to assets/build, e.g. "Css/main.css".
 * @return array{0:string,1:string} [uri, version]
 */
function bdp_scouting_asset( $relative ) {
	$file = BDP_SCOUTING_DIR . '/assets/build/' . $relative;
	$uri  = BDP_SCOUTING_URI . '/assets/build/' . $relative;
	$ver  = file_exists( $file ) ? (string) filemtime( $file ) : BDP_SCOUTING_VERSION;

	return array( $uri, $ver );
}

/**
 * Enqueue theme styles and scripts on the front end.
 */
function bdp_scouting_enqueue_assets() {
	list( $main_css_uri, $main_css_ver ) = bdp_scouting_asset( 'Css/main.css' );
	wp_enqueue_style( 'bdp-scouting-main', $main_css_uri, array(), $main_css_ver );

	list( $main_js_uri, $main_js_ver ) = bdp_scouting_asset( 'Js/main.js' );
	wp_enqueue_script( 'bdp-scouting-main', $main_js_uri, array(), $main_js_ver, true );

	list( $cookie_js_uri, $cookie_js_ver ) = bdp_scouting_asset( 'Js/cookie-consent.js' );
	wp_enqueue_script( 'bdp-scouting-cookie-consent', $cookie_js_uri, array(), $cookie_js_ver, true );

	if ( bdp_scouting_instagram_feed_is_active() ) {
		list( $ig_css_uri, $ig_css_ver ) = bdp_scouting_asset( 'Js/instagram.entry.css' );
		if ( file_exists( BDP_SCOUTING_DIR . '/assets/build/Js/instagram.entry.css' ) ) {
			wp_enqueue_style( 'bdp-scouting-instagram', $ig_css_uri, array(), $ig_css_ver );
		}
		list( $ig_js_uri, $ig_js_ver ) = bdp_scouting_asset( 'Js/instagram.js' );
		wp_enqueue_script( 'bdp-scouting-instagram', $ig_js_uri, array(), $ig_js_ver, true );
	}
}
add_action( 'wp_enqueue_scripts', 'bdp_scouting_enqueue_assets' );

/**
 * Enqueue the header slider (Swiper) assets. Called from
 * template-parts/content-blocks/headerslider.php, the equivalent of the
 * "headear-slider" ContentBlocks element, rather than on every page — this
 * mirrors how the original entry was only loaded on pages that used it.
 */
function bdp_scouting_enqueue_headerslider() {
	list( $css_uri, $css_ver ) = bdp_scouting_asset( 'Js/headerslider.entry.css' );
	if ( file_exists( BDP_SCOUTING_DIR . '/assets/build/Js/headerslider.entry.css' ) ) {
		wp_enqueue_style( 'bdp-scouting-headerslider', $css_uri, array(), $css_ver );
	}
	list( $js_uri, $js_ver ) = bdp_scouting_asset( 'Js/headerslider.js' );
	wp_enqueue_script( 'bdp-scouting-headerslider', $js_uri, array(), $js_ver, true );
}

/**
 * Preload the Jost variable font like the TYPO3 font-face-jost.css did.
 */
function bdp_scouting_preload_fonts() {
	$fonts = array( 'jost-latin.woff2' );
	foreach ( $fonts as $font ) {
		$path = BDP_SCOUTING_DIR . '/assets/fonts/Jost/' . $font;
		if ( file_exists( $path ) ) {
			printf(
				'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
				esc_url( BDP_SCOUTING_URI . '/assets/fonts/Jost/' . $font )
			);
		}
	}
}
add_action( 'wp_head', 'bdp_scouting_preload_fonts', 1 );
