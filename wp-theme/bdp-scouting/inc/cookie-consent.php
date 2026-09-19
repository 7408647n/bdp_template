<?php
/**
 * Native cookie consent banner.
 *
 * The TYPO3 extension's own CookieManager/Templates/CookieFrontend/List.html
 * is an empty stub in this branch (the real banner design lives inside the
 * separate, TYPO3-only "cf-cookiemanager" extension it is suggested
 * alongside — see composer.json). There is therefore no markup to port; this
 * is a from-scratch, minimal, dependency-free banner in the site's brand
 * colors (see assets/src/js/entries/cookie-consent.entry.ts for the logic
 * and template-parts/cookie/banner.php for the markup).
 *
 * A site that wants a fuller consent-management solution can install the
 * free "GDPR Cookie Consent" plugin instead — see README.md.
 *
 * @package BdP_Scouting
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const BDP_COOKIE_CONSENT_NAME = 'bdp_cookie_consent';

/**
 * The consent categories offered in the banner. "necessary" is always on
 * and cannot be turned off, matching standard GDPR banner conventions.
 *
 * @return array<string,array{label:string,description:string,locked:bool}>
 */
function bdp_scouting_cookie_categories() {
	return array(
		'necessary' => array(
			'label'       => __( 'Necessary', 'bdp-scouting' ),
			'description' => __( 'Required for the site to function (session, security, load balancing).', 'bdp-scouting' ),
			'locked'      => true,
		),
		'statistics' => array(
			'label'       => __( 'Statistics', 'bdp-scouting' ),
			'description' => __( 'Helps us understand how visitors use the site.', 'bdp-scouting' ),
			'locked'      => false,
		),
		'marketing'  => array(
			'label'       => __( 'Marketing', 'bdp-scouting' ),
			'description' => __( 'Used to embed content such as the Instagram feed and YouTube videos.', 'bdp-scouting' ),
			'locked'      => false,
		),
	);
}

/**
 * Expose the categories to assets/build/Js/cookie-consent.js via
 * wp_localize_script so the banner's categories can be edited from PHP
 * without touching the compiled JS.
 */
function bdp_scouting_cookie_consent_localize() {
	if ( ! wp_script_is( 'bdp-scouting-cookie-consent', 'enqueued' ) && ! wp_script_is( 'bdp-scouting-cookie-consent', 'registered' ) ) {
		return;
	}

	wp_localize_script( 'bdp-scouting-cookie-consent', 'bdpCookieConsent', array(
		'cookieName' => BDP_COOKIE_CONSENT_NAME,
		'categories' => array_map(
			function ( $key, $category ) {
				return array(
					'key'         => $key,
					'label'       => $category['label'],
					'description' => $category['description'],
					'locked'      => $category['locked'],
				);
			},
			array_keys( bdp_scouting_cookie_categories() ),
			bdp_scouting_cookie_categories()
		),
	) );
}
add_action( 'wp_enqueue_scripts', 'bdp_scouting_cookie_consent_localize', 20 );
