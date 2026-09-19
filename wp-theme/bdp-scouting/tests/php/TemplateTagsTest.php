<?php
declare( strict_types=1 );

namespace BdPScouting\Tests;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;

use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Tests bdp_scouting_inline_svg(), bdp_scouting_uri_host() (inc/template-tags.php)
 * and bdp_scouting_infotype_label() (inc/setup.php).
 */
final class TemplateTagsTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
		Functions\when( 'esc_attr' )->returnArg( 1 );
		Functions\when( '__' )->returnArg( 1 );
		Functions\when( 'wp_parse_url' )->alias( 'parse_url' );
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	public function test_inline_svg_returns_empty_string_for_missing_file(): void {
		self::assertSame( '', \bdp_scouting_inline_svg( 'does/not/exist.svg' ) );
	}

	public function test_inline_svg_injects_attributes_into_the_root_element(): void {
		$svg = \bdp_scouting_inline_svg( 'brand/bundeszeichen_inline.svg', array(
			'class'  => 'h-14',
			'width'  => '208',
			'height' => '56',
		) );

		self::assertStringStartsWith( '<svg', $svg );
		self::assertStringContainsString( 'class="h-14"', $svg );
		self::assertStringContainsString( 'width="208"', $svg );
		self::assertStringContainsString( 'height="56"', $svg );
		self::assertStringContainsString( 'aria-hidden="false"', $svg );
	}

	public function test_inline_svg_marks_decorative_icons_as_aria_hidden(): void {
		$svg = \bdp_scouting_inline_svg( 'menu.svg', array( 'aria-hidden' => true ) );

		self::assertStringContainsString( 'aria-hidden="true"', $svg );
	}

	public function test_uri_host_extracts_the_host(): void {
		self::assertSame( 'www.pfadfinden.de', \bdp_scouting_uri_host( 'https://www.pfadfinden.de/ueber-uns' ) );
	}

	public function test_uri_host_returns_empty_string_for_invalid_input(): void {
		self::assertSame( '', \bdp_scouting_uri_host( '' ) );
	}

	#[DataProvider( 'infotypeProvider' )]
	public function test_infotype_label( string $infotype, string $expected ): void {
		self::assertSame( $expected, \bdp_scouting_infotype_label( $infotype ) );
	}

	public static function infotypeProvider(): array {
		return array(
			'stamm default'     => array( 'stamm', 'Stamm' ),
			'unknown falls back' => array( 'does-not-exist', 'Stamm' ),
			'lv'                 => array( 'lv', 'Landesverband' ),
			'project'            => array( 'project', 'Projekt' ),
			'service'            => array( 'service', 'Service' ),
		);
	}
}
