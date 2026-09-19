<?php

use PHPUnit\Framework\TestCase;

/**
 * Shared base test case: wires up Brain Monkey (which provides real
 * add_action/add_filter/do_action/apply_filters implementations, so the
 * theme's inc/*.php files can be required without a full WordPress
 * install) and stubs the handful of formatting/i18n functions the theme
 * calls directly.
 */
abstract class BdpTestCase extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Brain\Monkey\setUp();

		Brain\Monkey\Functions\stubs(
			array(
				'__'                => static fn( $text ) => $text,
				'esc_html__'        => static fn( $text ) => $text,
				'esc_attr__'        => static fn( $text ) => $text,
				'esc_html'          => static fn( $text ) => htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' ),
				'esc_attr'          => static fn( $text ) => htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' ),
				'sanitize_text_field' => static fn( $text ) => trim( (string) $text ),
				'wp_json_encode'    => static fn( $data ) => json_encode( $data ),
			)
		);
	}

	protected function tearDown(): void {
		Brain\Monkey\tearDown();
		parent::tearDown();
	}
}
