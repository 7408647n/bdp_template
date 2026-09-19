<?php
/**
 * BdP Scouting theme bootstrap.
 *
 * Ported from the TYPO3 "bdp_template" sitepackage (cd-2026 redesign) of the
 * Bund der Pfadfinder*innen e.V. No plugins are required by default: the
 * event-directory style pieces of the original TYPO3 extension (address
 * book, Instagram feed, cookie consent) are re-implemented natively here.
 *
 * @package BdP_Scouting
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BDP_SCOUTING_VERSION', '1.0.0' );
define( 'BDP_SCOUTING_DIR', get_template_directory() );
define( 'BDP_SCOUTING_URI', get_template_directory_uri() );

require_once BDP_SCOUTING_DIR . '/inc/setup.php';
require_once BDP_SCOUTING_DIR . '/inc/enqueue.php';
require_once BDP_SCOUTING_DIR . '/inc/nav-walker.php';
require_once BDP_SCOUTING_DIR . '/inc/cpt-bdp-group.php';
require_once BDP_SCOUTING_DIR . '/inc/instagram.php';
require_once BDP_SCOUTING_DIR . '/inc/cookie-consent.php';
require_once BDP_SCOUTING_DIR . '/inc/template-tags.php';
require_once BDP_SCOUTING_DIR . '/inc/block-patterns.php';
