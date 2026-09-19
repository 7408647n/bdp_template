<?php
declare( strict_types=1 );

namespace BdPScouting\Tests;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;

/**
 * Tests bdp_scouting_normalize_instagram_item(), bdp_scouting_instagram_feed_is_active()
 * and bdp_scouting_get_instagram_feed() from inc/instagram.php.
 */
final class InstagramTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	public function test_normalize_image_item(): void {
		$normalized = \bdp_scouting_normalize_instagram_item( array(
			'id'         => '123',
			'caption'    => 'Hello',
			'media_type' => 'IMAGE',
			'media_url'  => 'https://example.test/a.jpg',
			'permalink'  => 'https://instagram.com/p/123',
			'timestamp'  => '2026-01-01T00:00:00+0000',
		) );

		self::assertSame( '123', $normalized['id'] );
		self::assertSame( 'IMAGE', $normalized['type'] );
		self::assertSame( 'https://example.test/a.jpg', $normalized['image_url'] );
		self::assertSame( '', $normalized['video_url'] );
	}

	public function test_normalize_video_item_uses_thumbnail_for_image_url(): void {
		$normalized = \bdp_scouting_normalize_instagram_item( array(
			'id'             => '456',
			'media_type'     => 'VIDEO',
			'media_url'      => 'https://example.test/a.mp4',
			'thumbnail_url'  => 'https://example.test/a-thumb.jpg',
			'permalink'      => 'https://instagram.com/p/456',
		) );

		self::assertSame( 'https://example.test/a-thumb.jpg', $normalized['image_url'] );
		self::assertSame( 'https://example.test/a.mp4', $normalized['video_url'] );
	}

	public function test_normalize_item_defaults_missing_fields(): void {
		$normalized = \bdp_scouting_normalize_instagram_item( array() );

		self::assertSame( '', $normalized['id'] );
		self::assertSame( 'IMAGE', $normalized['type'] );
		self::assertSame( '', $normalized['image_url'] );
	}

	public function test_feed_is_inactive_without_a_token(): void {
		Functions\expect( 'get_option' )
			->once()
			->with( \BDP_INSTAGRAM_OPTION_NAME, '' )
			->andReturn( '' );

		self::assertFalse( \bdp_scouting_instagram_feed_is_active() );
	}

	public function test_feed_is_active_with_a_token(): void {
		Functions\expect( 'get_option' )
			->once()
			->with( \BDP_INSTAGRAM_OPTION_NAME, '' )
			->andReturn( 'a-token' );

		self::assertTrue( \bdp_scouting_instagram_feed_is_active() );
	}

	public function test_get_feed_returns_empty_array_when_inactive(): void {
		Functions\expect( 'get_option' )
			->once()
			->with( \BDP_INSTAGRAM_OPTION_NAME, '' )
			->andReturn( '' );

		self::assertSame( array(), \bdp_scouting_get_instagram_feed() );
	}

	public function test_get_feed_returns_cached_items_without_a_remote_call(): void {
		Functions\when( 'get_option' )->justReturn( 'a-token' );
		Functions\expect( 'get_transient' )
			->once()
			->with( \BDP_INSTAGRAM_TRANSIENT )
			->andReturn( array(
				array( 'id' => '1' ),
				array( 'id' => '2' ),
			) );
		Functions\expect( 'wp_remote_get' )->never();

		$feed = \bdp_scouting_get_instagram_feed( 1 );

		self::assertCount( 1, $feed );
		self::assertSame( '1', $feed[0]['id'] );
	}
}
