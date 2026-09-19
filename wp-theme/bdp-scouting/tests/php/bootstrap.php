<?php
/**
 * PHPUnit bootstrap for the BdP Scouting theme.
 *
 * Loads Brain Monkey (which fakes core WordPress hook functions like
 * add_action/do_action) plus a handful of hand-written stubs for the other
 * WordPress functions the theme's inc/*.php files call at file-load time or
 * that are simple enough to stub deterministically, so the theme's PHP can
 * be unit tested without a WordPress install.
 */

declare( strict_types=1 );

require_once __DIR__ . '/../../vendor/autoload.php';

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', sys_get_temp_dir() . '/' );
}

define( 'BDP_SCOUTING_TEST_THEME_DIR', dirname( __DIR__, 2 ) );

if ( ! defined( 'BDP_SCOUTING_DIR' ) ) {
	define( 'BDP_SCOUTING_DIR', BDP_SCOUTING_TEST_THEME_DIR );
}
if ( ! defined( 'BDP_SCOUTING_URI' ) ) {
	define( 'BDP_SCOUTING_URI', 'https://example.test/wp-content/themes/bdp-scouting' );
}
if ( ! defined( 'BDP_SCOUTING_VERSION' ) ) {
	define( 'BDP_SCOUTING_VERSION', '1.0.0-test' );
}

if ( ! defined( 'MINUTE_IN_SECONDS' ) ) {
	define( 'MINUTE_IN_SECONDS', 60 );
}
if ( ! defined( 'HOUR_IN_SECONDS' ) ) {
	define( 'HOUR_IN_SECONDS', 3600 );
}

// Walker_Nav_Menu is a WordPress core class; stub the minimal shape so
// inc/nav-walker.php can be loaded and its walkers instantiated in tests.
if ( ! class_exists( 'Walker_Nav_Menu' ) ) {
	class Walker_Nav_Menu {
		public $tree_type = 'menu';
		public $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

		public function start_lvl( &$output, $depth = 0, $args = null ) {}
		public function end_lvl( &$output, $depth = 0, $args = null ) {}
		public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {}
		public function end_el( &$output, $item, $depth = 0, $args = null ) {}
	}
}

// WP_Post is only needed as a type-hint target for the meta box renderer;
// tests call that function with a lightweight stdClass-based double.
if ( ! class_exists( 'WP_Post' ) ) {
	class WP_Post {
		public $ID = 0;
	}
}

// Plain no-op stubs for the WordPress registration/hook functions that run
// at file-load time (add_action(...) at the bottom of each inc/*.php file)
// or that this suite does not need to assert calls against. Brain Monkey's
// per-test Functions\when()/expect() calls (used below in individual tests)
// take priority for the handful of functions actually exercised by the
// functions under test (get_option, update_post_meta, wp_verify_nonce, …).
foreach (
	array(
		'add_action',
		'add_filter',
		'register_setting',
		'add_settings_section',
		'add_settings_field',
		'register_post_type',
		'register_post_meta',
		'add_meta_box',
		'register_block_pattern_category',
		'register_block_pattern',
		'register_nav_menus',
		'add_theme_support',
		'add_image_size',
		'load_theme_textdomain',
	) as $bdp_test_stub_fn
) {
	if ( ! function_exists( $bdp_test_stub_fn ) ) {
		eval( "function {$bdp_test_stub_fn}( ...\$args ) { return true; }" ); // phpcs:ignore
	}
}
unset( $bdp_test_stub_fn );

require_once BDP_SCOUTING_TEST_THEME_DIR . '/inc/cpt-bdp-group.php';
require_once BDP_SCOUTING_TEST_THEME_DIR . '/inc/instagram.php';
require_once BDP_SCOUTING_TEST_THEME_DIR . '/inc/cookie-consent.php';
require_once BDP_SCOUTING_TEST_THEME_DIR . '/inc/template-tags.php';
require_once BDP_SCOUTING_TEST_THEME_DIR . '/inc/nav-walker.php';
require_once BDP_SCOUTING_TEST_THEME_DIR . '/inc/setup.php';
