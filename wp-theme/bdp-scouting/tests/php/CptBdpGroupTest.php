<?php
declare( strict_types=1 );

namespace BdPScouting\Tests;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;

/**
 * Tests bdp_scouting_group_meta_fields() and bdp_scouting_save_group_meta()
 * from inc/cpt-bdp-group.php.
 */
final class CptBdpGroupTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	public function test_meta_fields_cover_the_ported_tt_address_columns(): void {
		$fields = \bdp_scouting_group_meta_fields();

		$expected = array(
			'bdp_group_position',
			'bdp_group_street',
			'bdp_group_zip',
			'bdp_group_city',
			'bdp_group_phone',
			'bdp_group_mobile',
			'bdp_group_fax',
			'bdp_group_email',
			'bdp_group_website',
			'bdp_group_latitude',
			'bdp_group_longitude',
		);

		self::assertSame( $expected, array_keys( $fields ) );

		foreach ( $fields as $key => $args ) {
			self::assertSame( 'string', $args['type'], "$key should be a string field" );
			self::assertTrue( $args['single'], "$key should be a single-value field" );
		}
	}

	public function test_save_meta_is_skipped_without_a_valid_nonce(): void {
		Functions\expect( 'wp_verify_nonce' )->andReturn( false );
		Functions\expect( 'update_post_meta' )->never();

		$_POST['bdp_group_nonce']   = 'bad-nonce';
		$_POST['bdp_group_position'] = 'Sippenführung';

		\bdp_scouting_save_group_meta( 42 );

		unset( $_POST['bdp_group_nonce'], $_POST['bdp_group_position'] );
		self::assertTrue( true, 'update_post_meta was expected never to run; verified by Mockery in tearDown().' );
	}

	public function test_save_meta_is_skipped_without_edit_capability(): void {
		Functions\when( 'wp_verify_nonce' )->justReturn( true );
		Functions\when( 'current_user_can' )->justReturn( false );
		Functions\expect( 'update_post_meta' )->never();

		$_POST['bdp_group_nonce']    = 'ok';
		$_POST['bdp_group_position'] = 'Sippenführung';

		\bdp_scouting_save_group_meta( 42 );

		unset( $_POST['bdp_group_nonce'], $_POST['bdp_group_position'] );
		self::assertTrue( true, 'update_post_meta was expected never to run; verified by Mockery in tearDown().' );
	}

	public function test_save_meta_persists_known_fields(): void {
		Functions\when( 'wp_verify_nonce' )->justReturn( true );
		Functions\when( 'current_user_can' )->justReturn( true );
		Functions\when( 'wp_unslash' )->returnArg();
		Functions\when( 'sanitize_text_field' )->returnArg();

		Functions\expect( 'update_post_meta' )
			->once()
			->with( 42, 'bdp_group_position', 'Sippenführung' );

		$_POST['bdp_group_nonce']    = 'ok';
		$_POST['bdp_group_position'] = 'Sippenführung';

		\bdp_scouting_save_group_meta( 42 );

		unset( $_POST['bdp_group_nonce'], $_POST['bdp_group_position'] );
		self::assertTrue( true, 'update_post_meta call verified by Mockery in tearDown().' );
	}
}
