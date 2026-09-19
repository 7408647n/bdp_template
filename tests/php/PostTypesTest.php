<?php

require_once __DIR__ . '/BdpTestCase.php';

use Brain\Monkey\Functions;

/**
 * @covers ::bdp_register_event_post_type
 * @covers ::bdp_register_group_post_type
 * @covers ::bdp_get_event_fields
 * @covers ::bdp_get_group_fields
 */
final class PostTypesTest extends BdpTestCase {

	protected function setUp(): void {
		parent::setUp();
		require_once dirname( __DIR__, 2 ) . '/inc/post-types.php';
	}

	public function test_bdp_register_event_post_type_registers_cpt_and_meta(): void {
		Functions\expect( 'register_post_type' )
			->once()
			->with( 'bdp_event', Mockery::type( 'array' ) );

		Functions\expect( 'register_post_meta' )
			->times( 6 )
			->with( 'bdp_event', Mockery::type( 'string' ), Mockery::type( 'array' ) );

		Functions\when( '__' )->returnArg( 1 );

		bdp_register_event_post_type();

		$this->addToAssertionCount(1);
	}

	public function test_bdp_register_group_post_type_registers_cpt_and_meta(): void {
		Functions\expect( 'register_post_type' )
			->once()
			->with( 'bdp_group', Mockery::type( 'array' ) );

		Functions\expect( 'register_post_meta' )
			->times( 10 )
			->with( 'bdp_group', Mockery::type( 'string' ), Mockery::type( 'array' ) );

		Functions\when( '__' )->returnArg( 1 );

		bdp_register_group_post_type();

		$this->addToAssertionCount(1);
	}

	public function test_bdp_get_event_fields_reads_all_meta_keys(): void {
		Functions\when( 'get_post_meta' )->alias(
			static function ( $post_id, $key ) {
				$values = array(
					'bdp_event_start'     => '2026-05-01T10:00:00',
					'bdp_event_end'       => '2026-05-01T12:00:00',
					'bdp_event_all_day'   => '',
					'bdp_event_location'  => 'Wiese',
					'bdp_event_canceled'  => '1',
					'bdp_event_organizer' => 'Stamm Foo',
				);
				return $values[ $key ] ?? '';
			}
		);

		$fields = bdp_get_event_fields( 42 );

		$this->assertSame( '2026-05-01T10:00:00', $fields['start'] );
		$this->assertSame( '2026-05-01T12:00:00', $fields['end'] );
		$this->assertFalse( $fields['all_day'] );
		$this->assertSame( 'Wiese', $fields['location'] );
		$this->assertTrue( $fields['canceled'] );
		$this->assertSame( 'Stamm Foo', $fields['organizer'] );
	}

	public function test_bdp_get_group_fields_reads_all_meta_keys(): void {
		Functions\when( 'get_post_meta' )->alias(
			static function ( $post_id, $key ) {
				$values = array(
					'bdp_group_street' => 'Musterstr. 1',
					'bdp_group_zip'    => '12345',
					'bdp_group_city'   => 'Musterstadt',
					'bdp_group_email'  => 'info@example.org',
				);
				return $values[ $key ] ?? '';
			}
		);

		$fields = bdp_get_group_fields( 7 );

		$this->assertSame( 'Musterstr. 1', $fields['street'] );
		$this->assertSame( '12345', $fields['zip'] );
		$this->assertSame( 'Musterstadt', $fields['city'] );
		$this->assertSame( 'info@example.org', $fields['email'] );
	}

	public function test_bdp_get_group_fields_accepts_a_post_object(): void {
		$post = (object) array( 'ID' => 99 );

		Functions\expect( 'get_post_meta' )
			->with( 99, Mockery::type( 'string' ), true )
			->times( 10 )
			->andReturn( '' );

		bdp_get_group_fields( $post );

		$this->addToAssertionCount(1);
	}
}
