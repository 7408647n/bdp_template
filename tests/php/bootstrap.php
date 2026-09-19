<?php
/**
 * PHPUnit bootstrap. Loads Composer's autoloader only - the theme's own
 * inc/*.php files are required inside each test's setUp(), after
 * Brain\Monkey\setUp() has defined the WordPress hook functions
 * (add_action, add_filter, ...) they call at the top level.
 */

define( 'ABSPATH', __DIR__ . '/' );

require_once dirname( __DIR__, 2 ) . '/vendor/autoload.php';

// Minimal WordPress time constants used by inc/instagram.php.
define( 'MINUTE_IN_SECONDS', 60 );
define( 'HOUR_IN_SECONDS', 60 * MINUTE_IN_SECONDS );

if (! class_exists( 'WP_Error' ) ) {
	/**
	 * Minimal stand-in for WordPress's WP_Error, sufficient for
	 * inc/instagram.php's is_wp_error() branch in tests.
	 */
	class WP_Error {
		public function __construct( $code = '', $message = '', $data = '' ) {}
	}
}
