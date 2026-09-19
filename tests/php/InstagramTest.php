<?php

require_once __DIR__ . '/BdpTestCase.php';

use Brain\Monkey\Functions;

/**
 * @covers ::bdp_instagram_get_access_token
 * @covers ::bdp_instagram_get_feed
 */
final class InstagramTest extends BdpTestCase {

	protected function setUp(): void {
		parent::setUp();
		require_once dirname( __DIR__, 2 ) . '/inc/instagram.php';
	}

	public function test_get_access_token_reads_the_option(): void {
		Functions\expect( 'get_option' )
			->once()
			->with( BDP_INSTAGRAM_TOKEN_OPTION, '' )
			->andReturn( 'a-token' );

		$this->assertSame( 'a-token', bdp_instagram_get_access_token() );
	}

	public function test_get_feed_returns_cached_value_when_transient_is_set(): void {
		$cached = array( array( 'id' => '1' ) );

		Functions\expect( 'get_transient' )
			->once()
			->with( BDP_INSTAGRAM_TRANSIENT )
			->andReturn( $cached );

		$this->assertSame( $cached, bdp_instagram_get_feed() );
	}

	public function test_get_feed_returns_empty_array_without_a_token(): void {
		Functions\expect( 'get_transient' )->once()->andReturn( false );
		Functions\expect( 'get_option' )->once()->andReturn( '' );

		$this->assertSame( array(), bdp_instagram_get_feed() );
	}

	public function test_get_feed_caches_empty_result_on_http_error(): void {
		Functions\expect( 'get_transient' )->once()->andReturn( false );
		Functions\expect( 'get_option' )->once()->andReturn( 'a-token' );
		Functions\when( 'add_query_arg' )->alias(
			static function ( $args, $url ) {
				return $url . '?' . http_build_query( $args );
			}
		);
		Functions\expect( 'wp_remote_get' )->once()->andReturn( new WP_Error() );
		Functions\when( 'is_wp_error' )->justReturn( true );

		Functions\expect( 'set_transient' )
			->once()
			->with( BDP_INSTAGRAM_TRANSIENT, array(), Mockery::type( 'int' ) );

		$this->assertSame( array(), bdp_instagram_get_feed() );
	}

	public function test_get_feed_normalizes_successful_response(): void {
		Functions\expect( 'get_transient' )->once()->andReturn( false );
		Functions\expect( 'get_option' )->once()->andReturn( 'a-token' );
		Functions\when( 'add_query_arg' )->alias(
			static function ( $args, $url ) {
				return $url . '?' . http_build_query( $args );
			}
		);

		$api_response = array(
			'data' => array(
				array(
					'id'             => '123',
					'media_type'     => 'IMAGE',
					'caption'        => 'Hello',
					'permalink'      => 'https://instagram.com/p/123',
					'media_url'      => 'https://example.org/img.jpg',
					'thumbnail_url'  => 'https://example.org/thumb.jpg',
				),
			),
		);

		Functions\when( 'is_wp_error' )->justReturn( false );
		Functions\expect( 'wp_remote_get' )->once()->andReturn( array( 'response' => array( 'code' => 200 ), 'body' => json_encode( $api_response ) ) );
		Functions\when( 'wp_remote_retrieve_response_code' )->justReturn( 200 );
		Functions\when( 'wp_remote_retrieve_body' )->alias(
			static fn( $response ) => $response['body']
		);

		Functions\expect( 'set_transient' )
			->once()
			->with( BDP_INSTAGRAM_TRANSIENT, Mockery::type( 'array' ), BDP_INSTAGRAM_CACHE_TTL );

		$feed = bdp_instagram_get_feed();

		$this->assertCount( 1, $feed );
		$this->assertSame( '123', $feed[0]['id'] );
		$this->assertSame( 'IMAGE', $feed[0]['mediaType'] );
		$this->assertSame( 'https://instagram.com/p/123', $feed[0]['link'] );
		$this->assertSame( 'https://example.org/img.jpg', $feed[0]['mediaUrl'] );
	}
}
