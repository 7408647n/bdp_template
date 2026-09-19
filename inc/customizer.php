<?php
/**
 * Customizer settings mirroring the TypoScript constants the sitepackage
 * exposed under `settings.contact.*` / `settings.social.*`
 * (Partials/Page/Footer.html and FooterSocial.html).
 *
 * @package BdpTemplate
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Registers the "Contact & Social" Customizer panel.
 */
function bdp_customize_register( WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_section(
		'bdp_contact_social',
		array(
			'title'    => __( 'Contact & Social', 'bdp-template' ),
			'priority' => 160,
		)
	);

	$text_settings = array(
		'bdp_contact_name'    => __( 'Contact name', 'bdp-template' ),
		'bdp_contact_address' => __( 'Address', 'bdp-template' ),
		'bdp_contact_phone'   => __( 'Phone', 'bdp-template' ),
		'bdp_contact_fax'     => __( 'Fax', 'bdp-template' ),
		'bdp_contact_email'   => __( 'E-Mail', 'bdp-template' ),
		'bdp_contact_website' => __( 'Website', 'bdp-template' ),
		'bdp_social_instagram'=> __( 'Instagram URL', 'bdp-template' ),
		'bdp_social_facebook' => __( 'Facebook URL', 'bdp-template' ),
		'bdp_social_youtube'  => __( 'YouTube URL', 'bdp-template' ),
	);

	foreach ( $text_settings as $id => $label ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $label,
				'section' => 'bdp_contact_social',
				'type'    => 'text',
			)
		);
	}
}
add_action( 'customize_register', 'bdp_customize_register' );
