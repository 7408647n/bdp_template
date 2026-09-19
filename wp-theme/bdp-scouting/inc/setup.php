<?php
/**
 * Core theme setup: supports, menus, sidebars.
 *
 * Mirrors what the TYPO3 PageView layout (Resources/Private/PageView) needed:
 * a custom logo (settings.page.logo / infotype), a main + footer navigation,
 * post thumbnails for News teaser images and title-tag handling.
 *
 * @package BdP_Scouting
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme supports and navigation menus.
 */
function bdp_scouting_setup() {
	load_theme_textdomain( 'bdp-scouting', BDP_SCOUTING_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 56,
		'width'       => 208,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

	register_nav_menus( array(
		'meta'   => __( 'Meta navigation (top bar)', 'bdp-scouting' ),
		'main'   => __( 'Main navigation', 'bdp-scouting' ),
		'footer' => __( 'Footer navigation', 'bdp-scouting' ),
	) );

	add_image_size( 'bdp-news-teaser', 500, 350, true );
	add_image_size( 'bdp-news-teaser-big', 1000, 562, true );
	add_image_size( 'bdp-news-hero', 1800, 909, true );
}
add_action( 'after_setup_theme', 'bdp_scouting_setup' );

/**
 * The "infotype" setting from Configuration/Sets/SitePackage/settings.definitions.yaml
 * (stamm|bund|lv|project|service) decided whether the group-specific logo or
 * the association's own bundeszeichen logo was shown, and which "member of"
 * sentence appeared in the footer. We expose the same choice as a Customizer
 * setting so a site editor can pick it per-installation.
 *
 * @return string
 */
function bdp_scouting_get_infotype() {
	$infotype = get_theme_mod( 'bdp_infotype', 'stamm' );
	$allowed  = array( 'stamm', 'bund', 'lv', 'project', 'service' );

	return in_array( $infotype, $allowed, true ) ? $infotype : 'stamm';
}

/**
 * German label for the infotype, used in the footer "Ein <Label> im Bund der..." line.
 *
 * @param string $infotype One of stamm|bund|lv|project|service.
 * @return string
 */
function bdp_scouting_infotype_label( $infotype ) {
	$labels = array(
		'lv'      => __( 'Landesverband', 'bdp-scouting' ),
		'project' => __( 'Projekt', 'bdp-scouting' ),
		'service' => __( 'Service', 'bdp-scouting' ),
		'stamm'   => __( 'Stamm', 'bdp-scouting' ),
	);

	return $labels[ $infotype ] ?? $labels['stamm'];
}

/**
 * Register the Customizer settings that replace the TYPO3
 * "pfadfinden.settings" / "pfadfinden.contact" TypoScript constants.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 */
function bdp_scouting_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'bdp_settings', array(
		'title'    => __( 'BdP Base Settings', 'bdp-scouting' ),
		'priority' => 30,
	) );

	$wp_customize->add_setting( 'bdp_infotype', array(
		'default'           => 'stamm',
		'sanitize_callback' => 'sanitize_key',
	) );
	$wp_customize->add_control( 'bdp_infotype', array(
		'label'   => __( 'Site type (infotype)', 'bdp-scouting' ),
		'section' => 'bdp_settings',
		'type'    => 'select',
		'choices' => array(
			'stamm'   => __( 'Stamm', 'bdp-scouting' ),
			'bund'    => __( 'Bundesseite', 'bdp-scouting' ),
			'lv'      => __( 'Landesverband', 'bdp-scouting' ),
			'project' => __( 'Projekt', 'bdp-scouting' ),
			'service' => __( 'Service', 'bdp-scouting' ),
		),
	) );

	$contact_fields = array(
		'association' => __( 'Association name', 'bdp-scouting' ),
		'name'        => __( 'Contact name', 'bdp-scouting' ),
		'street'      => __( 'Street', 'bdp-scouting' ),
		'housenumber' => __( 'House number', 'bdp-scouting' ),
		'postcode'    => __( 'Postcode', 'bdp-scouting' ),
		'city'        => __( 'City', 'bdp-scouting' ),
		'phone'       => __( 'Phone', 'bdp-scouting' ),
		'email'       => __( 'E-Mail', 'bdp-scouting' ),
		'website'     => __( 'Website', 'bdp-scouting' ),
		'instagram'   => __( 'Instagram URL', 'bdp-scouting' ),
		'youtube'     => __( 'YouTube URL', 'bdp-scouting' ),
	);

	foreach ( $contact_fields as $key => $label ) {
		$wp_customize->add_setting( "bdp_contact_{$key}", array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( "bdp_contact_{$key}", array(
			'label'   => $label,
			'section' => 'bdp_settings',
			'type'    => 'text',
		) );
	}
}
add_action( 'customize_register', 'bdp_scouting_customize_register' );

/**
 * Read a bdp_contact_* theme mod.
 *
 * @param string $key Field key without the bdp_contact_ prefix.
 * @return string
 */
function bdp_scouting_contact( $key ) {
	return (string) get_theme_mod( "bdp_contact_{$key}", '' );
}
