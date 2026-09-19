<?php
/**
 * Theme bootstrap.
 *
 * Ported from the pfadfinden/bdp_template TYPO3 sitepackage. See README.md
 * for the full mapping between the original TYPO3 extension and this
 * WordPress theme.
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}

define( 'BDP_THEME_VERSION', wp_get_theme()->get( 'Version' ) );

require_once get_template_directory() . '/inc/pipe-break.php';
require_once get_template_directory() . '/inc/post-types.php';
require_once get_template_directory() . '/inc/instagram.php';
require_once get_template_directory() . '/inc/cookie-consent.php';
require_once get_template_directory() . '/inc/customizer.php';

/**
 * Theme setup: feature support, nav menus, translations.
 */
function bdp_setup(): void {
	load_theme_textdomain( 'bdp-template', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );

	register_nav_menus(
		array(
			'primary' => __( 'Main Navigation', 'bdp-template' ),
			'side'    => __( 'Side Navigation', 'bdp-template' ),
			'footer'  => __( 'Footer Navigation', 'bdp-template' ),
		)
	);
}
add_action( 'after_setup_theme', 'bdp_setup' );

/**
 * Enqueues the theme's built CSS/JS (see scripts/build.ts). Falls back
 * gracefully (no fatal) if `bun run build` has not been run yet.
 */
function bdp_enqueue_assets(): void {
	$css_path = '/assets/build/css/main.css';
	if ( file_exists( get_template_directory() . $css_path ) ) {
		wp_enqueue_style(
			'bdp-template-main',
			get_template_directory_uri() . $css_path,
			array(),
			BDP_THEME_VERSION
		);
	}

	$main_js = '/assets/build/js/main.entry.js';
	if ( file_exists( get_template_directory() . $main_js ) ) {
		wp_enqueue_script(
			'bdp-template-main',
			get_template_directory_uri() . $main_js,
			array(),
			BDP_THEME_VERSION,
			array( 'in_footer' => true )
		);
		wp_script_add_data( 'bdp-template-main', 'type', 'module' );
	}

	// Cookie consent banner runs on every page.
	$cookie_js = '/assets/build/js/cookiemanager.entry.js';
	if ( file_exists( get_template_directory() . $cookie_js ) ) {
		wp_enqueue_script(
			'bdp-template-cookiemanager',
			get_template_directory_uri() . $cookie_js,
			array(),
			BDP_THEME_VERSION,
			array( 'in_footer' => true )
		);
		wp_script_add_data( 'bdp-template-cookiemanager', 'type', 'module' );
	}

	if ( is_singular( 'post' ) || is_home() || is_category() || is_tag() ) {
		bdp_enqueue_entry( is_singular( 'post' ) ? 'news-detail' : 'news-list' );
	}

	if ( is_singular( 'bdp_event' ) ) {
		bdp_enqueue_entry( 'calendarize-detail' );
	} elseif ( is_post_type_archive( 'bdp_event' ) ) {
		bdp_enqueue_entry( 'calendarize-list' );
	}

	if ( is_post_type_archive( 'bdp_group' ) || is_singular( 'bdp_group' ) ) {
		bdp_enqueue_entry( 'address-list' );
	}
}
add_action( 'wp_enqueue_scripts', 'bdp_enqueue_assets' );

/**
 * Enqueues one page-specific bundle produced by scripts/build.ts.
 */
function bdp_enqueue_entry( string $name ): void {
	$path = "/assets/build/js/{$name}.entry.js";
	if (! file_exists( get_template_directory() . $path ) ) {
		return;
	}

	$handle = "bdp-template-{$name}";
	wp_enqueue_script( $handle, get_template_directory_uri() . $path, array(), BDP_THEME_VERSION, array( 'in_footer' => true ) );
	wp_script_add_data( $handle, 'type', 'module' );

	if ( 'news-detail' === $name ) {
		wp_enqueue_style(
			'bdp-template-lightgallery',
			get_template_directory_uri() . '/assets/build/vendor/lightgallery/lightgallery-bundle.min.css',
			array(),
			BDP_THEME_VERSION
		);
	}
}

/**
 * Register the theme's single widget area (footer contact info block),
 * mirroring Partials/Page/Footer.html's static contact/branding section.
 */
function bdp_widgets_init(): void {
	register_sidebar(
		array(
			'name'          => __( 'Footer', 'bdp-template' ),
			'id'            => 'footer-1',
			'before_widget' => '<div class="page__footer__content__section">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="page__footer__title">',
			'after_title'   => '</div>',
		)
	);
}
add_action( 'widgets_init', 'bdp_widgets_init' );
