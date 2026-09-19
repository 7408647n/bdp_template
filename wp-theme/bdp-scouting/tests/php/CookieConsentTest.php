<?php
declare( strict_types=1 );

namespace BdPScouting\Tests;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;

/**
 * Tests bdp_scouting_cookie_categories() from inc/cookie-consent.php.
 */
final class CookieConsentTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
		Functions\when( '__' )->returnArg( 1 );
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	public function test_necessary_category_is_locked(): void {
		$categories = \bdp_scouting_cookie_categories();

		self::assertArrayHasKey( 'necessary', $categories );
		self::assertTrue( $categories['necessary']['locked'] );
	}

	public function test_statistics_and_marketing_are_unlocked(): void {
		$categories = \bdp_scouting_cookie_categories();

		self::assertFalse( $categories['statistics']['locked'] );
		self::assertFalse( $categories['marketing']['locked'] );
	}

	public function test_every_category_has_a_label_and_description(): void {
		foreach ( \bdp_scouting_cookie_categories() as $key => $category ) {
			self::assertNotEmpty( $category['label'], "$key should have a label" );
			self::assertNotEmpty( $category['description'], "$key should have a description" );
		}
	}
}
