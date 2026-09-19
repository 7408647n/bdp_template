<?php
/**
 * Small template helpers that replace TYPO3 ViewHelpers used throughout
 * Resources/Private/PageView.
 *
 * @package BdP_Scouting
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Replaces Classes/ViewHelpers/InlineSvgViewHelper.php: inline an SVG file
 * from the theme's assets/images directory so it can be styled/colored via
 * currentColor + Tailwind utility classes, exactly like <bdp:inlineSvg>.
 *
 * @param string $relative Path relative to assets/images, e.g. "brand/bundeszeichen_inline.svg".
 * @param array  $attrs    Optional attributes: class, width, height, aria-hidden, aria-label.
 * @return string Sanitized inline <svg>, or an empty string if the file is missing.
 */
function bdp_scouting_inline_svg( $relative, $attrs = array() ) {
	$path = BDP_SCOUTING_DIR . '/assets/images/' . ltrim( $relative, '/' );

	if ( ! file_exists( $path ) ) {
		return '';
	}

	$svg = file_get_contents( $path );
	if ( false === $svg ) {
		return '';
	}

	// Strip XML prolog / doctype / comments, keep only the <svg>...</svg> node.
	$svg = preg_replace( '/<\?xml.*?\?>/s', '', $svg );
	$svg = preg_replace( '/<!DOCTYPE.*?>/s', '', $svg );
	$svg = trim( $svg );

	if ( ! preg_match( '/<svg\b/i', $svg ) ) {
		return '';
	}

	$injected = '';
	if ( ! empty( $attrs['class'] ) ) {
		$injected .= sprintf( ' class="%s"', esc_attr( $attrs['class'] ) );
	}
	if ( isset( $attrs['width'] ) && '' !== $attrs['width'] ) {
		$injected .= sprintf( ' width="%s"', esc_attr( $attrs['width'] ) );
	}
	if ( isset( $attrs['height'] ) && '' !== $attrs['height'] ) {
		$injected .= sprintf( ' height="%s"', esc_attr( $attrs['height'] ) );
	}
	$injected .= isset( $attrs['aria-hidden'] ) && $attrs['aria-hidden']
		? ' aria-hidden="true"'
		: ' aria-hidden="false"';
	if ( ! empty( $attrs['aria-label'] ) ) {
		$injected .= sprintf( ' role="img" aria-label="%s"', esc_attr( $attrs['aria-label'] ) );
	}

	// Inject attributes right after the opening <svg tag.
	$svg = preg_replace( '/<svg\b/i', '<svg' . $injected, $svg, 1 );

	return $svg;
}

/**
 * Replaces Classes/ViewHelpers/UriHostViewHelper.php: extract the host part
 * of a URL, used for the small "pfadfinden.de"-style label in the header
 * meta bar that links to the site root.
 *
 * @param string $uri Absolute URL.
 * @return string Host name, or an empty string.
 */
function bdp_scouting_uri_host( $uri ) {
	if ( ! is_string( $uri ) || '' === $uri ) {
		return '';
	}

	$host = wp_parse_url( $uri, PHP_URL_HOST );

	return is_string( $host ) ? $host : '';
}

/**
 * Render the meta (top bar) navigation — Partials/Header.html's
 * "metaleftnavigation" loop.
 */
function bdp_scouting_meta_nav() {
	if ( ! has_nav_menu( 'meta' ) ) {
		return;
	}

	wp_nav_menu( array(
		'theme_location' => 'meta',
		'container'      => false,
		'items_wrap'     => '<ul class="hidden md:block">%3$s</ul>',
		'link_before'    => '',
		'link_after'     => '',
		'add_li_class'   => '',
		'depth'          => 1,
		'fallback_cb'    => false,
	) );
}

/**
 * Render the desktop main navigation using the dropdown-aware walker.
 */
function bdp_scouting_main_nav() {
	wp_nav_menu( array(
		'theme_location' => 'main',
		'container'      => false,
		'items_wrap'     => '<ul id="main-menu-list" class="font-medium lg:flex lg:text-lg">%3$s</ul>',
		'walker'         => new BdP_Scouting_Nav_Walker(),
		'fallback_cb'    => false,
	) );
}

/**
 * Render the off-canvas mobile main navigation.
 */
function bdp_scouting_mobile_nav() {
	wp_nav_menu( array(
		'theme_location' => 'main',
		'container'      => false,
		'items_wrap'     => '<ul class="m-0 list-none p-0">%3$s</ul>',
		'walker'         => new BdP_Scouting_Mobile_Nav_Walker(),
		'fallback_cb'    => false,
	) );
}

/**
 * Render the footer navigation.
 */
function bdp_scouting_footer_nav() {
	wp_nav_menu( array(
		'theme_location' => 'footer',
		'container'      => false,
		'items_wrap'     => '<ul class="text-lg font-bold">%3$s</ul>',
		'depth'          => 1,
		'fallback_cb'    => false,
	) );
}
