<?php
/**
 * Native Instagram feed.
 *
 * Replaces Resources/Private/InstagramBusiness/Templates/Post/List.html.
 * The TYPO3 extension had no OAuth/business logic of its own (it only
 * rendered whatever InstagramBusiness delivered), so this is a from-scratch,
 * minimal implementation: a Settings API field for the access token, an
 * `wp_remote_get` call against the Instagram Graph API, and a WordPress
 * transient cache so the feed page isn't hit on every request.
 *
 * A site that would rather not manage a token can install the free
 * "Instagram Feed by Smash Balloon" plugin instead — see README.md.
 *
 * @package BdP_Scouting
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const BDP_INSTAGRAM_OPTION_GROUP = 'bdp_scouting_instagram';
const BDP_INSTAGRAM_OPTION_NAME  = 'bdp_scouting_instagram_token';
const BDP_INSTAGRAM_TRANSIENT    = 'bdp_scouting_instagram_feed';
const BDP_INSTAGRAM_API_BASE     = 'https://graph.instagram.com/me/media';

/**
 * Register the Settings API option holding the Instagram Graph API access token.
 */
function bdp_scouting_instagram_register_settings() {
	register_setting( BDP_INSTAGRAM_OPTION_GROUP, BDP_INSTAGRAM_OPTION_NAME, array(
		'type'              => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => '',
	) );

	add_settings_section(
		'bdp_scouting_instagram_section',
		__( 'Instagram Feed', 'bdp-scouting' ),
		function () {
			echo '<p>' . esc_html__( 'Enter a long-lived Instagram Graph API access token to show the feed on this site. Leave empty to disable the native feed (you can use the "Instagram Feed by Smash Balloon" plugin instead).', 'bdp-scouting' ) . '</p>';
		},
		'general'
	);

	add_settings_field(
		BDP_INSTAGRAM_OPTION_NAME,
		__( 'Instagram Access Token', 'bdp-scouting' ),
		function () {
			$value = get_option( BDP_INSTAGRAM_OPTION_NAME, '' );
			printf(
				'<input type="text" class="regular-text" name="%1$s" value="%2$s">',
				esc_attr( BDP_INSTAGRAM_OPTION_NAME ),
				esc_attr( $value )
			);
		},
		'general',
		'bdp_scouting_instagram_section'
	);
}
add_action( 'admin_init', 'bdp_scouting_instagram_register_settings' );

/**
 * Whether a token is configured and the native feed should render.
 *
 * @return bool
 */
function bdp_scouting_instagram_feed_is_active() {
	return '' !== trim( (string) get_option( BDP_INSTAGRAM_OPTION_NAME, '' ) );
}

/**
 * Fetch (and cache for one hour) the latest Instagram media items.
 *
 * @param int $limit Maximum number of posts to return.
 * @return array<int,array<string,mixed>> Normalized post list, empty on error or when disabled.
 */
function bdp_scouting_get_instagram_feed( $limit = 12 ) {
	if ( ! bdp_scouting_instagram_feed_is_active() ) {
		return array();
	}

	$cached = get_transient( BDP_INSTAGRAM_TRANSIENT );
	if ( is_array( $cached ) ) {
		return array_slice( $cached, 0, $limit );
	}

	$token = get_option( BDP_INSTAGRAM_OPTION_NAME, '' );
	$url   = add_query_arg(
		array(
			'fields'       => 'id,caption,media_type,media_url,thumbnail_url,permalink,timestamp',
			'access_token' => $token,
			'limit'        => 25,
		),
		BDP_INSTAGRAM_API_BASE
	);

	$response = wp_remote_get( $url, array( 'timeout' => 8 ) );

	if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
		// Cache the failure briefly too, so a broken token doesn't hammer the API.
		set_transient( BDP_INSTAGRAM_TRANSIENT, array(), 5 * MINUTE_IN_SECONDS );
		return array();
	}

	$body = json_decode( wp_remote_retrieve_body( $response ), true );
	$items = is_array( $body['data'] ?? null ) ? $body['data'] : array();

	$normalized = array_map( 'bdp_scouting_normalize_instagram_item', $items );

	set_transient( BDP_INSTAGRAM_TRANSIENT, $normalized, HOUR_IN_SECONDS );

	return array_slice( $normalized, 0, $limit );
}

/**
 * Normalize one Graph API media item into the shape used by the
 * template-parts/instagram/feed.php partial.
 *
 * @param array<string,mixed> $item Raw Graph API item.
 * @return array<string,mixed>
 */
function bdp_scouting_normalize_instagram_item( $item ) {
	return array(
		'id'        => $item['id'] ?? '',
		'caption'   => $item['caption'] ?? '',
		'type'      => $item['media_type'] ?? 'IMAGE',
		'image_url' => 'VIDEO' === ( $item['media_type'] ?? '' )
			? ( $item['thumbnail_url'] ?? ( $item['media_url'] ?? '' ) )
			: ( $item['media_url'] ?? '' ),
		'video_url' => 'VIDEO' === ( $item['media_type'] ?? '' ) ? ( $item['media_url'] ?? '' ) : '',
		'permalink' => $item['permalink'] ?? '',
		'timestamp' => $item['timestamp'] ?? '',
	);
}
